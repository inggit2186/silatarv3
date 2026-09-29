<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppPatch extends Model
{
    use HasFactory;

    protected $table = 'app_patches';

    protected $fillable = [
        'version',
        'version_code',
        'file_name',
        'file_path',
        'file_size',
        'md5',
        'changelog',
        'is_mandatory',
        'is_active',
        'update_type',
        'apk_url',
        'size_hint',
        'min_app_version',
        'max_app_version',
    ];

    protected $casts = [
        'is_mandatory' => 'boolean',
        'is_active' => 'boolean',
        'file_size' => 'integer',
        'version_code' => 'integer',
    ];

    /**
     * Scope for patch type updates (Dart only)
     */
    public function scopePatchType($query)
    {
        return $query->where('update_type', 'patch');
    }

    /**
     * Scope for APK type updates (full APK)
     */
    public function scopeApkType($query)
    {
        return $query->where('update_type', 'apk');
    }

    /**
     * Get download URL based on update type
     */
    public function getDownloadUrl(): string
    {
        if ($this->update_type === 'apk' && $this->apk_url) {
            return $this->apk_url;
        }

        return url('/api/patch/download/' . $this->id);
    }

    /**
     * Get formatted file size
     */
    public function getSizeHintAttribute($value)
    {
        if ($value) {
            return $value;
        }

        if ($this->file_size) {
            if ($this->file_size < 1024) {
                return $this->file_size . ' B';
            }
            if ($this->file_size < 1024 * 1024) {
                return round($this->file_size / 1024, 1) . ' KB';
            }
            return round($this->file_size / 1024 / 1024, 1) . ' MB';
        }

        return '0 B';
    }

    /**
     * Get latest active patch
     */
    public static function getLatest(): ?self
    {
        return static::where('is_active', true)
            ->orderBy('version_code', 'desc')
            ->first();
    }

    /**
     * Get available patch for given version
     */
    public static function getAvailableForVersion(string $version, int $versionCode): ?self
    {
        return static::where('is_active', true)
            ->where('version_code', '>', $versionCode)
            ->where(function ($query) use ($version) {
                $query->whereNull('min_app_version')
                    ->orWhere('min_app_version', '<=', $version);
            })
            ->where(function ($query) use ($version) {
                $query->whereNull('max_app_version')
                    ->orWhere('max_app_version', '>=', $version);
            })
            ->orderBy('version_code', 'desc')
            ->first();
    }
}
