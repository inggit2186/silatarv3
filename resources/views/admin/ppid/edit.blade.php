@php
    $sectionTypes = config('ppid.section_types');
    $visibleCount = $sections->where('is_visible', 1)->count();
    $hiddenCount = $sections->count() - $visibleCount;
@endphp

<x-admin.layouts.app title="{{ $title }}">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// PPID</span>
            <h1 class="page-title">Edit: {{ $page->title }}</h1>
            <p class="page-subtitle">Kelola konten halaman PPID secara inline — tanpa JSON manual</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.ppid.index') }}" class="btn btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                    <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
                </svg>
                Kembali
            </a>
            <a href="{{ $page->slug === 'index' ? route('ppid') : route('ppid.' . $page->slug) }}" target="_blank" class="btn btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                    <polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>
                </svg>
                Lihat Website
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success mb-6">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger mb-6">
            {{ session('error') }}
        </div>
    @endif

    <!-- Alpine scope: form utama + modal (semua modal punya form sendiri di luar form utama) -->
    <div x-data="ppidEditor()">

        <!-- ==================== FORM UTAMA ==================== -->
        <form id="pageForm" method="POST" action="{{ route('admin.ppid.update', $page->slug) }}"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid gap-6 lg:grid-cols-3">
                <!-- Left Column: Page Settings + Sections (2/3) -->
                <div class="lg:col-span-2 space-y-6">

                    {{-- Page Settings --}}
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <svg class="inline w-4 h-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                                </svg>
                                Pengaturan Halaman
                            </h3>
                        </div>
                        <div class="card-body space-y-4">
                            <div class="form-group">
                                <label class="form-label">Judul Halaman <span class="text-danger">*</span></label>
                                <input type="text" name="title" value="{{ old('title', $page->title) }}" required class="form-input" placeholder="Judul halaman...">
                                @error('title')
                                <p class="text-danger text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group mb-0">
                                <label class="form-label">Subtitle</label>
                                <input type="text" name="subtitle" value="{{ old('subtitle', $page->subtitle) }}" class="form-input" placeholder="Subtitle halaman...">
                            </div>
                        </div>
                    </div>

                    {{-- Sections --}}
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold">Sections</h3>
                                <p class="text-xs" style="color: var(--text-muted);">
                                    {{ $sections->count() }} section · {{ $visibleCount }} tampil · {{ $hiddenCount }} disembunyikan
                                </p>
                            </div>
                            <button type="button" @click="showAddModal = true" class="btn btn-primary btn-sm">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                                </svg>
                                Tambah Section
                            </button>
                        </div>

                        @forelse($sections as $section)
                            @php
                                $typeMeta = $sectionTypes[$section->section_type] ?? null;
                                $typeLabel = $typeMeta['label'] ?? $section->section_type;
                                $typeColor = $typeMeta['color'] ?? 'primary';
                                $typeIcon = $typeMeta['icon'] ?? '<circle cx="12" cy="12" r="9"/>';
                            @endphp

                            <div class="card ppid-section mb-4"
                                 id="section-{{ $section->id }}"
                                 x-data="{ open: true, visible: {{ $section->is_visible ? 'true' : 'false' }}, order: '{{ $section->sort_order }}' }"
                                 :class="{ 'is-open': open, 'is-hidden': !visible }">

                                {{-- Header --}}
                                <div class="ppid-section-header">
                                    <div class="ppid-section-main" @click="open = !open">
                                        <span class="ppid-order" x-text="order"></span>

                                        {{-- Badge tipe: klik untuk mengganti tipe --}}
                                        <button type="button"
                                                class="badge ppid-type-badge {{ $typeColor }}"
                                                title="Klik untuk mengganti tipe section"
                                                @click.stop="openTypeModal('{{ route('admin.ppid.sections.update-type', $section->id) }}', '{{ $section->section_type }}', '{{ $section->section_key }}', '{{ $typeLabel }}')">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                 width="13" height="13" aria-hidden="true">{!! $typeIcon !!}</svg>
                                            {{ $typeLabel }}
                                            <svg class="ppid-badge-pencil" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                 width="10" height="10" aria-hidden="true">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                            </svg>
                                        </button>

                                        <span class="ppid-key">{{ $section->section_key }}</span>
                                        <span class="ppid-section-title">{{ $section->title ?: 'Tanpa judul' }}</span>
                                    </div>

                                    <div class="ppid-section-actions" @click.stop>
                                        {{-- Toggle tampil/sembunyi --}}
                                        <label class="ppid-switch" title="Tampilkan atau sembunyikan section ini di website">
                                            <input type="checkbox"
                                                   name="sections[{{ $section->id }}][is_visible]"
                                                   value="1"
                                                   {{ $section->is_visible ? 'checked' : '' }}
                                                   @change="visible = $event.target.checked">
                                            <span class="ppid-switch-track"><span class="ppid-switch-thumb"></span></span>
                                            <span class="ppid-switch-label" x-text="visible ? 'Tampilkan' : 'Sembunyikan'"></span>
                                        </label>

                                        {{-- Hapus section --}}
                                        <button type="submit"
                                                form="delete-section-{{ $section->id }}"
                                                class="ppid-icon-btn"
                                                title="Hapus section ini"
                                                onclick="return confirm('Hapus section &quot;{{ $section->section_key }}&quot;? Tindakan ini tidak dapat dibatalkan.')">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15">
                                                <polyline points="3 6 5 6 21 6"/>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                            </svg>
                                        </button>

                                        <button type="button" class="ppid-chevron" @click="open = !open"
                                                title="Buka / tutup editor">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                                                <polyline points="6 9 12 15 18 9"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                {{-- Body --}}
                                <div class="card-body" x-show="open">
                                    <input type="hidden" name="sections[{{ $section->id }}][id]" value="{{ $section->id }}">
                                    <input type="hidden" name="sections[{{ $section->id }}][section_key]" value="{{ $section->section_key }}">
                                    <input type="hidden" name="sections[{{ $section->id }}][section_type]" value="{{ $section->section_type }}">

                                    <div class="ppid-section-toolbar">
                                        <div class="ppid-order-field">
                                            <label class="form-label text-xs" for="order-{{ $section->id }}">Urutan</label>
                                            <input type="number"
                                                   id="order-{{ $section->id }}"
                                                   name="sections[{{ $section->id }}][sort_order]"
                                                   value="{{ $section->sort_order }}"
                                                   min="0"
                                                   class="form-input"
                                                   @input="order = $event.target.value">
                                        </div>
                                        <div class="flex-1">
                                            <label class="form-label text-xs">Judul Section</label>
                                            <input type="text"
                                                   name="sections[{{ $section->id }}][title]"
                                                   value="{{ old('sections.' . $section->id . '.title', $section->title) }}"
                                                   class="form-input"
                                                   placeholder="Judul section (opsional)...">
                                        </div>
                                    </div>

                                    <div class="ppid-editor-block">
                                        @switch($section->section_type)
                                            @case('text')
                                                @include('admin.ppid.partials._text_section')
                                                @break
                                            @case('list')
                                                @include('admin.ppid.partials._list_section')
                                                @break
                                            @case('card_grid')
                                                @include('admin.ppid.partials._card_grid_section')
                                                @break
                                            @case('timeline')
                                                @include('admin.ppid.partials._timeline_section')
                                                @break
                                            @case('stats')
                                                @include('admin.ppid.partials._stats_section')
                                                @break
                                            @case('table')
                                                @include('admin.ppid.partials._table_section')
                                                @break
                                            @case('form_fields')
                                                @include('admin.ppid.partials._form_fields_section')
                                                @break
                                            @case('image')
                                                @include('admin.ppid.partials._image_section')
                                                @break
                                            @default
                                                <div class="alert alert-warning">
                                                    Tipe section "<strong>{{ $section->section_type }}</strong>" belum memiliki editor visual.
                                                    Data metadata: <code>{{ json_encode($section->metadata) }}</code>
                                                </div>
                                        @endswitch
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width: 64px; height: 64px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                </svg>
                                <p class="empty-state-text">Belum ada section di halaman ini.</p>
                                <button type="button" @click="showAddModal = true" class="btn btn-primary">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                                    </svg>
                                    Tambah Section Pertama
                                </button>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Right Column (1/3) -->
                <div class="space-y-6">

                    {{-- Page Status --}}
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <svg class="inline w-4 h-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <path d="M12 6v6l4 2"/>
                                </svg>
                                Status Halaman
                            </h3>
                        </div>
                        <div class="card-body space-y-4">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" {{ $page->is_active ? 'checked' : '' }} class="form-checkbox mt-0.5">
                                <div>
                                    <span class="font-medium">Halaman Aktif</span>
                                    <p class="text-xs" style="color: var(--text-muted);">Aktifkan untuk ditampilkan di website</p>
                                </div>
                            </label>

                            <div>
                                <label class="form-label text-xs">Slug</label>
                                <code class="block p-2 rounded" style="background: var(--secondary); font-size: 0.85rem;">{{ $page->slug }}</code>
                            </div>

                            <div>
                                <label class="form-label text-xs">Terakhir Diperbarui</label>
                                <p class="text-sm">{{ $page->updated_at }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Ringkasan section --}}
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <svg class="inline w-4 h-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                                    <path d="M3 9h18M3 15h18M12 3v18"/>
                                </svg>
                                Ringkasan
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="grid grid-cols-3 gap-3 text-center">
                                <div class="ppid-legend-dot" style="margin: 0 auto 6px; background: var(--primary-50); color: var(--primary-dark);">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                                        <rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-lg font-bold" style="color: var(--text-primary);">{{ $sections->count() }}</div>
                                    <div class="text-xs" style="color: var(--text-muted);">Total</div>
                                </div>
                                <div>
                                    <div class="text-lg font-bold" style="color: var(--success);">{{ $visibleCount }}</div>
                                    <div class="text-xs" style="color: var(--text-muted);">Tampil</div>
                                </div>
                                <div>
                                    <div class="text-lg font-bold" style="color: var(--text-muted);">{{ $hiddenCount }}</div>
                                    <div class="text-xs" style="color: var(--text-muted);">Disembunyikan</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Panduan tipe section --}}
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <svg class="inline w-4 h-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                                </svg>
                                Tipe Section
                            </h3>
                        </div>
                        <div class="card-body">
                            @foreach($sectionTypes as $cardKey => $card)
                                <div class="ppid-legend-item">
                                    <span class="ppid-legend-dot {{ $card['color'] }}">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                             width="14" height="14" aria-hidden="true">{!! $card['icon'] !!}</svg>
                                    </span>
                                    <span>
                                        <span class="ppid-legend-name">{{ $card['label'] }}</span>
                                        <span class="ppid-legend-desc">{{ $card['description'] }}</span>
                                    </span>
                                </div>
                            @endforeach
                            <p class="text-xs mt-3" style="color: var(--text-muted);">
                                Klik badge tipe pada badge section untuk mengganti tipenya.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sticky save bar --}}
            <div class="ppid-savebar mt-6">
                <div class="ppid-savebar-info">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"
                         style="color: var(--text-muted);">
                        <circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>
                    </svg>
                    Perubahan disimpan saat Anda klik "Simpan Perubahan".
                </div>
                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>

        {{-- Delete forms (harus di luar main form — nested form tidak valid HTML) --}}
        @foreach($sections as $section)
            <form id="delete-section-{{ $section->id }}"
                  method="POST"
                  action="{{ route('admin.ppid.sections.destroy', $section->id) }}"
                  class="hidden">
                @csrf
                @method('DELETE')
            </form>
        @endforeach

        {{-- ==================== MODAL GANTI TIPE ==================== --}}
        <form id="typeChangeForm" method="POST" :action="typeChangeAction">
            @csrf
            @method('PUT')

            <div class="modal-backdrop" :class="{ 'active': showTypeModal }" x-cloak
                 @keydown.escape.window="showTypeModal = false"
                 @click.self="showTypeModal = false">

                <div class="modal modal-lg" @click.stop>
                    <div class="modal-header">
                        <div class="modal-title-group">
                            <div class="modal-head-icon amber">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="modal-title">Ganti Tipe Section</h3>
                                <p class="modal-subtitle">
                                    Section <code x-text="typeChangeKey"></code> · saat ini
                                    <strong x-text="typeChangeCurrentLabel"></strong>
                                </p>
                            </div>
                        </div>
                        <button @click="showTypeModal = false" class="modal-close" type="button" aria-label="Tutup">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="alert alert-warning mb-4">
                            <div class="alert-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                                    <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                                </svg>
                            </div>
                            <div class="alert-content">
                                <p class="alert-title">Konten akan dikosongkan</p>
                                <p class="alert-message">
                                    Mengganti tipe menghapus seluruh isi section ini. Simpan dulu bila masih ingin menyimpan konten lama.
                                </p>
                            </div>
                        </div>

                        <label class="form-label">Pilih tipe baru <span class="text-danger">*</span></label>
                        @include('admin.ppid.partials._type_cards', [
                            'radioName' => 'section_type',
                            'alpineModel' => 'typeChoice',
                        ])
                    </div>

                    <div class="modal-footer">
                        <button type="button" @click="showTypeModal = false" class="btn btn-secondary">Batal</button>
                        <button type="submit" class="btn btn-primary"
                                :disabled="!typeChoice || typeChoice === typeChangeCurrent">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                            Ganti Tipe
                        </button>
                    </div>
                </div>
            </div>
        </form>

        {{-- ==================== MODAL TAMBAH SECTION ==================== --}}
        <form id="addSectionForm" method="POST" action="{{ route('admin.ppid.sections.store', $page->slug) }}">
            @csrf

            <div class="modal-backdrop" :class="{ 'active': showAddModal }" x-cloak
                 @keydown.escape.window="showAddModal = false"
                 @click.self="showAddModal = false">

                <div class="modal modal-lg" @click.stop>
                    <div class="modal-header">
                        <div class="modal-title-group">
                            <div class="modal-head-icon cyan">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20">
                                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="modal-title">Tambah Section Baru</h3>
                                <p class="modal-subtitle">Pilih tipe tampilan, lalu isi kontennya setelah section dibuat.</p>
                            </div>
                        </div>
                        <button @click="showAddModal = false" class="modal-close" type="button" aria-label="Tutup">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                        </button>
                    </div>

                    <div class="modal-body">
                        <label class="form-label">Tipe Section <span class="text-danger">*</span></label>
                        @include('admin.ppid.partials._type_cards', [
                            'radioName' => 'section_type',
                            'alpineModel' => 'newSectionType',
                        ])

                        <div class="grid grid-cols-2 gap-4 mt-5">
                            <div>
                                <label class="form-label">Section Key <span class="text-danger">*</span></label>
                                <input type="text" name="section_key" required class="form-input"
                                       placeholder="contoh: statistik_2024"
                                       :value="newSectionKey"
                                       @input="newSectionKey = slugify($event.target.value); $event.target.value = newSectionKey">
                                <p class="text-xs mt-1" style="color: var(--text-muted);">
                                    Identifier unik. Huruf kecil, angka, underscore.
                                </p>
                            </div>
                            <div>
                                <label class="form-label">Judul (Opsional)</label>
                                <input type="text" name="title" class="form-input" placeholder="Judul section...">
                            </div>
                        </div>

                        <div class="mt-3" x-show="newSectionKey">
                            <span class="text-xs" style="color: var(--text-muted);">Key akan disimpan sebagai:</span>
                            <span class="ppid-key-preview ml-2" x-text="newSectionKey"></span>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" @click="showAddModal = false" class="btn btn-secondary">Batal</button>
                        <button type="submit" class="btn btn-primary" :disabled="!newSectionType || !newSectionKey">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            Tambah Section
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
    function ppidEditor() {
        return {
            // Modal tambah section
            showAddModal: false,
            newSectionType: '',
            newSectionKey: '',

            // Modal ganti tipe
            showTypeModal: false,
            typeChangeAction: '',
            typeChoice: '',
            typeChangeCurrent: '',
            typeChangeKey: '',
            typeChangeCurrentLabel: '',

            openTypeModal(url, currentType, sectionKey, currentLabel) {
                this.typeChangeAction = url;
                this.typeChangeCurrent = currentType;
                this.typeChangeCurrentLabel = currentLabel || currentType;
                this.typeChoice = currentType;
                this.typeChangeKey = sectionKey;
                this.showTypeModal = true;
            },

            slugify(value) {
                return value
                    .toLowerCase()
                    .replace(/[^a-z0-9_]+/g, '_')
                    .replace(/^_+|_+$/g, '');
            },
        }
    }
    </script>
    @endpush
</x-admin.layouts.app>
