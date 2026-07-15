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
        // Tabel utama penilaian kinerja
        Schema::create('penilaian_kinerja', function (Blueprint $table) {
            $table->id();
            $table->year('tahun');
            $table->tinyInteger('triwulan')->comment('1=Q1, 2=Q2, 3=Q3, 4=Q4');
            $table->foreignId('pejabat_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('penilai_id')->constrained('users')->onDelete('cascade');
            $table->text('catatan_umum')->nullable()->comment('Catatan umum penilaian');
            $table->unsignedInteger('total_thumbs_up')->default(0)->comment('Total thumbs up dari semua kriteria');
            $table->unsignedInteger('total_thumbs_down')->default(0)->comment('Total thumbs down dari semua kriteria');
            $table->timestamps();

            // Unique constraint: satu pejabat hanya bisa dinilai sekali per triwulan per tahun
            $table->unique(['tahun', 'triwulan', 'pejabat_id'], 'unique_penilaian');
            // Index untuk query faster
            $table->index(['penilai_id', 'tahun', 'triwulan']);
            $table->index(['pejabat_id', 'tahun']);
        });

        // Tabel kriteria penilaian
        Schema::create('penilaian_kriteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penilaian_id')->constrained('penilaian_kinerja')->onDelete('cascade');
            $table->string('kriteria', 50)->comment('Nama kriteria: orientasi_pelayanan, akuntabel, dll');
            $table->unsignedTinyInteger('thumbs_up')->default(0)->comment('Jumlah thumbs up (0-9)');
            $table->unsignedTinyInteger('thumbs_down')->default(0)->comment('Jumlah thumbs down (0-9)');
            $table->text('catatan')->nullable()->comment('Catatan untuk kriteria ini');
            $table->timestamps();

            // Unique: satu penilaian hanya punya satu record per kriteria
            $table->unique(['penilaian_id', 'kriteria'], 'unique_kriteria');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian_kriteria');
        Schema::dropIfExists('penilaian_kinerja');
    }
};
