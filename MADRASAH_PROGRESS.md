# Progress Laporan Madrasah

## Overview
Fitur Laporan Madrasah adalah modul untuk mengelola pelaporan semester madrasah (MIN, MTSN, MAN, dan sekolah lainnya) yang terintegrasi dengan sistem SILATAR V2.

## Status: DALAM PROGRES

## Target User
- User dari madrasah (kategori: min, mtsn, man, other)
- Admin/Kasubbag untuk verifikasi laporan

## Checklist

### Phase 1: Setup & Database
- [x] MadrasahController sudah ada
- [x] Routes user-side sudah ada (profil, pegawai, guru, laporan-semester)
- [x] Menu Laporan Madrasah di dropdown user (berdasarkan kategori dept)
- [x] Tabel `ktd_laporan_semester_madrasah` sudah ada di database
- [x] Tabel `ktd_laporan_bulanan_madrasah` sudah ada di database
- [x] Tabel `ktd_madrasah_pegawai` (guru_madrasah) sudah ada
- [x] Tabel `ktd_madrasah_guru` (pegawai_madrasah) sudah ada

### Phase 2: User-Side Pages
- [x] Header/Hero Section - Menggunakan `.hero-page`, `.content-centered`
- [x] Tab Navigation - Menggunakan `.neo-tabs`, `.neo-tab`
- [x] Section 1: Identitas Madrasah - Menggunakan `.neo-card`, `.neo-field-input`
- [x] Section 2: Alamat & Lokasi - Menggunakan `.neo-card`, `.neo-field-input`
- [x] Section 3: Kontak & Website - Menggunakan `.neo-card`, `.neo-field-input`
- [x] Section 4: SK Pendirian - Menggunakan `.neo-card`, `.neo-field-input`
- [x] Section 5: Visi Madrasah - Menggunakan `.neo-card`, `.neo-field-input`
- [x] Action Buttons - Menggunakan `.neo-form-actions`, `.neo-btn-action-*`
- [x] pegawaimadrasah.blade.php - Full NEO MIRAI theme dengan CSS classes
- [x] gurumadrasah.blade.php - Full NEO MIRAI theme dengan CSS classes
- [x] laporansemester.blade.php - Full NEO MIRAI theme dengan CSS classes
- [x] Tambah fitur Submit laporan (selain draft)
- [x] Save Profil Madrasah - update ktd_department
- [x] Save Laporan Semester - update ktd_laporan_semester_madrasah
- [x] Save Laporan Bulanan - update ktd_laporan_bulanan_madrasah

### Phase 3: Admin-Side (Verifikasi)
- [ ] Buat view admin `resources/views/admin/madrasah/index.blade.php`
- [ ] Buat view admin `resources/views/admin/madrasah/show.blade.php`
- [ ] Buat controller method untuk list & verify laporan
- [ ] Tambah route admin untuk madrasah
- [ ] Tambah menu sidebar admin untuk Madrasah

### Phase 4: Export & Report
- [ ] Generate laporan ke PDF
- [ ] Export Excel (optional)

### Phase 5: Laporan Bulanan (BARU!)
- [x] Buat view `laporanbulanan.blade.php`
- [x] Hero section dengan status badge
- [x] Meta info cards (tanggal submit, catatan admin)
- [x] Section Informasi Laporan (bulan, tahun, tahun ajaran, semester, nama madrasah, RB)
- [x] Section Keadaan Siswa per rombel (card-based per tingkat)
- [x] Section Mutasi siswa (card-based dengan badge)
- [x] Reactive totals dengan JavaScript
- [x] Add/Remove rombel functionality
- [x] Add/Remove mutation row functionality
- [x] Action buttons (Reset, Simpan Draft, Kirim) - dipindahkan ke bawah form
- [x] Route baru `/madrasah/laporan-bulanan`
- [x] Controller method `laporanBulananMadrasah()`
- [x] Controller method `saveLaporanBulananMadrasah()`
- [x] Tab navigation dengan semua halaman madrasah
- [x] Tambah tab menu di halaman Profil, Pegawai, Guru, Laporan Semester
- [x] Tabel `ktd_laporan_bulanan_madrasah` sudah ada di database
- [x] Alert messages di view untuk success/error
- [x] Tambah fitur Submit laporan (selain draft)
- [x] Hapus form filter duplikat - sekarang hanya satu form Informasi Madrasah
- [x] Filter form dengan default value waktu saat ini
- [x] RB field read-only dan auto-calculate dari rombel cards
- [x] Dropdown kelas dinamis berdasarkan rombel yang ada
- [x] Total Data Mutasi hanya hitung jika Nama Siswa terisi
- [x] Ambil data terbaru jika belum ada untuk periode dipilih

