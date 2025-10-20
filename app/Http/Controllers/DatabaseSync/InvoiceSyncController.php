<?php

namespace App\Http\Controllers\DatabaseSync;

use App\Enums\PaymentStatuses;
use App\Http\Controllers\Common\BaseSyncController;
use App\Models\Base\{
    Invoice,
    PaymentType,
    InvoiceFile,
};
use App\Models\User;
use Exception;
use Illuminate\Support\Collection;

class InvoiceSyncController extends BaseSyncController
{

    public function __construct()
    {
        parent::__construct(config('services.conspiracy.api_base_url') . '/db-sync/invoice');
    }

    /**
     * Process the data fetched from the remote
     * 
     * @param Collection $data The fetched data to sync
     */
    protected function processData(Collection $data)
    {
        $paymentTypes = PaymentType::select('id', 'slug')->get()->keyBy('slug');
        $users = User::select('id', 'name')->get()->keyBy('name');

        foreach ($data as $invoiceData) {
            try {
                $invoiceFile = $invoiceData['file'] ? InvoiceFile::create([
                    'file' => $invoiceData['file'],
                ]) : InvoiceFile::select('id')->get()->random();

                Invoice::create([
                    'invoice_number' => $invoiceData['invoice_number'],
                    'buyer' => $users->get($invoiceData['buyer'], $users->random())->id,
                    'payment_status' => match ($invoiceData['payment_status']) {
                        'PAGADO' => PaymentStatuses::APPROVED->value,
                        default => PaymentStatuses::PENDING->value,
                    },
                    'issuance_date' => $invoiceData['issuance_date'],
                    'payment_type_id' => $paymentTypes->get($invoiceData['payment_type'], $paymentTypes->first())->id,
                    'exchange_rate' => $invoiceData['exchange_rate'],
                    'invoice_file' => $invoiceFile->id,
                ]);
            } catch (Exception $e) {
                $this->errors[] = $invoiceData['invoice_number'] . ' - ' . $e->getMessage();
            }
        }
    }
}
