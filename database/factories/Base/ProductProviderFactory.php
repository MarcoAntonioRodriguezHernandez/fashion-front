<?php

namespace Database\Factories\Base;

use App\Models\Base\{
    Product,
    Provider,
};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Base\ProductProvider>
 */
class ProductProviderFactory extends Factory
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
            'provider_id' => Provider::all()->random()->id,
        ];
    }
}