### Phase 6: Konsolidasi Tabel Tenaga Kependidikan
- [x] Analisis struktur `guru_madrasah` dan `pegawai_madrasah`
- [x] Rancang tabel unified `tenaga_ktd` dengan semua field
- [x] Buat migration `2026_08_04_000001_create_tenaga_ktd_table.php`
- [x] Buat migration `2026_08_04_000002_add_users_columns_to_tenaga_ktd.php`
- [x] Buat Artisan command `MigrateTenagaKtd.php` untuk migrasi data
- [x] Buat Artisan command `CleanupUsersColumns.php` untuk cleanup kolom
- [x] Update `pegawaiMadrasah()` di PageController - baca dari `tenaga_ktd`
- [x] Update `guruMadrasah()` di PageController - baca dari `tenaga_ktd`
- [x] Update view `pegawaimadrasah.blade.php` - $pegawai->name jadi $pegawai->nama
- [x] Update view `gurumadrasah.blade.php` - $guru->name jadi $guru->nama
- [x] Fix migration `personal_access_tokens` - add if-not-exists check
- [x] Fix command untuk handle invalid dates (0000-00-00)
- [x] Cek area lain yang terpengaruh - tidak ada yang perlu diubah
- [ ] Update modal form Tambah Pegawai untuk simpan ke `tenaga_ktd`
- [ ] Phase 3: Admin-Side views (list & verify laporan)

## Data Flow

### Input (User-Side)
```
User Login → Cek dept_id → Cek kategori
    ↓ (jika min/mtsn/man/other)
Menu Laporan Madrasah Muncul
    ↓
Isi Form Laporan Semester
    ↓
Save as Draft / Submit
```

### Proses (Admin-Side)
```
Admin Login → Menu Madrasah
    ↓
Lihat Daftar Laporan
    ↓
Review & Verifikasi
    ↓
Approve / Reject
```

### Output
```
- Laporan tersimpan di database
- Status berubah (draft → submitted → verified/rejected)
- PDF export tersedia
```

## Files yang Dimodifikasi

| File | Perubahan |
|------|-----------|
| `resources/views/components/layouts/site-header.blade.php` | Tambah menu Laporan Madrasah |
| `resources/views/madrasah/profilmadrasah.blade.php` | Full NEO MIRAI theme + form action + CSRF + alert |
| `resources/views/madrasah/pegawaimadrasah.blade.php` | Full NEO MIRAI theme + change $pegawai->name to $pegawai->nama |
| `resources/views/madrasah/gurumadrasah.blade.php` | Full NEO MIRAI theme + change $guru->name to $guru->nama |
| `resources/views/madrasah/laporansemester.blade.php` | Full NEO MIRAI theme + form action + CSRF + alert |
| `resources/views/madrasah/laporanbulanan.blade.php` | Full NEO MIRAI theme + form action + CSRF + alert |
| `app/Http/Controllers/PageController.php` | Method saveProfilMadrasah, saveLaporanSemesterMadrasah, pegawaiMadrasah (read from tenaga_ktd), guruMadrasah (read from tenaga_ktd) |
| `routes/web.php` | Route POST untuk saveProfilMadrasah |
| `resources/css/neo-mirai-home.css` | Komponen baru: `.hero-label`, `.hero-title`, `.hero-desc`, `.hero-actions`, `.neo-stat-*`, `.neo-table-*`, `.neo-avatar-*`, `.neo-badge-*`, `.neo-action-btn`, `.neo-user-cell`, `.neo-pagination-*` |
| `MADRASAH_PROGRESS.md` | Update progress Phase 6 |
| `database/migrations/2026_08_02_062924_create_personal_access_tokens_table.php` | Add if-not-exists check |

