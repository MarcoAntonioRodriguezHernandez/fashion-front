<?php

namespace App\Notifications;

use App\Models\Base\Supply;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SupplyUpdatedNotification extends Notification
{
    use Queueable;

    private Supply $supply;
    private $operationType;
    /**
     * Create a new notification instance.
     */
    public function __construct( Supply $supply, $operationType)
    {
        $this->supply = $supply;
        $this->operationType = $operationType;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
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
        $operationText = ($this->operationType === 'create') ? 'creado correctamente.' : 'actualizado.';
        $subject = 'Distribución con código ' . $this->supply->code . ' ' . $operationText;

        return (new MailMessage)
            ->subject($subject)        
            ->line('La distribución de código ' . $this->supply->code . ' ha sido ' . $operationText)
            ->line('Status: ' . $this->supply->status_name)
            ->action('Ver distribución', route('base.supply.show', $this->supply->id))
            ->line('Gracias por usar nuestra aplicación!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
