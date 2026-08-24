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
            if (!Schema::hasColumn('ktd_presensi', 'error_taken_at')) {
                $table->time('error_taken_at')->nullable()->after('keterangan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ktd_presensi', function (Blueprint $table) {
            $table->dropColumn('error_taken_at');
        });
    }
};
