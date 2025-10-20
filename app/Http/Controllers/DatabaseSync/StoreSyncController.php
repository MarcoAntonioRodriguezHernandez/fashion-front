<?php

namespace App\Http\Controllers\DatabaseSync;

use App\Enums\{
    StoreStatuses,
    StoreTypes,
};
use App\Http\Controllers\Common\BaseSyncController;
use App\Models\Base\Store;
use Illuminate\Support\{
    Collection,
    Str,
};

class StoreSyncController extends BaseSyncController
{

    public function __construct()
    {
        parent::__construct(config('services.conspiracy.api_base_url') . '/db-sync/store');
    }

    /**
     * Process the data fetched from the remote
     * 
     * @param Collection $data The fetched data to sync
     */
    protected function processData(Collection $data)
    {
        foreach ($data as $store) {
            Store::create([
                'name' => Str::title($store['name']),
                'slug' => Str::slug($store['name']),
                'code' => fake()->bothify('##??##?#'),
                'lat' => fake()->randomFloat(5, 100, 2000),
                'long' => fake()->randomFloat(5, 100, 2000),
                'cp' => fake()->numerify('#####'),
                'address' => fake()->address(),
                'municipality' => fake()->words(5, true),
                'store_type' => fake()->randomElement([StoreTypes::SHOP, StoreTypes::STORE]),
                'status' => StoreStatuses::ACTIVE,
            ]);
        }
    }
}
