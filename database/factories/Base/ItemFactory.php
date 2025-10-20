<?php

namespace Database\Factories\Base;

use App\Enums\{
    ItemConditions,
    ItemIntegrities,
    ItemStatuses,
};
use App\Models\Base\{
    Invoice,
    ProductVariant,
    Store,
};
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Base\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $productVariant = ProductVariant::all()->random();
        $product = $productVariant->product;

        return [
            'product_variant_id' => $productVariant->id,
            'store_id' => Store::all()->random()->id,
            'serial_number' => Str::uuid(),
            'barcode' => fake()->numerify('#######'),
            'price_sale' => $product->full_price,
            'price_purchase' => $product->full_price * 0.8,
            'invoice_id' => Invoice::all()->random()->id,
            'status' => fake()->randomElement(ItemStatuses::cases()),
            'condition' => fake()->randomElement(ItemConditions::cases()),
            'integrity' => fake()->randomElement(ItemIntegrities::cases()),
            'details' => fake()->text(),
        ];
    }
}
