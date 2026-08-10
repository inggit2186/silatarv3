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
        Schema::create('ktd_madrasah', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dept_id')->nullable()->index();
            $table->string('nama');
            $table->string('nsm', 50)->nullable()->index();
            $table->string('npsm', 50)->nullable();
            $table->string('kategori', 20)->nullable()->index();
            $table->string('status_lembaga', 20)->nullable();

            // Alamat
            $table->string('jalan')->nullable();
            $table->string('jorong')->nullable();
            $table->string('nagari')->nullable();
            $table->string('kecamatan')->nullable();

            // Kontak
            $table->string('koordinat', 100)->nullable();
            $table->string('telepon', 30)->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();

            // Data Institusi
            $table->string('waktu_belajar', 20)->nullable();
            $table->text('visi')->nullable();
            $table->string('sk_pendirian')->nullable();
            $table->date('tanggal_sk')->nullable();
            $table->string('komite_lembaga', 30)->nullable();
            $table->string('akreditasi', 10)->nullable();
            $table->date('tanggal_akreditasi')->nullable();
            $table->string('status_kkm', 20)->nullable();

            // Jarak
            $table->string('jarak_pusat_provinsi', 50)->nullable();
            $table->string('jarak_pusat_kabupaten', 50)->nullable();
            $table->string('jarak_kecamatan', 50)->nullable();
            $table->string('jarak_kanwil_kemenag', 50)->nullable();
            $table->string('jarak_kemenag_kab', 50)->nullable();
            $table->string('jarak_kua', 50)->nullable();
            $table->string('jarak_ra_terdekat', 50)->nullable();
            $table->string('jarak_mi_terdekat', 50)->nullable();
            $table->string('jarak_mts_terdekat', 50)->nullable();
            $table->string('jarak_ma_terdekat', 50)->nullable();
            $table->string('jarak_pontren_terdekat', 50)->nullable();
            $table->string('jarak_tk_terdekat', 50)->nullable();
            $table->string('jarak_sd_terdekat', 50)->nullable();
            $table->string('jarak_smp_terdekat', 50)->nullable();
            $table->string('jarak_sma_terdekat', 50)->nullable();

            // Meta
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            // Unique constraint
            $table->unique(['dept_id', 'nsm']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ktd_madrasah');
    }
};
