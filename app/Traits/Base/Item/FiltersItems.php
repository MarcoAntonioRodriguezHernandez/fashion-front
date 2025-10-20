<?php

namespace App\Traits\Base\Item;

use App\Models\Base\{
    Item,
    Product,
    Store,
    SupplyTransfer,
};
use DateTime;
use Illuminate\Support\Str;
use App\Enums\{
    ItemConditions,
    ItemIntegrities,
    ItemStatuses,
};
use Illuminate\Support\Collection;

trait FiltersItems
{
    public function filterItemsBy(object $filters, int|bool $pageSize = null)
    {
        return Product::with('items')
            ->when($filters->ids ?? false, fn($q) => $q->whereIn('id', $filters->ids))
            ->when($filters->name ?? false, fn($q) => $q->where(function ($query) use ($filters) {
                $query->where('slug', Str::slug($filters->name))->orWhere('origin_code', $filters->name);
            }))
            ->when($filters->category ?? false, fn($q) => $q->where('category_id', $filters->category))
            ->when($filters->designer ?? false, fn($q) => $q->where('designer_id', $filters->designer))
            ->when($filters->characteristics ?? false, fn($q) => $q->whereHas('characteristics', fn($c) => $c->whereIn('characteristics.id', $filters->characteristics)))
            ->get()
            ->map(function (Product $product) use ($filters) {
                return $product->items()
                    ->with('product')
                    ->when($filters->barcode ?? false, fn($q) => $q->where('barcode', $filters->barcode))
                    ->when($filters->store ?? false, fn($q) => $q->where('store_id', $filters->store))
                    ->when($filters->condition ?? false, function ($q) use ($filters) {
                        if ((int)$filters->condition === ItemConditions::BAZAAR->value) {
                            $storeId = Store::where('name', 'Almacén')->value('id');
                            if ($storeId) {
                                $q->where('condition', $filters->condition)->where('store_id', $storeId);
                            } else {
                                $q->where('condition', $filters->condition);
                            }
                        } else {
                            $q->where('condition', $filters->condition);
                        }
                    })
                    ->when($filters->integrities ?? false, fn($q) => $q->where('integrity', $filters->integrities))
                    ->when($filters->status ?? false, fn($q) => $q->where('status', $filters->status))
                    ->when($filters->sizes ?? false, fn($q) => $q->whereHas('variant', fn($c) => $c->whereIn('size_id', $filters->sizes)))
                    ->when($filters->colors ?? false, fn($q) => $q->whereHas('variant', fn($c) => $c->whereIn('color_id', $filters->colors)))
                    ->tap(function ($q) use ($filters) { // Exclude items by default
                        $excludes = $this->getDefaultExcludes();

                        foreach ($excludes as $key => $values) {
                            $shouldExclude = !property_exists($filters, $key) || !in_array($filters->$key, $values); // If the filter is not in the exclude list, exclude the items

                            $q->when($shouldExclude, fn($s) => $s->whereNotIn($key, $values));
                        }
                    })
                    ->get();
            })
            ->flatten(1)
            ->pipe(fn($c) => $this->normalizeData($c, $pageSize));
    }

    public function filterItemsById(array $items, int|bool $pageSize = null)
    {
        return Item::with('product')
            ->findOrFail($items)
            ->pipe(fn($c) => $this->normalizeData($c, $pageSize));
    }

    protected function normalizeData(Collection $items, int|bool $pageSize = null)
    {
        return $items
            ->when($pageSize === false, fn($c) => $c->map(fn(Item $i) => $this->buildItemData($i)))
            ->when($pageSize !== false, fn($c) => $c->paginate($pageSize)->through(fn(Item $i) => $this->buildItemData($i)));
    }

