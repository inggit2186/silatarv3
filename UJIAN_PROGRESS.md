# Progress Halaman Peserta Ujian (SILATAR V2)

> File ini adalah **catatan kerja hidup** untuk developer lain / AI lain. Isi dokumen ini menjelaskan
> **apa yang sudah jadi, apa yang belum, keputusan desain, dan langkah berikutnya** supaya pekerjaan
> bisa dilanjutkan tanpa membaca ulang seluruh plan.
>
> Plan lengkap (disetujui user): `C:\Users\Admin\.claude\plans\misty-waddling-pine.md`
> (salinan ringkas juga ada di bagian "Arsitektur" bawah).

## Status: DALAM PROGRES (implementasi kode selesai, verifikasi manual pending)

**Tanggal mulai:** 2026-10-28
**Update terakhir:** 2026-11-18 (sesi 9 — cover fit-to-cover, waktu ujian mulai saat tombol layar penuh ditekan, tombol kembali & modal layar penuh ringkas di mobile)

> **Ringkasan 2026-11-18 (sesi 9):** **Cover ujian** di daftar kini benar-benar memenuhi
> area cover (*fit to cover*). **Waktu ujian tidak lagi berjalan saat halaman dibuka** — sesi
> baru started setelah peserta menekan **"Mulai Ujian"** di modal layar penuh (endpoint baru
> `POST /ujian/{id}/mulai`); sebelum itu jawaban/lampiran/pelanggaran ditolak server
> (`409`). Modal layar penuh kini **hanya 2 tombol — "Kembali" & "Mulai Ujian"** (ujian wajib
> layar penuh, tombol "Lanjut Tanpa Layar Penuh" dihapus) dan di mobile tampil **lebih ringkas**
> (tombol kecil, kepala modal diperkecil) supaya area informasi tidak tergerus.
> Detail di section 3 (Fase 12), 5.2, 9, dan 11.

> **Ringkasan 2026-11-18 (sesi 8):** Halaman `/ujian` dibuat **responsif untuk mobile/Android** —
> bilah atas dua baris, palet soal jadi strip geser, navigasi dua kolom, modal lebih ringkas.
> **Bottom mobile nav disembunyikan** saat mengerjakan ujian (kelas `neo-ujian-mode` di `<body>`),
> dan **backdrop modal ujian dinaikkan ke `z-index: 200`** sehingga tombol **"Masuk Layar Penuh"**
> tidak lagi tertutup bottom nav. Ketiga modal ujian kini bisa digulir (badan scroll, kaki modal
> tetap terlihat). Detail di section 3 (Fase 11), 5.2, 9, dan 11.

> **Ringkasan 2026-11-18 (sesi 7):** Tiga tampilan dipercantik — modal **"Total Pelanggaran pada
> sesi ini"** (`.neo-ujian-alert-*`), modal **"Kumpulkan Jawaban?"** (`.neo-ujian-submit-*`),
> dan **halaman hasil** (`.neo-hasil-*`). Tombol "Kembali ke Ujian" kini mengembalikan peserta
> ke halaman ujian **dalam mode layar penuh**. Bagian **"Pembahasan"** di halaman hasil punya
> **expand/collapse satu tombol** (`.neo-hasil-reveal`) yang **tersembunyi secara default** untuk
> menampilkan/menyembunyikan seluruh daftar pembahasan. Detail di section 3 (Fase 10), 5.2, 5.5, 9, dan 11.

> **Ringkasan 2026-11-18 (sesi 5):** Halaman **`/ujian` didesain ulang** — hero background
> `ujian-bg.webp` (hasil konversi `ujian.jpg` 4,6 MB → **236 KB**), kartu ujian kini menampilkan
> **cover** + chip meta + countdown, dan **admin bisa mengunggah cover ujian** (kolom `cover_path`
> baru + upload WebP terkompresi + opsi hapus). Style baru memakai prefix `.neo-ujian-*` di
> `resources/css/neo-mirai-home.css` (tanpa file CSS baru). Detail di section 3 (Fase 8), 5.1, 9, 11.

**Branch:** `main`

> **Ringkasan 2026-11-18 (sesi 4):** Pencarian peserta di tab **Peserta** halaman kelola ujian
> diperbaiki — sekarang admin bisa mencari pegawai berdasarkan **nama dan Nomor Induk (NIP)**.
> Sebelumnya endpoint cari hanya cocokkan kolom `users.nip` yang isinya **NIK (16 digit)**, bukan NIP,
> sehingga pencarian dengan NIP asli selalu gagal (0 hasil). NIP/NUPTK asli ada di `users.nomor_induk`.
> `UjianTest` kini **24/24 hijau** (102 assertion), `UjianImportTest` tetap **10/10 hijau**.
> Detail di section 9 dan 11.

> **Ringkasan 2026-11-17 (sesi 3):** Fitur **import soal dari Excel** ditambahkan. Admin bisa
> mengunggah file `.xlsx` berisi daftar soal (pilihan ganda + esai) langsung dari halaman
> **Tambah Ujian** (`/admin/ujian/create`) maupun dari tab **Soal** di halaman kelola ujian.
> Template Excel bisa diunduh, baris bermasalah dilaporkan per nomor baris, dan impor bersifat
> *all-or-nothing*. `UjianImportTest` **10/10 hijau**, `UjianTest` tetap **22/22 hijau**.
> Detail di section 3 (Fase 7), 8, 9, dan 11.

> **Ringkasan 2026-11-16 (sesi 2):** Fitur **pengawasan ujian** (mencegah peserta membuka tab baru
> atau keluar dari halaman saat ujian) selesai diimplementasikan: penjaga peramban (fullscreen,
> deteksi tab baru, blokir salin/tempel/cetak), pencatatan pelanggaran di server
> (`ujian_pelanggaran`), deteksi sesi ganda lewat sidik perangkat, rekap di admin, dan
> `UjianTest` kini **22/22 hijau**. Yang tersisa: uji manual di browser (daftar ada di section 8),
> uji beban, dan upload ke branch/PR.
>
> **Ringkasan 2026-11-16 (sesi 1):** Seluruh kode fitur (migrasi → model → controller admin →
> controller peserta → view → route → menu → seeder → test) **sudah ada**. `UjianTest` kini
> **16/16 hijau** — blokir `pdo_sqlite` yang tercatat sebelumnya ternyata bukan masalah fitur,
> melainkan konfigurasi default `phpunit.xml`. Dua perbaikan penting sudah masuk: **perbaikan
> kebocoran kunci jawaban** di halaman hasil dan **implementasi `acak_soal`** (acak urutan soal +
> pilihan per peserta). Yang tersisa: uji manual di browser, uji beban, dan upload ke branch/PR.
> Detail di section 3, 8, 9, dan 11.

---

## 1. Ringkasan Fitur

Portal SILATAR V2 (KEMENAG Tanah Datar) butuh halaman khusus **peserta ujian** untuk pegawai.
Sudah dipastikan **belum ada fitur ujian sama sekali** di repo (grep `ujian|soal|exam|question|kuis|pilihan ganda|essay`
di `app/`, `resources/`, `database/migrations/`, `routes/` → nol hasil). Jadi ini fitur baru,
bukan refactor.

Cakupan (sudah dikonfirmasi user via AskUserQuestion):

| Keputusan | Nilai |
|---|---|
| Cakupan | **Full stack** (bukan mock UI) |
| Sumber soal | **Langsung dari admin panel** (bukan seeder/hardcoded) |
| Fitur wajib | Timer/countdown, navigasi nomor soal, auto-save jawaban, upload file di jawaban essay |
| K_prioritas | **Ringan bagi user (UI cepat) dan server (query & request minim)** |
| Tema | NEO MIRAI, **tanpa CSS baru & tanpa inline `style=`** (kecuali progress bar yang nilainya dinamis) |

---

## 2. Arsitektur (ringkas — baca kalau perlu konteks)

### 2.1 Model Data (5 tabel baru)

```
ujian                 id, kode(uniq), judul, deskripsi, cover_path?(null = belum ada cover),
                      materi, durasi_menit, status(draft|terbit|selesai),
                      mulai_at, selesai_at, acak_soal(bool), created_by, timestamps
ujian_soal            id, ujian_id(fk), tipe(pilihan_ganda|essay), pertanyaan, petunjuk, poin, urut
ujian_pilihan         id, soal_id(fk), label(A..E), teks, benar(bool), urut
ujian_jawaban         id, ujian_id, soal_id, user_id, pilihan_id?, esai?, file_path?, file_nama?,
                      skor?(null = belum dinilai), dijawab_at   [UNIQUE(user_id, soal_id)]
ujian_peserta         id, ujian_id, user_id, ip?, user_agent?, sidik_perangkat?,
                      status(belum_mulai|berjalan|selesai),
                      started_at, ends_at, submitted_at, nilai?   [UNIQUE(ujian_id, user_id)]
ujian_pelanggaran      id, ujian_id, user_id, jenis, keterangan?, ip?, timestamps
                      (perpindahan_fokus | keluar_layar_penh | tanpa_layar_penh | sesi_ganda |
                       tab_baru | salin | tempel | cetak)
```

`ujian_peserta` dipakai dua-duanya: **penugasan peserta** (admin menentukan siapa boleh ikut)
sekaligus **state sesi** (waktu mulai/selesai, nilai).

Index yang sudah dibuat di migration:
`ujian(kode)` unique · `ujian_soal(ujian_id,urut)` · `ujian_pilihan(soal_id,urut)` ·
`ujian_jawaban` unique(`user_id`,`soal_id`) + index `ujian_id` + index `soal_id` ·
`ujian_peserta` unique(`ujian_id`,`user_id`) + index `user_id`.

### 2.2 Route

Publik (`routes/web.php`, di dalam grup `Route::middleware('auth')` — grup ada di baris ~21-132):

```
GET  /ujian                      → ujian.index        daftar ujian
GET  /ujian/{id}                 → ujian.kerjakan     halaman pengerjaan
POST /ujian/{id}/jawaban         → ujian.jawaban      auto-save BATCH
POST /ujian/{id}/upload          → ujian.upload       lampiran essay
POST /ujian/{id}/submit          → ujian.submit       kumpulkan jawaban
GET  /ujian/{id}/hasil           → ujian.hasil        hasil & pembahasan
GET  /ujian/jawaban/{id}/berkas  → ujian.berkas       unduh lampiran
POST /ujian/{id}/perangkat       → ujian.perangkat    daftar sidik perangkat (deteksi sesi ganda)
POST /ujian/{id}/pelanggaran     → ujian.pelanggaran  catat pelanggaran dari peramban
```

Admin (`routes/admin.php`, di dalam grup `Route::middleware(['auth','admin'])->name('admin.')`):

```
GET    /admin/ujian                      index
GET    /admin/ujian/create               create
POST   /admin/ujian                      store
GET    /admin/ujian/{id}/edit            edit
PUT    /admin/ujian/{id}                 update
DELETE /admin/ujian/{id}                 destroy
POST   /admin/ujian/{id}/soal            storeSoal
POST   /admin/ujian/{id}/soal/import     importSoal      ← import soal dari Excel
GET    /admin/ujian/soal/template        templateSoal    ← unduh template Excel
PUT    /admin/ujian/soal/{soalId}        updateSoal
DELETE /admin/ujian/soal/{soalId}        destroySoal
POST   /admin/ujian/soal/{soalId}/pilihan storePilihan
PUT    /admin/ujian/pilihan/{pilihanId}  updatePilihan
DELETE /admin/ujian/pilihan/{pilihanId}  destroyPilihan
POST   /admin/ujian/{id}/peserta         storePeserta
DELETE /admin/ujian/peserta/{pesertaId}  destroyPeserta
GET    /admin/ujian/{id}/peserta/cari    cariPeserta   ← tambahan dari plan (pencarian peserta)
GET    /admin/ujian/{id}/hasil           hasil (rekap + penilaian manual)
PUT    /admin/ujian/jawaban/{id}/skor    updateSkor
```

### 2.3 Prinsip "ringan" (WAJIB dijaga saat lanjut development)

| Aspek | Aturan |
|---|---|
| Beban halaman | Semua soal + pilihan dimuat **sekali** sebagai payload JSON (`@js()`). Berpindah soal = Alpine lokal, **0 request** |
| Auto-save | **Batch**: Alpine antrekan `pending{soal_id → nilai}`, `flush()` kirim **1** POST berisi semua perubahan. Debounce 1.5 dtk idle. Nilai tidak berubah tidak pernah dikirim |
| Pelanggaran | 1 POST `sendBeacon` per kejadian (tidak memblokir UI, tetap terkirim saat tab ditutup). Deduplikasi 3 detik per jenis agar satu kejadian tidak terhitung dua kali |
| Flush terakhir | `visibilitychange`/`pagehide` → `navigator.sendBeacon()` (agar tidak memblokir unload) |
| Timer | Client-side dari `ends_at` server. **Tanpa polling**. Server hanya dihubungi saat waktu habis |
| Query render | 2 query (`ujian` + eager-load `soal.pilihan`) + 1 query state sesi. Tanpa N+1 |
| Cache | Payload soal: `Cache::remember("ujian:{id}:soal", 300, ...)`, di-`forget()` saat admin simpan soal. Cache hasil: `ujian:{id}:hasil:{user_id}` 60 dtk |
| Skor | 1 aggregate query saat submit (bukan loop per soal) |
| Aset | **Tidak ada file JS/CSS baru**. Logika ujian = satu blok `<script>` inline Alpine di view |
| Upload | On-demand, maks 5 MB, tanpa resize, nama file `Str::uuid()` |

Ringkas: **1 request buka halaman, 1 request per batch jawaban, 1 request saat submit.**

### 2.4 Keamanan (WAJIB)

- Semua route peserta harus cek bahwa user **adalah peserta yang ditunjuk** (`ujian_peserta`) atau punya sesi valid.
- `soal_id` harus milik `ujian_id` yang diakses; `pilihan_id` harus milik `soal_id` tersebut.
- **Kolom `benar` TIDAK BOLEH** pernah sampai ke browser peserta. Ambil payload server-side (bisa cache), lalu buang `benar` sebelum dikirim ke view. Jangan `$soal->pilihan` ter-eager-load dengan `benar` di view peserta.
  - `tanpaKunci()` memakai **allowlist** (bukan blacklist), jadi kunci baru bisa lolos kalau ada field baru.
  - **Halaman hasil hanya boleh dibuka setelah sesi `selesai`.** `hasil()` wajib menolak sesi
    berstatus `belum_mulai`/`berjalan`, kalau tidak kunci jawaban bocor sebelum ujian ditutup.
- File essay: whitelist extension, `max:5120`, nama acak, simpan di `storage/app/public/ujian/{ujian_id}/{user_id}/`, unduh hanya oleh pemilik atau admin.
- Semua POST butuh CSRF (`X-CSRF-TOKEN` untuk fetch).
- Blade escaping `{{ }}` untuk semua teks soal; JSON Alpine lewat `@js()`.

