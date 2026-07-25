<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('adhesion_id')->unique()->constrained('adhesions')->cascadeOnDelete();
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('show_in_directory')->default(true); // apparaît dans le trombinoscope
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::table('adhesions', function (Blueprint $table) {
            $table->string('account_token', 64)->nullable()->index()->after('statut');
            $table->timestamp('account_token_expires_at')->nullable()->after('account_token');
        });
    }

    public function down(): void
    {
        Schema::table('adhesions', function (Blueprint $table) {
            $table->dropColumn(['account_token', 'account_token_expires_at']);
        });
        Schema::dropIfExists('members');
    }
};
