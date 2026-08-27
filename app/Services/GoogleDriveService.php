<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Yaza\LaravelGoogleDriveStorage\Gdrive;

class GoogleDriveService
{
    protected ?Client $client = null;
    protected ?Drive $service = null;
    protected string $folderId;

    public function __construct()
    {
        $this->folderId = config('services.gdrive.folder_id');
    }

    /**
     * Upload file to Google Drive using OAuth2 (Recommended)
     */
    public function upload(UploadedFile|string $file, string $filename = null, string $subfolder = ''): array
    {
        $fileContent = $file instanceof UploadedFile
            ? file_get_contents($file->getRealPath())
            : file_get_contents($file);

        $filename = $filename ?? ($file instanceof UploadedFile ? $file->getClientOriginalName() : basename($file));

        // Create folder path
        $path = $subfolder ? "{$subfolder}/{$filename}" : $filename;

        // Upload using yaza/laravel-google-drive-storage package
        Storage::disk('gdrive')->put($path, $fileContent);

        // Get file ID (you may need to list and find the file)
        $fileId = $this->getFileId($path);

        return [
            'id' => $fileId,
            'name' => $filename,
            'path' => $path,
            'url' => $fileId ? $this->getPublicUrl($fileId) : null,
        ];
    }

    /**
     * Get file ID by path
     */
    protected function getFileId(string $path): ?string
    {
        try {
            $files = Storage::disk('gdrive')->listContents($path);
            if (!empty($files)) {
                return $files[0]['path'] ?? null;
            }
        } catch (\Exception $e) {
            // Fallback
        }
        return null;
    }

    /**
     * Download file from Google Drive
     */
    public function download(string $path): string
    {
        return Storage::disk('gdrive')->get($path);
    }

    /**
     * Delete file from Google Drive
     */
    public function delete(string $path): bool
    {
        return Storage::disk('gdrive')->delete($path);
    }

    /**
     * List files in folder
     */
    public function listFiles(string $folder = ''): array
    {
        return Storage::disk('gdrive')->listContents($folder, true);
    }

    /**
     * Create or get subfolder
     */
    public function createOrGetFolder(string $folderPath): void
    {
        Storage::disk('gdrive')->makeDirectory($folderPath);
    }

    /**
     * Get public URL for file
     */
    public function getPublicUrl(string $fileId): string
    {
        return "https://drive.google.com/uc?export=view&id={$fileId}";
    }

    /**
     * Get MIME type from filename
     */
    protected function getMimeType(string $filename): string
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'txt' => 'text/plain',
        ];

        return $mimeTypes[$extension] ?? 'application/octet-stream';
    }
}
