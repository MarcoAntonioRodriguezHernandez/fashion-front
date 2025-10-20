<?php

namespace Database\Seeders\Base;

use App\Models\Base\SuppliedItem;
use Illuminate\Database\Seeder;

class SuppliedItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seeding random data
        SuppliedItem::factory()->times(90)->create();
    }
}