## Files Baru

| File | Purpose |
|------|---------|
| `resources/views/madrasah/laporanbulanan.blade.php` | View laporan bulanan |
| `database/migrations/2026_08_04_000001_create_tenaga_ktd_table.php` | Migration tabel tenaga_ktd |
| `database/migrations/2026_08_04_000002_add_users_columns_to_tenaga_ktd.php` | Migration tambah kolom dari users ke tenaga_ktd |
| `app/Console/Commands/MigrateTenagaKtd.php` | Command migrasi data ke tenaga_ktd |
| `app/Console/Commands/CleanupUsersColumns.php` | Command cleanup kolom users |
| `resources/views/admin/madrasah/index.blade.php` | View list laporan (belum dibuat) |
| `resources/views/admin/madrasah/show.blade.php` | View detail & verifikasi (belum dibuat) |
| `MADRASAH_ADMIN_PROGRESS.md` | Progress admin-side (belum dibuat) |

**Note:** Tabel database sudah ada di database - tidak perlu buat migrasi baru (kecuali tenaga_ktd).

## TODO (Next Steps)
- [x] Cek struktur database yang sudah ada (tabel ktd_department, ktd_laporan_bulanan_madrasah, ktd_laporan_semester_madrasah)
- [x] Buat method saveProfilMadrasah - update ktd_department
- [x] Fix saveLaporanSemesterMadrasah - return redirect
- [x] Fix saveLaporanBulananMadrasah - sudah ada
- [x] Tambah POST route untuk saveProfilMadrasah
- [x] Update views dengan form action dan CSRF token
- [x] Phase 6: Buat migration & command untuk konsolidasi tenaga_ktd
- [ ] Jalankan migrasi: `php artisan migrate`
- [ ] Jalankan command migrasi: `php artisan madrasah:migrate-tenaga --migrate-all`
- [ ] Update PageController untuk baca dari `tenaga_ktd` (bukan users)
- [ ] Update view Pegawai & Guru Madrasah
- [ ] Phase 3: Admin-Side views (list & verify laporan)

## Changelog

### 2026-07-09
- Menambahkan menu Laporan Madrasah di user dropdown
- Menu muncul untuk user dengan kategori dept: min, mtsn, man, other
- Menu juga ditambahkan di mobile navigation
- File: `resources/views/components/layouts/site-header.blade.php`
- Memulai update tema halaman madrasah ke NEO MIRAI
- Header/Hero section sudah menggunakan `.hero-page`, `.content-centered`
- Tab Navigation sudah menggunakan `.neo-tabs`, `.neo-tab`
- Section 1 (Identitas) dan Section 2 (Alamat) sudah menggunakan `.neo-card`, `.neo-field-input`
- Menambahkan CSS components baru di `resources/css/neo-mirai-home.css`:
  - `.neo-field-label`, `.neo-field-input`, `.neo-field-select`, `.neo-field-hint`
  - `.neo-card` (enhanced), `.neo-card-header`, `.neo-card-icon`, `.neo-card-title`, `.neo-card-desc`, `.neo-card-body`
  - `.neo-tabs`, `.neo-tab`
  - `.neo-form-actions`, `.neo-btn-action-*` classes

### 2026-07-09 (Sesi 2)
- Update `pegawaimadrasah.blade.php` ke NEO MIRAI dengan CSS classes:
  - Stats cards menggunakan `.neo-stat-card`, `.neo-stat-icon`, `.neo-stat-info`
  - Table menggunakan `.neo-table-wrapper`, `.neo-table`, `.neo-table-header`, `.neo-table-row`, `.neo-table-cell`
  - Avatar menggunakan `.neo-avatar`, `.neo-avatar-initials`
  - Badge menggunakan `.neo-badge`, `.neo-badge-dot`, `.neo-badge-primary`, `.neo-badge-warning`
  - User cell menggunakan `.neo-user-cell`, `.neo-user-info`, `.neo-user-name`, `.neo-user-nip`
  - Action button menggunakan `.neo-action-btn`
  - Pagination menggunakan `.neo-pagination-wrap`, `.neo-pagination-row`, `.neo-pagination-info`, `.neo-pagination-nav`, `.neo-pagination-link`, `.neo-pagination-link is-active`, `.neo-pagination-link is-disabled`
  - Empty state menggunakan `.neo-empty-state`
