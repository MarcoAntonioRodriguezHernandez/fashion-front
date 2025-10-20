<?php

namespace Database\Factories\Base;

use App\Models\Base\{
    Product,
    Variant
};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductImage>
 */
class ProductImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::all()->random()->id,
            'color_id' => Variant::all()->random()->color_id,
            'src_image' => fake()->imageUrl(),
            'camera_perspective' => fake()->randomElement(['front', 'left', 'right', 'back']),
        ];
    }
}
