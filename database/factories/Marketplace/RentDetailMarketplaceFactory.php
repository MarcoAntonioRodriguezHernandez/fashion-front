<?php

namespace Database\Factories\Marketplace;

use App\Models\Marketplace\ItemOrderMarketplace;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RentDetailMarketplace>
 */
class RentDetailMarketplaceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTime()->format('Y-m-d');
        $finishDate = date('Y-m-d', strtotime($startDate . ' +5 days'));

        return [
            'item_order_marketplace_id' => ItemOrderMarketplace::all()->random()->id,
            'date_start' => $startDate,
            'date_end' => $finishDate,
            'insurance_price' => fake()->randomElement([null, 95]),
            'description' => fake()->text(),
            'status' => 1,
        ];
    }
}
