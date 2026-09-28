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
