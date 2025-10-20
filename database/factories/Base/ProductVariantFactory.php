<?php

namespace Database\Factories\Base;

use App\Models\Base\{
    Product,
    Variant,
};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Base\ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $product = Product::all()->random();
        $image = $product->images->random();
        $variant = Variant::where('color_id', $image->color_id)->get()->random();

        return [
            'product_image_id' => $image->id,
            'product_id' => $product->id,
            'variant_id' => $variant->id,
        ];
    }
}
