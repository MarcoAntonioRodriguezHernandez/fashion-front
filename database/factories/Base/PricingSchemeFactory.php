<?php

namespace Database\Factories\Base;

use App\Models\Base\{
    Sku,
    Category
};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Base\PricingScheme>
 */
class PricingSchemeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sku_4_id' => Sku::factory(), 
            'sku_8_id' => Sku::factory(), 
            'category_id' => Category::all()->random()->id,
            'msrp' => fake()->randomFloat(2, 10, 1000),
            'increase' => fake()->randomFloat(2, 0, 100), 
        ];
    }
}
