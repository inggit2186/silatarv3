<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class FileHelper
{
    /**
     * Get the storage path for pemberkasan files
     *
     * @param string $nomorInduk User's nomor_induk
     * @param string $filename File name
     * @return string Path relative to public disk
     */
    public static function getPemberkasanPath(string $nomorInduk, string $filename): string
    {
        return "users_berkas/{$nomorInduk}/Request/{$filename}";
    }

    /**
     * Get the full URL for pemberkasan file
     *
     * @param string $nomorInduk User's nomor_induk
     * @param string $filename File name
     * @return string Full URL to access the file
     */
    public static function getPemberkasanUrl(string $nomorInduk, string $filename): string
    {
        $path = self::getPemberkasanPath($nomorInduk, $filename);
        return Storage::disk('public')->url($path);
    }

    /**
     * Check if pemberkasan file exists
     *
     * @param string $nomorInduk User's nomor_induk
     * @param string $filename File name
     * @return bool
     */
    public static function pemberkasanFileExists(string $nomorInduk, string $filename): bool
    {
        $path = self::getPemberkasanPath($nomorInduk, $filename);
        return Storage::disk('public')->exists($path);
    }

    /**
     * Save pemberkasan file to public disk
     *
     * @param string $nomorInduk User's nomor_induk
     * @param string $filename File name
     * @param mixed $content File content
     * @return bool
     */
    public static function savePemberkasanFile(string $nomorInduk, string $filename, $content): bool
    {
        $path = self::getPemberkasanPath($nomorInduk, $filename);
        return Storage::disk('public')->put($path, $content);
    }

    /**
     * Get pemberkasan file content
     *
     * @param string $nomorInduk User's nomor_induk
     * @param string $filename File name
     * @return string|null File content or null if not found
     */
    public static function getPemberkasanFile(string $nomorInduk, string $filename): ?string
    {
        $path = self::getPemberkasanPath($nomorInduk, $filename);

        if (!Storage::disk('public')->exists($path)) {
            return null;
        }

        return Storage::disk('public')->get($path);
    }

    /**
     * Delete pemberkasan file
     *
     * @param string $nomorInduk User's nomor_induk
     * @param string $filename File name
     * @return bool
     */
    public static function deletePemberkasanFile(string $nomorInduk, string $filename): bool
    {
        $path = self::getPemberkasanPath($nomorInduk, $filename);

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }

        return true;
    }

    /**
     * Get legacy path (from users_berkas disk without Request subdirectory)
     * Used for migration purposes
     *
     * @param string $nomorInduk User's nomor_induk
     * @param string $filename File name
     * @return string Path relative to users_berkas disk
     */
    public static function getLegacyPath(string $nomorInduk, string $filename): string
    {
        return "{$nomorInduk}/{$filename}";
    }

    /**
     * Check if file exists in legacy location
     *
     * @param string $nomorInduk User's nomor_induk
     * @param string $filename File name
     * @return bool
     */
    public static function legacyFileExists(string $nomorInduk, string $filename): bool
    {
        $path = self::getLegacyPath($nomorInduk, $filename);
        return Storage::disk('users_berkas')->exists($path);
    }

    /**
     * Move file from legacy location to public location
     *
     * @param string $nomorInduk User's nomor_induk
     * @param string $filename File name
     * @param bool $deleteOld Whether to delete the old file
     * @return bool Success status
     */
    public static function migrateFileToPublic(string $nomorInduk, string $filename, bool $deleteOld = false): bool
    {
        $legacyPath = self::getLegacyPath($nomorInduk, $filename);
        $newPath = self::getPemberkasanPath($nomorInduk, $filename);

        // Check if legacy file exists
        if (!Storage::disk('users_berkas')->exists($legacyPath)) {
            return false;
        }

        // Read from legacy location
        $content = Storage::disk('users_berkas')->get($legacyPath);

        // Write to public location
        $success = Storage::disk('public')->put($newPath, $content);

        // Delete old file if requested
        if ($success && $deleteOld) {
            Storage::disk('users_berkas')->delete($legacyPath);
        }

        return $success;
    }

    /**
     * Get file size
     *
     * @param string $nomorInduk User's nomor_induk
     * @param string $filename File name
     * @return int|null File size in bytes or null if not found
     */
    public static function getPemberkasanFileSize(string $nomorInduk, string $filename): ?int
    {
        $path = self::getPemberkasanPath($nomorInduk, $filename);

        if (!Storage::disk('public')->exists($path)) {
            return null;
        }

        return Storage::disk('public')->size($path);
    }
}
