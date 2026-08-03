<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KtdPresensi extends Model
{
    protected $table = 'ktd_presensi';

    protected $fillable = [
        'user_nip',
        'dept_id',
        'tanggal',
        'm_absen',
        'm_latitude',
        'm_longitude',
        'm_distance',
        'm_location',
        'p_absen',
        'p_latitude',
        'p_longitude',
        'p_distance',
        'p_location',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'm_latitude' => 'decimal:7',
        'm_longitude' => 'decimal:7',
        'm_distance' => 'decimal:2',
        'p_latitude' => 'decimal:7',
        'p_longitude' => 'decimal:2',
        'p_distance' => 'decimal:2',
    ];

    /**
     * Get the user that owns the presensi (via user_nip).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_nip', 'nomor_induk');
    }

    /**
     * Get the department (unit kerja).
     */
    public function dept()
    {
        return $this->belongsTo(Department::class, 'dept_id');
    }

    /**
     * Check if has presensi masuk
     */
    public function hasMasuk(): bool
    {
        return !empty($this->m_absen);
    }

    /**
     * Check if has presensi pulang
     */
    public function hasPulang(): bool
    {
        return !empty($this->p_absen);
    }

    /**
     * Scope: Filter by user NIP
     */
    public function scopeForUser($query, $userNip)
    {
        return $query->where('user_nip', $userNip);
    }

    /**
     * Scope: Filter by date
     */
    public function scopeOnDate($query, $date)
    {
        return $query->whereDate('tanggal', $date);
    }

    /**
     * Scope: Filter by month
     */
    public function scopeForMonth($query, $year, $month)
    {
        return $query->whereYear('tanggal', $year)->whereMonth('tanggal', $month);
    }
}
