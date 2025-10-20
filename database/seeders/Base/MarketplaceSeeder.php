<?php

namespace Database\Seeders\Base;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Base\Marketplace;
use Illuminate\Support\Str;

class MarketplaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach($this->name as $name => $syncEnabled) {
            Marketplace::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'sync_enabled' => $syncEnabled,
                'url' => fake()->url(),
            ]);
        }
    }

    private $name=[
        'Liverpool' => false,
        'Shopify' => true,
        'Conspiracion' => false,
    ];
}
