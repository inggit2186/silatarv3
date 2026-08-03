<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HariKerja extends Model
{
    protected $table = 'hari_kerja';

    protected $fillable = [
        'masuk',
        'biasa',
        'jumat',
        'sabtu',
        'minggu',
    ];

    public $timestamps = false;

    /**
     * Get format jam kerja (converts "07.30.59" to "07.30")
     */
    public function getMasukFormattedAttribute(): ?string
    {
        return $this->formatJam($this->masuk);
    }

    public function getBiasaFormattedAttribute(): ?string
    {
        return $this->formatJam($this->biasa);
    }

    public function getJumatFormattedAttribute(): ?string
    {
        return $this->formatJam($this->jumat);
    }

    public function getSabtuFormattedAttribute(): ?string
    {
        return $this->formatJam($this->sabtu);
    }

    public function getMingguFormattedAttribute(): ?string
    {
        return $this->formatJam($this->minggu);
    }

    /**
     * Format jam dari "07.30.59" ke "07.30"
     */
    private function formatJam(?string $jam): ?string
    {
        if (!$jam) return null;

        $parts = explode('.', $jam);
        if (count($parts) >= 2) {
            return $parts[0] . ':' . $parts[1];
        }

        return $jam;
    }

    /**
     * Check apakah hari ini adalah hari kerja
     */
    public function isWorkDay(int $dayOfWeek): bool
    {
        return match($dayOfWeek) {
            1 => true, // Senin
            2 => true, // Selasa
            3 => true, // Rabu
            4 => true, // Kamis
            5 => $this->jumat !== null, // Jumat
            6 => $this->sabtu !== null, // Sabtu
            7 => $this->minggu !== null, // Minggu
            default => false,
        };
    }
}
