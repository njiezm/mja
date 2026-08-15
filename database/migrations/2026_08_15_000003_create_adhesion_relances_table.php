<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Journal des relances envoyées (paiement en attente, renouvellement annuel).
 *
 * Chaque envoi est tracé : cela sert à la fois d'historique consultable en
 * back-office et de garde-fou — c'est ce journal qui empêche d'envoyer deux
 * fois la même relance, y compris si le déclencheur est appelé plusieurs fois.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('adhesion_relances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('adhesion_id')->constrained('adhesions')->cascadeOnDelete();
            $table->string('type', 30);                 // paiement | renouvellement
            $table->unsignedTinyInteger('numero');      // 1re, 2e, 3e relance…
            $table->string('email', 150);
            $table->boolean('automatique')->default(true);
            $table->timestamp('envoyee_le');
            $table->timestamps();

            $table->index(['adhesion_id', 'type']);
        });

        Schema::table('adhesions', function (Blueprint $table) {
            // Lien magique de renouvellement : ouvre le formulaire pré-rempli
            // sans passer par la connexion.
            $table->string('renouvellement_token', 64)->nullable()->index()->after('account_token_expires_at');
            $table->timestamp('renouvellement_token_expires_at')->nullable()->after('renouvellement_token');
            // Adhésion de l'année précédente dont celle-ci est le renouvellement.
            $table->foreignId('renouvelle_adhesion_id')->nullable()->after('renouvellement_token_expires_at')
                ->constrained('adhesions')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('adhesions', function (Blueprint $table) {
            // L'index est retiré avant sa colonne : SQLite reconstruit la table
            // à chaque suppression de colonne et refuse un index orphelin.
            $table->dropIndex(['renouvellement_token']);
        });

        Schema::table('adhesions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('renouvelle_adhesion_id');
            $table->dropColumn(['renouvellement_token', 'renouvellement_token_expires_at']);
        });

        Schema::dropIfExists('adhesion_relances');
    }
};