---

## 3. Checklist

### Fase 1 — Skema & Model
- [x] Migration `2026_10_28_000001_create_ujian_table.php`
- [x] Migration `2026_10_28_000002_create_ujian_soal_table.php`
- [x] Migration `2026_10_28_000003_create_ujian_pilihan_table.php`
- [x] Migration `2026_10_28_000004_create_ujian_jawaban_table.php`
- [x] Migration `2026_10_28_000005_create_ujian_peserta_table.php`
- [x] Model `app/Models/Ujian.php`
- [x] Model `app/Models/UjianSoal.php`
- [x] Model `app/Models/UjianPilihan.php`
- [x] Model `app/Models/UjianJawaban.php`
- [x] Model `app/Models/UjianPeserta.php`

### Fase 2 — Admin Panel (sumber soal)
- [x] Controller `app/Http/Controllers/Admin/UjianController.php` (17 method)
  - [x] `index` (list + `paginate(15)` + `withCount('soal')`/`withCount('peserta')` + pencarian)
  - [x] `create` / `store`
  - [x] `edit` / `update`
  - [x] `destroy`
  - [x] `storeSoal` / `updateSoal` / `destroySoal`
  - [x] `storePilihan` / `updatePilihan` / `destroyPilihan`
  - [x] `storePeserta` / `destroyPeserta` + `cariPeserta` (pencarian user via AJAX, cocokkan nama + Nomor Induk)
  - [x] `hasil` (rekap + penilaian manual) / `updateSkor`
  - [x] Setiap method yang mengubah soal → `Cache::forget("ujian:{id}:soal")` (7 titik: `:153, :198, :236, :249, :275, :295, :313`)
- [x] View `resources/views/admin/ujian/index.blade.php`
- [x] View `resources/views/admin/ujian/create.blade.php`
- [x] View `resources/views/admin/ujian/edit.blade.php` (tab: Soal & Pilihan / Peserta / Hasil)
- [x] View `resources/views/admin/ujian/hasil.blade.php` (rekap admin)
- [x] Partial `resources/views/admin/ujian/_partials/{pilihan-rows,flash}.blade.php`
- [x] Route di `routes/admin.php:244-266` (`admin.ujian.*`)
- [x] Menu sidebar admin di `resources/views/admin/layouts/app.blade.php:145` (grup "Kelola" + `menu-group-count` di `:90`)

### Fase 3 — Halaman Peserta (NEO MIRAI)
- [x] Controller `app/Http/Controllers/UjianController.php` (7 method)
  - [x] `index` — daftar ujian untuk user
  - [x] `kerjakan` — buat/resume sesi, susun payload soal (tanpa `benar`), hitung `ends_at` (`:90`)
  - [x] `simpanJawaban` — batch upsert dalam 1 transaksi + guard waktu (`:160`)
  - [x] `uploadLampiran` — simpan file essay + guard waktu (`:260`)
  - [x] `submit` — tutup sesi + hitung skor 1 aggregate query
  - [x] `hasil` — rekap + pembahasan (cache 60 dtk, `:356`)
  - [x] `unduhBerkas` — unduh lampiran (pemilik/admin saja)
- [x] View `resources/views/ujian/index.blade.php`
- [x] View `resources/views/ujian/kerjakan.blade.php` (+ blok `<script>` Alpine inline, ~470 baris)
- [x] View `resources/views/ujian/hasil.blade.php`
- [x] Route di `routes/web.php:66-74` (grup `auth`)
- [x] Menu "Ujian" di `resources/views/components/layouts/site-header.blade.php` (desktop user-dropdown `:98`, mobile bottom-nav dropdown `:184`)

### Fase 4 — Data contoh & verifikasi
- [x] Seeder `database/seeders/UjianSeeder.php` (1 ujian terbit + 5 PG + 2 essay + 5 peserta)
- [x] `php artisan migrate` sukses — 5 tabel baru tercatat `[34] Ran` di `migrate:status`
- [x] `php artisan db:seed --class=UjianSeeder` sukses — terverifikasi: **1 ujian, 7 soal, 5 peserta**
- [x] `UjianTest` hijau — **16/16 test, 67 assertion** (10 test lama + 6 test baru 2026-11-16).
      **Blocker `pdo_sqlite` yang tercatat sebelumnya SALAH.** `phpunit.xml:26-27` memang memakai
      `DB_CONNECTION=sqlite`, tapi `UjianTest` memakai trait `DatabaseTransactions` (bukan
      `RefreshDatabase`) sehingga **tidak pernah menjalankan migrasi** — dengan sqlite `:memory:`
      tabel tidak akan pernah ada, jadi **install `pdo_sqlite` saja TIDAK akan membuat test hijau**.
      Solusi yang benar: jalankan suite dengan driver MySQL.
      ```bash
      DB_CONNECTION=mysql DB_DATABASE=kemenagtd_db php vendor/bin/phpunit tests/Feature/UjianTest.php
      ```
      ⚠️ `tests/Feature/PresensiImportTest.php` memaksa `config(['database.default' => 'sqlite'])`
      di `setUp` sehingga 9 test-nya tetap gagal `"could not find driver"` — **pre-existing, tidak
      terkait fitur ujian**.
- [ ] Uji manual di browser (timer, autosave batch, navigasi palette, upload essay, submit, hasil)
- [ ] Uji beban (1 request per batch jawaban, tanpa polling, response < 500 ms dengan 50 soal)
- [x] `composer pint` — semua file fitur ujian lolos (sebelumnya `tests/Feature/UjianTest.php` gagal)

### Fase 5 — Perbaikan & penyempurnaan (2026-11-16)
- [x] **Perbaikan kebocoran kunci jawaban** — `UjianController::hasil()` kini menolak sesi yang
      belum `selesai` (redirect ke `ujian.kerjakan`), jadi kunci tidak bisa dibuka sebelum ujian ditutup
- [x] **Implementasi `acak_soal`** — urutan soal dan urutan pilihan diacak per peserta, deterministik
      (seed `ujian:{id}:user:{user_id}`), label pilihan diurutkan ulang mengikuti posisi tampil
- [x] **Perbaikan urutan cek waktu** — cek kedaluwarsa dipindah sebelum sesi dimulai ulang di
      `kerjakan()`, supaya durasi ujian tidak bisa diperpanjang saat halaman dimuat ulang
- [x] `composer pint` — `tests/Feature/UjianTest.php` diformat ulang

### Fase 6 — Pengawasan ujian / pencegahan pelanggaran tab (2026-11-16)

> Keputusan user (AskUserQuestion): **peringatan + log saja** (tidak ada auto-submit),
> **fullscreen wajib dengan toleransi**, dan **salin/klik kanan/tempel/cetak diblokir**.

- [x] Migration `2026_11_16_000002_add_perangkat_to_ujian_peserta_table.php`
      (`ip`, `user_agent`, `sidik_perangkat` + index `sidik_perangkat`)
- [x] Migration `2026_11_16_000003_create_ujian_pelanggaran_table.php`
- [x] Model `app/Models/UjianPelanggaran.php` (+ konstanta `LABEL` untuk label jenis)
- [x] Relasi `Ujian::pelanggaran()` & `UjianPeserta::pelanggaran()`
- [x] `POST /ujian/{id}/perangkat` — mendaftarkan sidik perangkat, mendeteksi **sesi ganda**
      (perangkat berbeda pada sesi yang sama → HTTP 409 + baris pelanggaran `sesi_ganda`)
- [x] `POST /ujian/{id}/pelanggaran` — menyimpan pelanggaran (jenis whitelisted), membalas
      jumlah total pelanggaran
- [x] Penjaga peramban di `resources/views/ujian/kerjakan.blade.php`:
      - Modal **Mulai Ujian dalam Mode Layar Penuh** (masuk fullscreen / lewati dengan catatan)
      - Deteksi **tab atau jendela baru** (`visibilitychange` + `blur` + override `window.open`
        + intercept `a[target="_blank"]`)
      - Deteksi **keluar dari layar penuh** (`fullscreenchange`)
      - Blokir **salin / potong / tempel / klik kanan / Ctrl+P**
      - Peringatan `beforeunload` saat meninggalkan halaman
      - Badge jumlah pelanggaran di bilah atas + modal peringatan per kejadian
- [x] Rekap di **admin halaman hasil ujian** (kolom Pelanggaran + rincian jenis/waktu/IP)
- [x] Informasi jumlah pelanggaran di **halaman hasil peserta**
- [x] `UjianTest` 16 → **22 test, 97 assertion, semua hijau**

### Fase 7 — Import soal dari Excel (2026-11-17)
> Permintaan user: pada halaman **Tambah Ujian** (`/admin/ujian/create`) tersedia cara cepat
> memasukkan soal dari file Excel, tanpa mengetik satu per satu.

- [x] Service `app/Services/UjianSoalImportService.php`
      - `parse()` — baca sheet pertama file `.xlsx`/`.xls`, petakan baris pertama sebagai header
        kolom, validasi tiap baris soal
      - `simpan()` — simpan soal + pilihan dalam **1 transaksi**, `urut` dilanjutkan dari soal
        terakhir yang ada
      - `template()` / `unduhTemplate()` — buat file template Excel (sheet `Soal` + sheet
        `Petunjuk`), file disimpan di `storage/app/exports/` dan dihapus setelah diunduh
- [x] Format kolom Excel (baris pertama = header):
      `tipe`, `pertanyaan`, `petunjuk`, `poin`, `pilihan_a`..`pilihan_e`, `kunci`
      - `tipe` boleh dikosongkan → dideteksi otomatis (`pilihan_ganda` bila ada opsi, selain itu `essay`)
      - `kunci` diisi huruf `A`–`E` (angka `1`–`5` juga diterima)
      - nama kolom alternatif diterima: `soal`, `jenis`, `opsi_a`..`opsi_e`, `jawaban_benar`, dll.
      - baris contoh diawali `[CONTOH]` otomatis dilewati
- [x] **All-or-nothing**: bila ada satu baris bermasalah, seluruh impor dibatalkan dan nomor
      baris + alasannya ditampilkan di halaman (tidak ada soal parsial yang tersimpan)
- [x] Controller `Admin\UjianController`
      - `importSoal()` — `POST /admin/ujian/{id}/soal/import` (import ke ujian yang sudah ada)
      - `templateSoal()` — `GET /admin/ujian/soal/template` (unduh template)
      - `store()` — field `file_soal` opsional; bila diisi, soal langsung diimpor setelah ujian
        dibuat (dari halaman Tambah Ujian)
      - `prosesImportSoal()` — helper: parse → simpan → `Cache::forget("ujian:{id}:soal")`
- [x] Route baru di `routes/admin.php`: `admin.ujian.soal.import`, `admin.ujian.soal.template`
- [x] View:
      - `resources/views/admin/ujian/_partials/import-soal.blade.php` (partial bersama)
      - `create.blade.php` — kartu **Import Soal dari Excel (Opsional)** + form jadi `multipart`
      - `edit.blade.php` — kartu import di tab **Soal** (untuk menambah soal lagi di kemudian hari)
- [x] `tests/Feature/UjianImportTest.php` — **10 test, 41 assertion, semua hijau**
- [x] `composer pint` — semua file baru lolos

### Fase 8 — Cover ujian & redesign halaman peserta (2026-11-18)
> Permintaan user (sesi 5): (1) header halaman `/ujian` disamakan dengan halaman lain
> (`/pelayanan`, `/publikasi`) memakai background `public/assets/img/template/ujian.jpg` yang
> dikonversi ke WebP, (2) desain halaman `/ujian` dipercantik tanpa keluar dari tema NEO MIRAI,
> (3) daftar ujian menampilkan **cover ujian**, dan admin bisa mengunggah cover tersebut.

- [x] Konversi gambar header dengan **sharp** (Node, dependency bawaan project):
      `public/assets/img/template/ujian.jpg` (4,6 MB, 3712×1152) → `ujian-bg.webp`
      (1920×596, **236 KB**, quality 70, `fit: cover` + `position: attention`) — **~95% lebih ringan**.
      Berkas `.jpg` asli tetap disimpan sebagai sumber.
- [x] Migration `2026_11_18_000001_add_cover_path_to_ujian_table.php` — kolom `cover_path`
      (string nullable) pada tabel `ujian`
- [x] Model `App\Models\Ujian` — `cover_path` masuk `$fillable`, accessor
      `getCoverUrlAttribute()` mengembalikan URL publik disk `public` (null bila belum ada cover)
- [x] Controller `Admin\UjianController`
      - `simpanCover()` — kompresi via Intervention Image (GD) → WebP quality 82, lebar maks 1200px,
        disimpan di `ujian/cover/{uuid}.webp`; ada fallback simpan berkas asli bila proses gagal
      - `hapusCover()` — hapus berkas lama saat cover diganti/dihapus, dan saat ujian dihapus
      - `store()` / `update()` — field `cover` (image, maks 4 MB) + checkbox `remove_cover`
- [x] Admin:
      - `admin/ujian/create.blade.php` — area unggah cover + pratinjau gambar (JS `previewCover`)
      - `admin/ujian/edit.blade.php` — cover saat ini + unggah pengganti + checkbox "Hapus cover"
        (form identitas diubah jadi `multipart/form-data`)
      - `admin/ujian/index.blade.php` — thumbnail cover 48×48 pada kolom Ujian (fallback ikon)
- [x] Halaman peserta `resources/views/ujian/index.blade.php` — **desain baru**:
      - Hero `hero-page neo-ujian-hero` dengan background `ujian-bg.webp`, **overlay terang**
        (krem semi transparan, sama seperti halaman lain) supaya tulisan tetap terbaca,
        ikon emas berglow, eyebrow mono, judul gradasi, 2 CTA, dan 4 ringkasan angka
      - Kartu ujian `.neo-ujian-card` (grid `auto-fill minmax(22rem)`): cover 16:9, badge status
        berwarna, kode ujian, judul, deskripsi, chip meta (soal / durasi / nilai), tombol aksi
      - Countdown sisa waktu berjalan (Alpine, `x-init setInterval`)
      - Empty state `.neo-ujian-empty`
- [x] CSS: **tidak menambah file CSS baru** (sesuai aturan section 12). Semua gaya khusus
      `/ujian` (prefix `.neo-ujian-*`) ditambahkan di `resources/css/neo-mirai-home.css`;
      `npm run build` sukses.
- [x] Test `tests/Feature/UjianTest.php` — 3 test baru: unggah cover, hapus cover, cover tampil
      di daftar ujian peserta (pakai `Storage::fake('public')`)

### Fase 9 — Redesign halaman pengerjaan & modal layar penuh (2026-11-18, sesi 6)
> Permintaan user: setelah klik **"Mulai Ujian"**, modal **"Masuk ke Layar Penuh"** dan
> halaman pengerjaan ujian harus dipercantik — pakai font & ikon menarik tanpa keluar dari tema
> NEO MIRAI.

