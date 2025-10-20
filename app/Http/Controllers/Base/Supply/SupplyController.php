<?php

namespace App\Http\Controllers\Base\Supply;

use App\Enums\{
    CrudAction,
    ItemStatuses,
    SupplyStatuses,
    StoreStatuses,
};
use App\Enums\Auth\RoleAliases;
use App\Http\Controllers\Common\GenericCrudProvider;
use App\Http\Requests\Base\Supply\{
    PostRequest,
    PutRequest,
    SearchRequest,
};
use App\Jobs\Marketplace\RefreshInventoryItem;
use App\Models\Base\{
    Designer,
    Item,
    Product,
    Store,
    SuppliedItem,
    Supply,
    SupplyTransfer,
};
use App\Models\User;
use App\Services\Marketplace\RefreshProductService;
use App\Services\SupplyCreationService;
use App\Traits\Base\ShortenedSets;
use App\Traits\Base\Supply\{
    CreatesReport,
    ShowsBoards,
};
use App\Traits\Helpers\HandlerFilesTrait;
use Exception;
use Illuminate\Database\Eloquent\{
    Builder,
    Model,
    ModelNotFoundException,
};
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\{
    Auth,
    Session,
};
use Inertia\Inertia;
use Nette\NotImplementedException;
use Symfony\Component\HttpFoundation\Response;

class SupplyController extends GenericCrudProvider
{

    use HandlerFilesTrait, CreatesReport, ShowsBoards, ShortenedSets;

    protected string $modelClass = Supply::class;

    protected string $indexView = 'base.supply.index';
    protected string $showView = 'base.supply.show';
    protected string $createView = '';
    protected string $editView = 'base.supply.edit';

    protected ?string $searchField = 'code';

    protected SupplyCreationService $supplyCreationService;

    public function __construct(SupplyCreationService $supplyCreationService)
    {
        parent::__construct();

        $this->supplyCreationService = $supplyCreationService;
    }

    protected function rules(): array
    {
        return [
            CrudAction::CREATE->value => (new PostRequest())->rules(),
            CrudAction::UPDATE->value => (new PutRequest())->rules(),
        ];
    }

    protected function createView()
    {
        throw new NotImplementedException('Use fullCreateView for this operation');
    }

    protected function beforeReadAll(Builder $query, Request $request): Builder
    {
        return $query->latest('created_at');
    }

    protected function fullCreateView(Request $request, $productId = null)
    {
        $values = collect([
            $productId,
            ...$this->decodeShortenArray($request->d ?? ''),
        ])->filter()->values();

        return $this->processRequest(function () use ($values) {
            $products = Product::findOrFail($values);

            $designers = Designer::select('id', 'name')->orderBy('name')->get();
            $stores = [];
            $items = [];
            $common = [];

            if ($products->isNotEmpty()) {
                $stores = $this->getStoresData(Store::getByStatus(StoreStatuses::ACTIVE));

                $items = $this->filterItemsBy((object) ['ids' => $products->pluck('id')->toArray()], false)
                    ->mapToGroups(fn($i) => [$i['support']['color_id'] => $i])
                    ->map(fn($items) => [
                        'id' => $items->pluck('support.product_variant_id')->unique()->join('-'),
                        'name' => $items->value('support.color_name'),
                        'image_src' => $items->value('support.image_src'),
                        'items' => $this->getItemsByStore($items, $stores),
                    ])
                    ->values();

                $common = [
                    'name' => $products->pluck('name')->unique()->join(', '),
                ];
            }

            return Inertia::render('Supply/Create', compact('stores', 'items', 'designers', 'common'));
        }, 'Record not found', 'Error retrieving record');
    }
    
    public function searchProductAction(SearchRequest $request)
    {
        return $this->processRequest(function () use ($request) {
            $products = $this->searchProducts($request->validated());

            $values = $this->encodeShortenArray($products->pluck('id')->toArray());

            return redirect()
                ->route('base.supply.create.view', ['d' => $values])
                ->with(
                    $products->isNotEmpty() ?
                        ['success' => 'Producto ' . $request->name . ' encontrado.'] :
                        ['error' => 'No se encuentra el producto ' . $request->name]
                );
        }, 'Record not found', 'Error searching product');
    }

