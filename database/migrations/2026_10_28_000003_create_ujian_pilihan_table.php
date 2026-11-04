<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ujian_pilihan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('soal_id');
            $table->string('label', 5);
            $table->text('teks');
            $table->boolean('benar')->default(false);
            $table->unsignedInteger('urut')->default(0);
            $table->timestamps();

            $table->index(['soal_id', 'urut']);
            $table->foreign('soal_id')->references('id')->on('ujian_soal')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ujian_pilihan');
    }
};
