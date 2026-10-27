# Progress Simplifikasi Admin PPID

## Overview
Menyederhanakan admin PPID agar user-friendly seperti editing "Konten Berita" — single-page form, tanpa input JSON manual, inline section editors.

## Status: SELESAI ✓

## Checklist
- [x] Analisis codebase (controller, view, routes, DB schema)
- [x] Buat file progress ini
- [x] Buat section partial views: _text, _list, _card_grid, _timeline, _stats, _table, _form_fields, _image
- [x] Buat admin/ppid/edit.blade.php baru — single-page form, 2 kolom layout
- [x] Modifikasi PpidController::update() — handle all sections in one PUT
- [x] Update routes/admin.php — hapus AJAX routes (editSection, updateSection, toggleVisibility, reorder)
- [x] Hapus edit-section.blade.php lama
- [x] Update progress file

## Data Flow (Sesudah)

```
Admin Form (PUT /admin/ppid/{slug})
  ├── Page: title, subtitle, meta_description, is_active
  ├── Sections[]:
  │     [id][title], [id][content], [id][metadata][items][0], dll.
  │     [id][is_visible], [id][sort_order]
  └── Image files (inline upload)
       │
       ▼
PpidController::update() → syncSections()
       │
       ▼
Database (ppid_pages + ppid_sections)
       │
       ▼
Public /ppid/{slug} renders from DB
```

## Files yang Dimodifikasi/Ditambahkan

| File | Status | Deskripsi |
|------|--------|-----------|
| `app/Http/Controllers/Admin/PpidController.php` | MODIFIED | `update()` handle all sections; `storeSection()` → redirect; `destroySection()` → redirect; hapus `editSection`, `updateSection`, `toggleVisibility`, `reorder` |
| `routes/admin.php` | MODIFIED | Hapus routes AJAX: `editSection`, `updateSection`, `toggleVisibility`, `reorder`. |
| `resources/views/admin/ppid/edit.blade.php` | CREATED | Single-page form, 2-kolom, inline section editors dengan Alpine.js |
| `resources/views/admin/ppid/partials/_text_section.blade.php` | CREATED | Quill WYSIWYG editor |
| `resources/views/admin/ppid/partials/_list_section.blade.php` | CREATED | Add/remove item rows (Alpine) |
| `resources/views/admin/ppid/partials/_card_grid_section.blade.php` | CREATED | Per-card: title, description, link, icon (Alpine) |
| `resources/views/admin/ppid/partials/_timeline_section.blade.php` | CREATED | Per-step: number, title, description (Alpine) |
| `resources/views/admin/ppid/partials/_stats_section.blade.php` | CREATED | Per-stat: value, label, icon (Alpine) |
| `resources/views/admin/ppid/partials/_table_section.blade.php` | CREATED | Headers + dynamic rows (Alpine) |
| `resources/views/admin/ppid/partials/_form_fields_section.blade.php` | CREATED | Per-field: name, label, type, required, options (Alpine) |
| `resources/views/admin/ppid/partials/_image_section.blade.php` | CREATED | File upload + preview + alt text |
| `resources/views/admin/ppid/edit-section.blade.php` | DELETED | Sudah digabung ke edit.blade.php |
| `config/ppid.php` | CREATED | Registry tipe section (label, deskripsi, ikon, warna) — sumber tunggal untuk controller & view |
| `resources/views/admin/ppid/partials/_type_cards.blade.php` | CREATED | Picker tipe visual (grid kartu radio) dipakai kedua modal |
| `resources/views/components/ui/quill-editor.blade.php` | MODIFIED | Label disembunyikan bila kosong |

## Pattern UI — Sesudah

