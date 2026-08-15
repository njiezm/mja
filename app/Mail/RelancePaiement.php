<?php

namespace App\Mail;

use App\Models\Adhesion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RelancePaiement extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Adhesion $adhesion, public int $numero = 1) {}

    public function envelope(): Envelope
    {
        $objet = $this->numero === 1
            ? "Votre cotisation MJA n'a pas encore été reçue"
            : "Rappel — votre adhésion MJA est en attente de paiement";

        return new Envelope(subject: $objet);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.relance-paiement');
    }
}
