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
            if (!Schema::hasColumn('ktd_presensi', 'manual_supervisor_name')) {
                $table->string('manual_supervisor_name')->nullable()->after('error_pulang_taken_at');
            }
            if (!Schema::hasColumn('ktd_presensi', 'manual_supervisor_nip')) {
                $table->string('manual_supervisor_nip')->nullable()->after('manual_supervisor_name');
            }
            if (!Schema::hasColumn('ktd_presensi', 'manual_unit_kerja')) {
                $table->string('manual_unit_kerja')->nullable()->after('manual_supervisor_nip');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ktd_presensi', function (Blueprint $table) {
            $table->dropColumn(['manual_supervisor_name', 'manual_supervisor_nip', 'manual_unit_kerja']);
        });
    }
};
