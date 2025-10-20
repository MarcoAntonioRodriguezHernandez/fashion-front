<?php

namespace Database\Factories\Marketplace;

use App\Enums\PaymentStatuses;
use App\Models\Base\PaymentType;
use App\Models\Marketplace\OrderMarketplace;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentOrderMarketplace>
 */
class PaymentOrderMarketplaceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_marketplace_id' => OrderMarketplace::all()->random()->id,
            'total' => fake()->randomFloat(2, 100, 4000),
            'payment' => fake()->randomFloat(2, 100, 4000),
            'payment_type_id' => PaymentType::all()->random()->id,
            'status' => fake()->randomElement(PaymentStatuses::cases()),
        ];
    }
}
