<?php

namespace App\Mail;

use App\Models\Adhesion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdhesionNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Adhesion $adhesion) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Nouvelle demande d'adhésion — {$this->adhesion->nom_complet}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.adhesion-notification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
