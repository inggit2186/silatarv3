<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KtdPresensiFile extends Model
{
    protected $table = 'ktd_presensifiles';

    protected $fillable = [
        'dept',
        'bulan',
        'tahun',
        'presensi',
        'uangmakan',
        'tukin',
    ];

    protected $casts = [
        'bulan' => 'integer',
        'tahun' => 'integer',
    ];

    /**
     * Get the department name
     */
    public function getDeptName(): string
    {
        return $this->dept ?? 'Unknown';
    }

    /**
     * Get presensi file full path (path file detail presensi)
     */
    public function getPresensiFullPath(): ?string
    {
        return $this->presensi ? storage_path('app/' . $this->presensi) : null;
    }

    /**
     * Get uangmakan file full path (path file rekap presensi)
     */
    public function getUangmakanFullPath(): ?string
    {
        return $this->uangmakan ? storage_path('app/' . $this->uangmakan) : null;
    }
}
