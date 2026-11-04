<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UjianPeserta extends Model
{
    protected $table = 'ujian_peserta';

    protected $fillable = [
        'ujian_id',
        'user_id',
        'status',
        'started_at',
        'ends_at',
        'submitted_at',
        'nilai',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ends_at' => 'datetime',
        'submitted_at' => 'datetime',
        'nilai' => 'float',
    ];

    public function ujian(): BelongsTo
    {
        return $this->belongsTo(Ujian::class, 'ujian_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeAktif($query)
    {
        return $query->whereIn('status', ['belum_mulai', 'berjalan']);
    }
}
