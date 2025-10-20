<?php

namespace Database\Factories\Marketplace;

use App\Models\Base\{
    ShippingPrice,
    UserAddress,
};
use App\Models\Marketplace\OrderMarketplace;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Marketplace\OrderShippingMarketplace>
 */
class OrderShippingMarketplaceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'shipping_price_id' => ShippingPrice::all()->random()->id,
            'order_marketplace_id' => OrderMarketplace::all()->random()->id,
            'user_address_id' => UserAddress::all()->random()->id,
            'status' => true,
        ];
    }
}
