<?php

namespace Database\Seeders\Base;

use App\Models\Base\ProductTag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seeding random data
        ProductTag::factory()->times(20)->create();
    }
}
