<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('adhesions', function (Blueprint $table) {
            $table->boolean('rgpd_consentement')->default(false)->after('droit_image');
        });

        // Les adhésions déjà enregistrées sont considérées comme ayant consenti
        // (le droit à l'image était déjà obligatoire à la soumission).
        DB::table('adhesions')->update(['rgpd_consentement' => true]);
    }

    public function down(): void
    {
        Schema::table('adhesions', function (Blueprint $table) {
            $table->dropColumn('rgpd_consentement');
        });
    }
};
