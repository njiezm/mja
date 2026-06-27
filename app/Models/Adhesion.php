<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adhesion extends Model
{
    protected $fillable = [
        'premiere_adhesion', 'civilite', 'nom', 'prenom', 'date_naissance',
        'profession', 'telephone', 'email', 'adresse_postale', 'taille_tshirt',
        'permis', 'problemes_sante', 'urgence_contact', 'droit_image', 'statut', 'lu',
    ];

    protected $casts = [
        'droit_image' => 'boolean',
        'lu' => 'boolean',
    ];

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
            'nouvelle'  => 'Nouvelle',
            'traitee'   => 'Traitée',
            'acceptee'  => 'Acceptée',
            'refusee'   => 'Refusée',
            default     => $this->statut,
        };
    }
}
