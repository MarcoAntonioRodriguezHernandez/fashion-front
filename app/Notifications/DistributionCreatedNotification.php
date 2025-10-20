<?php

namespace App\Notifications;

use App\Models\Marketplace\OrderMarketplace;
use App\Models\User;
use App\Models\Base\Supply;
use App\Models\Base\Notification;

class DistributionCreatedNotification
{
    /**
     * Create a custom database notification.
     *
     * @param User $user The user who will receive the notification
     * @param Supply $supply The associated supply
     * @param string $operationType Either 'create' or 'update'
     * @param OrderMarketplace|null $order The associated order (required to get ID)
     * @return Notification
     */
    public static function notify(User $user, Supply $supply, string $operationType, ?OrderMarketplace $order): Notification
    {
        $text = 'Se ha creado una distribución automática desde Almacén para la orden #' . $order->code;

        return Notification::create([
            'user_id' => $user->id,
            'text' => $text,
            'link' => route('base.supply.show', $supply->id),
            'order_marketplace_id' => $order?->id,
            'date' => now(),
        ]);
    }
}
