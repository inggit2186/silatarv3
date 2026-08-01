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
        Schema::create('pegawai_madrasah', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dept_id')->nullable()->index();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('name');
            $table->string('status', 50)->nullable(); // pns, pppk, honor
            $table->string('nomor_induk', 50)->nullable(); // NIP
            $table->string('nik', 20)->nullable();
            $table->string('npwp', 30)->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('jk', 10)->nullable(); // L, P
            $table->string('golongan', 20)->nullable();
            $table->string('jabatan')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->date('tmt_tugas')->nullable();
            $table->date('kgb')->nullable();
            $table->string('masa_kerja_tahun', 10)->nullable();
            $table->string('masa_kerja_bulan', 10)->nullable();
            $table->string('jurusan')->nullable();
            $table->string('fakultas')->nullable();
            $table->string('universitas')->nullable();
            $table->string('email')->nullable();
            $table->string('telp', 20)->nullable();
            $table->text('alamat_ktp')->nullable();
            $table->text('alamat')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index('name');
            $table->index('nomor_induk');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawai_madrasah');
    }
};
