<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Cleanup unused columns from users and tenaga_ktd tables
     * based on analysis of Flutter app and backend usage.
     */
    public function up(): void
    {
        // ═══════════════════════════════════════════════════════════════════
        // USERS TABLE - Remove only clearly unused columns
        // ═══════════════════════════════════════════════════════════════════

        // Columns to remove from users table:
        // - asn: ASN status (data already exists in tenaga_ktd)
        // - tmt_tugas: TMT Tugas (data already exists in tenaga_ktd)
        // - kgb: KGB (data already exists in tenaga_ktd)
        // - masa_kerja_tahun: Masa kerja tahun (data already exists in tenaga_ktd)
        // - masa_kerja_bulan: Masa kerja bulan (data already exists in tenaga_ktd)
        // - npwp: NPWP (data already exists in tenaga_ktd)

        // KEEP these columns:
        // - notif: Might be used in the future
        // - jabatan: Used in Admin\UserController
        // - pekerjaan: Used in Admin\UserController store
        // - satker: Used in RegisterController for guru_pai

        $columnsToDrop = [
            'asn',
            'tmt_tugas',
            'kgb',
            'masa_kerja_tahun',
            'masa_kerja_bulan',
            'npwp',
        ];

        Schema::table('users', function (Blueprint $table) use ($columnsToDrop) {
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        // ═══════════════════════════════════════════════════════════════════
        // TENAGA_KTD TABLE - Keep all columns for future use
        // ═══════════════════════════════════════════════════════════════════

        // No columns dropped from tenaga_ktd table
        // All columns kept for potential future use in the application
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: This migration drops columns, which cannot be easily reversed
        // without knowing the original column definitions.
        // If you need to rollback, you'll need to manually recreate the columns.
        echo "WARNING: This migration drops columns. Rollback not supported.\n";
    }
};
