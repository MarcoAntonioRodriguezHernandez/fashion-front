<?php

namespace Database\Seeders\Base;

use App\Models\Base\{
    PricingScheme,
    Sku,
};
use Database\Data\PricingSchemeData;
use Illuminate\Database\Seeder;

class PricingSchemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sku4 = Sku::factory()->create([
            'sku' => '0000000004',
            'name' => '4 DÍAS NO DEFINIDO',
            'description' => '4 DÍAS NO DEFINIDO',
            'duration' => 4,
            'price' => 0,
        ]);
        $sku8 = Sku::factory()->create([
            'sku' => '0000000008',
            'name' => '8 DÍAS NO DEFINIDO',
            'description' => '8 DÍAS NO DEFINIDO',
            'duration' => 8,
            'price' => 0,
        ]);

        PricingScheme::factory()
            ->create([
                'sku_4_id' => $sku4,
                'sku_8_id' => $sku8,
                'msrp' => 0,
            ]);

        if (!config('services.conspiracy.sync_enabled')) {
            // Seeding predefined data if the database should not be synced
            $data = PricingSchemeData::getData();

            foreach ($data as $pricingSchemeData) {
                PricingScheme::factory()->create($pricingSchemeData);
            }
        }
    }
}
