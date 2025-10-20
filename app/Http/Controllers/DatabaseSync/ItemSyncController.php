<?php

namespace App\Http\Controllers\DatabaseSync;

use App\Http\Controllers\Common\BaseSyncController;
use App\Models\Base\{
    Color,
    Invoice,
    Item,
    Product,
    ProductVariant,
    Size,
    Store,
    Variant,
};
use App\Traits\Helpers\Support;
use Exception;
use Illuminate\Support\{
    Collection,
    Str,
};

class ItemSyncController extends BaseSyncController
{

    use Support;

    public function __construct()
    {
        parent::__construct(config('services.conspiracy.api_base_url') . '/db-sync/item');
    }

    /**
     * Process the data fetched from the remote
     * 
     * @param Collection $data The fetched data to sync
     */
    protected function processData(Collection $data)
    {
        $products = Product::select('id', 'name')->get()->each(fn($p) => $p->name = Str::slug($p->name))->keyBy('name');
        $colors = Color::select('id', 'slug')->get()->keyBy('slug');
        $sizes = Size::select('id', 'slug')->get()->keyBy('slug');
        $stores = Store::select('id', 'name')
            ->get()
            ->map(fn($s) => [
                'id' => $s->id,
                'slug' => Str::slug($s->name),
                'name' => $s->name,
            ])
            ->keyBy('slug');

        foreach ($data as $itemsData) {
            try {
                [, $productName] = explode(',', $itemsData['product']);

                $productName = Str::slug($productName);

                $product = $this->getValue($products, $productName, 'product');
                $image = $product->images()->where('src_image', 'LIKE', '%' . $itemsData['image'])->firstOrFail();

                foreach ($itemsData['variants'] as $varData) {
                    if (in_array($varData['color'], ['null'])) {
                        $varData['color'] = 'unknown';
                    }

                    $variant = Variant::firstOrCreate([
                        'code' => $this->generateVariantCode($varData['size'], $varData['color']),
                    ], [
                        'size_id' => $this->getValue($sizes, $varData['size'], 'size')->id,
                        'color_id' => $this->getValue($colors, $varData['color'], 'color')->id,
                        'status' => true,
                    ]);

                    $productVariant = ProductVariant::firstOrCreate([
                        'product_id' => $product->id,
                        'variant_id' => $variant->id,
                    ], [
                        'product_image_id' => $image->id,
                    ]);

                    foreach ($varData['items'] as $itemData) {
                        try {
                            Item::create([
                                'product_variant_id' => $productVariant->id,
                                'store_id' => $this->getValue($stores, $itemData['store'], 'store')['id'],
                                'serial_number' => trim($itemData['serial_number']) ?: null,
                                'barcode' => trim($itemData['barcode']) ?: null,
                                'price_sale' => $itemData['price_sale'],
                                'price_purchase' => $itemData['price_purchase'],
                                'details' => '',
                                'invoice_id' => Invoice::first()->id,
                                // These states might be overwritten on OrderSyncController
                                'condition' => $itemData['condition'],
                                'status' => $itemData['status'],
                                'integrity' => $itemData['integrity'],
                            ]);
                        } catch (Exception $e) {
                            $this->errors[$itemsData['product']][] = 'Item (' . $itemData['serial_number'] . ' | ' . $itemData['barcode'] . '): ' . $e->getMessage();
                        }
                    }
                }
            } catch (Exception $e) {
                $this->errors[$itemsData['product']] = $e->getMessage();
            }
        }

        // Delete products without items
        Product::doesntHave('items')->delete();
    }
}
