<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ujian_peserta', function (Blueprint $table) {
            // Data perangkat saat sesi dimulai, dipakai untuk mendeteksi
            // satu akun yang dipakai dari dua perangkat berbeda bersamaan.
            $table->string('ip', 45)->nullable()->after('user_id');
            $table->string('user_agent', 255)->nullable()->after('ip');
            $table->string('sidik_perangkat', 64)->nullable()->after('user_agent');

            $table->index('sidik_perangkat');
        });
    }

    public function down(): void
    {
        Schema::table('ujian_peserta', function (Blueprint $table) {
            $table->dropIndex(['sidik_perangkat']);
            $table->dropColumn(['ip', 'user_agent', 'sidik_perangkat']);
        });
    }
};
