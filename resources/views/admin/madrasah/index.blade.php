<x-admin.layouts.app>
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// Manajemen Madrasah</span>
            <h1 class="page-title">Manajemen Madrasah</h1>
            <p class="page-subtitle">Kelola data madrasah dan assign user</p>
        </div>
        <div class="page-actions">
            <button type="button" class="btn btn-primary" onclick="openAddModal()">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Madrasah
            </button>
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

    @if(session('error'))
        <div class="alert alert-danger mb-6">
            <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="alert-message">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Stats -->
    <div class="grid-4 mb-6">
        <div class="stat-card">
            <div class="stat-icon amber">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Total Madrasah</span>
                <span class="stat-value">{{ $madrasahs->count() }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon emerald">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Negeri</span>
                <span class="stat-value">{{ $madrasahs->where('status_lembaga', 'Negeri')->count() }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon violet">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Swasta</span>
                <span class="stat-value">{{ $madrasahs->where('status_lembaga', 'Swasta')->count() }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon cyan">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Total User</span>
                <span class="stat-value">{{ DB::table('users')->whereNotNull('madrasah_id')->count() }}</span>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card mb-6">
        <div class="card-header">
            <div class="flex items-center gap-3">
                <div class="stat-icon emerald" style="width: 36px; height: 36px;">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V16l-4-4z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="card-title">Filter Data</h3>
                    <p class="text-sm text-muted">Cari madrasah berdasarkan kriteria</p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.madrasah.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="form-group">
                    <label class="form-label">Pencarian</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama, NSM, NPSM..." class="form-input pl-10">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <select name="kategori" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($madrasahCategories as $key => $label)
                            <option value="{{ $key }}" {{ request('kategori') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="Negeri" {{ request('status') === 'Negeri' ? 'selected' : '' }}>Negeri</option>
                        <option value="Swasta" {{ request('status') === 'Swasta' ? 'selected' : '' }}>Swasta</option>
                    </select>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('admin.madrasah.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center gap-3">
                <div class="stat-icon amber" style="width: 36px; height: 36px;">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="card-title">Daftar Madrasah</h3>
                    <p class="text-sm text-muted">Menampilkan {{ $madrasahs->count() }} madrasah</p>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($madrasahs->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="w-12">No</th>
                                <th>Nama Madrasah</th>
                                <th>NSM</th>
                                <th>Kategori</th>
                                <th>Status</th>
                                <th>Dept Induk</th>
                                <th>User</th>
                                <th class="w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($madrasahs as $index => $madrasah)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="font-medium">{{ $madrasah->nama }}</div>
                                        @if($madrasah->email)
                                            <div class="text-sm text-muted">{{ $madrasah->email }}</div>
                                        @endif
                                    </td>
                                    <td>{{ $madrasah->nsm ?? '-' }}</td>
                                    <td>
                                        @php
                                            $kategoriLabels = [
                                                'mi' => 'MI',
                                                'mts' => 'MTs',
                                                'ma' => 'MA',
                                                'man' => 'MAN',
                                                'mtsn' => 'MTsN',
                                                'min' => 'MIN',
                                                'ra' => 'RA',
                                            ];
                                        @endphp
                                        <span class="badge badge-primary">{{ $kategoriLabels[$madrasah->kategori] ?? strtoupper($madrasah->kategori) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $madrasah->status_lembaga === 'Negeri' ? 'badge-success' : 'badge-warning' }}">
                                            {{ $madrasah->status_lembaga ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $dept = DB::table('ktd_department')->where('id', $madrasah->dept_id)->first();
                                        @endphp
                                        {{ $dept->nama ?? '-' }}
                                    </td>
                                    <td>
                                        @php
                                            $userCount = DB::table('users')->where('madrasah_id', $madrasah->id)->count();
                                        @endphp
                                        <span class="badge badge-info">{{ $userCount }} user</span>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <button type="button" class="btn btn-sm btn-primary" onclick="openEditModal({{ $madrasah->id }})">
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $madrasah->id }}, '{{ addslashes($madrasah->nama) }}')">
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
                <div class="empty-state">
                    <svg class="empty-state-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <h3 class="empty-state-title">Belum ada madrasah</h3>
                    <p class="empty-state-text">Klik tombol "Tambah Madrasah" untuk menambahkan madrasah baru</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="madrasahModal" class="modal hidden">
        <div class="modal-overlay" onclick="closeModal()"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Tambah Madrasah</h3>
                <button type="button" class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <form id="madrasahForm" method="POST" action="{{ route('admin.madrasah.store') }}">
                @csrf
                <input type="hidden" id="madrasah_id" name="madrasah_id" value="">
                <input type="hidden" id="method_field" name="_method" value="POST">

                <div class="modal-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Nama Madrasah <span class="text-danger">*</span></label>
                            <input type="text" id="nama" name="nama" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select id="kategori" name="kategori" class="form-select" required>
                                @foreach($madrasahCategories as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">NSM</label>
                            <input type="text" id="nsm" name="nsm" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">NPSM</label>
                            <input type="text" id="npsm" name="npsm" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Status Lembaga</label>
                            <select id="status_lembaga" name="status_lembaga" class="form-select">
                                <option value="">Pilih Status</option>
                                @foreach($statusLembaga as $status)
                                    <option value="{{ $status }}">{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Dept Induk</label>
                            <select id="dept_id" name="dept_id" class="form-select">
                                <option value="">Pilih Dept Induk</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Telepon</label>
                            <input type="text" id="telepon" name="telepon" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Website</label>
                            <input type="url" id="website" name="website" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Waktu Belajar</label>
                            <select id="waktu_belajar" name="waktu_belajar" class="form-select">
                                <option value="">Pilih Waktu</option>
                                @foreach($waktuBelajar as $waktu)
                                    <option value="{{ $waktu }}">{{ $waktu }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="form-label">Alamat</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input type="text" id="jalan" name="jalan" class="form-input" placeholder="Jalan">
                            <input type="text" id="jorong" name="jorong" class="form-input" placeholder="Jorong">
                            <input type="text" id="nagari" name="nagari" class="form-input" placeholder="Nagari">
                            <input type="text" id="kecamatan" name="kecamatan" class="form-input" placeholder="Kecamatan">
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="form-label">Visi</label>
                        <textarea id="visi" name="visi" class="form-input" rows="3"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal hidden">
        <div class="modal-overlay" onclick="closeDeleteModal()"></div>
        <div class="modal-content" style="max-width: 400px;">
            <div class="modal-header">
                <h3>Konfirmasi Hapus</h3>
                <button type="button" class="modal-close" onclick="closeDeleteModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus <strong id="deleteMadrasahName"></strong>?</p>
                <p class="text-sm text-muted mt-2">Data akan di-soft delete (status diubah menjadi tidak aktif).</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Tambah Madrasah';
            document.getElementById('madrasahForm').reset();
            document.getElementById('madrasah_id').value = '';
            document.getElementById('method_field').value = 'POST';
            document.getElementById('madrasahForm').action = '{{ route("admin.madrasah.store") }}';
            document.getElementById('madrasahModal').classList.remove('hidden');
        }

        function openEditModal(id) {
            document.getElementById('modalTitle').textContent = 'Edit Madrasah';
            document.getElementById('method_field').value = 'PUT';
            document.getElementById('madrasahForm').action = '/admin/madrasah/' + id;

            // Fetch madrasah data
            fetch('/admin/madrasah/' + id + '/profile')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const madrasah = data.data;
                        document.getElementById('madrasah_id').value = madrasah.id;
                        document.getElementById('nama').value = madrasah.nama || '';
                        document.getElementById('kategori').value = madrasah.kategori || '';
                        document.getElementById('nsm').value = madrasah.nsm || '';
                        document.getElementById('npsm').value = madrasah.npsm || '';
                        document.getElementById('status_lembaga').value = madrasah.status_lembaga || '';
                        document.getElementById('dept_id').value = madrasah.dept_id || '';
                        document.getElementById('telepon').value = madrasah.telepon || '';
                        document.getElementById('email').value = madrasah.email || '';
                        document.getElementById('website').value = madrasah.website || '';
                        document.getElementById('waktu_belajar').value = madrasah.waktu_belajar || '';
                        document.getElementById('jalan').value = madrasah.jalan || '';
                        document.getElementById('jorong').value = madrasah.jorong || '';
                        document.getElementById('nagari').value = madrasah.nagari || '';
                        document.getElementById('kecamatan').value = madrasah.kecamatan || '';
                        document.getElementById('visi').value = madrasah.visi || '';
                    }
                });

            document.getElementById('madrasahModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('madrasahModal').classList.add('hidden');
        }

        function confirmDelete(id, name) {
            document.getElementById('deleteMadrasahName').textContent = name;
            document.getElementById('deleteForm').action = '/admin/madrasah/' + id;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
    </script>
    @endpush
</x-admin.layouts.app>
