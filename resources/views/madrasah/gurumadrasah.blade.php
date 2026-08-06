<x-layouts.app title="Guru Madrasah - SILATAR">
    @php
        $stats = $stats ?? ['total' => 0, 'sertifikasi' => 0, 'belum_sertifikasi' => 0];
        $deptName = $deptName ?? 'Madrasah';
    @endphp

    <main class="neo-mirai madrasah-guru madrasah-fullwidth" x-data="{ expandedRows: [], showModal: false, showViewModal: false, showEditModal: false, showDeleteModal: false, selectedGuru: null }">
        <!-- Hidden data for JavaScript -->
        <script type="application/json" id="guruData">
            {!! json_encode($guruList->keyBy('id')) !!}
        </script>
        <!-- Hero Section -->
        <section class="hero-page has-bg-image">
            <div class="hero-content-wrapper">
                <div class="hero-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Data Guru
                </div>
                <h1 class="hero-title">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="1.5"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    GURU {{ strtoupper($deptName) }}
                </h1>
                <p class="hero-subtitle">Daftar guru yang tercatat dalam sistem berdasarkan unit kerja madrasah Anda.</p>
            </div>
        </section>

        <!-- Section Divider -->
        <div class="section-divider wave-rounded"></div>

        <!-- Content -->
        <section class="page-content page-content-expanded">
            <!-- Tab Navigation - Large & Prominent -->
            <div class="neo-tabs neo-tabs-large" role="tablist">
                <a href="{{ route('madrasah.profil') }}" class="neo-tab" role="tab">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>Profil</span>
                </a>
                <a href="{{ route('madrasah.pegawai') }}" class="neo-tab" role="tab">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Pegawai</span>
                </a>
                <a href="{{ route('madrasah.guru') }}" class="neo-tab is-active" role="tab">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span>Guru</span>
                </a>
                <a href="{{ route('madrasah.laporan-semester') }}" class="neo-tab" role="tab">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Semester</span>
                </a>
                <a href="{{ route('madrasah.laporan-bulanan') }}" class="neo-tab" role="tab">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Bulanan</span>
                </a>
            </div>

            <div class="content-inner">
                <!-- Stats Cards -->
                <div class="stat-grid stat-grid-3">
                    <div class="stat-card stat-primary">
                        <div class="stat-icon">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <div class="stat-info">
                            <span class="stat-label">Total Guru</span>
                            <strong class="stat-value">{{ $stats['total'] }}</strong>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon stat-icon-success">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div class="stat-info">
                            <span class="stat-label">Tersertifikasi</span>
                            <strong class="stat-value stat-value-success">{{ $stats['sertifikasi'] }}</strong>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon stat-icon-warning">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="stat-info">
                            <span class="stat-label">Belum Sertifikasi</span>
                            <strong class="stat-value stat-value-warning">{{ $stats['belum_sertifikasi'] }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Table Section -->
                <div class="neo-card table-card">
                    <div class="neo-card-header">
                        <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                        </div>
                        <div class="neo-card-text">
                            <h2 class="neo-card-title">Daftar Guru</h2>
                            <p class="neo-card-desc">Klik baris untuk melihat detail lengkap</p>
                        </div>
                        <div class="neo-card-actions">
                            <button type="button" class="neo-btn-add" @click="showModal = true">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14m-7-7h14"/></svg>
                                Tambah Guru
                            </button>
                        </div>
                    </div>
                    <div class="neo-table-wrapper table-responsive">
                        <table class="neo-table">
                            <thead class="neo-table-header">
                                <tr>
                                    <th class="col-user">Nama & NIP</th>
                                    <th class="col-mapel">Mapel / Bidang</th>
                                    <th class="col-status">Status</th>
                                    <th class="col-kontak">Kontak</th>
                                    <th class="col-aksi">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($guruList as $guru)
                                    <tr class="neo-table-row"
                                        @click="expandedRows.includes({{ $guru->id }}) ? expandedRows = expandedRows.filter(id => id !== {{ $guru->id }}) : expandedRows.push({{ $guru->id }})">
                                        <td class="neo-table-cell">
                                            <div class="neo-user-cell">
                                                <div class="neo-avatar neo-avatar-lg">
                                                    @if($guru->photo_url)
                                                        <img src="{{ $guru->photo_url }}" alt="{{ $guru->nama }}" onerror="this.parentElement.innerHTML = '<span class=\'neo-avatar-initials\'>{{ $guru->initials }}</span>'">
                                                    @else
                                                        <span class="neo-avatar-initials">{{ $guru->initials }}</span>
                                                    @endif
                                                </div>
                                                <div class="neo-user-info">
                                                    <p class="neo-user-name">{{ $guru->nama ?? '-' }}</p>
                                                    <p class="neo-user-nip">{{ $guru->nomor_induk ?? 'NIP belum terdaftar' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="neo-table-cell">
                                            <p class="neo-table-cell-primary">{{ $guru->bidang_studi_diajar ?? '-' }}</p>
                                            <p class="neo-table-cell-secondary">{{ $guru->jabatan ?? 'Guru' }}</p>
                                        </td>
                                        <td class="neo-table-cell">
                                            @php $sertifVariant = ($guru->serdik ?? 'non-sertifikasi') === 'sertifikasi' ? 'neo-badge-success' : 'neo-badge-warning'; @endphp
                                            <span class="neo-badge {{ $sertifVariant }}">
                                                <span class="neo-badge-dot"></span>
                                                {{ $guru->serdik ?? 'non-sertifikasi' }}
                                            </span>
                                        </td>
                                        <td class="neo-table-cell">
                                            @if($guru->email || $guru->telp)
                                                <div class="neo-table-cell-stack">
                                                    @if($guru->email)
                                                        <p class="neo-table-cell-mono">{{ $guru->email }}</p>
                                                    @endif
                                                    @if($guru->telp)
                                                        <p class="neo-table-cell-mono">{{ $guru->telp }}</p>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="neo-text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="neo-table-cell">
                                            <div class="neo-table-actions">
                                                <button type="button" class="neo-action-btn neo-action-btn-primary"
                                                    title="Lihat Detail" onclick='openViewGuru({{ json_encode($guru) }})'>
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                </button>
                                                @if(!$guru->user_id)
                                                    <button type="button" class="neo-action-btn neo-action-btn-edit"
                                                        title="Edit" onclick='openEditGuru({{ json_encode($guru) }})'>
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                                    </button>
                                                    <button type="button" class="neo-action-btn neo-action-btn-delete"
                                                        title="Hapus" onclick='openDeleteGuru({{ json_encode($guru) }})'>
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="neo-table-cell">
                                            <div class="neo-empty-state">
                                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--ash)" stroke-width="1.5"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                                <p class="neo-empty-title">Belum ada data guru</p>
                                                <p class="neo-empty-text">Data guru akan ditampilkan di sini setelah ditambahkan.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($guruList->hasPages())
                    <div class="neo-pagination-wrap">
                        <div class="neo-pagination-row">
                            <p class="neo-pagination-info">
                                Menampilkan {{ $guruList->firstItem() ?? 0 }} - {{ $guruList->lastItem() ?? 0 }} dari {{ $guruList->total() }} data
                            </p>
                            <div class="neo-pagination-nav">
                                @if($guruList->onFirstPage())
                                    <span class="neo-pagination-link is-disabled">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 19l-7-7 7-7"/></svg>
                                    </span>
                                @else
                                    <a href="{{ $guruList->previousPageUrl() }}" class="neo-pagination-link">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 19l-7-7 7-7"/></svg>
                                    </a>
                                @endif

                                @foreach($guruList->getUrlRange(max(1, $guruList->currentPage() - 2), min($guruList->lastPage(), $guruList->currentPage() + 2)) as $page => $url)
                                    @if($page == $guruList->currentPage())
                                        <span class="neo-pagination-link is-active">{{ $page }}</span>
                                    @else
                                        <a href="{{ $url }}" class="neo-pagination-link">{{ $page }}</a>
                                    @endif
                                @endforeach

                                @if($guruList->hasMorePages())
                                    <a href="{{ $guruList->nextPageUrl() }}" class="neo-pagination-link">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                @else
                                    <span class="neo-pagination-link is-disabled">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5l7 7-7 7"/></svg>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </section>
    </main>

    <script>
        // Store selected data
        let selectedGuru = null;

        function openViewGuru(data) {
            selectedGuru = data;
            const modal = document.getElementById('viewGuruModal');
            if (modal) {
                modal.querySelector('.modal-guru-nama').textContent = data.nama || '-';
                modal.querySelector('.modal-guru-jabatan').textContent = data.jabatan || '-';
                modal.querySelector('.modal-guru-status').textContent = data.status || '-';
                modal.querySelector('.modal-guru-serdik').textContent = data.serdik || '-';
                modal.querySelector('.modal-guru-nuptk').textContent = data.nuptk || '-';
                modal.querySelector('.modal-guru-mapel').textContent = data.bidang_studi_diajar || '-';
                modal.querySelector('.modal-guru-email').textContent = data.email || '-';
                modal.querySelector('.modal-guru-telp').textContent = data.telp || '-';
                modal.style.cssText = '';
                modal.style.display = 'flex';
            }
        }

        function openEditGuru(data) {
            selectedGuru = data;
            const modal = document.getElementById('editGuruModal');
            if (modal) {
                const setVal = (name, value) => {
                    const el = modal.querySelector(`[name="${name}"]`);
                    if (el) el.value = value || '';
                };
                setVal('edit_id', data.id);
                setVal('edit_name', data.nama);
                setVal('edit_status', data.status);
                setVal('edit_jabatan', data.jabatan);
                setVal('edit_serdik', data.serdik);
                setVal('edit_nuptk', data.nuptk);
                setVal('edit_nomor_induk', data.nomor_induk);
                setVal('edit_nik', data.nik);
                setVal('edit_tempat_lahir', data.tempat_lahir);
                setVal('edit_tanggal_lahir', data.tanggal_lahir);
                setVal('edit_jk', data.jenis_kelamin);
                setVal('edit_bidang_studi', data.bidang_studi_diajar);
                setVal('edit_tmt_tugas', data.tmt_tugas);
                setVal('edit_pendidikan', data.pendidikan);
                setVal('edit_email', data.email);
                setVal('edit_telp', data.telp);
                modal.style.cssText = '';
                modal.style.display = 'flex';
            }
        }

        function openDeleteGuru(data) {
            selectedGuru = data;
            const modal = document.getElementById('deleteGuruModal');
            if (modal) {
                modal.querySelector('.delete-guru-name').textContent = data.nama || '';
                modal.style.cssText = '';
                modal.style.display = 'flex';
            }
        }

        function closeGuruModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'none';
            }
        }

        function submitEditGuruForm() {
            const modal = document.getElementById('editGuruModal');
            const form = modal.querySelector('form');
            const formData = new FormData(form);
            formData.append('_token', '{{ csrf_token() }}');
            // Rename edit_* fields to match controller expectations
            const renameField = (from, to) => {
                if (formData.has(from)) {
                    formData.append(to, formData.get(from));
                    formData.delete(from);
                }
            };
            renameField('edit_id', 'id');
            renameField('edit_name', 'nama');
            renameField('edit_status', 'status');
            renameField('edit_jabatan', 'kat_jabatan');
            renameField('edit_serdik', 'serdik');
            renameField('edit_nuptk', 'nuptk');
            renameField('edit_nomor_induk', 'nomor_induk');
            renameField('edit_nik', 'nik');
            renameField('edit_tempat_lahir', 'tempat_lahir');
            renameField('edit_tanggal_lahir', 'tanggal_lahir');
            renameField('edit_jk', 'jenis_kelamin');
            renameField('edit_bidang_studi', 'bidang_studi_diajar');
            renameField('edit_tmt_tugas', 'tmt_tugas');
            renameField('edit_pendidikan', 'pendidikan');
            renameField('edit_email', 'email');
            renameField('edit_telp', 'telp');

            fetch('{{ route("madrasah.guru.update") }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Guru berhasil diperbarui!');
                    location.reload();
                } else {
                    alert('Gagal menyimpan: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menyimpan data');
            });
        }

        function submitDeleteGuruForm() {
            if (!confirm('Yakin ingin menghapus data ini?')) return;

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('id', selectedGuru.id);

            fetch('{{ route("madrasah.guru.delete") }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Guru berhasil dihapus!');
                    location.reload();
                } else {
                    alert('Gagal menghapus: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus data');
            });
        }
    </script>

