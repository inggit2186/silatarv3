# Progress Export Presensi Excel

## Overview
Fitur untuk mengekspor data presensi dari database ke file Excel (.xlsx). Export dilakukan per unit kerja (dept_id) dengan format horizontal.

## Status: **SELESAI**

---

## ✅ Cara Penentuan Bulan Export

### Penentuan Bulan:
1. **Default:** Bulan sekarang (jika tidak ditentukan)
2. **Manual:** Gunakan parameter `--month` dan `--year`
3. **Contoh:** Export bulan Agustus 2026: `--month=8 --year=2026`

### Yang Diexport:
- **SEMUA data presensi** di bulan yang dipilih
- **Dipisah per unit kerja (dept_id)**
- **1 file excel = 1 unit kerja**
- **Format horizontal:** Kolom = tanggal 1-31, Baris = setiap user di unit kerja

---

## ✅ Perintah Export

### Export Satu Unit Kerja
```bash
# Export unit kerja tertentu
php artisan presensi:export --dept={dept_id} --month=8 --year=2026

# Contoh: Export KUA Batipuh (dept_id = 20220927102)
php artisan presensi:export --dept=20220927102 --month=8 --year=2026
```

### Export Semua Unit Kerja
```bash
# Export SEMUA unit kerja dalam 1 command
php artisan presensi:export --all --month=8 --year=2026

# Akan menghasilkan 1 file per unit kerja
```

### Export Bulan Sekarang (Default)
```bash
# Export bulan sekarang untuk semua unit kerja
php artisan presensi:export --all
```

---

## ✅ Format Export (Horizontal)

### Contoh Format dalam 1 File Excel:

| NIP | Nama | 1 | 2 | 3 | 4 | 5 | ... | 31 | Total |
|-----|------|---|---|---|---|---|-----|-----|-------|
| 19680101... | Ahmad | 1 | | 1 | | 1 | ... | | **15** |
| 19720202... | Budi | | 1 | | 1 | | ... | 1 | **12** |
| 19850303... | Citra | 1 | 1 | 1 | 1 | 1 | ... | | **22** |

### Keterangan:
- **Kolom 1-31:** Tanggal dalam bulan
- **Value 1:** Ada presensi masuk atau pulang
- **Value kosong:** Tidak ada presensi
- **Kolom Total:** Jumlah hari kerja di bulan tersebut

---

## ✅ Struktur Folder Output

```
storage/app/exports/presensi/
└── 2026/
    └── 8/
        ├── presensi_KUA_Kecamatan_Batipuh_2026_8.xlsx
        ├── presensi_KUA_Kecamatan_Lima_Kaum_2026_8.xlsx
        ├── presensi_MAN_1_Tanah_Datar_2026_8.xlsx
        └── ... (1 file per unit kerja)
```

---

## ✅ Contoh Output Console

```
╔════════════════════════════════════════════════════════════╗
║           EXPORT PRESENSI EXCEL                          ║
╚════════════════════════════════════════════════════════════╝

📊 Parameter Export:
   Bulan: Agustus 2026
   Total Unit Kerja: 52
   Tipe: Horizontal

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
🏢 Unit Kerja: KUA Kecamatan Batipuh (ID: 20220927102)
👥 Total User: 21
📄 File: presensi_KUA_Kecamatan_Batipuh_2026_8.xlsx
✅ Berhasil

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
🏢 Unit Kerja: KUA Kecamatan Lima Kaum (ID: 20220927105)
👥 Total User: 13
📄 File: presensi_KUA_Kecamatan_Lima_Kaum_2026_8.xlsx
✅ Berhasil

...

╔════════════════════════════════════════════════════════════╗
║                  RINGKASAN EXPORT                         ║
╚════════════════════════════════════════════════════════════╝

📊 Total Unit Kerja Diexport: 52

📂 Lokasi File: D:\...\storage\app\exports\presensi\2026\8\

💡 File yang dihasilkan (1 per unit kerja):
   - presensi_{nama_unit_kerja}_{year}_{month}.xlsx
```

---

## ✅ Test Results

✅ Export 1 unit kerja berhasil (21 users, 1 file excel)
✅ Export semua unit kerja berhasil (52 unit kerja, 52 file excel)
✅ Format horizontal dengan kolom tanggal 1-31
✅ Kolom Total di akhir
✅ Highlight hijau untuk value 1
✅ Styling menarik (header biru, border, freeze pane)
✅ File naming: presensi_{nama_unit_kerja}_{year}_{month}.xlsx

---

## Changelog

### 2026-09-03
- ✅ Update Console Command untuk export per unit kerja
- ✅ Add --dept option untuk filter unit kerja tertentu
- ✅ Add --all option untuk export semua unit kerja
- ✅ Auto-group users by dept_id
- ✅ Buat 1 file excel per unit kerja dengan format horizontal
- ✅ Kolom Total di akhir
- ✅ Test export dengan data real (52 unit kerja)
- ✅ Update dokumentasi

---

## Update: Users Tanpa Data Presensi

### Perubahan (2026-09-03)

**Fitur Baru:**
- ✅ Users yang tidak ada data presensinya tetap ditampilkan di file excel
- ✅ Kolom presensi kosong untuk users tanpa data
- ✅ Kolom Total menunjukkan jumlah hari kerja (0 jika tidak ada presensi)

**Contoh Output:**
```
KUA Kecamatan Batipuh: 21 users
- 16 users ada presensi (Total: 31 hari)
- 5 users tanpa presensi (Total: 0 hari)
```

