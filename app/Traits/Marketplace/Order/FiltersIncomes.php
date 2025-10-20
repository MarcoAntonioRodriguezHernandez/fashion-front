<?php

namespace App\Traits\Marketplace\Order;

use App\Enums\PaymentStatuses;
use App\Models\Marketplace\PaymentOrderMarketplace;
use Illuminate\Support\Collection;

trait FiltersIncomes
{
    public function filterIncomesBy(object $filters, int|bool $pageSize = null)
    {
        return PaymentOrderMarketplace::with([
            'orderMarketplace.store:id,name',
            'orderMarketplace.client:id,name,last_name,email,phone',
            'orderMarketplace.itemOrders.item.product.designer:id,slug',
            'orderMarketplace.itemOrders.item.variant.size:id,name,number',
            'orderMarketplace.itemOrders.item.variant.color:id,name',
            'orderMarketplace.employee:id,name,last_name',
            'orderMarketplace.orderShippingMarketplace.shippingPrice:id,price',
            'orderMarketplace.itemOrders.rentDetailsMarketplace',
            'orderMarketplace.discounts',
            'orderMarketplace.paymentOrderMarketplace:id,order_marketplace_id,created_at',
            'paymentType:id,name',
            ])
            ->where('status', PaymentStatuses::APPROVED)
            ->when($filters->store_id ?? false, fn($q) =>
                $q->whereHas('orderMarketplace', fn($q) =>
                    $q->where('store_id', $filters->store_id)
                )
            )
            ->when($filters->sale_type != 0, fn($q) =>
                $q->whereHas('orderMarketplace.itemOrders', fn($q) =>
                    $q->where('sale_type', $filters->sale_type)
                )
            )
            ->whereDate('created_at', '>=', \Carbon\Carbon::parse($filters->start_date))
            ->whereDate('created_at', '<=', \Carbon\Carbon::parse($filters->finish_date))
            ->get()
            ->pipe(fn($collection) => $this->normalizeData($collection, $pageSize));
    }

    protected function validateDateRange(object $filters): void
    {
        $startDate = Carbon::parse($filters->start_date);
        $finishDate = Carbon::parse($filters->finish_date);
        
        if ($startDate->diffInMonths($finishDate) > 6) {
            throw ValidationException::withMessages([
                'finish_date' => 'El rango de fechas no puede exceder los 6 meses.'
            ]);
        }
    }
    
    protected function normalizeData(Collection $data, int|bool $pageSize = null)
    {
        return $data
            ->when($pageSize === false, fn($c) => $c->map(fn($payment) => $this->buildPaymentData($payment)))
            ->when($pageSize !== false, fn($c) => $c->paginate($pageSize)->through(fn($payment) => $this->buildPaymentData($payment))
            );
    }

    private function buildPaymentData(PaymentOrderMarketplace $payment): array
    {
        $order = $payment->orderMarketplace;
        $client = $order->client;

        return [
            'id' => $payment->id,
            'order_code' => $order->code ?? '-',
            'store_name' => $order->store->name ?? '-',
            'request_date' => $payment->created_at->format('d / m / Y'),
            'client_name' => $client?->full_name ?? '-',
            'client_email' => $client?->email ?? '-',
            'client_phone' => $client?->phone ?? '-',
            'payment_type' => $payment->paymentType->name ?? '-',
            'amount' => $payment->payment,
        ];
    }
}
