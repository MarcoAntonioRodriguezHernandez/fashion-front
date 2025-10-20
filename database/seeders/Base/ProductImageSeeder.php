<?php

namespace Database\Seeders\Base;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Base\ProductImage;


class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seeding random data
        ProductImage::factory()->times(20)->create();
    }
}
