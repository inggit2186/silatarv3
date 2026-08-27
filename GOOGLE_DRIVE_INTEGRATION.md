# Google Drive Integration - SILATAR V2

## Status: Ready for OAuth2 Setup

Package `yaza/laravel-google-drive-storage` sudah terinstall dan dikonfigurasi untuk menggunakan **OAuth2** dengan akun Google Drive yang sudah ada (100GB storage).

---

## Konfigurasi yang Sudah Dilakukan

1. ✅ Package terinstall: `yaza/laravel-google-drive-storage`
2. ✅ Service class dibuat: `app/Services/GoogleDriveService.php`
3. ✅ Config ditambahkan: `config/services.php` (OAuth2 format)
4. ✅ Disk config: `config/filesystems.php`
5. ✅ Test command siap: `php artisan gdrive:test`

---

## Yang Perlu Dilakukan Sekarang

### 1. Buat OAuth2 Credential di Google Cloud Console

**Ikuti panduan lengkap di [GOOGLE_DRIVE_SETUP.md](GOOGLE_DRIVE_SETUP.md)**

Singkatnya:
1. Buka Google Cloud Console → APIs & Services → Credentials
2. Buat OAuth 2.0 Client ID (Web application)
3. Tambahkan redirect URI: `http://localhost:8000/auth/google/callback`
4. Copy **Client ID** dan **Client Secret**
5. Dapatkan **Refresh Token** dengan authorize flow

---

### 2. Update .env File

Tambahkan variabel berikut di `.env`:

```env
# Google Drive OAuth2 Configuration
GOOGLE_DRIVE_CLIENT_ID=xxx.apps.googleusercontent.com
GOOGLE_DRIVE_CLIENT_SECRET=xxx
GOOGLE_DRIVE_REFRESH_TOKEN=xxx
GOOGLE_DRIVE_FOLDER_ID=1p8tupdHSriouRTfby-IjoFLSv755RCKu
```

**Catatan:**
- `GOOGLE_DRIVE_FOLDER_ID`: ID folder di Google Drive tempat file akan disimpan
  - Buka folder di Google Drive → Copy ID dari URL
  - Contoh: `https://drive.google.com/drive/folders/1p8tupdHSriouRTfby-IjoFLSv755RCKu`

---

### 3. Test Integrasi

```bash
php artisan gdrive:test
```

Jika berhasil:
```
✅ Configuration found
✅ Service initialized successfully
✅ Folder created/found
✅ File uploaded successfully
✅ File downloaded
✅ File deleted
✅ All Google Drive tests passed!
```

---

## Cara Penggunaan

### Upload File

```php
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;

// Controller method
public function uploadLaporan(Request $request)
{
    $request->validate([
        'file' => 'required|file|max:10240', // Max 10MB
    ]);

    $gdrive = new GoogleDriveService();
    $result = $gdrive->upload(
        file: $request->file('file'),
        filename: uniqid('laporan_') . '.' . $request->file('file')->getClientOriginalExtension(),
        subfolder: 'laporan/2026'
    );

    // $result['id']   → File ID di Google Drive
    // $result['path'] → File path di Drive
    // $result['url']  → Public URL file

    return response()->json($result);
}
```

### Download File

```php
use App\Services\GoogleDriveService;

public function downloadLaporan($filePath)
{
    $gdrive = new GoogleDriveService();
    $content = $gdrive->download($filePath);

    return response($content, 200)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'attachment; filename="laporan.pdf"');
}
```

### List Files

```php
use App\Services\GoogleDriveService;

public function listLaporan()
{
    $gdrive = new GoogleDriveService();
    $files = $gdrive->listFiles('laporan/2026');

    return view('laporan.index', compact('files'));
}
```

### Delete File

```php
use App\Services\GoogleDriveService;

public function deleteLaporan($filePath)
{
    $gdrive = new GoogleDriveService();
    $deleted = $gdrive->delete($filePath);

    return response()->json(['success' => $deleted]);
}
```

### Create Subfolder

```php
use App\Services\GoogleDriveService;

public function organizeFiles()
{
    $gdrive = new GoogleDriveService();

    // Buat folder berdasarkan tahun/bulan
    $gdrive->createOrGetFolder('laporan/2026-08');

    return response()->json(['success' => true]);
}
```

