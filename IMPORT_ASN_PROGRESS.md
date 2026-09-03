# Progress Import Data ASN dari Excel

## Overview

Import data ASN (Aparatur Sipil Negara) dari file Excel (`dataASNSILATAR.xlsx`) ke database `tenaga_ktd` dan `users` di aplikasi SILATAR V2.

## Status: DRAFT (PLAN) — Revised

### Catatan Perubahan (3 perubahan dari user):
1. **NIP tidak ditemukan di DB → SKIP saja**, tidak perlu buat record baru
2. **Kategori Bank → tabel `users`**, bukan `tenaga_ktd`
3. **Hanya update kolom yang ada di Excel**, kolom lain yang tidak ada di Excel **jangan diubah/diganggu**

---

## Analisis Data

### Struktur File Excel (`dataASNSILATAR.xlsx`)

| Kolom | Header | Tipe | Isi | Contoh |
|-------|--------|------|-----|--------|
| A | No | int | Nomor urut | 1, 7, 27 |
| B | Kategori | string | Kategori pegawai | pns, honorer, pppk |
| C | ASN | string | Jenis ASN | adm, penghulu, penyuluh, guru, kepala, kasubbag, kasi, kaur |
| D | Nama | string | Nama lengkap | PUSPITA DWI INDRA S. Akun |
| E | JK | string | Jenis kelamin | Pria, Wanita |
| F | NIP | string | Nomor Induk Pegawai | 199907092025052005 |
| G | NIK | string | Nomor Induk Kependudukan | 1304044907990001 |
| H | KK | string | Nomor Kartu Keluarga | 1304043107070090 |
| I | NPWP | string | NPWP | 139099808204000 |
| J | Serdik | string | Sertifikat | NONE (semua) |
| K | Kategori Bank | string | Kategori rekening bank | KEAGAMAAN_BSI, KEPENDIDIKAN_BSI, dll |
| L | Rekening | string | Nomor rekening | 7312708315 |

**Total baris data: 1.225 row** (termasuk header = 1.226 baris)

### Distribusi Data

| Kolom | Unique Values |
|-------|---------------|
| Kategori (B) | pns=800, honorer=37, pppk=388 |
| ASN (C) | adm=257, penghulu=15, penyuluh=81, guru=795, kepala=44, kasubbag=1, kasi=6, kaur=19 |
| JK (E) | Wanita=842, Pria=383 |
| Serdik (J) | NONE=1225 (semua) |
| Kategori Bank (K) | 7 variasi, 50 row kosong |
| Rekening (L) | 82 row kosong |

### Null/Empty Check

| Kolom | Filled | Empty/Null/0 |
|-------|--------|--------------|
| A (No) | 1225 | 0 |
| B (Kategori) | 1225 | 0 |
| C (ASN) | 1218 | 7 |
| D (Nama) | 1225 | 0 |
| E (JK) | 1225 | 0 |
| F (NIP) | 1225 | 0 |
| G (NIK) | 1225 | 0 |
| H (KK) | 1225 | 0 |
| I (NPWP) | 1219 | 6 |
| J (Serdik) | 1225 | 0 |
| K (Kategori Bank) | 1175 | 50 |
| L (Rekening) | 1143 | 82 |

---

## Mapping Excel → Database

### Mapping ke tabel `tenaga_ktd` (UPDATE ONLY — skip jika NIP tidak ada)

| Kolom Excel | Kolom DB `tenaga_ktd` | Tipe DB | Notes |
|-------------|----------------------|---------|-------|
| F (NIP) | `nomor_induk` | string(50) | **WHERE clause** — cari record berdasarkan ini |
| D (Nama) | `nama` | string | UPDATE hanya jika kolom Excel tidak kosong |
| C (ASN) | `kat_jabatan` | string(50) | UPDATE hanya jika kolom Excel tidak kosong |
| B (Kategori) | `status` | string(50) | UPDATE hanya jika kolom Excel tidak kosong |
| E (JK) | `jenis_kelamin` | string(10) | UPDATE hanya jika kolom Excel tidak kosong |
| G (NIK) | `nik` | string(20) | UPDATE hanya jika kolom Excel tidak kosong |
| H (KK) | `kk` | string(20) | UPDATE hanya jika kolom Excel tidak kosong |
| I (NPWP) | `npwp` | string(30) | UPDATE hanya jika kolom Excel tidak kosong |
| J (Serdik) | `serdik` | string(50) | UPDATE: NONE → null |
| L (Rekening) | `rekening` | string | **Perlu kolom baru** — UPDATE hanya jika kolom Excel tidak kosong |