- [x] Modal **"Masuk ke Layar Penuh"** (`.neo-ujian-fs-*`) — bukan lagi modal polos:
      - kepala gradasi malam + ikon monitor emas berglow + judul display + subjudul
      - 3 aturan tampil sebagai kartu bernomor (`.neo-ujian-fs-rule`, nomor mono emas)
      - catatan merah "keluar layar penuh = pelanggaran" (`.neo-ujian-fs-alert`)
      - footer 2 tombol: sekunder "Kembali" & utama "Mulai Ujian" (ikon) — lihat Fase 12
- [x] Halaman pengerjaan:
      - bar atas sticky `.neo-ujian-topbar` + **garis progres jawaban** tipis di bawah bar
      - timer jadi pill `.neo-ujian-timer`, **berkedip merah** saat sisa waktu ≤ 60 detik
      - catatan mode layar penuh jadi callout `.neo-ujian-notice`
      - panel soal `.neo-ujian-panel` (kepala "Soal X dari Y" + badge tipe/poin, pertanyaan
        display font, petunjuk dengan aksen garis emas)
      - pilihan jawaban jadi kartu `.neo-ujian-option` (huruf A/B/C jadi chip yang berubah jadi
        gradasi emas saat dipilih + centang hijau-emas, hover geser 3px)
      - palet soal `.neo-ujian-palette` (nomor kotak,answered = gradasi emas, soal aktif = outline)
      - bar progres gradasi emas + tombol navigasi Previously/Berikutnya berikon
      - modal "Kumpulkan Jawaban" & modal peringatan pelanggaran dirapikan (ikon + bar progres)
- [x] CSS: semua gaya baru prefix `.neo-ujian-*` di `resources/css/neo-mirai-home.css`
      (tanpa file CSS baru, font tetap Chakra Petch + JetBrains Mono, ikon SVG inline gaya Lucide)
- [x] `npm run build` sukses, seluruh view lolos `php artisan view:cache`

### Fase 10 — Redesign modal & halaman hasil (2026-11-18, sesi 7)
> Permintaan user: (1) modal **"Total Pelanggaran pada sesi ini"** dipercantik dan tombol
> **"Kembali ke Ujian"** harus mengembalikan peserta ke halaman ujian **dengan layar penuh**,
> (2) modal **"Kumpulkan Jawaban?"** dipercantik, (3) **halaman hasil** dipercantik tanpa
> keluar dari tema NEO MIRAI.

- [x] Modal **"Total Pelanggaran pada sesi ini"** (`.neo-ujian-alert-*`) — bukan lagi modal polos:
      - kepala gradasi malam + halo merah + ikon segitiga peringatan beranimasi (`neo-ujian-halo`)
      - eyebrow mono "Pelanggaran Terdeteksi" + judul dari `warnTitle` + subjudul
      - isi: kartu pesan (border kiri merah) + **penghitung total** angka mono besar
        + strip penanda tiap pelanggaran (`x-for` atas `tickTotal`)
      - footer: satu tombol **"Kembali ke Ujian"** (ikon panah kiri) lebar penuh
- [x] **Tombol "Kembali ke Ujian" mengembalikan layar penuh:**
      - helper Alpine baru `aktifkanLayarPenuh()` — meminta `requestFullscreen` **tanpa**
        mencatat pelanggaran (dipisah dari `masukLayarPenuh()` yang lama)
      - `kembaliKeUjian()` menutup modal, memanggil `aktifkanLayarPenuh()` (klik tombol adalah
        gestur pengguna sehingga browser mengizinkan), lalu menyinkronkan `fsAktif`/`fsPernahAktif`
      - `fsPernahAktif` baru: penanda bahwa peserta pernah masuk layar penuh
      - callout mode layar penuh di bawah bilah atas sekarang **dinamis** (`x-show="fsAktif"`)
- [x] Modal **"Kumpulkan Jawaban?"** (`.neo-ujian-submit-*`):
      - kepala gradasi malam + ikon centang emas + eyebrow "Konfirmasi Pengumpulan"
      - **cincin progres SVG** (gradien emas) menampilkan persentase jawaban terjawab
      - 3 kartu angka: Dijawab / Kosong / Total Soal (angka "Kosong" merah bila > 0)
      - callout merah bila masih ada soal belum terjawab + catatan "jawaban aman di server"
      - footer: Batal (sekunder) + "Ya, Kumpulkan" (utama, berubah jadi "Mengirim...")
- [x] **Halaman hasil** (`resources/views/ujian/hasil.blade.php`) — redesign penuh (`.neo-hasil-*`):
      - hero gradasi malam dengan eyebrow mono, judul ujian, subjudul, **chip meta**
        (durasi, jumlah soal, durasi pengerjaan) dan lencana status (Lulus / Belum Lulus / Belum Dinilai)
      - **lingkaran nilai SVG** (gradien emas) menampilkan nilai akhir
      - 4 kartu statistik berikon: Jawaban Benar, Jawaban Salah, Tidak Dijawab, Skor dari total poin
      - kartu peringatan pelanggaran (hanya tampil bila ada pelanggaran)
      - judul seksi "Pembahasan" + daftar kartu soal: nomor mono, lencana tipe/status/skor,
        pertanyaan, petunjuk, kartu **Jawaban Anda** vs **Kunci Jawaban** dengan ikon
        centang/silang, lampiran esai, dan tombol **Cetak Hasil**
- [x] **Expand / collapse bagian Pembahasan** di halaman hasil (satu tombol untuk **seluruh
      bagian**, bukan per soal):
      - tombol `.neo-hasil-reveal` — blok besar bertanda **emas**: border putus-putus, latar
        gradien hangat, ikon panah dalam lingkaran gradien emas (berputar 180° saat terbuka),
        judul display font, keterangan, dan lencana jumlah soal
      - label berpindah antara "Tampilkan Pembahasan" dan "Sembunyikan Pembahasan"
      - daftar kartu soal dibungkus satu blok `x-show` + `x-transition.opacity`
      - **default tersembunyi** saat halaman dibuka (`buka: false`)
      - state Alpine `buka`, atribut `aria-expanded` pada tombol
      - Alpine `hasilUjian(total)` didaftarkan inline di view (`@once`)
      - tombol **"Cetak Hasil"** membuka pembahasan lebih dulu lalu mengembalikan susunan
        sebelumnya setelah selesai dicetak (`afterprint`)
- [x] CSS: semua gaya baru (`.neo-ujian-alert-*`, `.neo-ujian-submit-*`, `.neo-hasil-*`) ditambahkan
      di `resources/css/neo-mirai-home.css` — **tanpa file CSS baru**; `npm run build` sukses
- [x] **Perbaikan test** (pre-existing, bukan dari redesign ini):
      - `assertDatabaseCount('ujian_pelanggaran', 0)` → cakupan ke `ujian_id` test sendiri,
        supaya sisa data uji manual di database dev tidak menggagalkan suite
      - `test_cover_ujian_tampil_pada_daftar_ujian_peserta` sebelumnya membuat `ujian_peserta`
        ganda (sudah dibuat di `setUp`) → duplikat; dan `Storage::put()` mengembalikan `bool`,
        bukan path → path ditulis manual
- [x] `UjianTest` **27/27 hijau** (112 assertion) + `UjianImportTest` **10/10 hijau** (41 assertion)

### Fase 11 — Responsive mobile & perbaikan modal ujian (2026-11-18, sesi 8)
> Permintaan user: (1) halaman `/ujian` dibuat **responsif untuk semua layar mobile/Android**,
> (2) pada modal **"Masuk ke Layar Penuh"** tombol **"Masuk Layar Penuh"** tertutup **bottom nav
> mobile**, (3) **bottom mobile nav disembunyikan** saat di halaman ujian/pengisian jawaban.

- [x] **Bottom mobile nav disembunyikan saat mengerjakan ujian:**
      - `<body>` dapat kelas `neo-ujian-mode` bila route = `ujian.kerjakan`
        (`resources/views/components/layouts/app.blade.php`)
      - CSS di `resources/views/components/layouts/site-header.blade.php` (blok `@media (max-width: 900px)`):
        `body.neo-ujian-mode .bottom-nav { display: none !important; }`
      - padding bawah halaman (`padding-bottom: 70px`) yang biasanya ada untuk memberi ruang
        bottom nav diganti jadi `calc(1rem + env(safe-area-inset-bottom, 0px))` — tidak ada lagi
        celah kosong di bawah halaman ujian
- [x] **Tombol modal "Masuk Layar Penuh" tidak lagi tertutup bottom nav:**
      - `.neo-ujian-fs-backdrop` (dan `.neo-ujian-submit-backdrop`, `.neo-ujian-alert-backdrop`)
        dinaikkan ke `z-index: 200` — di atas `.bottom-nav` (`z-index: 100`)
      - `padding-bottom` backdrop memakai `env(safe-area-inset-bottom)` supaya tombol tetap
        terjangkau pada perangkat Android dengan gesture bar
- [x] **Ketiga modal ujian jadi bisa digulir (badan modal scroll, kaki modal tetap terlihat):**
      - `.neo-ujian-fs-modal`, `.neo-ujian-submit-modal`, `.neo-ujian-alert-modal` memakai
        `display: flex; flex-direction: column; max-height: calc(100dvh - 2rem)` (fallback `92vh`)
      - kepala & kaki modal `flex: 0 0 auto`, badan modal `flex: 1 1 auto; min-height: 0;
        overflow-y: auto` — previously `overflow: hidden` menimpa `overflow-y: auto` sehingga
        isi modal terpotong dan tombolnya tidak bisa dijangkau
- [x] **Responsive halaman pengerjaan (`.neo-ujian-*`):**
      - bilah atas jadi dua baris di layar kecil: judul + badge (dapat digeser horizontal) di baris 1,
        timer + tombol **"Kumpulkan"** (label pendek) di baris 2, status simpan di baris 3
      - palet soal di sidebar berubah jadi **strip horizontal yang dapat digeser** di ≤1023px,
        dan sidebar dipindah **ke atas** panel soal (`order-first lg:order-none`)
      - tombol **"Kumpulkan Jawaban"** di sidebar disembunyikan di ≤1023px (sudah ada di bilah atas)
      - navigasi.soal: di ≤480px tombol Sebelumnya/Berikutnya jadi dua kolom dan penomoran
        pindah ke baris sendiri
      - notifikasi mode layar penuh, ikon & padding ketiga modal diperkecil di ≤640px
- [x] **Responsive halaman daftar (`index.blade.php`) & halaman hasil (`hasil.blade.php`):**
      - padding hero dipindah dari inline style ke kelas `.neo-ujian-hero-pad` (responsif)
      - halaman hasil: lingkaran nilai dipusatkan, statistik jadi **2 kolom** di ≤640px,
        tombol "Tampilkan Pembahasan" boleh membungkus
      - banner promo Android disembunyikan saat mengerjakan ujian (`body.neo-ujian-mode .neo-app-promo`)
- [x] CSS ditambahkan di `resources/css/neo-mirai-home.css` — **tanpa file CSS baru**;
      `php artisan view:cache` lolos. **Verifikasi manual di browser ditunda** (sesuai instruksi user).

---

### Fase 12 — Cover fit-to-cover, mulai ujian saat tombol ditekan & modal layar penuh ringkas (2026-11-18, sesi 9)
> Permintaan user: (1) **cover pada list ujian** harus pas di area cover (*fit to cover*),
> (2) **waktu ujian tidak boleh berjalan sejak halaman dibuka** — baru berjalan saat peserta
> menekan **"Mulai Ujian"**, (3) di mobile tombol pada modal **"Masuk ke Layar Penuh"**
> terlalu besar sehingga area informasi jadi kecil,
> (4) ganti tombol modal menjadi **2 buah: "Kembali" & "Mulai Ujian"** — ujian **wajib**
> dalam layar penuh, sehingga tombol **"Lanjut Tanpa Layar Penuh"** dihapus.

- [x] **Cover ujian memenuhi area cover (fit to cover):**
      - `.neo-ujian-card-img` diubah jadi `position: absolute; inset: 0; display: block;
        object-fit: cover; object-position: center` di `resources/css/neo-mirai-home.css`
      - sebelumnya `height: 100%` pada `<img>` inline di dalam box `aspect-ratio: 16/9`
        tidak selalu sampai penuh sehingga cover terlihat tidak proporsional
- [x] **Waktu ujian baru berjalan setelah peserta menekan tombol mulai:**
      - **Route baru** `POST /ujian/{id}/mulai` → `UjianController::mulai()`
        (`routes/web.php`, name `ujian.mulai`)
      - `UjianController::kerjakan()` **tidak lagi** menulis `started_at` / `ends_at` /
        status `berjalan`; halaman hanya menampilkan soal
      - `UjianController::mulai()`: cek peserta, cek ujian terbit & sudah mulai,
        daftar **sidik perangkat** (perangkat berbeda → pelanggaran `sesi_ganda` + `409`),
        baru tulis `status = berjalan`, `started_at = now()`,
        `ends_at = started_at + durasi_menit`, dan kembalikan `remaining` ke browser
      - Helper `sesiBelumMulai()` dipakai sebagai penjaga di `simpanJawaban()`,
        `uploadLampiran()`, `submit()`, dan `catatPelanggaran()` → `409 "Ujian belum dimulai."`
        (validasi `jenis` pelanggaran tetap diperiksa lebih dulu agar tetap `422`)
      - `catatPerangkat()` **tidak** lagi menyimpan sidik perangkat sebelum ujian dimulai,
        sehingga perpindahan perangkat sebelum menekan tombol mulai tidak dianggap sesi ganda
      - View `ujian/kerjakan.blade.php`: state Alpine `dimulai` / `memulai` / `startError`,
        method `mulaiSesi()` (POST ke `ujian.mulai`), `tick()` & `timeText` baru mulai
        setelah sesi berjalan (sebelumnya tampil `--:--`), `catat()` diabaikan sebelum mulai,
        `beforeunload` tidak memunculkan peringatan sebelum ujian dimulai
      - Peserta yang **batal** mengikuti ujian: `kembaliKeDaftarUjian()` kembali ke daftar
        tanpa memulai sesi, tanpa mencatat pelanggaran
