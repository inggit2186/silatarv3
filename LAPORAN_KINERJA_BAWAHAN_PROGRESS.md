# Progress Laporan Kinerja Bawahan

## Overview
Perbaikan halaman Laporan Bawahan untuk menampilkan loading saat verifikasi laporan dan mengamankan preview PDF agar nama user dengan simbol seperti apostrof tetap aman.

## Status: DALAM PROGRES

## Checklist
- [x] Telusuri alur Laporan Bawahan dan sumber error PDF
- [x] Tambahkan state loading saat tombol Tolak/Setujui diproses
- [x] Amankan payload PDF agar tidak pecah saat nama mengandung simbol
- [ ] Validasi manual di browser untuk preview PDF dan tombol verifikasi

## Data Flow
```
Klik PDF/Verifikasi -> Alpine component -> request ke route verifikasi / preview -> response -> modal/loading state
```

## Files yang Dimodifikasi
| File | Perubahan |
|------|-----------|
| resources/views/laporan-kinerja-bawahan.blade.php | Ubah payload PDF ke object aman, tambah state loading/disabled/spinner untuk Tolak & Setujui |

## TODO
- [ ] Jalankan validasi browser manual pada halaman Laporan Bawahan
- [ ] Pastikan preview PDF untuk nama dengan apostrof tetap terbuka
- [ ] Pastikan loading tombol verifikasi tampil dan tombol terkunci selama proses

## Changelog
### 2026-08-11
- Menambahkan loading visual saat verifikasi laporan bawahan.
- Mengganti pemanggilan preview PDF ke payload terenkode aman agar nama seperti `NUR'AINA` tidak memutus JavaScript.
- Menambahkan filter `role` untuk daftar bawahan agar user `pindah` dan `pensiun` tidak ikut muncul.
- Membuat verifikasi laporan tetap sukses walau regenerasi PDF gagal, lalu menampilkan toast sukses/gagal sebelum reload.
- Menambahkan catatan progress perubahan ini.
