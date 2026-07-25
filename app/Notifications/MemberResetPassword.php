<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MemberResetPassword extends Notification
{
    use Queueable;

    public function __construct(public string $token) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $url = route('member.password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        return (new MailMessage)
            ->subject("Réinitialisation de votre mot de passe — Espace membre MJA")
            ->greeting('Bonjour,')
            ->line("Vous avez demandé la réinitialisation du mot de passe de votre espace membre Madin'Jeunes Ambition.")
            ->action('Réinitialiser mon mot de passe', $url)
            ->line("Ce lien expire dans 60 minutes.")
            ->line("Si vous n'êtes pas à l'origine de cette demande, ignorez cet email.");
    }
}