- [x] **Tombol modal layar penuh diganti 2 buah: "Kembali" & "Mulai Ujian":**
      - tombol **"Lanjut tanpa layar penuh"** dihapus — **ujian wajib layar penuh**
      - tombol **"Mulai Ujian"**: masuk ke layar penuh → `POST /ujian/{id}/mulai` → waktu
        ujian mulai; bila peramban menolak layar penuh, sesi **tidak** dimulai dan pesan
        `.neo-ujian-fs-error` muncul
      - tombol **"Kembali"**: kembali ke daftar ujian tanpa memulai sesi dan tanpa mencatat
        pelanggaran
      - `.neo-ujian-fs-foot` jadi **dua baris**: baris 1 dua tombol aksi
        (`.neo-ujian-fs-actions`), baris 2 tombol kembali
      - di ≤640px tombol aksi diperkecil (`padding: 0.5rem 0.6rem`) supaya area
        informasi (aturan 01–03) tetap luas
      - kepala modal juga diringkas (ikon & padding lebih kecil) di ≤640px
- [x] **Test `tests/Feature/UjianTest.php` disesuaikan** dengan aturan baru:
      - helper `mulaiUjian()` memanggil endpoint `ujian.mulai` (dengan header
        `X-Ujian-Perangkat`), ditambahkan ke test yang butuh sesi berjalan
      - test baru: `test_ujian_tidak_bisa_dimulai_oleh_bukan_peserta` &
        `test_jawaban_tidak_bisa_disimpan_sebelum_ujian_dimulai`
      - `test_peserta_ditunjuk_dapat_membuka_halaman_pengerjaan` kini memastikan halaman
        **tidak** memulai sesi, lalu `mulaiUjian()` memulai sesi
- [x] CSS & view ditambahkan di `resources/css/neo-mirai-home.css` dan
      `resources/views/ujian/kerjakan.blade.php` — **tanpa file CSS baru**;
      `php artisan view:cache` lolos. **Verifikasi manual di browser ditunda**
      (sesuai instruksi user).

---

## 4. Referensi pola existing yang WAJIB dipakai ulang

> Jangan bikin CSS/komponen baru. Semua sudah ada.

### Layout & shell
- Layout publik: `resources/views/components/layouts/app.blade.php` → `<x-layouts.app title="...">`
  Load `app.css` + `neo-mirai-home.css` + `resources/js/app.js`. Body class `neo-mirai`.
- Layout admin: `resources/views/admin/layouts/app.blade.php` → `<x-admin.layouts.app title="...">`
  Load `app.css` + `admin-new.css` (kelas: `page-header`, `page-title`, `stat-card`, `card`, `btn btn-primary`, `form-group`, `form-input`, `form-select`).
- Hero sub-page publik: `resources/views/components/ui/page-hero.blade.php` (`x-ui.page-hero`),
  atau section `.hero-page` + `.hero-page-content` — contoh nyata di `resources/views/penilaian-kinerja/index.blade.php:9-22`.
- Kontainer: `.page-content` + `.content-centered` (`neo-mirai-home.css:294`, `:1562`).

### Kelas CSS NEO MIRAI (semua sudah ada di `resources/css/neo-mirai-home.css`)
| Kebutuhan | Kelas | Baris |
|---|---|---|
| Kartu | `.neo-card`, `.neo-card-header`, `.neo-card-title`, `.neo-card-badge` | 1587-1627 |
| Grid & stat | `.neo-grid`, `.neo-grid-2/3/4`, `.neo-stat-card`, `.neo-stat-icon`, `.neo-stat-value`, `.neo-stat-label` | 1629-1645, 2799-2888 |
| Tombol | `.neo-btn` (primer pill emas), `.neo-btn-secondary` (outline), `.neo-btn-icon`, `.neo-btn-add`, `.neo-btn-remove` | 2985-3011, 3242-3263, 7866-7904 |
| Form | `.neo-form-group/-label/-input/-textarea/-select/-hint/-error` | 3109-3183, 6587-6700 |
| Field | `.neo-field-label/-input/-textarea/-select/-hint/-error`, `.neo-fields-section`, `.neo-fields-grid` | 7248-7335, 7437-7503 |
| Upload | `.neo-upload-section/-grid/-card/-dropzone/-preview/-badge/-hint` | 6946-7245 |
| Badge | `.neo-badge`, `.neo-badge-dot`, `.neo-badge-primary/-warning/-gold` | 7944-7976 |
| Modal | `.neo-modal-backdrop`, `.neo-modal`, `.neo-modal-sm/lg/xl`, `.neo-modal-header/-title/-body/-footer/-close/-actions` | 2382-2430, 3037-3108, 8319-8421 |
| Kosong | `.neo-empty` / `.neo-empty-state`, `.neo-empty-title`, `.neo-empty-text` | 2354-2380, 4870-4900 |
| Tabs | `.neo-tabs` | 4542, 7556 |
| Hero | `.hero-page`, `.hero-page-content`, `.hero-page-title`, `.section-label` | 44-67, 1536-1560 |

> ⚠️ **Hindari `.neo-btn-primary`** — ada dua definisi `.neo-btn` yang tumpang tindih
> (`neo-mirai-home.css:2985` pill emas vs `penilaian-kinerja-neo.css:250` rounded-0.75rem).
> Karena `neo-mirai-home.css` dimuat setelah `app.css`, pasangan yang konsisten adalah
> `.neo-btn` (primer) + `.neo-btn-secondary`.

> Arbitrary value Tailwind ke CSS variable yang sudah ada **diizinkan** (bukan CSS baru):
> `bg-[var(--paper)]`, `text-[var(--ink)]`, `border-[var(--line)]`, `bg-[var(--gold)]`,
> `text-[var(--night)]`, `ring-[var(--gold)]`. Tailwind 4 memindai `resources/**/*.blade.php`
> (`@source` di `resources/css/app.css:5`), jadi class ini otomatis ter-generate.

### Ikon & font
- Ikon = **inline SVG** `fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"`.
  Tidak ada library ikon. Contoh lengkap ada di `resources/views/admin/publikasi/index.blade.php`.
- Font: `Chakra Petch` (display/body, sudah di-load layout publik) + `JetBrains Mono`
  (untuk angka timer, sudah di-load). Variabel: `--font-display`, `--font-mono`.
- CSS variables tema (`:root` di `resources/css/app.css:10-54`, juga inline di layout publik):
  `--paper`, `--paper-soft`, `--paper-deep`, `--ink`, `--ink-soft`, `--ash`, `--line`,
  `--gold`, `--gold-bright`, `--sun`, `--sun-deep`, `--night`, `--night-soft`, `--rice`,
  `--focus`, `--shadow`, `--ease`, `--font-display`, `--font-serif`, `--font-mono`.

### Lain-lain
- Upload file: `Storage::disk('public')->storeAs($dir, $filename, 'public')`
  (contoh: `app/Http/Controllers/PageController.php:5177`, `:5787`). Disk `public` root =
  `storage/app/public` (`config/filesystems.php:41`).
- Akses admin: `app/Http/Middleware/AdminAccess.php` — role `petugas, kasi, kasubbag, admin, superadmin, kepala`.
  Alias middleware `admin`, `kepala`, `profile.complete` didaftarkan di `bootstrap/app.php:19-23`.
- `EnsureProfileComplete` dijalankan untuk SEMUA route web (`bootstrap/app.php:26-28`) → pegawai
  yang profilnya belum lengkap akan diarahkan sebelum bisa buka halaman ujian. Tidak perlu
  penanganan khusus.
- Data user: tabel `users` kolom `id, name, nip, dept_id, kat_jabatan, role, ...`
  (`app/Models/User.php`). Model `User` sudah ada — jangan ubah except relasi bila perlu.
- Ikon sidebar admin & nav publik: `resources/views/admin/layouts/app.blade.php` (grup "Kelola"
  baris ~98-190) dan `resources/views/components/layouts/site-header.blade.php`.

---

## 5. Detail Desain Halaman Peserta

### 5.1 `ujian/index.blade.php` — daftar ujian
> **Diperbarui 2026-11-18 (sesi 5)** — lihat "Fase 8" untuk detail perubahan.

- Hero `.hero-page .neo-ujian-hero` background `ujian-bg.webp` (overlay terang krem semi transparan
  supaya tulisan di atas foto tetap mudah dibaca), ikon emas, eyebrow mono, judul gradasi
  "Ujian *Pegawai*", 2 CTA, 4 stat ringkasan.
- Daftar ujian `.neo-ujian-list` → kartu `.neo-ujian-card`: cover 16:9 (atau placeholder),
  badge status, kode, judul, deskripsi, chip meta (soal/durasi/nilai), tombol aksi.
  Tombol aksi per state:
  - `belum_mulai` → "Mulai Ujian" (`.neo-btn`)
  - `berjalan` → "Lanjutkan Ujian" + countdown sisa waktu (Alpine)
  - `selesai` → "Lihat Hasil & Pembahasan" (`.neo-btn .neo-btn-secondary`)
  - belum terbit / belum mulai → tombol nonaktif "Belum Tersedia"
- Empty state: `.neo-ujian-empty` + ikon + judul + teks.
- Kelas CSS khusus: `.neo-ujian-*` (di `resources/css/neo-mirai-home.css`).

### 5.2 `ujian/kerjakan.blade.php` — pengerjaan
> **Diperbarui 2026-11-18 (sesi 6)** — halaman pengerjaan & modal "Masuk ke Layar Penuh"
> didesain ulang (lihat "Fase 9").

Kerangka: `<x-layouts.app>` → `<main class="neo-mirai">` → hero → `.page-content .content-centered`.

Semua interaksi = **satu blok `<script>` inline Alpine** di akhir view (component `ujian`),
menggunakan `x-data="ujian(@js($payload), @js($remainingSeconds), @js($autoSubmitUrl))"`.

Layout: grid 2 kolom desktop `lg:grid-cols-[minmax(0,1fr)_20rem]`, 1 kolom mobile.

**Bilah atas (sticky)**: kiri = badge tipe + judul + durasi/jumlah soal; kanan = **timer MM:SS**
(`font-mono`) + ikon jam + tombol "Kumpulkan Jawaban" (`.neo-btn`) → konfirmasi via
`.neo-modal` + `.neo-modal-backdrop`. Sebelum submit, `flush()` antrean jawaban.

**Panel utama**: `.neo-ujian-panel` + `.neo-ujian-panel-head` + `.neo-ujian-panel-body`.
- Nomor soal "Soal 3 dari 20" + badge tipe + badge poin.
- **Pilihan ganda**: `<input type="radio" class="peer sr-only">` + `<label>` dengan
  `peer-checked:border-[var(--gold)] peer-checked:bg-[var(--paper-soft)]`. Label A/B/C/D
  dalam chip `rounded-full border border-[var(--line)]`. Radio tetap accessible (sr-only, bukan display:none).
- **Essay**: `<textarea class="neo-field-textarea">` + penghitung karakter + **upload lampiran**
  pakai komponen existing `.neo-upload-section` / `.neo-upload-grid` / `.neo-upload-card` /
  `.neo-upload-dropzone` / `.neo-upload-preview` / `.neo-upload-badge` / `.neo-upload-hint`.
  Form terpisah `POST /ujian/{id}/upload` (`enctype="multipart/form-data"`).
- Navigasi bawah: "Sebelumnya" (`.neo-btn .neo-btn-secondary`) / "Berikutnya" (`.neo-btn`).

**Sidebar**: `.neo-card` berisi grid kotak nomor (`grid gap-2`, 3 kolom mobile / 4 desktop).
Per kotak:
`flex h-10 items-center justify-center rounded-xl border border-[var(--line)] bg-[var(--paper)] text-sm font-mono text-[var(--ink-soft)]`
- dijawab → `bg-[var(--gold)] text-[var(--night)] border-[var(--gold)]`
- aktif → `ring-2 ring-[var(--gold)]`
State di-bind Alpine `:class`, kotak dirender `x-for` dari payload soal.
Legenda: `.neo-badge` + `.neo-badge-dot`.
Progress: "12 dari 20 soal terjawab" + bar `bg-[var(--paper-deep)]` / `bg-[var(--gold)]`
dengan `style="width: X%"` (satu-satunya inline style, nilainya dinamis server).

**State Alpine**:
```js
{
  soal: [...],            // payload
  current: 0,             // index soal aktif
  answers: {soal_id: pilihan_id},
  essays:   {soal_id: teks},
  pending:  {},           // antrean perubahan
  saving: false, savedAt: null, error: false,
  remaining: <detik>,
  violasi: <jumlah pelanggaran>,   // badge di bilah atas
  fsOpen: true, fsAktif: false, fsPernahAktif: false,  // modal & status layar penuh
  warnOpen: false, warnTitle, warnText,  // modal peringatan pelanggaran
  init() { ...setInterval timer...; document.addEventListener('visibilitychange', ...) },
  flush() { ...1 POST batch... },
  submit() { ... },
  aktifkanLayarPenuh() { ...request fullscreen tanpa mencatat pelanggaran... },
  kembaliKeUjian() { ...tutup modal + kembali ke layar penuh... }
}
```

**Pengawasan ujian (added 2026-11-16, diubah di sesi 9):**
- Saat halaman dibuka muncul modal **"Masuk ke Layar Penuh"** (`fsOpen`).
  **Tombol "Mulai Ujian"** memanggil `requestFullscreen` lalu `POST /ujian/{id}/mulai`
  (waktu ujian baru berjalan sejak tombol ini ditekan); **tombol "Kembali"** menutup
  halaman tanpa memulai sesi. Ujian **wajib** berjalan dalam mode layar penuh — tombol
  "Lanjut tanpa layar penuh" sudah dihapus (semula mencatat `tanpa_layar_penh`).
- Penjaga peramban dipasang di `pasangPenjaga()`: `visibilitychange`, `blur`, `fullscreenchange`,
  override `window.open`, intercept `a[target="_blank"]`, `copy`, `cut`, `paste`, `contextmenu`,
  `keydown` (Ctrl/Cmd + C/X/V/P), dan `beforeunload`.
- Semua kejadian dikirim ke `POST /ujian/{id}/pelanggaran` memakai `navigator.sendBeacon`
  (body JSON berisi `_token`, `jenis`, `keterangan`).
- Deduplikasi 3 detik per jenis supaya satu kejadian (mis. `blur` + `visibilitychange`) tidak
  terhitung dua kali.
- `POST /ujian/{id}/perangkat` dipanggil sekali saat halaman dibuka, header `X-Ujian-Perangkat`
  berisi sidik peramban (properti peramban + id acak di `localStorage`).

### 5.3 Guard waktu (server)
`ujian_peserta.ends_at = started_at + durasi_menit` dihitung server saat mulai.
Endpoint `simpanJawaban` / `uploadLampiran` / `submit` semuanya cek:
`now() > ends_at` → tutup sesi (`status=selesai`, `submitted_at=now()`, hitung skor) dan tolak
(409). Manipulasi jam lokal tidak menambah waktu.

### 5.4 Penilaian
- Saat submit: skor PG dihitung dengan **1 aggregate query** (ambil semua `pilihan.benar` untuk
  soal ujian ini, bandingkan dengan `ujian_jawaban.pilihan_id` yang tersimpan).
