<?php

namespace Database\Seeders\Base;

use App\Models\Base\MarketplaceCode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MarketplaceCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seeding random data
        MarketplaceCode::factory()->times(100)->create();
    }
}
