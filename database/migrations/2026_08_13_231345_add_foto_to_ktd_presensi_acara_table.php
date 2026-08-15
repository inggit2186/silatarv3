<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ktd_presensi_acara', function (Blueprint $table) {
            if (!Schema::hasColumn('ktd_presensi_acara', 'foto')) {
                $table->string('foto')->nullable()->after('location');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ktd_presensi_acara', function (Blueprint $table) {
            $table->dropColumn('foto');
        });
    }
};
