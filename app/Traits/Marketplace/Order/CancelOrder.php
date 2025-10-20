<?php

namespace App\Traits\Marketplace\Order;

use App\Enums\{
    ItemStatuses,
    NotificationTypes,
    OrderStatuses,
    PaymentStatuses,
};
use App\Models\Base\{
    ClientDetail,
    Notification,
};
use App\Models\Marketplace\OrderMarketplace;
use App\Notifications\OrderUpdatedNotification;
use App\Models\User;
use Exception;

trait CancelOrder
{
    use ManagesOrders;

    public function cancelOrder(int $orderId, int $creditAmount = null): OrderMarketplace
    {
        $order = $this->findOrder($orderId);

        $orderStatus = $order->is_active ?
            OrderStatuses::CANCELLED :
            OrderStatuses::RETURNED;

        return $this->disableOrderWithStatus($order, $orderStatus, $creditAmount);
    }

    /**
     * Cancel an order
     */
    public function disableOrderWithStatus(OrderMarketplace $order, OrderStatuses $orderStatus, int $creditAmount = null): OrderMarketplace
    {
        $order->update([
            'status' => $orderStatus->value,
            'date_canceled' => now()
        ]);

        $this->updateItemsStatus($order);

        $this->createClientCreditForCancelled($order, $creditAmount);

        $this->notifyCancel($order);

        return $order;
    }

    /**
     * Find an order to cancel
     */
    protected function findOrder(int $orderId): OrderMarketplace
    {
        $order = OrderMarketplace::findOrFail($orderId);

        if (!$order->is_enabled)
            throw new Exception('The order is already disabled (cancelled or returned)');

        return $order;
    }

    /**
     * Create a client credit for a cancelled order
     */
    public function createClientCreditForCancelled(OrderMarketplace $order, int $creditAmount = null)
    {
        $allPayments = $order->paymentOrderMarketplace()
            ->where('status', PaymentStatuses::APPROVED->value)
            ->latest()
            ->get();

        $amountToCredit = $creditAmount ?? $allPayments->sum('payment');

        $this->createClientCredit(
            $allPayments,
            ClientDetail::where('user_id', $order->client_id)->first(),
            $amountToCredit,
        );
    }

    /**
     * Notify the cancellation of an order
     */
    protected function notifyCancel(OrderMarketplace $order): void
    {
        $this->createNotification($order, true);

        $notifyUsers = $this->findNotifiedUsers($order);

        foreach ($notifyUsers as $notifyUser) {
            $notifyUser->notify(new OrderUpdatedNotification($order));
        }
    }

    /**
     * Update the status of the items in the order
     */
    private function updateItemsStatus(OrderMarketplace $order): void
    {
        $itemOrders = $order->itemOrders()->get();

        foreach ($itemOrders as $itemOrder) {
            $itemOrder->update([
                'status' => 0
            ]);

            $itemOrder->item->update([
                'status' => ItemStatuses::AVAILABLE->value
            ]);
        }
    }

    /**
     * Create a notification
     */
    private function createNotification(OrderMarketplace $order, bool $success)
    {
        $client = User::findOrFail($order->client_id);

        $text = $success ?
            'La orden ' . $order->id . ' de ' . $client->full_name . ' fue cancelada exitosamente' :
            'Hola ' . $client->name . ' no se pudo cancelar tu orden ' . $order->id;

        return Notification::create([
            'user_id' => $client->id,
            'text' => $text,
            'link' => route('marketplace.order_marketplace.show', ['id' => $order->id]),
            'order_marketplace_id' => $order->id,
            'date' => now(),
        ]);
    }

    /**
     * Find users to notify
     */
    protected function findNotifiedUsers(OrderMarketplace $order)
    {
        if (! in_array($order->marketplace->slug, config('common.conspiracy_marketplaces')))
            return collect();

        return User::query()
            ->notifiedFor(NotificationTypes::ORDER_STATUS)
            ->get()
            ->push(User::findOrFail($order->client_id));
    }
}
