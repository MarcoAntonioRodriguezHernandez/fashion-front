<?php

namespace App\Http\Controllers\Base\SupplyTransfer;

use App\Enums\{
    CrudAction,
    ItemStatuses,
    SupplyStatuses
};
use App\Enums\Auth\RoleAliases;
use App\Models\Base\{
    Product,
    Store,
    Supply,
    SupplyTransfer,
    SuppliedItem
};
use App\Models\User;
use App\Http\Controllers\Common\GenericCrudProvider;
use App\Http\Requests\Base\SupplyTransfer\{
    PostRequest,
    PutRequest
};
use App\Jobs\Marketplace\RefreshInventoryItem;
use App\Jobs\Marketplace\UploadProduct;
use App\Services\Marketplace\RefreshProductService;
use App\Traits\Helpers\HandlerFilesTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\{
    Auth,
    DB,
};
use Symfony\Component\HttpFoundation\Response;

class SupplyTransferController extends GenericCrudProvider
{
    use HandlerFilesTrait;

    protected string $modelClass = SupplyTransfer::class;

    protected string $indexView = 'base.supply_transfer.index';
    protected string $showView = 'base.supply_transfer.show';
    protected string $createView = 'base.supply_transfer.create';
    protected string $editView = 'base.supply_transfer.edit';

    private RefreshProductService $refreshProductService;

    public function __construct()
    {
        parent::__construct();

        $this->refreshProductService = new RefreshProductService();
    }

    protected function rules(): array
    {
        return [
            CrudAction::CREATE->value => (new PostRequest())->rules(),
            CrudAction::UPDATE->value => (new PutRequest())->rules(),
        ];
    }

    protected function pushCreateView()
    {
        $supplies = Supply::all();
        $users = User::all();
        $origin = Store::all();
        $destination = Store::all();

        return compact('supplies', 'users', 'origin', 'destination');
    }

    protected function pushEditView(Model $model)
    {
        $origin = Store::all();
        $destination = Store::all();

        return compact('origin', 'destination');
    }

    public function readRecord($field)
    {
        $model = $this->getModel($field);

        $user = Auth::user();
        $store = $model->destination ?? $model->load('destination')->destination;

        if (
            $user->hasAnyRole(RoleAliases::INVENTORY) &&
            $user->employeeDetail?->store_id !== $store->id
        ) {
            return $this->makeResponse([
                'message' => 'No tienes permiso para acceder a esta tienda.',
                'success' => false,
                'status' => Response::HTTP_FORBIDDEN,
            ]);
        }
        return parent::readRecord($field);
    }

    public function editView($field)
    {
        $model = $this->getModel($field);

        $user = Auth::user();
        $store = $model->destination ?? $model->load('destination')->destination;

        if (
            $user->hasAnyRole(RoleAliases::INVENTORY) &&
            $user->employeeDetail?->store_id !== $store->id
        ) {
            return $this->makeResponse([
                'message' => 'No tienes permiso para acceder a esta tienda.',
                'success' => false,
                'status' => Response::HTTP_FORBIDDEN,
            ]);
        }
        return parent::editView($field);
    }

    public function updateRecord(Request $request)
    {
        $model = $this->getModel($request->id);
        $user  = Auth::user();

        $items = $request->input('items', []);
        if (!is_array($items)) {
            $items = [];
        } else {
            $items = array_filter($items, 'is_array');
            $items = array_filter($items, fn($v) => array_key_exists('status', $v));
        }
        $request->merge(['items' => $items]);

        if (!$user?->hasAnyRole(RoleAliases::SUPER_ADMIN->value)) {
            $itemsPayload = $request->input('items', []);
            $ids = collect($itemsPayload)
                ->keys()
                ->map(fn($k) => (int) $k)
                ->filter()->values();

            $currentStatuses = SuppliedItem::query()
                ->whereIn('id', $ids)
                ->pluck('status', 'id');

            foreach ($itemsPayload as $id => $it) {
                if (!is_array($it) || !isset($it['status'])) {
                    continue;
                }

                $new = (int) $it['status'];
                $old = (int) ($currentStatuses[(int)$id] ?? null);

                $isTryingToSetRedistribution =
                    $new === SupplyStatuses::REDISTRIBUTION->value &&
                    $old !== SupplyStatuses::REDISTRIBUTION->value;

                if ($isTryingToSetRedistribution) {
                    return $this->makeResponse([
                        'message' => 'Sólo un Super Administrador puede marcar Redistribución.',
                        'success' => false,
                        'status'  => Response::HTTP_FORBIDDEN,
                    ]);
                }
            }
        }

        $itemsPayload = $request->input('items', []);
        if (!empty($itemsPayload)) {
            $idsToCheck = collect($itemsPayload)->keys()->map(fn($k) => (int)$k)->filter()->values();

            if ($idsToCheck->isNotEmpty()) {
                $lockedFound = SuppliedItem::query()
                    ->whereIn('id', $idsToCheck)
                    ->get()
                    ->first(fn(SuppliedItem $si) => $si->is_locked);

                if ($lockedFound) {
                    return $this->makeResponse([
                        'message' => 'Este renglón fue redistribuido y es solo lectura.',
                        'success' => false,
                        'status'  => Response::HTTP_FORBIDDEN,
                    ]);
                }
            }
        }

        if ($user?->hasAnyRole(RoleAliases::SUPER_ADMIN->value)) {
            return parent::updateRecord($request);
        }

        $isReceiving = $this->isReceivingUpdate($request);
        if ($isReceiving) {
            $recipient_id = $model->recipient_id;

            if ($user->hasAnyRole(RoleAliases::INVENTORY->value) && $user->id !== $recipient_id) {
                return $this->makeResponse([
                    'message' => 'No puedes recibir este artículo.',
                    'success' => false,
                    'status' => Response::HTTP_FORBIDDEN,
                ]);
            }
        }

        return parent::updateRecord($request);
    }

