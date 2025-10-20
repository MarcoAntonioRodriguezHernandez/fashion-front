<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

class DailyReportNotification extends Notification
{
    use Queueable;

    private string $fileLink;
    private Carbon $date;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $fileLink, Carbon $date)
    {
        $this->fileLink = $fileLink;
        $this->date = $date;
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
        return (new MailMessage)
            ->subject('Reporte diario de órdenes')
            ->line('Se ha generado un reporte diario de las órdenes del día ' . $this->date->format('d / m / Y') . '.')
            ->action('Descargar', $this->fileLink)
            ->line('Este reporte se ha generado automáticamente y se ha enviado a tu correo electrónico.');
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