```
┌─────────────────────────────────┬──────────────────┐
│ PENGATURAN HALAMAN              │ STATUS           │
│  [Judul]  [Subtitle]           │ [ Aktif ✓ ]     │
├─────────────────────────────────│ [ slug ]         │
│ SECTIONS (N section)            │                  │
│ ┌─────────────────────────────┐ │ TIPE SECTION    │
│ │ [badge] hero    [Vis] [Del]│ │ • text          │
│ │ Hero section content here  │ │ • list          │
│ └─────────────────────────────┘ │ • card_grid     │
│ ┌─────────────────────────────┐ │ • timeline      │
│ │ [badge] stats  [Vis] [Del] │ │ • stats         │
│ │ ┌──────┬──────┬──────┐   │ │ • table         │
│ │ │ 156  │  42  │  89  │   │ │ • form_fields   │
│ │ │Total  │Layan │Seles │   │ │ • image         │
│ │ └──────┴──────┴──────┘   │ │                 │
│ └─────────────────────────────┘ │                 │
│ [+ Tambah Section]              │                 │
├─────────────────────────────────┴──────────────────┤
│          [ Simpan Perubahan ]                       │
└────────────────────────────────────────────────────┘
```

## Tipe Section → Form Editor

| Type | Editor |
|------|--------|
| `text` | Quill WYSIWYG |
| `list` | Add/remove text rows (Alpine) |
| `card_grid` | Per-card: title, description, link, icon (Alpine) |
| `timeline` | Per-step: number, title, description (Alpine) |
| `stats` | Per-stat: value, label, icon (Alpine) |
| `table` | Headers input + dynamic rows (Alpine) |
| `form_fields` | Per-field: name, label, type, required, options (Alpine) |
| `image` | File upload + preview + alt text |

## Routes Perubahan

### Sebelum
```
PUT  /ppid/{slug}          → update()         [page settings only]
GET  /ppid/{slug}/edit-section/{id} → editSection() [separate page]
POST /ppid/{slug}/sections → storeSection()   [AJAX JSON]
PUT  /ppid/sections/{id}  → updateSection()  [AJAX JSON]
DELETE /ppid/sections/{id} → destroySection() [AJAX JSON]
POST /ppid/sections/reorder → reorder()       [AJAX JSON]
POST /ppid/sections/{id}/toggle-visibility → toggleVisibility() [AJAX JSON]
```

### Sesudah
```
PUT  /ppid/{slug}          → update()         [page settings + all sections]
POST /ppid/{slug}/sections → storeSection()   [form POST → redirect]
DELETE /ppid/sections/{id} → destroySection() [form DELETE → redirect]
```

## Form Field Naming

Setiap section field menggunakan nama yang flat agar mudah diproses:

```php
// Contoh: section ID 5, type=list
<input name="sections[5][id]" value="5">
<input name="sections[5][section_key]" value="statistik">
<input name="sections[5][section_type]" value="list">
<input name="sections[5][title]" value="Statistik Layanan">
<input name="sections[5][metadata][items][0]" value="Item pertama">
<input name="sections[5][metadata][items][1]" value="Item kedua">
<input name="sections[5][is_visible]" value="1">
<input name="sections[5][sort_order]" value="2">
```

Alpine.js menggunakan `x-for` + `x-model` untuk bind ke array JS, tapi nama field tetap flat
menggunakan index loop: `sections[{{ $section->id }}][metadata][items][' + idx + ']'`

## TODO
- [x] Semua task selesai
- [ ] Test end-to-end di browser (kirim PR untuk test)

## Ganti Tipe Section

Admin dapat mengganti tipe section dari badge tipe pada header section.
Setiap tipe punya **style/editor hardcoded** sendiri (partial view terpisah),
bukan template generik.

### Alur

```
Klik badge tipe → openTypeModal(url, type, key, label)
  → modal "Ganti Tipe Section" (pilih tipe lewat kartu visual)
  → submit form#typeChangeForm (PUT /admin/ppid/sections/{id}/type)
  → PpidController::updateType()
  → reset content + metadata ke default tipe baru
  → redirect ke edit page, section tampil dengan editor tipe baru
```

### Catatan Teknis

- Modal `Ganti Tipe Section` memakai form terpisah (`#typeChangeForm`) di luar
  main form — nested form tidak valid HTML. Radio input di dalam modal berada
  di form tersebut secara langsung (form membungkus seluruh isi modal),
  bukan lewat atribut `form=`.
- `.modal-backdrop` di `admin-new.css` punya `display: none` dan hanya tampil
  dengan class `.active`. Karena itu Alpine memakai `:class="{ 'active': ... }"`,
  bukan `x-show` (x-show hanya mengatur inline `display` dan tidak overcoming
  `display: none` dari CSS).