- Update `gurumadrasah.blade.php` dengan pattern yang sama
- Menambahkan CSS components untuk Hero section: `.hero-label`, `.hero-title`, `.hero-desc`, `.hero-actions`
- Menghapus inline CSS yang tidak diperlukan
- Progress: `gurumadrasah.blade.php` sudah SELESAI

### 2026-07-09 (Sesi 3)
- Update `laporansemester.blade.php` ke NEO MIRAI theme
- Header/hero section menggunakan `.hero-label`, `.hero-title`, `.hero-desc`
- Tab navigation menggunakan `.neo-tabs`, `.neo-tab is-active`
- Form tables menggunakan `.neo-card`, `.neo-card-header`, `.neo-card-icon`, `.neo-table-wrapper`, `.neo-table`
- Input fields menggunakan `.neo-form-input`
- Action buttons menggunakan `.neo-form-actions`, `.neo-btn-action-save`
- Semua section: Keadaan Gedung, Sarana, Bantuan Pemerintah, Bantuan Non Pemerintah, Data Guru/Pegawai, Tingkat Pendidikan, Sertifikasi, Absensi, Tanah
- Progress: `laporansemester.blade.php` sudah SELESAI

### 2026-07-09 (Sesi 4 - Perbaikan)
- Convert `madrasah-bg.png` ke `madrasah-bg.webp` (10MB -> 287KB, 97% reduction)
- Tambahkan class `.hero-page.has-bg-image` untuk background image di hero section
- Update semua file madrasah: pegawaimadrasah, gurumadrasah, laporansemester, profilmadrasah
- Tambahkan section "Jarak Madrasah ke..." di profilmadrasah.blade.php (sesuai gambar referensi)
- Reactive totals menggunakan JavaScript sederhana dengan data attributes `data-tot-*`
- Total update otomatis saat input berubah
- Fix: profilmadrasah hero background muncul, totals berfungsi reactive

### 2026-07-09 (Sesi 5 - NEO MIRAI Theme)
- **Rebuild `laporansemester.blade.php`** dari tema dark ke **NEO MIRAI** theme
- Perubahan yang dilakukan:
  - Hero section: menggunakan `.hero-page`, `.has-bg-image` dengan CSS variables `var(--gold)`, `var(--ink)`
  - Tab navigation: menggunakan `.neo-tabs`, `.neo-tab`, `.neo-tab.is-active`
  - Semua card sections: menggunakan `.neo-card`, `.neo-card-header`, `.neo-card-icon`, `.neo-card-title`, `.neo-card-desc`, `.neo-card-body`
  - Semua tables: menggunakan `.neo-table-wrapper`, `.neo-table`, `.neo-table-header`, `.neo-table-row`, `.neo-table-cell-*`
  - Semua input fields: menggunakan `.neo-form-input`
  - Action buttons: menggunakan `.neo-form-actions`, `.neo-btn-action-save`
  - Menghapus semua class dark theme: `slate-*`, `bg-gradient-*`, `backdrop-blur`, `shadow-xl`, `text-white`
- Sections yang di-convert:
  1. Keadaan Gedung
  2. Sarana Pendidikan
  3. Bantuan dari Pemerintah
  4. Bantuan Non Pemerintah
  5. Data Guru/Pegawai
  6. Tingkat Pendidikan
  7. Sertifikasi
  8. Kehadiran & Absensi
  9. Tanah & Sertifikat Tanah
- Progress: `laporansemester.blade.php` sekarang menggunakan **100% NEO MIRAI theme**

