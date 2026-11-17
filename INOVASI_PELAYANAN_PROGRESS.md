# Progress Dokumen Inovasi Pelayanan SILATAR (PMPZI)

## Overview

Menyusun **dokumen "inovasi pelayanan" SILATAR** untuk keperluan penilaian **PMPZI
(Penilaian Mandiri Pembangunan Zona Integritas)** Kementerian Agama. Dokumen
disusun sebagai **halaman HTML print-friendly** (ramai cetak ke PDF) berisi
penjelasan aplikasi SILATAR sebagai inovasi pelayanan Kantor Kementerian Agama
Kabupaten Tanah Datar, dilengkapi tangkapan layar (screenshot) aplikasi web
portal dan aplikasi mobile.

## Status: SELESAI (menunggu verifikasi cetak oleh pemohon)

## Checklist

- [x] Inventarisasi fitur aplikasi mobile SILATAR (Flutter, repo `d:/silatar_v2`)
- [x] Inventarisasi fitur web portal SILATAR (Laravel, repo ini)
- [x] Menjalankan web portal lokal (`php artisan serve`, port 8123)
- [x] Menyiapkan alat tangkapan layar (puppeteer-core + Chrome, headless)
- [x] Membuat akun uji sementara di DB lokal untuk login & screenshot
- [x] Menangkap screenshot halaman publik (beranda, katalog layanan, form
      pengajuan, tracking pengajuan, berita, publikasi, unit kerja, pengaduan)
- [x] Menangkap screenshot panel administrasi (dashboard, users, layanan,
      unit kerja, pengajuan, berita, acara, janji temu, rekap presensi)
- [x] Menangkap screenshot versi mobile (responsive) web portal
- [x] Membangun Flutter web build untuk tangkapan layar aplikasi mobile
- [x] Menangkap screenshot aplikasi mobile (welcome, login, dashboard, presensi,
      layanan, kegiatan, janji temu, berita)
- [x] Menyusun HTML dokumen print-friendly
- [x] Verifikasi struktur cetak (A4, page break, legibilitas) — otomatis;
      pemeriksaan visual akhir dilakukan manual oleh pemohon
- [x] Membersihkan data uji (akun, pengajuan contoh, revert `api_config.dart`)

## Data Flow

```
Repo mobile (d:/silatar_v2)      Repo web (silatarV2)
   Flutter (Dart)        <--API-->   Laravel 13 + Tailwind 4
        |                                  |
   build/web (screenshot)           php artisan serve :8123
        |                                  |
        +----------> puppeteer-core --------+
                          |
              public/dokumen/inovasi-pelayanan/img/*.jpg
                          |
              public/dokumen/inovasi-pelayanan/inovasi-pelayanan-silatar.html
```

## Temuan Teknis

### 1. Login gagal bila memakai email (bug laten di `AuthController::login`)

Kode:

```php
$user = User::query()
    ->where('email', strtolower($login))
    ->orWhere('nomor_induk', $login)
    ->first();
```

Kolom `users.nomor_induk` bertipe **INT**. Ketika pengguna mengetik **email**,
MySQL melakukan konversi implisit `'screenshot.tmp@silatar.local'` → `0`, sehingga
klausa `OR nomor_induk = 0` ikut mencocokkan **semua baris** dengan
`nomor_induk = 0` (banyak akun(role `user`)). Karena `first()` tanpa `ORDER BY`,
baris yang dikembalikan bisa bukan akun yang dimaksud sehingga validasi password
gagal meskipun email & password benar.

**Workaround (non-invasive, dipakai khusus coursework screenshot):** login memakai
**nomor induk** (NIP) numerik. **Belum diperbaiki di kode produksi** — perlu
diperbaiki (grouping `where` / cast kolom / `$user->getAuthIdentifierName()`).

### 2. Akun uji sementara — **SUDAH DIHAPUS**

Dibuat di DB lokal `kemenagtd_db` (tabel `users`):

| Field | Nilai |
|-------|-------|
| name | `Ahmad Fauzi, S.Ag.` |
| nomor_induk / nip | `196701011990031000` |
| email | `screenshot.tmp@silatar.local` |
| role | `superadmin` |
| dept_id | `4` (Sub-Bagian Tata Usaha) |
| password | `Screenshot2026!` |
| id | `11190` |

