<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommercantWelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $commercant; // Juste pour l'accès au nom si besoin, ou on peut le passer en param

    public string $otp;

    public function __construct($commercant, string $otp)
    {
        $this->commercant = $commercant;
        $this->otp = $otp;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // Route demandée par l'utilisateur : avec paramètre email comme pour l'agent
        $completionUrl = route('commercant.complete-registration.show', ['email' => $notifiable->email]);

        return (new MailMessage)
            ->subject('Invitation à finaliser votre inscription')
            ->greeting('Bonjour '.$this->commercant->nom.' !')
            ->line('Vous avez été invité à créer un compte pour votre commerce.')
            ->line('Utilisez ce code OTP pour finaliser votre inscription :')
            ->line(new \Illuminate\Support\HtmlString('<strong style="font-size: 1.5em; color: #0d6efd;">'.$this->otp.'</strong>'))
            ->action('Finaliser mon inscription', $completionUrl)
            ->line('Ce code expirera dans 30 minutes.');
    }
}
