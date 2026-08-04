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
        if (!Schema::hasTable('tenaga_ktd')) {
            Schema::create('tenaga_ktd', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dept_id')->nullable()->index();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('user_id')->nullable()->index();

            // Basic
            $table->string('nama');
            $table->string('kat_jabatan', 50)->nullable()->index();
            $table->string('status', 50)->nullable()->index();

            // IDs
            $table->string('nomor_induk', 50)->nullable()->unique();
            $table->string('nik', 20)->nullable();
            $table->string('npwp', 30)->nullable();
            $table->string('nuptk', 20)->nullable();
            $table->string('npk', 20)->nullable();
            $table->string('nrg', 20)->nullable();

            // Personal
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('jenis_kelamin', 10)->nullable();
            $table->string('nama_ibu')->nullable();

            // Position
            $table->string('golongan', 20)->nullable();
            $table->string('jabatan')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('bidang_studi_diajar')->nullable();
            $table->string('bidang_sertifikasi')->nullable();
            $table->string('serdik', 50)->nullable();
            $table->string('jenis_guru', 50)->nullable();

            // Education
            $table->string('pendidikan', 20)->nullable();
            $table->string('jurusan')->nullable();
            $table->string('fakultas')->nullable();
            $table->string('universitas')->nullable();
            $table->string('tahun_lulus', 10)->nullable();

            // Work
            $table->date('tmt_tugas')->nullable();
            $table->date('kgb')->nullable();
            $table->date('tmt_cpns')->nullable();
            $table->date('tmt_pns')->nullable();
            $table->string('masa_kerja_tahun', 10)->nullable();
            $table->string('masa_kerja_bulan', 10)->nullable();

            // Contact
            $table->string('email')->nullable();
            $table->string('telp', 20)->nullable();
            $table->text('alamat_ktp')->nullable();
            $table->text('alamat')->nullable();
            $table->text('keterangan')->nullable();

            // Meta
            $table->boolean('is_active')->default(true);
            $table->string('source_table', 50)->nullable();
            $table->timestamps();

            $table->index('nama');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenaga_ktd');
    }
};
