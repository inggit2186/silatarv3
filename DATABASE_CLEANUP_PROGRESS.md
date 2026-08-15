# Progress Cleanup Database Columns

## Overview
Analisis dan cleanup kolom yang tidak terpakai di tabel `users` berdasarkan penggunaan di aplikasi Flutter dan backend Laravel.

## Status: SELESAI

## Analisis Kolom

### **TABEL USERS**

#### ✅ Kolom yang DIGUNAKAN (Jangan dihapus):
| Kolom | Fungsi | Digunakan di |
|-------|--------|--------------|
| `id` | Primary key | Semua |
| `name` | Nama user | Flutter, Backend |
| `email` | Email login | Flutter, Backend |
| `password` | Password auth | Backend |
| `nomor_induk` | Nomor induk pegawai | Flutter, Backend |
| `nip` | NIP 16 digit | Flutter, Backend |
| `jk` | Jenis kelamin (L/P) | Flutter, Backend |
| `telp` | Nomor HP | Flutter, Backend |
| `alamat` | Alamat | Flutter, Backend |
| `tempat_lahir` | Tempat lahir | Flutter, Backend |
| `tanggal_lahir` | Tanggal lahir | Flutter, Backend |
| `pp` | Foto profil | Flutter, Backend |
| `role` | Role user | Flutter, Backend |
| `status` | Status aktif | Flutter, Backend |
| `dept_id` | ID unit kerja | Flutter, Backend |
| `jabatan` | Jabatan | Admin\UserController |
| `pekerjaan` | Pekerjaan | Admin\UserController, RegisterController |
| `satker` | Satuan kerja | RegisterController (guru_pai) |
| `remember_token` | Token remember | Backend |
| `created_at` | Timestamp | Semua |
| `updated_at` | Timestamp | Semua |

#### ❌ Kolom yang TIDAK DIGUNAKAN (Bisa dihapus):
| Kolom | Fungsi | Alasan Hapus |
|-------|--------|--------------|
| `asn` | ASN status | Data sudah ada di `tenaga_ktd` |
| `tmt_tugas` | TMT Tugas | Data sudah ada di `tenaga_ktd` |
| `kgb` | KGB | Data sudah ada di `tenaga_ktd` |
| `masa_kerja_tahun` | Masa kerja tahun | Data sudah ada di `tenaga_ktd` |
| `masa_kerja_bulan` | Masa kerja bulan | Data sudah ada di `tenaga_ktd` |
| `npwp` | NPWP | Data sudah ada di `tenaga_ktd` |

**Total dihapus: 6 kolom**

---

### **TABEL TENAGA_KTD**

#### ✅ Kolom yang DIGUNAKAN (Jangan dihapus):
| Kolom | Fungsi | Digunakan di |
|-------|--------|--------------|
| `id` | Primary key | Backend |
| `user_id` | Link ke users | Backend |
| `dept_id` | ID unit kerja | Backend |
| `madrasah_id` | ID madrasah | Backend |
| `created_by` | Created by | Backend |
| `nama` | Nama lengkap | Flutter, Backend |
| `kat_jabatan` | Kategori jabatan | Backend |
| `status` | Status | Backend |
| `nomor_induk` | Nomor induk | Flutter, Backend |
| `nik` | NIK KTP | Flutter, Backend |
| `npwp` | NPWP | PageController (pegawai/guru) |
| `nuptk` | NUPTK | Backend |
| `npk` | NPK | Backend |
| `nrg` | NRG | Backend |
| `tempat_lahir` | Tempat lahir | Flutter, Backend |
| `tanggal_lahir` | Tanggal lahir | Flutter, Backend |
| `jenis_kelamin` | Pria/Wanita | Flutter, Backend |
| `golongan` | Golongan | Backend |
| `jabatan` | Jabatan | Backend |
| `pekerjaan` | Pekerjaan | Backend |
| `bidang_studi_diajar` | Bidang studi | Backend |
| `bidang_sertifikasi` | Sertifikasi | Backend |
| `serdik` | Serdik | Backend |
| `jenis_guru` | Jenis guru | Backend |
| `pendidikan` | Pendidikan | Backend |
| `jurusan` | Jurusan | Backend |
| `fakultas` | Fakultas | Backend |
| `universitas` | Universitas | Backend |
| `tahun_lulus` | Tahun lulus | Backend |
| `tmt_tugas` | TMT Tugas | PageController (pegawai/guru) |
| `kgb` | KGB | PageController (pegawai/guru) |
| `tmt_cpns` | TMT CPNS | Backend |
| `tmt_pns` | TMT PNS | Backend |
| `masa_kerja_tahun` | Masa kerja tahun | PageController (pegawai) |
| `masa_kerja_bulan` | Masa kerja bulan | PageController (pegawai) |
| `email` | Email | Flutter, Backend |
| `telp` | Nomor HP | Flutter, Backend |
| `alamat_ktp` | Alamat KTP | PageController (pegawai) |
| `alamat` | Alamat | Flutter, Backend |
| `nama_ibu` | Nama ibu | PageController (guru) |
| `keterangan` | Keterangan | PageController (pegawai) |
| `bio` | Bio/deskripsi | Flutter, Backend |
| `is_active` | Status active | Backend |
| `source_table` | Source table | Backend |
| `created_at` | Timestamp | Semua |
| `updated_at` | Timestamp | Semua |

