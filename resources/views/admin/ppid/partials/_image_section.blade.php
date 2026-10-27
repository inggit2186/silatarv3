{{-- Section: image --}}
{{-- Single image with alt text --}}
@php
    $alt = $section->metadata['alt'] ?? '';
    $currentImage = $section->metadata['image_path'] ?? '';
@endphp
<div class="section-block" data-section-id="{{ $section->id }}"
     x-data="{ previewUrl: '', previewName: '' }">

    <div class="ppid-editor-block-head">
        <span class="ppid-editor-block-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" aria-hidden="true">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                <circle cx="8.5" cy="8.5" r="1.5"/>
                <polyline points="21 15 16 10 5 21"/>
            </svg>
            Gambar
        </span>
    </div>

    @if($currentImage)
        <div class="mb-3" style="border: 1px solid var(--border); border-radius: 0.5rem; padding: 0.5rem; display: inline-block; background: var(--card);">
            <img src="{{ asset('storage/' . $currentImage) }}" alt="{{ $alt }}" style="max-height: 200px; border-radius: 0.375rem; display: block;">
        </div>
        <p class="text-xs mb-3" style="color: var(--text-muted);">Gambar saat ini. Upload gambar baru untuk menggantinya.</p>
    @endif

    <div class="form-group">
        <label class="form-label text-xs">Upload Gambar Baru</label>
        <input type="file"
               name="sections[{{ $section->id }}][metadata][image_file]"
               accept="image/*"
               class="form-input"
               @change="previewUrl = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : ''; previewName = $event.target.files[0]?.name ?? ''">
        <p class="text-xs mt-1" style="color: var(--text-muted);">Format: JPG, PNG, WebP, GIF. Maksimal 10MB.</p>

        <div class="mt-3" x-show="previewUrl" x-cloak>
            <img :src="previewUrl" alt="Preview gambar baru" style="max-height: 200px; border-radius: 0.5rem; border: 1px solid var(--border); display: block;">
            <p class="text-xs mt-2" style="color: var(--success);" x-text="previewName"></p>
        </div>
    </div>

    <div class="form-group mb-0">
        <label class="form-label text-xs">Alt Text (deskripsi gambar)</label>
        <input type="text"
               name="sections[{{ $section->id }}][metadata][alt]"
               value="{{ old('sections.' . $section->id . '.metadata.alt', $alt) }}"
               class="form-input"
               placeholder="Deskripsi gambar untuk aksesibilitas...">
    </div>
</div>
