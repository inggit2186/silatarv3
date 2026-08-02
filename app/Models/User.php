<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'nomor_induk', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get penilaian where user is yang dinilai (pejabat)
     */
    public function penilaianSebagaiPejabat(): HasMany
    {
        return $this->hasMany(PenilaianKinerja::class, 'pejabat_id');
    }

    /**
     * Get penilaian where user is yang menilai (kepala)
     */
    public function penilaianSebagaiPenilai(): HasMany
    {
        return $this->hasMany(PenilaianKinerja::class, 'penilai_id');
    }

    /**
     * Get kat_jabatan label
     */
    public function getKatJabatanLabelAttribute(): string
    {
        $labels = [
            'kepala' => 'Kepala Kantor',
            'kasubbag' => 'Kepala Sub Bagian',
            'kasubag' => 'Kepala Sub Bagian',
            'kasi' => 'Kepala Seksi',
            'kaur' => 'Kepala Urusan',
            'pelaksana' => 'Pelaksana',
            'staf' => 'Staf',
            'honorer' => 'Honorer',
            'guru' => 'Guru',
        ];

        return $labels[$this->kat_jabatan] ?? ucfirst($this->kat_jabatan ?? '-');
    }

    /**
     * Get department (unit kerja)
     */
    public function dept()
    {
        return $this->belongsTo(Department::class, 'dept_id');
    }

    /**
     * Scope: Get users yang bisa dinilai (kasubbag, kasi, kepala)
     */
    public function scopeDapatDinilai($query)
    {
        return $query->whereIn('kat_jabatan', ['kasubbag', 'kasubag', 'kasi', 'kepala']);
    }

    /**
     * Check apakah user adalah kepala
     */
    public function isKepala(): bool
    {
        return $this->role === 'kepala';
    }
}
