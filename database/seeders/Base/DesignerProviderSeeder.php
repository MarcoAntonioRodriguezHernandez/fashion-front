<?php

namespace Database\Seeders\Base;

use App\Models\Base\DesignerProvider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DesignerProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seeding random data
        DesignerProvider::factory()->times(60)->create();
    }
}
