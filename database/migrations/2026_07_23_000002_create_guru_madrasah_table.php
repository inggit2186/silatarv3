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
        Schema::create('guru_madrasah', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dept_id')->nullable()->index();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('nama');
            $table->string('kat_jabatan', 50)->nullable(); // guru, kepala
            $table->string('status', 50)->nullable(); // PNS, PPPK, HONOR
            $table->string('nomor_induk', 50)->nullable(); // NIP/NUPTK
            $table->string('nik', 20)->nullable();
            $table->string('npwp', 30)->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('jenis_kelamin', 10)->nullable(); // L, P
            $table->string('golongan', 20)->nullable();
            $table->string('jabatan')->nullable();
            $table->string('bidang_studi_diajar')->nullable();
            $table->string('bidang_sertifikasi')->nullable();
            $table->string('serdik', 50)->nullable(); // sertifikasi, non-sertifikasi
            $table->string('nuptk', 20)->nullable();
            $table->string('npk', 20)->nullable();
            $table->string('nrg', 20)->nullable();
            $table->string('nama_ibu')->nullable();
            $table->date('tmt_tugas')->nullable();
            $table->date('kgb')->nullable();
            $table->string('pendidikan', 20)->nullable();
            $table->string('jurusan')->nullable();
            $table->string('fakultas')->nullable();
            $table->string('universitas')->nullable();
            $table->string('tahun_lulus', 10)->nullable();
            $table->string('email')->nullable();
            $table->string('telp', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->timestamps();

            $table->index('nama');
            $table->index('nomor_induk');
            $table->index('kat_jabatan');
            $table->index('serdik');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guru_madrasah');
    }
};