> **PENTING:** Hanya kolom di atas yang di-UPDATE. Kolom lain di `tenaga_ktd` (dept_id, golongan, jabatan, pendidikan, alamat, dll) **TIDAK BOLEH DIUBAH** meskipun kosong di database.

### Mapping ke tabel `users` (UPDATE ONLY — skip jika NIP tidak ada)

| Kolom Excel | Kolom DB `users` | Tipe DB | Notes |
|-------------|-----------------|---------|-------|
| F (NIP) | `nomor_induk` | string | **WHERE clause** — cari record berdasarkan ini |
| D (Nama) | `name` | string | UPDATE hanya jika kolom Excel tidak kosong |
| E (JK) | `jk` | string | UPDATE: Pria→Laki-laki, Wanita→Perempuan |
| C (ASN) | `kat_jabatan` | string | UPDATE hanya jika kolom Excel tidak kosong |
| K (Kategori Bank) | *(belum ada kolom)* | - | **Perlu kolom baru:** `bank_kategori` |
| L (Rekening) | `rekening` | string | UPDATE hanya jika kolom Excel tidak kosong |

> Catatan:
> - Kolom `nik` **tidak ada** di tabel `users` (hanya ada di `tenaga_ktd`)
> - Kolom `status` di `users` adalah **integer** (bukan string PNS/PPPK), jangan di-sync dari Excel

> **PENTING:** Hanya kolom di atas yang di-UPDATE. Kolom lain di `users` (email, password, role, dept_id, jabatan, telp, alamat, dll) **TIDAK BOLEH DIUBAH** meskipun kosong di database. Khusus `jabatan` — **jangan di-sync dari kolom ASN**, biarkan apa adanya.

### Ringkasan Perubahan Database

| Tabel | Kolom Baru | Alasan |
|-------|-----------|--------|
| `tenaga_ktd` | `rekening` | Menyimpan nomor rekening dari Excel kolom L |
| `users` | `bank_kategori` | Menyimpan kategori bank dari Excel kolom K |

> Catatan: Kolom `rekening` di tabel `users` **sudah ada** dari migration `2026_06_25`.

---

## Struktur Menu yang Diusulkan

### Posisi di Sidebar
Menu baru "Import ASN" ditambahkan di dalam group **"Kelola"**, tepat di bawah "Pengguna", karena ini terkait data pengguna/ASN.

### URL & Route
- Index (form upload): `GET /admin/import-asn`
- Preview: `POST /admin/import-asn/preview`
- Execute import: `POST /admin/import-asn/import`
- History: `GET /admin/import-asn/history`

---

## Rencana Implementasi

### Phase 1: Database Migration

**1.1 Tambah kolom `rekening` ke `tenaga_ktd`** (belum ada)
```php
// Migration: 2026_09_03_000001_add_import_columns.php
Schema::table('tenaga_ktd', function (Blueprint $table) {
    $table->string('rekening')->nullable()->after('kk');
});
```

**1.2 Tambah kolom `bank_kategori` ke `users`** (belum ada, `rekening` sudah ada dari migration sebelumnya)
```php
// Migration: 2026_09_03_000001_add_import_columns.php (same file)
Schema::table('users', function (Blueprint $table) {
    if (!Schema::hasColumn('users', 'bank_kategori')) {
        $table->string('bank_kategori')->nullable()->after('rekening');
    }
});
```

### Phase 2: Service Layer

**File baru: `app/Services/AsnImportService.php`**

Responsibilities:
- `parseExcel($filePath)` — Parse file Excel, return array data rows
- `validateData($data)` — Validasi data (cek NIP ada di DB, nama tidak kosong, dll)
- `importData($validatedData, $userId)` — Loop data, update `tenaga_ktd` + `users`
- `getImportHistory()` — Ambil history import
- `rollbackImport($batchId)` — Rollback import sebelumnya

