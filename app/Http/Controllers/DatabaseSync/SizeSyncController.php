<?php

namespace App\Http\Controllers\DatabaseSync;

use App\Http\Controllers\Common\BaseSyncController;
use App\Models\Base\Size;
use Exception;
use Illuminate\Support\{
    Collection,
    Str,
};

class SizeSyncController extends BaseSyncController
{

    public function __construct()
    {
        parent::__construct(config('services.conspiracy.api_base_url') . '/db-sync/size');
    }

    /**
     * Process the data fetched from the remote
     * 
     * @param Collection $data The fetched data to sync
     */
    protected function processData(Collection $data)
    {
        foreach ($data as $sizeData) {
            try {
                $size = Size::create([
                    'name' => $sizeData['name'],
                    'slug' => Str::slug($sizeData['full_name']),
                    'number' => $sizeData['number'],
                    'hex_color' => $sizeData['color'],
                    'status' => $sizeData['status'],
                ]);

                if ($sizeData['code'] != null) {
                    $size->marketplaceCode()->create([
                        'code' => $sizeData['code'],
                        'marketplace_id' => 1, //Liverpool
                    ]);
                }
            } catch (Exception $e) {
                $this->errors[] = $sizeData['name'] . ': ' . $e->getMessage();
            }
        }
    }
}
