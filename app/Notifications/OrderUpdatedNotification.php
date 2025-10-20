<?php

namespace App\Notifications;

use App\Enums\OrderStatuses;
use App\Models\Marketplace\OrderMarketplace;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderUpdatedNotification extends Notification
{
    use Queueable;

    private OrderMarketplace $orderMarketplace;

    /**
     * Create a new notification instance.
     */
    public function __construct(OrderMarketplace $orderMarketplace)
    {
        $this->orderMarketplace = $orderMarketplace;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $orderStatus = $this->orderMarketplace->status;
        $headerText = OrderStatuses::getNotificationMessage()[$orderStatus] ?? 'Estado desconocido';

        $shippingPrice = $this->orderMarketplace->orderShippingMarketplace?->shippingPrice->price ?? 0;
        $totalPrice = $this->orderMarketplace->amount_total + $shippingPrice;

        $userAddress = $this->orderMarketplace->orderShippingMarketplace?->userAddress;

        return (new MailMessage)
            ->subject($headerText)
            ->view('mail.marketplace.orders.updated', [
                'orderMarketplace' => $this->orderMarketplace,
                'headerText' => $headerText,
                'totalPrice' => number_format($totalPrice, 2),
                'shippingPrice' => number_format($shippingPrice, 2),
                'userAddress' => $userAddress,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable): array
    {
        return [
            //
        ];
    }
}
