<x-admin.layouts.app title="{{ $title }}">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">PPID Management</span>
            <h1 class="page-title">Edit Section</h1>
            <p class="page-subtitle">Edit section: {{ $section->section_key }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.ppid.edit', $page->slug) }}" class="btn btn-secondary">
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

    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Left Column: Section Settings (2/3) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Section Info Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Section Information</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <strong class="text-sm text-gray-600">Section Key:</strong>
                            <div><code>{{ $section->section_key }}</code></div>
                        </div>
                        <div>
                            <strong class="text-sm text-gray-600">Section Type:</strong>
                            <div><span class="badge badge-info">{{ $section->section_type }}</span></div>
                        </div>
                        <div>
                            <strong class="text-sm text-gray-600">Sort Order:</strong>
                            <div>{{ $section->sort_order }}</div>
                        </div>
                        <div>
                            <strong class="text-sm text-gray-600">Status:</strong>
                            <div>
                                @if($section->is_visible)
                                    <span class="badge badge-success">Visible</span>
                                @else
                                    <span class="badge badge-warning">Hidden</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Form -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Section Content</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.ppid.sections.update', $section->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" value="{{ old('title', $section->title) }}" class="form-input">
                        </div>

                        @if(in_array($section->section_type, ['text']))
                            <div class="form-group">
                                <label class="form-label">Content</label>
                                <x-ui.jodit-editor
                                    :name="'content'"
                                    :id="'jodit-editor-content'"
                                    :label="'Konten Section'"
                                    :content="old('content', $section->content ?? '')"
                                    :height="500"
                                />
                            </div>
                        @endif

                        @if(in_array($section->section_type, ['list']))
                            <div class="form-group">
                                <label class="form-label">List Items (JSON Array)</label>
                                <textarea name="metadata[items]" class="form-input" rows="8" placeholder='["Item 1", "Item 2", "Item 3"]'>{{ is_array($section->metadata['items'] ?? null) ? json_encode($section->metadata['items'], JSON_PRETTY_PRINT) : '[]' }}</textarea>
                                <p class="text-xs mt-1" style="color: var(--text-muted);">Format: JSON array of strings. Example: ["Item 1", "Item 2"]</p>
                            </div>
                        @endif

                        @if(in_array($section->section_type, ['card_grid']))
                            <div class="form-group">
                                <label class="form-label">Cards (JSON Array)</label>
                                <textarea name="metadata[cards]" class="form-input" rows="10" placeholder='[{"title":"Card Title","description":"Description"}]'>{{ is_array($section->metadata['cards'] ?? null) ? json_encode($section->metadata['cards'], JSON_PRETTY_PRINT) : '[]' }}</textarea>
                                <p class="text-xs mt-1" style="color: var(--text-muted);">Format: JSON array of objects with title, description, and optional link fields.</p>
                            </div>
                        @endif

                        @if(in_array($section->section_type, ['timeline']))
                            <div class="form-group">
                                <label class="form-label">Steps (JSON Array)</label>
                                <textarea name="metadata[steps]" class="form-input" rows="10" placeholder='[{"number":1,"title":"Step 1","description":"Description"}]'>{{ is_array($section->metadata['steps'] ?? null) ? json_encode($section->metadata['steps'], JSON_PRETTY_PRINT) : '[]' }}</textarea>
                                <p class="text-xs mt-1" style="color: var(--text-muted);">Format: JSON array of objects with number, title, and description fields.</p>
                            </div>
                        @endif

                        @if(in_array($section->section_type, ['stats']))
                            <div class="form-group">
                                <label class="form-label">Stats (JSON Array)</label>
                                <textarea name="metadata[stats]" class="form-input" rows="8" placeholder='[{"value":"156","label":"Total"}]'>{{ is_array($section->metadata['stats'] ?? null) ? json_encode($section->metadata['stats'], JSON_PRETTY_PRINT) : '[]' }}</textarea>
                                <p class="text-xs mt-1" style="color: var(--text-muted);">Format: JSON array of objects with value and label fields.</p>
                            </div>
                        @endif

                        @if(in_array($section->section_type, ['table']))
                            <div class="form-group">
                                <label class="form-label">Headers (JSON Array)</label>
                                <textarea name="metadata[headers]" class="form-input" rows="4" placeholder='["Column 1", "Column 2"]'>{{ is_array($section->metadata['headers'] ?? null) ? json_encode($section->metadata['headers'], JSON_PRETTY_PRINT) : '[]' }}</textarea>
                                <p class="text-xs mt-1" style="color: var(--text-muted);">Format: JSON array of strings for table headers.</p>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Rows (JSON Array of Arrays)</label>
                                <textarea name="metadata[rows]" class="form-input" rows="10" placeholder='[["Row 1 Col 1", "Row 1 Col 2"], ["Row 2 Col 1", "Row 2 Col 2"]]'>{{ is_array($section->metadata['rows'] ?? null) ? json_encode($section->metadata['rows'], JSON_PRETTY_PRINT) : '[]' }}</textarea>
                                <p class="text-xs mt-1" style="color: var(--text-muted);">Format: JSON array of arrays. Each inner array represents a row.</p>
                            </div>
                        @endif

                        @if(in_array($section->section_type, ['form_fields']))
                            <div class="form-group">
                                <label class="form-label">Form Fields (JSON Array)</label>
                                <textarea name="metadata[fields]" class="form-input" rows="12" placeholder='[{"name":"field","label":"Label","type":"text","required":true}]'>{{ is_array($section->metadata['fields'] ?? null) ? json_encode($section->metadata['fields'], JSON_PRETTY_PRINT) : '[]' }}</textarea>
                                <p class="text-xs mt-1" style="color: var(--text-muted);">Format: JSON array of objects with name, label, type (text/email/textarea/select), required, and optional options fields.</p>
                            </div>
                        @endif

                        <div class="form-group">
                            <label class="form-label">Sort Order</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', $section->sort_order) }}" class="form-input" min="0">
                        </div>

                        <div class="form-group">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="is_visible" value="1" {{ $section->is_visible ? 'checked' : '' }}>
                                <span>Visible</span>
                            </label>
                        </div>

                        <div class="flex gap-3 mt-6">
                            <a href="{{ route('admin.ppid.edit', $page->slug) }}" class="btn btn-secondary flex-1">Batal</a>
                            <button type="submit" class="btn btn-primary flex-1">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column: Info (1/3) -->
        <div class="space-y-6">
            <!-- Section Info -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Info</h3>
                </div>
                <div class="card-body space-y-3">
                    <div>
                        <strong class="text-sm text-gray-600">Page:</strong>
                        <div>{{ $page->title }}</div>
                    </div>
                    <div>
                        <strong class="text-sm text-gray-600">Slug:</strong>
                        <div><code>{{ $page->slug }}</code></div>
                    </div>
                    <div>
                        <strong class="text-sm text-gray-600">Section ID:</strong>
                        <div>{{ $section->id }}</div>
                    </div>
                    <div>
                        <strong class="text-sm text-gray-600">Created:</strong>
                        <div>{{ $section->created_at }}</div>
                    </div>
                    <div>
                        <strong class="text-sm text-gray-600">Updated:</strong>
                        <div>{{ $section->updated_at }}</div>
                    </div>
                </div>
            </div>

            <!-- Help Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Bantuan</h3>
                </div>
                <div class="card-body">
                    <div class="text-sm text-gray-600 space-y-2">
                        <p><strong>Section Key:</strong> Identifier unik untuk section ini (tidak bisa diubah)</p>
                        <p><strong>Section Type:</strong> Tipe konten section (text, list, card_grid, dll)</p>
                        <p><strong>Metadata:</strong> Data JSON untuk section. Format tergantung tipe section.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin.layouts.app>