- Skor essay = input manual admin (kolom `skor` null sampai dinilai).
- `ujian_peserta.nilai` = total skor / total poin × 100 (2 desimal).
- Cache hasil `ujian:{id}:hasil:{user_id}` 60 dtk; di-invalidate saat admin ubah skor.

### 5.5 `ujian/hasil.blade.php`
> **Diperbarui 2026-11-18 (sesi 7)** — halaman hasil didesain ulang (lihat "Fase 10").

Struktur baru (kelas `.neo-hasil-*`):

- **Hero** `.neo-hasil-hero` → `.neo-hasil-hero-grid`:
  - kiri: eyebrow mono (`Hasil Ujian · {kode}`), judul ujian (display font), subjudul,
    chip meta (durasi ujian, jumlah soal, durasi pengerjaan), lencana status lulus/tidak
  - kanan: `.neo-hasil-ring` — lingkaran nilai SVG bergradien emas + angka nilai (display font)
- **Statistik** `.neo-hasil-stats` → 4 kartu `.neo-hasil-stat` (ikon + angka + label):
  Benar, Salah, Tidak Dijawab, Skor dari total poin.
- **Pelanggaran** `.neo-hasil-alert` (hanya bila `jumlahPelanggaran > 0`).
- **Pembahasan** — judul seksi `.neo-hasil-section` (ikon + garis pembatas) lalu **tombol besar
  `.neo-hasil-reveal`** untuk **expand/collapse seluruh bagian** (default **tersembunyi**):
  - tombol full-width, border emas putus-putus + latar gradien hangat, ikon panah dalam
    lingkaran gradien emas (berputar 180° saat terbuka), judul display font, keterangan, dan
    lencana jumlah soal; saat terbuka tombol melembut (garis solid, latar polos)
  - di bawahnya daftar kartu `.neo-hasil-card` per soal dalam satu blok `x-show` + fade:
    - kepala: nomor mono (`01`, `02`, …) + lencana tipe/status/skor (`.neo-hasil-badge`)
    - isi: pertanyaan (display font), petunjuk, lalu:
      - PG → kartu **Jawaban Anda** (ikon centang/silang/garis sesuai status) + kartu
        **Kunci Jawaban** (ikon kunci, gradasi emas)
      - Esai → kartu jawaban esai + tautan berkas lampiran (`.neo-hasil-file`)
- **Footer** `.neo-hasil-foot` → "Kembali ke Daftar Ujian" + "Cetak Hasil" (`window.print()`;
  pembahasan dibuka otomatis saat mencetak lalu dikembalikan seperti semula).

Data ringkasan (nilai akhir, jumlah benar/salah/skip, skor) tetap berasal dari
`UjianController::susunHasil()` — tidak ada perubahan pada logika penilaian.

---

## 6. Detail Desain Admin Panel

- `admin/ujian/index.blade.php`: `page-header` + tombol "Tambah Ujian", `stat-card`
  (Total Ujian / Aktif / Selesai / Peserta), tabel (kode, judul, status, jumlah soal, durasi,
  jumlah peserta, actions). `paginate(15)`.
- `admin/ujian/create.blade.php` & `edit.blade.php`: form identitas ujian
  (`card` + `form-group`/`form-input`/`form-select`) — kode, judul, deskripsi, materi, durasi,
  status, mulai/selesai, acak soal.
- `edit.blade.php` = 3 tab (Alpine):
  1. **Soal & Pilihan** — daftar soal; tambah soal (tipe, pertanyaan, petunjuk, poin);
     untuk PG: baris opsi + penanda kunci jawaban; tambah/hapus opsi.
  2. **Peserta** — pencarian + checklist user (filter `dept_id`), simpan terpilih.
  3. **Hasil** — tabel peserta (status, nilai); untuk essay ada input skor manual; rekap per soal.
- Tombol publish (`status=terbit`) hanya aktif jika minimal 1 soal dan ada peserta.

---

## 7. Data Flow

```
Admin ──simpan ujian/soal/pilihan/peserta──▶ ujian, ujian_soal, ujian_pilihan, ujian_peserta
                                              + Cache::forget("ujian:{id}:soal")

Pegawai ──GET /ujian───────────▶ daftar ujian sesuai penugasan        [1 query, paginated]
          ──GET /ujian/{id}────▶ 2 query (ujian + soal.pilihan dari cache) + 1 query sesi
                                   → 1 payload JSON ke browser
          ──POST /ujian/{id}/jawaban──▶ 1 request BATCH → upsert 1 transaksi
          ──POST /ujian/{id}/upload───▶ simpan lampiran essay (on-demand)
          ──POST /ujian/{id}/submit───▶ status=selesai + 1 aggregate query skor
          ──GET /ujian/{id}/hasil────▶ rekap + pembahasan             [cache 60 dtk]
Admin ──PUT /admin/ujian/jawaban/{id}/skor──▶ skor manual essay
```

---

## 8. Verifikasi (checklist testing)

### Sudah diverifikasi
- [x] `php artisan migrate` → 5 tabel baru terbentuk (`[34] Ran`)
- [x] `php artisan db:seed --class=UjianSeeder` → 1 ujian + 7 soal + 5 peserta
- [x] Route teregistrasi di `routes/web.php` (7 route `ujian.*`) dan `routes/admin.php` (17 route `admin.ujian.*`)
- [x] Menu "Ujian" ada di sidebar admin & header publik (desktop + mobile)
- [x] Cache pattern terverifikasi di kode: `Cache::remember("ujian:{id}:soal")` + 7× `Cache::forget` di controller admin; `Cache::remember("ujian:{id}:hasil:{user_id}")` + invalidasi di `UjianController:449` & `Admin\UjianController:478`
- [x] Auto-save batch + `sendBeacon` terverifikasi di `resources/views/ujian/kerjakan.blade.php:352-419` (debounce 1500ms, `flush()`, `visibilitychange`/`pagehide` → `navigator.sendBeacon`)
- [x] Guard waktu terverifikasi di `UjianController` (`now() > ends_at` → sesi ditutup)
- [x] `UjianTest` **22/22 hijau** dengan `DB_CONNECTION=mysql` (97 assertion) — termasuk regression test
      kebocoran kunci jawaban, acak soal, sesi kedaluwarsa, pencatatan pelanggaran, dan deteksi
      sesi ganda
- [x] Pengawasan ujian terpasang di halaman pengerjaan: penjaga peramban (fullscreen, tab baru,
      fokus, salin/tempel/cetak), endpoint `ujian.pelanggaran` & `ujian.perangkat`, rekap di admin
- [x] Kebocoran kunci jawaban sudah ditutup: `hasil()` menolak sesi berstatus selain `selesai`
      (regresi: peserta `belum_mulai`/`berjalan` diarahkan ke `ujian.kerjakan`)
- [x] `acak_soal` terimplementasi: urutan soal + pilihan berbeda per peserta, stabil saat reload;
      label pilihan diurutkan ulang (A, B, C…) mengikuti posisi tampil; penilaian tetap benar
- [x] **Import soal dari Excel**: `UjianImportTest` **10/10 hijau** (41 assertion) — import PG +
      esai, urutan `urut` berlanjut, impor dibatalkan bila kunci hilang/tidak sesuai opsi, baris
      `[CONTOH]` dilewati, impor dari halaman Tambah Ujian, template terunduh & bisa dibaca kembali

### Belum (perlu dijalankan)
- [ ] Manual browser:
  - [ ] Admin: buat ujian → tambah PG (5 opsi, 1 kunci) + essay → assign peserta → publish
  - [ ] Peserta: `/ujian` → Mulai Ujian → timer jalan → radio tersimpan → palette update
        → upload lampiran essay → submit → halaman hasil + skor
  - [ ] Durasi 1 menit → biarkan habis → auto-submit jalan, sesi terkunci
  - [ ] Refresh di tengah ujian → jawaban & sisa waktu tetap
  - [ ] 375px → grid 1 kolom, sidebar ke bawah
  - [ ] Admin centang "Acak Urutan Soal" → urutan soal & pilihan berubah, nomor soal tetap 1..N
- [ ] Manual browser (**pencarian peserta**):
  - [ ] `/admin/ujian/{id}/edit` → tab **Peserta** → ketik **Nomor Induk** (mis. `197007301992031001`)
        → pegawai yang cocok muncul
  - [ ] Ketik **nama** sebagian (mis. `YUSMARLI`) → muncul
  - [ ] Hasil pencarian & tabel "Peserta Terdaftar" menampilkan Nomor Induk
  - [ ] Tab **Hasil** menampilkan Nomor Induk di kolom peserta
- [ ] Manual browser (**fitur import Excel**):
  - [ ] `/admin/ujian/create` → unduh template → isi beberapa soal → unggah → soal muncul di tab Soal
  - [ ] File dengan baris bermasalah → pesan error menyebut nomor baris, tidak ada soal yang tersimpan
  - [ ] Unggah file `.csv`/`.txt` → ditolak oleh validasi dengan pesan jelas
  - [ ] Import kedua kali pada ujian yang sama → `urut` soal baru melanjutkan urutan lama
- [ ] Manual browser (**fitur pengawasan ujian**):
  - [ ] Modal layar penuh muncul; "Mulai Ujian" → browser fullscreen + waktu mulai berjalan;
        keluar fullscreen → badge pelanggaran bertambah dan baris `keluar_layar_penh` muncul di admin
  - [ ] "Kembali" → kembali ke daftar ujian, waktu ujian belum berjalan, tidak ada pelanggaran
  - [ ] Buka tab baru → badge bertambah, jenis `perpindahan_fokus` / `tab_baru` tercatat
  - [ ] `window.open` dari console diblokir (return `null`)
  - [ ] Ctrl+C / Ctrl+V / klik kanan / Ctrl+P diblokir; menyalin jawaban esai sendiri tetap boleh
  - [ ] Menutup halaman → peringatan `beforeunload` muncul
  - [ ] Upload lampiran essay **tidak** tercatat sebagai pelanggaran (dialog berkas dikecualikan)
  - [ ] Buka ujian dari peramban lain → peringatan "Sesi Ganda Terdeteksi" + baris `sesi_ganda`
  - [ ] Auto-save tetap 1 request per batch (penjaga tidak menambah request lain selain
        `POST /perangkat` saat halaman dibuka dan `POST /pelanggaran` per kejadian)
- [ ] Manual browser (**redesign sesi 7**):
  - [ ] Modal **"Kumpulkan Jawaban?"** — cincin progres menunjukkan persentase, kartu angka
        (Dijawab / Kosong / Total) benar, callatan merah muncul bila ada soal kosong
  - [ ] Modal **pelanggaran** — angka total pelanggaran sesuai badge di bilah atas, strip
        penanda sesuai jumlah pelanggaran
  - [ ] Klik **"Kembali ke Ujian"** → modal tertutup, browser **masuk layar penuh** lagi
        (bukan hanya menutup modal), dan callout mode layar penuh berubah jadi "layar penuh"
  - [ ] Pelanggaran yang terjadi **saat sudah di layar penuh** (mis. salin) → klik "Kembali
        ke Ujian" tetap di layar penuh dan **tidak menambah** jumlah pelanggaran
  - [ ] **Halaman hasil** — lingkaran nilai sesuai nilai akhir, lencana lulus/tidak benar,
        kartu jawaban (hijau = benar, merah = salah, garis = kosong) sesuai status per soal,
        tombol "Cetak Hasil" mencetak halaman
  - [ ] **Expand/collapse pembahasan** — saat halaman dibuka **pembahasan tersembunyi**; klik
        tombol emas di bawah judul "Pembahasan" menampilkan seluruh daftar soal (panah berputar
        180°, tombol melembut), label berpindah "Tampilkan Pembahasan" ↔ "Sembunyikan
        Pembahasan", dan "Cetak Hasil" tetap mencetak seluruh pembahasan
- [ ] Beban:
  - [ ] Network: buka `/ujian/{id}` → 1 document request, **tanpa polling**
  - [ ] Klik 5 radio berbeda → **1** POST setelah idle
  - [ ] Query log render → **8 query** (2 di antaranya dari middleware profil & layout, 6 fitur);
        submit → **4 query** (1 `SELECT` sesi + 1 `UPDATE` skor CASE + 1 `SUM` aggregate + 1 `UPDATE` peserta)
  - [ ] Response time < 500ms dengan 50 soal


---

## 9. Files yang Dibuat / Dimodifikasi

### Files Baru
| File | Purpose |
|------|---------|
| `app/Http/Controllers/UjianController.php` | Controller sisi peserta (7 method: index, kerjakan, simpanJawaban, uploadLampiran, submit, hasil, unduhBerkas) |
| `app/Http/Controllers/Admin/UjianController.php` | Controller sisi admin (17 method: CRUD ujian/soal/pilihan/peserta + hasil + updateSkor) |
| `app/Models/Ujian.php` | Model tabel `ujian` |
| `app/Models/UjianSoal.php` | Model tabel `ujian_soal` |
| `app/Models/UjianPilihan.php` | Model tabel `ujian_pilihan` |
| `app/Models/UjianJawaban.php` | Model tabel `ujian_jawaban` |
| `app/Models/UjianPeserta.php` | Model tabel `ujian_peserta` |
| `app/Models/UjianPelanggaran.php` | Model tabel `ujian_pelanggaran` (riwayat pelanggaran ujian) |
| `database/migrations/2026_10_28_000001_create_ujian_table.php` | Migrasi tabel `ujian` |
| `database/migrations/2026_10_28_000002_create_ujian_soal_table.php` | Migrasi tabel `ujian_soal` |
| `database/migrations/2026_10_28_000003_create_ujian_pilihan_table.php` | Migrasi tabel `ujian_pilihan` |
| `database/migrations/2026_10_28_000004_create_ujian_jawaban_table.php` | Migrasi tabel `ujian_jawaban` |
| `database/migrations/2026_10_28_000005_create_ujian_peserta_table.php` | Migrasi tabel `ujian_peserta` |
| `database/seeders/UjianSeeder.php` | Data contoh (1 ujian terbit, 5 PG, 2 essay, 5 peserta) |
| `resources/views/ujian/index.blade.php` | Halaman daftar ujian peserta |
| `resources/views/ujian/kerjakan.blade.php` | Halaman pengerjaan + blok Alpine inline |
| `resources/views/ujian/hasil.blade.php` | Halaman hasil & pembahasan |
| `resources/views/admin/ujian/index.blade.php` | Admin: daftar ujian |
| `resources/views/admin/ujian/create.blade.php` | Admin: form buat ujian |
| `resources/views/admin/ujian/edit.blade.php` | Admin: form edit + 3 tab (Soal/Peserta/Hasil) |
| `resources/views/admin/ujian/hasil.blade.php` | Admin: rekap hasil & penilaian manual |
| `resources/views/admin/ujian/_partials/pilihan-rows.blade.php` | Partial baris opsi pilihan ganda (form tambah soal) |
| `resources/views/admin/ujian/_partials/flash.blade.php` | Partial flash message |
| `tests/Feature/UjianTest.php` | 22 feature test (semua hijau dengan driver MySQL) |
| `app/Services/UjianSoalImportService.php` | Import soal dari Excel: parse, simpan (1 transaksi), buat template |
| `resources/views/admin/ujian/_partials/import-soal.blade.php` | Partial form import Excel + daftar format kolom + daftar error baris |
| `tests/Feature/UjianImportTest.php` | 10 feature test import Excel (semua hijau dengan driver MySQL) |
| `UJIAN_PROGRESS.md` | File progress ini |

