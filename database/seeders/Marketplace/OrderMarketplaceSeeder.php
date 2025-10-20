<?php

namespace Database\Seeders\Marketplace;

use App\Models\Base\Event;
use App\Models\Marketplace\{
    OrderMarketplace,
    OrderShippingMarketplace,
};
use Illuminate\Database\Seeder;

class OrderMarketplaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seeding random data
        OrderMarketplace::factory()
            ->for(Event::factory())
            ->count(10)
            ->create();

        OrderMarketplace::factory()
            ->for(Event::factory())
            ->has(OrderShippingMarketplace::factory())
            ->count(10)
            ->create();
    }
}
