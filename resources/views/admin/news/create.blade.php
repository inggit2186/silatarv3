<x-admin.layouts.app title="{{ $title }}">
    {{-- Page Header with Neo Mirai Style --}}
    <div class="neo-page-header">
        <div class="flex items-center gap-4">
            <div class="neo-section-icon">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <div class="neo-page-header-content">
                <span class="neo-page-label">Konten</span>
                <h1 class="neo-page-title">Tambah Berita Baru</h1>
                <p class="neo-page-subtitle">Buat berita atau pengumuman baru untuk portal SILATAR</p>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid gap-6 lg:grid-cols-3">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Title --}}
                <div class="neo-card">
                    <div class="neo-card-body">
                        <label class="neo-form-label">
                            <svg class="inline h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                            Judul Berita
                        </label>
                        <input type="text" name="title" value="{{ old('title') }}" required class="neo-form-input" placeholder="Masukkan judul berita...">
                        @error('title')
                        <p class="neo-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Excerpt --}}
                <div class="neo-card">
                    <div class="neo-card-body">
                        <label class="neo-form-label">
                            <svg class="inline h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Ringkasan
                        </label>
                        <textarea name="excerpt" rows="3" required class="neo-form-textarea" placeholder="Ringkasan singkat berita (maks 500 karakter)...">{{ old('excerpt') }}</textarea>
                        @error('excerpt')
                        <p class="neo-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Content --}}
                <div class="neo-card">
                    <div class="neo-card-body">
                        <label class="neo-form-label">
                            <svg class="inline h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/>
                            </svg>
                            Konten Berita
                        </label>

                        {{-- Toolbar --}}
                        <div class="neo-editor-toolbar">
                            <div class="neo-editor-group">
                                <select id="fontSelect" onchange="execCmd('fontName', this.value)" class="neo-editor-select">
                                    <option value="Arial">Arial</option>
                                    <option value="Courier New">Courier New</option>
                                    <option value="Georgia">Georgia</option>
                                    <option value="Times New Roman">Times New Roman</option>
                                    <option value="Verdana">Verdana</option>
                                </select>
                                <select id="fontSizeSelect" onchange="execCmd('fontSize', this.value)" class="neo-editor-select w-16">
                                    <option value="1">10px</option>
                                    <option value="2">12px</option>
                                    <option value="3">14px</option>
                                    <option value="4" selected>16px</option>
                                    <option value="5">18px</option>
                                    <option value="6">24px</option>
                                    <option value="7">36px</option>
                                </select>
                            </div>

                            <div class="neo-editor-group">
                                <button type="button" onclick="execCmd('bold')" class="neo-editor-btn" title="Bold (Ctrl+B)">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M6 4h8a4 4 0 014 4 4 4 0 01-4 4H6z"/><path d="M6 12h9a4 4 0 014 4 4 4 0 01-4 4H6z"/></svg>
                                </button>
                                <button type="button" onclick="execCmd('italic')" class="neo-editor-btn" title="Italic (Ctrl+I)">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M19 4h-9M14 20H5M15 4L9 20"/></svg>
                                </button>
                                <button type="button" onclick="execCmd('underline')" class="neo-editor-btn" title="Underline (Ctrl+U)">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M6 3v7a6 6 0 006 6 6 6 0 006-6V3"/><path d="M4 21h16"/></svg>
                                </button>
                            </div>

                            <div class="neo-editor-group">
                                <button type="button" onclick="execCmd('justifyLeft')" class="neo-editor-btn" title="Align Left">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 6h18M3 12h12M3 18h18"/></svg>
                                </button>
                                <button type="button" onclick="execCmd('justifyCenter')" class="neo-editor-btn" title="Align Center">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 6h18M6 12h12M3 18h18"/></svg>
                                </button>
                                <button type="button" onclick="execCmd('justifyRight')" class="neo-editor-btn" title="Align Right">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 6h18M9 12h12M3 18h18"/></svg>
                                </button>
                            </div>

                            <div class="neo-editor-group">
                                <button type="button" onclick="execCmd('insertUnorderedList')" class="neo-editor-btn" title="Bullet List">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
                                </button>
                                <button type="button" onclick="execCmd('insertOrderedList')" class="neo-editor-btn" title="Numbered List">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M10 6h11M10 12h11M10 18h11M4 6h1v4M4 10h2M4 14h2l1 2v2h-2M7 18v-4"/></svg>
                                </button>
                            </div>

                            <div class="neo-editor-group">
                                <button type="button" onclick="execCmd('formatBlock', 'h1')" class="neo-editor-btn neo-editor-btn-text" title="Heading 1">H1</button>
                                <button type="button" onclick="execCmd('formatBlock', 'h2')" class="neo-editor-btn neo-editor-btn-text" title="Heading 2">H2</button>
                                <button type="button" onclick="execCmd('formatBlock', 'h3')" class="neo-editor-btn neo-editor-btn-text" title="Heading 3">H3</button>
                            </div>

                            <div class="neo-editor-group">
                                <button type="button" onclick="insertLink()" class="neo-editor-btn" title="Insert Link">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg>
                                </button>
                                <button type="button" onclick="openImageModal()" class="neo-editor-btn" title="Insert Image">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </button>
                            </div>
                        </div>

                        <div id="editor" contenteditable="true" class="neo-editor">{{ old('content') }}</div>
                        <input type="hidden" name="content" id="contentInput" value="{{ old('content') }}">

                        @error('content')
                        <p class="neo-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- SEO Section --}}
                <div class="neo-card">
                    <div class="neo-card-body">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="h-5 w-5" style="color: var(--gold);" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <h3 class="neo-section-title">SEO Settings</h3>
                            <span class="text-xs ml-auto" style="color: var(--ink-soft);">Optimasi untuk Google</span>
                        </div>

                        <div class="neo-form-group">
                            <label class="neo-form-label">Meta Title <span class="text-xs" style="color: var(--gold);">(Opsional)</span></label>
                            <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}" maxlength="70" class="neo-form-input" placeholder="Judul untuk SEO...">
                            <p class="neo-form-hint">Judul yang akan muncul di hasil pencarian Google. Disarankan 50-60 karakter.</p>
                        </div>

                        <div class="neo-form-group">
                            <label class="neo-form-label">Meta Description <span class="text-xs" style="color: var(--gold);">(Opsional)</span></label>
                            <textarea name="meta_description" id="meta_description" rows="3" maxlength="160" class="neo-form-textarea" placeholder="Deskripsi untuk SEO...">{{ old('meta_description') }}</textarea>
                            <p class="neo-form-hint">Deskripsi singkat yang akan muncul di hasil pencarian.</p>
                        </div>

                        <div class="neo-form-group">
                            <label class="neo-form-label">Meta Keywords <span class="text-xs" style="color: var(--gold);">(Opsional)</span></label>
                            <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}" class="neo-form-input" placeholder="kemenag, agama, tanah datar...">
                            <p class="neo-form-hint">Kata kunci untuk membantu mesin pencari. Pisahkan dengan koma.</p>
                        </div>

                        <div class="neo-seo-preview">
                            <p class="neo-seo-preview-label">Preview Google:</p>
                            <div id="seo_preview" class="neo-seo-preview-content">
                                <p id="seo_preview_title" class="neo-seo-preview-title">Judul Berita - SILATAR</p>
                                <p id="seo_preview_url" class="neo-seo-preview-url">tanahdatar.kemenag.go.id/berita/judul-berita</p>
                                <p id="seo_preview_desc" class="neo-seo-preview-desc">Deskripsi meta akan muncul di sini...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Publish Settings --}}
                <div class="neo-card">
                    <div class="neo-card-body">
                        <h3 class="neo-section-title mb-4">
                            <svg class="inline h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Pengaturan
                        </h3>

                        <div class="space-y-4">
                            <div class="neo-form-group">
                                <label class="neo-form-label">Kategori</label>
                                <select name="category" required class="neo-form-select">
                                    @foreach($categories as $key => $label)
                                    <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="neo-form-group">
                                <label class="neo-form-label">Status</label>
                                <select name="status" required class="neo-form-select">
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
                            </div>

                            <div class="neo-form-group">
                                <label class="neo-form-label">Tanggal Publish</label>
                                <input type="datetime-local" name="publish_date" value="{{ old('publish_date', \Carbon\Carbon::now()->format('Y-m-d\TH:i')) }}" class="neo-form-input">
                            </div>

                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="neo-checkbox">
                                <label for="is_featured" class="text-sm" style="color: var(--ink);">Tampilkan di Featured</label>
                            </div>

                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="is_slideshow" id="is_slideshow" value="1" {{ old('is_slideshow') ? 'checked' : '' }} class="neo-checkbox">
                                <label for="is_slideshow" class="text-sm" style="color: var(--ink);">Tampilkan di Banner</label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tim Berita --}}
                <div class="neo-card">
                    <div class="neo-card-body">
                        <h3 class="neo-section-title mb-4">
                            <svg class="inline h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Tim Berita
                        </h3>

                        <div class="space-y-4">
                            <div class="neo-form-group">
                                <label class="neo-form-label">Penulis</label>
                                <input type="text" name="writer" value="{{ old('writer', $currentUser ?? '') }}" class="neo-form-input" placeholder="Nama penulis...">
                            </div>

                            <div class="neo-form-group">
                                <label class="neo-form-label">Editor</label>
                                <input type="text" name="editor" value="{{ old('editor') }}" class="neo-form-input" placeholder="Nama editor...">
                            </div>

                            <div class="neo-form-group">
                                <label class="neo-form-label">Fotografer</label>
                                <input type="text" name="photographer" value="{{ old('photographer') }}" class="neo-form-input" placeholder="Nama fotografer...">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Image Upload --}}
                <div class="neo-card">
                    <div class="neo-card-body">
                        <h3 class="neo-section-title mb-4">
                            <svg class="inline h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Gambar Berita
                        </h3>

                        <div class="neo-image-upload">
                            <input type="file" name="image" id="imageInput" accept="image/*" class="hidden" onchange="previewImage(this)">
                            <label for="imageInput" class="neo-image-upload-label">
                                <div id="imagePreview" class="hidden w-full h-full absolute inset-0">
                                    <img id="previewImg" class="w-full h-full object-cover">
                                </div>
                                <div id="imagePlaceholder" class="neo-image-upload-placeholder">
                                    <svg class="h-8 w-8 mb-2" style="color: var(--ink-soft);" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-sm" style="color: var(--ink-soft);">Klik untuk upload</p>
                                    <p class="text-xs mt-1" style="color: var(--ink-soft); opacity: 0.6;">JPG, PNG, GIF (Maks 2MB)</p>
                                </div>
                            </label>
                        </div>
                        @error('image')
                        <p class="neo-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Submit --}}
                <div class="flex flex-col gap-3">
                    <button type="submit" class="neo-btn w-full justify-center">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Berita
                    </button>
                    <a href="{{ route('admin.news.index') }}" class="neo-btn-secondary w-full justify-center">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>

    {{-- Image Modal --}}
    <div id="imageModal" class="neo-modal-backdrop">
        <div class="neo-modal">
            <div class="neo-modal-header">
                <h3 class="neo-modal-title">Insert Gambar</h3>
                <button type="button" onclick="closeImageModal()" class="neo-modal-close">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="neo-modal-body">
                <input type="file" id="contentImageInput" accept="image/*" class="hidden" onchange="handleContentImageSelect(this)">
                <label for="contentImageInput" class="neo-image-upload">
                    <div id="contentImagePreview" class="hidden w-full h-full absolute inset-0 p-2">
                        <img id="contentPreviewImg" class="w-full h-full object-contain rounded-lg">
                    </div>
                    <div id="contentImagePlaceholder" class="neo-image-upload-placeholder">
                        <svg class="w-12 h-12 mb-3" style="color: var(--ink-soft);" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm" style="color: var(--ink-soft);">Klik untuk upload gambar</p>
                        <p class="text-xs mt-1" style="color: var(--ink-soft);">JPG, PNG, GIF, WebP (Maks 5MB)</p>
                    </div>
                </label>
                <p id="uploadError" class="neo-form-error hidden"></p>

                <div class="neo-form-group mt-4">
                    <label class="neo-form-label">URL Gambar (alternatif)</label>
                    <input type="url" id="imageUrlInput" class="neo-form-input" placeholder="https://example.com/image.jpg" oninput="previewUrlImage()">
                </div>

                <div class="neo-form-group mt-4">
                    <label class="neo-form-label">Caption</label>
                    <input type="text" id="imageCaption" class="neo-form-input" placeholder="Caption gambar (opsional)">
                </div>
            </div>
            <div class="neo-modal-footer" style="justify-content: flex-end;">
                <button type="button" onclick="closeImageModal()" class="neo-btn-secondary">Batal</button>
                <button type="button" onclick="insertContentImage()" class="neo-btn">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Insert
                </button>
            </div>
        </div>
    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('imagePreview').classList.remove('hidden');
                    document.getElementById('imagePlaceholder').classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function execCmd(command, value = null) {
            document.execCommand(command, false, value);
            document.getElementById('editor').focus();
            updateHiddenInput();
        }

        function updateHiddenInput() {
            document.getElementById('contentInput').value = document.getElementById('editor').innerHTML;
        }

        function insertLink() {
            var url = prompt('Masukkan URL:', 'https://');
            if (url) execCmd('createLink', url);
        }

        let pendingImageUrl = null;
        let pendingImageFile = null;
        let savedSelection = null;

        function openImageModal() {
            const editor = document.getElementById('editor');
            const selection = window.getSelection();
            if (selection.rangeCount > 0 && editor.contains(selection.anchorNode)) {
                savedSelection = selection.getRangeAt(0).cloneRange();
            }
            document.getElementById('imageModal').classList.add('active');
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.remove('active');
        }

        function handleContentImageSelect(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const maxSize = 5 * 1024 * 1024;
                if (file.size > maxSize) {
                    document.getElementById('uploadError').textContent = 'Ukuran file terlalu besar. Maksimal 5MB.';
                    document.getElementById('uploadError').classList.remove('hidden');
                    return;
                }
                document.getElementById('uploadError').classList.add('hidden');
                pendingImageFile = file;
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('contentPreviewImg').src = e.target.result;
                    document.getElementById('contentImagePreview').classList.remove('hidden');
                    document.getElementById('contentImagePlaceholder').classList.add('hidden');
                }
                reader.readAsDataURL(file);
            }
        }

        function previewUrlImage() {
            const url = document.getElementById('imageUrlInput').value;
            if (url) {
                document.getElementById('contentPreviewImg').src = url;
                document.getElementById('contentImagePreview').classList.remove('hidden');
                document.getElementById('contentImagePlaceholder').classList.add('hidden');
                pendingImageUrl = url;
                pendingImageFile = null;
            }
        }

        function insertContentImage() {
            const urlInput = document.getElementById('imageUrlInput').value;
            const caption = document.getElementById('imageCaption').value;

            if (pendingImageFile) {
                const formData = new FormData();
                formData.append('image', pendingImageFile);
                fetch('{{ route("admin.news.upload-image") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                    body: formData
                })
                .then(response => response.json())
                .then(data => { if (data.success) insertImageElement(data.url, caption); else alert('Upload gagal'); })
                .catch(error => alert('Error: ' + error.message));
            } else if (urlInput) {
                insertImageElement(urlInput, caption);
            } else {
                alert('Silakan upload gambar atau masukkan URL.');
                return;
            }
            closeImageModal();
        }

        function insertImageElement(src, caption) {
            const figure = document.createElement('figure');
            figure.style.cssText = 'margin: 1rem 0; text-align: center;';
            figure.innerHTML = `<img src="${src}" alt="${caption || 'Image'}" style="max-width: 100%; height: auto; border-radius: 8px;"><figcaption style="font-size: 0.875rem; color: var(--ink-soft); margin-top: 0.5rem; font-style: italic;">${caption || ''}</figcaption>`;

            const editor = document.getElementById('editor');
            if (savedSelection) {
                try {
                    const range = savedSelection.cloneRange();
                    range.deleteContents();
                    range.insertNode(figure);
                    editor.focus();
                    updateHiddenInput();
                    savedSelection = null;
                    return;
                } catch (e) { savedSelection = null; }
            }
            editor.appendChild(figure);
            updateHiddenInput();
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateHiddenInput();
            initSEOPreview();
        });

        function initSEOPreview() {
            const titleInput = document.querySelector('input[name="title"]');
            const metaTitleInput = document.getElementById('meta_title');
            const metaDescInput = document.getElementById('meta_description');
            if (metaTitleInput) metaTitleInput.addEventListener('input', updateSEOPreview);
            if (metaDescInput) metaDescInput.addEventListener('input', updateSEOPreview);
            if (titleInput) titleInput.addEventListener('input', updateSEOPreview);
            updateSEOPreview();
        }

        function updateSEOPreview() {
            const title = document.querySelector('input[name="title"]')?.value || 'Judul Berita';
            const metaTitle = document.getElementById('meta_title')?.value || title;
            const metaDesc = document.getElementById('meta_description')?.value || '';
            const slug = title.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
            const previewTitle = document.getElementById('seo_preview_title');
            const previewUrl = document.getElementById('seo_preview_url');
            const previewDesc = document.getElementById('seo_preview_desc');
            if (previewTitle) previewTitle.textContent = metaTitle + ' - SILATAR';
            if (previewUrl) previewUrl.textContent = 'tanahdatar.kemenag.go.id/berita/' + (slug || 'judul-berita');
            if (previewDesc) previewDesc.textContent = metaDesc.substring(0, 160) || 'Deskripsi meta akan muncul di sini...';
        }
    </script>
</x-admin.layouts.app>
