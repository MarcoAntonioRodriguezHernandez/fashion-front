<?php

namespace Database\Seeders\Base;

use App\Models\Base\CharacteristicProduct;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CharacteristicProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seeding random data
        CharacteristicProduct::factory()->times(60)->create();
    }
}
