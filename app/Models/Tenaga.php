<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenaga extends Model
{
    protected $table = 'tenaga_ktd';

    protected $fillable = [
        'dept_id',
        'madrasah_id',
        'created_by',
        'user_id',
        'nama',
        'kat_jabatan',
        'status',
        'nomor_induk',
        'nik',
        'kk',
        'npwp',
        'nuptk',
        'npk',
        'nrg',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'nikah',
        'jenis_pjob',
        'pjob',
        'instansi',
        'req_tunjangan',
        'jml_anak',
        'nama_ibu',
        'nama_istri_suami',
        'golongan',
        'jabatan',
        'pekerjaan',
        'bidang_studi_diajar',
        'bidang_sertifikasi',
        'serdik',
        'jenis_guru',
        'pendidikan',
        'jurusan',
        'fakultas',
        'universitas',
        'tahun_lulus',
        'tmt_tugas',
        'kgb',
        'tmt_cpns',
        'tmt_pns',
        'masa_kerja_tahun',
        'masa_kerja_bulan',
        'email',
        'telp',
        'alamat_ktp',
        'alamat',
        'bio',
        'facebook',
        'twitter',
        'linkedin',
        'instagram',
        'keterangan',
        'is_active',
        'source_table',
    ];

    public $timestamps = true;

    /**
     * Get the user that owns this tenaga record
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Check if employee is CPNS
     */
    public function isCpns(): bool
    {
        return strtolower($this->status) === 'cpns';
    }

    /**
     * Check if employee is PNS
     */
    public function isPns(): bool
    {
        return strtolower($this->status) === 'pns';
    }

    /**
     * Check if employee is PPPK
     */
    public function isPppk(): bool
    {
        return strtolower($this->status) === 'pppk';
    }
}
