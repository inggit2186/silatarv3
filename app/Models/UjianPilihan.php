<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UjianPilihan extends Model
{
    protected $table = 'ujian_pilihan';

    protected $fillable = [
        'soal_id',
        'label',
        'teks',
        'benar',
        'urut',
    ];

    protected $casts = [
        'benar' => 'boolean',
        'urut' => 'integer',
    ];

    public function soal(): BelongsTo
    {
        return $this->belongsTo(UjianSoal::class, 'soal_id');
    }
}
