<?php

namespace App\Http\Controllers\DatabaseSync;

use App\Http\Controllers\Common\BaseSyncController;
use App\Models\Base\Characteristic;
use Exception;
use Illuminate\Support\{
    Collection,
    Str,
};

class CharacteristicSyncController extends BaseSyncController
{

    public function __construct()
    {
        parent::__construct(config('services.conspiracy.api_base_url') . '/db-sync/characteristic', ['withKeys' => true]);
    }


    /**
     * Process the data fetched from the remote
     * 
     * @param Collection $data The fetched data to sync
     */
    protected function processData(Collection $data)
    {
        foreach ($data as $parentName => $charGroup) {
            // Create parent characteristic
            $parent = Characteristic::create([
                'name' => $parentName,
                'slug' => Str::slug($parentName),
            ]);

            foreach ($charGroup as $charData) {
                try {
                    Characteristic::create([
                        'name' => $charData['name'],
                        'slug' => Str::slug($charData['name']),
                        'parent_characteristic_id' => $parent->id,
                    ]);
                } catch (Exception $e) {
                    $this->errors[] = $parentName . ': ' . $e->getMessage();
                }
            }
        }
    }
}