</x-layouts.app>

        <!-- MODALS (Vanilla JS) -->

        <!-- View Guru Modal -->
        <div id="viewGuruModal" class="modal-overlay">
            <div class="modal-content" style="max-width:550px">
                <div class="modal-header">
                    <h3>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Detail Guru
                    </h3>
                    <button type="button" onclick="closeGuruModal('viewGuruModal')" style="background:rgba(255,255,255,0.1);border:none;border-radius:8px;color:#94a3b8;cursor:pointer;padding:8px 12px;font-size:14px;font-weight:600;">✕</button>
                </div>
                <div class="modal-body">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                        <div style="background:#f8fafc;padding:14px;border-radius:10px;border:1px solid #e2e8f0">
                            <div style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;margin-bottom:4px">Nama</div>
                            <div style="font-size:15px;font-weight:600;color:#1e293b" class="modal-guru-nama">-</div>
                        </div>
                        <div style="background:#f8fafc;padding:14px;border-radius:10px;border:1px solid #e2e8f0">
                            <div style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;margin-bottom:4px">Jabatan</div>
                            <div style="font-size:15px;font-weight:600;color:#1e293b" class="modal-guru-jabatan">-</div>
                        </div>
                        <div style="background:#f8fafc;padding:14px;border-radius:10px;border:1px solid #e2e8f0">
                            <div style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;margin-bottom:4px">Status</div>
                            <div style="font-size:15px;font-weight:600;color:#1e293b" class="modal-guru-status">-</div>
                        </div>
                        <div style="background:#f8fafc;padding:14px;border-radius:10px;border:1px solid #e2e8f0">
                            <div style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;margin-bottom:4px">Sertifikasi</div>
                            <div style="font-size:15px;font-weight:600;color:#1e293b" class="modal-guru-serdik">-</div>
                        </div>
                        <div style="background:#f8fafc;padding:14px;border-radius:10px;border:1px solid #e2e8f0">
                            <div style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;margin-bottom:4px">NUPTK</div>
                            <div style="font-size:15px;font-weight:600;color:#1e293b;font-family:monospace" class="modal-guru-nuptk">-</div>
                        </div>
                        <div style="background:#f8fafc;padding:14px;border-radius:10px;border:1px solid #e2e8f0">
                            <div style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;margin-bottom:4px">Mapel</div>
                            <div style="font-size:15px;font-weight:600;color:#1e293b" class="modal-guru-mapel">-</div>
                        </div>
                        <div style="background:#f8fafc;padding:14px;border-radius:10px;border:1px solid #e2e8f0">
                            <div style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;margin-bottom:4px">Email</div>
                            <div style="font-size:15px;font-weight:600;color:#1e293b" class="modal-guru-email">-</div>
                        </div>
                        <div style="background:#f8fafc;padding:14px;border-radius:10px;border:1px solid #e2e8f0">
                            <div style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;margin-bottom:4px">No. HP</div>
                            <div style="font-size:15px;font-weight:600;color:#1e293b;font-family:monospace" class="modal-guru-telp">-</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeGuruModal('viewGuruModal')" style="padding:10px 20px;background:#f1f5f9;color:#475569;font-weight:600;border:1px solid #e2e8f0;border-radius:8px;cursor:pointer;font-size:14px">Tutup</button>
                </div>
            </div>
        </div>

        <!-- Edit Guru Modal -->
        <div id="editGuruModal" class="modal-overlay">
            <div class="modal-content" style="max-width:650px">
                <div class="modal-header">
                    <h3>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        Edit Guru
                    </h3>
                    <button type="button" onclick="closeGuruModal('editGuruModal')" style="background:rgba(255,255,255,0.1);border:none;border-radius:8px;color:#94a3b8;cursor:pointer;padding:8px 12px;font-size:14px;font-weight:600;">✕</button>
                </div>
                <form onsubmit="event.preventDefault(); submitEditGuruForm();" style="display:contents">
                <input type="hidden" name="edit_id" value="">
                <div class="modal-body" style="overflow-y:auto;max-height:60vh">
                    <div style="margin-bottom:20px">
                        <div style="font-weight:700;color:#d4a106;font-size:13px;text-transform:uppercase;margin-bottom:12px;padding-bottom:8px;border-bottom:2px solid #d4a106">Data Wajib</div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:#475569;margin-bottom:4px">Nama Lengkap</label>
                                <input type="text" name="edit_name" required style="width:100%;padding:10px 12px;border:2px solid #e2e8f0;border-radius:8px;font-size:14px;background:#fff">
                            </div>
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:#475569;margin-bottom:4px">Status</label>
                                <select name="edit_status" required style="width:100%;padding:10px 12px;border:2px solid #e2e8f0;border-radius:8px;font-size:14px;background:#fff">
                                    <option value="PNS">PNS</option>
                                    <option value="PPPK">PPPK</option>
                                    <option value="Honorer">Honorer</option>
                                </select>
                            </div>
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:#475569;margin-bottom:4px">Jabatan</label>
                                <select name="edit_jabatan" required style="width:100%;padding:10px 12px;border:2px solid #e2e8f0;border-radius:8px;font-size:14px;background:#fff">
                                    <option value="Guru">Guru</option>
                                    <option value="Kepala">Kepala Madrasah</option>
                                </select>
                            </div>
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:#475569;margin-bottom:4px">Sertifikasi</label>
                                <select name="edit_serdik" style="width:100%;padding:10px 12px;border:2px solid #e2e8f0;border-radius:8px;font-size:14px;background:#fff">
                                    <option value="sertifikasi">Sertifikasi</option>
                                    <option value="non-sertifikasi">Non Sertifikasi</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div style="margin-bottom:20px">
                        <div style="font-weight:700;color:#d4a106;font-size:13px;text-transform:uppercase;margin-bottom:12px;padding-bottom:8px;border-bottom:2px solid #d4a106">Data Pribadi</div>
                        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px">
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:#475569;margin-bottom:4px">NUPTK</label>
                                <input type="text" name="edit_nuptk" style="width:100%;padding:10px 12px;border:2px solid #e2e8f0;border-radius:8px;font-size:14px;font-family:monospace">
                            </div>
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:#475569;margin-bottom:4px">NIP</label>
                                <input type="text" name="edit_nomor_induk" style="width:100%;padding:10px 12px;border:2px solid #e2e8f0;border-radius:8px;font-size:14px;font-family:monospace">
                            </div>
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:#475569;margin-bottom:4px">NIK</label>
                                <input type="text" name="edit_nik" style="width:100%;padding:10px 12px;border:2px solid #e2e8f0;border-radius:8px;font-size:14px;font-family:monospace">
                            </div>
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:#475569;margin-bottom:4px">Tempat Lahir</label>
                                <input type="text" name="edit_tempat_lahir" style="width:100%;padding:10px 12px;border:2px solid #e2e8f0;border-radius:8px;font-size:14px">
                            </div>
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:#475569;margin-bottom:4px">Tanggal Lahir</label>
                                <input type="date" name="edit_tanggal_lahir" style="width:100%;padding:10px 12px;border:2px solid #e2e8f0;border-radius:8px;font-size:14px">
                            </div>
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:#475569;margin-bottom:4px">Jenis Kelamin</label>
                                <select name="edit_jk" style="width:100%;padding:10px 12px;border:2px solid #e2e8f0;border-radius:8px;font-size:14px;background:#fff">
                                    <option value="">Pilih</option>
                                    <option value="Pria">Laki-laki</option>
                                    <option value="Wanita">Perempuan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div style="margin-bottom:20px">
                        <div style="font-weight:700;color:#d4a106;font-size:13px;text-transform:uppercase;margin-bottom:12px;padding-bottom:8px;border-bottom:2px solid #d4a106">Data Mengajar</div>
                        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px">
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:#475569;margin-bottom:4px">Bidang Studi</label>
                                <input type="text" name="edit_bidang_studi" style="width:100%;padding:10px 12px;border:2px solid #e2e8f0;border-radius:8px;font-size:14px">
                            </div>
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:#475569;margin-bottom:4px">TMT Tugas</label>
                                <input type="date" name="edit_tmt_tugas" style="width:100%;padding:10px 12px;border:2px solid #e2e8f0;border-radius:8px;font-size:14px">
                            </div>
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:#475569;margin-bottom:4px">Pendidikan</label>
                                <input type="text" name="edit_pendidikan" style="width:100%;padding:10px 12px;border:2px solid #e2e8f0;border-radius:8px;font-size:14px">
                            </div>
                        </div>
                    </div>
                    <div>
                        <div style="font-weight:700;color:#d4a106;font-size:13px;text-transform:uppercase;margin-bottom:12px;padding-bottom:8px;border-bottom:2px solid #d4a106">Kontak</div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:#475569;margin-bottom:4px">Email</label>
                                <input type="email" name="edit_email" style="width:100%;padding:10px 12px;border:2px solid #e2e8f0;border-radius:8px;font-size:14px">
                            </div>
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:#475569;margin-bottom:4px">No. HP</label>
                                <input type="tel" name="edit_telp" style="width:100%;padding:10px 12px;border:2px solid #e2e8f0;border-radius:8px;font-size:14px;font-family:monospace">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeGuruModal('editGuruModal')" style="padding:10px 20px;background:#f1f5f9;color:#475569;font-weight:600;border:1px solid #e2e8f0;border-radius:8px;cursor:pointer;font-size:14px">Batal</button>
                    <button type="submit" style="padding:10px 24px;background:#d4a106;color:#0f172a;font-weight:700;border:none;border-radius:8px;cursor:pointer;font-size:14px">Simpan</button>
                </div>
                </form>
            </div>
        </div>

        <!-- Delete Guru Modal -->
        <div id="deleteGuruModal" class="modal-overlay">
            <div class="modal-content" style="max-width:400px">
                <div class="modal-header" style="background:linear-gradient(135deg,#dc2626,#991b1b)">
                    <h3>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                        Konfirmasi Hapus
                    </h3>
                    <button type="button" onclick="closeGuruModal('deleteGuruModal')" style="background:rgba(255,255,255,0.1);border:none;border-radius:8px;color:#fecaca;cursor:pointer;padding:8px 12px;font-size:14px;font-weight:600;">✕</button>
                </div>
                <div class="modal-body" style="text-align:center;padding:32px">
                    <div style="width:64px;height:64px;background:rgba(239,68,68,0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                    </div>
                    <h4 style="margin:0 0 8px;font-size:18px;color:#1e293b">Hapus Data Guru?</h4>
                    <p style="color:#64748b;margin:0;font-size:14px">Anda yakin ingin menghapus <strong style="color:#dc2626" class="delete-guru-name">-</strong>?<br>Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="modal-footer" style="justify-content:center">
                    <button type="button" onclick="closeGuruModal('deleteGuruModal')" style="padding:10px 20px;background:#f1f5f9;color:#475569;font-weight:600;border:1px solid #e2e8f0;border-radius:8px;cursor:pointer;font-size:14px">Batal</button>
                    <button type="button" onclick="submitDeleteGuruForm()" style="padding:10px 20px;background:#dc2626;color:#fff;font-weight:600;border:none;border-radius:8px;cursor:pointer;font-size:14px">Ya, Hapus</button>
                </div>
            </div>
        </div>

    </main>
