<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ujian', function (Blueprint $table) {
            // Cover ujian ditampilkan pada daftar ujian di halaman peserta.
            // Path relatif terhadap disk "public" (contoh: ujian/cover/xxx.webp).
            $table->string('cover_path')->nullable()->after('deskripsi');
        });
    }

    public function down(): void
    {
        Schema::table('ujian', function (Blueprint $table) {
            $table->dropColumn('cover_path');
        });
    }
};
