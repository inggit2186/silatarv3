# Progress Halaman Peserta Ujian (SILATAR V2)

> File ini adalah **catatan kerja hidup** untuk developer lain / AI lain. Isi dokumen ini menjelaskan
> **apa yang sudah jadi, apa yang belum, keputusan desain, dan langkah berikutnya** supaya pekerjaan
> bisa dilanjutkan tanpa membaca ulang seluruh plan.
>
> Plan lengkap (disetujui user): `C:\Users\Admin\.claude\plans\misty-waddling-pine.md`
> (salinan ringkas juga ada di bagian "Arsitektur" bawah).

## Status: DALAM PROGRES (implementasi kode selesai, verifikasi manual pending)

**Tanggal mulai:** 2026-10-28
**Update terakhir:** 2026-11-16 (sesi 2 — pengawasan ujian)
**Branch:** `main`

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
ujian                 id, kode(uniq), judul, deskripsi, materi, durasi_menit, status(draft|terbit|selesai),
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
  - [x] `storePeserta` / `destroyPeserta` + `cariPeserta` (pencarian user via AJAX)
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
- Hero `.hero-page` (`x-ui.page-hero`) judul "Ujian Pegawai".
- Statistik `.neo-grid-4` + `.neo-stat-card`: Total Ujian / Sedang Berjalan / Belum Dimulai / Selesai.
- Kartu per ujian (`.neo-card`): badge status, judul, materi ringkas, jumlah soal, durasi, tenggat.
  Tombol aksi per state:
  - `belum_mulai` → "Mulai Ujian" (`.neo-btn`)
  - `berjalan` → "Lanjutkan Ujian" + sisa waktu
  - `selesai` → "Lihat Hasil" (`.neo-btn .neo-btn-secondary`)
  - belum terbit / belum jadi peserta → tombol nonaktif "Belum Tersedia"
- Empty state: `.neo-empty` + `.neo-empty-title` + `.neo-empty-text` + ikon SVG besar.

### 5.2 `ujian/kerjakan.blade.php` — pengerjaan
Kerangka: `<x-layouts.app>` → `<main class="neo-mirai">` → hero → `.page-content .content-centered`.

Semua interaksi = **satu blok `<script>` inline Alpine** di akhir view (component `ujian`),
menggunakan `x-data="ujian(@js($payload), @js($remainingSeconds), @js($autoSubmitUrl))"`.

Layout: grid 2 kolom desktop `lg:grid-cols-[minmax(0,1fr)_20rem]`, 1 kolom mobile.

**Bilah atas (sticky)**: kiri = badge tipe + judul + durasi/jumlah soal; kanan = **timer MM:SS**
(`font-mono`) + ikon jam + tombol "Kumpulkan Jawaban" (`.neo-btn`) → konfirmasi via
`.neo-modal` + `.neo-modal-backdrop`. Sebelum submit, `flush()` antrean jawaban.

**Panel utama**: `.neo-card` + `.neo-card-header` + `.neo-card-title`.
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
  fsOpen: true, fsAktif: false,    // modal & status layar penuh
  warnOpen: false, warnTitle, warnText,  // modal peringatan pelanggaran
  init() { ...setInterval timer...; document.addEventListener('visibilitychange', ...) },
  flush() { ...1 POST batch... },
  submit() { ... }
}
```

**Pengawasan ujian (added 2026-11-16):**
- Saat halaman dibuka muncul modal **"Mulai Ujian dalam Mode Layar Penuh"** (`fsOpen`).
  Tombol "Masuk Layar Penuh" memanggil `requestFullscreen`; tombol "Lanjut tanpa layar penuh"
  menutup modal dan mencatat `tanpa_layar_penh`.
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
Ringkasan (nilai besar font display, badge lulus/tidak, jumlah benar/salah/skip, durasi pengerjaan)
+ daftar per soal: nomor, soal, jawaban peserta (penanda benar/salah untuk PG), kunci jawaban +
penjelasan, skor. Soal essay: berkas terunggah + skor admin (atau "Menunggu penilaian").

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

### Belum (perlu dijalankan)
- [ ] Manual browser:
  - [ ] Admin: buat ujian → tambah PG (5 opsi, 1 kunci) + essay → assign peserta → publish
  - [ ] Peserta: `/ujian` → Mulai Ujian → timer jalan → radio tersimpan → palette update
        → upload lampiran essay → submit → halaman hasil + skor
  - [ ] Durasi 1 menit → biarkan habis → auto-submit jalan, sesi terkunci
  - [ ] Refresh di tengah ujian → jawaban & sisa waktu tetap
  - [ ] 375px → grid 1 kolom, sidebar ke bawah
  - [ ] Admin centang "Acak Urutan Soal" → urutan soal & pilihan berubah, nomor soal tetap 1..N
- [ ] Manual browser (**fitur pengawasan ujian**):
  - [ ] Modal layar penuh muncul; "Masuk Layar Penuh" → browser fullscreen; keluar fullscreen
        → badge pelanggaran bertambah dan baris `keluar_layar_penh` muncul di admin
  - [ ] Buka tab baru → badge bertambah, jenis `perpindahan_fokus` / `tab_baru` tercatat
  - [ ] `window.open` dari console diblokir (return `null`)
  - [ ] Ctrl+C / Ctrl+V / klik kanan / Ctrl+P diblokir; menyalin jawaban esai sendiri tetap boleh
  - [ ] Menutup halaman → peringatan `beforeunload` muncul
  - [ ] Upload lampiran essay **tidak** tercatat sebagai pelanggaran (dialog berkas dikecualikan)
  - [ ] Buka ujian dari peramban lain → peringatan "Sesi Ganda Terdeteksi" + baris `sesi_ganda`
  - [ ] Auto-save tetap 1 request per batch (penjaga tidak menambah request lain selain
        `POST /perangkat` saat halaman dibuka dan `POST /pelanggaran` per kejadian)
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

---

## 10. Out of Scope (sprint berikutnya)

- Notifikasi email/WhatsApp pengingat ujian
- Proctoring, ekspor hasil ke Excel/PDF
- Bank soal reusable lintas ujian (skema sekarang soal menempel pada satu ujian)

---

## 11. Changelog

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
     acak soal, **pengawasan ujian**).
  2. Uji beban (target 1 request per batch jawaban, tanpa polling, response < 500ms dgn 50 soal).
  3. Commit ke branch `feature/ujian-peserta` → PR ke `main` (perubahan saat ini masih di `main`).
  4. Fitur yang masih jadi groundwork saja (belum dipakai): kolom `ujian.jumlah_soal_diambil`
     (migration `2026_11_16_000001`) untuk mengambil sejumlah soal dari bank soal.
- Cara menjalankan test (sudah terverifikasi hijau):
  ```bash
  DB_CONNECTION=mysql DB_DATABASE=kemenagtd_db php vendor/bin/phpunit tests/Feature/UjianTest.php
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
