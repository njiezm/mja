<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Une prise d'informations n'est pas une adhésion : elle n'a pas besoin de
 * la date de naissance, de la taille de T-shirt ni du contact d'urgence, mais
 * elle a besoin d'un endroit où poser sa question.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('adhesions', function (Blueprint $table) {
            $table->text('message')->nullable()->after('urgence_contact');
        });
    }

    public function down(): void
    {
        Schema::table('adhesions', function (Blueprint $table) {
            $table->dropColumn('message');
        });
    }
};
