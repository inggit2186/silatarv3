# Progress Waktu Pelaksanaan (Opsional)

## Overview

Menambahkan isian **Waktu Pelaksanaan** pada setiap baris kegiatan di halaman `/laporan-kinerja`
(tab Harian). Isian terdiri dari dua field opsional:

- **Jam Mulai** — input waktu (format `H:i`)
- **Durasi (menit)** — input angka (0–1440 menit)

Tujuan: atasan/pembina dapat melihat kapan kegiatan dilaksanakan dan berapa lama durasinya
pada rekap, PDF, dan aplikasi mobile.

**Batasan utama:** penambahan field ini **tidak boleh mengecilkan kolom existing** (Kegiatan /
Volume / Jenis) pada modal Tambah maupun Edit, di layar PC maupun mobile.

## Status: SELESAI (menunggu verifikasi manual user)

## Checklist

- [x] Helper format tunggal `App\Support\WaktuPelaksanaan`
- [x] Penyimpanan di `satker_kegiatan.data_json` (kunci baru `jm` & `dm`, tanpa migrasi DB)
- [x] Validasi `items.*.jam_mulai` & `items.*.durasi_menit` di PageController
- [x] `sanitizeActivityRows()` membawa key baru
- [x] `storeLaporanKinerja()` & `updateLaporanKinerjaByDate()` menulis `jm`/`dm`
- [x] `duplicateKegiatanToAtasan()` meneruskan `jm`/`dm` ke atasan
- [x] Mapper read: 6 titik di PageController + AutoRekapCKH + Api\KegiatanController
- [x] Form modal Tambah & Edit (field Jam Mulai + Durasi)
- [x] Badge waktu pada tabel harian
- [x] Badge waktu pada PDF laporan harian
- [x] Alpine `reportKinerjaPage` (rows/editRows/addRow/removeRow/openEditModal)
- [x] CSS grid placement (kolom existing tidak berubah)
- [x] API `store`/`updateByDate` menerima & menyimpan `jm`/`dm`
- [x] `./vendor/bin/pint` (formatting)
- [x] `npm run build` (Vite — CSS & JS ter-compile)
- [x] `php artisan view:cache` (semua Blade template compile)
- [ ] Verifikasi manual di browser
- [x] Side Flutter (repo `../flutterSILATAR`) memakai field baru

## Data Flow

```
Form (Jam Mulai + Durasi, opsional)
  → POST /laporan-kinerja/harian  (atau PUT /laporan-kinerja/day)
  → Validator: items.*.jam_mulai (H:i), items.*.durasi_menit (0–1440)
  → sanitizeActivityRows()  (normalisasi + carry key baru)
  → satker_kegiatan.data_json = {"items":[{...,"jm":"08:00","dm":90}]}
  → (opsional) duplikasi ke atasan, jm/dm ikut tersalin
  → read: PageController / Api\KegiatanController memetakan ke
          jam_mulai, durasi_menit, waktu_label
  → tampil: badge di tabel harian, PDF laporan harian, JSON API
```

### Kontrak data

| Kunci JSON | Arti | Tipe | Wajib |
|------------|------|------|-------|
| `k` | Nama kegiatan | string | ya |
| `v` | Volume | int | tidak |
| `s` | Jenis/satuan | string | ya |
| `jm` | Jam mulai (`H:i`) | string\|null | tidak |
| `dm` | Durasi (menit) | int\|null | tidak |

Baris lama (tanpa `jm`/`dm`) tetap valid — dibaca dengan `?? null` dan tidak menampilkan badge
waktu.

## Keputusan Layout

Grid baris item memakai `.silatar-report-create-grid` yang punya **tiga definisi bertumpuk** di
`resources/css/app.css`:

| Baris | Isi | Layer |
|-------|-----|-------|
| 665 | `@apply grid gap-3 lg:grid-cols-[minmax(0,1fr)_120px_220px_56px]` | `@layer components` (kalah) |
| 1835 | `grid-template-columns: 4fr 1fr 1fr auto` | unlayered |
| **2108** | **`grid-template-columns: 2fr 1fr 1fr auto`** | unlayered (menang) |
| 2119 | `@media (max-width: 768px)` → `1fr` | container |
| 2657 | `@media (max-width: 640px)` → `1fr !important` | container |

