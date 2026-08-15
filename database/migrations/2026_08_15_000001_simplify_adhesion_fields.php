<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Allège le formulaire d'adhésion :
 *  - l'adresse postale n'est plus demandée (colonne conservée mais facultative,
 *    pour ne pas perdre l'historique — sa purge éventuelle est une décision à part) ;
 *  - les réseaux sociaux, facultatifs, sont stockés en JSON pour pouvoir en
 *    ajouter sans nouvelle migration.
 *
 * La photo devient facultative côté validation uniquement : la colonne était
 * déjà nullable.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('adhesions', function (Blueprint $table) {
            $table->json('reseaux_sociaux')->nullable()->after('email');
            $table->text('adresse_postale')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('adhesions', function (Blueprint $table) {
            $table->dropColumn('reseaux_sociaux');
        });
    }
};