### 2026-07-09 (Sesi 6 - Reactive Totals)
- Menambahkan **JavaScript reactive** untuk menghitung totals secara otomatis saat input berubah
- Fungsi yang ditambahkan:
  - `calculateRowTotal()` - menghitung total per baris
  - `calculateSectionTotal()` - menghitung total section
  - Event listeners untuk setiap input number field
- Tables yang di-handle:
  1. **4-column tables** (Baik, Ringan, Sedang, Berat, Jml): Keadaan Gedung, Sarana
  2. **3-column tables** (Diterima, Terserap, Saldo): Bantuan Pemerintah, Bantuan Non Pemerintah
  3. **3-column tables** (L, P, Jml): Data Guru/Pegawai, Tingkat Pendidikan, Absensi
  4. **Sertifikasi** table
- Footer totals juga ikut ter-update saat data berubah
- Saldo (Diterima - Terserap) dihitung otomatis

### 2026-07-09 (Sesi 8 - Fix Totals & Add Row)
- **Perbaikan perhitungan totals**:
  - Menggunakan `<tfoot>` untuk footer totals (bukan `<tbody>` dengan last row)
  - Data attributes untuk tracking: `data-row-total`, `data-col-total`, `data-grand-total`
  - Semua totals dihitung dari `<tfoot>` dengan benar
  - Initial calculation saat page load

- **Fitur Tambah/Hapus Row**:
  - Tombol "Tambah" di setiap section (`.neo-btn-add`)
  - Tombol hapus per row (`.neo-btn-remove`)
  - Fungsi JavaScript `addRow(type)` dan `removeRow(btn)`
  - Template untuk setiap type table: gedung, sarana, bantuanP, bantuanNP, guru, tingkat, sertifikasi, absensi
  - Minimum 1 row harus ada (tidak bisa dihapus semua)

- **Perbaikan CSS**:
  - `.neo-btn-add`: gold background, auto placement di header
  - `.neo-btn-remove`: transparent dengan hover merah
  - `.neo-table-footer`: styling untuk footer row

- **Tables yang diupdate**:
  1. Keadaan Gedung (5 kolom: label, baik, ringan, sedang, berat)
  2. Sarana Pendidikan (5 kolom)
  3. Bantuan Pemerintah (4 kolom: label, diterima, terserap, saldo)
  4. Bantuan Non Pemerintah (4 kolom)
  5. Data Guru/Pegawai (4 kolom: label, L, P, Jml)
  6. Tingkat Pendidikan (4 kolom)
  7. Sertifikasi (4 kolom)
  8. Absensi Siswa (4 kolom)

### 2026-07-09 (Sesi 9 - Bug Fix)
- **Fix error `insertBefore` not a child node**:
  - Error terjadi karena `tfoot` tidak ada di beberapa tables
  - Solution: menggunakan `appendChild` untuk menambahkan row baru
  - Row baru ditambahkan dengan `tbody.appendChild(newRow)`
  - Recalculate totals setelah row ditambahkan

### 2026-07-09 (Sesi 7 - Table Total Styling)
- Menambahkan CSS class `.neo-table-total` dan `.neo-table-total.highlight` di `neo-mirai-home.css`
- Styling baru:
  - Font size: `1rem` (lebih besar dari default `0.7rem`)
  - Font weight: `700` (bold)
  - Color: `var(--gold)`
  - Background highlight dengan subtle glow
- Class diterapkan pada:
  - Semua cell totals per baris
  - Footer row totals (Jumlah)
  - Saldo cells
- JavaScript reactive juga meng-update class saat totals berubah

### 2026-07-22 (Fitur Baru: Laporan Bulanan)
- **Membuat fitur Laporan Bulanan Madrasah** (mirip Vue component yang diberikan user)
- File baru: `resources/views/madrasah/laporanbulanan.blade.php`
- Struktur halaman:
  - Hero section dengan status badge dan meta info
  - Tab navigation untuk semua halaman madrasah
  - Section A: Informasi Laporan (bulan, tahun, tahun ajaran, semester, RB)
  - Section B: Keadaan Siswa per rombel (card-based, grid layout)
  - Section C: Data Mutasi (card-based dengan badge badge)
