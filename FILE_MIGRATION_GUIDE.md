# File Migration Guide - Pemberkasan Files

## Overview

File pemberkasan TPG dipindahkan dari **private storage** (`storage/app/users_berkas/`) ke **public storage** (`storage/app/public/users_berkas/`) agar bisa diakses via URL.

---

## Changes Made

### 1. FileHelper Class (NEW)
**File:** `app/Helpers/FileHelper.php`

Helper class untuk mengelola path file pemberkasan secara konsisten.

**Key Methods:**
- `getPemberkasanPath($nomorInduk, $filename)` - Returns path untuk storage public
- `getPemberkasanUrl($nomorInduk, $filename)` - Returns full URL untuk akses file
- `pemberkasanFileExists($nomorInduk, $filename)` - Check apakah file ada
- `savePemberkasanFile($nomorInduk, $filename, $content)` - Simpan file ke public
- `getPemberkasanFile($nomorInduk, $filename)` - Ambil isi file
- `migrateFileToPublic($nomorInduk, $filename, $deleteOld)` - Pindahkan file dari legacy ke public
- `getLegacyPath($nomorInduk, $filename)` - Returns path lama (untuk migrasi)

### 2. Migration Command (NEW)
**File:** `app/Console/Commands/MigratePemberkasanFilePaths.php`

Command untuk memindahkan file yang sudah ada dari lokasi lama ke lokasi baru.

**Signature:**
```bash
php artisan pemberkasan:migrate-file-paths
{--dry-run : Preview tanpa pindah file}
{--delete-old : Hapus file lama setelah dipindah}
{--batch=50 : Records per batch}
{--start-id= : Mulai dari ID tertentu}
{--limit= : Batasi jumlah record}
```

**Usage:**
```bash
# Preview dulu
php artisan pemberkasan:migrate-file-paths --dry-run

# Jalankan migrasi
php artisan pemberkasan:migrate-file-paths

# Jalankan dan hapus file lama
php artisan pemberkasan:migrate-file-paths --delete-old
```

### 3. Updated DownloadPemberkasanFiles Command
**File:** `app/Console/Commands/DownloadPemberkasanFiles.php`

**Changes:**
- Line 249: Changed path from `{nomor_induk}/{filename}` to `users_berkas/{nomor_induk}/Request/{filename}`
- Line 274: Changed disk from `users_berkas` to `public`
- Line 142: Updated success message

**Impact:** File yang di-download dari PTSP lama sekarang langsung tersimpan di lokasi yang benar.

### 4. Updated buildFilesSnapshot Method
**File:** `app/Http/Controllers/PageController.php`

**Changes:**
- Line 4799: Changed path from `{nomor_induk}/{filename}` to `{nomor_induk}/Request/{filename}`
- Line 4801: Updated comment
- Line 4802: Changed disk from `users_berkas` to `public`

**Impact:** File yang di-upload via form pengajuan layanan tersimpan di lokasi yang benar.

### 5. Updated uploadTpgFile Method
**File:** `app/Http/Controllers/PageController.php`

**Changes:**
- Line 4934: Changed disk from `users_berkas` to `public`

**Impact:** File TPG yang di-upload tersimpan di public storage.

### 6. Updated previewTpgFile Method
**File:** `app/Http/Controllers\PageController.php`

**Changes:**
- Line 1180-1194: Added fallback logic to check both new and legacy locations

**Impact:** File bisa diakses dari kedua lokasi (backwards compatible).

### 7. Updated previewTpgBulananFile Method
**File:** `app/Http/Controllers/PageController.php`

**Changes:**
- Line 1481-1495: Added fallback logic to check both new and legacy locations

**Impact:** File bisa diakses dari kedua lokasi (backwards compatible).

---

## File Locations

### Old Location (Legacy)
```
storage/app/users_berkas/{nomor_induk}/{filename}
```
- **Access:** Private (cannot be accessed via web URL)
- **Used by:** DownloadPemberkasanFiles (before update)

