<?php

namespace App\Notifications;

use App\Models\Marketplace\OrderMarketplace;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SupplyFailedNotification extends Notification
{
    use Queueable;

    private OrderMarketplace $order;
    private string $reason;

    /**
     * Create a new notification instance.
     */
    public function __construct(OrderMarketplace $order, string $reason)
    {
        $this->order = $order;
        $this->reason = $reason;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $subject = 'Fallo en creación de distribución automática (Orden ' . $this->order->id . ')';

        return (new MailMessage)
            ->subject($subject)
            ->line('No fue posible crear la distribución para la orden con código: ' . $this->order->id)
            ->line('Motivo: ' . $this->reason)
            ->action('Ver orden', route('marketplace.order_marketplace.show', $this->order->id))
            ->line('Por favor, revise la orden para hacer restock.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'reason'   => $this->reason,
        ];
    }
}