**Logic Import (UPDATE ONLY):**
1. Cek NIP (`nomor_induk`) sudah ada di `tenaga_ktd`?
   - **TIDAK ADA → SKIP**, log sebagai "skipped - NIP not found"
   - **ADA → lanjut ke step 2**
2. UPDATE `tenaga_ktd` — **hanya kolom yang ada di Excel**, skip jika nilai Excel kosong:
   ```
   UPDATE tenaga_ktd SET
     nama = COALESCE(excel.nama, tenaga_ktd.nama),
     kat_jabatan = COALESCE(excel.kat_jabatan, tenaga_ktd.kat_jabatan),
     status = COALESCE(excel.status, tenaga_ktd.status),
     jenis_kelamin = COALESCE(excel.jenis_kelamin, tenaga_ktd.jenis_kelamin),
     nik = COALESCE(excel.nik, tenaga_ktd.nik),
     kk = COALESCE(excel.kk, tenaga_ktd.kk),
     npwp = COALESCE(excel.npwp, tenaga_ktd.npwp),
     serdik = excel.serdik,  -- NONE → null
     rekening = COALESCE(excel.rekening, tenaga_ktd.rekening)
   WHERE nomor_induk = ?
   ```
   > **Kolom lain (dept_id, golongan, jabatan, pendidikan, alamat, dll) TIDAK DIUBAH.**
3. UPDATE `users` — **hanya kolom yang ada di Excel**, skip jika nilai Excel kosong:
   ```
   UPDATE users SET
     name = COALESCE(excel.name, users.name),
     jk = excel.jk,          -- Pria→Laki-laki, Wanita→Perempuan
     status = excel.status,   -- pns→PNS, honorer→HONORER, pppk→PPPK
     kat_jabatan = COALESCE(excel.kat_jabatan, users.kat_jabatan),
     nik = COALESCE(excel.nik, users.nik),
     bank_kategori = COALESCE(excel.bank_kategori, users.bank_kategori),
     rekening = COALESCE(excel.rekening, users.rekening)
   WHERE nomor_induk = ?
   ```
   > **Kolom lain (email, password, role, dept_id, jabatan, telp, alamat, dll) TIDAK DIUBAH.**
4. Gunakan DB transaction per batch (100 baris per transaksi)
5. Log semua operasi ke tabel `import_logs` (buat baru) atau gunakan `activities` table

**Format Konversi:**
| Excel | Output |
|-------|--------|
| Pria | Laki-laki |
| Wanita | Perempuan |
| pns | PNS |
| honorer | HONORER |
| pppk | PPPK |
| adm | ADM |
| guru | GURU |
| penghulu | PENGHULU |
| NONE (serdik) | null |

### Phase 3: Controller

**File baru: `app/Http/Controllers/Admin/AsnImportController.php`**

Methods:
```php
class AsnImportController extends Controller
{
    public function index()           // Tampilkan form upload + history
    public function preview(Request $request)  // Preview data sebelum import
    public function import(Request $request)   // Execute import
    public function history()         // Tampilkan history import
}
```

### Phase 4: Routes

**Tambah di `routes/admin.php`:**
```php
// Import ASN Routes
Route::prefix('import-asn')->name('import-asn.')->group(function () {
    Route::get('/', [AsnImportController::class, 'index'])->name('index');
    Route::post('/preview', [AsnImportController::class, 'preview'])->name('preview');
    Route::post('/import', [AsnImportController::class, 'import'])->name('import');
    Route::get('/history', [AsnImportController::class, 'history'])->name('history');
});
```

### Phase 5: Views

**5.1 Form Upload: `resources/views/admin/asn-import/index.blade.php`**
- Upload form (drag & drop + button)
- Validasi: mimes:xlsx,xls, max 10MB
- Tampilkan history import sebelumnya

**5.2 Preview: `resources/views/admin/asn-import/preview.blade.php`**
- Tabel preview data yang akan di-import
- Kolom: No, NIP, Nama, Kategori, ASN, JK, Status (baru/update/error)
- Total data baru, data update, error
- Tombol "Import Sekarang" dan "Batal"
- Download template Excel

**5.3 History: `resources/views/admin/asn-import/history.blade.php`**
- Tabel history import
- Kolom: Tanggal, File, Jumlah Data, Status, Imported By

### Phase 6: Sidebar Navigation

**Edit: `resources/views/admin/layouts/app.blade.php`**
Tambahkan menu item di dalam group "Kelola", tepat di bawah "Pengguna":

