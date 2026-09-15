# PPID Views Implementation Guide

## Overview

All 22 PPID views need to be updated to render content from database instead of hardcoded values.

**Status:**
- ✅ Updated: index, visi-misi, profil-singkat, jadwal (4 views)
- ⏳ Remaining: 18 views to update

---

## Pattern Reference

### 1. Text Section Pattern
```blade
@if(isset($sectionsByType['section_key']))
    @php $section = $sectionsByType['section_key']->first(); @endphp
    <section class="ppid-section" data-reveal>
        <h2 class="ppid-section-title">{{ $section->title ?? 'Default Title' }}</h2>
        <div class="ppid-section-content">
            {!! $section->content !!}
        </div>
    </section>
@endif
```

### 2. List Section Pattern
```blade
@if(isset($sectionsByType['section_key']))
    @php $section = $sectionsByType['section_key']->first(); @endphp
    @php $items = json_decode($section->metadata)->items ?? []; @endphp
    <section class="ppid-section" data-reveal>
        <h2 class="ppid-section-title">{{ $section->title ?? 'List Title' }}</h2>
        <ul class="ppid-list">
            @foreach($items as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>
    </section>
@endif
```

### 3. Card Grid Section Pattern
```blade
@if(isset($sectionsByType['section_key']))
    @php $section = $sectionsByType['section_key']->first(); @endphp
    @php $cards = json_decode($section->metadata)->cards ?? []; @endphp
    <section class="ppid-section" data-reveal>
        <h2 class="ppid-section-title">{{ $section->title ?? 'Cards Title' }}</h2>
        <div class="ppid-grid">
            @foreach($cards as $card)
                <div class="ppid-card">
                    <div class="ppid-card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                    <h3 class="ppid-card-title">{{ $card->title }}</h3>
                    <p class="ppid-card-text">{{ $card->description }}</p>
                </div>
            @endforeach
        </div>
    </section>
@endif
```

### 4. Timeline Section Pattern
```blade
@if(isset($sectionsByType['section_key']))
    @php $section = $sectionsByType['section_key']->first(); @endphp
    @php $steps = json_decode($section->metadata)->steps ?? []; @endphp
    <section class="ppid-section" data-reveal>
        <h2 class="ppid-section-title">{{ $section->title ?? 'Timeline Title' }}</h2>
        <div class="ppid-timeline">
            @foreach($steps as $step)
                <div class="ppid-timeline-item">
                    <span class="ppid-timeline-number">Langkah {{ $step->number }}</span>
                    <h3 class="ppid-timeline-title">{{ $step->title }}</h3>
                    <p class="ppid-timeline-text">{{ $step->description }}</p>
                </div>
            @endforeach
        </div>
    </section>
@endif
```

### 5. Stats Section Pattern
```blade
@if(isset($sectionsByType['section_key']))
    @php $section = $sectionsByType['section_key']->first(); @endphp
    @php $stats = json_decode($section->metadata)->stats ?? []; @endphp
    <div class="ppid-stats" data-reveal>
        @foreach($stats as $stat)
            <div class="ppid-stat">
                <div class="ppid-stat-value">{{ $stat->value }}</div>
                <div class="ppid-stat-label">{{ $stat->label }}</div>
            </div>
        @endforeach
    </div>
@endif
```

### 6. Table Section Pattern
```blade
@if(isset($sectionsByType['section_key']))
    @php $section = $sectionsByType['section_key']->first(); @endphp
    @php $tableData = json_decode($section->metadata); @endphp
    <section class="ppid-section" data-reveal>
        <h2 class="ppid-section-title">{{ $section->title ?? 'Table Title' }}</h2>
        <div style="background: white; border: 1px solid rgba(140, 135, 130, 0.15); border-radius: 1.5rem; overflow: hidden;">
            <table class="ppid-table">
                <thead>
                    <tr>
                        @foreach($tableData->headers as $header)
                            <th>{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($tableData->rows as $row)
                        <tr>
                            @foreach($row as $cell)
                                <td>{{ $cell }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endif
```

