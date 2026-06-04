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
        Schema::table('satker_kegiatan', function (Blueprint $table) {
            $table->json('data_json')->nullable()->after('satuan');
            $table->index(['user_id', 'tanggal'], 'idx_satker_kegiatan_user_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('satker_kegiatan', function (Blueprint $table) {
            $table->dropIndex('idx_satker_kegiatan_user_date');
            $table->dropColumn('data_json');
        });
    }
};
