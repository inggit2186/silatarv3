<x-admin.layouts.app title="{{ $title }}">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">PPID Management</span>
            <h1 class="page-title">Gallery: {{ $page->title }}</h1>
            <p class="page-subtitle">Kelola gambar untuk halaman ini</p>
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

    <div x-data="galleryManager()" x-init="init()">
        <!-- Upload Card -->
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="card-title">Upload Gambar Baru</h3>
            </div>
            <div class="card-body">
                <form id="upload-form" enctype="multipart/form-data">
                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="form-group">
                            <label class="form-label">Judul Gambar <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-input" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Deskripsi</label>
                            <input type="text" name="description" class="form-input">
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <label class="form-label">File Gambar <span class="text-danger">*</span></label>
                        <input type="file" name="image" accept="image/*" class="form-input" required @change="previewImage($event)">
                    </div>

                    <!-- Preview -->
                    <div x-show="previewUrl" class="mt-4">
                        <img :src="previewUrl" class="rounded-lg" style="max-height: 200px;">
                    </div>

                    <button type="submit" class="btn btn-primary mt-4" :disabled="uploading">
                        <span x-show="!uploading">Upload</span>
                        <span x-show="uploading">Uploading...</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Gallery Grid -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Gambar ({{ $galleryItems->count() }})</h3>
            </div>
            <div class="card-body">
                @if($galleryItems->count() > 0)
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        @foreach($galleryItems as $item)
                            <div class="gallery-item" data-id="{{ $item->id }}" style="border: 1px solid #e5e7eb; border-radius: 0.75rem; overflow: hidden;">
                                <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" class="w-full h-48 object-cover">
                                <div class="p-4">
                                    <h4 class="font-semibold">{{ $item->title }}</h4>
                                    @if($item->description)
                                        <p class="text-sm text-gray-600 mt-1">{{ $item->description }}</p>
                                    @endif
                                    <div class="flex items-center justify-between mt-3">
                                        <span class="badge {{ $item->is_active ? 'badge-success' : 'badge-warning' }}">
                                            {{ $item->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                        <button @click="deleteGalleryItem({{ $item->id }})" class="btn btn-sm btn-danger">Hapus</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-state-title">Belum ada gambar</div>
                        <div class="empty-state-text">Upload gambar pertama menggunakan form di atas.</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function galleryManager() {
            return {
                previewUrl: null,
                uploading: false,

                init() {
                    // Handle form submission
                    document.getElementById('upload-form').addEventListener('submit', async (e) => {
                        e.preventDefault();
                        await this.uploadImage(e.target);
                    });
                },

                previewImage(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.previewUrl = URL.createObjectURL(file);
                    }
                },

                async uploadImage(form) {
                    this.uploading = true;

                    try {
                        const formData = new FormData(form);
                        const response = await fetch('{{ route("admin.ppid.gallery.upload", $page->slug) }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body: formData,
                        });

                        const result = await response.json();

                        if (result.success) {
                            window.location.reload();
                        } else {
                            alert(result.message || 'Gagal upload gambar');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat upload');
                    } finally {
                        this.uploading = false;
                    }
                },

                async deleteGalleryItem(id) {
                    if (!confirm('Apakah Anda yakin ingin menghapus gambar ini?')) {
                        return;
                    }

                    try {
                        const response = await fetch(`/admin/ppid/gallery/${id}`, {
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
                            alert(result.message || 'Gagal menghapus gambar');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menghapus');
                    }
                },
            };
        }
    </script>
</x-admin.layouts.app>