Karena ada blok `!important` pada `grid-template-columns`, **`grid-template-areas` tidak dipakai**
(area menjadi invalid bila jumlah kolom tidak cocok). Solusinya *grid placement eksplisit*:

```
.neo-mirai .silatar-report-create-grid--item > .sr-item-kegiatan { grid-column: 1; grid-row: 1; }
.neo-mirai .silatar-report-create-grid--item > .sr-item-volume   { grid-column: 2; grid-row: 1; }
.neo-mirai .silatar-report-create-grid--item > .sr-item-jenis     { grid-column: 3; grid-row: 1; }
.neo-mirai .silatar-report-create-grid--item > .sr-item-waktu     { grid-column: 1 / 4; grid-row: 2; }
.neo-mirai .silatar-report-create-grid--item > .sr-item-aksi      { grid-column: 4; grid-row: 1 / 3; align-self: center; }
```

Field waktu diletakkan pada **baris kedua** yang menyeleksi kolom 1–3, sehingga lebar kolom
Kegiatan / Volume / Jenis **tidak berubah sama sekali**. Di mobile (`≤768px`) semua anak di-reset
ke `grid-column: auto; grid-row: auto;` dan kembali menumpuk mengikuti urutan DOM:

**Kegiatan → Volume → Jenis → Waktu → 🗑**

Grid di dalam sel waktu memakai `repeat(auto-fit, minmax(150px, 1fr))` sehingga otomatis menjadi
satu kolom di layar sempit tanpa media query tambahan.

## Files yang Dimodifikasi

| File | Perubahan |
|------|-----------|
| `app/Http/Controllers/PageController.php` | Validasi, `sanitizeActivityRows()`, tulis `jm`/`dm`, 6 mapper read |
| `app/Http/Controllers/Api/KegiatanController.php` | Validasi API, `normalizeWaktu()`, mapping `index()` & `downloadPdf()` |
| `app/Console/Commands/AutoRekapCKH.php` | Mapping item untuk PDF auto-rekap |
| `resources/views/laporan-kinerja.blade.php` | Field form (Tambah + Edit), badge waktu di tabel harian |
| `resources/views/components/jam-input.blade.php` | Komponen input jam 24 jam + picker Jam/Menit (dipakai di modal Tambah & Edit) |
| `resources/views/pdf/laporan-kinerja-harian.blade.php` | Baris waktu pada item kegiatan + style `.activity-time` |
| `resources/js/app.js` | Alpine `reportKinerjaPage`: `jam_mulai` & `durasi_menit`, masking jam, picker |
| `resources/css/app.css` | Grid placement `.sr-item-*` + `.sr-item-waktu-grid` + style `.sr-jam-*` |

## Files Baru

| File | Purpose |
|------|---------|
| `app/Support/WaktuPelaksanaan.php` | Helper statis `label()` untuk format waktu konsisten |
| `resources/views/components/jam-input.blade.php` | Komponen input Jam Mulai (masker 24 jam + picker Jam/Menit) |
| `WAKTU_PELAKSANAAN_PROGRESS.md` | Progress tracking feature ini |

### Helper `WaktuPelaksanaan::label()`

| Masukan | Keluaran |
|---------|----------|
| jam `08:00`, durasi `90` | `08:00-09:30 (90 menit)` |
| jam `08:00`, durasi `null` | `08:00` |
| jam `null`, durasi `90` | `90 menit` |
| jam `null`, durasi `null` | `null` (tidak ditampilkan) |

## Catatan

- `updateLaporanKinerja()` (PageController) hanya menulis kolom legacy dan **tidak** menyentuh
  `data_json` — jalur lama, tidak diubah.
- `ConvertSatkerActivitiesToJson` / `ImportSatkerKegiatanSupplement` tidak punya sumber data
  waktu → tidak diubah.
- View `laporan-kinerja-bawahan.blade.php` hanya menampilkan tabel laporan bulanan (bukan daftar
  item kegiatan) → tidak ada tempat menampilkan waktu.
