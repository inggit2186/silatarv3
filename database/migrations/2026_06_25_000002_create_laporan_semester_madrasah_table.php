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
        Schema::create('ktd_laporan_semester_madrasah', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dept_id');
            $table->string('semester', 10); // ganjil / genap
            $table->string('tahun_ajaran', 20); // 2025/2026
            $table->string('status', 20)->default('draft'); // draft / submitted / revisi / approved
            $table->timestamp('submitted_at')->nullable();
            $table->text('catatan_admin')->nullable();

            // JSON columns for form data
            $table->json('keadaan_gedung')->nullable();
            $table->json('sarana_pendidikan')->nullable();
            $table->json('bantuan_pemerintah')->nullable();
            $table->json('bantuan_non_pemerintah')->nullable();
            $table->json('data_guru_pegawai')->nullable();
            $table->json('tingkat_pendidikan')->nullable();
            $table->json('sertifikasi')->nullable();
            $table->integer('banyak_hari_sekolah')->default(0);
            $table->json('absensi_siswa')->nullable();
            $table->json('luas_tanah')->nullable();
            $table->json('sertifikat_tanah')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('dept_id');
            $table->unique(['dept_id', 'semester', 'tahun_ajaran']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ktd_laporan_semester_madrasah');
    }
};
