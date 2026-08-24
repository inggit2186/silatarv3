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
        Schema::table('ktd_presensi', function (Blueprint $table) {
            if (!Schema::hasColumn('ktd_presensi', 'error_masuk_taken_at')) {
                $table->time('error_masuk_taken_at')->nullable()->after('keterangan');
            }
            if (!Schema::hasColumn('ktd_presensi', 'error_pulang_taken_at')) {
                $table->time('error_pulang_taken_at')->nullable()->after('error_masuk_taken_at');
            }
            // Drop kolom lama jika ada
            if (Schema::hasColumn('ktd_presensi', 'error_taken_at')) {
                $table->dropColumn('error_taken_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ktd_presensi', function (Blueprint $table) {
            $table->dropColumn(['error_masuk_taken_at', 'error_pulang_taken_at']);
        });
    }
};
