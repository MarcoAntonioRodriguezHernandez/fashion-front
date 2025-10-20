<?php

namespace Database\Seeders\Marketplace;

use App\Models\Marketplace\RentDetailMarketplace;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RentDetailMarketplaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seeding random data
        RentDetailMarketplace::factory()->times(20)->create();
    }
}
