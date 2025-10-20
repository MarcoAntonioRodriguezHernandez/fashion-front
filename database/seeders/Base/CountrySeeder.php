<?php

namespace Database\Seeders\Base;

use App\Models\Base\Country;
use Database\Data\CountryData;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = CountryData::getData();

        foreach ($data as $countryData) {
            Country::create($countryData);
        }
    }
}
