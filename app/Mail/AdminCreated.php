<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminCreated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $plainPassword
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Accès backoffice Madin'Jeunes Ambition",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-created',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
