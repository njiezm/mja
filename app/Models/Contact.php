<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'nom', 'email', 'telephone', 'sujet', 'message', 'lu',
        'type_contact', 'premiere_adhesion', 'civilite', 'prenom',
        'date_naissance', 'profession', 'adresse_postale', 'taille_tshirt',
        'permis', 'problemes_sante', 'urgence_contact', 'droit_image',
    ];

    protected $casts = [
        'lu' => 'boolean',
        'droit_image' => 'boolean',
    ];
}