    protected function buildItemData(Item $item)
    {
        $productVariant = $item->productVariant;
        $product = $item->product;
        $pricingScheme = $product->pricingScheme;

        return [
            'id' => $item->id,
            'product_variant_id' => $productVariant->id,
            'product_name' => $product->name,
            'code_origin' => $product->origin_code,
            'product_full_name' => $product->full_name,
            'category_name' => $product->category->name,
            'barcode' => $item->barcode,
            'first_image' => $productVariant->productImage?->src_image ?? asset('media/misc/image.png'),
            'designer_name' => $product->designer->name,
            'store_name' => $item->store->name,
            'size_name' => $item->variant->size->full_name,
            'color_name' => $item->variant->color->name,
            'price_rent_label' => '$ ' . $pricingScheme->sku_4->price . ' / $ ' . $pricingScheme->sku_8->price,
            'prices_rent' => [
                '4' => $pricingScheme->sku_4->price,
                '8' => $pricingScheme->sku_8->price,
            ],
            'price_sale' => $item->price_sale,
            'full_price' => $product->full_price,
            'entered_date' => $item->created_at->format('d / m / Y'),
            'amount_rents' => $item->itemOrderMarketplaces()->count(),
            'last_rent_date' => $item->latestItemOrderMarketplace?->created_at->format('d / m / Y'),
            'condition' => $item->condition_name,
            'integrity' => $item->integrity_name,
            'status' => $item->status_name,
            'conditionColor' => ItemConditions::getColor($item->condition),
            'integrityColor' => ItemIntegrities::getColor($item->integrity),
            'statusColor' => ItemStatuses::getColor($item->status),
            'conditionName' => ItemConditions::getName($item->condition),
            'integrityName' => ItemIntegrities::getName($item->integrity),
            'statusName' => ItemStatuses::getName($item->status),
            'states' => collect([
                [
                    'color' => ItemConditions::getColor($item->condition),
                    'name' => ItemConditions::getName($item->condition),
                    'enabled' => in_array($item->condition, [
                        ItemConditions::LIQUIDATION->value,
                        ItemConditions::PRE_LIQUIDATION->value,
                    ]),
                ],
                [
                    'color' => ItemIntegrities::getColor($item->integrity),
                    'name' => ItemIntegrities::getName($item->integrity),
                    'enabled' => in_array($item->integrity, [
                        ItemIntegrities::BROKEN->value,
                        ItemIntegrities::MISSING_PARTS->value,
                    ]),
                ],
                [
                    'color' => ItemStatuses::getColor($item->status),
                    'name' => ItemStatuses::getName($item->status),
                    'enabled' => in_array($item->status, [
                        ItemStatuses::IMPORTATION->value,
                    ]),
                ],
                [
                    'color' => 'danger',
                    'name' => 'En Trayecto',
                    'enabled' => $item->status == ItemStatuses::TRAYECT->value,
                ],
                [
                    'color' => 'warning',
                    'name' => 'Movimiento Pendiente',
                    'enabled' => $item->status == ItemStatuses::TRANSFER->value,
                ],
            ])->filter(),
        ];
    }

    private function buildItemInfoData(Item $item)
    {
        $productVariant = $item->productVariant;
        $product = $productVariant->product;

        return [
            'item_id' => $item->id,
            'name' => $product->name,
            'product_title' => $product->title,
            'variant_color' => $productVariant->variant->color->name,
            'variant_size' => $productVariant->variant->size->full_name,
            'current_store_name' => $item->store->name,
            'target_store_name' => $item->latestActiveSuppliedItem?->supplyTransfer->destination->name ?? 'Ninguno',
            'condition' => $item->condition,
            'status' => $item->status,
            'price_sale' => $item->price_sale,
            'full_price' => $product->full_price,
            'barcode' => $item->barcode,
            'serial_number' => $item->serial_number,
            'details' => $item->details,
            'description' => $product->description,
            'pricing_scheme_id' => $product->pricing_scheme_id,
            'created_at' => $item->created_at->format('d / m / Y'),
            'supplies' => $this->buildStoreHistory($item),
        ];
    }

    protected function buildStoreHistory(Item $item)
    {
        $rawSupplies = $item->supplyTransfers
            ->map(function (SupplyTransfer $s) {
                return [
                    'date' => new DateTime($s->reception_date),
                    'origin_name' => $s->origin->name,
                    'destination_name' => $s->destination->name,
                ];
            })
            ->sortBy('date')
            ->values();

        $rawSupplies->push([
            'date' => now(),
            'origin_name' => $rawSupplies->last()['destination_name'] ?? $item->store->name,
            'destination_name' => null,
        ]);

        $supplies = collect();

        for ($i = 0; $i < $rawSupplies->count(); $i++) {
            $data = $rawSupplies->get($i);

            $elapsed = date_diff($data['date'], $rawSupplies->get($i - 1)['date'] ?? $item->created_at)->format('%a');

            $supplies->push([
                'date' => $data['date']->format('d / m / Y'),
                'store_name' => $data['origin_name'],
                'elapsed' => [
                    'weeks' => intval($elapsed / 7),
                    'days' => intval($elapsed % 7),
                ],
            ]);
        }

        return $supplies;
    }

    /**
     * Default filters to exclude items
     * 
     * Should be formatted as $fieldName => $excludedValues
     */
    protected function getDefaultExcludes()
    {
        return [
            'status' => [
                ItemStatuses::SOLD->value,
                ItemStatuses::ARCHIVED->value,
                ItemStatuses::LOST->value,
            ],
        ];
    }
}
