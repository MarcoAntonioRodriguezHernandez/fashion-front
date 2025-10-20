<?php

namespace App\Traits\Base\Product;

use App\Http\Requests\Base\Products\Mass\FullPriceRequest;
use App\Models\Base\{
    Item,
    Product,
};

trait MassUpdate
{

    public function massUpdateFullPrice(FullPriceRequest $request)
    {
        $products = Item::with('product:products.id')->whereIn('items.id', $request->items)->get()->pluck('product.id')->unique()->toArray();

        return $this->massUpdate($products, 'full_price', $request->full_price, fn($products, $value) => $products->each(fn($p) => $p->items()->update([
            'price_sale' => $value,
        ])));
    }

    protected function massUpdate(array $products, string $field, mixed $value, callable $postProcess = null)
    {
        if (method_exists($this, 'processRequest')) {
            return $this->processRequest(function () use ($products, $field, $value, $postProcess) {
                return $this->performMassUpdate($products, $field, $value, $postProcess);
            }, '', 'Error while mass updating product ' . $field);
        } else {
            return $this->performMassUpdate($products, $field, $value, $postProcess);
        }
    }

    protected function performMassUpdate(array $products, string $field, mixed $value, callable $postProcess = null)
    {
        $products = Product::whereIn('id', $products);

        $products->update([
            $field => $value,
        ]);

        if ($postProcess) {
            $postProcess($products->get(), $value);
        }

        return redirect()->back()->with('success', 'Product ' . $field . ' updated successfully');
    }
}
