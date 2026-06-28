<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Mail;

class ResetPasswordNotification extends Notification
{
    public function __construct(public string $token) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject("Réinitialisation de mot de passe — Madin'Jeunes Ambition")
            ->view('emails.reset-password', ['resetUrl' => $resetUrl]);
    }
}
