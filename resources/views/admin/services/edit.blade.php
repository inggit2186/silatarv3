<x-admin.layouts.app>
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// Layanan</span>
            <h1 class="page-title">Edit Layanan</h1>
            <p class="page-subtitle">Perbarui data layanan dan persyaratan</p>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success mb-6">
            <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="alert-message">{{ session('success') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.services.update', $service->id) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid gap-6 lg:grid-cols-2">
            <!-- Basic Info -->
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center gap-3">
                        <div class="stat-icon emerald" style="width: 36px; height: 36px;">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="card-title">Informasi Dasar</h3>
                            <p class="text-sm text-muted">Data utama layanan</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Nama Layanan <span class="text-danger">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama', $service->nama) }}" required class="form-input" placeholder="Contoh: Pembuatan KTP">
                        @error('nama')
                        <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Unit Kerja</label>
                        <select name="dept_id" class="form-select">
                            <option value="">Semua Unit</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('dept_id', $service->dept_id) == $dept->id ? 'selected' : '' }}>{{ $dept->nama }}</option>
                            @endforeach
                        </select>
                        @error('dept_id')
                        <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" rows="4" class="form-input" style="height: auto; padding: 10px 12px;" placeholder="Deskripsi singkat layanan">{{ old('deskripsi', $service->deskripsi ?? '') }}</textarea>
                        @error('deskripsi')
                        <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Output Layanan</label>
                        <input type="text" name="output" value="{{ old('output', $service->output ?? '') }}" class="form-input" placeholder="Contoh: Terbitnya Surat Keterangan">
                        @error('output')
                        <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Settings -->
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center gap-3">
                        <div class="stat-icon cyan" style="width: 36px; height: 36px;">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="card-title">Pengaturan</h3>
                            <p class="text-sm text-muted">Opsi tambahan layanan</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Waktu Proses</label>
                            <input type="text" name="waktu" value="{{ old('waktu', $service->waktu ?? '') }}" class="form-input" placeholder="Contoh: 3x24 jam">
                            @error('waktu')
                            <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Biaya (Rp)</label>
                            <input type="number" name="biaya" value="{{ old('biaya', $service->biaya ?? 0) }}" min="0" class="form-input" placeholder="0">
                            <p class="text-xs mt-1" style="color: var(--text-muted);">0 = Gratis</p>
                            @error('biaya')
                            <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="1" {{ old('status', $service->status) == 1 ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('status', $service->status) == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                        @error('status')
                        <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Layanan Khusus</label>
                        <select name="spesial" class="form-select">
                            <option value="0" {{ old('spesial', $service->spesial) == 0 ? 'selected' : '' }}>Tidak</option>
                            <option value="1" {{ old('spesial', $service->spesial) == 1 ? 'selected' : '' }}>Ya</option>
                        </select>
                        @error('spesial')
                        <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3 mt-6">
                        <button type="submit" class="btn btn-primary">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Update
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Requirements Section -->
    <div class="card mt-6">
        <div class="card-header">
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center gap-3">
                    <div class="stat-icon amber" style="width: 36px; height: 36px;">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="card-title">Persyaratan Layanan</h3>
                        <p class="text-sm text-muted">{{ $requirements->count() }} persyaratan</p>
                    </div>
                </div>
                <button type="button" class="btn btn-primary" onclick="openAddRequirementModal()">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Persyaratan
                </button>
            </div>
        </div>
        <div class="card-body">
            @if($requirements->count() > 0)
                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama Persyaratan</th>
                                <th>Tipe</th>
                                <th>Deskripsi</th>
                                <th>Wajib</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requirements as $req)
                                <tr>
                                    <td>
                                        <span class="font-medium">{{ $req->syarat ?? $req->nama }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ strtoupper($req->type) }}</span>
                                    </td>
                                    <td>
                                        <span class="text-sm text-muted">{{ Str::limit($req->keterangan ?? '', 50) ?: '-' }}</span>
                                    </td>
                                    <td>
                                        @if($req->wajib == 1)
                                            <span class="badge badge-danger">Wajib</span>
                                        @else
                                            <span class="badge badge-neutral">Opsional</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button type="button" class="action-btn" title="Edit" onclick="openEditRequirementModal({{ $req->id }}, '{{ addslashes($req->syarat ?? '') }}', '{{ $req->type }}', {{ $req->wajib }}, '{{ addslashes($req->keterangan ?? '') }}')">
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h5.586a1 1 0 00.707-.293l5.414-5.414a1 1 0 000-1.414l-5.414-5.414A1 1 0 0011.828 6H16"/>
                                                </svg>
                                            </button>
                                            <button type="button" class="action-btn delete" title="Hapus" onclick="deleteRequirement({{ $req->id }})">
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state py-8">
                    <svg class="w-12 h-12 mx-auto mb-3" style="color: var(--text-muted);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="empty-state-title">Belum ada persyaratan</p>
                    <p class="empty-state-text">Tambahkan persyaratan/berkas yang diperlukan untuk layanan ini</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Requirement Modal -->
    <div id="requirementModal" class="modal-backdrop">
        <div class="modal" style="max-width: 500px;">
            <div class="modal-header">
                <div>
                    <h2 class="modal-title" id="modalTitle">Tambah Persyaratan</h2>
                </div>
                <button onclick="closeRequirementModal()" class="modal-close">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="requirementForm">
                <div class="modal-body">
                    <input type="hidden" id="requirementId" value="">
                    <div class="form-group">
                        <label class="form-label">Nama Persyaratan <span class="text-danger">*</span></label>
                        <input type="text" id="reqNama" required class="form-input" placeholder="Contoh: Scan KTP">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tipe Input <span class="text-danger">*</span></label>
                        <select id="reqType" required class="form-select">
                            <option value="file">File Upload</option>
                            <option value="textarea">Teks Panjang</option>
                            <option value="text">Teks Singkat</option>
                            <option value="date">Tanggal</option>
                            <option value="datetime">Tanggal & Waktu</option>
                            <option value="number">Angka</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea id="reqDeskripsi" rows="2" class="form-input" style="height: auto; padding: 10px 12px;" placeholder="Deskripsi singkat"></textarea>
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="checkbox" id="reqWajib" value="1" checked class="w-4 h-4">
                        <label for="reqWajib">Persyaratan wajib</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeRequirementModal()" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-primary" id="requirementSubmitBtn">Tambah</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Back Link -->
    <div class="mt-6">
        <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar Layanan
        </a>
    </div>