### Files Dimodifikasi
| File | Perubahan |
|------|-----------|
| `routes/web.php` | Tambah grup route `ujian.*` di dalam `Route::middleware('auth')` (`:66-74`) |
| `routes/admin.php` | Tambah grup route `admin.ujian.*` (`:244-266`) |
| `resources/views/components/layouts/site-header.blade.php` | Menu "Ujian" di user-dropdown desktop (`:98`) & bottom-nav dropdown mobile (`:184`) |
| `resources/views/admin/layouts/app.blade.php` | Menu sidebar "Ujian" di grup "Kelola" (`:145`) + `menu-group-count` (`:90`) |

### Files Diperbarui Setelah Implementasi Awal (2026-11-16)
| File | Perubahan |
|------|-----------|
| `app/Http/Controllers/UjianController.php` | Guard status sesi di `hasil()` (tutup kebocoran kunci jawaban); helper `payloadSoalUntuk()` + `acakArray()` untuk `acak_soal`; `susunHasil()` menerima flag acak & `nomor` memakai posisi tampil; cek kedaluwarsa dipindah sebelum sesi dimulai ulang di `kerjakan()` |
| `tests/Feature/UjianTest.php` | 10 → 16 test: regression kebocoran kunci, urutan acak per peserta, urutan tetap saat acak nonaktif, penilaian tetap benar saat diacak, konsistensi seed, sesi kedaluwarsa; diformat ulang dengan Pint |

### Files Diperbarui — Pengawasan Ujian (2026-11-16, sesi 2)
| File | Perubahan |
|------|-----------|
| `app/Http/Controllers/UjianController.php` | Tambah `catatPerangkat()` + `catatPelanggaran()`; helper `sidikPerangkat()` & `simpanPelanggaran()`; kirim jumlah pelanggaran ke halaman pengerjaan dan halaman hasil |
| `app/Http/Controllers/Admin/UjianController.php` | Muat data pelanggaran per peserta + `stats['total_pelanggaran']` pada halaman hasil ujian |
| `app/Models/UjianPelanggaran.php` | Model baru (tabel `ujian_pelanggaran`) + konstanta `LABEL` jenis pelanggaran |
| `app/Models/Ujian.php` | Relasi `pelanggaran()` |
| `app/Models/UjianPeserta.php` | Relasi `pelanggaran()` + kolom `ip`, `user_agent`, `sidik_perangkat` |
| `routes/web.php` | Tambah route `ujian.perangkat` & `ujian.pelanggaran` |
| `resources/views/ujian/kerjakan.blade.php` | Penjaga peramban (fullscreen, tab baru, fokus, salin/tempel/cetak), badge & modal pelanggaran, baris aturan |
| `resources/views/ujian/hasil.blade.php` | Kartu pemberitahuan jumlah pelanggaran untuk peserta |
| `resources/views/admin/ujian/hasil.blade.php` | Kolom Pelanggaran + rincian per peserta + ringkasan total |
| `database/migrations/2026_11_16_000002_add_perangkat_to_ujian_peserta_table.php` | Migration baru: `ip`, `user_agent`, `sidik_perangkat` |
| `database/migrations/2026_11_16_000003_create_ujian_pelanggaran_table.php` | Migration baru: tabel `ujian_pelanggaran` |
| `tests/Feature/UjianTest.php` | 16 → 22 test (pencatatan pelanggaran, whitelist jenis, penolakan setelah sesi selesai, deteksi sesi ganda, peserta asing, penjaga di halaman) |

> Catatan: `app/Models/User.php` **tidak** diubah — relasi `ujian()` tidak dipakai karena
> query peserta diambil langsung via `ujian_peserta.user_id`.

### Files Diperbarui — Cover Ujian & Redesign Halaman Peserta (2026-11-18, sesi 5)
| File | Perubahan |
|------|-----------|
| `database/migrations/2026_11_18_000001_add_cover_path_to_ujian_table.php` | **Baru** — kolom `cover_path` (nullable) pada tabel `ujian` |
| `app/Models/Ujian.php` | `cover_path` masuk `$fillable`; accessor `cover_url` (URL publik disk `public`) |
| `app/Http/Controllers/Admin/UjianController.php` | `simpanCover()` (WebP q82, maks 1200px), `hapusCover()`, `store()`/`update()` menerima `cover` + `remove_cover`, `destroy()` menghapus berkas cover |
| `resources/views/admin/ujian/create.blade.php` | Area unggah cover + pratinjau gambar (JS `previewCover`) |
| `resources/views/admin/ujian/edit.blade.php` | Cover saat ini + unggah pengganti + checkbox "Hapus cover"; form identitas jadi `multipart/form-data` |
| `resources/views/admin/ujian/index.blade.php` | Thumbnail cover 48×48 pada kolom Ujian (fallback ikon) |
| `resources/views/ujian/index.blade.php` | **Redesign penuh**: hero baru (background `ujian-bg.webp`, ikon emas, stat ringkasan), kartu ujian ber-cover, chip meta, countdown, empty state baru |
| `resources/css/neo-mirai-home.css` | Tambahan section gaya `.neo-ujian-*` (hero, kartu, chip, empty state) — tanpa file CSS baru |
| `public/assets/img/template/ujian-bg.webp` | **Baru** — hasil konversi `ujian.jpg` (4,6 MB) → WebP 1920×596, 236 KB |
| `tests/Feature/UjianTest.php` | 3 test baru: unggah cover, hapus cover, cover tampil di daftar ujian peserta |

### Files Diperbarui — Redesign Modal Pelanggaran, Modal Kumpulkan Jawaban & Halaman Hasil (2026-11-18, sesi 7)
| File | Perubahan |
|------|---------|
| `resources/views/ujian/kerjakan.blade.php` | Modal **"Kumpulkan Jawaban?"** diganti dengan desain `.neo-ujian-submit-*` (cincin progres SVG, kartu angka, callout belum dijawab); modal **pelanggaran** diganti dengan desain `.neo-ujian-alert-*` (kepala gradasi + halo merah, penghitung total, strip penanda); tombol "Kembali ke Ujian" kini memanggil `kembaliKeUjian()`; helper Alpine `aktifkanLayarPenuh()` + state `fsPernahAktif`; callout mode layar penuh jadi dinamis |
| `resources/views/ujian/hasil.blade.php` | **Redesign penuh** — hero gradasi malam + lingkaran nilai SVG, chip meta, lencana status, 4 kartu statistik berikon, kartu pelanggaran, kartu soal `.neo-hasil-*` (nomor mono, lencana status, jawaban Anda vs kunci jawaban dengan ikon), tombol cetak hasil. **Expand/collapse satu tombol besar `.neo-hasil-reveal`** di bawah judul seksi "Pembahasan" (default **tersembunyi**; border emas putus-putus, ikon panah dalam lingkaran gradien emas berputar saat terbuka, lencana jumlah soal) untuk menampilkan/menampilkan seluruh daftar kartu soal via `x-show` + fade, state Alpine `buka`, `aria-expanded`; "Cetak Hasil" membuka pembahasan lebih dulu lalu mengembalikannya (`afterprint`) |
| `resources/css/neo-mirai-home.css` | Tambahan section gaya `.neo-ujian-alert-*`, `.neo-ujian-submit-*`, `.neo-hasil-*` (+ keyframe `neo-ujian-pop`, `neo-ujian-halo`) termasuk `.neo-hasil-reveal` + `-icon/-text/-title/-sub/-badge` — tanpa file CSS baru; `npm run build` sukses |
| `tests/Feature/UjianTest.php` | 3 assertion `assertDatabaseCount('ujian_pelanggaran', 0)` → cakupan ke `ujian_id` test sendiri; `test_cover_ujian_tampil_pada_daftar_ujian_peserta` tidak lagi membuat `ujian_peserta` ganda dan menulis `cover_path` manual (`Storage::put()` mengembalikan bool) |

> Catatan: `UjianTest` kini **27/27 hijau** (112 assertion) dan `UjianImportTest` **10/10 hijau**
> (41 assertion) dengan `DB_CONNECTION=mysql`. Tiga kegagalan lama (`ujian_pelanggaran` masih
> berisi baris dari uji manual di browser pada 2026-11-18) diperbaiki dengan pembatasan
> cakupan assertion, bukan dengan menghapus data.

### Files Diperbarui — Cover, Mulai Ujian Saat Tombol Ditekan & Modal Layar Penuh (2026-11-18, sesi 9)
| File | Perubahan |
|------|---------|
| `app/Http/Controllers/UjianController.php` | `kerjakan()` tidak lagi memulai sesi otomatis; **tambah `mulai()`** (daftar sidik perangkat + tulis `started_at`/`ends_at` + kirim `remaining`); helper `sesiBelumMulai()` dipakai sebagai penjaga di `simpanJawaban()`, `uploadLampiran()`, `submit()`, `catatPelanggaran()` (409 "Ujian belum dimulai."); `catatPerangkat()` tidak menyimpan sidik perangkat sebelum ujian dimulai |
| `routes/web.php` | Tambah route `POST ujian/{id}/mulai` → `ujian.mulai` |
| `resources/views/ujian/kerjakan.blade.php` | State Alpine `dimulai`/`memulai`/`startError` + method `mulaiSesi()`, `keluarLayarPenuh()`, `kembaliKeDaftarUjian()`; `tick()`/`timeText`/`catat()`/`beforeunload` hanya aktif setelah sesi berjalan; kaki modal layar penuh dipecah jadi `.neo-ujian-fs-actions` (2 tombol: "Kembali" & "Mulai Ujian"); pesan error mulai di `.neo-ujian-fs-error`; notifikasi baris atas punya kondisi "belum dimulai" |
| `resources/css/neo-mirai-home.css` | `.neo-ujian-card-img` jadi `position:absolute; inset:0; object-fit:cover` (cover memenuhi area); `.neo-ujian-fs-foot` jadi kolom + `.neo-ujian-fs-actions` + `.neo-ujian-fs-error`; ≤640px tombol modal layar penuh diperkecil & kepala modal diperkecil |
| `tests/Feature/UjianTest.php` | Helper `mulaiUjian()`; test yang butuh sesi berjalan disesuaikan; test baru `test_ujian_tidak_bisa_dimulai_oleh_bukan_peserta` & `test_jawaban_tidak_bisa_disimpan_sebelum_ujian_dimulai` |
| `UJIAN_PROGRESS.md` | Fase 12 + changelog sesi 9 |

> Catatan: **tidak ada test/Pint/browser yang dijalankan** untuk sesi ini — verifikasi manual
> dilakukan user sesuai instruksi. `php artisan view:cache` & `php -l` lolos.

### Files Diperbarui — Responsive Mobile & Perbaikan Modal Ujian (2026-11-18, sesi 8)
| File | Perubahan |
|------|---------|
| `resources/views/components/layouts/app.blade.php` | `<body>` mendapat kelas `neo-ujian-mode` bila route = `ujian.kerjakan` (dasar sembunyikan bottom nav & banner promo) |
| `resources/views/components/layouts/site-header.blade.php` | `@media (max-width: 900px)`: `body.neo-ujian-mode .bottom-nav { display: none !important; }` + padding bawah halaman jadi `calc(1rem + env(safe-area-inset-bottom, 0px))` |
| `resources/views/ujian/kerjakan.blade.php` | Bilah atas dua baris di layar kecil (badge dapat digeser, label tombol "Kumpulkan" pendek, status simpan baris sendiri); sidebar palet `order-first lg:order-none` + kelas `neo-ujian-side-hint` / `neo-ujian-side-submit`; backdrop modal kumpulkan diberi kelas `neo-ujian-submit-backdrop` |
| `resources/views/ujian/index.blade.php` | Padding hero dipindah dari inline style ke kelas `.neo-ujian-hero-pad` supaya bisa responsif |
| `resources/css/neo-mirai-home.css` | Backdrop modal ujian `z-index: 200` + safe-area; ketiga modal ujian jadi flex column dengan badan bisa digulir; section baru "Ujian - penyesuaian layar mobile & tablet" (1023/640/480 px): palet geser, navigasi dua kolom, modal ringkas, halaman hasil 2 kolom, banner promo disembunyikan saat ujian — tanpa file CSS baru |
| `UJIAN_PROGRESS.md` | Fase 11 + changelog sesi 8 |

> Catatan: **tidak ada test/Pint/browser yang dijalankan** untuk sesi ini — verifikasi manual
> dilakukan user sesuai instruksi. `php artisan view:cache` lolos ( syntaks Blade valid).

### Files Diperbarui — Pencarian Peserta berdasarkan Nomor Induk (2026-11-18)
| File | Perubahan |
|------|-----------|
| `app/Http/Controllers/Admin/UjianController.php` | `cariPeserta()` sekarang mencocokkan `name` + `nomor_induk` (sebelumnya `name` + `nip`); kolom `nip` (isi = NIK) tidak lagi jadi target; payload JSON mengembalikan `nomor_induk` (field `nip` & `role` dihapus karena tidak lagi dipakai view) |
| `resources/views/admin/ujian/edit.blade.php` | Placeholder & label kartu "Tambah Peserta" → "nama atau Nomor Induk (NIP)"; sub-label hasil pencarian & tabel "Peserta Terdaftar" menampilkan `nomor_induk` (sebelumnya `nip`) |
| `resources/views/admin/ujian/hasil.blade.php` | Rekap peserta menampilkan `nomor_induk` (sebelumnya `nip`) |
| `tests/Feature/UjianTest.php` | 22 → 24 test: `test_admin_bisa_mencari_peserta_berdasarkan_nomor_induk` & `test_admin_bisa_mencari_peserta_berdasarkan_nama` |

> Catatan penting: **kolom `users.nip` berisi NIK (16 digit), bukan NIP.** NIP/NUPTK asli ada di
> `users.nomor_induk` (unik, dipakai sebagai kunci login & join ke `tenaga_ktd`).
> `cariPeserta` adalah satu-satunya tempat di aplikasi yang memakai `users.nip`, sekarang sudah
> diselaraskan. Lihat `PROFILE_COMPLETION_PROGRESS.md` & `app/Http/Controllers/PageController.php:3099`.

