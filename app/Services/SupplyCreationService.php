<?php

namespace App\Services;

use App\Enums\OrderSaleType;
use App\Enums\SupplyStatuses;
use App\Enums\ItemStatuses;
use App\Enums\ItemConditions;
use App\Enums\NotificationTypes;
use App\Jobs\Marketplace\RefreshInventoryItem;
use App\Models\Base\Item;
use App\Models\Base\SuppliedItem;
use App\Models\Base\ProductVariant;
use App\Models\Base\Supply;
use App\Models\Base\SupplyTransfer;
use App\Models\Marketplace\OrderMarketplace;
use App\Models\User;
use App\Notifications\DistributionCreatedNotification;
use App\Services\Marketplace\RefreshProductService;
use Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Exception;
use Log;

class SupplyCreationService
{
    protected RefreshProductService $refreshProductService;

    public function __construct(RefreshProductService $refreshProductService)
    {
        $this->refreshProductService = $refreshProductService;
    }

    /**
     *
     * @param array $validatedData 
     *
     * @return Supply
     *
     * @throws Exception
     */

    public function createSupply(array $validatedData): Supply
    {
        return DB::transaction(function () use ($validatedData) {
            $supply = Supply::create($validatedData);

            $itemIds = collect($validatedData['items'])->pluck('item_id');
            $itemList = Item::with('product', 'variant')->find($itemIds)->keyBy('id');

            $groupedItems = collect($validatedData['items'])
                ->mapToGroups(function ($data) use ($itemList) {
                    $item = $itemList->get($data['item_id']);

                    return [$item->store_id . '-' . $data['destination_id'] => [
                        'origin_id' => $item->store_id,
                        ...$data,
                    ]];
                })
                ->map(function ($group) {
                    return [
                        'origin_id' => $group[0]['origin_id'],
                        'destination_id' => $group[0]['destination_id'],
                        'recipient_id' => $group[0]['recipient_id'] ?? null,
                        'items' => $group,
                    ];
                })
                ->values();

            $touchedSupplyIds = [];

            foreach ($groupedItems as $data) {
                $supplyTransfer = SupplyTransfer::create([
                    'supply_id'      => $supply->id,
                    'recipient_id'   => $data['recipient_id'],
                    'reception_date' => null,
                    'origin_id'      => $data['origin_id'],
                    'destination_id' => $data['destination_id'],
                ]);


                foreach ($data['items'] as $itemData) {
                    $item = $itemList->get($itemData['item_id']);

                    $previousOpen = SuppliedItem::query()
                        ->where('item_id', $item->id)
                        ->where('delivered', false)
                        ->latest('id')
                        ->first();

                    $newStatus = SupplyStatuses::PENDING->value;

                    if ($previousOpen && in_array((int)$previousOpen->status, [
                        SupplyStatuses::PENDING->value,
                        SupplyStatuses::REDISTRIBUTION->value,
                    ], true)) {
                        $newStatus = SupplyStatuses::REDISTRIBUTION->value;

                        if ((int)$previousOpen->status === SupplyStatuses::PENDING->value) {
                            $previousOpen->update(['status' => SupplyStatuses::REDISTRIBUTION->value]);
                        }

                        $touchedSupplyIds[] = $previousOpen->supplyTransfer->supply_id ?? null;
                    }

                    $newSupplied = SuppliedItem::create([
                        'supply_transfer_id' => $supplyTransfer->id,
                        'item_id'            => $item->id,
                        'delivered'          => false,
                        'status'             => $newStatus,
                        'details'            => $previousOpen ? [
                            'previous_supplied_item_id' => $previousOpen->id,
                        ] : [],
                    ]);

                    if ($previousOpen) {
                        $prevDetails = is_array($previousOpen->details) ? $previousOpen->details : [];
                        $prevDetails['locked']                          = true;
                        $prevDetails['redirected_to_supply_id']         = $supply->id;
                        $prevDetails['redirected_to_supply_transfer_id'] = $supplyTransfer->id;
                        $prevDetails['redirected_to_supplied_item_id']  = $newSupplied->id;

                        $previousOpen->update(['details' => $prevDetails]);
                    }

                    $item->update([
                        'status' => ItemStatuses::TRANSFER->value,
                    ]);
                }
            }

            $itemList->mapToGroups(fn($i) => [$i->product->slug => $i->variant->color_id])
                ->each(function (Collection $colors, string $slug) {
                    $this->refreshProductService->chainRefreshFromColors($slug, $colors, [
                        RefreshInventoryItem::class,
                    ])->dispatch();
                });

            $this->recalcSupplyStatus($supply);

            collect($touchedSupplyIds)
                ->filter()
                ->unique()
                ->each(function ($sid) {
                    if ($sid) {
                        $old = Supply::find($sid);
                        if ($old) {
                            $this->recalcSupplyStatus($old);
                        }
                    }
                });


            return $supply;
        });
    }

