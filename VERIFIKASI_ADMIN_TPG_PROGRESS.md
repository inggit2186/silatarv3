# Progress Verifikasi Admin TPG

## Overview

Membuat halaman verifikasi admin untuk layanan TPG (1037, 1038, 1081, 1082) dengan role-based access.

## Akses Roles

| Role | Akses |
|------|-------|
| admin | Semua layanan |
| petugas | Layanan sesuai dept_id |

## Service Reference

| Service ID | Nama | dept_id |
|------------|------|---------|
| 1037 | Pemberkasan TPG Semester | 5 |
| 1038 | Pemberkasan TPG Bulanan | 5 |
| 1081 | Pemberkasan PENMAD TPG Bulanan | 7 |
| 1082 | Pemberkasan PENMAD Pengawas Bulanan | ? |

## Status

**SELESAI** (dengan fitur bulanan & semester)

---

## Checklist

### Core Features
- [x] Buat controller verifikasi TPG (admin)
- [x] Buat routes untuk verifikasi TPG
- [x] Buat view verifikasi TPG
- [x] Logic role-based access (admin vs petugas)
- [x] Filter berdasarkan dept_id untuk petugas
- [x] Exclude status DRAFT dari query

### Tab Bulanan/Semester
- [x] Tab navigasi Bulanan dengan icon calendar (cyan)
- [x] Tab navigasi Semester dengan icon document (violet)
- [x] Filter Bulan + Tahun untuk tab Bulanan
- [x] Filter Periode (Ganjil/Genap) + Tahun Ajaran untuk tab Semester
- [x] Default value: Bulan sebelumnya, Tahun saat ini, Status SUBMITTED
- [x] Filter Nama Layanan dengan format [Dept] - [Nama Layanan]

### Dynamic Service Filtering
- [x] Ambil daftar layanan dari ktd_layanan berdasarkan kolom `tipe`
- [x] Layanan bulanan: tipe = 'bulanan'
- [x] Layanan semester: tipe = 'semester'
- [x] Filter dept_id untuk petugas
- [x] Kolom `tipe` di ktd_layanan dengan default 'normal'

### Table/List View
- [x] Tampilkan list pengajuan
- [x] Filter status
- [x] Filter layanan (berdasarkan ID)
- [x] Filter bulan/tahun atau semester/tahun ajaran
- [x] Filter pencarian (nama, NIP, no req)
- [x] Pagination dengan query string preserved
- [x] Styling NEO MIRAI

### Detail View
- [x] Lihat detail pengajuan
- [x] Preview file dengan modal
- [x] Download file lampiran
- [x] Verifikasi/Setuju/Tolak
- [x] Input komentar/verifikator
- [x] Styling NEO MIRAI

### Notifications (future)
- [ ] Notifikasi ke user saat status berubah

## Files untuk Dibuat/Modifikasi

| File | Aksi | Status |
|------|------|--------|
| app/Http/Controllers/Admin/TpgVerificationController.php | Buat | Base class (digabung ke TpgController) |
| app/Http/Controllers/Admin/TpgController.php | Buat | Selesai |
| resources/views/admin/tpg/index.blade.php | Buat | Selesai |
| resources/views/admin/tpg/show.blade.php | Buat | Selesai |
| resources/views/admin/layouts/app.blade.php | Modifikasi | Selesai - sidebar menu |
| routes/admin.php | Modifikasi | Selesai - routes |
| app/Http/Middleware/AdminAccess.php | Modifikasi | Check |

## Route Plan

```
/admin/tpg              -> list semua TPG
/admin/tpg/{id}         -> detail
/admin/tpg/{id}/verify  -> proses verifikasi
/admin/tpg/{id}/reject  -> proses penolakan
/admin/tpg/{id}/file/{syaratId}   -> download file
/admin/tpg/{id}/preview/{syaratId} -> preview file
```

## Data Flow Verifikasi

```
Admin/Petugas akses halaman verifikasi
    |
    v
Cek role (admin vs petugas)
    |
    v
Ambil list pengajuan sesuai akses (exclude DRAFT)
    |
    v
Petugas: filter dept_id
Admin: semua dept_id
    |
    v
Tampilkan list dengan status
    |
    v
Klik detail -> Preview/Download file
    |
    v
Proses verifikasi (Terima/Tolak)
```

## Fitur Yang Sudah Selesai

1. **Tab Navigasi Bulanan/Semester**
   - Tab Bulanan dengan icon calendar dan warna cyan
   - Tab Semester dengan icon document dan warna violet
   - Efek shadow dan transisi yang halus
   - Default tab: Bulanan

2. **Filter Bulanan**
   - Filter Bulan (default: bulan sebelumnya)
   - Filter Tahun (default: tahun saat ini)
   - Filter Status (default: SUBMITTED)
   - Filter Nama Layanan (format: [Dept] - [Nama])
   - Filter pencarian (nama, NIP, no req)

3. **Filter Semester**
   - Filter Periode (Ganjil/Genap, default: Ganjil)
   - Filter Tahun Ajaran (default: tahun ajaran aktif)
   - Filter Status (default: SUBMITTED)
   - Filter Nama Layanan (format: [Dept] - [Nama])
   - Filter pencarian (nama, NIP, no req)

4. **Dynamic Service Filtering**
   - Ambil daftar layanan dari database berdasarkan kolom `tipe`
   - Layanan bulanan: tipe = 'bulanan'
   - Layanan semester: tipe = 'semester'
   - Filter dept_id untuk petugas

5. **Halaman Index (per tab)**
   - Statistik pengajuan (Total, Draft, Pending, Diterima, Sukses, Ditolak)
   - Periode Aktif yang sedang difilter
   - Tabel dengan styling NEO MIRAI
   - Pagination dengan query string preserved
   - Empty state

6. **Halaman Detail**
   - Info pemohon (nama, unit, tipe layanan, periode)
   - Grid dokumen upload (max 3 per baris)
   - Modal preview file (gambar, PDF, file lain)
   - Tombol download
   - Form verifikasi (Ubah Status, Tolak)
   - Info timeline

7. **Controller**
   - Role-based access (admin vs petugas)
   - Preview file (inline)
   - Download file
   - Verify & Reject
   - Helper methods untuk dynamic service filtering

---

## Changelog

### 2026-08-11
- Implementasi tab Bulanan/Semester dengan icon dan styling menarik
- Filter bulanan: Bulan + Tahun (default: bulan lalu + tahun ini)
- Filter semester: Periode + Tahun Ajaran (default: Ganjil + tahun ajaran aktif)
- Dynamic service filtering berdasarkan kolom `tipe` di ktd_layanan
- Migration tambah kolom `tipe` di ktd_layanan dengan default 'normal'
- Filter Nama Layanan dengan format [Dept] - [Nama Layanan]
- Pagination dengan query string preserved
- Default status: SUBMITTED

### 2026-07-09
- Selesai styling halaman index dengan tema NEO MIRAI
- Selesai styling halaman detail dengan tema NEO MIRAI
- Implementasi modal preview file
- Fixed SVG error di sidebar
- Fixed file card grid wrapping
- Exclude status DRAFT dari query

### 2026-07-08
- Buat controller TpgController dengan CRUD operations
- Buat routes untuk verifikasi TPG
- Buat view index dan show
- Implementasi role-based access (admin vs petugas)
- Add sidebar menu Verif TPG

### TODO
- [ ] Implementasi notifikasi ke user (email/WhatsApp)
- [ ] Export data ke Excel/PDF
- [ ] Log aktivitas verifikasi
