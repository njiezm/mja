<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Trace d'une relance envoyée à un adhérent (paiement ou renouvellement).
 * Sert d'historique en back-office et de garde-fou anti-doublon.
 */
class AdhesionRelance extends Model
{
    protected $table = 'adhesion_relances';

    public const TYPE_PAIEMENT       = 'paiement';
    public const TYPE_RENOUVELLEMENT = 'renouvellement';

    public const TYPES = [
        self::TYPE_PAIEMENT       => 'Relance de paiement',
        self::TYPE_RENOUVELLEMENT => 'Relance de renouvellement',
    ];

    protected $fillable = ['adhesion_id', 'type', 'numero', 'email', 'automatique', 'envoyee_le'];

    protected $casts = [
        'automatique' => 'boolean',
        'envoyee_le'  => 'datetime',
    ];

    public function adhesion(): BelongsTo
    {
        return $this->belongsTo(Adhesion::class);
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }
}
