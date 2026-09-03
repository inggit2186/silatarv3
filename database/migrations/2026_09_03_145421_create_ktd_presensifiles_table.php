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
        // Buat tabel jika belum ada
        if (!Schema::hasTable('ktd_presensifiles')) {
            Schema::create('ktd_presensifiles', function (Blueprint $table) {
                $table->id();
                $table->string('dept')->index(); // Nama unit kerja
                $table->integer('bulan'); // Bulan (1-12)
                $table->integer('tahun'); // Tahun
                $table->string('presensi_path')->nullable()->comment('Path file Excel detail presensi');
                $table->string('uangmakan_path')->nullable()->comment('Path file Excel rekap presensi');
                $table->timestamps();

                // Unique constraint: 1 file per dept per bulan
                $table->unique(['dept', 'bulan', 'tahun']);
            });
        } else {
            // Tabel sudah ada, tambah kolom path jika belum ada
            if (!Schema::hasColumn('ktd_presensifiles', 'presensi_path')) {
                Schema::table('ktd_presensifiles', function (Blueprint $table) {
                    $table->string('presensi_path')->nullable()->comment('Path file Excel detail presensi');
                });
            }

            if (!Schema::hasColumn('ktd_presensifiles', 'uangmakan_path')) {
                Schema::table('ktd_presensifiles', function (Blueprint $table) {
                    $table->string('uangmakan_path')->nullable()->comment('Path file Excel rekap presensi');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ktd_presensifiles');
    }
};
