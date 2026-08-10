<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Madrasah extends Model
{
    protected $table = 'ktd_madrasah';

    protected $fillable = [
        'dept_id',
        'nama',
        'nsm',
        'npsm',
        'kategori',
        'status_lembaga',
        'jalan',
        'jorong',
        'nagari',
        'kecamatan',
        'koordinat',
        'telepon',
        'email',
        'website',
        'waktu_belajar',
        'visi',
        'sk_pendirian',
        'tanggal_sk',
        'komite_lembaga',
        'akreditasi',
        'tanggal_akreditasi',
        'status_kkm',
        'jarak_pusat_provinsi',
        'jarak_pusat_kabupaten',
        'jarak_kecamatan',
        'jarak_kanwil_kemenag',
        'jarak_kemenag_kab',
        'jarak_kua',
        'jarak_ra_terdekat',
        'jarak_mi_terdekat',
        'jarak_mts_terdekat',
        'jarak_ma_terdekat',
        'jarak_pontren_terdekat',
        'jarak_tk_terdekat',
        'jarak_sd_terdekat',
        'jarak_smp_terdekat',
        'jarak_sma_terdekat',
        'status',
    ];

    /**
     * Get the department (induk organisasi)
     */
    public function dept()
    {
        return $this->belongsTo(Department::class, 'dept_id');
    }

    /**
     * Get users assigned to this madrasah
     */
    public function users()
    {
        return $this->hasMany(User::class, 'madrasah_id');
    }

    /**
     * Get tenaga_ktd (pegawai/guru) for this madrasah
     */
    public function tenagaKtd()
    {
        return $this->hasMany(TenagaKtd::class, 'madrasah_id');
    }
}
