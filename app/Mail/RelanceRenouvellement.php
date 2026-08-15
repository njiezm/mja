<?php

namespace App\Mail;

use App\Models\Adhesion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RelanceRenouvellement extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  string  $lien  URL du formulaire pré-rempli (lien magique) : c'est
     *                        lui qui évite toute ressaisie à l'adhérent.
     */
    public function __construct(
        public Adhesion $adhesion,
        public int $numero = 1,
        public string $lien = '',
    ) {}

    public function envelope(): Envelope
    {
        $objet = $this->numero === 1
            ? "C'est le moment de renouveler votre adhésion MJA"
            : "Rappel — votre adhésion MJA arrive à échéance";

        return new Envelope(subject: $objet);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.relance-renouvellement');
    }
}
