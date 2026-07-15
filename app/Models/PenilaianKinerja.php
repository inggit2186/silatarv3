<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PenilaianKinerja extends Model
{
    use HasFactory;

    protected $table = 'penilaian_kinerja';

    protected $fillable = [
        'tahun',
        'triwulan',
        'pejabat_id',
        'penilai_id',
        'catatan_umum',
        'total_thumbs_up',
        'total_thumbs_down',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'triwulan' => 'integer',
        'pejabat_id' => 'integer',
        'penilai_id' => 'integer',
        'total_thumbs_up' => 'integer',
        'total_thumbs_down' => 'integer',
    ];

    /**
     * Get the pejabat (user yang dinilai)
     */
    public function pejabat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pejabat_id');
    }

    /**
     * Get the penilai (kepala yang menilai)
     */
    public function penilai(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penilai_id');
    }

    /**
     * Get all kriteria for this penilaian
     */
    public function kriterias(): HasMany
    {
        return $this->hasMany(PenilaianKriteria::class, 'penilaian_id');
    }

    /**
     * Get kriteria by nama kriteria
     */
    public function getKriteria(string $kriteria): ?PenilaianKriteria
    {
        return $this->kriterias()->where('kriteria', $kriteria)->first();
    }

    /**
     * Recalculate total thumbs from all kriteria
     */
    public function recalculateTotals(): void
    {
        $totals = $this->kriterias()->selectRaw('SUM(thumbs_up) as total_up, SUM(thumbs_down) as total_down')->first();

        $this->update([
            'total_thumbs_up' => $totals->total_up ?? 0,
            'total_thumbs_down' => $totals->total_down ?? 0,
        ]);
    }

    /**
     * Scope: Filter by tahun and triwulan
     */
    public function scopePeriode($query, int $tahun, int $triwulan)
    {
        return $query->where('tahun', $tahun)->where('triwulan', $triwulan);
    }

    /**
     * Scope: Filter by penilai (kepala)
     */
    public function scopeByPenilai($query, int $penilaiId)
    {
        return $query->where('penilai_id', $penilaiId);
    }

    /**
     * Get triwulan label
     */
    public function getTriwulanLabelAttribute(): string
    {
        return 'Triwulan ' . $this->triwulan . ' / ' . $this->tahun;
    }

    /**
     * Get net score (thumbs up - thumbs down)
     */
    public function getNetScoreAttribute(): int
    {
        return $this->total_thumbs_up - $this->total_thumbs_down;
    }
}
