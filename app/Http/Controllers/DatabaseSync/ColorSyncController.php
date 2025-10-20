<?php

namespace App\Http\Controllers\DatabaseSync;

use App\Http\Controllers\Common\BaseSyncController;
use App\Models\Base\Color;
use Exception;
use Illuminate\Support\{
    Collection,
    Str,
};
use InvalidArgumentException;

class ColorSyncController extends BaseSyncController
{

    public function __construct()
    {
        parent::__construct(config('services.conspiracy.api_base_url') . '/db-sync/color');
    }

    /**
     * Process the data fetched from the remote
     * 
     * @param Collection $data The fetched data to sync
     */
    protected function processData(Collection $data)
    {
        foreach ($data as $colorData) {
            try {
                if (in_array($colorData['name'], ['null'])) {
                    $colorData['name'] = 'Unknown';
                }

                $color = Color::create([
                    'name' => $colorData['name'],
                    'slug' => Str::slug($colorData['name']),
                    'hexadecimal' => $colorData['hexadecimal'] ?? '#FFFFFF',
                    'parent_color_id' => Color::where('slug', Str::slug($colorData['parent_color']))->first()?->id,
                ]);

                if ($colorData['code'] != null) {
                    $color->marketplaceCode()->create([
                        'code' => $colorData['code'],
                        'marketplace_id' => 1, //Liverpool
                    ]);
                }
            } catch (Exception $e) {
                $this->errors[] = $colorData['name'] . ': ' . $e->getMessage();
            }
        }
    }
}
