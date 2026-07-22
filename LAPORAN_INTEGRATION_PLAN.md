# Plan Integrasi Laporan Madrasah

## Overview
Mengintegrasikan form Laporan Semester dan Laporan Bulanan dengan database yang sudah ada.

---

## 1. Verifikasi Database Tables

### ✅ Tabel yang sudah ADA di database `kemenagtd_db`:

| Tabel | Status | Keterangan |
|--------|--------|-------------|
| `ktd_department` | ✅ ADA | Profil Madrasah |
| `ktd_laporan_semester_madrasah` | ✅ ADA | Laporan Semester |
| `ktd_laporan_bulanan_madrasah` | ✅ ADA | Laporan Bulanan |

---

## 2. Struktur Database (Actual)

### 2.1 Tabel `ktd_department` (Profil Madrasah)
**Fields (50 columns):**
| Field | Type | Keterangan |
|-------|------|-------------|
| id | bigint | Primary key |
| nama | varchar(255) | Nama madrasah |
| kategori | varchar(255) | min, mtsn, man, other |
| status_lembaga | varchar | NEGERI/SWASTA |
| nsm | varchar | Nomor Statistik Madrasah |
| npsm | varchar | Nomor Pokok Sekolah |
| jalan | varchar | Alamat |
| jorong | varchar | Jorong |
| nagari | varchar | Nagari |
| kecamatan | varchar | Kecamatan |
| koordinat | varchar | GPS |
| telepon | varchar | Telepon |
| email | varchar | Email |
| website | varchar | Website |
| waktu_belajar | varchar | Pagi/Siang/Sore |
| visi | text | Visi Madrasah |
| sk_pendirian | varchar | SK Pendirian |
| tanggal_sk | date | Tanggal SK |
| komite_lembaga | varchar | Komite |
| akreditasi | varchar | Status Akreditasi |
| tanggal_akreditasi | date | Tanggal Akreditasi |
| status_kkm | varchar | TERAKREDITASI/BELUM |
| jarak_* | varchar | 12 field jarak |

---

### 2.2 Tabel `ktd_laporan_semester_madrasah`
**Fields (20 columns):**
| Field | Type | Keterangan |
|-------|------|-------------|
| id | bigint unsigned | Primary key |
| dept_id | bigint | FK ke ktd_department |
| semester | enum('ganjil','genap') | Semester |
| tahun_ajaran | varchar(9) | 2025/2026 |
| status | enum('draft','submitted','revisi','approved') | Status |
| keadaan_gedung_json | longtext | JSON data gedung |
| sarana_pendidikan_json | longtext | JSON data sarana |
| bantuan_pemerintah_json | longtext | JSON bantuan |
| bantuan_non_pemerintah_json | longtext | JSON bantuan non |
| data_guru_pegawai_json | longtext | JSON SDM |
| tingkat_pendidikan_json | longtext | JSON siswa |
| sertifikasi_json | longtext | JSON sertifikasi |
| banyak_hari_sekolah | int | Jumlah hari |
| absensi_siswa_json | longtext | JSON absensi |
| luas_tanah_json | longtext | JSON tanah |
| sertifikat_tanah_json | longtext | JSON sertifikat |
| catatan_admin | text | Catatan dari admin |
| submitted_at | datetime | Tanggal submit |
| created_at | datetime | |
| updated_at | datetime | |

---

### 2.3 Tabel `ktd_laporan_bulanan_madrasah`
**Fields (16 columns):**
| Field | Type | Keterangan |
|-------|------|-------------|
| id | bigint unsigned | Primary key |
| dept_id | bigint | FK ke ktd_department |
| bulan_laporan | varchar(20) | Januari-Desember |
| tahun_laporan | int(4) | 2026 |
| tahun_ajaran | varchar(9) | 2025/2026 |
| semester | enum('Ganjil','Genap') | Semester |
| status | enum('draft','submitted','revisi','approved') | Status |
| nama_madrasah_snapshot | varchar | Snapshot nama |
| instansi_snapshot | varchar | Snapshot instansi |
| rb | int | Jumlah rombel |
| student_counts_json | longtext | JSON: {"I.A":{"l":20,"p":18}} |
| mutation_rows_json | longtext | JSON: [{nama,nisn,...}] |
| catatan_admin | text | Catatan dari admin |
| submitted_at | datetime | Tanggal submit |
| created_at | datetime | |
| updated_at | datetime | |