- `[x-cloak]` di `app.css` memakai `display: none !important`, jadi element
  bermodal **harus** berada di dalam form-nya sendiri agar radio tetap
  terkirim saat form disubmit.
- `@push('scripts')` harus **di dalam** `<x-admin.layouts.app>` — layout
  me-render `@stack('scripts')`, sehingga push setelah component ditutup
  tidak akan ter-output.
- Nama `section_type` dipakai radio di **kedua** modal, tetapi keduanya berada
  di form berbeda (`#typeChangeForm` vs `#addSectionForm`) sehingga tidak
  saling memengaruhi.

### Tipe → Editor Partial

| Tipe | Partial | Style |
|------|---------|-------|
| `text` | `_text_section.blade.php` | Quill WYSIWYG |
| `list` | `_list_section.blade.php` | Baris list bernomor |
| `card_grid` | `_card_grid_section.blade.php` | Kartu grid + icon/link |
| `timeline` | `_timeline_section.blade.php` | Steps bernomor |
| `stats` | `_stats_section.blade.php` | Grid angka + label |
| `table` | `_table_section.blade.php` | Tabel header + rows |
| `form_fields` | `_form_fields_section.blade.php` | Definisi field form |
| `image` | `_image_section.blade.php` | Upload + preview + alt |

## Picker Tipe Visual (Card Picker)

Kedua modal (Ganti Tipe & Tambah Section) **tidak** lagi memakai `<select>`.
Tipe dipilih lewat grid kartu visual yang dibangun dari satu partial yang
dibagikan: `admin/ppid/partials/_type_cards.blade.php`.

```blade
@include('admin.ppid.partials._type_cards', [
    'radioName'   => 'section_type',
    'alpineModel' => 'typeChoice',   // atau 'newSectionType'
])
```

Partial ini memakai class `.update-type-*` yang sudah ada di `admin-new.css`
(dipertahankan dari fitur publikasi), ditambah varian warna baru.

## Registry Tipe Section

Daftar tipe dipusatkan di `config/ppid.php` — dipakai oleh controller
(validasi `in:`) maupun semua view (label, deskripsi, ikon, warna).

```php
config('ppid.section_types') // ['text' => ['label'=>..., 'description'=>..., 'color'=>..., 'icon'=>...], ...]
```

Menambah tipe baru cukup di config ini; controller dan admin UI ikut menyesuaikan.

## Peningkatan UI Sesi Ini

### Header Section
- Badge tipe berwarna per tipe + ikon + ikon pensil (hover), klik untuk ganti tipe
- Badge nomor urut (`.ppid-order`) di kiri, sinkron dengan input "Urutan"
- `section_key` ditampilkan sebagai chip monospace (`.ppid-key`)
- Judul section tampil sebagai teks (jika kosong → "Tanpa judul")
- Section yang disembunyikan diredupkan (`.is-hidden`) + judul dicoret
- Toggle visibilitas jadi switch pill (`.ppid-switch`) dengan label dinamis
  "Tampilkan" / "Sembunyikan", bukan checkbox polos
- Tombol hapus jadi ikon button ghost (merah saat hover), bukan tombol merah solid
- Chevron putar saat section dibuka

### Body Section
- Toolbar atas: input "Urutan" + input "Judul Section" berdampingan
- Editor dibungkus `.ppid-editor-block` (panel dengan background)
- Semua partial partial sekarang memakai header `.ppid-editor-block-head`
  yang konsisten, dan baris item memakai `.ppid-item` (bukan `.card` bersarang)
- Empty state per tipe ("Belum ada item. Klik ... untuk ...")

### Modal
- Lebar (`modal-lg`, 780px) untuk accommodate grid kartu tipe
- Header modal punya ikon + judul + subjudul (konteks section / key)
- Modal Ganti Tipe punya alert peringatan "Konten akan dikosongkan"
- Tombol submit nonaktif bila tipe tidak berubah / belum dipilih
- Modal Tambah Section punya auto-slug pada Section Key + preview key

### Halaman
- Kolom kanan: card "Status Halaman", card "Ringkasan" (total/tampil/disembunyikan),
  card "Tipe Section" berupa legenda berwarna
- Save bar sticky di bawah (`sticky bottom-0`) — selalu terjangkau saat scroll

