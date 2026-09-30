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
        'build_number',
        'patch_count',
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
        'patch_count' => 'integer',
        'build_number' => 'integer',
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
        // For APK with external URL (starts with http)
        if ($this->update_type === 'apk' && $this->apk_url && str_starts_with($this->apk_url, 'http')) {
            return $this->apk_url;
        }

        // Otherwise use the download endpoint
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
     * Get latest APK (not patch)
     */
    public static function getLatestApk(): ?self
    {
        return static::where('is_active', true)
            ->where('update_type', 'apk')
            ->orderBy('version_code', 'desc')
            ->first();
    }

    /**
     * Get latest patch for specific version_code and patch_count
     */
    public static function getLatestPatchForVersion(int $versionCode, ?int $currentPatchCount = null): ?self
    {
        $query = static::where('is_active', true)
            ->where('update_type', 'patch')
            ->where('version_code', $versionCode);

        if ($currentPatchCount !== null) {
            $query->where('patch_count', '>', $currentPatchCount);
        }

        return $query->orderBy('patch_count', 'desc')->first();
    }

    /**
     * Get available APK update for given build number
     */
    public static function getAvailableApkUpdateByBuildNumber(int $buildNumber): ?self
    {
        return static::where('is_active', true)
            ->where('update_type', 'apk')
            ->where(function ($query) use ($buildNumber) {
                $query->where('build_number', '>', $buildNumber)
                    ->orWhere(function ($q) use ($buildNumber) {
                        // Fallback to version_code if build_number not set
                        $q->whereNull('build_number')
                          ->where('version_code', '>', $buildNumber);
                    });
            })
            ->orderByRaw("COALESCE(build_number, version_code) DESC")
            ->first();
    }

    /**
     * Get available APK update for given version (legacy, deprecated)
     */
    public static function getAvailableApkUpdate(int $versionCode): ?self
    {
        return static::where('is_active', true)
            ->where('update_type', 'apk')
            ->where('version_code', '>', $versionCode)
            ->orderBy('version_code', 'desc')
            ->first();
    }

    /**
     * Get full version string (with patch count if applicable)
     * Format: "2.0.0" for APK, "2.0.0.1" for patch
     */
    public function getFullVersionAttribute(): string
    {
        if ($this->update_type === 'patch' && $this->patch_count > 0) {
            return $this->version . '.' . $this->patch_count;
        }
        return $this->version;
    }
}
