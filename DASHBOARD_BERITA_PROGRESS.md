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

## Data Flow
```
GET /api/news?limit=5
  -> NewsController@index (news where status=published, order publish_date desc, limit 5)
  -> { id, title, slug, category, excerpt, image_url, publish_date }
  -> NewsModel (Flutter)
  -> NewsSlideshow (PageView + autoplay 5 detik)
  -> tap card -> showNewsDetailSheet (cover, judul, ringkasan)
```

## Files yang Dimodifikasi
| File | Perubahan |
|------|-----------|
| `routes/api.php` | Tambah route public `/news` & `/news/{slug}` |
| `lib/core/services/api_service.dart` | Import `news_model.dart`, tambah `getLatestNews()` |
| `lib/features/dashboard/dashboard_content.dart` | Hapus stats & aktivitas, tambah `NewsSlideshow`, aksi cepat 3 tombol terhubung |
| `lib/features/dashboard/dashboard_page.dart` | Perubahan sama (legacy dashboard, tetap dipakai dari `LayananPage`) |

## Files Baru
| File | Purpose |
|------|---------|
| `app/Http/Controllers/Api/NewsController.php` | API berita (index + show) |
| `lib/core/models/news_model.dart` | Model berita |
| `lib/features/berita/news_slideshow.dart` | Slideshow berita dashboard |
| `lib/features/berita/news_detail_sheet.dart` | Bottom sheet ringkasan berita |

## Catatan
- Endpoint `/api/news` bersifat public (tanpa auth) karena konten berita memang publik.
- `image_url` dibentuk di backend via `asset('storage/'.$image)` sehingga app tidak perlu
  menyusun URL storage sendiri.
- Dashboard utama aplikasi adalah `DashboardContent` (dipakai `MainShell`), sedangkan
  `DashboardPage` adalah versi legacy yang masih diakses dari bottom nav `LayananPage`.

## TODO
- [ ] Opsional: tambahkan halaman daftar berita penuh + halaman detail berita di app
- [ ] Opsional: cache berita (offline-first) menggunakan `sqflite`

## Changelog
### 2026-11-05
- Implementasi endpoint API berita dan redesign dashboard (slideshow berita + 3 aksi cepat).
