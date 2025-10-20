<?php

namespace Database\Seeders\Marketplace;

use App\Models\Marketplace\PaymentOrderMarketplace;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentOrderMarketplaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seeding random data
        PaymentOrderMarketplace::factory()->times(20)->create();
    }
}