    private function recalcSupplyStatus(Supply $supply): void
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

    public function createAutomaticSupply(OrderMarketplace $order): void
    {
        $warehouseStoreId = 7;
        $orderId = $order->id;

        $soldItems = $order->itemOrders->filter(fn($item) => $item->sale_type === OrderSaleType::SALE->value);

        Log::info('[as] Items vendidos filtrados', ['count' => $soldItems->count(), 'order_id' => $orderId]);

        if ($soldItems->isEmpty()) {
            Log::info('[as] No hay items vendidos para suplir, terminando función');
            return;
        }

        $itemIds = $soldItems->pluck('item_id');

        $items = Item::whereIn('id', $itemIds)
            ->with(['variant.color', 'variant.size'])
            ->get()
            ->keyBy('id');

        $variantIds = $soldItems
            ->map(fn($io) => $items->get($io->item_id)?->product_variant_id)
            ->filter()
            ->unique()
            ->values();

        Log::info('[as] Variantes a buscar en almacén', ['variant_ids' => $variantIds->toArray()]);

        if ($variantIds->isEmpty()) {
            Log::info('[as] No hay variantes válidas en items vendidos', ['order_id' => $orderId]);
            return;
        }

        $productIds = ProductVariant::whereIn('id', $variantIds)->pluck('product_id')->unique()->values();
        Log::info('[as] Productos relacionados con las variantes', ['product_ids' => $productIds->toArray()]);

        $warehouseItemsCollection = Item::query()
            ->with(['variant.color', 'variant.size'])
            ->where('store_id', $warehouseStoreId)
            ->where('status', ItemStatuses::AVAILABLE)
            ->where('condition', ItemConditions::NEW)
            ->whereHas('variant', function ($q) use ($productIds) {
                $q->whereIn('product_id', $productIds);
            })
            ->get();

        $warehouseItems = $warehouseItemsCollection->groupBy(fn($it) => $it->product_variant_id);

        Log::info('[as] Items disponibles en almacén agrupados por variante', [
            'counts' => $warehouseItems->map->count(),
            'variant_keys' => $warehouseItems->keys()->toArray(),
            'preview' => $warehouseItems->map(function ($group, $key) {
                $first = $group->first();
                return [
                    'variant_id' => $key,
                    'example_item_id' => $first?->id,
                    'variant_product_id' => $first?->variant?->product_id ?? null,
                    'color_id' => $first?->variant?->color?->id ?? $first?->variant?->color_id ?? null,
                    'size_id' => $first?->variant?->size?->id ?? $first?->variant?->size_id ?? null,
                    'count' => $group->count()
                ];
            }),
        ]);

        $supplyItems = [];
        $missingVariants = [];

        foreach ($soldItems as $soldItem) {
            $itemDetail = $items->get($soldItem->item_id);
            if (!$itemDetail) {
                Log::warning('[as] Item vendido no encontrado en collection $items', ['item_id' => $soldItem->item_id, 'order_id' => $orderId]);
                continue;
            }

            $variantId = $itemDetail->product_variant_id;
            $destinationStoreId = $itemDetail->store_id;
            $itemContext = ['variant_id' => $variantId, 'store_id' => $destinationStoreId, 'item_id' => $soldItem->item_id];
            $designerId = $itemDetail->product?->designer_id;

            // Regla 2: ya existe en tienda destino (excepto diseñador Barly)
            $alreadyExists = Item::where('store_id', $destinationStoreId)
                ->where('product_variant_id', $variantId)
                ->where('status', ItemStatuses::AVAILABLE)
                ->whereNotIn('id', $itemIds)
                ->exists();
            if ($alreadyExists && $designerId != 24) {
                Log::info('[as] La variante ya existe en tienda destino (no aplica para Barly)', $itemContext);
                continue;
            }

            // Regla 3:
            if (!isset($warehouseItems[$variantId]) || $warehouseItems[$variantId]->isEmpty()) {
                Log::info('[as] No hay stock en almacén para variante', $itemContext);
                $missingVariants[] = $variantId;

                $originalVariant = $itemDetail->variant;
                if (!$originalVariant) {
                    Log::warning('[as] Variant missing en originalItem', $itemContext);
                    continue;
                }

                $productId = $originalVariant->product_id;
                $originalColor = $originalVariant->color_id ?? $originalVariant->color?->id ?? null;
                $originalSize = $originalVariant->size_id ?? $originalVariant->size?->id ?? null;

                $alternativeItem = null;

                foreach ($warehouseItems as $altVariantId => $itemsGroup) {
                    if ($itemsGroup->isEmpty()) continue;
                    if ($altVariantId == $variantId) continue;

                    $altItem = $itemsGroup->first();
                    $altVariant = $altItem->variant;
                    if (!$altVariant) continue;

                    if (($altVariant->product_id ?? null) != ($productId ?? null)) continue;

                    $altColor = $altVariant->color_id ?? $altVariant->color?->id ?? null;
                    if ($altColor === null || $originalColor === null) continue;
                    if ($altColor != $originalColor) continue;

                    $altSize = $altVariant->size_id ?? $altVariant->size?->id ?? null;
                    if ($altSize === null || $originalSize === null) continue;
                    if ($altSize == $originalSize) continue;

                    $existsInStore = Item::where('store_id', $destinationStoreId)
                        ->where('product_variant_id', $altVariantId)
                        ->where('status', ItemStatuses::AVAILABLE)
                        ->exists();
                    if ($existsInStore) continue;

                    if ($itemIds->contains($altItem->id)) continue;

                    $alternativeItem = $itemsGroup->shift();
                    break;
                }

                if ($alternativeItem) {
                    Log::info('[as] Supliendo variante alternativa (mismo modelo, mismo color, diferente talla)', [
                        'original_variant' => $variantId,
                        'alternative_variant' => $alternativeItem->product_variant_id,
                        'store_id' => $destinationStoreId
                    ]);
                    $supplyItems[] = [
                        'item_id' => $alternativeItem->id,
                        'destination_id' => $destinationStoreId,
                    ];
                }

                continue;
            }

            $item = $warehouseItems[$variantId]->shift();
            if (
                !$item ||
                $item->status !== ItemStatuses::AVAILABLE->value ||
                $item->condition !== ItemConditions::NEW->value
            ) {
                Log::warning('[as] Ítem descartado por no cumplir Disponible + Nuevo', [
                    'item_id' => $item?->id,
                    'status'  => $item?->status,
                    'cond'    => $item?->condition,
                ]);
                continue;
            }


            $supplyItems[] = [
                'item_id' => $item->id,
                'destination_id' => $destinationStoreId,
            ];
        }

        if (!empty($missingVariants)) {
            Log::info('[as] No hay stock en almacén para algunos items', [
                'order_id' => $orderId,
                'missing_variants' => $missingVariants,
            ]);
        }

        if (empty($supplyItems)) {
            Log::info('[as] No se generó ningún supply porque no había stock disponible', [
                'order_id' => $orderId,
            ]);
            $employeesToNotify = User::notifiedFor(NotificationTypes::WARE_HOUSE)->get();
            foreach ($employeesToNotify as $employee) {
                \App\Notifications\DistributionFailedNotification::notify(
                    $employee,
                    $order,
                    'No se encontró stock en almacén de la misma variante o variantes alternativas'
                );

                $employee->notify(
                    new \App\Notifications\SupplyFailedNotification(
                        $order,
                        'No se encontró stock en almacén de la misma variante o variantes alternativas'
                    )
                );
            }
            return;
        }

        $validatedData = [
            'code' => Supply::generateCode(),
            'sender_id' => Auth::id(),
            'shipping_date' => now(),
            'status' => SupplyStatuses::PENDING->value,
            'is_automatic' => true,
            'items' => $supplyItems,
        ];

        Log::info('[as] Creando supply con los siguientes items', ['items_count' => count($supplyItems), 'code' => $validatedData['code']]);
        $supply = $this->createSupply($validatedData);

        Log::info('[as] Supply creado automáticamente para la orden', [
            'order_id' => $orderId,
            'supply_code' => $validatedData['code'],
            'items_supplied' => count($supplyItems),
        ]);

        $employeesToNotify = User::notifiedFor(NotificationTypes::WARE_HOUSE)->get();
        foreach ($employeesToNotify as $employee) {
            DistributionCreatedNotification::notify($employee, $supply, 'create', $order);
        }
    }
}
