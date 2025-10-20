<?php

namespace App\Http\Controllers\Base\Item;

use App\Enums\{
    CrudAction,
    ItemConditions,
    ItemIntegrities,
    ItemStatuses,
    StoreStatuses,
};
use App\Http\Controllers\Common\GenericCrudProvider;
use App\Http\Requests\Base\Item\{
    DataRequest,
    FilterRequest,
    PostRequest,
    PutRequest,
};
use App\Jobs\Item\{
    CardsCompleted,
    GenerateCards,
};
use App\Jobs\Marketplace\{
    RefreshInventoryItem,
    RefreshProduct,
    RefreshProductVariant,
};
use App\Models\Base\{
    Invoice,
    Characteristic,
    Color,
    Designer,
    Item,
    PricingScheme,
    Product,
    ProductVariant,
    Size,
    Store,
    Variant,
    Category,
};
use App\Models\Base\EmployeeDetail;
use App\Services\Item\ItemCardService;
use App\Services\Marketplace\RefreshProductService;
use App\Services\Support\{
    JobChainBuilder,
    ZipFiles,
};
use App\Traits\Base\Item\{
    FiltersItems,
    MassUpdate,
};
use App\Traits\Base\ShortenedSets;
use App\Traits\Helpers\Support;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;
use Exception;
use Illuminate\Support\Facades\{
    Auth,
    File,
    DB
};
use InvalidArgumentException;

class ItemController extends GenericCrudProvider
{
    use ShortenedSets, MassUpdate, FiltersItems, Support;

    protected string $modelClass = Item::class;

    protected string $indexView = 'base.item.index';
    protected string $showView = 'base.item.show';
    protected string $createView = 'base.item.create';
    protected string $editView = 'base.item.edit';

    protected ?string $searchField = 'id';

    private RefreshProductService $refreshProductService;
    private ItemCardService $itemCardService;

    public function __construct()
    {
        parent::__construct();

        $this->refreshProductService = new RefreshProductService();
        $this->itemCardService = new ItemCardService();
    }

    protected function rules(): array
    {
        return [
            CrudAction::CREATE->value => (new PostRequest())->rules(),
            CrudAction::UPDATE->value => (new PutRequest())->rules(),
        ];
    }

    protected function beforeCreate(array &$validatedData, Request $request): ?array
    {
        $request->validate([
            'barcode' => 'nullable|unique:items,barcode',
            'serial_number' => 'nullable|unique:items,serial_number',
        ]);

        return [
            'serial_number' => $request->serial_number ?: Str::uuid(),
            'product_variant_id' => $this->findProductVariant($request->product_id, $request->variant_id)->id,
        ];
    }

    protected function afterCreate(Model $model, Request $request): ?array
    {
        $colorId = Variant::findOrFail($request->variant_id)->color_id;

        $this->refreshProductService->chainRefreshFromColors($model->product->slug, [$colorId], [
            RefreshProduct::class,
            RefreshProductVariant::class,
            RefreshInventoryItem::class,
        ])->dispatch();

        return null;
    }

    protected function beforeUpdate(array &$validatedData, Model $model, Request $request): ?array
    {
        $request->validate([
            'barcode' => 'nullable|unique:items,barcode,' . $model->id,
            'serial_number' => 'nullable|unique:items,serial_number,' . $model->id,
        ]);
        $size = Size::find($request->size_id);
        $color = Color::find($request->color_id);

        $variant = Variant::firstOrCreate([
            'size_id' => $size->id,
            'color_id' => $color->id,
        ], [
            'code' => Support::generateVariantCode($size->slug, $color->slug),
            'status' => 1,
        ]);

        return [
            'product_variant_id' => $this->findProductVariant($request->product_id, $variant->id)->id,
        ];
    }

    protected function afterUpdate(Model $model, Request $request): ?array
    {
        $colorId = $model->variant->color_id;

        $this->refreshProductService->chainRefreshFromColors($model->product->slug, [$colorId], [
            ...($model->wasChanged(['condition', 'status']) ? [
                RefreshProduct::class,
                RefreshProductVariant::class,
            ] : []),
            RefreshInventoryItem::class,
        ])->dispatch();

        return null;
    }