- `pdf/supervisor-input.blade.php` merupakan pratinjau form input → tidak diubah.
- Tidak ada migrasi database: field baru disimpan di dalam JSON `data_json` yang sudah ada.
- `php artisan test` gagal **sebelum** perubahan ini juga: environment PHP lokal tidak punya
  driver `pdo_sqlite` (`could not find driver`), sehingga seluruh test yang menyentuh database
  error. Tidak ada test yang meng-cover `laporan-kinerja` / `satker_kegiatan` — verifikasi
  dilakukan manual oleh user.

## TODO

- [ ] Verifikasi manual di browser (desktop & mobile) — user menguji sendiri
- [x] Side Flutter: tampilkan `waktu_label` dari `GET /api/laporan-kinerja`
      (selesai 2026-11-05 — lihat bagian **Sinkronisasi Flutter**)
- [x] Agregasi total durasi per hari & per bulan pada rekap + respons API
- [ ] Pertimbangkan tampilan diagram distribusi waktu (follow-up, belum diminta)

## Sinkronisasi Flutter (`d:\silatar_v2`)

Semua rekomendasi paritas fitur telah dikerjakan di aplikasi Flutter:

| # | Item | Perubahan |
|---|------|-----------|
| 1 | Waktu tampil di tab Rekap | `kegiatan_bulanan_tab.dart` — setiap kartu "Aktivitas Terakhir" menampilkan badge total durasi hari + daftar kegiatan ber-waktu beserta `waktuLabel`; kartu statistik mendapat baris **Total Durasi** |
| 2 | Urutan kronologis per hari | `KegiatanItem.urutanMenit` + sort di `KegiatanHarian.fromJson`; backend `Api\KegiatanController::index()` & `downloadPdf()` juga meng-`usort` item per tanggal (ber-jam dulu) |
| 3 | Input jam manual + picker | `_JamMaskFormatter` pada field Jam Mulai (masker `HH:MM`, auto-koreksi 00-23 / 00-59) + tombol picker tetap tersedia; `_lengkapiJam()` menormalkan nilai tidak lengkap saat simpan |
| 4 | `copyWith` bisa mengosongkan waktu | parameter `clearJamMulai` / `clearDurasiMenit` di `KegiatanItem.copyWith` |
| 5 | Penanda "Tidak tercatat" | `kegiatan_harian_item.dart` — bila hari punya minimal satu kegiatan ber-jam, kegiatan tanpa jam diberi label *italic* "Tidak tercatat" (+ durasi bila ada) |
| 6 | Endpoint per-item yang benar | Backend baru `PUT/DELETE /api/laporan-kinerja/item` (berbasis `tanggal` + `id` item di `data_json`); service Flutter `updateKegiatanItem()` / `deleteKegiatanItem()` disesuaikan |
| 7 | Agregasi total durasi | `KegiatanHarian.totalDurasiMenit/totalDurasiLabel`, `KegiatanRekap.totalDurasiMenit/totalDurasiLabel`; API `summary.total_durasi_menit` & `rekap.total_durasi_menit` |

### File Flutter yang Dimodifikasi

| File | Perubahan |
|------|-----------|
| `lib/core/models/kegiatan_model.dart` | `copyWith` + clear flag, `urutanMenit`, `menitDariJam()`, `formatDurasi()`, sort & total durasi di `KegiatanHarian`, `totalDurasiMenit` di `KegiatanRekap` |
| `lib/core/services/kegiatan_service.dart` | `updateKegiatanItem` / `deleteKegiatanItem` memakai `/laporan-kinerja/item` |
| `lib/core/services/api_config.dart` | Konstanta `kegiatanItem` |
| `lib/core/providers/kegiatan_provider.dart` | `_calculateRekap()` menghitung total durasi |
| `lib/features/kegiatan/widgets/kegiatan_form_modal.dart` | `_JamMaskFormatter`, `_lengkapiJam()`, helper durasi, input jam manual + picker, `id` item dipertahankan |
| `lib/features/kegiatan/widgets/kegiatan_harian_item.dart` | Badge total durasi di header tanggal, label "Tidak tercatat" |
| `lib/features/kegiatan/widgets/kegiatan_harian_tab.dart` | Stat card Durasi (jika ada) |
| `lib/features/kegiatan/widgets/kegiatan_bulanan_tab.dart` | Total Durasi di statistik + rincian kegiatan ber-waktu per kartu |

### File Backend yang Dimodifikasi (tambahan)

