<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('adhesion_periods', function (Blueprint $table) {
            $table->id();
            $table->string('label');            // ex. « Saison 2025-2026 »
            $table->date('date_debut');
            $table->date('date_fin');
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });

        Schema::table('adhesions', function (Blueprint $table) {
            $table->foreignId('period_id')->nullable()->after('source_id')
                ->constrained('adhesion_periods')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('adhesions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('period_id');
        });
        Schema::dropIfExists('adhesion_periods');
    }
};