### 7. Form Fields Section Pattern
```blade
@if(isset($sectionsByType['section_key']))
    @php $section = $sectionsByType['section_key']->first(); @endphp
    @php $fields = json_decode($section->metadata)->fields ?? []; @endphp
    <section class="ppid-section" data-reveal>
        <h2 class="ppid-section-title">{{ $section->title ?? 'Form Title' }}</h2>
        <form class="ppid-form">
            @foreach($fields as $field)
                <div class="ppid-form-group">
                    <label class="ppid-form-label">{{ $field->label }}{{ $field->required ? ' *' : '' }}</label>
                    @if($field->type === 'textarea')
                        <textarea name="{{ $field->name }}" class="ppid-form-input" {{ $field->required ? 'required' : '' }}></textarea>
                    @elseif($field->type === 'select')
                        <select name="{{ $field->name }}" class="ppid-form-input" {{ $field->required ? 'required' : '' }}>
                            <option value="">Pilih...</option>
                            @foreach($field->options ?? [] as $option)
                                <option value="{{ $option }}">{{ $option }}</option>
                            @endforeach
                        </select>
                    @else
                        <input type="{{ $field->type }}" name="{{ $field->name }}" class="ppid-form-input" {{ $field->required ? 'required' : '' }}>
                    @endif
                </div>
            @endforeach
            <button type="submit" class="ppid-btn ppid-btn-primary">Kirim</button>
        </form>
    </section>
@endif
```

---

## Views to Update

### Simple Views (1-2 sections)
These views have only text and/or list sections:

1. **tugas-fungsi.blade.php** - 3 list sections
   - Section keys: atasan_ppid, ppid_utama, ppid_pelaksana
   - Type: list

2. **struktur.blade.php** - 1 card_grid section
   - Section key: peran
   - Type: card_grid

3. **regulasi.blade.php** - 1 card_grid section
   - Section key: daftar_regulasi
   - Type: card_grid

4. **informasi-berkala.blade.php** - 1 card_grid section
   - Section key: daftar_informasi
   - Type: card_grid

5. **informasi-serta-merta.blade.php** - 2 sections (text, list)
   - Section keys: peringatan, daftar_informasi
   - Types: text, list

6. **informasi-setiap-saat.blade.php** - 1 card_grid section
   - Section key: daftar_informasi
   - Type: card_grid

### Medium Complexity Views (2-3 sections)
These views have mixed section types:

7. **maklumat.blade.php** - 2 sections (text, list)
   - Section keys: pernyataan, janji_pelayanan
   - Types: text, list

8. **biaya.blade.php** - 2 sections (text, table)
   - Section keys: informasi_biaya, pengecualian
   - Types: text, table

9. **laporan-layanan.blade.php** - 2 sections (stats, table)
   - Section keys: statistik, rekap_bulanan
   - Types: stats, table

10. **prosedur-permohonan.blade.php** - 2 sections (timeline, card_grid)
    - Section keys: timeline, waktu_penyelesaian
    - Types: timeline, card_grid

11. **prosedur-keberatan.blade.php** - 2 sections (timeline, list)
    - Section keys: timeline, alasan_keberatan
    - Types: timeline, list

12. **prosedur-sengketa.blade.php** - 1 timeline section
    - Section key: timeline
    - Type: timeline

13. **tentang-kami.blade.php** - 3 sections (text, text, card_grid)
    - Section keys: sambutan, visi, kontak
    - Types: text, text, card_grid

### Complex Views (Forms)
These views have form fields:

14. **formulir-permohonan.blade.php** - 2 sections (text, form_fields)
    - Section keys: instruksi, form_fields
    - Types: text, form_fields

15. **formulir-keberatan.blade.php** - 2 sections (text, form_fields)
    - Section keys: instruksi, form_fields
    - Types: text, form_fields

16. **pengaduan.blade.php** - 2 sections (text, form_fields)
    - Section keys: instruksi, form_fields
    - Types: text, form_fields

### Gallery Views
These views use gallery table instead of sections:

17. **gallery-fasilitas.blade.php** - Gallery from ppid_gallery table
18. **gallery-kegiatan.blade.php** - Gallery from ppid_gallery table

---

## Template Structure

Every view should follow this structure:

```blade
<x-layouts.ppid-layout title="{{ $page->title }}">
    <x-ppid.nav />

    <main class="ppid-content">
        <div class="ppid-page">
            {{-- Breadcrumb --}}
            <div class="ppid-page-breadcrumb">
                <a href="{{ route('ppid') }}">PPID</a>
                <span>/</span>
                <span>{{ $page->title }}</span>
            </div>

            {{-- Page Header --}}
            <div class="ppid-page-header" data-reveal>
                <h1 class="ppid-page-title">{{ $page->title }}</h1>
                <p class="ppid-page-subtitle">{{ $page->subtitle ?? '' }}</p>
            </div>

            {{-- Sections --}}
            @if(isset($sectionsByType['key']))
                @php $section = $sectionsByType['key']->first(); @endphp
                <section class="ppid-section" data-reveal>
                    {{-- Section content based on type --}}
                </section>
            @endif
        </div>

        @include('ppid.partials.footer')
    </main>
</x-layouts.ppid-layout>
```

---

## Section Key Mapping

### Pages & Their Sections

