<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sources', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();          // segment d'URL : /{slug}
            $table->string('label');                   // nom lisible (ex. « Flyer BAC 2026 »)
            $table->string('description')->nullable();
            $table->string('target')->default('/');    // destination après enregistrement
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('source_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_id')->nullable()->constrained('sources')->nullOnDelete();
            $table->string('visitor_hash', 64)->index(); // hash IP+UA (visiteur unique / jour)
            $table->string('ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('referer')->nullable();
            $table->timestamps();
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('source_visits');
        Schema::dropIfExists('sources');
    }
};