- Komponen UI baru:
  - `.neo-card-hero` - Card untuk hero info
  - `.rombel-card` - Card untuk setiap rombel
  - `.mutation-card` - Card untuk setiap data mutasi
  - `.gender-input-grid` - Grid untuk input L/P
  - `.status-badge` - Badge untuk status laporan
- JavaScript features:
  - Reactive total calculation per rombel dan level
  - Grand total (Laki-laki, Perempuan, Total Siswa)
  - Mutation stats (Mutasi Masuk/Keluar, DO)
  - Add/Remove rombel per tingkat
  - Add/Remove mutation rows
- Routes baru:
  - `GET /madrasah/laporan-bulanan` - Halaman form
  - `POST /madrasah/laporan-bulanan/save` - Simpan laporan
- Controller methods:
  - `laporanBulananMadrasah()` - Tampilkan form
  - `saveLaporanBulananMadrasah()` - Simpan data
  - `getMadrasahClassLevels()` - Helper untuk struktur rombel per kategori
- Theme: **100% NEO MIRAI**

### 2026-07-22 (Fixes & Enhancements)
- **Fix PHP syntax error** - template variables yang kosong telah diisi
- **Fix CSS buttons** - menggunakan class yang benar:
  - `neo-btn-action-reset` untuk Reset
  - `neo-btn-action-save` untuk Simpan Draft
  - `neo-btn-submit` untuk Kirim Laporan
- **Tambah CSS classes baru**:
  - `.level-header` - Header section per tingkat
  - `.level-footer` - Footer dengan totals per tingkat
  - `.rombel-level-section` - Section untuk tingkat kelas
  - `.neo-badge-primary` - Badge dengan warna gold
- **Hapus inline styles** - styling dipindahkan ke CSS classes
- **Fix dropdown placeholder** - option "Pilih" sekarang disabled
  - "Pilih jenis data" → disabled
  - "Pilih kelas" → disabled
  - "Pilih" → disabled + selected
- **Tambah tab menu** di semua halaman madrasah:
  - profilmadrasah.blade.php
  - pegawaimadrasah.blade.php
  - gurumadrasah.blade.php
  - laporansemester.blade.php

### 2026-08-04 (Implementasi Database Storage)
- **Cek struktur database yang sudah ada:**
  - `ktd_laporan_bulanan_madrasah` - sudah ada dengan field: id, dept_id, bulan_laporan, tahun_laporan, tahun_ajaran, semester, status, student_counts_json, mutation_rows_json, dll
  - `ktd_laporan_semester_madrasah` - sudah ada dengan field: id, dept_id, semester, tahun_ajaran, status, keadaan_gedung_json, sarana_pendidikan_json, dll
  - `ktd_department` - sudah ada dengan field untuk profil madrasah lengkap (nama, nsm, npsm, alamat, kontak, akreditasi, jarak, dll)
- **Tidak perlu migrasi baru** - tabel sudah ada di database

- **Implementasi `saveProfilMadrasah`:**
  - Method baru di PageController.php (setelah profilMadrasah)
  - Update data ke tabel `ktd_department`
  - Handle semua field: nsm, npsm, status_lembaga, alamat (gabungan jalan/jorong/nagari/kecamatan), kontak, website, visi, sk_pendirian, akreditasi, jarak, dll
  - Return redirect dengan success/error message

- **Fix `saveLaporanSemesterMadrasah`:**
  - Ubah return dari JSON ke redirect (sesuai pattern Laravel)
  - Handle semester dengan uppercase/lowercase (accept Ganjil/Genap/ganjil/genap)
  - Gunakan `action` button value (draft/submit) untuk menentukan status
  - Return redirect dengan success message

- **Route baru:**
  - `POST /madrasah/profil/save` -> saveProfilMadrasah

