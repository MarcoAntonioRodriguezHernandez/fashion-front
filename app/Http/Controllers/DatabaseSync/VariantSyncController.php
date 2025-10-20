<?php

namespace App\Http\Controllers\DatabaseSync;

use App\Http\Controllers\Common\BaseSyncController;
use App\Models\Base\{
    Color,
    Size,
    Variant,
};
use App\Traits\Helpers\Support;
use Exception;
use Illuminate\Support\Collection;

class VariantSyncController extends BaseSyncController
{
    
    use Support;

    public function __construct()
    {
        parent::__construct(config('services.conspiracy.api_base_url') . '/db-sync/variant');
    }

    /**
     * Process the data fetched from the remote
     * 
     * @param Collection $data The fetched data to sync
     */
    protected function processData(Collection $data)
    {
        $colors = Color::select('id', 'slug')->get()->keyBy('slug');
        $sizes = Size::select('id', 'slug')->get()->keyBy('slug');

        foreach ($data as $variantData) {
            try {
                $variant = Variant::create([
                    'size_id' => $this->getValue($sizes, $variantData['size'], 'size')->id,
                    'color_id' => $this->getValue($colors, $variantData['color'], 'color')->id,
                    'code' => $variantData['size'] . '-' . $variantData['color'],
                    'status' => true,
                ]);

                if ($variantData['code'] != null) {
                    $variant->marketplaceCode()->create([
                        'code' => $variantData['code'],
                        'marketplace_id' => 1, //Liverpool
                    ]);
                }
            } catch (Exception $e) {
                $this->errors[] = $variantData['code'] . ': ' . $e->getMessage();
            }
        }
    }
}
