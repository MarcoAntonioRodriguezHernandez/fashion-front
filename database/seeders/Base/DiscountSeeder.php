<?php

namespace Database\Seeders\Base;

use App\Models\Base\Discount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seeding random data
        Discount::factory()->times(20)->create();
    }
}
