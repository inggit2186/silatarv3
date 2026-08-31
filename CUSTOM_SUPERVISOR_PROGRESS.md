# Progress Custom Supervisor Override

## Overview
Fitur untuk mengizinkan staff memiliki atasan yang berbeda dari hierarki unit kerja default. Contoh: staff di Tata Usaha bisa report langsung ke Kepala Kankemenag (bukan Kepala Tata Usaha).

## Status: SELESAI

## Checklist
- [x] Buat migration untuk tambah kolom `custom_supervisor_id` di tabel users
- [x] Update logic `rekapLaporanKinerja()` untuk cek custom supervisor
- [x] Update logic `verifyLaporanKinerja()` untuk izinkan custom supervisor verifikasi
- [x] Update Admin users edit view untuk management custom supervisor
- [x] Update Admin UserController untuk handle field custom_supervisor_id
- [x] Update API endpoint untuk handle custom supervisor

## Data Flow
```
User Input → Custom Supervisor (opsional)
    ↓
Generate PDF Rekap
    ↓
Cek custom_supervisor_id
    ↓ (jika NULL)
Gunakan logic lama (hierarchy dept_id + PLT/PLH)
    ↓
Penandatangan ditentukan
    ↓
PDF Generated & Status DIKIRIM
    ↓
Supervisor Verifikasi
    ↓
Status DISETUJUI / DITOLAK
```

## Files yang Dimodifikasi
| File | Perubahan |
|------|-----------|
| database/migrations/2026_08_31_000001_add_custom_supervisor_to_users_table.php | Migration baru untuk tambah kolom |
| app/Http/Controllers/PageController.php | Update rekapLaporanKinerja() dan verifyLaporanKinerja() |
| resources/views/admin/users/edit.blade.php | Tambah dropdown custom supervisor |
| app/Http/Controllers/Admin/UserController.php | Handle custom_supervisor_id di update |

## Files Baru
| File | Purpose |
|------|---------|
| CUSTOM_SUPERVISOR_PROGRESS.md | Progress tracking file ini |

## Logic Penentuan Atasan (Setelah Update)
```
1. Apakah user punya custom_supervisor_id?
   ↓ YES → Gunakan custom supervisor
   ↓ NO ↓
2. Apakah user adalah atasan (kepala/kasi/kasubbag)?
   ↓ YES → Kepala Kankemenag
   ↓ NO ↓
3. Ada PLT/PLH di tabel plt_plh?
   ↓ YES → PLT/PLH
   ↓ NO ↓
4. Cari kepala/kasi/kasubbag di dept_id yang sama
```

## TODO
- [ ] Jalankan migration
- [ ] Test dengan user yang punya custom supervisor
- [ ] Test dengan user tanpa custom supervisor (pastikan backwards compatible)
- [ ] Update mobile API jika diperlukan

## Changelog
### 2026-08-31
- Rencana implementasi fitur custom supervisor override
- Analisis struktur data users dan plt_plh
- Membuat progress file ini
- Implementasi migration untuk tambah kolom custom_supervisor_id
- Update logic rekapLaporanKinerja() dengan custom supervisor check
- Update logic verifyLaporanKinerja() untuk izinkan custom supervisor verifikasi
- Update Admin users edit view dengan dropdown custom supervisor
- Update Admin UserController untuk handle field custom_supervisor_id
- Update API KegiatanController downloadPdf() untuk handle custom supervisor
- Semua implementasi selesai
