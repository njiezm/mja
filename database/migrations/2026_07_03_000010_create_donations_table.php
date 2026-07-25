<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('prenom')->nullable();
            $table->string('nom')->nullable();
            $table->string('email');
            $table->decimal('montant', 8, 2);
            $table->text('message')->nullable();
            $table->string('statut')->default('en_attente'); // en_attente / paye
            $table->string('stripe_session_id')->nullable();
            $table->boolean('lu')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
