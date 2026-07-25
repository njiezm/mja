<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Copie chiffrée (réversible) du mot de passe, pour affichage au super admin.
            $table->text('password_encrypted')->nullable()->after('password');
            // Révocation d'accès sans suppression du compte.
            $table->boolean('is_active')->default(true)->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['password_encrypted', 'is_active']);
        });
    }
};
