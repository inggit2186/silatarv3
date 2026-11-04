<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ujian_jawaban', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ujian_id');
            $table->unsignedBigInteger('soal_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('pilihan_id')->nullable();
            $table->text('esai')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_nama')->nullable();
            $table->decimal('skor', 6, 2)->nullable();
            $table->dateTime('dijawab_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'soal_id']);
            $table->index('ujian_id');
            $table->index('soal_id');
            $table->foreign('ujian_id')->references('id')->on('ujian')->cascadeOnDelete();
            $table->foreign('soal_id')->references('id')->on('ujian_soal')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ujian_jawaban');
    }
};
