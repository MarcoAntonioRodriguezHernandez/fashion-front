<?php

namespace App\Http\Controllers\DatabaseSync;

use App\Enums\{
    FoundByMethods,
    ItemConditions,
    ItemStatuses,
    OrderStatuses,
    OrderSaleType,
    PaymentStatuses,
};
use App\Http\Controllers\Common\BaseSyncController;
use App\Models\Base\{
    Item,
    PaymentType,
    Store,
};
use App\Models\Marketplace\{
    ItemOrderMarketplace,
    OrderMarketplace,
    PaymentOrderMarketplace,
    RentDetailMarketplace,
};
use App\Models\User;
use App\Traits\Helpers\Support;
use Exception;
use Illuminate\Support\{
    Collection,
    Str,
};

class OrderSyncController extends BaseSyncController
{

    use Support;

    public function __construct()
    {
        parent::__construct(config('services.conspiracy.api_base_url') . '/db-sync/order');
    }

    /**
     * Process the data fetched from the remote
     * 
     * @param Collection $data The fetched data to sync
     */
    protected function processData(Collection $data)
    {
        $clients = User::select('id', 'email')->get()->keyBy('email');
        $items = Item::select('id', 'serial_number', 'product_variant_id', 'condition', 'price_sale')->get()->keyBy('serial_number');
        $paymentTypes = PaymentType::select('id', 'slug')->get()->keyBy('slug');
        $stores = Store::select('id', 'name')
            ->get()
            ->map(fn($s) => [
                'id' => $s->id,
                'slug' => Str::slug($s->name),
                'name' => $s->name,
            ])
            ->keyBy('slug');

        OrderMarketplace::unguard();
        ItemOrderMarketplace::unguard();
        RentDetailMarketplace::unguard();
        PaymentOrderMarketplace::unguard();

        foreach ($data as $orderData) {
            try {
                $order = OrderMarketplace::create([
                    'code' => $orderData['code'] ?? Str::random(10),
                    'client_id' => $this->getValue($clients, $orderData['client_email'], 'client')->id,
                    'employee_id' => User::employees()->first()->id,
                    'marketplace_id' => 1, // Liverpool
                    'amount' => collect($orderData['items'])->sum('price'),
                    'discount' => 0,
                    'surcharge' => 0,
                    'store_id' => $this->getValue($stores, $orderData['store'], 'store')['id'],
                    'number_products' => count($orderData['items']),
                    'status' => OrderStatuses::TO_VALIDATE,
                    'date_canceled' => null,
                    'found_by' => $orderData['found_by'] ?? FoundByMethods::UNSPECIFIED->value,
                    'created_at' => $orderData['created_at'],
                    'updated_at' => $orderData['created_at'],
                ]);

                // Create items relations
                foreach ($orderData['items'] as $item) {
                    if (!$items->has($item['item_code'])) { // If the item doesn't exist
                        $this->errors[] = 'Order for ' . $orderData['client_email'] . ': Item (' . $item['item_code'] . ') doesn\'t exists';

                        continue;
                    }

                    $itemRecord = $this->getValue($items, $item['item_code'], 'item');
                    $saleType = -1;

                    switch ($item['sale_type']) {
                        case 'sale':
                            $saleType = OrderSaleType::SALE;

                            $itemRecord->update([
                                'status' => ItemStatuses::SOLD,
                            ]);
                            break;
                        case 'rent':
                            $saleType = OrderSaleType::RENT;

                            $itemRecord->update([
                                'condition' => max($itemRecord->condition, ItemConditions::PRE_LOVED->value), // Update to pre-loved or higher value
                            ]);
                            break;
                    }

                    $itemOrder = ItemOrderMarketplace::create([
                        'item_id' => $itemRecord->id,
                        'order_marketplace_id' => $order->id,
                        'sale_type' => $saleType,
                        'status' => $item['paid'],
                        'additional_notes' => $item['additional_notes'],
                        'created_at' => $orderData['created_at'],
                        'updated_at' => $orderData['created_at'],
                    ]);

                    if ($saleType == OrderSaleType::RENT) {
                        RentDetailMarketplace::create([
                            'item_order_marketplace_id' => $itemOrder->id,
                            'date_start' => $item['date_start'],
                            'date_end' => $item['date_end'],
                            'description' => '',
                            'status' => $item['paid'],
                            'created_at' => $orderData['created_at'],
                            'updated_at' => $orderData['created_at'],
                        ]);
                    }
                }

                // Create payments relations
                foreach ($orderData['payments'] as $payment) {
                    PaymentOrderMarketplace::create([
                        'order_marketplace_id' => $order->id,
                        'total' => $payment['amount'],
                        'payment' => $payment['amount'],
                        'payment_type_id' => $this->getValue($paymentTypes, $payment['payment_type'], 'payment type')->id,
                        'status' => PaymentStatuses::APPROVED,
                        'created_at' => $orderData['created_at'],
                        'updated_at' => $orderData['created_at'],
                    ]);
                }
            } catch (Exception $e) {
                $this->errors[] = 'Order for ' . $orderData['client_email'] . ': ' . $e->getMessage();
            }
        }

        OrderMarketplace::reguard();
        ItemOrderMarketplace::reguard();
        RentDetailMarketplace::reguard();
        PaymentOrderMarketplace::reguard();
    }
}
