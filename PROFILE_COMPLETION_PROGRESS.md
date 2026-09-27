# Progress Profile Completion Enforcement

## Overview
Fitur untuk memaksa user (role pegawai/ASN) melengkapi data profil wajib saat login / auto-login. Jika salah satu dari 4 data wajib (Foto Profil, NIK 16 digit, Nomor KK 16 digit, Jabatan) belum lengkap, user akan diarahkan otomatis ke halaman edit profil.

## Status: SELESAI (perbaikan redirect foto diterapkan; menunggu testing)

## Checklist
- [x] Buat middleware `EnsureProfileComplete`
- [x] Registrasi middleware di `bootstrap/app.php`
- [x] Update view `profil-edit.blade.php` (banner, field baru, upload foto)
- [x] Update controller `PageController::updateProfil()` & `editProfil()`
- [x] Sync data ke `tenaga_ktd`
- [x] Cek ulang kelengkapan setelah save
- [x] Redirect ke intended URL / home setelah lengkap
- [ ] Testing manual: login user dengan data tidak lengkap

## Data Wajib & Mapping

| Data Wajib   | Kolom Utama          | Fallback              | Aturan          |
|--------------|----------------------|------------------------|-----------------|
| Foto Profil  | `users.pp`           | —                      | Tidak kosong    |
| NIK          | `users.nip`          | `tenaga_ktd.nik`       | 16 digit angka  |
| Nomor KK     | `tenaga_ktd.kk`      | —                      | 16 digit angka  |
| Jabatan      | `users.pekerjaan`    | `tenaga_ktd.pekerjaan` | Tidak kosong    |

## Scope Role
- **Berlaku**: `pegawai`, `petugas`, `kasi`, `kasubbag`, `kepala`
- **Dikecualikan**: `admin`, `superadmin`, `other`, `pensiun`, `pindah`, `frontdesk`

## Data Flow
```
User Login/Auto-Login
    ↓
Auth Middleware ✓
    ↓
EnsureProfileComplete Middleware
    ↓
Cek role → hanya pegawai/petugas/kasi/kasubbag/kepala
    ↓
Ambil data users + tenaga_ktd
    ↓
Cek 4 field wajib
    ↓
Lengkap → lanjut ke route tujuan
Kurang  → redirect ke /profil/edit + flash warning
```

## Files yang Dimodifikasi
| File | Perubahan |
|------|-----------|
| bootstrap/app.php | Register alias & append middleware ke web group |
| resources/views/profil-edit.blade.php | Banner alert, field wajib, upload foto, validasi 16 digit |
| app/Http/Controllers/PageController.php | Handle upload foto, validasi NIK/KK, sync tenaga_ktd |

## Files Baru
| File | Purpose |
|------|---------|
| app/Http/Middleware/EnsureProfileComplete.php | Middleware pengecek kelengkapan |
| PROFILE_COMPLETION_PROGRESS.md | Dokumentasi progress |

## Route yang Dikecualikan (Skip Check)
- `profil.edit`, `profil.update`
- `logout`
- `signature.get`, `signature.save`
- `impersonate.stop`
- `ubah-password`, `ubah-password.update`
- AJAX / JSON request
- Non-pegawai roles

## TODO
- [ ] Testing dengan user real
- [ ] Handle edge case: user upload foto tanpa isi field lain

## Changelog
### 2026-09-27
- Memperbaiki middleware agar user non-admin tanpa `users.pp`, NIK valid, atau KK valid diarahkan ke `/profil/edit`.
- NIK dan KK harus tepat 16 digit angka; jabatan tetap diwajibkan untuk role target.
- Mengecualikan role `superadmin` dan `admin` dari seluruh pemeriksaan kelengkapan profil.
- Memindahkan pengecekan setelah route exemption dan filter method agar tidak terjadi redirect loop atau intersep request update profil.

### 2026-09-XX
- Buat plan dan progress file
- Buat middleware `EnsureProfileComplete` dengan skip untuk AJAX/JSON, non-GET method, dan route yang dikecualikan
- Registrasi middleware di `bootstrap/app.php` dan append ke web group
- Tambah banner warning + section data wajib (Foto, NIK, KK, Jabatan) di form `profil-edit.blade.php`
- Client-side validation: input NIK/KK hanya angka, maksimal 16 digit
- Preview avatar sebelum upload
- Update `editProfil()` untuk pass `$tenagaKtd`
- Update `updateProfil()` untuk validasi field baru, upload foto, sync ke `tenaga_ktd`
- Setelah save, cek ulang kelengkapan → redirect ke intended URL jika lengkap, kembali ke edit jika masih kurang
- Verifikasi syntax semua file lulus (`php -l`)
- Clear cache Laravel (config, route, view)

### 2026-09-XX (Update: Foto Cropper)
- Tambah modal crop foto profil dengan **Cropper.js 1.6.2** (CDN cdnjs)
- Aspect ratio 1:1 (square) untuk foto profil
- Fitur: zoom in/out, rotate kiri/kanan, reset
- Output: JPEG 800x800 (quality 90%) untuk optimasi ukuran & konsistensi
- Preview real-time avatar setelah crop
- 2 input file: `pp_selector` (trigger cropper) & `pp` (hidden, dikirim ke server)
- Convert canvas → Blob → File → DataTransfer → assign ke `input[name="pp"]`
- Modal responsive mobile & dark mode support
- ESC key & klik overlay untuk close modal
- Reset selector setelah close agar bisa pilih file sama lagi
