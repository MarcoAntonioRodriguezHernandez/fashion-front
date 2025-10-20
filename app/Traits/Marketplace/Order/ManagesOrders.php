<?php

namespace App\Traits\Marketplace\Order;

use App\Enums\{
    ItemStatuses,
    OrderSaleType,
    OrderStatuses,
    PaymentStatuses,
};
use App\Models\Base\{
    ClientCredit,
    ClientDetail,
    PaymentType,
};
use App\Models\Marketplace\{
    OrderMarketplace,
    PaymentOrderMarketplace,
};
use Exception;
use Illuminate\Support\{
    Carbon,
    Collection,
};

trait ManagesOrders
{

    /**
     * Update the status of an order
     *
     * @param OrderMarketplace $orderMarketplace The order to update
     * @param OrderStatuses $newStatus The new status of the order
     *
     * @return OrderStatuses The new status of the order
     */
    public function updateOrderStatus(OrderMarketplace $orderMarketplace, OrderStatuses $newStatus)
    {
        if (!$orderMarketplace->is_active && !in_array($newStatus->value, [OrderStatuses::PAY->value]) && empty($orderMarketplace->code))
            throw new Exception('Can not update as the order is not active');

        $hasRents = $orderMarketplace->itemOrders()->where('sale_type', OrderSaleType::RENT->value)->exists();

        if ($newStatus == OrderStatuses::PAY && !$hasRents) { // If paid and sales only, mark as closed automatically
            $newStatus = OrderStatuses::CLOSED;
        }

        $itemStatuses = [
            OrderStatuses::DELIVERED_TO_CUSTOMER->value => [ // When order is delivered to the customer
                OrderSaleType::RENT->value => ItemStatuses::RENT, // Rent items are marked as rented
                OrderSaleType::SALE->value => ItemStatuses::SOLD, // Sale items are marked as sold
            ],
            OrderStatuses::DELIVERED_BY_CUSTOMER->value => [
                OrderSaleType::RENT->value => ItemStatuses::DRY_CLEANING,
            ],
            OrderStatuses::CANCELLED->value => [ // When order is cancelled
                0 => ItemStatuses::AVAILABLE, // All items are marked as available
            ],
            OrderStatuses::RETURNED->value => [
                0 => ItemStatuses::AVAILABLE,
            ],
            OrderStatuses::CLOSED->value => [
                OrderSaleType::SALE->value => ItemStatuses::SOLD,
            ],
        ];

        if (array_key_exists($newStatus->value, $itemStatuses)) { // Update status of items
            foreach ($itemStatuses[$newStatus->value] as $saleType => $itemStatus) {
                $orderMarketplace->itemOrders()
                    ->when($saleType, fn($q) => $q->where('sale_type', $saleType))
                    ->get()
                    ->each(fn($io) => $io->item->update([
                        'status' => $itemStatus->value,
                    ]));
            }
        }

        $surchargeAmount = $this->calculateOrderSurcharge($orderMarketplace, $newStatus);

        $orderMarketplace->update([
            'status' => $newStatus->value,
            'surcharge' => $surchargeAmount === false ? $orderMarketplace->surcharge : $surchargeAmount,
        ]);

        return $newStatus;
    }

    /**
     * Update the total amount of the order and create client credits or payments if necessary
     *
     * @return int The remaining amount to be paid
     */
    public function updateOrderTotalAmount(OrderMarketplace $orderMarketplace)
    {
        $allPayments = $orderMarketplace->paymentOrderMarketplace()
            ->where('status', PaymentStatuses::APPROVED->value)
            ->latest()
            ->get();

        $orderTotalPayment = $allPayments->sum('payment');
        $newTotalAmount = $orderMarketplace->itemOrders->sum('totalPrice');
        $clientDetail = ClientDetail::where('user_id', $orderMarketplace->client_id)->first();

        $orderMarketplace->update([
            'amount' => $newTotalAmount,
        ]);

        $allClientCredits = $clientDetail->clientCredits()->where('expiration_date', '>', now())->get();
        $totalCredit = $allClientCredits->sum('amount');

        if ($orderTotalPayment > $newTotalAmount) {
            $newClientCredit = $this->createClientCredit($allPayments, $clientDetail, ($orderTotalPayment - $newTotalAmount));
        } else if ($totalCredit > 0) {
            $newOrderPayment = $this->createOrderPayments($allClientCredits, $orderMarketplace, min($newTotalAmount - $orderTotalPayment, $totalCredit));
        }

        return $orderMarketplace->amount - ($newOrderPayment?->payment ?? 0);
    }

    /**
     * Create a client credit for the payment exceeding the total amount of the order
     */
    protected function createClientCredit(Collection $payments, ClientDetail $clientDetail, int $creditAmount)
    {
        if ($creditAmount <= 0)
            return null;

        $clientCredit = ClientCredit::create([
            'client_detail_id' => $clientDetail->id,
            'amount' => $creditAmount,
            'expiration_date' => now()->addMonths(config('common.client_credit_validity'))
        ]);

        foreach ($payments as $p) {
            $diff = min($p->payment, $creditAmount);
            $p->decrement('payment', $diff);
            $creditAmount -= $diff;

            if ($creditAmount <= 0)
                break;
        }

        return $clientCredit;
    }

    /**
     * Create payments for the order using the client credits
     */
    protected function createOrderPayments(Collection $clientCredits, OrderMarketplace $orderMarketplace, int $paymentAmount, string $paymentType = 'credito-de-cliente')
    {
        if ($paymentAmount <= 0)
            return null;

        $payment = PaymentOrderMarketplace::create([
            'order_marketplace_id' => $orderMarketplace->id,
            'total' => $paymentAmount,
            'payment' => $paymentAmount,
            'payment_type_id' => PaymentType::where('slug', $paymentType)->first()->id,
            'status' => PaymentStatuses::APPROVED->value,
        ]);

        foreach ($clientCredits as $credit) {
            $diff = min($credit->amount, $paymentAmount);
            $credit->decrement('amount', $diff);
            $paymentAmount -= $diff;

            if ($paymentAmount <= 0)
                break;
        }

        return $payment;
    }

    /**
     * Calculate the surcharge for an order based on the date
     *
     * @param OrderMarketplace $orderMarketplace The order to calculate the surcharge
     * @param OrderStatuses $newStatus The new status of the order
     * @param Carbon $date The date to calculate the surcharge
     *
     * @return int The surcharge amount
     */
    protected function calculateOrderSurcharge(OrderMarketplace $orderMarketplace, OrderStatuses $newStatus, Carbon $date = null)
    {
        if (!in_array($newStatus->value, [OrderStatuses::DELIVERED_BY_CUSTOMER->value]))
            return false;

        $date ??= now();

        return $orderMarketplace->itemOrders()
            ->where('sale_type', OrderSaleType::RENT)
            ->get()
            ->filter(fn($io) => $io->rentDetailsMarketplace->date_end->isPast())
            ->map(fn($io) => $io->rentDetailsMarketplace->date_end->diffInDays(now()->setTime(0, 0)))
            ->sum(function ($days) {
                if ($days <= 0)
                    return 0;

                return 150 + (($days - 1) * 250); // 150 on first day, 250 on the rest
            });
    }
}