### Files Diperbarui — Import Soal dari Excel (2026-11-17, sesi 3)
| File | Perubahan |
|------|-----------|
| `app/Http/Controllers/Admin/UjianController.php` | Tambah `importSoal()`, `templateSoal()`, helper `prosesImportSoal()`; `store()` menerima `file_soal` opsional; validasi `file_soal` (mimes xlsx/xls, max 5 MB) |
| `routes/admin.php` | Tambah route `admin.ujian.soal.import` & `admin.ujian.soal.template` |
| `resources/views/admin/ujian/create.blade.php` | Kartu "Import Soal dari Excel (Opsional)"; form jadi `enctype="multipart/form-data"` |
| `resources/views/admin/ujian/edit.blade.php` | Kartu import di tab Soal (form terpisah POST ke `admin.ujian.soal.import`) |

---

## 10. Out of Scope (sprint berikutnya)

- Notifikasi email/WhatsApp pengingat ujian
- Proctoring, ekspor hasil ke Excel/PDF
- Bank soal reusable lintas ujian (skema sekarang soal menempel pada satu ujian)

---

## 11. Changelog

### 2026-11-18 (sesi 9) — Cover fit-to-cover, mulai ujian saat tombol ditekan & modal layar penuh ringkas

- **Cover ujian (daftar peserta)** kini memenuhi area cover — `.neo-ujian-card-img` diposisikan
  absolut dengan `object-fit: cover`.
- **Waktu ujian tidak lagi berjalan sejak halaman dibuka.** Sesi baru dimulai setelah peserta
  menekan **"Mulai Ujian"** pada modal layar penuh melalui endpoint baru
  `POST /ujian/{id}/mulai`. Server mengembalikan sisa waktu sehingga jam di browser selalu sama
  dengan batas waktu server. Sebelum sesi berjalan, jawaban/lampiran/pelanggaran ditolak (`409`).
- **Modal layar penuh kini hanya 2 tombol: "Kembali" & "Mulai Ujian".** Tombol
  **"Lanjut tanpa layar penuh" dihapus** — ujian **wajib** berjalan dalam mode layar penuh.
  Bila peramban menolak permintaan layar penuh, sesi tidak dimulai dan pesan error tampil.
- **Tombol "Kembali"** mengembalikan peserta ke daftar ujian tanpa memulai sesi dan tanpa
  mencatat pelanggaran.
- **Sidik perangkat** baru didaftarkan saat ujian benar-benar dimulai, sehingga perpindahan
  perangkat sebelum menekan tombol mulai tidak salah dianggap sebagai sesi ganda.
- **Modal layar penuh di mobile** diringkas: dua tombol aksi jadi kecil dengan label pendek
  ("Tanpa layar penuh" / "Layar Penuh"), area informasi (aturan 01–03) jadi lebih luas.
- **Tombol "Kembali"** pada modal layar penuh mengembalikan peserta ke daftar ujian tanpa
  memulai sesi dan tanpa mencatat pelanggaran.
- **Test** `tests/Feature/UjianTest.php` disesuaikan (helper `mulaiUjian()` + 2 test baru).

### 2026-11-18 (sesi 8) — Responsive mobile, perbaikan modal & bottom nav saat ujian

- Permintaan user:
  1. halaman `/ujian` dibuat **responsif untuk semua macam layar mobile/Android**;
  2. saat peserta klik **"Mulai Ujian"**, modal **"Masuk ke Layar Penuh"** tombol
     **"Masuk Layar Penuh"** tertutup **bottom mobile nav**;
  3. **bottom mobile nav disembunyikan** di halaman ujian/pengisian jawaban.
- **Bottom nav disembunyikan saat ujian:** `<body>` mendapat kelas `neo-ujian-mode` bila route =
  `ujian.kerjakan` (`resources/views/components/layouts/app.blade.php`), lalu
  `body.neo-ujian-mode .bottom-nav { display: none !important; }` di style site-header
  (hanya relevan di `@media (max-width: 900px)`). Padding bawah halaman yang biasanya menyisakan
  ruang untuk bottom nav (`70px`) diganti jadi `calc(1rem + env(safe-area-inset-bottom, 0px))`.
- **Modal tidak lagi tertutup bottom nav:** backdrop modal ujian (`.neo-ujian-fs-backdrop`,
  `.neo-ujian-submit-backdrop`, `.neo-ujian-alert-backdrop`) dinaikkan ke `z-index: 200`
  (di atas `.bottom-nav` z-100) dan memakai `padding-bottom` dengan `env(safe-area-inset-bottom)`.
- **Modal jadi bisa digulir:** ketiga modal ujian kini flex column dengan
  `max-height: calc(100dvh - 2rem)` (fallback `92vh`), kepala & kaki modal `flex: 0 0 auto`,
  badan modal `overflow-y: auto`. Sebelumnya `overflow: hidden` menimpa `overflow-y: auto`
  sehingga isi modal terpotong dan tombol footer tidak terjangkau di layar pendek.
- **Responsive halaman pengerjaan:** bilah atas dua baris di layar kecil (judul + badge dapat
  digeser / timer + tombol "Kumpulkan" / status simpan), palet soal jadi strip horizontal yang
  dapat digeser dan dipindah ke atas panel soal di ≤1023px, navigasi soal dua kolom di ≤480px,
  serta ikon/padding/notifikasi modal diperkecil di ≤640px.
- **Responsive daftar & halaman hasil:** padding hero dipindah ke kelas `.neo-ujian-hero-pad`;
  halaman hasil memakai lingkaran nilai terpusat, statistik 2 kolom, dan tombol pembahasan yang
  boleh membungkus. Banner promo Android disembunyikan saat mengerjakan ujian.
- CSS di `resources/css/neo-mirai-home.css` (tanpa file CSS baru), `php artisan view:cache` lolos.
  **Verifikasi manual di browser tidak dijalankan** (sesuai instruksi user).

### 2026-11-18 (sesi 7) — Redesign modal pelanggaran, modal kumpulkan jawaban & halaman hasil

- Permintaan user:
  1. modal **"Total Pelanggaran pada sesi ini"** dipercantik (font & ikon menarik), dan tombol
     **"Kembali ke Ujian"** harus mengembalikan peserta ke halaman ujian **dengan layar penuh**;
  2. modal **"Kumpulkan Jawaban?"** dipercantik;
  3. **halaman hasil** dipercantik tanpa keluar dari tema NEO MIRAI.
- **Modal pelanggaran** (`.neo-ujian-alert-*`): kepala gradasi malam + halo merah beranimasi,
  ikon segitiga peringatan, eyebrow mono, penghitung total pelanggaran angka mono besar,
  strip penanda tiap pelanggaran, dan tombol "Kembali ke Ujian" lebar penuh.
- **Layar penuh saat kembali:** helper Alpine `aktifkanLayarPenuh()` (meminta `requestFullscreen`
  tanpa mencatat pelanggaran) dipakai oleh `kembaliKeUjian()`; klik tombol adalah gestur pengguna
  sehingga browser mengizinkan permintaan layar penuh. State baru `fsPernahAktif` menandai bahwa
  peserta pernah masuk layar penuh. Callout di bawah bilah atas kini menyesuaikan dengan
  status layar penuh (`x-show="fsAktif"`).
- **Modal "Kumpulkan Jawaban?"** (`.neo-ujian-submit-*`): kepala gradasi malam + ikon centang emas,
  **cincin progres SVG** bergradien emas, 3 kartu angka (Dijawab / Kosong / Total Soal),
  callout merah bila ada soal kosong, catatan "jawaban aman di server", dan tombol utama yang
  berubah jadi "Mengirim…" saat proses berjalan.
- **Halaman hasil** (`resources/views/ujian/hasil.blade.php`): hero gradasi malam dengan eyebrow,
  judul, chip meta (durasi, jumlah soal, durasi pengerjaan), lencana lulus/tidak, dan **lingkaran
  nilai SVG**; 4 kartu statistik berikon; kartu peringatan pelanggaran; kartu soal `.neo-hasil-*`
  (nomor mono, lencana tipe/status/skor, pertanyaan, petunjuk, kartu Jawaban Anda vs Kunci Jawaban
  dengan ikon centang/silang, lampiran esai); tombol "Kembali ke Daftar Ujian" + "Cetak Hasil".
- **Expand / collapse pembahasan** di halaman hasil: **satu tombol besar `.neo-hasil-reveal`** di
  bawah judul seksi "Pembahasan" menyembunyikan/menampilkan **seluruh** daftar kartu soal (bukan
  per soal). **Default tersembunyi** saat halaman dibuka. Tombol full-width dengan border emas
  putus-putus, latar gradien hangat, ikon panah dalam lingkaran gradien emas (berputar 180° saat
  terbuka), judul display font + keterangan + lencana jumlah soal; isi pembahasan memakai
  `x-show` + fade, dan "Cetak Hasil" membuka pembahasan lebih dulu lalu mengembalikan susunan
  semula setelah selesai dicetak.
- Font & ikon: tetap **Chakra Petch** (display) + **Azeret/JetBrains Mono** (angka) + ikon SVG
  inline gaya Lucide — tidak ada font atau ikon baru di luar tema NEO MIRAI.
- CSS: prefix `.neo-ujian-alert-*`, `.neo-ujian-submit-*`, `.neo-hasil-*` di
  `resources/css/neo-mirai-home.css` (tanpa file CSS baru), `npm run build` sukses,
  `php artisan view:cache` lolos.
- **Perbaikan test (pre-existing):** tiga assertion `assertDatabaseCount('ujian_pelanggaran', 0)`
  kini dibatasi ke `ujian_id` test sendiri (sebelumnya ikut menghitung baris uji manual di
  database dev), dan `test_cover_ujian_tampil_pada_daftar_ujian_peserta` tidak lagi membuat
  `ujian_peserta` ganda maupun menulis `cover_path` dari nilai balik `Storage::put()` (bool).
  `UjianTest` **27/27 hijau** (112 assertion), `UjianImportTest` **10/10 hijau** (41 assertion).

### 2026-11-18 (sesi 6) — Redesign halaman pengerjaan & modal layar penuh

- Permintaan user: setelah klik **"Mulai Ujian"**, modal **"Masuk ke Layar Penuh"** dan halaman
  pengerjaan ujian dipercantik (font & ikon menarik) tanpa keluar dari tema NEO MIRAI.
- **Modal "Masuk ke Layar Penuh"** (`.neo-ujian-fs-*`): kepala gradasi malam + ikon monitor emas
  berglow, 3 aturan sebagai kartu bernomor, catatan merah soal pencatatan pelanggaran, dan
  footer 2 tombol (sekunder "Lanjut tanpa layar penuh", utama "Masuk Layar Penuh").
- **Halaman pengerjaan**: bar atas sticky + garis progres jawaban, timer pill yang berkedip
  merah ≤ 60 detik, callout mode layar penuh, panel soal `.neo-ujian-panel`, pilihan jawaban
  kartu `.neo-ujian-option` (chip huruf jadi gradasi emas + centang saat dipilih), palet soal
  `.neo-ujian-palette`, bar progres gradasi emas, tombol navigasi berikon; modal "Kumpulkan
  Jawaban" dan modal peringatan pelanggaran ikut dirapikan.
- Font & ikon: tetap **Chakra Petch** (display) + **JetBrains Mono** (angka/mono) + ikon SVG
  inline gaya Lucide — tidak ada font atau ikon baru di luar tema.
- CSS: prefix `.neo-ujian-*` di `resources/css/neo-mirai-home.css` (tanpa file CSS baru),
  `npm run build` sukses, `php artisan view:cache` lolos.

### 2026-11-18 (sesi 5) — Cover ujian & redesign halaman peserta

- Permintaan user:
  1. header halaman `/ujian` dibikin sama seperti halaman lain (`/pelayanan`, `/publikasi`) dengan
     background `public/assets/img/template/ujian.jpg` yang **dikonversi & dikompresi ke WebP**
     supaya ringan di server;
  2. desain halaman `/ujian` dipercantik (font & ikon menarik) tanpa keluar dari tema NEO MIRAI;
  3. daftar ujian menampilkan **cover ujian**, termasuk facility **upload cover di admin**.
- **Gambar header:** `ujian.jpg` (4,6 MB, 3712×1152) → `ujian-bg.webp` (1920×596, **236 KB**,
  quality 70) memakai **sharp** (`npm` dependency project). Penghematan ±95%.
- **Desain halaman `/ujian` (public):** hero bergaya NEO MIRAI (overlay terang krem semi transparan,
  ikon emas berglow, judul gradasi, CTA, 4 stat ringkasan) + kartu ujian ber-cover 16:9, badge status
  berwarna, chip meta, countdown sisa waktu, dan empty state baru. Semua memakai font tema
  (Chakra Petch + JetBrains Mono) dan ikon SVG inline gaya Lucide — tidak ada font/ikon baru
  yang keluar dari tema.
- **Cover ujian:**
  - migration `2026_11_18_000001_add_cover_path_to_ujian_table.php` (kolom `cover_path`);
  - `App\Models\Ujian` — `$fillable` + accessor `cover_url`;
  - `Admin\UjianController` — `simpanCover()` (Intervention Image/GD → WebP q82, lebar maks 1200px,
    path `ujian/cover/{uuid}.webp`), `hapusCover()` (file lama dihapus saat diganti/dihapus),
    `store()`/`update()` menerima `cover` (maks 4 MB) dan `remove_cover`;
  - admin `create`/`edit` — area unggah + pratinjau + opsi hapus; `index` — thumbnail cover;
  - public `ujian/index.blade.php` — cover ditampilkan di kartu (fallback placeholder "Tanpa Cover").
- **Test:** 3 test baru di `UjianTest` (unggah cover, hapus cover, cover tampil di daftar peserta).
  Catatan: suite lokal tidak bisa jalan polos karena PHP CLI tidak punya `pdo_sqlite` — jalankan
  dengan `DB_CONNECTION=mysql DB_DATABASE=kemenagtd_db php vendor/bin/phpunit tests/Feature/UjianTest.php`.
- **Catatan gaya:** tidak menambah file CSS baru (sesuai section 12); semua gaya `.neo-ujian-*`
  ditambahkan di `resources/css/neo-mirai-home.css` dan `npm run build` sukses.

### 2026-11-18 — Pencarian peserta berdasarkan nama & Nomor Induk

- Permintaan user: pada halaman `/admin/ujian/{id}/edit` → tab **Peserta** → kartu **Tambah Peserta**,
  admin harus bisa mencari pegawai berdasarkan **nama dan nomor_induk**.
