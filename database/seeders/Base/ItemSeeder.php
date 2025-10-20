<?php

namespace Database\Seeders\Base;

use App\Models\Base\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seeding random data
        Item::factory()->times(60)->create();
    }
}
