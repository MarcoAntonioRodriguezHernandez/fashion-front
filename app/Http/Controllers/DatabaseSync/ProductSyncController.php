<?php

namespace App\Http\Controllers\DatabaseSync;

use App\Enums\ProductSaleTypes;
use App\Http\Controllers\Common\BaseSyncController;
use App\Models\Base\{
    Category,
    Characteristic,
    CharacteristicProduct,
    Color,
    Designer,
    PricingScheme,
    Product,
    ProductTag,
    Tag,
};
use App\Traits\Base\Product\CreatesProduct;
use App\Traits\Helpers\Support;
use Exception;
use Illuminate\Support\{
    Collection,
    Str,
};

class ProductSyncController extends BaseSyncController
{

    use Support, CreatesProduct;

    public function __construct()
    {
        parent::__construct(config('services.conspiracy.api_base_url') . '/db-sync/product');
    }

    /**
     * Process the data fetched from the remote
     * 
     * @param Collection $data The fetched data to sync
     */
    protected function processData(Collection $data)
    {
        $colors = Color::select('id', 'slug')->get()->keyBy('slug');
        $designers = Designer::select('id', 'slug')->get()->keyBy('slug');
        $categories = Category::select('id', 'slug')->get()->keyBy('slug');
        $pricingSchemes = PricingScheme::with('sku_4')->select('id', 'sku_4_id')->get()->keyBy('sku_4.price');
        $characteristics = Characteristic::select('id', 'slug')->get()->keyBy('slug');
        $tags = Tag::select('id', 'slug')->get()->keyBy('slug');

        foreach ($data as $productData) {
            try {
                // Find or create the product
                $product = Product::firstOrCreate([
                    'slug' => Str::slug($productData['name']),
                ], [
                    'name' => $productData['name'],
                    'title' => $this->cleanTitle($productData['title']),
                    'description' => $productData['description'],
                    'full_price' => $productData['full_price'],
                    'origin_code' => fake()->unique()->bothify('##??-##??-##??'),
                    'internal_code' => fake()->unique()->bothify('##??-##??-##??'),
                    'category_id' => $this->getValue($categories, $productData['category']['slug'], 'category')->id,
                    'designer_id' => $this->getValue($designers, $productData['designer']['slug'], 'designer')->id,
                    'pricing_scheme_id' => $this->getValue($pricingSchemes, $productData['pricing_scheme']['4_days'] ?: 0, 'pricing scheme')->id,
                    'sale_type' => fake()->randomElement(ProductSaleTypes::cases()),
                    'desired' => false,
                    'sku' => fake()->lexify('sku-????????'),
                ]);

                // Create product code
                if ($productData['code'] != null) {
                    $product->marketplaceCode()->create([
                        'code' => $productData['code'],
                        'marketplace_id' => 1, //Liverpool
                    ]);
                }

                // Create images relationships
                $cameraPerspectives = collect([
                    1 => 'front',
                    2 => 'back',
                    3 => 'right',
                    4 => 'left',
                ]);

                foreach ($productData['image'] as $imageData) {
                    $product->images()->create([
                        'color_id' => $this->getValue($colors, $productData['color'], 'color')->id,
                        'camera_perspective' => $this->getValue($cameraPerspectives, $imageData['order'], 'camera perspective', true),
                        'src_image' => $imageData['src_image'],
                    ]);
                }

                // Create characteristics relationships
                foreach ($productData['characteristic'] as $characteristicData) {
                    CharacteristicProduct::create([
                        'product_id' => $product->id,
                        'characteristic_id' => $this->getValue($characteristics, $characteristicData['slug'], 'characteristic')->id,
                    ]);
                }

                // Create tags relationships
                foreach ($productData['tag'] as $tagData) {
                    ProductTag::create([
                        'product_id' => $product->id,
                        'tag_id' => $this->getValue($tags, $tagData['slug'], 'tag')->id,
                    ]);
                }
            } catch (Exception $e) {
                $this->errors[] = $productData['full_name'] . ': ' . $e->getMessage();
            }
        }
    }
}
