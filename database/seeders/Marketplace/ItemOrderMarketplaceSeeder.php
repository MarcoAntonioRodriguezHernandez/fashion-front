<?php

namespace Database\Seeders\Marketplace;

use App\Models\Marketplace\ItemOrderMarketplace;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemOrderMarketplaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seeding random data
        ItemOrderMarketplace::factory()->times(20)->create();
    }
}
