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
        // Tabel sudah ada, tambah kolom baru jika belum ada
        // Set default value untuk kolom grade yang sudah ada
        DB::statement('ALTER TABLE ktd_tukin ALTER COLUMN grade SET DEFAULT 0');

        Schema::table('ktd_tukin', function (Blueprint $table) {
            if (!Schema::hasColumn('ktd_tukin', 'periode')) {
                $table->string('periode', 7)->after('id')->comment('Format: YYYY-MM');
            }

            if (!Schema::hasColumn('ktd_tukin', 'user_nip')) {
                $table->string('user_nip', 18)->after('periode')->comment('NIP pegawai');
            }

            if (!Schema::hasColumn('ktd_tukin', 'tk_jumlah')) {
                $table->decimal('tk_jumlah', 15, 2)->default(0)->after('tukin')->comment('TK Jumlah');
            }

            if (!Schema::hasColumn('ktd_tukin', 'tk_persen')) {
                $table->decimal('tk_persen', 5, 2)->default(0)->after('tk_jumlah')->comment('TK Persen (%)');
            }

            if (!Schema::hasColumn('ktd_tukin', 'tl')) {
                $table->decimal('tl', 15, 2)->default(0)->after('tk_persen')->comment('TL (Potongan Telat)');
            }

            if (!Schema::hasColumn('ktd_tukin', 'tl_persen')) {
                $table->decimal('tl_persen', 5, 2)->default(0)->after('tl')->comment('TL Persen (%)');
            }

            if (!Schema::hasColumn('ktd_tukin', 'psw')) {
                $table->decimal('psw', 15, 2)->default(0)->after('tl_persen')->comment('Potongan PSW');
            }

            if (!Schema::hasColumn('ktd_tukin', 'psw_persen')) {
                $table->decimal('psw_persen', 5, 2)->default(0)->after('psw')->comment('PSW Persen (%)');
            }

            if (!Schema::hasColumn('ktd_tukin', 'hukdis')) {
                $table->decimal('hukdis', 15, 2)->default(0)->after('psw_persen')->comment('Hukuman Disiplin');
            }

            if (!Schema::hasColumn('ktd_tukin', 'hukdis_persen')) {
                $table->decimal('hukdis_persen', 5, 2)->default(0)->after('hukdis')->comment('Hukdis Persen (%)');
            }

            if (!Schema::hasColumn('ktd_tukin', 'cpns')) {
                $table->decimal('cpns', 15, 2)->default(0)->after('hukdis_persen')->comment('Potongan CPNS');
            }

            if (!Schema::hasColumn('ktd_tukin', 'cpns_persen')) {
                $table->decimal('cpns_persen', 5, 2)->default(0)->after('cpns')->comment('CPNS Persen (%)');
            }

            if (!Schema::hasColumn('ktd_tukin', 'skp')) {
                $table->decimal('skp', 15, 2)->default(0)->after('cpns_persen')->comment('Potongan SKP');
            }

            if (!Schema::hasColumn('ktd_tukin', 'skp_persen')) {
                $table->decimal('skp_persen', 5, 2)->default(0)->after('skp')->comment('SKP Persen (%)');
            }

            if (!Schema::hasColumn('ktd_tukin', 'tb')) {
                $table->decimal('tb', 15, 2)->default(0)->after('skp_persen')->comment('Potongan TB');
            }

            if (!Schema::hasColumn('ktd_tukin', 'tb_persen')) {
                $table->decimal('tb_persen', 5, 2)->default(0)->after('tb')->comment('TB Persen (%)');
            }

            if (!Schema::hasColumn('ktd_tukin', 'potongan_lain')) {
                $table->decimal('potongan_lain', 15, 2)->default(0)->after('tb_persen')->comment('Potongan lain-lain');
            }

            if (!Schema::hasColumn('ktd_tukin', 'potongan_lain_persen')) {
                $table->decimal('potongan_lain_persen', 5, 2)->default(0)->after('potongan_lain')->comment('Persen lain-lain');
            }

            if (!Schema::hasColumn('ktd_tukin', 'total_potongan')) {
                $table->decimal('total_potongan', 15, 2)->default(0)->after('potongan_lain_persen')->comment('Total Potongan');
            }

            if (!Schema::hasColumn('ktd_tukin', 'import_batch_id')) {
                $table->string('import_batch_id', 50)->nullable()->after('total_potongan');
            }

            if (!Schema::hasColumn('ktd_tukin', 'imported_by')) {
                $table->integer('imported_by')->nullable()->after('import_batch_id');
            }

            if (!Schema::hasColumn('ktd_tukin', 'imported_at')) {
                $table->timestamp('imported_at')->nullable()->after('imported_by');
            }

            if (!Schema::hasColumn('ktd_tukin', 'import_source')) {
                $table->string('import_source', 50)->default('excel_manual')->after('imported_at');
            }
        });

        // Tambah indexes jika belum ada
        Schema::table('ktd_tukin', function (Blueprint $table) {
            try {
                $table->index('user_nip');
            } catch (\Exception $e) {
                // Index sudah ada, skip
            }

            try {
                $table->index('periode');
            } catch (\Exception $e) {
                // Index sudah ada, skip
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ktd_tukin');
    }
};
