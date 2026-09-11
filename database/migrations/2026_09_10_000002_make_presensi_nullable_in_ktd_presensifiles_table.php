<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Make presensi and uangmakan columns nullable for tukin-only generation
        // Using raw SQL because doctrine/dbal is not installed
        DB::statement("ALTER TABLE `ktd_presensifiles` MODIFY COLUMN `presensi` VARCHAR(255) NULL");
        DB::statement("ALTER TABLE `ktd_presensifiles` MODIFY COLUMN `uangmakan` VARCHAR(255) NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `ktd_presensifiles` MODIFY COLUMN `presensi` VARCHAR(255) NOT NULL");
        DB::statement("ALTER TABLE `ktd_presensifiles` MODIFY COLUMN `uangmakan` VARCHAR(255) NOT NULL");
    }
};