</x-admin.layouts.app>

@push('scripts')
<script>
const serviceId = {{ $service->id }};
const requirementModal = document.getElementById('requirementModal');
const requirementForm = document.getElementById('requirementForm');
const modalTitle = document.getElementById('modalTitle');
const requirementId = document.getElementById('requirementId');
const reqNama = document.getElementById('reqNama');
const reqType = document.getElementById('reqType');
const reqDeskripsi = document.getElementById('reqDeskripsi');
const reqWajib = document.getElementById('reqWajib');
const submitBtn = document.getElementById('requirementSubmitBtn');

function openAddRequirementModal() {
    modalTitle.textContent = 'Tambah Persyaratan';
    requirementId.value = '';
    reqNama.value = '';
    reqType.value = 'file';
    reqDeskripsi.value = '';
    reqWajib.checked = true;
    submitBtn.textContent = 'Tambah';
    requirementModal.classList.add('active');
}

function openEditRequirementModal(id, nama, type, wajib, deskripsi) {
    modalTitle.textContent = 'Edit Persyaratan';
    requirementId.value = id;
    reqNama.value = nama;
    reqType.value = type;
    reqDeskripsi.value = deskripsi || '';
    reqWajib.checked = wajib == 1;
    submitBtn.textContent = 'Update';
    requirementModal.classList.add('active');
}

function closeRequirementModal() {
    requirementModal.classList.remove('active');
}

requirementModal.addEventListener('click', function(e) {
    if (e.target === this) closeRequirementModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeRequirementModal();
});

requirementForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    const id = requirementId.value;
    const isEdit = !!id;
    const url = isEdit ? `/admin/services/${serviceId}/requirement/${id}` : `/admin/services/${serviceId}/requirement`;
    const method = isEdit ? 'PUT' : 'POST';

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-HTTP-Method-Override': method,
            },
            body: JSON.stringify({
                nama: reqNama.value,
                type: reqType.value,
                wajib: reqWajib.checked ? 1 : 0,
                deskripsi: reqDeskripsi.value,
            }),
        });
        const data = await response.json();
        if (data.success) {
            showToast('success', data.message);
            closeRequirementModal();
            setTimeout(() => location.reload(), 500);
        } else {
            showToast('error', data.message || 'Terjadi kesalahan');
        }
    } catch (error) {
        showToast('error', 'Terjadi kesalahan. Silakan coba lagi.');
    }
});

async function deleteRequirement(reqId) {
    if (!confirm('Yakin ingin menghapus persyaratan ini?')) return;
    try {
        const response = await fetch(`/admin/services/${serviceId}/requirement/${reqId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        });
        const data = await response.json();
        if (data.success) {
            showToast('success', data.message);
            setTimeout(() => location.reload(), 500);
        } else {
            showToast('error', data.message || 'Terjadi kesalahan');
        }
    } catch (error) {
        showToast('error', 'Terjadi kesalahan. Silakan coba lagi.');
    }
}

function showToast(type, message) {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <svg class="toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            ${type === 'success' ? '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>' : '<path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l2-2m-2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>'}
        </svg>
        <span class="toast-message">${message}</span>
        <button onclick="this.parentElement.remove()" class="toast-close">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    `;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 4000);
}
</script>
@endpush
