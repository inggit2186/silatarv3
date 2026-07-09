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
- [ ] Buat migrasi tabel `ktd_laporan_semester_madrasah`
- [ ] Buat migrasi tabel `ktd_madrasah_pegawai` (jika belum ada)
- [ ] Buat migrasi tabel `ktd_madrasah_guru` (jika belum ada)

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
- [ ] Tambah fitur Submit laporan (selain draft)

### Phase 3: Admin-Side (Verifikasi)
- [ ] Buat view admin `resources/views/admin/madrasah/index.blade.php`
- [ ] Buat view admin `resources/views/admin/madrasah/show.blade.php`
- [ ] Buat controller method untuk list & verify laporan
- [ ] Tambah route admin untuk madrasah
- [ ] Tambah menu sidebar admin untuk Madrasah

### Phase 4: Export & Report
- [ ] Generate laporan ke PDF
- [ ] Export Excel (optional)

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
| `resources/views/madrasah/profilmadrasah.blade.php` | Full NEO MIRAI theme |
| `resources/views/madrasah/pegawaimadrasah.blade.php` | Full NEO MIRAI theme dengan CSS classes |
| `resources/views/madrasah/gurumadrasah.blade.php` | Full NEO MIRAI theme dengan CSS classes |
| `resources/views/madrasah/laporansemester.blade.php` | Full NEO MIRAI theme dengan CSS classes |
| `resources/css/neo-mirai-home.css` | Komponen baru: `.hero-label`, `.hero-title`, `.hero-desc`, `.hero-actions`, `.neo-stat-*`, `.neo-table-*`, `.neo-avatar-*`, `.neo-badge-*`, `.neo-action-btn`, `.neo-user-cell`, `.neo-pagination-*` |

## Files Baru

| File | Purpose |
|------|---------|
| `database/migrations/xxxx_create_ktd_laporan_semester_madrasah.php` | Tabel laporan semester |
| `database/migrations/xxxx_create_ktd_madrasah_pegawai.php` | Tabel data pegawai |
| `database/migrations/xxxx_create_ktd_madrasah_guru.php` | Tabel data guru |
| `resources/views/admin/madrasah/index.blade.php` | View list laporan |
| `resources/views/admin/madrasah/show.blade.php` | View detail & verifikasi |
| `resources/css/admin-madrasah.css` | CSS khusus madrasah (jika perlu) |
| `MADRASAH_ADMIN_PROGRESS.md` | Progress admin-side |

## TODO (Next Steps)
- [ ] Cek struktur database yang sudah ada (tabel ktd_department, dll)
- [ ] Buat migrasi untuk tabel-tabel baru
- [ ] Review form laporan semester yang sudah ada
- [ ] Tambah fitur Submit laporan (selain draft)
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
