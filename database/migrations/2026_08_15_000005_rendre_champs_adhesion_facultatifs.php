<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Une prise d'informations ne collecte que l'identité, les coordonnées et la
 * question posée. Ces colonnes étaient pourtant restées obligatoires en base :
 * l'enregistrement échouait donc systématiquement, quand bien même le
 * formulaire ne demandait plus rien.
 */
return new class extends Migration
{
    /** Colonnes qui ne concernent que les adhésions, pas les demandes d'information. */
    private const COLONNES = [
        'date_naissance'  => 20,
        'profession'      => 150,
        'taille_tshirt'   => 10,
        'permis'          => 20,
        'urgence_contact' => 300,
    ];

    public function up(): void
    {
        Schema::table('adhesions', function (Blueprint $table) {
            foreach (self::COLONNES as $colonne => $longueur) {
                $table->string($colonne, $longueur)->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        // Les lignes créées entre-temps peuvent porter des valeurs nulles :
        // on les comble avant de refermer la contrainte, sans quoi le retour
        // arrière échouerait sur les prises d'informations enregistrées.
        foreach (array_keys(self::COLONNES) as $colonne) {
            \Illuminate\Support\Facades\DB::table('adhesions')
                ->whereNull($colonne)
                ->update([$colonne => '']);
        }

        Schema::table('adhesions', function (Blueprint $table) {
            foreach (self::COLONNES as $colonne => $longueur) {
                $table->string($colonne, $longueur)->nullable(false)->change();
            }
        });
    }
};
