<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ujian extends Model
{
    protected $table = 'ujian';

    protected $fillable = [
        'kode',
        'judul',
        'deskripsi',
        'materi',
        'durasi_menit',
        'status',
        'mulai_at',
        'selesai_at',
        'acak_soal',
        'created_by',
    ];

    protected $casts = [
        'mulai_at' => 'datetime',
        'selesai_at' => 'datetime',
        'acak_soal' => 'boolean',
        'durasi_menit' => 'integer',
    ];

    public function soal(): HasMany
    {
        return $this->hasMany(UjianSoal::class, 'ujian_id')->orderBy('urut');
    }

    public function peserta(): HasMany
    {
        return $this->hasMany(UjianPeserta::class, 'ujian_id');
    }

    public function jawaban(): HasMany
    {
        return $this->hasMany(UjianJawaban::class, 'ujian_id');
    }

    public function scopeTerbit($query)
    {
        return $query->where('status', 'terbit');
    }
}
