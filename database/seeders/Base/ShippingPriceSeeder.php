<?php

namespace Database\Seeders\Base;

use App\Models\Base\ShippingPrice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShippingPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ShippingPrice::factory()->create([
            'name' => 'Promoción Envío Gratis',
            'code' => 'ENVIO-GRATIS',
            'price' => 0,
        ]);

        if (!config('services.conspiracy.sync_enabled')) {
            // Seeding random data if the database should not be synced
            ShippingPrice::factory()->count(5)->create();
        }
    }
}
