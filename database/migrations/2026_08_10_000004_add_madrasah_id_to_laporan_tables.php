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
        // Add madrasah_id to ktd_laporan_semester_madrasah
        Schema::table('ktd_laporan_semester_madrasah', function (Blueprint $table) {
            if (!Schema::hasColumn('ktd_laporan_semester_madrasah', 'madrasah_id')) {
                $table->unsignedBigInteger('madrasah_id')->nullable()->after('dept_id');
                $table->index('madrasah_id');
            }
        });

        // Add madrasah_id to ktd_laporan_bulanan_madrasah
        Schema::table('ktd_laporan_bulanan_madrasah', function (Blueprint $table) {
            if (!Schema::hasColumn('ktd_laporan_bulanan_madrasah', 'madrasah_id')) {
                $table->unsignedBigInteger('madrasah_id')->nullable()->after('dept_id');
                $table->index('madrasah_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ktd_laporan_semester_madrasah', function (Blueprint $table) {
            $table->dropIndex(['madrasah_id']);
            $table->dropColumn('madrasah_id');
        });

        Schema::table('ktd_laporan_bulanan_madrasah', function (Blueprint $table) {
            $table->dropIndex(['madrasah_id']);
            $table->dropColumn('madrasah_id');
        });
    }
};