- **Update views dengan form action dan CSRF:**
  - `profilmadrasah.blade.php`:
    - Tambah `<form action="{{ route('madrasah.profil.save') }}" method="POST">`
    - Tambah `@csrf` token
    - Tambah alert messages untuk success/error
  - `laporansemester.blade.php`:
    - Tambah `<form action="{{ route('madrasah.laporan-semester.save') }}" method="POST">`
    - Tambah `@csrf` token
    - Tambah alert messages
    - Hapus orphan hidden input status (sekarang pakai action button)
  - `laporanbulanan.blade.php`:
    - Sudah punya form action dan CSRF
    - Tambah alert messages untuk success/error

### 2026-08-04 (Simplifikasi Form Informasi)
- **Hapus form filter duplikat** di halaman Laporan Bulanan:
  - Filter form (bulan, tahun, tahun ajaran, semester) di atas sudah dihapus
  - Sekarang hanya ada **satu form Informasi Madrasah** di Section A
  - Periode laporan tetap ditampilkan di banner info (read-only)
  - Field bulan, tahun, tahun ajaran, semester sekarang menggunakan hidden inputs
  - User mengisi data periode melalui dropdown di header/navigation (route `/madrasah/laporan-bulanan?bulan=X&tahun=Y&tahun_ajaran=Z&semester=S`)

### 2026-08-04 (Filter Form Perbaikan)
- **Perbaiki form filter menjadi lebih menarik**:
  - Tambahkan header "Pilih Periode Laporan" dengan icon filter
  - Semua dropdown sejajar ke kanan
  - Label dengan icon untuk setiap dropdown
  - Style CSS baru: `.filter-container`, `.filter-header`, `.filter-item`, `.filter-label`, `.filter-select`
- **Default value sesuai waktu saat ini**:
  - Bulan: menggunakan array `$bulanIndonesia` untuk nama bulan Bahasa Indonesia
  - Tahun Ajaran: logic Juli-Desember = Ganjil (tahun/tahun+1), Jan-Juni = Genap (tahun-1/tahun)
  - Controller `laporanBulananMadrasah()` diupdate untuk menggunakan nama bulan Indonesia

### 2026-08-04 (RB & Mutation Improvements)
- **RB Field (Rombel) di Informasi Madrasah**:
  - Dibuat read-only (`readonly`)
  - Otomatis menghitung jumlah rombel dari bagian Keadaan Siswa via JavaScript
  - Function `updateRombelInfo()` untuk sinkronisasi RB count
- **Dropdown Kelas di Data Siswa Mutasi**:
  - Sekarang dinamis - mengambil kelas dari rombel yang ada di Keadaan Siswa
  - Saat menambah/hapus rombel, dropdown kelas otomatis ter-update
  - Function `updateRombelInfo()` juga mengupdate semua dropdown `.kelas-select`
- **Total Data Mutasi**:
  - Sekarang hanya menghitung jika Nama Siswa terisi
  - Event listener untuk recalculate saat input nama berubah
- **Hapus tombol aksi di Hero Card**:
  - Tombol Reset, Simpan Draft, Kirim dihapus dari Hero Meta Info Card
  - Tombol tetap ada di bagian bawah form

### 2026-08-04 (Ambil Data Terbaru Jika Belum Ada)
- **Logic controller diupdate** untuk mengambil data terbaru:
  - Jika user memilih periode dan data belum ada:
    - Ambil `student_counts` dari periode terbaru yang ada
    - `mutation_rows` tetap kosong
    - Status tetap "Belum dikirim"
  - Jika data ada untuk periode tersebut, tampilkan semua data
- Ini mengikuti pola yang sama dengan Laporan Semester

### 2026-08-04 (Konsolidasi Tabel Tenaga Kependidikan)
- **Analisis & Perancangan:**
  - Bandingkan struktur `guru_madrasah` dan `pegawai_madrasah`
  - Temukan field duplikat: `name/nama`, `jk/jenis_kelamin`, dll
  - Rancang tabel unified `tenaga_ktd` dengan semua field yang diperlukan

- **File Baru:**
  - `database/migrations/2026_08_04_000001_create_tenaga_ktd_table.php` - Migration untuk tabel baru
  - `app/Console/Commands/MigrateTenagaKtd.php` - Command migrasi data
  - `app/Console/Commands/CleanupUsersColumns.php` - Command cleanup kolom