    protected function pushEditView(Model $model)
    {
        $products = Product::all();
        $stores = Store::getByStatus(StoreStatuses::ACTIVE);
        $variants = Variant::all();
        $invoices = Invoice::all();
        $sizes = Size::all();
        $colors = Color::active()->onlyChildren()->get()->keyBy('id');

        $colorId = $model->variant?->color_id;
        $sizeId = $model->variant?->size_id;

        return compact('products', 'stores', 'variants', 'invoices', 'sizes', 'colors', 'colorId', 'sizeId');
    }

    protected function pushCreateView()
    {
        $products = Product::all();
        $stores = Store::getByStatus(StoreStatuses::ACTIVE);
        $variants = Variant::all();
        $invoices = Invoice::all();

        return compact('products', 'stores', 'variants', 'invoices');
    }

    public function massUpdateConditionStatus(\App\Http\Requests\Base\Item\Mass\ConditionStatusRequest $request)
    {
        [$ids, $total] = $this->resolveMassSelection($request);

        if ($ids->isEmpty()) {
            return back()->with('error', 'No se encontraron artículos para actualizar.');
        }

        if ((int)$request->condition === ItemConditions::BAZAAR->value) {
            $warehouseId = Store::where('name', 'Almacén')->value('id');
            if (!$warehouseId) {
                return back()->with('error', 'No existe la sucursal "Almacén".');
            }

            $storeIds = Item::whereIn('id', $ids)->pluck('store_id')->unique();

            $todosEnAlmacen = ($storeIds->count() === 1) && ((int)$storeIds->first() === (int)$warehouseId);

            if (!$todosEnAlmacen) {
                return back()->with('error', 'Para asignar "Bazar" en edición masiva, todos los artículos deben estar en "Almacén".');
            }

            Item::whereIn('id', $ids)->update([
                'condition' => $request->condition,
                'status'    => $request->status,
            ]);

            return back()->with('success', "Se actualizaron {$total} artículos (todos en Almacén).");
        }

        Item::whereIn('id', $ids)->update([
            'condition' => $request->condition,
            'status'    => $request->status,
        ]);

        return back()->with('success', "Se actualizaron {$total} artículos.");
    }

    public function canBazarForMass(Request $request)
    {
        [$ids, $total] = $this->resolveMassSelection($request);

        if ($total === 0) {
            return response()->json(['allow_bazar' => false]);
        }

        $warehouseId = Store::where('name', 'Almacén')->value('id');
        if (!$warehouseId) {
            return response()->json(['allow_bazar' => false]);
        }

        $storeIds = Item::whereIn('id', $ids)->pluck('store_id')->unique();
        $allow = ($storeIds->count() === 1) && ((int)$storeIds->first() === (int)$warehouseId);

        return response()->json(['allow_bazar' => $allow]);
    }


    private function resolveMassSelection(\Illuminate\Http\Request $request): array
    {
        $idsFromForm = collect($request->input('items', []))->filter()->unique();
        if ($idsFromForm->isNotEmpty() && !$request->boolean('select_all')) {
            return [$idsFromForm->values(), $idsFromForm->count()];
        }
        if ($request->boolean('select_all')) {
            $filters = (object) ($request->input('filters', []) ?? []);
            $query = Item::query()->with(['product', 'productVariant.variant']);

            if (!empty($filters->name)) {
                $search = trim($filters->name);
                $query->whereHas('product', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('title', 'like', "%{$search}%");
                });
            }
            if (!empty($filters->barcode))     $query->where('barcode', $filters->barcode);
            if (!empty($filters->store))       $query->where('store_id', $filters->store);
            if (!empty($filters->status)) {
                $query->where('status', $filters->status);
            } else {
                $query->whereNotIn('status', [
                    ItemStatuses::ARCHIVED->value,
                    ItemStatuses::DRY_CLEANING->value,
                    ItemStatuses::TAILORING->value,
                    ItemStatuses::RENT->value,
                    ItemStatuses::SOLD->value,
                    ItemStatuses::LOST->value,
                    ItemStatuses::TRAYECT->value,
                    ItemStatuses::TRANSFER->value,
                ]);
            }


            if (!empty($filters->category)) {
                $query->whereHas('product', fn($q) => $q->where('category_id', $filters->category));
            }

            if (!empty($filters->designer)) {
                $query->whereHas('product', fn($q) => $q->where('designer_id', $filters->designer));
            }

            if (!empty($filters->condition)) $query->where('condition', $filters->condition);

            if (!empty($filters->sizes) && is_array($filters->sizes) && count($filters->sizes) > 0) {
                $query->whereHas('productVariant.variant', fn($q) => $q->whereIn('size_id', $filters->sizes));
            }

            if (!empty($filters->colors) && is_array($filters->colors) && count($filters->colors) > 0) {
                $query->whereHas('productVariant.variant', fn($q) => $q->whereIn('color_id', $filters->colors));
            }

            if (!empty($filters->characteristics) && is_array($filters->characteristics) && count($filters->characteristics) > 0) {
                $charIds = $filters->characteristics;
                $query->whereHas('product.characteristics', fn($q) => $q->whereIn('characteristics.id', $charIds));
            }

            $ids = $query->pluck('id')->unique()->values();
            return [$ids, $ids->count()];
        }

