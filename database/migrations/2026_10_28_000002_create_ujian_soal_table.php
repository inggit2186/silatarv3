<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ujian_soal', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ujian_id');
            $table->string('tipe', 20)->default('pilihan_ganda');
            $table->text('pertanyaan');
            $table->text('petunjuk')->nullable();
            $table->unsignedSmallInteger('poin')->default(1);
            $table->unsignedInteger('urut')->default(0);
            $table->timestamps();

            $table->index(['ujian_id', 'urut']);
            $table->foreign('ujian_id')->references('id')->on('ujian')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ujian_soal');
    }
};
