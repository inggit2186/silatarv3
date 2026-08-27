<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ktd_presensi', function (Blueprint $table) {
            $table->text('m_alamat')->nullable()->after('m_location');
            $table->text('p_alamat')->nullable()->after('p_location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ktd_presensi', function (Blueprint $table) {
            $table->dropColumn(['m_alamat', 'p_alamat']);
        });
    }
};
