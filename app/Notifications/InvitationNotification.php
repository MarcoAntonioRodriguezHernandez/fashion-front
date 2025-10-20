<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvitationNotification extends Notification
{
    use Queueable;

    private string $invitationLink;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $invitationLink)
    {
        $this->invitationLink = $invitationLink;
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
            ->subject('Realiza tu registro en Conspiración Moda.')
            ->line('¡Bienvenido/a al equipo de Conspiración Moda! Para unirte oficialmente, por favor realiza tu registro en el siguiente enlace.')
            ->action('Registrarme', $this->invitationLink)
            ->line('Si necesitas ayuda, contáctanos.')
            ->line('¡Estamos felices de tenerte con nosotros!');
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
