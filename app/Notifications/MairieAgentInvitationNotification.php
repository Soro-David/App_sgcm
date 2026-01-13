<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MairieAgentInvitationNotification extends Notification
{
    use Queueable;

    public string $otp;

    public function __construct(string $otp)
    {
        $this->otp = $otp;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $completionUrl = route('mairie.complete-registration.show', ['email' => $notifiable->email]);

        return (new MailMessage)
            ->subject('Invitation à finaliser votre inscription')
            ->greeting('Bonjour !')
            ->line('Vous avez été invité à créer un compte .')
            ->line('Utilisez ce code OTP pour finaliser votre inscription :')
            ->line(new \Illuminate\Support\HtmlString('<strong style="font-size: 1.5em;">'.$this->otp.'</strong>'))
            ->action('Finaliser mon inscription', $completionUrl)
            ->line('Ce code expirera dans 30 minutes.');
    }
}
