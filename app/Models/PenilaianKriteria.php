<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenilaianKriteria extends Model
{
    use HasFactory;

    protected $table = 'penilaian_kriteria';

    protected $fillable = [
        'penilaian_id',
        'kriteria',
        'thumbs_up',
        'thumbs_down',
        'catatan',
    ];

    protected $casts = [
        'penilaian_id' => 'integer',
        'thumbs_up' => 'integer',
        'thumbs_down' => 'integer',
    ];

    // Konstanta kriteria penilaian
    public const KRITERIA = [
        'orientasi_pelayanan' => [
            'nama' => 'Orientasi Pelayanan',
            'deskripsi' => 'Fokus pada pelayanan kepada masyarakat',
            'icon' => 'heart-handshake',
        ],
        'akuntabel' => [
            'nama' => 'Akuntabel',
            'deskripsi' => 'Tanggung jawab dan transparansi',
            'icon' => 'shield-check',
        ],
        'kompeten' => [
            'nama' => 'Kompeten',
            'deskripsi' => 'Keahlian dan kapabilitas',
            'icon' => 'award',
        ],
        'harmonis' => [
            'nama' => 'Harmonis',
            'deskripsi' => 'Kerukunan dan teamwork',
            'icon' => 'users',
        ],
        'loyal' => [
            'nama' => 'Loyal',
            'deskripsi' => 'Kesetiaan terhadap organisasi',
            'icon' => 'flag',
        ],
        'adaptif' => [
            'nama' => 'Adaptif',
            'deskripsi' => 'Kemampuan beradaptasi',
            'icon' => 'refresh-cw',
        ],
        'kolaboratif' => [
            'nama' => 'Kolaboratif',
            'deskripsi' => 'Kemampuan kolaborasi',
            'icon' => 'git-branch',
        ],
    ];

    /**
     * Get the parent penilaian
     */
    public function penilaian(): BelongsTo
    {
        return $this->belongsTo(PenilaianKinerja::class, 'penilaian_id');
    }

    /**
     * Get info kriteria dari konstanta
     */
    public function getInfoAttribute(): ?array
    {
        return self::KRITERIA[$this->kriteria] ?? null;
    }

    /**
     * Get nama kriteria
     */
    public function getNamaAttribute(): string
    {
        return self::KRITERIA[$this->kriteria]['nama'] ?? $this->kriteria;
    }

    /**
     * Get icon kriteria
     */
    public function getIconAttribute(): string
    {
        return self::KRITERIA[$this->kriteria]['icon'] ?? 'circle';
    }

    /**
     * Get net score untuk kriteria ini
     */
    public function getNetScoreAttribute(): int
    {
        return $this->thumbs_up - $this->thumbs_down;
    }

    /**
     * Static: Get all kriteria keys
     */
    public static function getKriteriaKeys(): array
    {
        return array_keys(self::KRITERIA);
    }

    /**
     * Static: Get all kriteria dengan info lengkap
     */
    public static function getAllKriteria(): array
    {
        return self::KRITERIA;
    }
}
