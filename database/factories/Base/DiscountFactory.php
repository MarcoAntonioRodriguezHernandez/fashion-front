<?php

namespace Database\Factories\Base;

use App\Models\Marketplace\OrderMarketplace;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Base\Discount>
 */
class DiscountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $order = OrderMarketplace::all()->random();

        return [
            'order_marketplace_id' => $order->id,
            'reason' => fake()->sentence(),
            'amount' => fake()->numberBetween(0, $order->amount_total),
        ];
    }
}