---

## Folder Structure yang Direkomendasikan

```
Google Drive/
└── SILATAR_UPLOADS/
    ├── laporan/
    │   ├── 2026/
    │   │   ├── 08/
    │   │   └── 09/
    │   └── ...
    ├── berkas/
    │   ├── ktp/
    │   └── surat/
    └── archive/
        └── 2025/
```

---

## Integration dengan Fitur Pelaporan

### Contoh: Upload saat Submit Laporan

```php
// Di controller yang handle pelaporan
public function submitLaporan(Request $request)
{
    // Validasi
    $validated = $request->validate([
        'deskripsi' => 'required|string',
        'files.*' => 'required|file|max:10240',
    ]);

    // Simpan laporan ke database
    $laporanId = DB::table('laporan')->insertGetId([
        'user_id' => auth()->id(),
        'deskripsi' => $validated['deskripsi'],
        'status' => 'pending',
        'created_at' => now(),
    ]);

    // Upload files ke Google Drive
    $gdrive = new GoogleDriveService();
    $uploadedFiles = [];

    foreach ($request->file('files') as $file) {
        $filename = "laporan_{$laporanId}_" . uniqid() . '.' . $file->getClientOriginalExtension();

        $result = $gdrive->upload(
            file: $file,
            filename: $filename,
            subfolder: "laporan/" . date('Y/m')
        );

        $uploadedFiles[] = $result;
    }

    // Simpan reference ke database
    DB::table('laporan_files')->insert(
        array_map(fn($file) => [
            'laporan_id' => $laporanId,
            'gdrive_path' => $file['path'],
            'filename' => $file['name'],
            'url' => $file['url'],
            'created_at' => now(),
        ], $uploadedFiles)
    );

    return redirect('/pengajuan-saya')
        ->with('success', 'Laporan berhasil dikirim');
}
```

---

## Monitoring & Maintenance

### Cek Storage Usage

```php
use App\Services\GoogleDriveService;

public function checkStorageUsage()
{
    $gdrive = new GoogleDriveService();
    $files = $gdrive->listFiles();

    $totalSize = array_reduce($files, function ($carry, $file) {
        return $carry + ($file['size'] ?? 0);
    }, 0);

    return response()->json([
        'total_files' => count($files),
        'total_size_mb' => round($totalSize / 1024 / 1024, 2),
        'total_size_gb' => round($totalSize / 1024 / 1024 / 1024, 2),
    ]);
}
```

### Auto-Cleanup Job (Optional)

```php
// App\Jobs\CleanupOldFiles.php
public function handle()
{
    $gdrive = new GoogleDriveService();
    $files = $gdrive->listFiles('laporan');

    $oneYearAgo = now()->subYear();

    foreach ($files as $file) {
        $createdTime = new \DateTime($file['timestamp'] ?? now());
        if ($createdTime < $oneYearAgo) {
            $gdrive->delete($file['path']);
        }
    }
}
```

---

## Troubleshooting

### Error: "invalid_grant"
- Refresh token expired atau tidak valid
- Dapatkan refresh token baru dengan mengikuti authorization flow

### Error: "access_not_configured"
- Google Drive API belum di-enable
- Buka Google Cloud Console → APIs & Services → Library → Enable Google Drive API

### Error: "insufficient permissions"
- Aplikasi belum di-authorize untuk akses Google Drive
- Jalankan authorization flow lagi dan berikan permission

### Error: "Missing configuration"
- Pastikan semua variabel sudah diisi di `.env`
- Jalankan `php artisan config:clear`

---

## Referensi

- [Google Drive API Quickstart](https://developers.google.com/drive/api/quickstart/php)
- [OAuth2 for Web Apps](https://developers.google.com/identity/protocols/oauth2/web-server)
- [Laravel Google Drive Storage Package](https://github.com/yaza-putu/laravel-google-drive-storage)
- [Laravel File Storage](https://laravel.com/docs/filesystem)

---

## Status

- [x] Package installed
- [x] Service class created
- [x] Config updated
- [x] Test command ready
- [ ] OAuth2 credential created
- [ ] .env configured with OAuth2
- [ ] Refresh token obtained
- [ ] Tested upload
- [ ] Tested download
