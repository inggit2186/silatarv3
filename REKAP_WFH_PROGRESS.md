# Progress Rekap WFH (Work From Home)

## Overview
Menambahkan tombol "Rekap WFH" di halaman Laporan Kinerja (tab Harian) yang menghasilkan PDF rekap kinerja khusus hari Jumat saja pada bulan terpilih, dengan judul "Laporan Kinerja Work From Home (WFH)".

## Status: SELESAI

## Checklist
- [x] Tambah route `laporan-kinerja.rekap-wfh`
- [x] Tambah controller method `rekapLaporanKinerjaWfh()`
- [x] Filter kegiatan hanya hari Jumat (DAYOFWEEK = 6 di MySQL)
- [x] Update PDF template agar menerima judul dinamis (`$reportTitle`)
- [x] Tambah tombol "Rekap WFH" di view, di samping tombol "Rekap"
- [x] Verifikasi syntax PHP & route terdaftar

## Data Flow
```
User klik "Rekap WFH"
  → GET /laporan-kinerja/rekap-wfh?tab=harian&month=YYYY-MM
  → PageController@rekapLaporanKinerjaWfh
      → Query satker_kegiatan WHERE DAYOFWEEK(tanggal) = 6 (Jumat only)
      → Group by tanggal → dailyGroups (hanya hari Jumat)
      → Resolve signature (PLT/custom supervisor/atasan)
      → Generate PDF via pdf.laporan-kinerja-harian (reportTitle="Laporan Kinerja Work From Home (WFH)")
      → Save ke storage/app/public/satker_ckh/{user_id}/{user_id}.wfh-MM-YYYY.pdf
      → Return inline PDF
```

## Files yang Dimodifikasi
| File | Perubahan |
|------|-----------|
| `routes/web.php` | Tambah route `GET /laporan-kinerja/rekap-wfh` → `laporan-kinerja.rekap-wfh` |
| `app/Http/Controllers/PageController.php` | Tambah method `rekapLaporanKinerjaWfh()` (duplikasi `rekapLaporanKinerja` dengan filter Jumat) |
| `resources/views/pdf/laporan-kinerja-harian.blade.php` | Judul pakai `{{ $reportTitle ?? 'Laporan Capaian Kinerja Harian' }}` |
| `resources/views/laporan-kinerja.blade.php` | Tambah anchor "Rekap WFH" di samping tombol "Rekap" |

## Files Baru
(tidak ada)

## Perbedaan dengan `rekapLaporanKinerja()`
| Aspek | Rekap (reguler) | Rekap WFH |
|-------|-----------------|-----------|
| Filter tanggal | Semua hari | Hanya hari Jumat (`DAYOFWEEK = 6`) |
| Judul PDF | "Laporan Capaian Kinerja Harian" | "Laporan Kinerja Work From Home (WFH)" |
| Pattern filename | `{user.id}.kinerja-MM-YYYY.pdf` | `{user.id}.wfh-MM-YYYY.pdf` |
| Insert `satker_ckh` | Ya (`item_id=1`, status DIKIRIM) | **Tidak** — hanya print & simpan file |
| Route | `laporan-kinerja.rekap` | `laporan-kinerja.rekap-wfh` |

## Catatan Teknis
- **Filter Jumat**: Menggunakan `DAYOFWEEK(tanggal) = 6` di MySQL (Sun=1, Mon=2, ..., Fri=6, Sat=7).
- **Tidak insert ke `satker_ckh`**: WFH adalah sub-aggregat dari laporan harian, bukan submission bulanan terpisah. Jika di-insert dengan `item_id=2`, akan muncul duplikat di tab Bulanan (karena query bulanan tidak filter `item_id`).
- **Signature logic**: Tetap menggunakan signature resolution yang sama (custom supervisor > atasan > PLT > kepala unit) agar konsisten.
- **Filename suffix `wfh`**: Mencegah overwrite file rekap reguler untuk user & bulan yang sama.

## TODO
- [ ] (Opsional) Tambahkan WFH records ke `satker_ckh` dengan `item_id=2` dan filter bulanan tab → `item_id=1` jika di kemudian hari ingin tracking WFH sebagai submission terpisah.

## Changelog
### 2026-09-27
- Tambah tombol "Rekap WFH" di halaman Laporan Kinerja (tab Harian)
- Filter kegiatan hanya hari Jumat via `DAYOFWEEK`
- PDF menggunakan judul "Laporan Kinerja Work From Home (WFH)"
- File output disimpan sebagai `{user.id}.wfh-MM-YYYY.pdf`