**Behavior:**
- Jika ada record di `ktd_presensi` untuk tanggal tertentu → dianggap hadir (value 1)
- Tidak perlu cek `m_absen` atau `p_absen`
- Jika tidak ada record → tidak hadir (kolom kosong)

**File Excel:**
```
presensi_KUA_Kecamatan_Batipuh_2026_8.xlsx
├── 16 rows dengan data presensi (Total: 31)
└── 5 rows tanpa data presensi (Total: 0)
```

---

## Update: Logic Presensi (Final)

### Perubahan (2026-09-03)

**Logic yang Benar:**
User dianggap hadir (value 1) jika:
1. Ada `m_absen` (presensi masuk) ATAU
2. Ada `p_absen` (presensi pulang) ATAU
3. Ada keduanya
4. Dengan `status = null`

**Formula:**
```php
$hasPresensi = isset($userPresensi[$day]) &&
    (!empty($userPresensi[$day]->m_absen) || !empty($userPresensi[$day]->p_absen)) &&
    ($userPresensi[$day]->status === null);
```

**Contoh Kasus:**
- User punya 31 records di ktd_presensi untuk bulan Agustus
- Tapi hanya 19 records yang punya m_absen atau p_absen dengan status null
- **Hasil:** Total = 19 hari kerja (bukan 31)

**Users Tanpa Presensi:**
- Tetap ditampilkan di file excel
- Kolom presensi kosong (tidak ada value 1)
- Total = 0

**Test Results:**
```
KUA Kecamatan Batipuh: 21 users
├── 16 users ada presensi (Total: 19 hari)
└── 5 users tanpa presensi (Total: 0 hari)
```

---

## Update: Export Detail Jam Presensi

### Tipe Export Baru (2026-09-03)

**Tipe: Detail Horizontal**
- Format: 1 file per unit kerja
- Kolom: NIP, Nama, Tanggal 1-31, Total Hari
- Value: "jam_masuk/jam_pulang" (contoh: "07:21:00/16:03:00")
- Jika tidak ada presensi: kolom kosong

**Contoh Format:**
```
| NIP | Nama | 1 | 2 | 3 | ... | 31 | Total Hari |
|-----|------|---|---|---|-----|-----|------------|
| 19680101... | Ahmad | 07:30/16:00 | - | 08:00/17:00 | ... | | 19 |
| 19720202... | Budi | | 07:15/16:45 | | ... | | 15 |
```

**Perintah:**
```bash
# Export detail jam presensi untuk 1 unit kerja
php artisan presensi:export --dept=20220927102 --month=8 --year=2026 --type=detail-horizontal

# Export detail jam presensi untuk semua unit kerja
php artisan presensi:export --all --month=8 --year=2026 --type=detail-horizontal
```

**File Output:**
```
presensi_detail_{nama_unit_kerja}_{year}_{month}.xlsx
```

**Test Results:**
```
KUA Kecamatan Batipuh: 21 users
├── 16 users ada presensi (Total: 19 hari, format: jam_masuk/jam_pulang)
└── 5 users tanpa presensi (Total: 0 hari, kolom kosong)
```

---

## Update: Format Jam Presensi Lebih Mudah Dibaca

### Perubahan Format (2026-09-03)

**Sebelumnya:**
```
07:21:00/16:03:00
```
- Dengan detik
- Tanpa spasi
- Kurang mudah dibaca

**Sekarang (Recommended):**
```
07:21 / 16:03
```
- Tanpa detik (hanya jam:menit)
- Dengan spasi dan slash
- Sangat mudah dibaca
- Lebih ringkas dan clean

**Fitur:**
- ✅ Otomatis remove detik dari jam
- ✅ Format HH:MM / HH:MM
- ✅ Menampilkan "-" jika tidak ada jam masuk atau pulang
- ✅ Spasi dan slash sebagai pemisah

**Contoh Output:**
```
| NIP | Nama | 1 | 2 | 3 | ... | Total Hari |
|-----|------|---|---|---|-----|------------|
| 19680101... | Ahmad | 07:30 / 16:00 | - | 08:00 / 17:00 | ... | 19 |
| 19720202... | Budi | - | 07:15 / 16:45 | - | ... | 15 |
```

---

## Update: Menu Download Export di Admin Panel

### Perubahan (2026-09-03)

**Fitur Baru:**
- ✅ Menu "Download Export" di sidebar admin
- ✅ List semua file export yang tersedia
- ✅ Download file individual
- ✅ Download ZIP untuk semua file dalam 1 bulan
- ✅ Hapus file export

**URL:** `http://your-domain/admin/exports`

**Menu di Sidebar:**
- Import Presensi
- Export Presensi
- **Download Export** ⭐ (NEW)

**Fitur Halaman:**
1. **View File Tree** - File tersusun per tahun dan bulan
2. **Download Individual** - Download 1 file .xlsx
3. **Download ZIP** - Download semua file dalam 1 bulan
4. **Delete File** - Hapus file export
5. **Info Size** - Ukuran file & total size

**Contoh Tampilan:**
```
📅 Tahun 2026 (2 file)
└── 📁 Agustus (2 file, 20.3 KB)
    ├── [ZIP] Download Semua
    ├── 📄 presensi_KUA_Kecamatan_Batipuh_2026_8.xlsx (9.3 KB)
    │   ├── [Download]
    │   └── [Hapus]
    └── 📄 presensi_detail_KUA_Kecamatan_Batipuh_2026_8.xlsx (11 KB)
        ├── [Download]
        └── [Hapus]
```

**Routes:**
```php
GET  /admin/exports                  → List file export
GET  /admin/exports/download         → Download file individual
GET  /admin/exports/download-month   → Download ZIP per bulan
POST /admin/exports/delete           → Hapus file
```
