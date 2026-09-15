# Progress Tukin Calculation Feature

## Overview
Implementasi sistem perhitungan tukin (tunjangan kinerja) berdasarkan data presensi, dengan menghapus dependency pada slip gaji dan menambahkan logika status presensi dinamis.

## Status: SELESAI

## Checklist
- [x] Buat migration untuk menambahkan 3 kolom baru di ktd_tukin
- [x] Buat model Ketidakhadiran untuk tabel ktd_ketidakhadiran
- [x] Update model KtdTukin dengan kolom baru
- [x] Buat class PresensiTukin export dengan logika perhitungan tukin yang disederhanakan
- [x] Buat Blade view untuk output Excel TukinDate
- [x] Tambah route untuk export tukin di admin.php
- [x] Tambah method exportTukin dan generateTukin di RekapPresensiController
- [x] Pisahkan tombol Generate di halaman rekap-presensi (presensi vs tukin)

## Data Flow
```
Input: dept_id, bulan, tahun
         ↓
    Get Users dari ktd_department
         ↓
    Load existing records dari ktd_tukin
         ↓
    Filter: skip user tanpa record di ktd_tukin
         ↓
    Get base tukin dari record yang ada
         ↓
    Apply diskon CPNS (80%) jika applicable
         ↓
    Get data presensi dari ktd_presensi
         ↓
    Cek status presensi:
      - Jika status ∈ ktd_ketidakhadiran (selain TL/PSW):
        → Hitung sebagai ketidakhadiran
      - Jika status TIDAK ∈ ktd_ketidakhadiran:
        → Hitung sebagai hadir → Proses TL/PSW logic
         ↓
    Hitung potongan berdasarkan persentase dari ktd_ketidakhadiran
         ↓
    Simpan hasil ke ktd_tukin (kolom baru):
      - tukin_final
      - total_potongan_final
      - detail_potongan_calc (JSON)
         ↓
    Export ke Excel dengan format vertikal
```

## Files yang Dimodifikasi
| File | Perubahan |
|------|-----------|
| `database/migrations/2026_09_10_000001_add_calculation_columns_to_ktd_tukin_table.php` | Menambahkan 3 kolom baru ke ktd_tukin |
| `app/Models/Ketidakhadiran.php` | Model baru untuk ktd_ketidakhadiran |
| `app/Models/KtdTukin.php` | Menambahkan tukin_final, total_potongan_final, detail_potongan_calc ke fillable/casts |
| `app/Exports/PresensiTukin.php` | Class export baru dengan logika perhitungan tukin |
| `resources/views/backend/export/TukinDate.blade.php` | Blade view untuk output Excel |
| `routes/admin.php` | Menambahkan 2 route baru untuk tukin |
| `app/Http/Controllers/Admin/RekapPresensiController.php` | Menambahkan method exportTukin dan generateTukin |
| `resources/views/admin/rekap-presensi/index.blade.php` | Konsolidasi form dengan 2 tombol Generate (presensi vs tukin) |

## Files Baru
| File | Purpose |
|------|---------|
| `database/migrations/2026_09_10_000001_add_calculation_columns_to_ktd_tukin_table.php` | Migration untuk kolom baru |
| `app/Models/Ketidakhadiran.php` | Model untuk status ketidakhadiran |
| `app/Exports/PresensiTukin.php` | Export class untuk tukin calculation |
| `resources/views/backend/export/TukinDate.blade.php` | Excel template untuk tukin |

## Perubahan Penting

### 1. Kolom Baru di ktd_tukin
- `tukin_final` - Hasil akhir tukin setelah potongan
- `total_potongan_final` - Total potongan dari presensi
- `detail_potongan_calc` - JSON detail potongan per jenis

### 2. Status Presensi Logic
**"HADIR" (dihitung TL/PSW):**
- NULL, '', MASUK, TERLAMBAT, PULANG, PULANG_CEPAT
- SISTEM_ERROR, TUGAS_LUAR (pengganti presensi dari /presensi-error)

**"KETIDAKHADIRAN":**
- Status yang terdaftar di tabel ktd_ketidakhadiran (selain TL/PSW)
- Contoh: CUTI, SAKIT, DINAS LUAR, DLC, dll.

### 3. UI Separation
- Single form with all inputs (Method, Unit Kerja/Kelompok, Bulan, Tahun)
- Two buttons at the bottom:
  - **Generate Rekap Presensi** (blue) → Generate presensi + detail files
  - **Generate Tukin** (purple) → Generate tukin file only
- Both buttons use the same form data but submit to different endpoints

## Changelog
### 2026-09-10
- Implementasi lengkap fitur tukin calculation
- Buat migration, models, export class, dan views
- Tambah route dan controller methods
- Pisahkan UI generate buttons (presensi vs tukin)
- Test status presensi handling (dynamic dari ktd_ketidakhadiran)
- Tambahkan loading overlay untuk generate tukin (sama seperti generate presensi)
- Tambahkan progress bar dan status messages untuk tukin generation
- Update Excel output untuk menampilkan data lama (PUSAKA) dan perhitungan baru
- Tambahkan kolom perbandingan: Nett Lama vs Nett Baru
- Implementasi warna merah untuk hari libur dan cuti bersama di Excel
- Tambahkan legend di Excel untuk menjelaskan warna
- Fix table name: libur → hari_libur
- Create command for fetching Indonesian holidays (php artisan holidays:fetch)
- Create HariLibur model
- Create AsnHariKerja model
- Implementasi working day logic berdasarkan harikerja_id
