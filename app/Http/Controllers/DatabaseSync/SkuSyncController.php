<?php

namespace App\Http\Controllers\DatabaseSync;

use App\Http\Controllers\Common\BaseSyncController;
use App\Models\Base\Sku;
use Exception;
use Illuminate\Support\Collection;

class SkuSyncController extends BaseSyncController
{

    public function __construct()
    {
        parent::__construct(config('services.conspiracy.api_base_url') . '/db-sync/sku');
    }

    /**
     * Process the data fetched from the remote
     * 
     * @param Collection $data The fetched data to sync
     */
    protected function processData(Collection $data)
    {
        foreach ($data as $skuData) {
            try {
                $sku = Sku::create([
                    'sku' => $skuData['sku'],
                    'name' => $skuData['name'],
                    'description' => $skuData['description'],
                    'duration' => $skuData['duration'],
                    'price' => $skuData['price'],
                ]);

                if ($skuData['code']  != null) {
                    $sku->marketplaceCode()->create([
                        'code' => $skuData['code'],
                        'marketplace_id' => 1, // Liverpool
                    ]);
                }
            } catch (Exception $e) {
                $this->errors[] = $skuData['name'] . ': ' . $e->getMessage();
            }
        }
    }
}
