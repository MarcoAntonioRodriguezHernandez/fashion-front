<?php

namespace App\Http\Controllers\DatabaseSync;

use App\Http\Controllers\Common\BaseSyncController;
use App\Models\Base\PaymentType;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PaymentTypeSyncController extends BaseSyncController
{

    public function __construct()
    {
        parent::__construct(config('services.conspiracy.api_base_url') . '/db-sync/payment-type');
    }

    /**
     * Process the data fetched from the remote
     * 
     * @param Collection $data The fetched data to sync
     */
    protected function processData(Collection $data)
    {

        foreach ($data as $paymentTypeData) {
            try {
                PaymentType::create([
                    'name' => $paymentTypeData['name'],
                    'slug'=> Str::slug($paymentTypeData['name']),
                ]);
            } catch (Exception $e) {
                $this->errors[] = $paymentTypeData['name'] . ': ' . $e->getMessage();
            }
        }
    }
}