```blade
@if($isAdmin)
<a href="{{ route('admin.import-asn.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.import-asn.*') ? 'active' : '' }}">
    <div class="sidebar-nav-icon-wrap teal">
        <svg class="sidebar-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
        </svg>
    </div>
    <span>Import ASN</span>
</a>
@endif
```

### Phase 7: Tests

- Unit test untuk `AsnImportService::parseExcel`
- Unit test untuk `AsnImportService::validateData`
- Unit test untuk `AsnImportService::importToTenagaKtd`
- Unit test untuk `AsnImportService::importToUsers`
- Feature test untuk upload & import flow

---

## Database Table: `import_logs` (opsional, bisa pakai `activities`)

```php
Schema::create('import_logs', function (Blueprint $table) {
    $table->id();
    $table->string('batch_id')->unique();
    $table->string('filename');
    $table->integer('total_rows');
    $table->integer('imported_count');
    $table->integer('updated_count');
    $table->integer('skipped_count');
    $table->integer('error_count');
    $table->json('errors')->nullable();
    $table->unsignedBigInteger('imported_by');
    $table->timestamps();
});
```

---

## Files yang Akan Dibuat/Dimodifikasi

### Files Baru
| File | Purpose |
|------|---------|
| `database/migrations/2026_09_03_000001_add_import_columns.php` | Tambah kolom: `rekening` (tenaga_ktd), `bank_kategori` (users) |
| `app/Services/AsnImportService.php` | Logic parse, validasi, import Excel (UPDATE ONLY) |
| `app/Http/Controllers/Admin/AsnImportController.php` | Controller untuk halaman import |
| `resources/views/admin/asn-import/index.blade.php` | Halaman upload file Excel |
| `resources/views/admin/asn-import/preview.blade.php` | Halaman preview data sebelum import |
| `resources/views/admin/asn-import/history.blade.php` | Halaman history import |

### Files yang Dimodifikasi
| File | Perubahan |
|------|-----------|
| `routes/admin.php` | Tambah route import-asn |
| `resources/views/admin/layouts/app.blade.php` | Tambah menu "Import ASN" di sidebar |

---

## Implementation Checklist

- [ ] **Phase 1**: Buat migration — tambah kolom `rekening` (tenaga_ktd), `bank_kategori` (users)
- [ ] **Phase 2**: Buat `AsnImportService` — parse Excel, validasi NIP ada di DB, UPDATE ONLY
- [ ] **Phase 3**: Buat `AsnImportController`
- [ ] **Phase 4**: Tambah routes di `admin.php`
- [ ] **Phase 5**: Buat views (index, preview, history)
- [ ] **Phase 6**: Tambah menu sidebar "Import ASN"
- [ ] **Phase 7**: Tambah tests
- [ ] **Phase 8**: Test manual dengan file `dataASNSILATAR.xlsx`
- [ ] **Phase 9**: Buat template Excel kosong untuk download

---

## Risk & Mitigation

| Risk | Mitigation |
|------|-----------|
| NIP tidak ada di DB | **SKIP** — log sebagai skipped, lanjut baris berikutnya |
| NIP duplicate di Excel | Ambil yang pertama, skip duplikat |
| File Excel corrupt/invalid | Validate mimes:xlsx,xls, try-catch saat parse |
| Data terlalu besar (1225 rows) | Batch 100 rows per DB transaction |
| Kolom tidak sesuai template | Validasi header Excel sesuai template |
| Data lain terganggu | **UPDATE ONLY kolom Excel**, kolom lain di-NULL-safe (`COALESCE`) |
| Rollback jika error | Simpan batch_id, bisa rollback via UI |

---

## Changelog

### 2026-09-03
- Analisis file Excel `dataASNSILATAR.xlsx` (12 kolom, 1225 data)
- Analisis struktur tabel `tenaga_ktd` dan `users` dari migration
- Buat mapping Excel → Database
- Buat rencana implementasi lengkap
- **Revisi 1**: NIP tidak ditemukan → SKIP (tidak buat baru)
- **Revisi 2**: Kategori Bank → tabel `users` (bukan `tenaga_ktd`)
- **Revisi 3**: UPDATE ONLY kolom yang ada di Excel, kolom lain jangan diubah