    public function showByStore($storeField)
    {
        try {
            $store = Store::findOrFail($storeField);
            $user = Auth::user();

            if ($user->hasAnyRole(RoleAliases::INVENTORY) &&
                $user->employeeDetail?->store_id !== $store->id) {
                return $this->makeResponse([
                    'message' => 'No tienes permiso para acceder a esta tienda.',
                    'success' => false,
                    'status' => Response::HTTP_FORBIDDEN,
                ]);
            }

                $latestOpenPerItem = function ($q) {
                    $q->where('delivered', false)
                    ->whereNotExists(function ($sub) {
                        $sub->select(DB::raw(1))
                            ->from('supplied_items as si2')
                            ->whereColumn('si2.item_id', 'supplied_items.item_id')
                            ->where('si2.delivered', false)
                            ->whereColumn('si2.id', '>', 'supplied_items.id');
                    });
                };

                $supplyTransfers = SupplyTransfer::query()
                    ->where('destination_id', $store->id)
                    ->whereHas('suppliedItems', $latestOpenPerItem)
                    ->with(['suppliedItems' => $latestOpenPerItem, 'origin'])
                    ->get()
                    ->filter(fn ($st) => $st->suppliedItems->isNotEmpty())
                    ->mapToGroups(fn ($st) => [
                        $st->origin_id => $st->suppliedItems
                    ])
                    ->map(fn ($items, $originId) => (object) [
                        'origin' => Store::findOrFail($originId),
                        'suppliedItems' => $items->flatten(1),
                    ]);


            return $this->makeResponse([
                'view' => 'base.supply.destination',
                'data' => ['data' => $supplyTransfers, 'store' => $store],
                'message' => 'Record retrieved successfully',
                'status' => Response::HTTP_OK,
            ]);
        } catch (ModelNotFoundException $exception) {
            return $this->makeResponse([
                'message' => 'Record not found',
                'success' => false,
                'status' => Response::HTTP_NOT_FOUND,
            ]);
        } catch (\Exception $exception) {
            return $this->makeResponse([
                'message' => 'Error retrieving record',
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'exception' => $exception,
            ]);
        }
    }

    protected function pushEditView(Model $model)
    {
        $users = User::all();

        return compact('users');
    }

    protected function beforeCreate(array &$validatedData, Request $request): ?array
    {
        $request->validate([
            'code' => 'required|string|unique:supplies,code',
        ]);

        return [
            'sender_id' => Auth::id(),
            'shipping_date' => now(),
            'status' => SupplyStatuses::PENDING->value,
        ];
    }

    protected function createRecord(Request $request)
    {
        $validatedData = $request->validate($this->rules()[CrudAction::CREATE->value]);

        $preCreateResponse = $this->beforeCreate($validatedData, $request);
        if ($preCreateResponse) {
            $validatedData = array_merge($validatedData, $preCreateResponse);
        }

        return $this->processRequest(function () use ($validatedData) {
            $supply = $this->supplyCreationService->createSupply($validatedData);
            Session::flash('success', 'Record created successfully');
            return Inertia::location(route($this->showView, $supply->id));
        }, 'Record not found', 'Failed to update record');
    }

    public function printReport($field)
    {
        try {
            $supply = $this->getModel($field);

            $pdf = $this->createReportFile($supply);

            $pdfContent = $pdf->output();

            return response($pdfContent, 200)->header('Content-Type', 'application/pdf');
        } catch (Exception $e) {
            return $this->makeResponse([
                'message' => 'Error generating report',
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'exception' => $e,
            ]);
        }
    }
    
    protected function beforeDelete($model)
    {
        foreach ($model->supplyTransfers as $transfer) {
            foreach ($transfer->suppliedItems as $suppliedItem) {
                $item = $suppliedItem->item;
                if ($item) {
                    $item->status = ItemStatuses::AVAILABLE;
                    $item->save();
                }
            }
        }
    }
}
