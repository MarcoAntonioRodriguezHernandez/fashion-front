<?php

namespace App\Http\Controllers\DatabaseSync;

use App\Http\Controllers\Common\BaseSyncController;
use App\Models\Base\{
    Designer,
    DesignerProvider,
    Provider,
};
use App\Traits\Helpers\Support;
use Exception;
use Illuminate\Support\{
    Collection,
    Str,
};

class DesignerSyncController extends BaseSyncController
{

    use Support;

    public function __construct()
    {
        parent::__construct(config('services.conspiracy.api_base_url') . '/db-sync/designer');
    }

    /**
     * Process the data fetched from the remote
     * 
     * @param Collection $data The fetched data to sync
     */
    protected function processData(Collection $data)
    {
        $providers = Provider::select('id', 'slug')->get()->keyBy('slug');

        foreach ($data as $designerData) {
            try {
                $designer = Designer::create([
                    'name' => Str::title($designerData['name']),
                    'slug' => Str::slug($designerData['name']),
                ]);

                foreach ($designerData['providers'] as $providerData) {
                    DesignerProvider::create([
                        'designer_id' => $designer->id,
                        'provider_id' => $this->getValue($providers, $providerData, 'provider')->id,
                    ]);
                }
            } catch (Exception $e) {
                $this->errors[] = $designerData['name'] . ': ' . $e->getMessage();
            }
        }
    }
}
