<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tidak perlu tambah kolom di ktd_presensi
        // File disimpan di tabel ktd_presensifiles dengan path
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak ada yang perlu di-drop
    }
};
