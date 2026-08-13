<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JanjiTemu extends Model
{
    protected $table = 'ktd_bukutamu';

    public $timestamps = true;

    protected $fillable = [
        'nomor_induk',
        'kategori',
        'tipe',
        'nama',
        'waktu',
        'nip_tujuan',
        'tujuan',
        'asal',
        'status',
        'onStaff',
        'komen',
        'ttd',
    ];

    protected $casts = [
        'waktu' => 'datetime',
    ];

    // ═══════════════════════════════════════════════════════════════════════
    // RELATIONSHIPS
    // ═══════════════════════════════════════════════════════════════════════

    /**
     * User yang mengajukan janji temu (via nomor_induk)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nomor_induk', 'nomor_induk');
    }

    /**
     * Pegawai tujuan (via nip_tujuan untuk tipe ASN)
     */
    public function pegawaiTujuan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nip_tujuan', 'nomor_induk');
    }

    /**
     * Unit kerja tujuan (via nip_tujuan untuk tipe satker)
     */
    public function unitTujuan(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'nip_tujuan', 'id');
    }

    /**
     * Staff yang menangani
     */
    public function staffPenangan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'onStaff', 'id');
    }

    // ═══════════════════════════════════════════════════════════════════════
    // SCOPES
    // ═══════════════════════════════════════════════════════════════════════

    /**
     * Filter by status
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Filter for specific user (via nomor_induk)
     */
    public function scopeForUser($query, string $nomorInduk)
    {
        return $query->where('nomor_induk', $nomorInduk);
    }

    /**
     * Filter by tipe (asn/satker)
     */
    public function scopeTipe($query, string $tipe)
    {
        return $query->where('tipe', $tipe);
    }

    /**
     * Filter appointments assigned to a specific staff
     */
    public function scopeAssignedTo($query, int $userId)
    {
        return $query->where('onStaff', $userId);
    }

    /**
     * Filter appointments for a department (for admin)
     */
    public function scopeForDepartment($query, int $deptId)
    {
        return $query->where(function ($q) use ($deptId) {
            // Direct appointments to pegawai in this dept
            $q->whereHas('pegawaiTujuan', function ($sub) use ($deptId) {
                $sub->where('dept_id', $deptId);
            })
            // Or appointments directly to this satker
            ->orWhere('nip_tujuan', $deptId);
        });
    }

    /**
     * Scope: Filter by date
     */
    public function scopeOnDate($query, $date)
    {
        return $query->whereDate('waktu', $date);
    }

    /**
     * Scope: Filter for today
     */
    public function scopeToday($query)
    {
        return $query->whereDate('waktu', now()->toDateString());
    }

    // ═══════════════════════════════════════════════════════════════════════
    // ACCESSORS & METHODS
    // ═══════════════════════════════════════════════════════════════════════

    /**
     * Get formatted status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'APPOINTMENT' => 'Menunggu Konfirmasi',
            'PENDING' => 'Menunggu',
            'APPROVED' => 'Disetujui',
            'REJECTED' => 'Ditolak',
            'CANCELLED' => 'Dibatalkan',
            default => $this->status,
        };
    }

    /**
     * Get formatted waktu
     */
    public function getWaktuFormattedAttribute(): string
    {
        return $this->waktu->format('d M Y, H:i');
    }

    /**
     * Get status badge color (for mobile)
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'APPOINTMENT' => 'yellow',
            'PENDING' => 'blue',
            'APPROVED' => 'green',
            'REJECTED' => 'red',
            'CANCELLED' => 'gray',
            default => 'gray',
        };
    }

    /**
     * Check if can be cancelled
     */
    public function canCancel(): bool
    {
        return in_array($this->status, ['APPOINTMENT', 'PENDING']);
    }

    /**
     * Check if can be approved/rejected (admin only)
     */
    public function canProcess(): bool
    {
        return in_array($this->status, ['APPOINTMENT', 'PENDING']);
    }
}
