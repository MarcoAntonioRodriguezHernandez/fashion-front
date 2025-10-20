<?php

namespace Database\Seeders\Base;


use App\Models\Base\Supply;
use Illuminate\Database\Seeder;

class SupplySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seeding random data
        Supply::factory()->times(10)->create();
    }
}