        return [collect(), 0];
    }


    public function updateItemData($field, DataRequest $request)
    {
        return $this->processRequest(function () use ($field, $request) {
            $item = Item::with(['itemOrderMarketplaces.orderMarketplace', 'product', 'variant', 'store'])
                ->findOrFail($field);

            if ((int)$request->condition === ItemConditions::BAZAAR->value) {
                $currentStoreName = trim($item->store?->name ?? '');
                if ($currentStoreName !== 'Almacén') {
                    return $this->makeResponse([
                        'message' => 'La condición "Bazar" sólo puede asignarse a artículos ubicados en Almacén.',
                        'success' => false,
                        'status'  => \Symfony\Component\HttpFoundation\Response::HTTP_UNPROCESSABLE_ENTITY,
                    ]);
                }
            }

            $item->update([
                'condition' => $request->condition,
                'status' => $request->status,
                'price_sale' => $request->price_sale,
                'barcode' => $request->barcode,
                'serial_number' => $request->serial_number,
                'details' => $request->details ?? '',
            ]);

            $item->product->update([
                'name' => $request->name,
                'title' => $request->product_title,
                'full_price' => $request->full_price,
                'description' => $request->description,
                'pricing_scheme_id' => $request->pricing_scheme_id,
            ]);

            $orders = $item->itemOrderMarketplaces
                ->whereNotNull('orderMarketplace')
                ->map(function ($itemOrder) {
                    return [
                        'order_code' => $itemOrder->orderMarketplace->code,
                        'date' => $itemOrder->orderMarketplace->created_at->format('Y-m-d H:i:s'),
                        'marketplace_id' => $itemOrder->order_marketplace_id
                    ];
                });

            if ($orders->isEmpty() && $item->itemOrderMarketplaces->isNotEmpty()) {
                logger()->warning('Problemas con órdenes', [
                    'item_id' => $item->id,
                    'orders_found' => $item->itemOrderMarketplaces->count(),
                    'sample_order' => $item->itemOrderMarketplaces->first() ? [
                        'exists' => !is_null($item->itemOrderMarketplaces->first()->orderMarketplace),
                        'code_value' => optional($item->itemOrderMarketplaces->first()->orderMarketplace)->code,
                        'marketplace_id' => $item->itemOrderMarketplaces->first()->order_marketplace_id
                    ] : null
                ]);
            }

            $colorId = $item->variant->color_id;
            $this->refreshProductService->chainRefreshFromColors($item->product->slug, [$colorId], [
                RefreshProductVariant::class,
                RefreshInventoryItem::class,
            ])
                ->when($item->product->wasChanged(), fn($chain) => $chain->prependJob(new RefreshProduct($item->product->slug)))
                ->when($item->product->wasChanged(['full_price', 'pricing_scheme_id']), fn($chain) => $chain->appendJob(new RefreshProductVariant($item->product->slug)))
                ->dispatch();

            return $this->makeResponse([
                'message' => 'Data updated successfully',
                'redirect' => null,
                'orders' => $orders,
                '_debug' => [
                    'orders_count' => $orders->count(),
                    'product_updated' => $item->product->wasChanged()
                ]
            ]);
        }, 'Could not find the requested item', 'Error while updating data');
    }

    public function filterView(FilterRequest $request)
    {
        return $this->processRequest(function () use ($request) {
            $items = Inertia::lazy(function () use ($request) {
                if ($request->values) {
                    return $this->filterItemsById($request->values);
                } else {
                    $perPage = $request->input('perPage', 15);
                    return $this->filterItemsBy((object) $request->all(), $perPage);
                }
            });

            $itemInfo = Inertia::lazy(function () use ($request) {
                return $this->buildItemInfoData(Item::with('productVariant.product')->findOrFail($request->itemId));
            });

            $employees = EmployeeDetail::with(['user:id,name,last_name'])
                ->get()
                ->map(function ($employee) {
                    return [
                        'user_id' => $employee->user->id,
                        'full_name' => $employee->user->full_name,
                        'store_id' => $employee->store_id,
                    ];
                });

            $categories = Category::select('id', 'name')->orderBy('name')->get();
            $stores = Store::select('id', 'name')->orderBy('name')->get();
            $designers = Designer::select('id', 'name')->orderBy('name')->get();
            $sizes = Size::select('id', 'name')->orderBy('name')->get();
            $productName = null;
            if ($request->filled('name')) {
                $productName = trim($request->get('name'));
            } elseif ($request->has('filters')) {
                $filters = is_array($request->get('filters')) ? $request->get('filters') : (array) $request->get('filters');
                if (!empty($filters['name'])) {
                    $productName = trim($filters['name']);
                }
            }

            if ($productName) {
                $productIds = Product::where('name', 'like', "%{$productName}%")
                    ->orWhere('title', 'like', "%{$productName}%")
                    ->pluck('id')
                    ->toArray();

                $usedColorIds = Item::query()
                    ->join('product_variants', 'items.product_variant_id', '=', 'product_variants.id')
                    ->join('variants', 'product_variants.variant_id', '=', 'variants.id')
                    ->whereIn('product_variants.product_id', $productIds)
                    ->pluck('variants.color_id')
                    ->unique()
                    ->toArray();

                $colors = Color::active()
                    ->onlyParents()
                    ->whereHas('children', fn($q) => $q->whereIn('id', $usedColorIds))
                    ->get()
                    ->mapWithKeys(fn($c) => [$c->id => [
                        'parent' => $c,
                        'children' => $c->children->whereIn('id', $usedColorIds)->values(),
                    ]]);
            } else {
                $usedColorIds = Item::query()
                    ->join('product_variants', 'items.product_variant_id', '=', 'product_variants.id')
                    ->join('variants', 'product_variants.variant_id', '=', 'variants.id')
                    ->pluck('variants.color_id')
                    ->unique()
                    ->toArray();

                $colors = Color::active()->onlyParents()->get()->mapWithKeys(fn($c) => [$c->id => [
                    'parent' => $c,
                    'children' => $c->children,
                ]]);
            }
            $pricingSchemes = PricingScheme::with('sku_4', 'sku_8')->get();
            $conditions = ItemConditions::getAllNames();
            $characteristics = Characteristic::onlyParents()->get()->map(fn($c) => [
                'parent' => $c,
                'children' => $c->children,
            ]);
            $integrities = ItemIntegrities::getAllNames();
            $statuses = ItemStatuses::getAllNames();

            return Inertia::render('Item/Filter', compact(
                'items',
                'itemInfo',
                'stores',
                'designers',
                'sizes',
                'colors',
                'characteristics',
                'integrities',
                'statuses',
                'conditions',
                'pricingSchemes',
                'categories',
                'employees',
                'usedColorIds'
            ));
        }, '', 'Error while retrieving filter data');
    }

    protected function buildItemInfoData(Item $item): array
    {
        $item->load([
            'suppliedItems.supplyTransfer.supply',
            'suppliedItems.supplyTransfer.destination',
            'store',
        ]);
        $item->loadMissing([
            'openSuppliedItem.supplyTransfer.destination',
            'openSuppliedItem.supplyTransfer.recipient',
        ]);

        $isInTransit = $item->is_in_transit;

        $transfers = $item->suppliedItems
            ->map(function ($suppliedItem) {
                $transfer = $suppliedItem->supplyTransfer;
                $supply = $transfer->supply ?? null;

                $storeName = optional($transfer->destination)->name ?? 'Desconocido';

                return [
                    'shipping_date' => $supply->shipping_date ?? null,
                    'reception_date' => $transfer->reception_date ?? null,
                    'store_name' => $storeName,
                ];
            })
            ->sortBy(function ($transfer) {
                return $transfer['reception_date'] ?? $transfer['shipping_date'] ?? '9999-99-99';
            })
            ->values();

        $storeFromItem = 'Almacén';

        $alreadyExists = $transfers->contains(function ($t) use ($storeFromItem, $item) {
            return $t['store_name'] === $storeFromItem &&
                isset($t['reception_date']) &&
                \Carbon\Carbon::parse($t['reception_date'])->equalTo($item->created_at);
        });

        if (!$alreadyExists) {
            $transfers->prepend([
                'shipping_date' => null,
                'reception_date' => $item->created_at->toDateString(),
                'store_name' => $storeFromItem,
                'from_created' => true,
            ]);
        }

        $suppliesHistory = [];

        for ($i = 0; $i < $transfers->count(); $i++) {
            $current = $transfers[$i];
            $next = $transfers[$i + 1] ?? null;

            $startDate = $current['reception_date']
                ? \Carbon\Carbon::parse($current['reception_date'])
                : null;

            $endDate = $next
                ? (\Carbon\Carbon::parse($next['shipping_date'] ?? $next['reception_date']))
                : now();

            if ($startDate && $startDate->gt($endDate)) {
                $startDate = clone $endDate;
            }

            $elapsed = $startDate
                ? [
                    'weeks' => floor($startDate->diffInDays($endDate) / 7),
                    'days' => $startDate->diffInDays($endDate) % 7,
                ]
                : null;

            $isCurrent = ($i === $transfers->count() - 1);

            $suppliesHistory[] = [
                'shipping_date' => $isCurrent ? now()->format('d/m/Y') : $endDate->format('d/m/Y'),
                'reception_date' => $startDate ? $startDate->format('d/m/Y') : null,
                'store_name' => $current['store_name'],
                'elapsed' => $elapsed,
                'is_current' => $isCurrent,
            ];
        }
        return [
            'id' => $item->id,
            'name' => $item->product?->name,
            'details' => $item->details,
            'description' => $item->product?->description,
            'variant_color' => $item->productVariant?->variant?->color,
            'variant_size' => $item->productVariant?->variant?->size,
            'product_title' => $item->product?->title,
            'full_price' => $item->product?->full_price,
            'pricing_scheme_id' => $item->product?->pricing_scheme_id,
            'price_sale' => $item->price_sale,
            'barcode' => $item->barcode,
            'serial_number' => $item->serial_number,
            'condition' => $item->condition,
            'status' => $item->status,
            'current_store_name' => optional($item->store)->name,
           'target_store_name'     => $isInTransit ? $item->target_store_name : null,
            'target_recipient_name' => $isInTransit ? $item->target_recipient_name : null,
            'created_at' => $item->created_at->format('d/m/Y'),
            'supplies' => $suppliesHistory,
            'first_image' => $item->productVariant->productImage?->src_image ?? asset('media/misc/image.png'),
            'is_in_transit'         => $isInTransit,
            'importation' => $item->status === ItemStatuses::IMPORTATION->value,
        ];
    }

    public function cardsPrint(Request $request, $field = null)
    {
        try {
            $values = collect([
                $field,
                ...$this->decodeShortenArray($request->data ?? ''),
            ])->filter()->values();

            if ($values->isEmpty()) {
                throw new InvalidArgumentException('At least one value expected');
            }

            $values = Item::query()
                ->whereIn('id', $values)
                ->orWhereIn('barcode', $values)
                ->orderBy('store_id')
                ->pluck('id');

            $token = strtolower(Str::random(8));

            JobChainBuilder::make()
                ->appendJobs(
                    $values->chunk(40)
                        ->map(fn($i) => new GenerateCards($i->toArray(), $token))
                        ->toArray()
                )
                ->appendJob(new CardsCompleted(Auth::id(), $token))
                ->dispatch();

            return $this->makeResponse([
                'message' => 'Se están generando las fichas. Recibirás un correo con el enlace de descarga.',
                'redirect' => null,
            ]);
        } catch (Exception $exception) {
            return $this->makeResponse([
                'message' => 'Error generating card',
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'exception' => $exception,
            ]);
        }
    }

    public function cardsDownload(string $token)
    {
        try {
            $cardsPath = storage_path('app/item_cards/' . $token);
            $zipPath = tempnam(sys_get_temp_dir(), 'product_cards_zip_');

            $files = collect(File::allFiles($cardsPath))
                ->mapWithKeys(fn($f, $index) => [Str::of($f->getFilename())->beforeLast('-')->value() . '-' . $index => $f])
                ->toArray(); // All files in the directory keyed by store name

            ZipFiles::make($files, 'pdf')->get($zipPath);

            File::deleteDirectory($cardsPath);

            return response()->download($zipPath, 'item-cards-' . date('Y-m-d_H-i-s') . '.zip')->deleteFileAfterSend(true);
        } catch (Exception $exception) {
            return $this->makeResponse([
                'message' => 'Error downloading card files',
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'exception' => $exception,
                'redirect' => 'dashboard',
            ]);
        }
    }

    private function findProductVariant($productId, $variantId)
    {
        $prodVariant = ProductVariant::where('product_id', $productId)->where('variant_id', $variantId)->first();

        if ($prodVariant)
            return $prodVariant;

        $product = Product::findOrFail($productId);
        $variant = Variant::findOrFail($variantId);
        $images = $product->images->mapToGroups(fn($image) => [$image->color_id => $image]);

        return ProductVariant::create([
            'product_id' => $productId,
            'variant_id' => $variantId,
            'product_image_id' => $images->get($variant->color_id)?->first()->id ?? $product->first_image->id,
        ]);
    }

    public function readAllRecords(Request $request)
    {
        try {
            $model = $this->getModelInstance();

            $perPage = in_array((int)$request->per_page, [10, 25, 50])
                ? (int)$request->per_page
                : 10;

            $currentFilters = $request->except(['page', 'per_page']);
            $previousFilters = session('previous_filters', []);
            if ($currentFilters != $previousFilters) {
                $request->merge(['page' => 1]);
            }
            session(['previous_filters' => $currentFilters]);

            $query = $model->with(['product', 'variant', 'store']);

            if ($this->searchField && $request->filled($this->searchField)) {
                $query->where($this->searchField, 'LIKE', $request->get($this->searchField));
            }

            if ($request->filled('search')) {
                $search = trim($request->search);
                $query->where(function ($q) use ($search) {
                    if (is_numeric($search)) {
                        $q->where('id', '=', $search);
                    } else {
                        $q->whereHas('product', function ($q) use ($search) {
                            $q->where('name', $search)
                                ->orWhere('name', 'like', '%' . $search . '%')
                                ->orWhere('title', $search)
                                ->orWhere('title', 'like', '%' . $search . '%');
                        });
                    }
                });
            }

            if ($request->filled('store_id')) {
                $query->where('store_id', $request->get('store_id'));
            }

            $this->beforeReadAll($query, $request);

            $data = $query->paginate($perPage)->appends($request->query());

            $responseData = [
                'data' => $data,
                'per_page' => $perPage,
                'searchBy' => $this->searchField ?? 'search',
                'stores' => Store::all(),
            ];

            return $this->makeResponse([
                'view' => $this->getIndexViewName(),
                'data' => $responseData,
                'message' => 'Artículos obtenidos correctamente',
                'status' => \Symfony\Component\HttpFoundation\Response::HTTP_OK,
            ]);
        } catch (\Exception $e) {
            return $this->makeResponse([
                'message' => 'Error al obtener artículos',
                'error' => $e->getMessage(),
                'status' => \Symfony\Component\HttpFoundation\Response::HTTP_INTERNAL_SERVER_ERROR,
            ]);
        }
    }
}
