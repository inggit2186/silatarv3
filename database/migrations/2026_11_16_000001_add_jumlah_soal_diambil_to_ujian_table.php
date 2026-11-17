<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ujian', function (Blueprint $table) {
            // Jumlah soal yang diambil per peserta dari bank soal ujian.
            // Null atau 0 berarti seluruh soal digunakan.
            $table->unsignedSmallInteger('jumlah_soal_diambil')->nullable()->after('acak_soal');
        });
    }

    public function down(): void
    {
        Schema::table('ujian', function (Blueprint $table) {
            $table->dropColumn('jumlah_soal_diambil');
        });
    }
};
