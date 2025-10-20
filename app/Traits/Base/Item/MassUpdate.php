<?php

namespace App\Traits\Base\Item;

use App\Http\Requests\Base\Item\Mass\{
    ConditionRequest,
    ConditionStatusRequest,
    PriceSaleRequest,
};
use App\Jobs\Marketplace\{
    RefreshInventoryItem,
    RefreshProduct,
    RefreshProductVariant,
};
use App\Models\Base\{
    Item,
    Product,
};

trait MassUpdate
{
    public function massUpdateCondition(ConditionRequest $request)
    {
        return $this->massUpdate($request->items, 'condition', $request->condition);
    }

    public function massUpdateConditionStatus(ConditionStatusRequest $request)

    {
        $items = $request->items;
        $condition = $request->condition;
        $status = $request->status;
        return $this->massUpdateMultiple($items, [
            'condition' => $condition,
            'status' => $status,
        ]);
    }

    public function massUpdatePriceSale(PriceSaleRequest $request)
    {
        if ($request->boolean('select_all') && method_exists($this, 'resolveMassSelection')) {
            [$idsCol] = $this->resolveMassSelection($request);
            $ids = $idsCol->all();
        } else {
            $ids = array_values(array_unique($request->items ?? []));
        }

        $total = count($ids);
        if ($total === 0) {
            return redirect()->back()->with('error', 'No se encontraron artículos para actualizar precio.');
        }

        $itemsQuery = Item::whereIn('id', $ids);
        $itemsQuery->each(fn($i) => $i->update(['price_sale' => $request->price_sale]));

        if ($this->refreshProductService ?? null) {
            $groupedColors = $itemsQuery->clone()
                ->get()
                ->unique('product_variant_id')
                ->mapToGroups(fn($item) => [$item->product->id => $item->variant])
                ->map(fn($variants) => $variants->unique('color_id')->pluck('color_id')->toArray());

            foreach ($groupedColors as $productId => $colors) {
                $product = Product::findOrFail($productId);
                $this->refreshProductService->chainRefreshFromColors($product->slug, $colors, [
                    RefreshProduct::class,
                    RefreshProductVariant::class,
                    RefreshInventoryItem::class,
                ])->dispatch();
            }
        }

        return redirect()->back()->with('success', "Se actualizaron {$total} artículos.");
    }

    protected function massUpdateMultiple(array $items, array $fields, callable $postProcess = null)
    {
        $query = Item::whereIn('id', $items);
        $query->each(fn($i) => $i->update($fields));
        if ($postProcess) {
            $postProcess($query->get(), $fields);
        }
        return redirect()->back()->with('success', 'Items updated successfully');
    }

    protected function massUpdate(array $items, string $field, mixed $value, callable $postProcess = null)
    {
        if (method_exists($this, 'processRequest')) {
            return $this->processRequest(function () use ($items, $field, $value, $postProcess) {
                return $this->performMassUpdate($items, $field, $value, $postProcess);
            }, '', 'Error while mass updating item ' . $field);
        } else {
            return $this->performMassUpdate($items, $field, $value, $postProcess);
        }
    }

    protected function performMassUpdate(array $items, string $field, mixed $value, callable $postProcess = null)
    {
        $items = Item::whereIn('id', $items);

        $items->each(fn($i) => $i->update([ // Perform mass update with events
            $field => $value,
        ]));

        if ($postProcess) {
            $postProcess($items->get(), $value);
        }

        if ($this->refreshProductService != null) {
            $groupedColors = $items->clone()
                ->get()
                ->unique('product_variant_id')
                ->mapToGroups(fn($item) => [$item->product->id => $item->variant])
                ->map(fn($variants) => $variants->unique('color_id')->pluck('color_id')->toArray());

            foreach ($groupedColors as $productId => $colors) {
                $product = Product::findOrFail($productId);

                $this->refreshProductService->chainRefreshFromColors($product->slug, $colors, [
                    ...(in_array($field, ['condition', 'status', 'price_sale']) ? [
                        RefreshProduct::class,
                        RefreshProductVariant::class,
                    ] : []),
                    RefreshInventoryItem::class,
                ])->dispatch();
            }
        }

        return redirect()->back()->with('success', 'Item ' . $field . ' updated successfully');
    }
}
