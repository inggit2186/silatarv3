# Console Commands Documentation

Dokumentasi lengkap untuk semua Artisan Console Commands yang tersedia di SILATAR V2.

## Daftar Isi

- [Presensi](#presensi)
  - [`presensi:import`](#presensiimport)
  - [`presensi:export`](#presensiexport)
- [CKH (Capai Kinerja Harian)](#ckh-capai-kinerja-harian)
  - [`ckh:auto-rekap`](#ckhauto-rekap)
  - [`ckh:migrate-files`](#ckhmigrate-files)
- [Pemberkasan](#pemberkasan)
  - [`pemberkasan:migrate-files`](#pemberkasanmigrate-files)
  - [`pemberkasan:migrate-file-paths`](#pemberkasanmigrate-file-paths)
  - [`pemberkasan:download-files`](#pemberkasandownload-files)
- [Satker & Kegiatan](#satker--kegiatan)
  - [`satker:convert-kegiatan`](#satkerconvert-kegiatan)
  - [`satker:import-supplement`](#satkerimport-supplement)
- [Madrasah](#madrasah)
  - [`madrasah:migrate-data`](#madrasahmigrate-data)
  - [`madrasah:migrate-tenaga`](#madrasahmigrate-tenaga)
  - [`madrasah:cleanup-users`](#madrasahcleanup-users)
- [Tukin](#tukin)
  - [`tukin:import`](#tukinimport)
- [Utilitas](#utilitas)
  - [`holidays:fetch`](#holidaysfetch)
  - [`images:optimize`](#imagesoptimize)
  - [`sync:pp`](#syncpp)
  - [`gdrive:test`](#gdrivetest)

---

## Presensi

### `presensi:import`

Import data presensi dari file Excel secara bulk.

**Signature:**
```bash
php artisan presensi:import {--path=} {--rollback=} {--history} {--dry-run} {--force} {--keep-files}
```

**Opsi:**

| Opsi | Deskripsi |
|------|-----------|
| `--path` | Path folder yang berisi file Excel (default: `public/uploads/pusaka/presensi`) |
| `--rollback={batch_id}` | Rollback import berdasarkan Batch ID |
| `--history` | Tampilkan riwayat import |
| `--dry-run` | Validasi saja, tidak import |
| `--force` | Skip konfirmasi |
| `--keep-files` | Jangan hapus file setelah import |

**Contoh Penggunaan:**

```bash
# Import dari folder default
php artisan presensi:import

# Import dari folder tertentu
php artisan presensi:import --path=public/uploads/custom-presensi

# Preview tanpa import
php artisan presensi:import --dry-run

# Lihat riwayat
php artisan presensi:import --history

# Rollback import tertentu
php artisan presensi:import --rollback=BATCH_2024_01_15_123456
```

**Fitur:**
- Parse Excel files (.xlsx)
- Validasi data sebelum import
- Deteksi department dari filename
- Batch processing dengan progress bar
- Rollback capability

---

### `presensi:export`

Export data presensi ke file Excel.

**Signature:**
```bash
php artisan presensi:export {--user=} {--dept=} {--month=} {--year=} {--type=} {--output=} {--all}
```

**Opsi:**

| Opsi | Deskripsi |
|------|-----------|
| `--user={id}` | Export untuk user tertentu |
| `--dept={id}` | Export untuk unit kerja tertentu |
| `--month={1-12}` | Bulan export (default: bulan sekarang) |
| `--year={YYYY}` | Tahun export (default: tahun sekarang) |
| `--type={horizontal\|detail-horizontal}` | Tipe export (default: horizontal) |
| `--output={path}` | Folder output (default: `exports/presensi`) |
| `--all` | Export semua dept_id |

**Contoh Penggunaan:**

```bash
# Export bulan ini
php artisan presensi:export

# Export bulan tertentu
php artisan presensi:export --month=3 --year=2024

# Export per unit kerja
php artisan presensi:export --dept=5

# Export satu user
php artisan presensi:export --user=123

# Export semua unit kerja
php artisan presensi:export --all
```

**Output:**
- File Excel tersimpan di `storage/app/exports/presensi/{year}/{month}/`
- Format: `presensi_{nama_unit_kerja}_{year}_{month}.xlsx`

---

## CKH (Capai Kinerja Harian)

### `ckh:auto-rekap`

Auto generate laporan kinerja CKH untuk seluruh pegawai aktif.

**Signature:**
```bash
php artisan ckh:auto-rekap {--bulan=} {--dry-run} {--user=}
```

**Opsi:**

| Opsi | Deskripsi |
|------|-----------|
| `--bulan=YYYY-MM` | Bulan yang akan diproses (default: bulan lalu) |
| `--dry-run` | Preview tanpa generate PDF |
| `--user={id}` | Proses user tertentu saja |

**Contoh Penggunaan:**

```bash
# Generate untuk bulan lalu
php artisan ckh:auto-rekap

# Generate bulan tertentu
php artisan ckh:auto-rekap --bulan=2026-01

# Preview only
php artisan ckh:auto-rekap --bulan=2026-01 --dry-run

# Untuk satu user
php artisan ckh:auto-rekap --bulan=2026-01 --user=123
```

**Fitur:**
- Generate PDF laporan harian
- Otomatis tentukan penandatangan (Kepala/PLT)
- Progress bar dengan status per user
- Skip user tanpa kegiatan

**Output:**
- PDF tersimpan di `storage/app/public/satker_ckh/{user_id}/`
- Update record di tabel `satker_ckh`

---

### `ckh:migrate-files`

Migrasi file CKH dari PTSP lama ke storage lokal.

**Signature:**
```bash
php artisan ckh:migrate-files {--dry-run} {--chunk=} {--start-id=} {--limit=}
```

**Opsi:**

| Opsi | Deskripsi |
|------|-----------|
| `--dry-run` | Preview tanpa download |
| `--chunk={num}` | Records per batch (default: 50) |
| `--start-id={id}` | Mulai dari ID tertentu |
| `--limit={num}` | Batasi total record |

**Contoh Penggunaan:**

```bash
# Preview
php artisan ckh:migrate-files --dry-run

# Full migration
php artisan ckh:migrate-files

# Dari ID tertentu
php artisan ckh:migrate-files --start-id=1000
```

**Output:**
- File tersimpan di `storage/app/public/satker_ckh/{user_id}/`

---

## Pemberkasan

### `pemberkasan:migrate-files`

Migrasi file dari `satker_filepemberkasan` ke format JSON di `satker_pemberkasan`.

**Signature:**
```bash
php artisan pemberkasan:migrate-files {--dry-run} {--force}
```

**Contoh Penggunaan:**

```bash
# Preview
php artisan pemberkasan:migrate-files --dry-run

# Execute
php artisan pemberkasan:migrate-files --force
```

**Fitur:**
- Konversi data per-barisd ke format JSON
- Ambil snapshot persyaratan dari `ktd_syarat`
- Skip jika sudah dimigrasi

---

### `pemberkasan:migrate-file-paths`

Migrasi file dari `users_berkas` ke `public/users_berkas`.

**Signature:**
```bash
php artisan pemberkasan:migrate-file-paths {--dry-run} {--keep-old} {--start-id=} {--limit=}
```

**Opsi:**

| Opsi | Deskripsi |
|------|-----------|
| `--dry-run` | Preview tanpa pindah |
| `--keep-old` | Simpan file lama |
| `--start-id={id}` | Mulai dari ID tertentu |
| `--limit={num}` | Batasi jumlah record |

**Contoh Penggunaan:**

```bash
# Preview
php artisan pemberkasan:migrate-file-paths --dry-run

# Full migration (hapus lama)
php artisan pemberkasan:migrate-file-paths

# Migration dengan retain file lama
php artisan pemberkasan:migrate-file-paths --keep-old
```

**Output:**
- File dipindahkan ke `storage/app/public/users_berkas/{nomor_induk}/Request/`

---

### `pemberkasan:download-files`

Download file pemberkasan dari API PTSP lama.

**Signature:**
```bash
php artisan pemberkasan:download-files {--dry-run} {--chunk=} {--start-id=} {--limit=} {--noreq=} {--from-date=}
```

**Opsi:**

| Opsi | Deskripsi |
|------|-----------|
| `--dry-run` | Preview tanpa download |
| `--chunk={num}` | Records per batch (default: 50) |
| `--start-id={id}` | Mulai dari ID tertentu |
| `--limit={num}` | Batasi jumlah record |
| `--noreq={noreq}` | Proses noreq spesifik |
| `--from-date=YYYY-MM-DD` | Filter dari tanggal |

**Contoh Penggunaan:**

```bash
# Preview
php artisan pemberkasan:download-files --dry-run

# Download semua
php artisan pemberkasan:download-files

# Dari tanggal tertentu
php artisan pemberkasan:download-files --from-date=2024-01-01
```

---

## Satker & Kegiatan

### `satker:convert-kegiatan`

Konversi format kegiatan dari per-row ke JSON per-tanggal.

**Signature:**
```bash
php artisan satker:convert-kegiatan {--dry-run} {--chunk=} {--start-id=} {--limit=} {--user=}
```

**Deskripsi:**
Menggabungkan multiple row kegiatan per user per tanggal menjadi satu record JSON.

**Opsi:**

| Opsi | Deskripsi |
|------|-----------|
| `--dry-run` | Preview tanpa perubahan |
| `--chunk={num}` | Records per batch (default: 100) |
| `--start-id={id}` | Mulai dari ID tertentu |
| `--limit={num}` | Batasi grup yang diproses |
| `--user={id}` | Convert hanya user tertentu |

**Contoh Penggunaan:**

```bash
# Preview
php artisan satker:convert-kegiatan --dry-run

# Execute
php artisan satker:convert-kegiatan

# Dari ID tertentu
php artisan satker:convert-kegiatan --start-id=5000
```

**Fitur:**
- Streaming SQL untuk memory efficient
- Progress bar dengan memory usage
- Merge duplicate rows ke JSON

---

### `satker:import-supplement`

Import data kegiatan tambahan dari file SQL.

**Signature:**
```bash
php artisan satker:import-supplement {file} {--dry-run} {--chunk=}
```

**Argument:**

| Argument | Deskripsi |
|----------|-----------|
| `file` | Path ke file SQL |

**Opsi:**

| Opsi | Deskripsi |
|------|-----------|
| `--dry-run` | Preview tanpa menyimpan |
| `--chunk={num}` | Ukuran chunk (default: 1000) |

**Contoh Penggunaan:**

```bash
# Preview
php artisan satker:import-supplement /path/to/data.sql --dry-run

# Import
php artisan satker:import-supplement /path/to/data.sql
```

**Fitur:**
- Stream parsing untuk file besar
- Skip tanggal yang sudah ada
- Progress indicator

---

## Madrasah

### `madrasah:migrate-data`

Migrasi data madrasah dari `ktd_department` ke `ktd_madrasah`.

**Signature:**
```bash
php artisan madrasah:migrate-data
```

**Contoh Penggunaan:**

```bash
php artisan madrasah:migrate-data
```

**Fitur:**
- Buat record di `ktd_madrasah`
- Update `madrasah_id` di `users`
- Update `madrasah_id` di `tenaga_ktd`
- Update `madrasah_id` di laporan semester & bulanan

---

### `madrasah:migrate-tenaga`

Migrasi dan konsolidasi data tenaga kependidikan.

**Signature:**
```bash
php artisan madrasah:migrate-tenaga {--create-table} {--migrate-guru} {--migrate-pegawai} {--migrate-users} {--migrate-all} {--drop-old} {--dry-run}
```

**Opsi:**

| Opsi | Deskripsi |
|------|-----------|
| `--create-table` | Buat tabel tenaga_ktd jika belum ada |
| `--migrate-guru` | Migrasi dari guru_madrasah |
| `--migrate-pegawai` | Migrasi dari pegawai_madrasah |
| `--migrate-users` | Migrasi dari tabel users |
| `--migrate-all` | Jalankan semua migrasi |
| `--drop-old` | Hapus tabel lama setelah migrasi |
| `--dry-run` | Preview tanpa perubahan |

**Contoh Penggunaan:**

```bash
# Buat tabel saja
php artisan madrasah:migrate-tenaga --create-table

# Full migration
php artisan madrasah:migrate-tenaga --migrate-all

# Dengan cleanup
php artisan madrasah:migrate-tenaga --migrate-all --drop-old

# Dry run
php artisan madrasah:migrate-tenaga --migrate-all --dry-run
```

**Tabel Target:**
- `tenaga_ktd` - tabel unified untuk semua tenaga kependidikan

**Tabel Sumber:**
- `guru_madrasah` - data guru
- `pegawai_madrasah` - data pegawai
- `users` - data user

---

### `madrasah:cleanup-users`

Cleanup kolom tidak diperlukan dari tabel users.

**Signature:**
```bash
php artisan madrasah:cleanup-users {--list} {--cleanup-all} {--remove-duplicates} {--drop-old-tables} {--dry-run}
```

**Opsi:**

| Opsi | Deskripsi |
|------|-----------|
| `--list` | Tampilkan kolom yang bisa dihapus |
| `--cleanup-all` | Hapus semua kolom yang sudah dimigrasikan |
| `--remove-duplicates` | Hapus kolom duplikat |
| `--drop-old-tables` | Drop tabel guru_madrasah & pegawai_madrasah |
| `--dry-run` | Preview tanpa perubahan |

**Contoh Penggunaan:**

```bash
# List kolom
php artisan madrasah:cleanup-users --list

# Cleanup all
php artisan madrasah:cleanup-users --cleanup-all --dry-run

# Drop old tables
php artisan madrasah:cleanup-users --drop-old-tables
```

**Kolom yang Dimigrasikan:**
- `gol`, `ijazah_*`, `tmt_cpns`, `tmt_pns`, `nikah`, dll.

---

## Tukin

### `tukin:import`

Import data Tukin dari file Excel.

**Signature:**
```bash
php artisan tukin:import {--path=} {--rollback=} {--history} {--dry-run} {--force} {--keep-files}
```

**Opsi:**

| Opsi | Deskripsi |
|------|-----------|
| `--path` | Path folder file Excel (default: `public/uploads/pusaka/tukin`) |
| `--rollback={batch_id}` | Rollback import |
| `--history` | Tampilkan riwayat import |
| `--dry-run` | Validasi saja |
| `--force` | Skip konfirmasi |
| `--keep-files` | Jangan hapus file |

**Contoh Penggunaan:**

```bash
# Import
php artisan tukin:import

# Preview
php artisan tukin:import --dry-run

# Rollback
php artisan tukin:import --rollback=BATCH_ID
```

---

## Utilitas

### `holidays:fetch`

Ambil data hari libur Indonesia dari API dan simpan ke database.

**Signature:**
```bash
php artisan holidays:fetch {year?}
```

**Argument:**

| Argument | Deskripsi |
|----------|-----------|
| `year` | Tahun (default: tahun sekarang) |

**Contoh Penggunaan:**

```bash
# Fetch tahun ini
php artisan holidays:fetch

# Fetch tahun tertentu
php artisan holidays:fetch 2026
```

**Fitur:**
- Fetch dari Nager.Date API
- Fallback ke holiday database lokal
- Support hari libur Islam (approximate)

**Tabel Target:**
- `hari_libur`

---

### `images:optimize`

Optimasi gambar dengan konversi ke WebP.

**Signature:**
```bash
php artisan images:optimize {--path=} {--quality=} {--resize=} {--dry-run} {--replace}
```

**Opsi:**

| Opsi | Deskripsi |
|------|-----------|
| `--path` | Path spesifik yang dioptimasi |
| `--quality={1-100}` | Kualitas WebP (default: 80) |
| `--resize={px}` | Max lebar (optional) |
| `--dry-run` | Preview saja |
| `--replace` | Replace file asli |

**Contoh Penggunaan:**

```bash
# Optimize folder default
php artisan images:optimize

# Path tertentu
php artisan images:optimize --path=avatars

# Kualitas tinggi
php artisan images:optimize --quality=90

# Preview
php artisan images:optimize --dry-run

# Replace original
php artisan images:optimize --replace
```

---

### `sync:pp`

Sync profile picture dari API eksternal ke storage lokal.

**Signature:**
```bash
php artisan sync:pp {--dry-run}
```

**Opsi:**

| Opsi | Deskripsi |
|------|-----------|
| `--dry-run` | Preview tanpa simpan |

**Contoh Penggunaan:**

```bash
# Sync all
php artisan sync:pp

# Preview
php artisan sync:pp --dry-run
```

**Fitur:**
- Download dari API PTSP
- Resize ke 300x300
- Kompres ke WebP (75%)
- Hapus file lama

**Output:**
- Tersimpan di `storage/app/public/users_berkas/{nomor_induk}/`

---

### `gdrive:test`

Test integrasi Google Drive OAuth2.

**Signature:**
```bash
php artisan gdrive:test
```

**Contoh Penggunaan:**

```bash
php artisan gdrive:test
```

**Fitur:**
- Cek konfigurasi
- Test folder creation
- Test upload
- Test download
- Test delete

---

## Tips & Tricks

### Dry Run First
Selalu gunakan `--dry-run` untuk preview sebelum execute command yang modify data.

### Progress Monitoring
Command dengan batch processing menampilkan progress bar. Di Windows, progress bar mungkin tidak tampil dengan benar di beberapa terminal.

### Memory Management
Command yang memproses banyak data (seperti import/export) secara otomatis melakukan garbage collection secara periodik.

### Rollback Strategy
Gunakan `--history` untuk melihat riwayat import, lalu gunakan `--rollback={batch_id}` untuk membatalkan.

### Scheduling
Commands ini bisa dijadwalkan di `app/Console/Kernel.php` untuk automation:

```php
$schedule->command('holidays:fetch')->yearly();
$schedule->command('ckh:auto-rekap')->monthlyOn(1, '00:00');
```
