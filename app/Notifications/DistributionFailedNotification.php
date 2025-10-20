<?php

namespace App\Notifications;

use App\Models\Marketplace\OrderMarketplace;
use App\Models\User;
use App\Models\Base\Notification;

class DistributionFailedNotification
{
    /**
     * Notifies when an automatic distribution could not be created.
     *
     * @param User $user User receiving the notification
     * @param OrderMarketplace $order Associated order
     * @param string $reason Reason for failure
     * @return Notification
     */
    public static function notify(User $user, OrderMarketplace $order, string $reason): Notification
    {
        $text = "No se pudo crear la distribución automática para la orden #{$order->code}. {$reason}";

        return Notification::create([
            'user_id'              => $user->id,
            'text'                 => $text,
            'link'                 => route('marketplace.order_marketplace.show', $order->id),
            'order_marketplace_id' => $order->id,
            'date'                 => now(),
        ]);
    }
}
