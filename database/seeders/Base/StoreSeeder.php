<?php

namespace Database\Seeders\Base;

use App\Enums\{
    StoreStatuses,
    StoreTypes
};
use Illuminate\Database\Seeder;
use App\Models\Base\Store;
use Illuminate\Support\Str;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Store::factory()->create([
            'name' => 'General',
            'slug' => 'general',
            'store_type' => StoreTypes::STORE,
            'status' => StoreStatuses::NOT_VISIBLE,
        ]);

        if (!config('services.conspiracy.sync_enabled')) {
            // Seeding random data if the database should not be synced
            foreach ($this->data as $storeData) {
                Store::factory()->create([
                    'name' => $storeData['name'],
                    'slug' => Str::slug($storeData['name']),
                    'lat' => $storeData['lat'],
                    'long' => $storeData['long'],
                    'status' => StoreStatuses::ACTIVE,
                ]);
            }
        }
    }

    protected $data = [
        ['name' => 'Almacen', 'lat' => '19.433667810440596', 'long' => '-99.18261180455588'],
        ['name' => 'Online Store', 'lat' => '19.656894198423568', 'long' => '-99.13087062109846'],
        ['name' => 'Liverpool Polanco', 'lat' => '19.433667810440596', 'long' => '-99.18261180455588'],
        ['name' => 'Liverpool Insurgentes', 'lat' => '19.3727853383443', 'long' => '-99.1786134045573'],
        ['name' => 'Liverpool Queretaro antea', 'lat' => '20.673071452032254', 'long' => '-100.43604846556275'],
        ['name' => 'Liverpool Lindavista', 'lat' => '19.48619123544766', 'long' => '-99.13099677251658'],
        ['name' => 'Liverpool Pachuca', 'lat' => '20.09746979967783', 'long' => '-98.76859882433732'],
        ['name' => 'Liverpool Perisur', 'lat' => '19.304242214951337', 'long' => '-99.18826175905707'],
        ['name' => 'Liverpool Satelite', 'lat' => '19.51184600719569', 'long' => '-99.23364329104525'],
        ['name' => 'Liverpool MTY Valle', 'lat' => '25.63902645699459', 'long' => '-100.3142404178743'],
        ['name' => 'Liverpool Puebla', 'lat' => '644.80438', 'long' => '154.19816'],
        ['name' => 'Liverpool MTY Galerias', 'lat' => '436.14672', 'long' => '1551.21573'],
    ];
}