### Perbaikan Teknis
- `_image_section.blade.php`: preview gambar pakai Alpine `URL.createObjectURL`
  (sebelumnya `handleImagePreview()` global yang ter-definisi ulang tiap section)
- Hidden input `id`/`section_key`/`section_type` dihapus dari partial
  (sudah disediakan di `edit.blade.php`) — tidak ada lagi duplikat nama field
- `quill-editor.blade.php`: label disembunyikan bila kosong (`@if(($label ?? '') !== '')`)
- Class `.btn-outline` ditambahkan ke `admin-new.css` (sebelumnya dipakai tapi belum ada)
- Class `.neo-checkbox` yang tidak pernah ada diganti `.form-checkbox` (yang ada)

## Catatan Verifikasi

Blade lulus `php artisan view:cache`. Halaman sudah di-render (layout admin
utuh, 8 section dengan 8 tipe berbeda) dan dipotret via Chrome headless untuk
memeriksa layout.

## Perbaikan: Section "Gambar" tidak muncul di halaman publik

### Gejala

Admin mengunggah gambar pada section bertipe `image` lalu menyimpan. Alt text
tersimpan, tetapi gambar tidak pernah muncul di `/ppid/*`. Section baru dengan
kategori Gambar juga tidak muncul sama sekali di halaman publik.

### Akar Masalah (3 bug)

1. **File upload tidak pernah sampai ke controller.**
   `syncSections()` membaca payload form lewat `$request->input('sections')`.
   Method tersebut hanya membaca *body* dan *query*, **tidak** membaca
   *file bag*. Akibatnya `metadata.image_file` selalu `null` dan
   `buildMetadata()` tidak pernah menyimpan gambar.

   Bukti: section `tugas` (tipe `image`) punya metadata
   `{"alt":"asdsadasdad"}` tanpa `image_path`, dan folder
   `storage/app/public/ppid/` tidak pernah dibuat.

2. **Gambar disimpan ke disk yang salah.**
   `storeImage()` memakai `$file->storeAs('public', $nama)`. Parameter pertama
   `storeAs()` adalah **path**, bukan nama disk — file akhirnya tersimpan di
   disk default (`local`) pada `storage/app/private/public/ppid/`, bukan di
   disk `public` yang disajikan lewat symlink `/storage/...`.
   Akibatnya file selalu 404 di halaman publik. Moreover karena sumber
   (`$disk->path(...)`) tidak ada, konversi WebP selalu gagal dan fallback ke
   file asli.

3. **Section baru di luar template halaman tidak pernah dirender.**
   Halaman publik (`resources/views/ppid/*.blade.php`) hanya merender section
   dengan `section_key` yang tercantum di `config('ppid.page_slots')`.
   Section dengan key lain — termasuk section yang baru dibuat admin — tidak
   punya tempat tampil, padahal komentar config sudah menjanjikan
   "di render sebagai section tambahan di akhir halaman".

### Perbaikan

| File | Perubahan |
|------|-----------|
| `app/Http/Controllers/Admin/PpidController.php` | `syncSections()` ambil file dari file bag (`$request->file("sections.{id}.metadata.image_file")`); `buildMetadata()` pertahankan `image_path` lama bila tidak ada upload baru (mengubah alt text tidak boleh menghapus gambar); `storeImage()` simpan ke disk `public` lewat `storeAs('ppid', $nama, ['disk' => 'public'])` + tolak file non-gambar/SVG; `deleteGallery()` memakai disk `public` |
| `app/Http/Controllers/PpidController.php` | `getPageData()` menghitung section yang tidak dipakai template (`leftoverSections()`) dan membagikannya ke view sebagai `extraSections` |
| `resources/views/ppid/partials/footer.blade.php` | Render `extraSections` sebelum `<footer>` — section baru dari admin langsung tampil di akhir halaman |

### Alur Section Tambahan

```
config('ppid.page_slots.{slug}')   → key yang dipakai template halaman
sections visible                   → section_key di luar daftar  → extraSections
sectionsByType[key]->first()       → dipakai template (hanya yang pertama per key)
```

Section dengan `section_key` yang sama (duplikat) juga ditampilkan sebagai
section tambahan, supaya tidak ada konten yang hilang.

### Catatan

