<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UjianSoal extends Model
{
    protected $table = 'ujian_soal';

    protected $fillable = [
        'ujian_id',
        'tipe',
        'pertanyaan',
        'petunjuk',
        'poin',
        'urut',
    ];

    protected $casts = [
        'poin' => 'integer',
        'urut' => 'integer',
    ];

    public function ujian(): BelongsTo
    {
        return $this->belongsTo(Ujian::class, 'ujian_id');
    }

    public function pilihan(): HasMany
    {
        return $this->hasMany(UjianPilihan::class, 'soal_id')->orderBy('urut');
    }
}
