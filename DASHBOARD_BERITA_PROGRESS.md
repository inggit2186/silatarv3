# Progress Dashboard Redesign - Berita Slideshow & Quick Actions

## Overview
Dashboard/home aplikasi SILATAR (Flutter) dirombak: statistik pengajuan dan aktivitas terakhir
dihilangkan, diganti slideshow berita terbaru (5 berita) dan aksi cepat disederhanakan jadi 3 tombol
yang benar-benar terhubung ke fitur masing-masing.

## Status: SELESAI

## Checklist
- [x] Endpoint API `/api/news` (5 berita terbaru, published)
- [x] Endpoint API `/api/news/{slug}` detail berita
- [x] Model `NewsModel` di Flutter
- [x] Method `ApiService.getLatestNews()` 
- [x] Widget slideshow berita (cover, judul, ringkasan, autoplay 5s, indikator)
- [x] Bottom sheet detail ringkasan berita
- [x] Hapus statistik pengajuan dari dashboard
- [x] Hapus aktivitas terakhir dari dashboard
- [x] Aksi cepat jadi 3 tombol: Presensi, Kegiatan, Info
- [x] Hubungkan Presensi -> `PresensiPage`
- [x] Hubungkan Kegiatan -> `KegiatanPage` (dengan `KegiatanProvider`)
- [x] Hubungkan Info -> dialog informasi aplikasi
- [x] Bonus: bottom nav "Riwayat Presensi" -> `RiwayatPresensiPage`
- [x] Klik kartu berita -> halaman baca berita lengkap (`NewsDetailPage`)
- [x] Endpoint `/api/news/categories` (kategori + jumlah berita)
- [x] Paginasi `/api/news?page=&per_page=&category=&q=`
- [x] Halaman daftar seluruh berita (`NewsListPage`) + pencarian + filter kategori
- [x] Tampilkan SEMUA berita dalam satu request (`per_page`/`limit` maksimum 200)
- [x] Tombol "Lihat Semua" di slideshow dihapus (cukup lewat aksi cepat Berita)
- [x] Aksi cepat "Info" diganti "Berita" (icon `newspaper_rounded`) -> `NewsListPage`

## Data Flow
```
GET /api/news?limit=5
  -> NewsController@index (news where status=published, order publish_date desc, limit 5)
  -> { id, title, slug, category, excerpt, image_url, publish_date }
  -> NewsModel (Flutter)
  -> NewsSlideshow (PageView + autoplay 5 detik)
  -> tap card -> NewsDetailPage
       -> GET /api/news/{slug} -> NewsModel (dengan content HTML)
       -> flutter_html merender isi lengkap berita

"Lihat Semua" tidak lagi ada di slideshow. Akses daftar berita lewat aksi cepat "Berita"
  -> NewsListPage
  -> GET /api/news?page=1&per_page=200&limit=200
       -> meta { current_page, last_page, total } (seluruh berita sekaligus)
       -> bila total > 200 -> scroll ke bawah memuat halaman berikutnya
  -> GET /api/news/categories -> chip filter kategori
  -> GET /api/news?...&category=&q= -> filter & pencarian
  -> tap item -> NewsDetailPage
```

## Files yang Dimodifikasi
| File | Perubahan |
|------|-----------|
| `routes/api.php` | Tambah route public `/news`, `/news/categories` & `/news/{slug}` |
| `lib/core/services/api_service.dart` | Import `news_model.dart`, tambah `getLatestNews()`, `getNewsList()`, `getNewsCategories()` & `getNewsDetail()` |
| `lib/core/models/news_model.dart` | Tambah field `content`, getter `hasCover`, serta model `NewsPage` (paginasi) & `NewsCategory` |
| `lib/features/berita/news_slideshow.dart` | Tap kartu -> buka `NewsDetailPage`; tombol "Lihat Semua" dihapus |
| `lib/features/dashboard/dashboard_content.dart` | Hapus stats & aktivitas, tambah `NewsSlideshow`, aksi cepat: Presensi, Kegiatan, **Berita** |
| `lib/features/dashboard/dashboard_page.dart` | Perubahan sama (legacy dashboard, tetap dipakai dari `LayananPage`) |
| `pubspec.yaml` | Tambah dependency `flutter_html` (render HTML berita) + pin `html: 0.15.6` |

## Files Baru
| File | Purpose |
|------|---------|
| `app/Http/Controllers/Api/NewsController.php` | API berita (index list/paginasi, categories, show) |
| `lib/core/models/news_model.dart` | Model berita + `NewsPage` + `NewsCategory` |
| `lib/features/berita/news_slideshow.dart` | Slideshow berita dashboard |
| `lib/features/berita/news_detail_page.dart` | Halaman baca berita lengkap (render HTML, cover, meta, footer) |
| `lib/features/berita/news_list_page.dart` | Halaman daftar seluruh berita (pencarian, filter kategori, infinite scroll) |

## Catatan
- Endpoint `/api/news` bersifat public (tanpa auth) karena konten berita memang publik.
- `image_url` dibentuk di backend via `asset('storage/'.$image)` sehingga app tidak perlu
  menyusun URL storage sendiri.
- Dashboard utama aplikasi adalah `DashboardContent` (dipakai `MainShell`), sedangkan
  `DashboardPage` adalah versi legacy yang masih diakses dari bottom nav `LayananPage`.
- **Penting:** `flutter_html 3.0.0` masih memakai `qs.matches()` dari internal `html`
  package. Fungsi itu dihapus di `html 0.15.7`, sehingga build gagal dengan
  `Error: Method not found: 'matches'`. Karena itu `html` dikunci ke `0.15.6`.
  Jangan upgrade `html` ke 0.15.7+ sebelum `flutter_html` dirilis ulang.

## TODO
- [ ] Opsional: cache berita (offline-first) menggunakan `sqflite`

## Changelog
### 2026-11-05
- Implementasi endpoint API berita dan redesign dashboard (slideshow berita + 3 aksi cepat).
- Klik kartu berita membuka `NewsDetailPage` sehingga pengguna dapat membaca berita lengkap
  (render HTML via `flutter_html`, cover, meta, dan footer institusi).
- Fix build error `Method not found: 'matches'` dengan mengunci dependency `html` ke `0.15.6`.
- Tambah halaman daftar seluruh berita (`NewsListPage`): pencarian judul, filter kategori,
  dan infinite scroll. Didukung endpoint `GET /api/news/categories` dan mode paginasi
  pada `GET /api/news` (`page`, `per_page`, `category`, `q`).
- Daftar berita memuat semua berita sekaligus (`per_page`/`limit` maksimal 200). Untuk itu
  batas `limit` di `/api/news` dinaikkan dari 20 menjadi 200.
- Tombol "Lihat Semua" pada slideshow dihapus; aksi cepat "Info" diganti "Berita"
  (icon `newspaper_rounded`) yang membuka `NewsListPage`.
