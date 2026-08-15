<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountDeleted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $member, public string $restoreUrl, public string $purgeDate) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: "Votre compte a été supprimé — Madin'Jeunes Ambition");
    }

    public function content(): Content
    {
        return new Content(view: 'emails.account-deleted');
    }

    public function attachments(): array
    {
        return [];
    }
}