- `deleteGallery()` ikut disesuaikan ke disk `public` karena lokasi file
  gallery berubah. Tabel `ppid_gallery` masih kosong, jadi tidak ada file lama
  yang perlu dimigrasi.
- Tersisa 3 file orphan di `storage/app/private/public/ppid/` dari percobaan
  sebelum perbaikan (upload masuk ke disk yang salah). Tidak dirujuk database
  mana pun — boleh dihapus manual.

### Hasil Verifikasi

Diverifikasi langsung terhadap database (MySQL tersedia di environment ini):

- Upload gambar → `metadata.image_path` = `ppid/<file>.webp`, file ada di
  `storage/app/public/ppid/`, URL `/storage/ppid/<file>.webp` resolve.
- Simpan ulang tanpa upload → `image_path` tetap sama (gambar tidak hilang).
- Upload file non-gambar → ditolak, `image_path` lama tidak berubah, pesan
  error ditampilkan ke admin.
- Section baru di luar `page_slots` muncul di HTML halaman publik.
- Seluruh 22 halaman publik `/ppid/*` berhasil dirender tanpa error.

## Changelog

### 2026-10-27
- Fix: file upload section `image` kini dibaca dari file bag controller
- Fix: `storeImage()` menyimpan ke disk `public` (sebelumnya ke disk default
  `local` sehingga file tidak bisa diakses lewat `/storage/...`)
- Fix: `image_path` lama dipertahankan saat admin hanya mengubah alt text
- Fix: file non-gambar / SVG ditolak saat upload
- Fix: section baru di luar `config('ppid.page_slots')` dirender di akhir
  halaman publik
- `deleteGallery()` disesuaikan ke disk `public`

### 2026-10-26
- Tambah `config/ppid.php` sebagai registry tipe section (label, deskripsi, ikon, warna)
- Controller (`PpidController`) baca daftar tipe dari config untuk validasi
- Rewrite `admin/ppid/edit.blade.php` — header section dengan badge berwarna,
  switch visibilitas, toolbar urutan/judul, save bar sticky, kolom ringkasan
- Tambah partial `_type_cards.blade.php` — picker tipe visual untuk kedua modal
- Ganti `<select>` tipe dengan kartu visual di modal Ganti Tipe & Tambah Section
- Perbagus kedua modal: header ikon + subjudul, alert peringatan, lebar `modal-lg`
- Section tersembunyi diredupkan + judul dicoret
- Rapikan semua partial section: header konsisten, `.ppid-item`, empty state
- `_image_section.blade.php`: preview via Alpine `URL.createObjectURL`, hapus script global
- Hapus hidden input duplikat dari partial section
- `quill-editor.blade.php`: sembunyikan label bila kosong
- `admin-new.css`: tambah ~330 baris style PPID editor (badge tipe, switch, item,
  modal-lg, save bar, legenda) + `.btn-outline`
- `npm run build` — asset frontend di-regenerate

### 2026-10-21
- Rewrite `admin/ppid/edit.blade.php` — single-page form, 2 kolom, inline section editors
- Buat 8 section partial views dengan Alpine.js untuk dynamic rows
- Modifikasi `PpidController::update()` — sekarang handle semua sections dalam satu PUT request
- `storeSection()` dan `destroySection()` sekarang form POST/DELETE dengan redirect
- Hapus `editSection`, `updateSection`, `toggleVisibility`, `reorder` methods dan AJAX routes
- Hapus `edit-section.blade.php` lama (sudah digabung)
- Hapus 4 route AJAX (editSection, updateSection, toggleVisibility, reorder)
- Style halaman public `/ppid/*` tetap hardcoded di blade, tidak berubah
- Tambah modal "Ganti Tipe Section" — badge tipe section bisa diklik untuk mengganti tipe
- Fix modal "Tambah Section" & modal tipe: `x-show` → `:class="{ 'active': ... }"`
  agar tampil, karena `.modal-backdrop` CSS-nya `display: none`
- Fix `@push('scripts')` dipindah ke dalam `<x-admin.layouts.app>` (sebelumnya di luar,
  sehingga `ppidEditor()` tidak pernah ter-output dan Alpine error)
- Fix `</div>` penutup `x-data="ppidEditor()"` yang hilang
