<?php

namespace Database\Seeders\Base;

use App\Models\Base\Sku;
use Database\Data\SkuData;
use Illuminate\Database\Seeder;

class SkuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = SkuData::getData();

        foreach ($data as $skuData) {
            Sku::create($skuData);
        }
    }
}