> Akun ini beserta seluruh data turunannya sudah dihapus pada 2026-11-17
> (lihat bagian *Cleanup* di bawah).

### 3. Data pengajuan contoh — **SUDAH DIHAPUS**

3 baris `users_request` dibuat atas nama akun uji (status `DITERIMA`,
`DIPROSES`, `PENDING`) agar halaman **Pengajuan Saya** dan panel
administrasi menampilkan data, bukan kondisi kosong.

Selain itu dibuat 2 baris `satker_kegiatan` (`tanggal` November 2026) berisi
6 butir kegiatan, 2 hari terisi, volume 29, durasi 8 jam 30 menit — dipakai
untuk mengisi tangkapan layar rekap bulanan.

### 4. Perubahan sementara pada repo mobile — **SUDAH DI-REVERT**

`lib/core/services/api_config.dart` — `baseUrl` diubah dari
`https://kemenagtanahdatar.id/api` menjadi `http://127.0.0.1:8123/api`
agar build web Flutter dapat terhubung ke API lokal.

> Sudah dikembalikan ke nilai produksi (`https://kemenagtanahdatar.id/api`)
> pada 2026-11-17. Folder `build/web` (hasil `flutter build web` khusus
> tangkapan layar, masih menunjuk ke localhost) juga sudah dihapus.

### 5. Ekstensi PHP `intl` belum aktif

`php artisan db:show` gagal karena extension `intl` belum di-load (error
`The "intl" PHP extension is required to use the [format] method`). Tidak
menghambat halaman web yang dirender, namun perlu diaktifkan agar
fitur laporan/angka berjalan rapi.

### 6. Modul SIMPEG belum punya tangkapan layar khusus

Layar **Verifikasi SIMPEG** (`lib/features/simpeg/admin_simpeg_page.dart`)
ada di aplikasi mobile, tetapi belum diambil tangkapan layar terpisah. Yang
dimuat di dokumen ini adalah tangkapan layar menu **Panel Admin**
(`56-mobile-admin.jpg`) yang memuat daftar menu, termasuk "Verifikasi SIMPEG".
Halaman **Manajemen Pengajuan** di panel administrasi web masih berupa
placeholder "Halaman dalam pengembangan" (`admin/requests/index.blade.php`),
dan memang dicatat demikian di lampiran.

## Files yang Dimodifikasi

| Path | Perubahan |
|------|-----------|
| `app/Http/Controllers/AuthController.php` | debug sementara untuk diagnosa login — **sudah dikembalikan** (verified via `git status`) |
| `lib/core/services/api_config.dart` (repo `d:/silatar_v2`) | `baseUrl` → server lokal — **sudah di-revert** (verified via `git status`) |

## Files Baru

| Path | Purpose |
|------|---------|
| `public/dokumen/inovasi-pelayanan/inovasi-pelayanan-silatar.html` | Dokumen "_inovasi pelayanan_" (print-friendly) |
| `public/dokumen/inovasi-pelayanan/img/*.jpg` | Tangkapan layar aplikasi (39 berkas) |
| `INOVASI_PELAYANAN_PROGRESS.md` | File progress fitur ini |

## Publikasi & Akses

Dokumen dipublikasikan di dalam docroot aplikasi agar punya URL sendiri dan
siap dipakai untuk dokumen-dokumen berikutnya:

```
public/dokumen/
  inovasi-pelayanan/          ← satu folder per dokumen
    inovasi-pelayanan-silatar.html
    img/*.jpg
```

| Lingkungan | URL |
|------------|-----|
| Produksi | `https://kemenagtanahdatar.id/dokumen/inovasi-pelayanan/inovasi-pelayanan-silatar.html` |
| Lokal | `http://127.0.0.1:8123/dokumen/inovasi-pelayanan/inovasi-pelayanan-silatar.html` |

Catatan:
- Dokumen **s mandiri** — 37 rujukan `<img>` relatif (`img/...`) dan tanpa
  satu pun tautan keluar, sehingga aman dipindah antar folder maupun
  dipaketkan ulang.
