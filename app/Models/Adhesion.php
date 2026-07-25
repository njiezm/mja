<?php

namespace App\Models;

use App\Support\Token;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Adhesion extends Model
{
    protected $fillable = [
        'premiere_adhesion', 'civilite', 'nom', 'prenom', 'date_naissance',
        'profession', 'telephone', 'email', 'adresse_postale', 'taille_tshirt',
        'permis', 'problemes_sante', 'urgence_contact', 'photo', 'moyen_paiement',
        'droit_image', 'rgpd_consentement', 'statut', 'lu', 'source_id', 'period_id',
        'account_token', 'account_token_expires_at',
    ];

    protected $casts = [
        'droit_image' => 'boolean',
        'rgpd_consentement' => 'boolean',
        'lu' => 'boolean',
        'account_token_expires_at' => 'datetime',
    ];

    public function member(): HasOne
    {
        return $this->hasOne(Member::class);
    }

    public function period(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AdhesionPeriod::class, 'period_id');
    }

    /**
     * Génère (si nécessaire) un jeton de création de compte et renvoie
     * l'URL correspondante. Renvoie null si un compte existe déjà.
     */
    public function ensureAccountToken(): ?string
    {
        if ($this->member()->exists()) {
            return null;
        }

        $expired = $this->account_token_expires_at && $this->account_token_expires_at->isPast();

        if (! $this->account_token || $expired) {
            $this->account_token = Token::uniqueFor(self::class, 'account_token');
            $this->account_token_expires_at = now()->addDays(30);
            $this->save();
        }

        return $this->accountCreationUrl();
    }

    public function accountCreationUrl(): ?string
    {
        return $this->account_token ? route('member.account.create', $this->account_token) : null;
    }

    public function getNomCompletAttribute(): string
    {
        return $this->civilite . ' ' . $this->prenom . ' ' . $this->nom;
    }

    public function getLabelPremiereAdhesionAttribute(): string
    {
        return match ($this->premiere_adhesion) {
            'premiere'    => 'Première adhésion',
            'readhesion'  => 'Réadhésion',
            'information' => 'Prise d\'informations',
            default       => $this->premiere_adhesion,
        };
    }

    public function getLabelStatutAttribute(): string
    {
        return match ($this->statut) {
            'nouvelle'             => 'Nouvelle',
            'prise_infos'          => "Prise d'infos",
            'en_attente_paiement'  => 'En attente de paiement',
            'payee'                => 'Payée (adhérent)',
            'refusee'              => 'Refusée',
            'desistement'          => 'Désistement',
            // Anciens statuts (compatibilité)
            'traitee'              => 'Traitée',
            'acceptee'             => 'Acceptée',
            default                => $this->statut,
        };
    }

    public function getLabelMoyenPaiementAttribute(): string
    {
        return match ($this->moyen_paiement) {
            'cheque'   => 'Chèque',
            'espece'   => 'Espèces',
            'virement' => 'Virement bancaire',
            'en_ligne' => 'Paiement en ligne (CB)',
            default    => '—',
        };
    }

    /** L'adhérent est confirmé une fois la cotisation payée. */
    public function isAdherent(): bool
    {
        return $this->statut === 'payee';
    }

    /** Statuts sélectionnables dans le back-office. */
    public const STATUTS = [
        'nouvelle'            => 'Nouvelle',
        'prise_infos'         => "Prise d'infos",
        'en_attente_paiement' => 'En attente de paiement',
        'payee'               => 'Payée (adhérent)',
        'refusee'             => 'Refusée',
        'desistement'         => 'Désistement',
    ];
}