| File | Perubahan |
|------|-----------|
| `app/Http/Controllers/Api/KegiatanController.php` | `total_durasi_menit` di `index()`/`rekap()`, `total_durasi_menit` per hari, `usort` kronologis, endpoint `updateItem()` & `deleteItem()` |
| `app/Support/WaktuPelaksanaan.php` | Helper `menit()` & `durasiLabel()` |
| `routes/api.php` | Rute `PUT/DELETE /laporan-kinerja/item` |

### Changelog Flutter

### 2026-11-05
- Paritas fitur waktu pelaksanaan di aplikasi Flutter (semua poin rekomendasi)
- Backend: agregasi total durasi bulanan, pengurutan kronologis, endpoint per-item

## Changelog
### 2026-11-05 (perubahan kesembilan — fix off-by-one jam picker)
- Bug: klik chip "Pilih Cepat" (mis. 07:00) membuat jam/minit yang tampil bergeser +1
  (menjadi 08:01). Penyebabnya posisi scroll dihitung dari atas padding
  (`pad + index * tinggi item`), sedangkan item memakai `scroll-snap-align: center`
  sehingga posisi snap sebenarnya `index * tinggi item` — index yang dibaca
  `onJamRoll` jadi meleset satu tingkat
- Perhitungan posisi scroll dan pembacaan index diseragamkan ke basis pita tengah:
  `scrollTop = index * tinggi item` dan `index = round(scrollTop / tinggi item)`
- `lockJamRoll()` menerima durasi; sinkronisasi awal picker dikunci 300 ms
- `setJamCepat()` mengunci nilai akhir (jam + menit) 750 ms setelah animasi scroll,
  supaya nilai tidak berubah bila ada event scroll yang menyusul

### 2026-11-05 (perubahan kedelapan — urutan kegiatan & label waktu kosong)
- Kegiatan dalam satu tanggal diurutkan ulang pada PDF: yang punya Jam Mulai tampil lebih dulu
  (kronologis), kegiatan tanpa waktu diletakkan di akhir baris hari tersebut
- Sel Waktu tanpa jam mulai kini menampilkan label *italic* "Tidak tercatat" (abu-abu)
  alih-alih tanda `-`; bila durasi tetap ada ditampilkan di bawahnya, mis. "(45 Menit)"

### 2026-11-05 (perubahan ketujuh — bug kolom saat halaman terputus + bullet)
- **Bug fix:** `rowspan` pada sel Tanggal dihapus. Dompdf menggeser isi baris ke kolom yang salah
  saat tabel terputus antar halaman (isi kegiatan muncul di kolom Waktu/Realisasi). Sekarang
  sel Tanggal dibuat per baris: baris pertama memuat kartu tanggal, baris berikutnya sel kosong
  dengan latar sama (`date-cell-empty`)
- Pemisah baris dibuat lebih jelas: garis tipis `#cbd5e1` antar kegiatan, garis `#94a3b8`
  (lebih tebal) sebagai pembatas antar tanggal (kelas `group-start`)
- Setiap kegiatan di kolom Detail Kegiatan diberi bullet titik biru (dibuat dengan CSS,
  bukan karakter, agar font subset Dompdf tidak gagal merender glif)

### 2026-11-05 (perubahan keenam — kolom waktu & spasi baris PDF rekap)
- Kolom **Waktu** di PDF rekap dipecah dua baris: jam di atas (`07:00 - 07:30 WIB`), durasi di
  bawah (`(30 Menit)`), memakai helper baru `App\Support\WaktuPelaksanaan::bagian()`
- Baris kegiatan diberi garis tipis pemisah (`#dbe3ee`) dan padding vertikal diperkecil
  (7px) supaya antar kegiatan tidak terlihat berdempet
- Helper `WaktuPelaksanaan::bagian()` mengembalikan `['jam' => ?string, 'durasi' => ?string]`

### 2026-11-05 (perubahan kelima — desain tabel PDF rekap)
- Tabel PDF `pdf/laporan-kinerja-harian` diubah dari 2 kolom (Tanggal | Detail Kegiatan) menjadi
  4 kolom: **Tanggal | Waktu | Detail Kegiatan | Realisasi**
