<?php

namespace Database\Seeders\Base;

use App\Models\Base\SupplyTransfer;
use Illuminate\Database\Seeder;

class SupplyTransferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seeding random data
        SupplyTransfer::factory()->times(25)->create();
    }
}
