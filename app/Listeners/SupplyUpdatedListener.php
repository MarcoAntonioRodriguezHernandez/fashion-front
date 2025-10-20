<?php

namespace App\Listeners;

use App\Events\SupplyUpdatedEvent;
use App\Notifications\SupplyUpdatedNotification;
use Illuminate\Support\Facades\Notification;

class SupplyUpdatedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     */
    public function handle(SupplyUpdatedEvent $event)
    {
        $user = $event->supply->sender;
        Notification::send($user, new SupplyUpdatedNotification($event->supply, $event->operationType));
    }
}