### New Location (Current)
```
storage/app/public/users_berkas/{nomor_induk}/Request/{filename}
```
- **Access:** Public (accessible via URL: https://domain.com/storage/users_berkas/...)
- **Used by:** Application upload, DownloadPemberkasanFiles (after update)

---

## Deployment Steps

### Step 1: Backup Files
```bash
# SSH ke server
ssh root@kemenagtanahdatar.id

# Backup file yang sudah ada
cd /www/wwwroot/kemenagtanahdatar.id
tar -czf users_berkas_backup_$(date +%Y%m%d_%H%M%S).tar.gz storage/app/users_berkas/

# Verify backup
ls -lh users_berkas_backup_*.tar.gz
```

### Step 2: Deploy Code Changes
```bash
# Pull code terbaru
cd /www/wwwroot/kemenagtanahdatar.id
git pull origin main

# Install dependencies jika ada
composer install --optimize-autoloader --no-dev

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Step 3: Verify Storage Symlink
```bash
# Pastikan symlink public/storage sudah ada
ls -la public/storage

# Jika belum ada, buat symlink
php artisan storage:link
```

### Step 4: Test Akses File
```bash
# Cek apakah ada file di lokasi lama
find storage/app/users_berkas -name "*.pdf" -o -name "*.jpg" | head -10

# Cek apakah ada file di lokasi baru
find storage/app/public/users_berkas -name "*.pdf" -o -name "*.jpg" | head -10
```

### Step 5: Jalankan Migration Command
```bash
# Preview dulu (dry-run)
php artisan pemberkasan:migrate-file-paths --dry-run

# Jalankan migrasi (tanpa hapus file lama dulu)
php artisan pemberkasan:migrate-file-paths

# Verifikasi
php artisan pemberkasan:migrate-file-paths --dry-run
```

### Step 6: Test Aplikasi
1. Login ke aplikasi
2. Buka **Pengajuan Saya**
3. Coba preview file pemberkasan yang sudah ada
4. Upload file baru via form pengajuan
5. Preview file yang baru di-upload

### Step 7: Cleanup (Opsional)
Setelah yakin semuanya berjalan lancar:

```bash
# Hapus file lama (backup sudah ada)
php artisan pemberkasan:migrate-file-paths --delete-old

# Atau manual cleanup
# Hati-hati! Pastikan backup sudah aman
# rm -rf storage/app/users_berkas/
```

---

## Rollback Plan

Jika ada masalah setelah deployment:

### Option 1: Rollback Code
```bash
git revert HEAD
composer install --optimize-autoloader --no-dev
php artisan cache:clear
```

### Option 2: Restore Files
```bash
# Restore file dari backup
cd /www/wwwroot/kemenagtanahdatar.id
tar -xzf users_berkas_backup_YYYYMMDD_HHMMSS.tar.gz
```

### Option 3: Emergency Fix
Edit manual path di preview methods untuk menggunakan lokasi lama:

```php
// Di previewTpgFile dan previewTpgBulananFile
$path = "{$user->nomor_induk}/{$fileEntry['filename']}";
abort_unless(Storage::disk('users_berkas')->exists($path), 404);
return Storage::disk('users_berkas')->response($path);
```

---

## Testing Checklist

- [ ] Backup file sudah aman
- [ ] Code sudah di-deploy
- [ ] Storage symlink sudah benar
- [ ] Migration command dry-run berhasil
- [ ] Migration command berhasil dijalankan
- [ ] File lama bisa diakses (fallback)
- [ ] File baru bisa di-upload
- [ ] File baru bisa di-preview
- [ ] Tidak ada error di log
- [ ] Semua pengguna bisa mengakses file mereka

---

## Monitoring

### Check Logs
```bash
# Monitor Laravel logs
tail -f storage/logs/laravel.log

# Cari error terkait file
grep -i "file" storage/logs/laravel.log | tail -20
```

### Check Disk Usage
```bash
# Cek usage sebelum migrasi
du -sh storage/app/users_berkas/

# Cek usage setelah migrasi
du -sh storage/app/public/users_berkas/
```

---

## Troubleshooting

### Masalah: File tidak bisa diakses via URL
**Solusi:**
```bash
# Cek symlink
ls -la public/storage

# Buat ulang jika perlu
php artisan storage:link --force

# Cek permission
chmod -R 755 storage/app/public/users_berkas
```

### Masalah: Migration command gagal
**Solusi:**
```bash
# Cek error detail
php artisan pemberkasan:migrate-file-paths --batch=1 2>&1 | head -50

# Cek database connection
php artisan migrate:status
```

### Masalah: File corrupt setelah migrasi
**Solusi:**
1. Restore dari backup
2. Jalankan migrasi lagi
3. Verifikasi checksum jika memungkinkan

---

## Notes

1. **Backwards Compatible:** Preview methods memiliki fallback untuk file di lokasi lama
2. **No Downtime:** Migration bisa dijalankan tanpa menghentikan aplikasi
3. **Rollback Ready:** Backup tersedia jika ada masalah
4. **Performance:** File di public storage bisa di-cache oleh CDN

---

## Contact

Jika ada masalah, hubungi:
- Developer: [Nama Developer]
- Server Admin: [Nama Admin]
- Backup Location: `/www/wwwroot/kemenagtanahdatar.id/users_berkas_backup_*.tar.gz`
