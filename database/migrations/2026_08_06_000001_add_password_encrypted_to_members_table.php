<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Copie chiffrée (réversible) du mot de passe adhérent, pour que le super admin
 * puisse le relire — même mécanisme que `users.password_encrypted`.
 * Le hash bcrypt reste la seule source d'authentification.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->text('password_encrypted')->nullable()->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('password_encrypted');
        });
    }
};