- **Tabel `tenaga_ktd`:**
  - `id`, `dept_id`, `created_by`, `user_id` - Relasi
  - `nama`, `kat_jabatan` (guru/staf/honorer), `status` (PNS/PPPK/Honorer)
  - `nomor_induk`, `nik`, `npwp`, `nuptk`, `npk`, `nrg` - Berbagai ID
  - `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `nama_ibu` - Data personal
  - `golongan`, `jabatan`, `pekerjaan`, `bidang_studi_diajar`, `serdik` - Jabatan
  - `pendidikan`, `jurusan`, `fakultas`, `universitas`, `tahun_lulus` - Pendidikan
  - `tmt_tugas`, `kgb`, `masa_kerja_tahun/bulan` - Kepegawaian
  - `email`, `telp`, `alamat_ktp`, `alamat`, `keterangan` - Kontak
  - `is_active`, `source_table` - Meta

- **Artisan Commands:**
  1. `php artisan madrasah:migrate-tenaga --migrate-all` - Migrasi semua data
  2. `php artisan madrasah:migrate-tenaga --create-table` - Buat tabel saja
  3. `php artisan madrasah:migrate-tenaga --migrate-guru` - Migrasi guru_madrasah
  4. `php artisan madrasah:migrate-tenaga --migrate-pegawai` - Migrasi pegawai_madrasah
  5. `php artisan madrasah:migrate-tenaga --migrate-users` - Migrasi users
  6. `php artisan madrasah:cleanup-users --list` - List kolom yang bisa dihapus
  7. `php artisan madrasah:cleanup-users --remove-duplicates` - Hapus kolom duplikat
  8. `php artisan madrasah:cleanup-users --drop-old-tables` - Hapus tabel lama

- **Pattern Sinkronisasi:**
  - `users` - untuk login & akses aplikasi
  - `tenaga_ktd` - untuk data lengkap tenaga kependidikan

### 2026-08-04 (Lanjutan Konsolidasi)
- **Migration tambahan:**
  - `2026_08_04_000002_add_users_columns_to_tenaga_ktd.php` - Tambah kolom dari users
  - Kolom ditambahkan: `tmt_cpns`, `tmt_pns`, `nikah`, `jenis_pjob`, `pjob`, `req_tunjangan`, `jml_anak`, `nama_istri_suami`, `kk`, `bio`, `facebook`, `twitter`, `linkedin`, `instagram`
  - Fix: migration `personal_access_tokens` add if-not-exists check

- **Perbaikan Command:**
  - Fix: `safeDate()` function untuk handle invalid dates (0000-00-00)
  - Fix: semua field menggunakan `?? null` untuk avoid undefined property error
  - Fix: `golongan` fallback dari `golongan ?? $record->gol`

- **Update PageController:**
  - `pegawaiMadrasah()` - query dari `tenaga_ktd`, filter `kat_jabatan IN ('staf', 'honorer')`
  - `guruMadrasah()` - query dari `tenaga_ktd`, filter `kat_jabatan = 'guru'`
  - Photo URL diambil dari `users` via `user_id`
  - Stats dihitung dari `tenaga_ktd`

- **Update Views:**
  - `pegawaimadrasah.blade.php`: `$pegawai->name` → `$pegawai->nama`, `$pegawai->asn` → `$pegawai->status`
  - `gurumadrasah.blade.php`: `$guru->name` → `$guru->nama`

- **Pengecekan Area Terpengaruh:**
  - PenilaianKinerjaController - TIDAK PERLU DIUBAH (untuk pejabat struktural Kantor)
  - PageController (laporan bawahan, signature) - TIDAK PERLU DIUBAH
  - Admin UserController - TIDAK PERLU DIUBAH (untuk login system)
  - API UserController - TIDAK PERLU DIUBAH (untuk mobile app)
  - MadrasahController (Admin) - TIDAK PERLU DIUBAH (untuk profil madrasah)