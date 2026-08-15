<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Nouveaux identifiants d'un compte « espace adhérent », envoyés quand un
 * administrateur crée le compte ou régénère son mot de passe.
 */
class MemberPasswordReset extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $member,
        public string $plainPassword,
        public bool $nouveauCompte = false,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->nouveauCompte
                ? "Votre espace adhérent Madin'Jeunes Ambition"
                : "Votre nouveau mot de passe — Espace adhérent MJA",
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.member-password-reset');
    }

    public function attachments(): array
    {
        return [];
    }
}