- Satu baris per kegiatan (sebelumnya semua kegiatan digabung dalam satu sel per tanggal);
  sel Tanggal memakai `rowspan` sehingga tanggal tampil satu kali untuk semua kegiatan hari itu
- Kolom **Waktu** menampilkan `waktu_label` (kosong → `-`), kolom **Realisasi** menampilkan
  volume + satuan (mis. `3 Kegiatan`, `4 data`; volume 0 → `-`)
- `Api\KegiatanController::downloadPdf()` sekarang menyertakan key `meta` (sebelumnya tidak ada,
  sehingga kolom Realisasi kosong pada PDF dari endpoint API)
- Tabel di layar `/laporan-kinerja` (tab Harian) sengaja tidak diubah

### 2026-11-05 (perubahan keempat — picker jam dipoles)
- Panel picker diberi display jam besar di header (`14:37` + badge WIB) yang ikut berubah realtime
- Tombol **▲/▼** per kolom (Jam ±1, Menit ±5) untuk penyesuaian cepat tanpa menggulir
- Baris **Pilih Cepat** berisi chip jam umum (06:30 … 17:00) — chip aktif tersorot
- Tombol aksi dirapikan: **Selesai** (gold, primer) / **Sekarang** / **Hapus**
- Panel pakai animasi masuk/keluar (fade + slide), shadow lembut, radius lebih besar
- `lockJamRoll()` menahan event scroll saat scroll programatis (chip/▲▼) agar nilai tidak
  berkedip di tengah animasi
- Verifikasi: `npm run build`, `php artisan view:cache`, render komponen via tinker

### 2026-11-05 (perubahan ketiga — picker jam rolling)
- Jam Mulai kini bisa diisi dengan **memilih** lewat tombol jam: panel **rolling** (scroll snap)
  dua kolom — Jam `00`–`23` dan Menit `00`–`59` — gaya ala picker jam iOS
- Nilai di input ikut berubah realtime saat digulir; item yang berada di pita tengah disorot
  (`is-active`), list diberi gradien fade di atas & bawah
- Tombol "Sekarang" (jam lokal) dan "Hapus" tetap tersedia
- Komponen baru `resources/views/components/jam-input.blade.php` dipakai di modal Tambah & Edit
  agar markup tidak duplikat
- State per baris di Alpine `reportKinerjaPage`: `row.jamOpen`, `row.jamHour`, `row.jamMinute`,
  `row.jamSynced` (`toggleJamPicker`, `syncJamRoll`, `onJamRoll`, `setJamSekarang`, `clearJam`)
- Menit tidak lagi dibatasi kelipatan 5 — seluruh 60 menit bisa dipilih
- Style baru `.sr-jam-roll*` di `resources/css/app.css`
- Verifikasi: `npm run build`, `php artisan view:cache`, render komponen via tinker

### 2026-11-05 (perubahan kedua — format 24 jam)
- Input Jam Mulai tidak lagi memakai `<input type="time">` (format tampilan ikut locale browser,
  bisa AM/PM). Diganti input teks ber-masker `HH:MM` 24 jam dengan validasi `pattern`
  `([01][0-9]|2[0-3]):[0-5][0-9]` (submit diblokir browser bila tidak valid)
- Input 12 jam (mis. `2:30 PM`) otomatis dikonversi ke 24 jam oleh Alpine
  (`formatJamInput`/`formatJamFinal`/`convert12to24` pada `reportKinerjaPage`)
- Badge/info waktu server-side dinormalisasi ke 24 jam oleh
  `App\Support\WaktuPelaksanaan::normalisasiJam()` (menangani `H:i`, `H:i:s`, `H.i`, `g:i A`)
- Verifikasi: `npm run build`, `php artisan view:cache`, unit check helper label

### 2026-11-05
- Menambahkan field opsional Jam Mulai + Durasi (menit) pada baris kegiatan laporan kinerja
- Penyimpanan tanpa migrasi: kunci JSON `jm` & `dm` di `satker_kegiatan.data_json`
- Layout grid dua baris agar kolom existing tidak mengecil (PC & mobile)
- Badge waktu pada tabel harian, PDF laporan harian, dan JSON API
- Helper `App\Support\WaktuPelaksanaan` untuk format waktu konsisten antar controller
- Verifikasi: Pint, `npm run build`, `php artisan view:cache`, dan unit check helper label
