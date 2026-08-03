<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'ktd_department';

    protected $fillable = [
        'nama',
        'kode',
        'latitude',
        'longitude',
        'radius',
        'jam_masuk',
        'jam_pulang',
        'hari_kerja',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'dept_id');
    }

    public function instansi()
    {
        return $this->hasOne(Instansi::class, 'dept_id');
    }

    /**
     * Get hari kerja schedule
     * Menggunakan field 'hari_kerja' yang berisi ID ke tabel hari_kerja
     */
    public function hariKerja()
    {
        return $this->belongsTo(HariKerja::class, 'hari_kerja');
    }
}
