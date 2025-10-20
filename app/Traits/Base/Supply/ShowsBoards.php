<?php

namespace App\Traits\Base\Supply;

use App\Enums\{
    ItemConditions,
    ItemIntegrities,
    ItemStatuses,
    SupplyStatuses,
};
use App\Enums\Auth\RoleAliases;
use App\Models\Base\{
    Item,
    Product,
};
use App\Traits\Base\Item\FiltersItems;
use Illuminate\Support\{
    Collection,
    Str,
};

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

trait ShowsBoards
{

    use FiltersItems;

    protected function searchProducts(array $data)
    {
        return Product::query()
            ->where(DB::raw('LOWER(name)'), Str::lower($data['name']))
            ->when($data['designer_id'], fn($q) => $q->where('designer_id', $data['designer_id']))
            ->get();
    }

    protected function getStoresData(Collection $stores)
    {
        return $stores->mapWithKeys(function ($store) {
            $storeKey = 'store-' . $store->id;

            return [$storeKey => (object) [
                'id' => $store->id,
                'key' => $storeKey,
                'title' => $store->name,
            ]];
        });
    }

    protected function getItemsByStore(Collection $items, Collection $stores)
    {
        $groupedItems = $items
            ->mapToGroups(fn($item) => [
                $item['support']['store_id'] => $item,
            ]);

        return $stores->mapWithKeys(function (object $store, string $storeKey) use ($groupedItems) {
            return [
                $storeKey =>
                $groupedItems
                    ->get($store->id, collect())
                    ->map(function ($item) use ($storeKey) {
                        $item['origin'] = $storeKey;
                        unset($item['support']);

                        return $item;
                    })
                    ->toArray(),
            ];
        });
    }

    protected function buildItemData(Item $item)
    {
        $baseMovable = in_array($item->status, [
            ItemStatuses::AVAILABLE->value,
            ItemStatuses::IMPORTATION->value,
            ItemStatuses::TRANSFER->value,
        ], true) &&
        in_array($item->integrity, [
            ItemIntegrities::HEALTHY->value,
        ], true);

        $lastSupplied = $item->suppliedItems()->latest()->first();

        $hasPendingLikeTransfer = in_array(
            $lastSupplied?->status,
            [SupplyStatuses::PENDING->value, SupplyStatuses::REDISTRIBUTION->value],
            true
        );
        $isRedistribution = ($lastSupplied?->status === SupplyStatuses::REDISTRIBUTION->value);
        $hasSentTransfer = ($lastSupplied?->status === SupplyStatuses::SENT->value);

        $user         = Auth::user();
        $isSuperAdmin = (bool) $user?->hasAnyRole(RoleAliases::SUPER_ADMIN->value);

        // Si el status es TRAYECT, siempre debe ser false
        if ($item->status === ItemStatuses::TRAYECT->value) {
            $enabled = false;
        } else {
            $enabled = match (true) {
                $hasSentTransfer        => false,
                $hasPendingLikeTransfer => $isSuperAdmin,
                default                 => $baseMovable,
            };
        }

        $lastTransfer   = $item->supplyTransfers()->latest()->first();
        $supportStoreId = $item->store_id;
        if ($hasPendingLikeTransfer && $lastTransfer) {
            $supportStoreId = $lastTransfer->destination_id;
        }

        return [
            'id' => $item->id,
            'support' => [
                'store_id' => $supportStoreId,
                'image_src' => $item->productVariant->productImage?->src_image ?? asset('media/misc/image.png'),
                'color_id' => $item->variant->color_id,
                'color_name' => $item->variant->color->name,
                'product_variant_id' => $item->product_variant_id,
                'last_supplied_status' => $lastSupplied?->status, 
            ],
            'enabled' => $enabled,
            'markers' => [
                'transfer' => $item->status == ItemStatuses::TRANSFER->value,
                'importation' => $item->status == ItemStatuses::IMPORTATION->value,
                'liquidation' => $item->condition == ItemConditions::LIQUIDATION->value,
                'lost' => $item->status == ItemStatuses::LOST->value,
                'pending_transfer' => $hasPendingLikeTransfer,
                'redistribution' => $isRedistribution, 
                'damaged' => $item->integrity != ItemIntegrities::HEALTHY->value,
                'trayect' => $item->status == ItemStatuses::TRAYECT->value, 
            ],
            'primaryData' => [
                'color' => $item->variant->color->hexadecimal,
                'text' => $item->variant->size->full_name,
            ],
            'secondaryData' => [
                'Código Barras' => $item->barcode ?: '---',
                'Última Renta' => ($item->latestItemOrderMarketplace?->created_at->format('d / m / Y') ?: '---') . ' (' . $item->itemOrderMarketplaces()->where('status', true)->count() . ' en total)',
                'Fecha Creación' => $item->created_at->format('d / m / Y'),
                'Destino' => $item->status == ItemStatuses::TRANSFER->value
                    ? $item->supplyTransfers()->latest()->get()->first()?->destination->name
                    : 'Ninguno',
                'Estatus de ubicación' => $item->status == ItemStatuses::TRAYECT->value ? 
                'En Trayecto' : 
                    ($hasPendingLikeTransfer ? 'Solicitud de Movimiento' : $item->statusName),
                'Estado' => $item->conditionName . ' (' . $item->integrityName . ')',
            ],
            'tertiaryData' => [
                'barcode' => $item->barcode,
                'serial_number' => $item->serial_number,
                'color' => $item->variant->color->hexadecimal,
                'size' => $item->variant->size->full_name,
                'first_image' => $item->productVariant->productImage->src_image,
                'status' => $item->statusName,
                'colorStatus' => $item->statusColor,
            ],
            'infoData' => [
                'name' => $item->product->name . ' / ' . $item->barcode,
                'current_store_name' => $item->store->name,
                'target_store_name' => $item->latestActiveSuppliedItem?->supplyTransfer->destination->name ?? 'Ninguno',
                'supplies' => $this->buildStoreHistory($item),
            ],
        ];
    }
}
