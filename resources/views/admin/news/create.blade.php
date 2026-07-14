<x-admin.layouts.app title="{{ $title }}">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// Konten</span>
            <h1 class="page-title">Tambah Berita Baru</h1>
            <p class="page-subtitle">Buat berita atau pengumuman baru untuk portal SILATAR</p>
        </div>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid gap-6 lg:grid-cols-3">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Title -->
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">
                                <svg class="inline w-4 h-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                </svg>
                                Judul Berita
                            </label>
                            <input type="text" name="title" value="{{ old('title') }}" required class="form-input" placeholder="Masukkan judul berita...">
                            @error('title')
                            <p class="text-danger text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Excerpt -->
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">
                                <svg class="inline w-4 h-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Ringkasan
                            </label>
                            <textarea name="excerpt" rows="3" required class="form-input" style="height: auto; padding: 10px 12px;" placeholder="Ringkasan singkat berita (maks 500 karakter)...">{{ old('excerpt') }}</textarea>
                            @error('excerpt')
                            <p class="text-danger text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Content with Quill Editor -->
                <div class="card">
                    <div class="card-body">
                        <x-ui.quill-editor
                            :name="'content'"
                            :id="'quill-editor'"
                            :label="'Konten Berita'"
                            :content="old('content')"
                        />
                    </div>
                </div>

                <!-- SEO Section -->
                <div class="card">
                    <div class="card-body">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="h-5 w-5" style="color: var(--primary);" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <h3 class="card-title">SEO Settings</h3>
                            <span class="text-xs ml-auto" style="color: var(--text-muted);">Optimasi untuk Google</span>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Meta Title <span class="text-xs" style="color: var(--text-muted);">(Opsional)</span></label>
                            <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}" maxlength="70" class="form-input" placeholder="Judul untuk SEO...">
                            <p class="text-xs mt-1" style="color: var(--text-muted);">Judul yang akan muncul di hasil pencarian Google. Disarankan 50-60 karakter.</p>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Meta Description <span class="text-xs" style="color: var(--text-muted);">(Opsional)</span></label>
                            <textarea name="meta_description" id="meta_description" rows="3" maxlength="160" class="form-input" style="height: auto; padding: 10px 12px;" placeholder="Deskripsi untuk SEO...">{{ old('meta_description') }}</textarea>
                            <p class="text-xs mt-1" style="color: var(--text-muted);">Deskripsi singkat yang akan muncul di hasil pencarian.</p>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Meta Keywords <span class="text-xs" style="color: var(--text-muted);">(Opsional)</span></label>
                            <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}" class="form-input" placeholder="kemenag, agama, tanah datar...">
                            <p class="text-xs mt-1" style="color: var(--text-muted);">Kata kunci untuk membantu mesin pencari. Pisahkan dengan koma.</p>
                        </div>

                        <div style="border: 1px solid var(--border); border-radius: var(--radius); padding: 16px; background: var(--secondary-light);">
                            <p class="text-sm font-medium mb-2">Preview Google:</p>
                            <div id="seo_preview">
                                <p id="seo_preview_title" style="font-size: 16px; color: #1a0dab; text-decoration: underline; margin: 0;">Judul Berita - SILATAR</p>
                                <p id="seo_preview_url" style="font-size: 14px; color: #006621; margin: 2px 0;">tanahdatar.kemenag.go.id/berita/judul-berita</p>
                                <p id="seo_preview_desc" style="font-size: 13px; color: #545454; margin: 0;">Deskripsi meta akan muncul di sini...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Publish Settings -->
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title mb-4">
                            <svg class="inline w-4 h-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Pengaturan
                        </h3>

                        <div class="space-y-4">
                            <div class="form-group mb-0">
                                <label class="form-label">Kategori</label>
                                <select name="category" required class="form-select">
                                    @foreach($categories as $key => $label)
                                    <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-0">
                                <label class="form-label">Status</label>
                                <select name="status" required class="form-select">
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
                            </div>

                            <div class="form-group mb-0">
                                <label class="form-label">Tanggal Publish</label>
                                <input type="datetime-local" name="publish_date" value="{{ old('publish_date', \Carbon\Carbon::now()->format('Y-m-d\TH:i')) }}" class="form-input">
                            </div>

                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="w-4 h-4">
                                <label for="is_featured" class="text-sm" style="color: var(--text-primary);">Tampilkan di Featured</label>
                            </div>

                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="is_slideshow" id="is_slideshow" value="1" {{ old('is_slideshow') ? 'checked' : '' }} class="w-4 h-4">
                                <label for="is_slideshow" class="text-sm" style="color: var(--text-primary);">Tampilkan di Banner</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tim Berita -->
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title mb-4">
                            <svg class="inline w-4 h-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Tim Berita
                        </h3>

                        <div class="space-y-4">
                            <div class="form-group mb-0">
                                <label class="form-label">Penulis</label>
                                <input type="text" name="writer" value="{{ old('writer', $currentUser ?? '') }}" class="form-input" placeholder="Nama penulis...">
                            </div>

                            <div class="form-group mb-0">
                                <label class="form-label">Editor</label>
                                <input type="text" name="editor" value="{{ old('editor') }}" class="form-input" placeholder="Nama editor...">
                            </div>

                            <div class="form-group mb-0">
                                <label class="form-label">Fotografer</label>
                                <input type="text" name="photographer" value="{{ old('photographer') }}" class="form-input" placeholder="Nama fotografer...">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Image Upload -->
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title mb-4">
                            <svg class="inline w-4 h-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Gambar Berita
                        </h3>

                        <div style="border: 2px dashed var(--border); border-radius: var(--radius); padding: 24px; text-align: center; transition: all 0.2s; cursor: pointer;" id="imageUploadArea" onclick="document.getElementById('imageInput').click()">
                            <input type="file" name="image" id="imageInput" accept="image/*" class="hidden" onchange="previewImage(this)">
                            <div id="imagePreviewContainer" class="hidden mb-4">
                                <img id="previewImg" class="w-full h-48 object-cover rounded-lg">
                            </div>
                            <div id="imagePlaceholder">
                                <svg class="w-12 h-12 mx-auto mb-2" style="color: var(--text-muted);" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-sm" style="color: var(--text-muted);">Klik untuk upload</p>
                                <p class="text-xs mt-1" style="color: var(--text-muted);">JPG, PNG, GIF (Maks 2MB)</p>
                            </div>
                        </div>
                        @error('image')
                        <p class="text-danger text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex flex-col gap-3">
                    <button type="submit" class="btn btn-primary w-full justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Berita
                    </button>
                    <a href="{{ route('admin.news.index') }}" class="btn btn-secondary w-full justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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
        // Image preview
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('imagePreviewContainer').classList.remove('hidden');
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
