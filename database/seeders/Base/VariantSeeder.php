<?php

namespace Database\Seeders\Base;

use App\Models\Base\Variant;
use Database\Data\ProductData;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = collect(ProductData::getData())
            ->map(fn ($p) => $p['images'])
            ->flatten(1)
            ->pluck('color_id')
            ->unique()
            ->filter()
            ->values();

        foreach ($colors as $color) {
            Variant::factory()->create([
                'color_id' => $color
            ]);
        }

        // Seeding random data
        Variant::factory()->times(20)->create();
    }
}
