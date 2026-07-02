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

                {{-- Content with Quill Editor --}}
                <div class="neo-card">
                    <div class="neo-card-body">
                        <x-ui.quill-editor
                            :name="'content'"
                            :id="'quill-editor'"
                            :label="'Konten Berita'"
                            :content="old('content')"
                        />
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

    @push('scripts')
    <script>
        // Image preview for main featured image
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

        // SEO Preview
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

        document.addEventListener('DOMContentLoaded', function() {
            initSEOPreview();
        });
    </script>
    @endpush

</x-admin.layouts.app>