---

## 3. Perbedaan dengan Kode Existing

### Issue yang perlu diperbaiki:

| Kode Controller | Database Field | Status |
|----------------|---------------|--------|
| `student_counts` | `student_counts_json` | ❌ SALAH |
| `mutation_rows` | `mutation_rows_json` | ❌ SALAH |
| `keadaan_gedung` | `keadaan_gedung_json` | ❌ SALAH |
| `sarana_pendidikan` | `sarana_pendidikan_json` | ❌ SALAH |
| `semester` = 'Genap' | `enum('ganjil','genap')` | ❌ LOWERCASE |

---

## 4. Checklist Perbaikan

### 4.1 Laporan Bulanan Controller
- [ ] Fix field name: `student_counts` → `student_counts_json`
- [ ] Fix field name: `mutation_rows` → `mutation_rows_json`
- [ ] Fix semester value: 'Genap' → 'genap'
- [ ] Add `nama_madrasah_snapshot` field
- [ ] Add `instansi_snapshot` field

### 4.2 Laporan Semester Controller
- [ ] Fix field names: add `_json` suffix
- [ ] Fix semester value: 'Genap' → 'genap'

### 4.3 Laporan Bulanan View
- [ ] Load data dari `student_counts_json`
- [ ] Load data dari `mutation_rows_json`

### 4.4 Laporan Semester View
- [ ] Load data dari `*_json` fields

---

## 5. Plan Kerja

### Phase 1: Fix Controller Methods
```
1. Fix saveLaporanBulananMadrasah():
   - student_counts → student_counts_json
   - mutation_rows → mutation_rows_json
   - Add nama_madrasah_snapshot
   - Add instansi_snapshot
   - Fix semester to lowercase

2. Fix saveLaporanSemesterMadrasah():
   - Add _json suffix to all JSON fields
   - Fix semester to lowercase

3. Fix load functions:
   - Parse _json fields correctly
```

### Phase 2: Update Views
```
1. Laporan Bulanan:
   - Pre-fill form fields from DB
   - Generate rombel cards from student_counts_json
   - Generate mutation cards from mutation_rows_json

2. Laporan Semester:
   - Pre-fill all table data from *_json fields
```

### Phase 3: Testing
```
1. Test save → load cycle
2. Test submit flow
3. Verify reactive totals work with DB data
```

---

## 6. File yang Perlu Dimodifikasi

| File | Changes |
|------|---------|
| `app/Http/Controllers/PageController.php` | Fix field names in save methods |
| `resources/views/madrasah/laporanbulanan.blade.php` | Load data dari JSON fields |
| `resources/views/madrasah/laporansemester.blade.php` | Load data dari *_json fields |
| `LAPORAN_INTEGRATION_PLAN.md` | Update plan (done) |

---

## 7. TODO List (Priority Order)

### HIGH PRIORITY:
1. [ ] Fix `saveLaporanBulananMadrasah()` - field names
2. [ ] Fix `saveLaporanSemesterMadrasah()` - field names
3. [ ] Fix `laporanBulananMadrasah()` - load from DB
4. [ ] Fix `laporanSemesterMadrasah()` - load from DB
5. [ ] Update view - display loaded data

### MEDIUM PRIORITY:
6. [ ] Test save/submit flow
7. [ ] Admin views (Phase 3)

---

## 8. Sample Data Format

### student_counts_json (Laporan Bulanan)
```json
{
  "I.A": { "l": 20, "p": 18 },
  "I.B": { "l": 22, "p": 20 },
  "II.A": { "l": 19, "p": 21 }
}
```

### mutation_rows_json (Laporan Bulanan)
```json
[
  {
    "nama_siswa": "Ahmad Fauzi",
    "nisn": "0012345678",
    "nik": "1301234567890000",
    "jenis_kelamin": "L",
    "tempat_lahir": "Batusangkar",
    "tanggal_lahir": "2015-03-15",
    "nama_ibu": "Siti Aminah",
    "kelas": "II.A",
    "keterangan": "Mutasi Keluar"
  }
]
```

### keadaan_gedung_json (Laporan Semester)
```json
[
  { "label": "Ruang Kelas", "baik": 5, "ringan": 1, "sedang": 0, "berat": 0 },
  { "label": "Ruang Guru", "baik": 2, "ringan": 0, "sedang": 0, "berat": 0 }
]
```
