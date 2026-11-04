<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UjianJawaban extends Model
{
    protected $table = 'ujian_jawaban';

    protected $fillable = [
        'ujian_id',
        'soal_id',
        'user_id',
        'pilihan_id',
        'esai',
        'file_path',
        'file_nama',
        'skor',
        'dijawab_at',
    ];

    protected $casts = [
        'dijawab_at' => 'datetime',
        'skor' => 'float',
    ];

    public function ujian(): BelongsTo
    {
        return $this->belongsTo(Ujian::class, 'ujian_id');
    }

    public function soal(): BelongsTo
    {
        return $this->belongsTo(UjianSoal::class, 'soal_id');
    }

    public function pilihan(): BelongsTo
    {
        return $this->belongsTo(UjianPilihan::class, 'pilihan_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
