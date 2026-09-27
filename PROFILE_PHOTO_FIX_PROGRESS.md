# Progress: Profile Photo Upload Fix

## Overview
Memperbaiki error upload foto profil pada `/profil/edit` (`League\\Flysystem\\UnableToCreateDirectory`) dan memastikan foto diproses ringan sebelum disimpan ke server.

## Status: DALAM PROGRES

## Diagnosis
- Foto profil aplikasi dibaca dari URL `storage/users_berkas/{nomor_induk}/{pp}`.
- Lokasi kanonisnya adalah disk `public`, yaitu `storage/app/public`, yang diakses melalui symlink `public/storage`.
- Alur lama mencampur `DIRECTORY_SEPARATOR` dengan path Flysystem dan melakukan pembuatan direktori manual. Pada Windows hal ini memicu kegagalan pembuatan direktori nested.
- Paket npm `sharp` merupakan modul Node dan tidak tepat dimuat sebagai global browser melalui CDN. Kompresi final dilakukan server-side dengan `Intervention Image`, yang sudah tersedia dan dipakai aplikasi.

## Checklist
- [x] Identifikasi lokasi display foto profil.
- [x] Identifikasi konflik disk `public` dan disk `users_berkas`.
- [x] Gunakan path relatif forward-slash pada disk `public`.
- [x] Hilangkan pembuatan direktori manual yang memicu error Flysystem.
- [x] Kompresi server-side dengan Intervention Image, maksimal 512x512 PNG.
- [x] Crop client-side tetap dipakai untuk mengurangi ukuran payload.
- [x] Hapus pemanggilan Sharp browser yang tidak kompatibel.
- [x] Pertahankan foto lama jika proses foto baru gagal.
- [x] Simpan NPWP ke `tenaga_ktd.npwp`, bukan `users.npwp` (kolom `users.npwp` sudah dihapus).
- [x] Tambahkan kolom `bank` ke `tenaga_ktd` dan simpan input bank ke kolom tersebut.
- [x] Pisahkan update tabel `users` dan `tenaga_ktd` agar kolom pegawai tidak dikirim ke `users`.
- [x] Selaraskan nama input form: `hp`, `no_rekening`, dan `bank`.
- [ ] Jalankan test suite dan formatter.
- [ ] Uji upload aktual pada Windows.
- [ ] Verifikasi URL foto dari halaman profil, header, admin, dan presensi.

## Data Flow

Input file -> Crop canvas 800x800 di browser -> multipart upload -> validasi Laravel -> Intervention Image scaleDown 512x512 -> `Storage::disk('public')->put('users_berkas/{nomor_induk}/{nomor_induk}.pp.png')` -> update `users.pp` -> URL `storage/users_berkas/{nomor_induk}/{pp}`

## Files yang Dimodifikasi

| File | Perubahan |
|------|-----------|
| `app/Http/Controllers/PageController.php` | Menyimpan foto pada disk `public` dengan path relatif, mengompres melalui Intervention, dan menghapus foto lama hanya setelah penyimpanan sukses. |
| `resources/views/profil-edit.blade.php` | Menghapus Sharp CDN dan penggunaan Sharp/Buffer; crop canvas tetap menjadi optimasi client-side. |

## Files Baru

Tidak ada.

## TODO
- [ ] Tambahkan test feature upload foto jika konfigurasi database test tersedia.
- [ ] Jalankan `composer pint` dan `php artisan test`.
- [ ] Uji manual upload foto di environment Windows.

## Changelog

### 2026-09-27
- Diperbaiki disk dan path penyimpanan foto profil agar konsisten dengan URL aplikasi.
- Kompresi final dipindahkan ke Intervention Image server-side.
- Sharp browser/CDN dihapus karena bukan runtime browser yang kompatibel.
