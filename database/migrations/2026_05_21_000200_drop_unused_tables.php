<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'activities',
            'app_history',
            'asn_harikerja',
            'failed_import',
            'hak_akses',
            'hari_kerja',
            'hari_libur',
            'kode_instansi',
            'ktd_acara',
            'ktd_bukutamu',
            'ktd_dokumen',
            'ktd_files',
            'ktd_ketidakhadiran',
            'ktd_kinerja',
            'ktd_klasifikasidata',
            'ktd_komentar',
            'ktd_konsul',
            'ktd_laporan_bulanan_madrasah',
            'ktd_laporan_semester_madrasah',
            'ktd_notif',
            'ktd_pengaduan',
            'ktd_presensi',
            'ktd_presensicuti',
            'ktd_presensierror',
            'ktd_presensifiles',
            'ktd_rating',
            'ktd_satudata',
            'ktd_slipgaji',
            'ktd_surat',
            'ktd_suratcuti',
            'ktd_surattugas',
            'ktd_tukin',
            'laporan_humas',
            'password_resets',
            'pengaduans',
            'peraturan_se',
            'personal_access_tokens',
            'rekap_absensi_pengaduan',
            'rekap_humas',
            'rekap_tukin',
            'rekap_um',
            'satker_apk',
            'satker_bezetting',
            'satker_ckh',
            'satker_device',
            'satker_humas',
            'satker_jabatan',
            'satker_kegiatan',
            'satker_laporan',
            'satker_nilaiskp',
            'satker_pemberkasan',
            'satker_resetdevice',
            'satker_sieka',
            'satker_sppt',
            'setjen_amprahgaji',
            'setjen_audit',
            'setjen_dockeuangan',
            'setjen_laporankeuangan',
            'short_url',
            'site_iklan',
            'site_setting',
            'tanggapans',
            'temp_db',
            'temp_presensi',
            'up_jabatan',
            'up_kgb',
            'up_pekerjaan',
            'up_pendidikan',
            'up_skp',
            'userlog',
            'users_berkas',
            'users_files',
            'users_request',
            'wa_chatbot',
            'wa_device',
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        // Intentionally left blank. These tables are legacy cleanup targets.
    }
};
