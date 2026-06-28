<?php

namespace App\Mail;

use App\Models\Adhesion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdhesionConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Adhesion $adhesion) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Confirmation de votre demande d'adhésion — Madin'Jeunes Ambition",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.adhesion-confirmation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
