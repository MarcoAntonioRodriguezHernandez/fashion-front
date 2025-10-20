<?php

namespace App\Http\Controllers\DatabaseSync;

use App\Http\Controllers\Common\BaseSyncController;
use App\Models\Base\Tag;
use Exception;
use Illuminate\Support\{
    Collection,
    Str,
};

class TagSyncController extends BaseSyncController
{

    public function __construct()
    {
        parent::__construct(config('services.conspiracy.api_base_url') . '/db-sync/tag');
    }

    /**
     * Process the data fetched from the remote
     * 
     * @param Collection $data The fetched data to sync
     */
    protected function processData(Collection $data)
    {
        foreach ($data as $tag) {
            try {
                Tag::create([
                    'name' => $tag['name'],
                    'slug' => Str::slug($tag['name']),
                ]);
            } catch (Exception $e) {
                $this->errors[] = $tag['name'] . ': ' . $e->getMessage();
            }
        }
    }
}
