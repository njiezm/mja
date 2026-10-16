<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Le lien entre une adhésion et son paiement Stripe, enfin tenu en base.
 *
 * Jusqu'ici il n'existait que dans les metadata Stripe, écrites après coup par
 * un appel HTTP sans rejeu : un échec réseau, et le lien était perdu pour de
 * bon avec une simple ligne de log pour trace. L'identifiant du paiement, lui,
 * était explicitement écarté des données enregistrées.
 *
 * Conséquence plus grave que l'absence de traçabilité : rien n'empêchait qu'un
 * même PaymentIntent valide plusieurs adhésions. L'identifiant venait du
 * navigateur et n'était comparé à rien — un double-clic sur « Envoyer », ou un
 * retour arrière après une erreur de validation, suffisait à créer deux
 * adhésions « payée » pour un seul encaissement.
 *
 * L'index unique règle ce point là où aucune situation de concurrence ne peut
 * le contourner : dans la base elle-même, et non dans une vérification PHP qui
 * se ferait doubler entre le SELECT et l'INSERT.
 *
 * Avant de lancer cette migration en production, passer
 * `php artisan adhesions:rapprocher-stripe` : si deux adhésions partagent déjà
 * un même paiement, l'index refusera de se poser et il faudra trancher à la
 * main lesquelles sont légitimes.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('adhesions', function (Blueprint $table) {
            $table->string('stripe_payment_intent_id', 255)->nullable();
            $table->timestamp('paye_le')->nullable();

            // Index partiel impossible en portable : PostgreSQL traite déjà
            // plusieurs NULL comme distincts, ce qui est exactement ce qu'on
            // veut — seules les valeurs renseignées doivent être uniques.
            $table->unique('stripe_payment_intent_id', 'adhesions_stripe_intent_unique');
        });
    }

    public function down(): void
    {
        Schema::table('adhesions', function (Blueprint $table) {
            $table->dropUnique('adhesions_stripe_intent_unique');
            $table->dropColumn(['stripe_payment_intent_id', 'paye_le']);
        });
    }
};
