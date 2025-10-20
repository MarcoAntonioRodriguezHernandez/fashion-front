<?php

namespace Database\Seeders\Base;

use App\Models\Base\ProductVariant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seeding random data
        ProductVariant::factory()->times(20)->create();
    }
}
