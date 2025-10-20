<?php

namespace Database\Factories\Base;

use App\Models\Base\Coupon;
use App\Models\Marketplace\OrderMarketplace;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CouponOrderMarketplaceFactory extends Factory
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
            'coupon_id' => Coupon::all()->random()->id,
            'user_id' => User::all()->random()->id,
        ];
    }
}
