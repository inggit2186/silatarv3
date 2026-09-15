# Progress Publikasi

## Overview
Halaman publikasi publik untuk menampilkan dokumen yang diunggah admin/petugas dan dapat diunduh oleh user.

## Status: SELESAI

## Checklist
- [x] Buat file progress feature
- [x] Buat migration tabel publikasi
- [x] Buat controller admin publikasi
- [x] Tambah route admin dan publik
- [x] Buat view admin CRUD publikasi
- [x] Buat halaman publikasi publik
- [x] Tambah menu Publikasi di navigasi publik dan admin
- [x] Tambah styling NEO MIRAI bila diperlukan
- [x] Verifikasi route, format, dan test

## Data Flow
```
Admin/Petugas upload file -> Storage public/publikasi -> Database publikasi -> Halaman /publikasi -> User download file
```

## Files yang Dimodifikasi
| File | Perubahan |
|------|-----------|
| routes/web.php | Route halaman publikasi dan download |
| routes/admin.php | Route CRUD admin publikasi |
| app/Http/Controllers/PageController.php | Query publikasi publik dan download |
| resources/views/components/layouts/site-header.blade.php | Menu Publikasi publik |
| resources/views/admin/layouts/app.blade.php | Menu Publikasi admin |
| resources/css/neo-mirai-home.css | Class publikasi NEO MIRAI |

## Files Baru
| File | Purpose |
|------|---------|
| database/migrations/2026_09_15_000001_create_publikasi_table.php | Tabel publikasi |
| app/Http/Controllers/Admin/PublikasiController.php | CRUD publikasi admin |
| resources/views/admin/publikasi/index.blade.php | Daftar publikasi admin |
| resources/views/admin/publikasi/create.blade.php | Form tambah publikasi |
| resources/views/admin/publikasi/edit.blade.php | Form edit publikasi |
| resources/views/publikasi.blade.php | Halaman publikasi publik |

## TODO
- [x] Jalankan formatting dan verifikasi setelah implementasi

## Changelog
### 2026-09-15
- Memulai implementasi fitur publikasi.
- Menambahkan migration tabel `publikasi`.
- Menambahkan controller dan route admin publikasi (`/admin/publikasi`).
- Menambahkan route publik `/publikasi` dan `/publikasi/{slug}/download`.
- Menambahkan method `publikasi()` dan `downloadPublikasi()` pada `PageController`.
- Menambahkan menu Publikasi pada navigasi header, hamburger menu, dan sidebar admin.
- Menambahkan view admin CRUD: index, create, edit.
- Menambahkan halaman publikasi publik dengan pencarian, filter, pagination, dan tombol download.
- Menambahkan CSS `.neo-publication-*` di `resources/css/neo-mirai-home.css` untuk mengurangi inline CSS.
- Verifikasi route berhasil, file ditemukan, dan syntax PHP tidak ada error.