- **Bug yang ditemukan & diperbaiki:** `cariPeserta()` mencocokkan kolom `users.nip`. Kolom itu
  sebenarnya berisi **NIK (16 digit KTP)**, bukan NIP — dibuktikan dari `PageController.php:3099-3108`
  (`// NIK: prioritas dari input 'nik', fallback ke 'nip'`) dan `PROFILE_COMPLETION_PROGRESS.md:23`.
  **NIP/NUPTK asli disimpan di `users.nomor_induk`** (kolom unik, dipakai sebagai kunci login di
  `AuthController.php:33,64`, join ke `tenaga_ktd`, dan folder foto profil).
  Konsekuensi: **mencari peserta dengan NIP selalu gagal** (0 hasil). Contoh data nyata:
  user id 2 — `nip`=1303043007700002, `nomor_induk`=197007301992031001; pencarian versi lama
  dengan `197007301992031001` mengembalikan 0 baris.
- **Perubahan:**
  - `cariPeserta()` → mencocokkan `name` + `nomor_induk`; kolom `nip` tidak lagi jadi target.
    Select & payload JSON mengikuti (`nomor_induk` dikembalikan, `nip` dihapus).
  - `edit.blade.php` → placeholder & label kartu "Tambah Peserta" menjadi "nama atau Nomor Induk (NIP)";
    sub-label hasil pencarian & tabel "Peserta Terdaftar" menampilkan Nomor Induk.
  - `hasil.blade.php` → rekap peserta menampilkan Nomor Induk.
  - Ketiga tampilan ini adalah satu-satunya di area admin ujian yang masih memakai `users.nip`;
    sekarang konsisten dengan seluruh admin screen lain (`admin/users/index.blade.php:175`,
    `admin/units/_pimpinan.blade.php`, `admin/penilaian-kinerja/*`, dll.).
- **Test:** `UjianTest` 22 → **24 test, 102 assertion, semua hijau**. Test baru:
  `test_admin_bisa_mencari_peserta_berdasarkan_nomor_induk` & `test_admin_bisa_mencari_peserta_berdasarkan_nama`.
  `UjianImportTest` tetap 10/10 hijau. `composer pint` lolos.

### 2026-11-17 (sesi 3) — Import soal dari Excel

- Permintaan user: halaman **Tambah Ujian** (`/admin/ujian/create`) perlu cara cepat memasukkan soal
  dari file Excel. Implementasi:
  - **Service baru** `App\Services\UjianSoalImportService`:
    - `parse()` membaca **sheet pertama** file `.xlsx`/`.xls`, memetakan baris pertama sebagai
      header kolom, lalu memvalidasi tiap baris (tipe, pertanyaan, poin, minimal 2 opsi, kunci).
      Nama kolom alternatif diterima (`soal`, `jenis`, `opsi_a`, `jawaban_benar`, …).
      Baris kosong dan baris contoh berawalan `[CONTOH]` dilewati.
    - `simpan()` menulis soal + pilihan dalam satu transaksi; `urut` dilanjutkan dari soal
      terakhir pada ujian tersebut.
    - `template()` menghasilkan workbook 2 sheet: `Soal` (header + 2 baris contoh) dan `Petunjuk`;
      sheet `Soal` dijadikan sheet aktif agar file yang diunduh langsung bisa diisi.
  - **Impor bersifat all-or-nothing**: bila ada baris bermasalah, tidak ada soal yang tersimpan dan
    admin menerima daftar pesan error beserta nomor baris (`Baris 7: kunci jawaban wajib diisi …`).
  - **Admin UI**:
    - Kartu "Import Soal dari Excel (Opsional)" di halaman Tambah Ujian; file diunggah bersama
      penyimpanan identitas ujian, soal langsung masuk setelah ujian dibuat.
    - Kartu import di tab **Soal** pada halaman kelola ujian untuk menambah soal di kemudian hari.
    - Partial bersama `admin.ujian._partials.import-soal` berisi input file, tombol unduh template,
      ringkasan format kolom, dan daftar error import.
    - Validasi file: `mimes:xlsx,xls`, maksimal 5 MB.
  - **Route baru**: `POST /admin/ujian/{id}/soal/import` dan `GET /admin/ujian/soal/template`.
  - **Test**: `tests/Feature/UjianImportTest.php` — 10 test, 41 assertion, semua hijau
    (import PG + esai, urutan lanjut, batal bila kunci salah, baris contoh dilewati, impor dari
    halaman tambah ujian, template terunduh & terbaca kembali). `UjianTest` tetap 22/22 hijau.
- Bug yang ditemukan saat uji manual round-trip template: **sheet aktif pada template adalah
  `Petunjuk`**, sehingga file hasil unduhan tidak terbaca sebagai tabel soal. Diperbaiki dengan
  `setActiveSheetIndex(0)` saat membuat template dan pembacaan memakai sheet pertama.

### 2026-11-16 (sesi 2) — Pencegahan membuka tab baru saat ujian

- **Fitur pengawasan ujian (anti-pelanggaran)** diimplementasikan penuh sesuai keputusan user:
  peringatan + log saja, fullscreen wajib dengan toleransi, blokir salin/klik kanan/tempel/cetak.
- **Sisi peramban** (`resources/views/ujian/kerjakan.blade.php`):
  - Modal **"Mulai Ujian dalam Mode Layar Penuh"** muncul saat halaman dibuka. Peserta bisa masuk
    fullscreen atau melanjutkan tanpa fullscreen (dicatat sebagai `tanpa_layar_penh`).
  - Deteksi perpindahan fokus: `visibilitychange` (tab/window lain) + `blur` (jendela kehilangan
    fokus) → jenis `perpindahan_fokus`. Dialog pemilih berkas dikecualikan agar upload lampiran
    tidak dianggap pelanggaran.
  - `window.open` di-override dan tautan `target="_blank"` dicegat → jenis `tab_baru`.
  - `fullscreenchange` → keluar dari fullscreen tercatat sebagai `keluar_layar_penh`.
  - Blokir `copy`, `cut`, `paste`, `contextmenu`, dan `Ctrl/Cmd+P`. Menyalin **jawaban esai sendiri**
    masih diizinkan (hanya isi soal yang diblokir).
  - `beforeunload` menampilkan peringatan browser saat meninggalkan halaman (dilewati setelah ujian
    dikumpulkan).
  - Badge jumlah pelanggaran di bilah atas dan modal peringatan untuk tiap kejadian.
  - Semua laporan pelanggaran dikirim memakai `navigator.sendBeacon` agar tetap terkirim ketika tab
    ditutup atau browser tidak sempat menyelesaikan `fetch`.
- **Sisi server**:
  - `POST /ujian/{id}/pelanggaran` — menyimpan pelanggaran dengan whitelist jenis, menolak sesi yang
    sudah `selesai` (409) dan jenis yang tidak dikenal (422).
  - `POST /ujian/{id}/perangkat` — mendaftarkan sidik perangkat (hash `sha256(app_key + sidik
    peramban)`). Perangkat yang berbeda dari perangkat memulai sesi dicatat sebagai `sesi_ganda`
    dan dijawab 409.
  - `UjianPeserta` mendapat kolom `ip`, `user_agent`, `sidik_perangkat` (migration baru).
- **Sisi admin**: halaman `admin/ujian/hasil` menampilkan kolom **Pelanggaran** per peserta
  (badge jumlah + rincian jenis, waktu, IP) dan ringkasan total di atas tabel.
- **Sisi peserta**: halaman `ujian/hasil` menampilkan kartu pemberitahuan bila ada pelanggaran.
- **`UjianTest` 16 → 22 test, 97 assertion, semua hijau.** Test baru: pencatatan pelanggaran &
  tampil di admin, jenis tak dikenal ditolak, pencatatan ditolak setelah sesi selesai, perangkat
  sama/differen (sesi ganda), peserta bukan peserta ujian ditolak, dan halaman pengerjaan memuat
  penjaga peramban.
- Catatan: `routes/web.php` masih gagal `pint --test` dengan fixer `ordered_imports` — **pre-existing**,
  bukan hasil perubahan fitur ini (dicek dengan `git stash`).

### 2026-11-16
- **Perbaikan kebocoran kunci jawaban (penting).** `UjianController::hasil()` hanya mengecek keberadaan
  baris `ujian_peserta`, tanpa memeriksa status sesi. `susunHasil()` selalu mengisi `kunciLabel`/
  `kunciTeks` dan view merendernya tanpa kondisi, sehingga peserta berstatus `belum_mulai` maupun
  `berjalan` bisa membuka `/ujian/{id}/hasil` dan melihat seluruh kunci jawaban (HTTP 200).
  Sudah terbukti lewat test, sekarang ditutup: sesi selain `selesai` diarahkan ke `ujian.kerjakan`.
  Regression test: `test_halaman_hasil_tidak_membocorkan_kunci_sebelum_ujian_selesai`.
- **Implementasi `acak_soal`.** Sebelumnya field ada di skema + toggle di form admin, tetapi tidak
  dipakai sama sekali (tidak ada pengacakan). Sekarang urutan soal **dan** urutan pilihan diacak
  per peserta memakai seed deterministik `ujian:{id}:user:{user_id}` (Fisher-Yates dengan
  `hash('sha256')`, tanpa menyentuh state generator acak global). Konsekuensinya:
  - urutan tetap sama saat halaman dimuat ulang, dan berbeda antar peserta;
  - label pilihan diurutkan ulang (A, B, C…) mengikuti posisi tampil pada peserta tersebut;
  - `nomor` soal pada halaman hasil memakai posisi tampil, bukan `urut` asli;
  - penilaian tetap benar karena yang disimpan ke DB tetap `pilihan_id` aslinya.
  Helper baru: `payloadSoalUntuk()` + `acakArray()` di `UjianController`.
- **Perbaikan urutan cek waktu.** Di `kerjakan()`, cek kedaluwarsa dipindahkan **sebelum** blok
  "mulai sesi". Sebelumnya, sesi berstatus `belum_mulai` yang sudah punya `ends_at` di masa lalu
  akan me-reset `started_at`/`ends_at` sehingga durasi ujian ikut diperpanjang.
- **`UjianTest` 10 → 16 test, 67 assertion, semua hijau.** Record:blockir `pdo_sqlite` yang
  sebelumnya tertulis di dokumen ini **tidak akurat** — test memakai `DatabaseTransactions`
  (bukan `RefreshDatabase`) sehingga tidak pernah memigrasi; memakai sqlite `:memory:` tabelnya
  tidak akan pernah ada. Fix yang benar: `DB_CONNECTION=mysql` saat menjalankan suite.
- `composer pint` dijalankan — `tests/Feature/UjianTest.php` diformat ulang. Semua file fitur
  ujian kini lolos.
- Target query di dokumen dikoreksi: render **8** query (bukan ≤4), submit **4** query (bukan ≤3).
  Klaim "skor dihitung dengan 1 aggregate query" tetap **benar** (`UPDATE ... CASE` + `SUM`).

### 2026-10-29
- Implementasi **seluruh** fase kode feature selesai: 5 migrasi, 5 model, 2 controller (admin 17
  method + peserta 7 method), 9 view (3 peserta + 6 admin termasuk 2 partial), 24 route, 2 menu,
  1 seeder, 1 test file.
- Migrasi sudah di-run di DB — `migrate:status` → 5 tabel `[34] Ran`.
- Seeder sudah di-run — terverifikasi 1 ujian / 7 soal / 5 peserta.
- Route `admin.ujian.peserta.cari` (pencarian peserta) ditambah — belum ada di plan awal.
- Ditemukan & dibersihkan file scratch testing image upload yang tertinggal di root repo:
  `tmp_filebag_test.php`, `tmp_ii_test.php`, `tmp_image_test.php`, `tmp_image_test2.php`,
  `tmp_upload/` (semuanya tidak terkait fitur ujian — sisa debugging PPID/publikasi 2026-10-26).
- **`php artisan test` terblokir** — 10/10 `UjianTest` error `"could not find driver"`.
  Root cause: `phpunit.xml` menetapkan `DB_CONNECTION=sqlite`, sementara PHP lokal hanya terinstall
  `pdo_mysql` (cek `php -m | grep -i sqlite` → kosong). Bukan bug fitur.
- `UJIAN_PROGRESS.md` diperbarui: checklist fase 2–4 disinkronkan dengan kondisi aktual, section
  routeoting, section verifikasi, section file, dan changelog.

### 2026-10-28
- Plan disetujui user: full stack, soal dari admin panel, 4 fitur wajib, prioritas ringan untuk user & server.
- Eksplorasi codebase selesai: konfirmasi tidak ada fitur ujian existing; inventaris kelas
  NEO MIRAI + pola layout publik/admin + konvensi upload file.
- Migration 5 tabel (`ujian`, `ujian_soal`, `ujian_pilihan`, `ujian_jawaban`, `ujian_peserta`)
  beserta index-nya dibuat.
- 5 Eloquent model dibuat: `Ujian`, `UjianSoal`, `UjianPilihan`, `UjianJawaban`, `UjianPeserta`.
- Progress file ini dibuat (permintaan user: dokumentasi detail agar bisa dilanjutkan dev/AI lain).

---

## 12. TODO / Catatan untuk melanjutkan

- **Langkah berikutnya (urutan prioritas):**
  1. Uji manual di browser sesuai section 8 (timer, autosave batch, upload, submit, guard waktu,
     acak soal, **pengawasan ujian**, **import Excel**).
  2. Uji beban (target 1 request per batch jawaban, tanpa polling, response < 500ms dgn 50 soal).
  3. Commit ke branch `feature/ujian-peserta` → PR ke `main` (perubahan saat ini masih di `main`).
  4. Fitur yang masih jadi groundwork saja (belum dipakai): kolom `ujian.jumlah_soal_diambil`
     (migration `2026_11_16_000001`) untuk mengambil sejumlah soal dari bank soal.
  5. Import Excel: peningkatan yang mungkin dibutuhkan — pratinjau (preview) sebelum disimpan, import
     peserta dari Excel, dan **ekspor hasil ujian ke Excel** (masih di luar scope).
- Cara menjalankan test (sudah terverifikasi hijau):
  ```bash
  DB_CONNECTION=mysql DB_DATABASE=kemenagtd_db php vendor/bin/phpunit tests/Feature/UjianTest.php
  DB_CONNECTION=mysql DB_DATABASE=kemenagtd_db php vendor/bin/phpunit tests/Feature/UjianImportTest.php
  ```
  Jangan `php artisan test` polos — `phpunit.xml` memaksa `DB_CONNECTION=sqlite` dan PHP lokal
  tidak punya `pdo_sqlite`.
- Saat melanjutkan, **baca section 2 & 4 dulu** (arsitektur + pola existing) supaya tidak
  membuat ulang komponen/CSS yang sudah ada.
- Jangan menambah file CSS baru. Kalau butuh styling, pakai utility Tailwind dengan
  arbitrary value ke `--paper/--ink/--gold/--line/--night` atau kelas `.neo-*` yang sudah ada.
- Kalau menambahkan kolom/tabel baru → update section 2.1 + section 11 (changelog).
- Kalau mengubah desain halaman → update section 5 + 6.
- Update section 3 (checklist) setiap task selesai.
- Kalau menambah/mengubah file → update section 9.
