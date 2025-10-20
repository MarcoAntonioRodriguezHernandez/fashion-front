<?php

namespace Database\Factories\Base;

use App\Enums\ProductSaleTypes;
use App\Models\Base\{
    Category,
    Designer,
    PricingScheme,
};
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(1, true);
        $title = fake()->unique()->words(3, true);

        return [
            'name' => $name,
            'title' => $title,
            'slug' => Str::slug($name),
            'description' => fake()->text(),
            'full_price' => fake()->numberBetween(90, 1500) * 10,
            'origin_code' => fake()->unique()->bothify('##??-##??-##??'),
            'internal_code' => fake()->unique()->bothify('##??-##??-##??'),
            'category_id' => Category::all()->random()->id,
            'designer_id' => Designer::all()->random()->id,
            'pricing_scheme_id' => PricingScheme::all()->random()->id,
            'sale_type' => fake()->randomElement(ProductSaleTypes::cases()),
            'desired' => fake()->boolean(),
            'sku' => fake()->lexify('sku-????????'),
        ];
    }
}
