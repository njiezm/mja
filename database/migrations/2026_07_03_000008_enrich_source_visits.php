<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('source_visits', function (Blueprint $table) {
            $table->string('utm_medium')->nullable()->after('referer');
            $table->string('utm_campaign')->nullable()->after('utm_medium');
            $table->string('device', 20)->nullable()->after('utm_campaign'); // mobile / tablet / desktop
        });
    }

    public function down(): void
    {
        Schema::table('source_visits', function (Blueprint $table) {
            $table->dropColumn(['utm_medium', 'utm_campaign', 'device']);
        });
    }
};
