<?php

namespace App\Http\Controllers\DatabaseSync;

use App\Http\Controllers\Common\BaseSyncController;
use App\Models\Base\{
    Provider,
    Country,
};
use App\Traits\Helpers\Support;
use Exception;
use Illuminate\Support\{
    Collection,
    Str,
};

class ProviderSyncController extends BaseSyncController
{

    use Support;

    public function __construct()
    {
        parent::__construct(config('services.conspiracy.api_base_url') . '/db-sync/provider');
    }

    /**
     * Process the data fetched from the remote
     * 
     * @param Collection $data The fetched data to sync
     */
    protected function processData(Collection $data)
    {
        $countries = Country::select('id', 'code')->get()->keyBy('code');

        foreach ($data as $providerData) {
            try {
                $countryCode = match ($providerData['country']) {
                    'Estados Unidos' => 'USA',
                    'United States' => 'USA',
                    default => $providerData['country'],
                };

                $countryCode = strtoupper(substr($countryCode, 0, 3));

                if (strlen($countryCode) != 3) {
                    $countryCode = 'UNK';
                }

                Provider::create([
                    'name' => $providerData['name'] ?: 'Sin Datos',
                    'slug' => Str::slug($providerData['name']) ?: 'Sin Datos',
                    'contact' => $providerData['contact'] ?: 'Sin Datos',
                    'email' => $providerData['email'] ?: 'Sin Datos',
                    'phone' => $providerData['phone'] ?: 'Sin Datos',
                    'url' => $providerData['url'] ?: 'Sin Datos',
                    'country_id' => $this->getValue($countries, $countryCode, 'country')->id,
                ]);
            } catch (Exception $e) {
                $this->errors[] = $providerData['name'] . ': ' . $e->getMessage();
            }
        }
    }
}