#### ⏸️ Kolom yang BELUM DIGUNAKAN (Dipertahankan untuk masa depan):
| Kolom | Fungsi | Status |
|-------|--------|--------|
| `kk` | Kartu Keluarga | Dipertahankan |
| `nikah` | Status nikah | Dipertahankan |
| `jenis_pjob` | Jenis pekerjaan | Dipertahankan |
| `pjob` | Pekerjaan | Dipertahankan |
| `req_tunjangan` | Tunjangan | Dipertahankan |
| `jml_anak` | Jumlah anak | Dipertahankan |
| `nama_istri_suami` | Nama pasangan | Dipertahankan |
| `facebook` | Facebook | Dipertahankan |
| `twitter` | Twitter | Dipertahankan |
| `linkedin` | LinkedIn | Dipertahankan |
| `instagram` | Instagram | Dipertahankan |
| `remember_token` | Token | Dipertahankan |

**Total: 0 kolom dihapus (semua dipertahankan)**

---

## Migration File

**File:** `database/migrations/2026_08_15_000001_cleanup_unused_columns.php`

### Users Table - Columns Dropped:
```php
$columnsToDrop = [
    'asn',           // ASN status (data in tenaga_ktd)
    'tmt_tugas',     // TMT Tugas (data in tenaga_ktd)
    'kgb',           // KGB (data in tenaga_ktd)
    'masa_kerja_tahun',  // Masa kerja tahun (data in tenaga_ktd)
    'masa_kerja_bulan',  // Masa kerja bulan (data in tenaga_ktd)
    'npwp',          // NPWP (data in tenaga_ktd)
];
```

### Tenaga_ktd Table - No Columns Dropped:
```php
// Semua kolom dipertahankan untuk penggunaan di masa depan
// Tidak ada kolom yang dihapus dari tabel ini
```

---

## Execution

### Run Migration:
```bash
php artisan migrate
```

### Backup Database First:
```bash
mysqldump -u root -p kemenagtd_db > backup_before_cleanup.sql
```

### Rollback (if needed):
Migration cannot be easily rolled back since it drops columns.
If you need to restore, use the backup SQL file.

---

## Impact Analysis

### Flutter App:
- ✅ Tidak ada perubahan kode Flutter
- ✅ Semua kolom yang dipakai sudah ada di model
- ✅ Tidak ada field yang hilang

### Backend Laravel:
- ✅ Web admin tidak terpengaruh:
  - `Admin\UserController` - masih menggunakan `jabatan`, `pekerjaan`, `satker`
  - `PageController` - masih menggunakan kolom untuk laporan madrasah (pegawai/guru)
  - `RegisterController` - masih menggunakan kolom untuk registrasi

### Web Admin:
- ✅ Web admin tidak terpengaruh karena kolom yang dihapus tidak dipakai
- ✅ Kolom untuk laporan madrasah (pegawai/guru) masih ada
- ✅ Semua kolom tenaga_ktd masih ada untuk masa depan

---

## Recommendation

### Safe Approach (Recommended):
1. Backup database dulu
2. Run migration (hanya menghapus 6 kolom dari users table)
3. Test web admin dan mobile app
4. Selesai! Kolom tenaga_ktd tetap utuh untuk masa depan

---

## Changelog

### 2026-08-15
- Created comprehensive analysis of used/unused columns
- Created migration file to remove unused columns
- Users table: 6 columns marked for removal
- Tenaga_ktd table: 0 columns removed (all kept for future use)
- Total: 6 columns to be removed
- Updated migration to keep columns used in web admin
- Kept notif column for potential future use
- Kept all tenaga_ktd columns for future development

---

## Catatan Penting

1. **Backup database sebelum run migration!**
2. **Web admin tidak terpengaruh** - kolom yang dihapus tidak dipakai di web admin
3. **Migration tidak bisa di-rollback** - karena drop column tidak bisa dikembalikan
4. **Kolom tenaga_ktd semua dipertahankan** - untuk penggunaan di masa depan
5. **Kolom notif dipertahankan** - mungkin akan digunakan nanti

Untuk informasi lebih lanjut, lihat file migration di:
`database/migrations/2026_08_15_000001_cleanup_unused_columns.php`
