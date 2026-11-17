<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ujian_pelanggaran', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ujian_id');
            $table->unsignedBigInteger('user_id');
            // perpindahan_fokus | keluar_layar_penh | tanpa_layar_penh | sesi_ganda
            // tab_baru | salin | tempel | cetak
            $table->string('jenis', 30);
            $table->string('keterangan', 255)->nullable();
            $table->string('ip', 45)->nullable();
            $table->timestamps();

            $table->index(['ujian_id', 'user_id']);
            $table->foreign('ujian_id')->references('id')->on('ujian')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ujian_pelanggaran');
    }
};
