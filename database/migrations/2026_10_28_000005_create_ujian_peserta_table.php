<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ujian_peserta', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ujian_id');
            $table->unsignedBigInteger('user_id');
            $table->string('status', 20)->default('belum_mulai');
            $table->dateTime('started_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->dateTime('submitted_at')->nullable();
            $table->decimal('nilai', 6, 2)->nullable();
            $table->timestamps();

            $table->unique(['ujian_id', 'user_id']);
            $table->index('user_id');
            $table->foreign('ujian_id')->references('id')->on('ujian')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ujian_peserta');
    }
};
