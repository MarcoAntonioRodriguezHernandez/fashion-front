<?php

namespace App\Http\Controllers\DatabaseSync;

use App\Enums\{
    ItemIntegrities,
    SupplyStatuses,
};
use App\Http\Controllers\Common\BaseSyncController;
use App\Models\Base\{
    Item,
    Store,
    SuppliedItem,
    Supply,
    SupplyTransfer,
};
use App\Models\User;
use App\Traits\Helpers\Support;
use Exception;
use Illuminate\Support\{
    Collection,
    Str,
};

class SupplySyncController extends BaseSyncController
{

    use Support;

    public function __construct()
    {
        parent::__construct(config('services.conspiracy.api_base_url') . '/db-sync/supply');
    }

    /**
     * Process the data fetched from the remote
     * 
     * @param Collection $data The fetched data to sync
     */
    protected function processData(Collection $data)
    {
        $users = User::select('id', 'email')->get()->keyBy('email');
        $items = Item::select('id', 'serial_number')->get()->keyBy('serial_number');
        $stores = Store::select('id', 'name')
            ->get()
            ->map(fn ($s) => [
                'id' => $s->id,
                'slug' => Str::slug($s->name),
                'name' => $s->name,
            ])
            ->keyBy('slug');

        Supply::flushEventListeners(); // Prevent the boot events to be fired

        foreach ($data as $supplyData) {
            try {
                $supply = Supply::create([
                    'sender_id' => $this->getValue($users, $supplyData['sender_email'], 'user')->id,
                    'code' => Str::random(20),
                    'shipping_date' => $supplyData['shipping_date'] ?: date('Y-m-d'),
                    'status' => SupplyStatuses::COMPLETE->value,
                ]);

                foreach ($supplyData['transfers'] as $transferData) {
                    $destination = $this->getValue($stores, $transferData['destination_slug'], 'store');
                    $origin = $transferData['origin_slug'] ? $this->getValue($stores, $transferData['origin_slug'], 'store') : $stores->first();

                    $supplyTransfer = SupplyTransfer::create([
                        'supply_id' => $supply->id,
                        'recipient_id' => $this->getValue($users, $transferData['recipient_email'], 'user')->id,
                        'reception_date' => $transferData['reception_date'] ?: date('Y-m-d'),
                        'origin_id' => $origin['id'],
                        'destination_id' => $destination['id'],
                    ]);

                    foreach ($transferData['items'] as $itemData) {
                        SuppliedItem::create([
                            'supply_transfer_id' => $supplyTransfer->id,
                            'item_id' => $this->getValue($items, $itemData['item_code'], 'item')->id,
                            'delivered' => $itemData['delivered'],
                            'status' => SupplyStatuses::COMPLETE->value,
                            'integrity' => ItemIntegrities::HEALTHY->value,
                            'details' => $itemData['details'],
                        ]);
                    }
                }
            } catch (Exception $e) {
                $this->errors[] = 'Supply from ' . $supplyData['sender_email'] . ': ' . $e->getMessage();
            }
        }
    }
}
