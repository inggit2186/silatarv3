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
        Schema::table('ktd_tukin', function (Blueprint $table) {
            // Add new columns for calculated tukin results
            if (!Schema::hasColumn('ktd_tukin', 'tukin_final')) {
                $table->decimal('tukin_final', 15, 2)->nullable()->after('tukin')
                    ->comment('Hasil akhir tukin setelah potongan');
            }

            if (!Schema::hasColumn('ktd_tukin', 'total_potongan_final')) {
                $table->decimal('total_potongan_final', 15, 2)->default(0)->after('tukin_final')
                    ->comment('Total potongan yang dihitung dari presensi');
            }

            if (!Schema::hasColumn('ktd_tukin', 'detail_potongan_calc')) {
                $table->json('detail_potongan_calc')->nullable()->after('total_potongan_final')
                    ->comment('JSON detail pemotongan per jenis: {tl1: 2, tl2: 1, psw3: 3, tanpa_keterangan: 1}');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ktd_tukin', function (Blueprint $table) {
            $table->dropColumn([
                'tukin_final',
                'total_potongan_final',
                'detail_potongan_calc',
            ]);
        });
    }
};