- Folder `img/` **wajib** ikut tersalin saat dokumen dipindahkan/diarsipkan.
- Karena berada di `public/`, dokumen ikut ter-*deploy* bersama aplikasi dan
  ikut ter-*version control* (bukan diabaikan `.gitignore`).
- Untuk dokumen berikutnya, gunakan pola `public/dokumen/<slug-dokumen>/`
  agar URL-nya konsisten.

## Hasil Verifikasi Cetak

Diperiksa pada 2026-11-17 dengan headless Chrome
(`--print-to-pdf`, `--no-pdf-header-footer`):

| Aspek | Hasil |
|-------|-------|
| Ukuran halaman | A4 — MediaBox `594.96 × 841.92` pt (210 × 297 mm) |
| Jumlah halaman | 35 |
| Halaman kosong | tidak ada |
| Integritas gambar | 39 berkas tangkapan layar tersedia; seluruh rujukan `<img>` teratasi (tanpa gambar rusak) |
| Struktur | 12 bagian + ringkasan eksekutif + daftar isi |

Pemeriksaan visual akhir (keterbacaan teks, spasi halaman, konsistensi
tampilan) **dilakukan manual oleh pemohon** — lihat bagian *TODO*.

## TODO

- [ ] Review visual hasil cetak oleh pemohon (cek manual di Chrome: Ctrl+P →
      *Save as PDF*, A4, margin *Default*, *Background graphics* aktif).
- [ ] Opsional: tangkapan layar khusus layar **Verifikasi SIMPEG** di aplikasi
      mobile (butuh build ulang Flutter web + akun uji).
- [ ] Opsional: perbaikan bug login email di `AuthController::login`
      (lihat Temuan Teknis #1) — terpisah dari pekerjaan dokumen ini.

## Cleanup — selesai 2026-11-17

- [x] Akun uji `users.id = 11190` dihapus.
- [x] 3 baris `users_request` (8746–8748) dihapus.
- [x] 2 baris `satker_kegiatan` milik akun uji dihapus.
- [x] 3 baris `sessions` milik akun uji dihapus.
- [x] `lib/core/services/api_config.dart` di-revert ke URL produksi.
- [x] `build/web` (build khusus tangkapan layar) dihapus.
- [x] `node_modules/puppeteer-core` + 10 paket turunannya yang tidak ada di
      `package-lock.json` dihapus (`@puppeteer/browsers`, `chromium-bidi`,
      `devtools-protocol`, `get-east-asian-width`, `mitt`, `modern-tar`,
      `typed-query-selector`, `webdriver-bidi-protocol`, `ws`, `zod`).
- [x] `public/dokumen/inovasi-pelayanan/img/62-mobile-simpeg.jpg` dihapus — berkas ini
      merupakan salinan identik dari `60-mobile-janji-temu.jpg` (hasil tangkapan
      yang tidak pernah sampai ke layar SIMPEG), sehingga tidak pernah dirujuk
      dokumen.

## Changelog

### 2026-11-17
- Dokumen dipindahkan dari `docs/inovasi-pelayanan/` ke
  `public/dokumen/inovasi-pelayanan/` agar punya URL di docroot aplikasi dan
  siap menampung dokumen-dokumen berikutnya. Verifikasi ulang: dokumen dan
  seluruh gambar merespons HTTP 200, hasil cetak tetap 35 halaman A4
  (ukuran byte identik dengan render dari `file://`).
- Dokumen HTML print-friendly selesai disusun: 12 bagian, 35 halaman A4.
- Screenshot aplikasi mobile (Flutter web build) berhasil diambil — welcome,
  login, dashboard, presensi, layanan, kegiatan, janji temu, berita, acara.
- Verifikasi struktur cetak: A4, 35 halaman, tanpa halaman kosong, seluruh
  gambar tertaut.
- `62-mobile-simpeg.jpg` dibuang (duplikat salah tangkap dari 60-mobile-janji-temu).
- Cleanup data uji di DB lokal, revert `api_config.dart`, hapus `build/web` dan
  `node_modules/puppeteer-core`.

### 2026-11-16
- Inventarisasi fitur mobile + web portal.
- Web portal dijalankan lokal; screenshot publik & admin berhasil diambil.
- Akun uji + pengajuan contoh dibuat di DB lokal.
- Flutter web build berhasil (untuk screenshot aplikasi mobile).
