<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = ['prenom', 'nom', 'email', 'montant', 'message', 'statut', 'stripe_session_id', 'lu'];

    protected $casts = [
        'montant' => 'decimal:2',
        'lu'      => 'boolean',
    ];

    public function isPaid(): bool
    {
        return $this->statut === 'paye';
    }

    public function getNomCompletAttribute(): string
    {
        return trim(($this->prenom ?? '') . ' ' . ($this->nom ?? '')) ?: 'Anonyme';
    }
}
