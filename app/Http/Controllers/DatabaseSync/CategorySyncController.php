<?php

namespace App\Http\Controllers\DatabaseSync;

use App\Enums\CategoryStatuses;
use App\Http\Controllers\Common\BaseSyncController;
use App\Models\Base\Category;
use Exception;
use Illuminate\Support\{
    Collection,
    Str,
};

class CategorySyncController extends BaseSyncController
{

    public function __construct()
    {
        parent::__construct(config('services.conspiracy.api_base_url') . '/db-sync/category');
    }


    /**
     * Process the data fetched from the remote
     * 
     * @param Collection $data The fetched data to sync
     */
    protected function processData(Collection $data)
    {
        foreach ($data as $categoryData) {
            try {
                $category = Category::create([
                    'name' => $categoryData['name'],
                    'slug' => Str::slug($categoryData['name']),
                    'status' => CategoryStatuses::ACTIVE,
                ]);

                if ($categoryData['code'] != null) {
                    $category->marketplaceCode()->create([
                        'code' => $categoryData['code'],
                        'marketplace_id' => 1, //Liverpool
                    ]);
                }
            } catch (Exception $e) {
                $this->errors[] = $categoryData['name'] . ': ' . $e->getMessage();
            }
        }
    }
}
