<?php

namespace App\Traits\Marketplace\Order;

use App\Enums\OrderSaleType;
use App\Models\{
    Base\PricingScheme,
    Marketplace\OrderMarketplace,
    Marketplace\RentDetailMarketplace
};
use Carbon\Carbon;
use Illuminate\Support\Collection;

trait FiltersOrders
{

    public function filterOrdersBy(object $filters, int|bool $pageSize = null)
    {
        return OrderMarketplace::with(['client:id,name,last_name,email,phone', 'store:id,name'])
            ->when($filters->order_code ?? false, fn($q) => $q->where('code', $filters->order_code))
            ->when($filters->order_store_id ?? false, fn($q) => $q->where('store_id', $filters->order_store_id))
            ->when($filters->client_email ?? false, fn($q) => $q->whereHas('client', fn($q) => $q->where('email', 'LIKE', '%' . $filters->client_email . '%')))
            ->when($filters->client_name ?? false, fn($q) => $q->whereHas('client', fn($q) => $q->whereRaw("CONCAT(name, ' ', last_name) LIKE ?", '%' . $filters->client_name . '%')))
            ->when($filters->product_name ?? false, fn($q) => $q->whereHas('itemOrders.item.product', fn($q) => $q->where('name', $filters->product_name )))
            ->where(
                fn($q) => $q
                    ->when(($filters->sale_type ?: OrderSaleType::RENT->value) == OrderSaleType::RENT->value,
                        fn($q) => $q->whereHas(
                            'itemOrders',
                            fn($q) => $q->where('sale_type', OrderSaleType::RENT->value)
                                ->whereHas(
                                    'rentDetailsMarketplace',
                                    fn($q) => $q->where('date_start', '<=', $filters->order_finish_date)
                                        ->where('date_end', '>=', $filters->order_start_date)
                                )
                        )
                    )
                    ->when(($filters->sale_type ?: OrderSaleType::SALE->value) == OrderSaleType::SALE->value,
                        fn($q) => $q->orWhereHas(
                            'itemOrders',
                            fn($q) => $q->where('sale_type', OrderSaleType::SALE->value)
                                ->where('created_at', '>=', $filters->order_start_date)
                                ->where('created_at', '<=', $filters->order_finish_date)
                        )
                    )
            )
            ->get()
            ->pipe(fn($c) => $this->normalizeData($c, $pageSize));
    }

    protected function normalizeData(Collection $data, int|bool $pageSize = null)
    {
        return $data
            ->when($pageSize === false, fn($c) => $c->map(fn(OrderMarketplace $o) => $this->buildOrderData($o)))
            ->when(
                $pageSize !== false,
                fn($c) => $c->paginate($pageSize)->through(fn(OrderMarketplace $o) => $this->buildOrderData($o))
            );
    }

    private function groupDataItems($itemOrders): array
    {
        $data = [];
        foreach ($itemOrders as $item) {
            $data[] = [
                'name' => $item->item->product->name,
                'designer' => $item->item->product->designer->name,
                'size' => $item->item->variant->size->full_name,
                'color' => $item->item->variant->color?->name,
                'store' => $item->item->store->name,
                'sale_type' => OrderSaleType::getAllNames()[$item->sale_type],
                'image' => $item->item->product->firstImage->src_image ?? asset('media/misc/image.png'),
                ...$item->sale_type != OrderSaleType::SALE->value ?
                    $this->getRentDetailsMarketplace($item->rentDetailsMarketplace, $item->item->product->pricingScheme) :
                    [
                        'full_price' => $item->item->price_sale,
                        'date_start' => 'No aplica',
                        'date_end' => 'No aplica'
                    ],
            ];
        }
        return $data;
    }

    private function getRentDetailsMarketplace(?RentDetailMarketplace $details, PricingScheme $pricingScheme): array
    {
        if ($details === null) {
            return [
                'date_start' => 'N/A',
                'date_end' => 'N/A',
                'full_price' => 'N/A',
            ];
        } else {
            return [
                'date_start' => Carbon::parse($details->date_start)->format('d / m / Y'),
                'date_end' => Carbon::parse($details->date_end)->format('d / m / Y'),
                'full_price' => $pricingScheme?->sku_4->price,
            ];
        }
    }

    private function buildOrderData(OrderMarketplace $order): array
    {
        $items = $this->groupDataItems($order->itemOrders()->get());
        return [
            'id' => $order->id,
            'code' => $order->code,
            'store_name' => $order->store->name,
            'request_date' => $order->created_at->format('d / m / Y'),
            'product_amount' => count($items),
            'client_name' => $order->client->full_name,
            'client_email' => $order->client->email,
            'employee_name' => $order->employee->full_name,
            'employee_email' => $order->employee->email,
            'client_phone' => $order->client->phone,
            'items' => $items,
        ];
    }

    private function buildOrderInfoData(OrderMarketplace $order): array
    {
        return [
            'order_id' => $order->id,
        ];
    }
}
