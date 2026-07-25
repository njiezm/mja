<?php

namespace App\Mail;

use App\Models\Adhesion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdhesionStatusUpdate extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Adhesion $adhesion) {}

    public function envelope(): Envelope
    {
        $subject = match ($this->adhesion->statut) {
            'payee'               => "Bienvenue parmi les adhérents de Madin'Jeunes Ambition !",
            'refusee'             => "Votre demande d'adhésion — Madin'Jeunes Ambition",
            'en_attente_paiement' => "Votre adhésion MJA — en attente de paiement",
            default               => "Suivi de votre adhésion — Madin'Jeunes Ambition",
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.adhesion-statut');
    }

    public function attachments(): array
    {
        return [];
    }
}