    protected function isReceivingUpdate(Request $request): bool
    {
        $deliveredStatuses = [
            SupplyStatuses::INCOMPLETE->value,
            SupplyStatuses::COMPLETE->value,
            SupplyStatuses::ERROR->value,
        ];

        $items = $request->input('items', []);
        if (!is_array($items) || empty($items)) return false;

        foreach ($items as $data) {
            if (!is_array($data)) continue;
            $st = isset($data['status']) ? (int) $data['status'] : null;
            if ($st !== null && in_array($st, $deliveredStatuses, true)) {
                return true;
            }
        }
        return false;
    }



    protected function beforeUpdate(array &$validatedData, Model $model, Request $request): ?array
    {

        if ($this->isReceivingUpdate($request)) {
            return [
                'recipient_id'   => Auth::id(),
                'reception_date' => now(),
            ];
        }

        return null;
    }


    protected function afterUpdate(Model $supplyTransfer, Request $request): ?array
    {
        $itemsInput = $request->input('items', []);
        if (!is_array($itemsInput) || empty($itemsInput)) {
            if (Auth::user()?->hasAnyRole(RoleAliases::SUPER_ADMIN->value)) {
                $affected = $supplyTransfer->suppliedItems()
                    ->where('delivered', false)
                    ->where('status', SupplyStatuses::PENDING->value)
                    ->update(['status' => SupplyStatuses::REDISTRIBUTION->value]);

                if ($affected > 0) {
                    $this->updateSupplyStatus($supplyTransfer->supply);
                }
            }
            return null;
        }


        $itemsInput = collect($itemsInput)
            ->filter(fn($v) => is_array($v) && array_key_exists('status', $v))
            ->all();

        if (empty($itemsInput)) {
            return null;
        }

        $ids = array_map('intval', array_keys($itemsInput));
        $suppliedItems = $supplyTransfer->suppliedItems()
            ->whereIn('id', $ids)
            ->get()
            ->keyBy('id');

        $slugsToColors = [];

        foreach ($itemsInput as $id => $data) {
            $sid = (int) $id;
            $suppliedItem = $suppliedItems->get($sid);
            if (!$suppliedItem) continue;

            $status = (int) ($data['status'] ?? $suppliedItem->status);
            $delivered = in_array(
                $status,
                [SupplyStatuses::INCOMPLETE->value, SupplyStatuses::COMPLETE->value, SupplyStatuses::ERROR->value],
                true
            );

            $prevLinkId = $suppliedItem->previous_supplied_item_id;
            $nextDetails = ($status === SupplyStatuses::ERROR->value)
                ? ($data['details'] ?? null)
                : $suppliedItem->details;

            $suppliedItem->update([
                'status'     => $status,
                'integrity'  => $status === SupplyStatuses::ERROR->value ? ($data['integrity'] ?? null) : null,
                'details'    => $nextDetails,
                'delivered'  => $delivered,
            ]);

            if ($delivered) {
                $latestDelivered = SuppliedItem::query()
                    ->where('item_id', $suppliedItem->item_id)
                    ->where('delivered', true)
                    ->orderByDesc('id')
                    ->first();

                $finalDestinationId = $latestDelivered?->supplyTransfer?->destination_id
                    ?? $supplyTransfer->destination_id;

                $suppliedItem->item->update([
                    'status'   => ItemStatuses::AVAILABLE->value,
                    'store_id' => $finalDestinationId,
                ]);

                if ($status === SupplyStatuses::COMPLETE->value) {
                    $touchedSupplyIds = [];
                    $cursorPrevId = $suppliedItem->previous_supplied_item_id;

                    while ($cursorPrevId) {
                        $prev = SuppliedItem::find($cursorPrevId);
                        if (!$prev) break;

                        if (!$prev->delivered) {
                            $prev->update([
                                'status'    => SupplyStatuses::COMPLETE->value,
                                'delivered' => true,
                            ]);

                            $pd = is_array($prev->details) ? $prev->details : [];
                            $pd['locked'] = true;         // queda solo lectura
                            $prev->update(['details' => $pd]);

                            if ($prev->supplyTransfer?->supply_id) {
                                $touchedSupplyIds[] = (int) $prev->supplyTransfer->supply_id;
                            }
                        }

                        $cursorPrevId = $prev->previous_supplied_item_id;
                    }

                    $touchedSupplyIds[] = (int) $supplyTransfer->supply_id;
                    collect($touchedSupplyIds)->filter()->unique()->each(function ($sid) {
                        if ($s = Supply::find($sid)) {
                            $this->updateSupplyStatus($s);
                        }
                    });
                }

                $itemEntity = $suppliedItem->item()->with('product', 'variant')->first();
                if ($itemEntity && $itemEntity->product && $itemEntity->variant) {
                    $slug    = $itemEntity->product->slug;
                    $colorId = (int) $itemEntity->variant->color_id;

                    $slugsToColors[$slug] = $slugsToColors[$slug] ?? [];
                    $slugsToColors[$slug][] = $colorId;
                }
            }
        }

        foreach ($slugsToColors as $slug => $colorsArr) {
            $colors = collect($colorsArr)->unique()->values();

            try {
                $productId = Product::where('slug', $slug)->value('id');

                $marketplaceProduct = DB::table('marketplace_objects')
                    ->where('conspiracy_type', Product::class)
                    ->where('conspiracy_id', $productId)
                    ->first();

                $productExists = false;
                if ($marketplaceProduct) {
                    $productExists = DB::table('marketplace_codes')
                        ->where(
                            fn($q) => $q
                                ->where('codable_type', 'App\Models\Shopify\ShopifyProductVariant')
                                ->where('codable_id', $marketplaceProduct->id)
                        )
                        ->exists();
                }

                $jobs = [$productExists ? RefreshInventoryItem::class : UploadProduct::class];

                if (config('marketplace.sync', true)) {
                    $this->refreshProductService
                        ->chainRefreshFromColors($slug, $colors, $jobs)
                        ->dispatch();
                }
            } catch (\Throwable $e) {
                \Log::warning('Marketplace sync error', [
                    'slug' => $slug,
                    'colors' => $colors,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->updateSupplyStatus($supplyTransfer->supply);

        return null;
    }



    private function updateSupplyStatus(Supply $supply)
    {
        $allItems = $supply->supplyTransfers->flatMap->suppliedItems;

        if ($allItems->isEmpty()) {
            return;
        }

        $allDelivered = $allItems->every(fn($si) => (bool)$si->delivered === true);
        if ($allDelivered) {
            $hasError = $allItems->contains(fn($si) => (int)$si->status === SupplyStatuses::ERROR->value);
            $supply->update([
                'status' => $hasError ? SupplyStatuses::INCOMPLETE->value : SupplyStatuses::COMPLETE->value,
            ]);
            return;
        }

        $openItems = $allItems->filter(fn($si) => (bool)$si->delivered === false);
        $pendingLike = [SupplyStatuses::PENDING->value, SupplyStatuses::REDISTRIBUTION->value];
        $onlyPendingLikeOpen = $openItems->every(fn($si) => in_array((int)$si->status, $pendingLike, true));

        if ($onlyPendingLikeOpen) {
            $allRedistributionOpen = $openItems->every(fn($si) => (int)$si->status === SupplyStatuses::REDISTRIBUTION->value);
            $supply->update([
                'status' => $allRedistributionOpen ? SupplyStatuses::REDISTRIBUTION->value : SupplyStatuses::PENDING->value,
            ]);
            return;
        }

        $supply->update(['status' => SupplyStatuses::INCOMPLETE->value]);
    }
}
