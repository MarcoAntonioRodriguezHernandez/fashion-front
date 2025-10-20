<?php

namespace Database\Factories\Marketplace;

use App\Enums\OrderSaleType;
use App\Models\Base\Item;
use App\Models\Marketplace\{
    OrderMarketplace,
};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Marketplace\ItemOrderMarketplace>
 */
class ItemOrderMarketplaceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fittingPrice = fake()->randomElement([null, fake()->numberBetween(100, 500)]);

        $item = Item::all()->random();

        return [
            'item_id' => $item->id,
            'order_marketplace_id' => OrderMarketplace::all()->random()->id,
            'additional_notes' => fake()->text(),
            'item_price' => $item->price_sale,
            'fitting_price' => $fittingPrice,
            'fitting_notes' => $fittingPrice ? fake()->text() : null,
            'sale_type' => fake()->randomElement([OrderSaleType::SALE, OrderSaleType::RENT]),
            'status' => 1,
        ];
    }
}
