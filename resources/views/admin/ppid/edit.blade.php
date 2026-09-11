<x-admin.layouts.app title="{{ $title }}">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">PPID Management</span>
            <h1 class="page-title">Edit: {{ $page->title }}</h1>
            <p class="page-subtitle">Kelola konten halaman PPID</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.ppid.index') }}" class="btn btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                    <line x1="19" y1="12" x2="5" y2="12"/>
                    <polyline points="12 19 5 12 12 5"/>
                </svg>
                Kembali
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

    <div x-data="ppidEditor()" x-init="init()">
        <div class="grid gap-6 lg:grid-cols-3">
            <!-- Left Column: Page Settings & Sections (2/3) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Page Settings Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Pengaturan Halaman</h3>
                    </div>
                    <div class="card-body">
                        <form id="page-form" method="POST" action="{{ route('admin.ppid.update', $page->slug) }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label class="form-label">Judul <span class="text-danger">*</span></label>
                                <input type="text" name="title" value="{{ old('title', $page->title) }}" class="form-input" required>
                                @error('title')
                                    <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Subtitle</label>
                                <input type="text" name="subtitle" value="{{ old('subtitle', $page->subtitle) }}" class="form-input">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Meta Description</label>
                                <textarea name="meta_description" class="form-input" rows="3">{{ old('meta_description', $page->meta_description) }}</textarea>
                            </div>

                            <div class="form-group">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="is_active" value="1" {{ $page->is_active ? 'checked' : '' }}>
                                    <span>Aktif</span>
                                </label>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sections List -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Sections ({{ $sections->count() }})</h3>
                        <button @click="addSection()" class="btn btn-primary btn-sm">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            Tambah Section
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="space-y-4">
                            @forelse($sections as $section)
                                <div class="section-item" data-id="{{ $section->id }}" style="border: 1px solid #e5e7eb; border-radius: 0.75rem; padding: 1rem; background: #fafafa;">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center gap-2">
                                            <span class="badge badge-info">{{ $section->section_type }}</span>
                                            <strong>{{ $section->section_key }}</strong>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <button @click="toggleVisibility({{ $section->id }})" class="btn btn-sm" :class="{{ $section->is_visible ? 'btn-success' : 'btn-warning' }}">
                                                {{ $section->is_visible ? 'Visible' : 'Hidden' }}
                                            </button>
                                            <button @click="editSection({{ $section->id }})" class="btn btn-sm btn-secondary">Edit</button>
                                            <button @click="deleteSection({{ $section->id }})" class="btn btn-sm btn-danger">Hapus</button>
                                        </div>
                                    </div>

                                    @if($section->title)
                                        <div class="text-sm text-gray-600 mb-2">
                                            <strong>Judul:</strong> {{ $section->title }}
                                        </div>
                                    @endif

                                    @if($section->content)
                                        <div class="text-sm text-gray-600" style="max-height: 100px; overflow: hidden;">
                                            <strong>Konten:</strong> {!! Str::limit(strip_tags($section->content), 200) !!}
                                        </div>
                                    @endif

                                    @if($section->metadata)
                                        <div class="text-sm text-gray-600 mt-2">
                                            <strong>Metadata:</strong> {{ Str::limit(json_encode($section->metadata), 200) }}
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="empty-state">
                                    <div class="empty-state-title">Belum ada section</div>
                                    <div class="empty-state-text">Klik "Tambah Section" untuk menambahkan konten baru.</div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Actions (1/3) -->
            <div class="space-y-6">
                <!-- Status Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Informasi</h3>
                    </div>
                    <div class="card-body">
                        <div class="space-y-3">
                            <div>
                                <strong class="text-sm text-gray-600">Slug:</strong>
                                <div><code>{{ $page->slug }}</code></div>
                            </div>
                            <div>
                                <strong class="text-sm text-gray-600">URL Publik:</strong>
                                <div>
                                    @php
                                        $publicRoute = $page->slug === 'index' ? 'ppid' : 'ppid.' . $page->slug;
                                    @endphp
                                    <a href="{{ route($publicRoute) }}" target="_blank" class="text-cyan-600 hover:underline">
                                        {{ route($publicRoute) }}
                                    </a>
                                </div>
                            </div>
                            <div>
                                <strong class="text-sm text-gray-600">Total Sections:</strong>
                                <div>{{ $sections->count() }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="card">
                    <div class="card-body">
                        <button type="submit" form="page-form" class="btn btn-primary w-full">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                                <polyline points="17 21 17 13 7 13 7 21"/>
                                <polyline points="7 3 7 8 15 8"/>
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Aksi Cepat</h3>
                    </div>
                    <div class="card-body space-y-2">
                        <a href="{{ route('admin.ppid.gallery', $page->slug) }}" class="btn btn-secondary w-full">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <polyline points="21 15 16 10 5 21"/>
                            </svg>
                            Kelola Gallery
                        </a>
                        @php
                            $publicRoute = $page->slug === 'index' ? 'ppid' : 'ppid.' . $page->slug;
                        @endphp
                        <a href="{{ route($publicRoute) }}" target="_blank" class="btn btn-secondary w-full">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                                <polyline points="15 3 21 3 21 9"/>
                                <line x1="10" y1="14" x2="21" y2="3"/>
                            </svg>
                            Lihat di Website
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Section Modal -->
    <div x-show="showAddModal" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4" @click.away="showAddModal = false">
            <h3 class="text-lg font-semibold mb-4">Tambah Section Baru</h3>

            <div class="space-y-4">
                <div class="form-group">
                    <label class="form-label">Section Key <span class="text-danger">*</span></label>
                    <input type="text" x-model="newSection.section_key" class="form-input" placeholder="contoh: hero, stats, visi">
                </div>

                <div class="form-group">
                    <label class="form-label">Section Type <span class="text-danger">*</span></label>
                    <select x-model="newSection.section_type" class="form-input">
                        <option value="">Pilih tipe...</option>
                        <option value="text">Text (Rich Content)</option>
                        <option value="list">List (Daftar)</option>
                        <option value="card_grid">Card Grid (Kartu)</option>
                        <option value="timeline">Timeline (Prosedur)</option>
                        <option value="stats">Statistics (Angka)</option>
                        <option value="table">Table (Tabel)</option>
                        <option value="form_fields">Form Fields (Formulir)</option>
                        <option value="image">Image (Gambar)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Judul</label>
                    <input type="text" x-model="newSection.title" class="form-input" placeholder="Judul section (opsional)">
                </div>

                <div class="flex gap-3 mt-6">
                    <button @click="showAddModal = false" class="btn btn-secondary flex-1">Batal</button>
                    <button @click="saveNewSection()" class="btn btn-primary flex-1">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function ppidEditor() {
            return {
                showAddModal: false,
                newSection: {
                    section_key: '',
                    section_type: '',
                    title: '',
                },

                init() {
                    console.log('PPID Editor initialized');
                },

                addSection() {
                    this.newSection = {
                        section_key: '',
                        section_type: '',
                        title: '',
                    };
                    this.showAddModal = true;
                },

                async saveNewSection() {
                    if (!this.newSection.section_key || !this.newSection.section_type) {
                        alert('Section key dan type harus diisi');
                        return;
                    }

                    try {
                        const response = await fetch('{{ route("admin.ppid.sections.store", $page->slug) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body: JSON.stringify(this.newSection),
                        });

                        const result = await response.json();

                        if (result.success) {
                            window.location.reload();
                        } else {
                            alert(result.message || 'Gagal menyimpan section');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menyimpan section');
                    }
                },

                editSection(id) {
                    // For now, just alert. In the future, could open a modal or redirect
                    alert('Edit section ID: ' + id);
                },

                async deleteSection(id) {
                    if (!confirm('Apakah Anda yakin ingin menghapus section ini?')) {
                        return;
                    }

                    try {
                        const response = await fetch(`/admin/ppid/sections/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                        });

                        const result = await response.json();

                        if (result.success) {
                            window.location.reload();
                        } else {
                            alert(result.message || 'Gagal menghapus section');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menghapus section');
                    }
                },

                async toggleVisibility(id) {
                    // Toggle visibility via AJAX
                    try {
                        const response = await fetch(`/admin/ppid/sections/${id}/toggle`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                        });

                        const result = await response.json();

                        if (result.success) {
                            window.location.reload();
                        } else {
                            alert(result.message || 'Gagal mengubah visibilitas');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan');
                    }
                },
            };
        }
    </script>
</x-admin.layouts.app>
