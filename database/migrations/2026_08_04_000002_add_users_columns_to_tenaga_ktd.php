<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom dari users ke tenaga_ktd
     */
    public function up(): void
    {
        if (!Schema::hasColumn('tenaga_ktd', 'tmt_cpns')) {
            Schema::table('tenaga_ktd', function (Blueprint $table) {
                $table->date('tmt_cpns')->nullable()->after('tmt_tugas');
            });
        }

        if (!Schema::hasColumn('tenaga_ktd', 'tmt_pns')) {
            Schema::table('tenaga_ktd', function (Blueprint $table) {
                $table->date('tmt_pns')->nullable()->after('tmt_cpns');
            });
        }

        if (!Schema::hasColumn('tenaga_ktd', 'nikah')) {
            Schema::table('tenaga_ktd', function (Blueprint $table) {
                $table->string('nikah', 10)->nullable()->after('jenis_kelamin');
            });
        }

        if (!Schema::hasColumn('tenaga_ktd', 'jenis_pjob')) {
            Schema::table('tenaga_ktd', function (Blueprint $table) {
                $table->string('jenis_pjob', 10)->nullable()->after('nikah');
            });
        }

        if (!Schema::hasColumn('tenaga_ktd', 'pjob')) {
            Schema::table('tenaga_ktd', function (Blueprint $table) {
                $table->string('pjob')->nullable()->after('jenis_pjob');
            });
        }

        if (!Schema::hasColumn('tenaga_ktd', 'req_tunjangan')) {
            Schema::table('tenaga_ktd', function (Blueprint $table) {
                $table->string('req_tunjangan', 10)->nullable()->after('pjob');
            });
        }

        if (!Schema::hasColumn('tenaga_ktd', 'jml_anak')) {
            Schema::table('tenaga_ktd', function (Blueprint $table) {
                $table->string('jml_anak', 10)->nullable()->after('req_tunjangan');
            });
        }

        if (!Schema::hasColumn('tenaga_ktd', 'nama_istri_suami')) {
            Schema::table('tenaga_ktd', function (Blueprint $table) {
                $table->string('nama_istri_suami')->nullable()->after('nama_ibu');
            });
        }

        if (!Schema::hasColumn('tenaga_ktd', 'kk')) {
            Schema::table('tenaga_ktd', function (Blueprint $table) {
                $table->string('kk', 20)->nullable()->after('nik');
            });
        }

        if (!Schema::hasColumn('tenaga_ktd', 'bio')) {
            Schema::table('tenaga_ktd', function (Blueprint $table) {
                $table->text('bio')->nullable()->after('alamat');
            });
        }

        if (!Schema::hasColumn('tenaga_ktd', 'facebook')) {
            Schema::table('tenaga_ktd', function (Blueprint $table) {
                $table->string('facebook', 255)->nullable()->after('bio');
            });
        }

        if (!Schema::hasColumn('tenaga_ktd', 'twitter')) {
            Schema::table('tenaga_ktd', function (Blueprint $table) {
                $table->string('twitter', 255)->nullable()->after('facebook');
            });
        }

        if (!Schema::hasColumn('tenaga_ktd', 'linkedin')) {
            Schema::table('tenaga_ktd', function (Blueprint $table) {
                $table->string('linkedin', 255)->nullable()->after('twitter');
            });
        }

        if (!Schema::hasColumn('tenaga_ktd', 'instagram')) {
            Schema::table('tenaga_ktd', function (Blueprint $table) {
                $table->string('instagram', 255)->nullable()->after('linkedin');
            });
        }

        if (!Schema::hasColumn('tenaga_ktd', 'remember_token')) {
            Schema::table('tenaga_ktd', function (Blueprint $table) {
                $table->string('remember_token', 100)->nullable()->after('instagram');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenaga_ktd', function (Blueprint $table) {
            $table->dropColumn([
                'tmt_cpns',
                'tmt_pns',
                'nikah',
                'jenis_pjob',
                'pjob',
                'req_tunjangan',
                'jml_anak',
                'nama_istri_suami',
                'kk',
                'bio',
                'facebook',
                'twitter',
                'linkedin',
                'instagram',
                'remember_token',
            ]);
        });
    }
};
