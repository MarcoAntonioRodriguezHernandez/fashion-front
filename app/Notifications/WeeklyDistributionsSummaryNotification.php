<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class WeeklyDistributionsSummaryNotification extends Notification
{
    use Queueable;

    public $distributions;
    public $startDate;
    public $endDate;

    public function __construct($distributions, $startDate, $endDate)
    {
        $this->distributions = $distributions;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }
    public function toMail($notifiable)
    {
        $subject = 'Resumen semanal de distribuciones automáticas';
        $mail = (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject($subject)
            ->line("Resumen semanal de distribuciones automáticas del {$this->startDate} al {$this->endDate}.");

        if (empty($this->distributions) || count($this->distributions) === 0) {
            $mail->line('No se realizaron distribuciones automáticas esta semana.');
        } else {
            $mail->line('Se realizaron las siguientes distribuciones automáticas:');
            foreach ($this->distributions as $distribution) {
                $mail->line('Código: ' . ($distribution['code'] ?? '-') . ' Fecha: ' . ($distribution['created_at'] ?? '-'));
            }
            $mail->action('Ver todas las distribuciones', route('base.supply.index'));
        }
        $mail->line('Gracias por usar nuestra aplicación!');
        return $mail;
    }
}
