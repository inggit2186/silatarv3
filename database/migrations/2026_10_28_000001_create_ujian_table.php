<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ujian', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 50)->unique();
            $table->string('judul', 255);
            $table->text('deskripsi')->nullable();
            $table->text('materi')->nullable();
            $table->unsignedSmallInteger('durasi_menit')->default(60);
            $table->string('status', 20)->default('draft');
            $table->dateTime('mulai_at')->nullable();
            $table->dateTime('selesai_at')->nullable();
            $table->boolean('acak_soal')->default(false);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ujian');
    }
};
