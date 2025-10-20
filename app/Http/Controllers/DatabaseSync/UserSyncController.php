<?php

namespace App\Http\Controllers\DatabaseSync;

use App\Http\Controllers\Common\BaseSyncController;
use App\Models\Base\Store;
use App\Models\User;
use App\Traits\Helpers\Support;
use Exception;
use Illuminate\Support\{
    Collection,
    Str,
};

class UserSyncController extends BaseSyncController
{

    use Support;

    public function __construct()
    {
        parent::__construct(config('services.conspiracy.api_base_url') . '/db-sync/user');
    }

    /**
     * Process the data fetched from the remote
     * 
     * @param Collection $data The fetched data to sync
     */
    protected function processData(Collection $data)
    {
        $stores = Store::select('id', 'name')
            ->get()
            ->map(fn ($s) => [
                'id' => $s->id,
                'slug' => Str::slug($s->name),
                'name' => $s->name,
            ])
            ->keyBy('slug');

        foreach ($data as $userData) {
            try {
                $user = new User();

                $user->setRawAttributes([
                    'name' => $userData['name'] ?: '',
                    'last_name' => $userData['last_name'] ?: '',
                    'password' => $userData['password'] ?: '',
                    'email' => $userData['email'] ?: '',
                    'phone' => $userData['phone'] ?: null,
                    'status' => true,
                ]);

                $user->save();

                if ($userData['employee_detail'] ?? false) {
                    $user->employeeDetail()->create([
                        'store_id' => $this->getValue($stores, $userData['employee_detail']['store'], 'store')['id'],
                    ]);
                }

                if ($userData['client_detail'] ?? false) {
                    $user->clientDetail()->create([
                        'date_of_birth' => null,
                        'gender' => $userData['client_detail']['gender'],
                        'credit' => 0,
                    ]);

                    if ($userData['client_detail']['zip_code']) {
                        $user->userAddresses()->create([
                            'interior_number' => null,
                            'external_number' => null,
                            'street' => $userData['client_detail']['street'] ?: 'Sin Especificar',
                            'colony' => $userData['client_detail']['colony'] ?: 'Sin Especificar',
                            'city' => $userData['client_detail']['city'] ?: 'Sin Especificar',
                            'state' => $userData['client_detail']['state'] ?: 'Sin Especificar',
                            'zip_code' => $userData['client_detail']['zip_code'],
                        ]);
                    }
                }
            } catch (Exception $e) {
                $this->errors[] = $userData['email'] . ': ' . $e->getMessage();
            }
        }
    }
}
