# Progress Halaman Peserta Ujian (SILATAR V2)

> File ini adalah **catatan kerja hidup** untuk developer lain / AI lain. Isi dokumen ini menjelaskan
> **apa yang sudah jadi, apa yang belum, keputusan desain, dan langkah berikutnya** supaya pekerjaan
> bisa dilanjutkan tanpa membaca ulang seluruh plan.
>
> Plan lengkap (disetujui user): `C:\Users\Admin\.claude\plans\misty-waddling-pine.md`
> (salinan ringkas juga ada di bagian "Arsitektur" bawah).

## Status: DALAM PROGRES (implementasi kode selesai, verifikasi manual pending)

**Tanggal mulai:** 2026-10-28
**Update terakhir:** 2026-10-29
**Branch:** `main` (rekomendasi: buat `feature/ujian-peserta` sebelum lanjut)

> **Ringkasan 2026-10-29:** Seluruh kode fitur (migrasi → model → controller admin → controller
> peserta → view → route → menu → seeder → test) **sudah ada**. Yang tersisa murni tahap
> verifikasi: `php artisan test` (terblokir `pdo_sqlite` belum terinstall di PHP lokal),
> uji manual di browser, uji beban, dan `composer pint`.
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
ujian_peserta         id, ujian_id, user_id, status(belum_mulai|berjalan|selesai),
                      started_at, ends_at, submitted_at, nilai?   [UNIQUE(ujian_id, user_id)]
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
- [ ] `php artisan test` hijau — **TERBLOKIR**: 10/10 `UjianTest` error `"could not find driver"`.
      Penyebab: `phpunit.xml:26-27` memakai `DB_CONNECTION=sqlite`, tapi PHP lokal hanya punya
      `pdo_mysql` (`pdo_sqlite` belum terinstall). **Bukan** bug fitur — test-nya sudah lengkap (10 test).
      Solusi: install ekstensi `pdo_sqlite`, atau jalankan suite dengan driver mysql.
- [ ] Uji manual di browser (timer, autosave batch, navigasi palette, upload essay, submit, hasil)
- [ ] Uji beban (target: ≤4 query saat render, ≤3 query saat submit, 1 request per batch jawaban, tanpa polling)
- [ ] `composer pint` (formatting)

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
  init() { ...setInterval timer...; document.addEventListener('visibilitychange', ...) },
  flush() { ...1 POST batch... },
  submit() { ... }
}
```

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
- [x] Guard waktu terverifikasi di `UjianController:95, 160, 260` (`now() > ends_at` → sesi ditutup)

### Belum (perlu dijalankan)
- [ ] `php artisan test` → **TERBLOKIR `pdo_sqlite` belum terinstall**. 10 test `UjianTest` sudah ditulis tapi semua error `"could not find driver"`.
      Fix: `php -m | grep sqlite` → kosong. Install ekstensi `pdo_sqlite` (Windows: aktifkan `extension=pdo_sqlite` di `php.ini`), lalu `php artisan test`.
- [ ] Manual browser:
  - [ ] Admin: buat ujian → tambah PG (5 opsi, 1 kunci) + essay → assign peserta → publish
  - [ ] Peserta: `/ujian` → Mulai Ujian → timer jalan → radio tersimpan → palette update
        → upload lampiran essay → submit → halaman hasil + skor
  - [ ] Durasi 1 menit → biarkan habis → auto-submit jalan, sesi terkunci
  - [ ] Refresh di tengah ujian → jawaban & sisa waktu tetap
  - [ ] 375px → grid 1 kolom, sidebar ke bawah
- [ ] Keamanan: peserta tidak ditunjuk → 403/404 di `/ujian/{id}`; pilihan dari soal lain ditolak
- [ ] Beban:
  - [ ] Network: buka `/ujian/{id}` → 1 document request, **tanpa polling**
  - [ ] Klik 5 radio berbeda → **1** POST setelah idle
  - [ ] Query log render → **≤ 4 query**; submit → **≤ 3 query**
  - [ ] Response time < 500ms dengan 50 soal
- [ ] `composer pint`

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
| `tests/Feature/UjianTest.php` | 10 feature test (menunggu `pdo_sqlite`) |
| `UJIAN_PROGRESS.md` | File progress ini |

### Files Dimodifikasi
| File | Perubahan |
|------|-----------|
| `routes/web.php` | Tambah grup route `ujian.*` di dalam `Route::middleware('auth')` (`:66-74`) |
| `routes/admin.php` | Tambah grup route `admin.ujian.*` (`:244-266`) |
| `resources/views/components/layouts/site-header.blade.php` | Menu "Ujian" di user-dropdown desktop (`:98`) & bottom-nav dropdown mobile (`:184`) |
| `resources/views/admin/layouts/app.blade.php` | Menu sidebar "Ujian" di grup "Kelola" (`:145`) + `menu-group-count` (`:90`) |

> Catatan: `app/Models/User.php` **tidak** diubah — relasi `ujian()` tidak dipakai karena
> query peserta diambil langsung via `ujian_peserta.user_id`.

---

## 10. Out of Scope (sprint berikutnya)

- Notifikasi email/WhatsApp pengingat ujian
- Randomisasi soal/pilihan (field `acak_soal` sudah ada di skema, belum dipakai)
- Proctoring, ekspor hasil ke Excel/PDF
- Bank soal reusable lintas ujian (skema sekarang soal menempel pada satu ujian)

---

## 11. Changelog

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
  1. Install `pdo_sqlite` → jalankan `php artisan test` (blocker utama, kode test sudah ada).
  2. Uji manual di browser sesuai section 8 (timer, autosave batch, upload, submit, guard waktu).
  3. Uji beban (target ≤4 query render, ≤3 query submit).
  4. `composer pint`.
  5. Commit ke branch `feature/ujian-peserta` → PR ke `main`.
- Saat melanjutkan, **baca section 2 & 4 dulu** (arsitektur + pola existing) supaya tidak
  membuat ulang komponen/CSS yang sudah ada.
- Jangan menambah file CSS baru. Kalau butuh styling, pakai utility Tailwind dengan
  arbitrary value ke `--paper/--ink/--gold/--line/--night` atau kelas `.neo-*` yang sudah ada.
- Kalau menambahkan kolom/tabel baru → update section 2.1 + section 11 (changelog).
- Kalau mengubah desain halaman → update section 5 + 6.
- Update section 3 (checklist) setiap task selesai.
- Kalau menambah/mengubah file → update section 9.
