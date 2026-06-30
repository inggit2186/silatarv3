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
        Schema::table('users', function (Blueprint $table) {
            // Basic user info
            if (!Schema::hasColumn('users', 'dept_id')) {
                $table->unsignedBigInteger('dept_id')->nullable()->after('id');
                $table->index('dept_id');
            }

            if (!Schema::hasColumn('users', 'nomor_induk')) {
                $table->string('nomor_induk')->nullable()->unique()->after('dept_id');
            }

            if (!Schema::hasColumn('users', 'kat_jabatan')) {
                $table->string('kat_jabatan')->nullable()->after('nomor_induk');
            }

            if (!Schema::hasColumn('users', 'pekerjaan')) {
                $table->string('pekerjaan')->nullable()->after('kat_jabatan');
            }

            if (!Schema::hasColumn('users', 'satker')) {
                $table->string('satker')->nullable()->after('pekerjaan');
            }

            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('pegawai')->after('satker');
            }

            // Profile
            if (!Schema::hasColumn('users', 'jk')) {
                $table->string('jk', 10)->nullable()->after('role');
            }

            if (!Schema::hasColumn('users', 'tempat_lahir')) {
                $table->string('tempat_lahir')->nullable()->after('jk');
            }

            if (!Schema::hasColumn('users', 'tanggal_lahir')) {
                $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            }

            if (!Schema::hasColumn('users', 'alamat')) {
                $table->text('alamat')->nullable()->after('tanggal_lahir');
            }

            if (!Schema::hasColumn('users', 'telp')) {
                $table->string('telp', 20)->nullable()->after('alamat');
            }

            if (!Schema::hasColumn('users', 'nip')) {
                $table->string('nip', 50)->nullable()->after('telp');
            }

            if (!Schema::hasColumn('users', 'npwp')) {
                $table->string('npwp', 30)->nullable()->after('nip');
            }

            if (!Schema::hasColumn('users', 'rekening')) {
                $table->string('rekening')->nullable()->after('npwp');
            }

            if (!Schema::hasColumn('users', 'pp')) {
                $table->string('pp')->nullable()->after('npwp');
            }

            // Bio & Social
            if (!Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable()->after('pp');
            }

            if (!Schema::hasColumn('users', 'facebook')) {
                $table->string('facebook')->nullable()->after('bio');
            }

            if (!Schema::hasColumn('users', 'twitter')) {
                $table->string('twitter')->nullable()->after('facebook');
            }

            if (!Schema::hasColumn('users', 'linkedin')) {
                $table->string('linkedin')->nullable()->after('twitter');
            }

            if (!Schema::hasColumn('users', 'instagram')) {
                $table->string('instagram')->nullable()->after('linkedin');
            }

            // Family
            if (!Schema::hasColumn('users', 'nikah')) {
                $table->string('nikah', 20)->nullable()->after('instagram');
            }

            if (!Schema::hasColumn('users', 'jenis_pjob')) {
                $table->string('jenis_pjob')->nullable()->after('nikah');
            }

            if (!Schema::hasColumn('users', 'pjob')) {
                $table->string('pjob')->nullable()->after('jenis_pjob');
            }

            if (!Schema::hasColumn('users', 'jml_anak')) {
                $table->string('jml_anak', 10)->nullable()->after('pjob');
            }

            if (!Schema::hasColumn('users', 'req_tunjangan')) {
                $table->string('req_tunjangan', 10)->nullable()->after('jml_anak');
            }

            // Status & Position
            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status', 50)->nullable()->after('req_tunjangan');
            }

            if (!Schema::hasColumn('users', 'asn')) {
                $table->string('asn', 20)->nullable()->after('status');
            }

            if (!Schema::hasColumn('users', 'gol')) {
                $table->string('gol', 10)->nullable()->after('asn');
            }

            if (!Schema::hasColumn('users', 'jabatan')) {
                $table->string('jabatan')->nullable()->after('gol');
            }

            // TMT (Terhitung Mulai Tanggal)
            if (!Schema::hasColumn('users', 'tmt_cpns')) {
                $table->date('tmt_cpns')->nullable()->after('jabatan');
            }

            if (!Schema::hasColumn('users', 'tmt_pns')) {
                $table->date('tmt_pns')->nullable()->after('tmt_cpns');
            }

            if (!Schema::hasColumn('users', 'tmt_tugas')) {
                $table->date('tmt_tugas')->nullable()->after('tmt_pns');
            }

            if (!Schema::hasColumn('users', 'kgb')) {
                $table->date('kgb')->nullable()->after('tmt_tugas');
            }

            // Masa Kerja
            if (!Schema::hasColumn('users', 'masa_kerja_tahun')) {
                $table->string('masa_kerja_tahun', 10)->nullable()->after('kgb');
            }

            if (!Schema::hasColumn('users', 'masa_kerja_bulan')) {
                $table->string('masa_kerja_bulan', 10)->nullable()->after('masa_kerja_tahun');
            }

            // Education
            if (!Schema::hasColumn('users', 'ijazah_jurusan')) {
                $table->string('ijazah_jurusan')->nullable()->after('masa_kerja_bulan');
            }

            if (!Schema::hasColumn('users', 'ijazah_fakultas')) {
                $table->string('ijazah_fakultas')->nullable()->after('ijazah_jurusan');
            }

            if (!Schema::hasColumn('users', 'ijazah_universitas')) {
                $table->string('ijazah_universitas')->nullable()->after('ijazah_fakultas');
            }

            if (!Schema::hasColumn('users', 'ijazah_pendidikan')) {
                $table->string('ijazah_pendidikan')->nullable()->after('ijazah_universitas');
            }

            if (!Schema::hasColumn('users', 'ijazah_tahun_lulus')) {
                $table->string('ijazah_tahun_lulus', 10)->nullable()->after('ijazah_pendidikan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [
                'dept_id', 'nomor_induk', 'kat_jabatan', 'pekerjaan', 'satker', 'role',
                'jk', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'telp', 'nip', 'npwp',
                'rekening', 'pp', 'bio', 'facebook', 'twitter', 'linkedin', 'instagram',
                'nikah', 'jenis_pjob', 'pjob', 'jml_anak', 'req_tunjangan',
                'status', 'asn', 'gol', 'jabatan', 'tmt_cpns', 'tmt_pns', 'tmt_tugas',
                'kgb', 'masa_kerja_tahun', 'masa_kerja_bulan',
                'ijazah_jurusan', 'ijazah_fakultas', 'ijazah_universitas',
                'ijazah_pendidikan', 'ijazah_tahun_lulus'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