| Page | Slug | Sections (section_key) |
|------|------|------------------------|
| Beranda | index | hero, stats, tentang_ppid, motto, layanan_populer, prosedur_timeline |
| Profil Singkat | profil-singkat | profil, tugas, fungsi |
| Visi Misi | visi-misi | visi, misi |
| Tugas Fungsi | tugas-fungsi | atasan_ppid, ppid_utama, ppid_pelaksana |
| Struktur | struktur | peran |
| Regulasi | regulasi | daftar_regulasi |
| Maklumat | maklumat | pernyataan, janji_pelayanan |
| Jadwal | jadwal | jadwal_opsional, kontak |
| Biaya | biaya | informasi_biaya, pengecualian |
| Laporan Layanan | laporan-layanan | statistik, rekap_bulanan |
| Prosedur Permohonan | prosedur-permohonan | timeline, waktu_penyelesaian |
| Prosedur Keberatan | prosedur-keberatan | timeline, alasan_keberatan |
| Prosedur Sengketa | prosedur-sengketa | timeline |
| Formulir Permohonan | formulir-permohonan | instruksi, form_fields |
| Formulir Keberatan | formulir-keberatan | instruksi, form_fields |
| Informasi Berkala | informasi-berkala | daftar_informasi |
| Informasi Serta Merta | informasi-serta-merta | peringatan, daftar_informasi |
| Informasi Setiap Saat | informasi-setiap-saat | daftar_informasi |
| Pengaduan | pengaduan | instruksi, form_fields |
| Gallery Fasilitas | gallery-fasilitas | (gallery table) |
| Gallery Kegiatan | gallery-kegiatan | (gallery table) |
| Tentang Kami | tentang-kami | sambutan, visi, kontak |

---

## Quick Reference

### For Text Content:
```blade
{!! $section->content !!}
```

### For List Items:
```blade
@php $items = json_decode($section->metadata)->items ?? []; @endphp
@foreach($items as $item)
    <li>{{ $item }}</li>
@endforeach
```

### For Card Grid:
```blade
@php $cards = json_decode($section->metadata)->cards ?? []; @endphp
@foreach($cards as $card)
    <h3>{{ $card->title }}</h3>
    <p>{{ $card->description }}</p>
@endforeach
```

### For Timeline:
```blade
@php $steps = json_decode($section->metadata)->steps ?? []; @endphp
@foreach($steps as $step)
    <span>Step {{ $step->number }}</span>
    <h3>{{ $step->title }}</h3>
    <p>{{ $step->description }}</p>
@endforeach
```

### For Stats:
```blade
@php $stats = json_decode($section->metadata)->stats ?? []; @endphp
@foreach($stats as $stat)
    <span>{{ $stat->value }}</span>
    <span>{{ $stat->label }}</span>
@endforeach
```

### For Table:
```blade
@php $table = json_decode($section->metadata); @endphp
<thead>
    <tr>
        @foreach($table->headers as $h)
            <th>{{ $h }}</th>
        @endforeach
    </tr>
</thead>
<tbody>
    @foreach($table->rows as $row)
        <tr>
            @foreach($row as $cell)
                <td>{{ $cell }}</td>
            @endforeach
        </tr>
    @endforeach
</tbody>
```

---

## Testing

After updating each view:

1. **Clear cache:**
   ```bash
   php artisan optimize:clear
   ```

2. **Test in browser:**
   - Access the page URL
   - Verify content displays correctly
   - Check section titles and content

3. **Verify data:**
   ```bash
   php artisan tinker --execute="
   echo DB::table('ppid_sections')
       ->where('page_id', 1)
       ->count();
   "
   ```

4. **Check for errors:**
   - View should not have any hardcoded text
   - All content should come from database
   - JSON metadata should be properly decoded

---

## Common Issues & Fixes

### Issue 1: Undefined variable $sectionsByType
**Cause:** Controller not passing variable
**Fix:** Check PpidController returns `$sectionsByType`

### Issue 2: Trying to get property of non-object
**Cause:** JSON decode failed
**Fix:** Check metadata JSON format in database

### Issue 3: Section not displaying
**Cause:** Wrong section_key or section not visible
**Fix:** Check section_key matches database, is_visible=true

### Issue 4: Array to string conversion
**Cause:** Forgetting to decode JSON metadata
**Fix:** Use `json_decode($section->metadata)` first

### Issue 5: Empty content
**Cause:** Content field is null or empty
**Fix:** Check database for missing content, add fallback

---

## Next Steps

1. **Batch update remaining views** (18 views)
2. **Test each page** in browser
3. **Verify data flows** from database to view
4. **Document any issues** found
5. **Add caching** if performance needed

**Estimated time to complete:** 3-4 hours for remaining 18 views
