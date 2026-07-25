<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('adhesions', function (Blueprint $table) {
            $table->string('photo')->nullable()->after('urgence_contact');
            $table->string('moyen_paiement')->nullable()->after('photo');
        });
    }

    public function down(): void
    {
        Schema::table('adhesions', function (Blueprint $table) {
            $table->dropColumn(['photo', 'moyen_paiement']);
        });
    }
};
