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
                                                        title="Edit" onclick='openEditGuru({{ json_encode($guru) }}, false)'>
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                                    </button>
                                                    <button type="button" class="neo-action-btn neo-action-btn-delete"
                                                        title="Hapus" onclick='openDeleteGuru({{ json_encode($guru) }})'>
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                                                    </button>
                                                @else
                                                    <button type="button" class="neo-action-btn neo-action-btn-edit"
                                                        title="Edit Data Pendukung Guru" onclick='openEditGuru({{ json_encode($guru) }}, true)'>
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
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

        <!-- Modal Tambah Guru -->
        <template x-if="showModal">
            <div style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(8px); z-index: 9999; display: flex; align-items: center; justify-content: center; animation: fadeIn 0.2s ease-out;"
                 @click.self="showModal = false"
                 @keydown.escape.window="showModal = false">
                <div style="display: flex; flex-direction: column; max-height: 90vh; background: var(--paper); border-radius: 16px; width: 95%; max-width: 650px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.1); animation: slideUp 0.3s ease-out;">
                    <!-- Header -->
                    <div style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); padding: 1.25rem 1.5rem; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.1); flex-shrink: 0;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #eab308 0%, #ca8a04 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(234, 179, 8, 0.4);">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0f172a" stroke-width="2.5"><path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                            </div>
                            <div>
                                <h3 style="margin: 0; font-size: 1.1rem; font-weight: 700; color: #f8fafc; font-family: var(--font-display);">Tambah Guru Baru</h3>
                                <p style="margin: 0; font-size: 0.7rem; color: #94a3b8;">Lengkapi data guru baru</p>
                            </div>
                        </div>
                        <button type="button" @click="showModal = false" style="background: rgba(255,255,255,0.1); border: none; color: #94a3b8; cursor: pointer; padding: 0.5rem; border-radius: 8px; transition: all 0.2s;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <!-- Body - Scrollable -->
                    <div style="padding: 1.25rem; overflow-y: auto; flex: 1;">
                        <form @submit.prevent="submitFormGuru" id="guruForm">
                            @csrf
                            <!-- Section 1: Data Wajib -->
                            <div style="margin-bottom: 1rem;">
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem; padding-bottom: 0.5rem; border-bottom: 2px solid var(--gold);">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                                    <span style="font-family: var(--font-display); font-weight: 600; color: var(--ink); font-size: 0.85rem;">1. Data Wajib</span>
                                    <span style="margin-left: auto; background: #fef3c7; color: #92400e; font-size: 0.6rem; padding: 0.15rem 0.4rem; border-radius: 4px; font-weight: 600;">WAJIB</span>
                                </div>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                                    <div>
                                        <label style="display: block; font-weight: 600; margin-bottom: 0.35rem; color: var(--ink); font-size: 0.8rem;">Nama Lengkap *</label>
                                        <input type="text" name="name" required placeholder="Nama lengkap" style="width: 100%; padding: 0.6rem 0.75rem; border: 2px solid var(--line); border-radius: 8px; font-size: 0.85rem; background: var(--paper); color: var(--ink); transition: all 0.2s; box-sizing: border-box;">
                                    </div>
                                    <div>
                                        <label style="display: block; font-weight: 600; margin-bottom: 0.35rem; color: var(--ink); font-size: 0.8rem;">Status *</label>
                                        <select name="status" required onchange="toggleAsnFieldsGuru(this.value)" style="width: 100%; padding: 0.6rem 0.75rem; border: 2px solid var(--line); border-radius: 8px; font-size: 0.85rem; background: var(--paper); color: var(--ink); transition: all 0.2s; box-sizing: border-box; cursor: pointer;">
                                            <option value="">Pilih Status</option>
                                            <option value="PNS">PNS</option>
                                            <option value="PPPK">PPPK</option>
                                            <option value="Honorer">Honorer</option>
                                        </select>
                                    </div>
                                </div>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-top: 0.75rem;">
                                    <div>
                                        <label style="display: block; font-weight: 600; margin-bottom: 0.35rem; color: var(--ink); font-size: 0.8rem;">Jabatan *</label>
                                        <select name="kat_jabatan" required style="width: 100%; padding: 0.6rem 0.75rem; border: 2px solid var(--line); border-radius: 8px; font-size: 0.85rem; background: var(--paper); color: var(--ink); transition: all 0.2s; box-sizing: border-box; cursor: pointer;">
                                            <option value="">Pilih</option>
                                            <option value="guru">Guru</option>
                                            <option value="kepala">Kepala</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label style="display: block; font-weight: 600; margin-bottom: 0.35rem; color: var(--ink); font-size: 0.8rem;">Sertifikasi</label>
                                        <select name="serdik" style="width: 100%; padding: 0.6rem 0.75rem; border: 2px solid var(--line); border-radius: 8px; font-size: 0.85rem; background: var(--paper); color: var(--ink); transition: all 0.2s; box-sizing: border-box; cursor: pointer;">
                                            <option value="">Pilih</option>
                                            <option value="sertifikasi">Sertifikasi</option>
                                            <option value="non-sertifikasi">Non Sertifikasi</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <br/>
                            <!-- Section 2: Data Pribadi -->
                            <div style="margin-bottom: 1rem;">
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem; padding-bottom: 0.5rem; border-bottom: 2px solid var(--gold);">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a4 4 0 004 4h2"/><circle cx="8.5" cy="7" r="4"/></svg>
                                    <span style="font-family: var(--font-display); font-weight: 600; color: var(--ink); font-size: 0.85rem;">2. Data Pribadi</span>
                                    <span style="margin-left: auto; background: #e2e8f0; color: #64748b; font-size: 0.6rem; padding: 0.15rem 0.4rem; border-radius: 4px; font-weight: 600;">OPSIONAL</span>
                                </div>
                                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; margin-bottom: 0.75rem;">
                                    <div>
                                        <label style="display: block; font-weight: 600; margin-bottom: 0.35rem; color: var(--ink); font-size: 0.8rem;">NUPTK</label>
                                        <input type="text" name="nuptk" placeholder="NUPTK" style="width: 100%; padding: 0.6rem 0.75rem; border: 2px solid var(--line); border-radius: 8px; font-size: 0.85rem; background: var(--paper); color: var(--ink); transition: all 0.2s; box-sizing: border-box; font-family: var(--font-mono);">
                                    </div>
                                    <div id="nip-field">
                                        <label style="display: block; font-weight: 600; margin-bottom: 0.35rem; color: var(--ink); font-size: 0.8rem;">NIP / NIK</label>
                                        <input type="text" name="nomor_induk" placeholder="NIP" style="width: 100%; padding: 0.6rem 0.75rem; border: 2px solid var(--line); border-radius: 8px; font-size: 0.85rem; background: var(--paper); color: var(--ink); transition: all 0.2s; box-sizing: border-box; font-family: var(--font-mono);">
                                    </div>
                                    <div id="nik-field">
                                        <label style="display: block; font-weight: 600; margin-bottom: 0.35rem; color: var(--ink); font-size: 0.8rem;">NIK</label>
                                        <input type="text" name="nik" placeholder="NIK" style="width: 100%; padding: 0.6rem 0.75rem; border: 2px solid var(--line); border-radius: 8px; font-size: 0.85rem; background: var(--paper); color: var(--ink); transition: all 0.2s; box-sizing: border-box; font-family: var(--font-mono);">
                                    </div>
                                </div>
                                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem;">
                                    <div>
                                        <label style="display: block; font-weight: 600; margin-bottom: 0.35rem; color: var(--ink); font-size: 0.8rem;">Tempat Lahir</label>
                                        <input type="text" name="tempat_lahir" placeholder="Tempat Lahir" style="width: 100%; padding: 0.6rem 0.75rem; border: 2px solid var(--line); border-radius: 8px; font-size: 0.85rem; background: var(--paper); color: var(--ink); transition: all 0.2s; box-sizing: border-box;">
                                    </div>
                                    <div>
                                        <label style="display: block; font-weight: 600; margin-bottom: 0.35rem; color: var(--ink); font-size: 0.8rem;">Tanggal Lahir</label>
                                        <input type="date" name="tanggal_lahir" style="width: 100%; padding: 0.6rem 0.75rem; border: 2px solid var(--line); border-radius: 8px; font-size: 0.85rem; background: var(--paper); color: var(--ink); transition: all 0.2s; box-sizing: border-box;">
                                    </div>
                                    <div>
                                        <label style="display: block; font-weight: 600; margin-bottom: 0.35rem; color: var(--ink); font-size: 0.8rem;">Jenis Kelamin</label>
                                        <select name="jk" style="width: 100%; padding: 0.6rem 0.75rem; border: 2px solid var(--line); border-radius: 8px; font-size: 0.85rem; background: var(--paper); color: var(--ink); transition: all 0.2s; box-sizing: border-box; cursor: pointer;">
                                            <option value="">Pilih</option>
                                            <option value="Pria">Laki-laki</option>
                                            <option value="Wanita">Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <br/>
                            <!-- Section 3: Data Mengajar -->
                            <div style="margin-bottom: 1rem;">
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem; padding-bottom: 0.5rem; border-bottom: 2px solid var(--gold);">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>
                                    <span style="font-family: var(--font-display); font-weight: 600; color: var(--ink); font-size: 0.85rem;">3. Data Mengajar</span>
                                    <span style="margin-left: auto; background: #e2e8f0; color: #64748b; font-size: 0.6rem; padding: 0.15rem 0.4rem; border-radius: 4px; font-weight: 600;">OPSIONAL</span>
                                </div>
                                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem;">
                                    <div>
                                        <label style="display: block; font-weight: 600; margin-bottom: 0.35rem; color: var(--ink); font-size: 0.8rem;">Bidang Studi</label>
                                        <input type="text" name="bidang_studi_diajar" placeholder="Bidang Studi" style="width: 100%; padding: 0.6rem 0.75rem; border: 2px solid var(--line); border-radius: 8px; font-size: 0.85rem; background: var(--paper); color: var(--ink); transition: all 0.2s; box-sizing: border-box;">
                                    </div>
                                    <div>
                                        <label style="display: block; font-weight: 600; margin-bottom: 0.35rem; color: var(--ink); font-size: 0.8rem;">TMT Tugas</label>
                                        <input type="date" name="tmt_tugas" style="width: 100%; padding: 0.6rem 0.75rem; border: 2px solid var(--line); border-radius: 8px; font-size: 0.85rem; background: var(--paper); color: var(--ink); transition: all 0.2s; box-sizing: border-box;">
                                    </div>
                                    <div>
                                        <label style="display: block; font-weight: 600; margin-bottom: 0.35rem; color: var(--ink); font-size: 0.8rem;">Pendidikan</label>
                                        <input type="text" name="pendidikan" placeholder="Pendidikan" style="width: 100%; padding: 0.6rem 0.75rem; border: 2px solid var(--line); border-radius: 8px; font-size: 0.85rem; background: var(--paper); color: var(--ink); transition: all 0.2s; box-sizing: border-box;">
                                    </div>
                                </div>
                            </div>
                            <br/>
                            <!-- Section 4: Kontak -->
                            <div style="margin-bottom: 0;">
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem; padding-bottom: 0.5rem; border-bottom: 2px solid var(--gold);">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
                                    <span style="font-family: var(--font-display); font-weight: 600; color: var(--ink); font-size: 0.85rem;">4. Kontak</span>
                                    <span style="margin-left: auto; background: #e2e8f0; color: #64748b; font-size: 0.6rem; padding: 0.15rem 0.4rem; border-radius: 4px; font-weight: 600;">OPSIONAL</span>
                                </div>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                                    <div>
                                        <label style="display: block; font-weight: 600; margin-bottom: 0.35rem; color: var(--ink); font-size: 0.8rem;">Email</label>
                                        <input type="email" name="email" placeholder="email@contoh.com" style="width: 100%; padding: 0.6rem 0.75rem; border: 2px solid var(--line); border-radius: 8px; font-size: 0.85rem; background: var(--paper); color: var(--ink); transition: all 0.2s; box-sizing: border-box;">
                                    </div>
                                    <div>
                                        <label style="display: block; font-weight: 600; margin-bottom: 0.35rem; color: var(--ink); font-size: 0.8rem;">No. HP</label>
                                        <input type="tel" name="telp" placeholder="08xxxxxxxxxx" style="width: 100%; padding: 0.6rem 0.75rem; border: 2px solid var(--line); border-radius: 8px; font-size: 0.85rem; background: var(--paper); color: var(--ink); transition: all 0.2s; box-sizing: border-box; font-family: var(--font-mono);">
                                    </div>
                                </div>
                                <div style="margin-top: 0.75rem;">
                                    <label style="display: block; font-weight: 600; margin-bottom: 0.35rem; color: var(--ink); font-size: 0.8rem;">Alamat</label>
                                    <textarea name="alamat" rows="2" placeholder="Alamat lengkap" style="width: 100%; padding: 0.6rem 0.75rem; border: 2px solid var(--line); border-radius: 8px; font-size: 0.85rem; background: var(--paper); color: var(--ink); transition: all 0.2s; box-sizing: border-box; resize: vertical;"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Footer - Fixed -->
                    <div style="padding: 1rem 1.25rem; background: #f8fafc; border-top: 1px solid var(--line); display: flex; justify-content: flex-end; gap: 0.75rem; flex-shrink: 0;">
                        <button type="button" @click="showModal = false" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.25rem; background: white; color: #64748b; font-family: var(--font-display); font-size: 0.85rem; font-weight: 600; border: 2px solid #e2e8f0; border-radius: 10px; cursor: pointer; transition: all 0.2s;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
                            Batal
                        </button>
                        <button type="button" @click="submitFormGuru" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #eab308 0%, #ca8a04 100%); color: #0f172a; font-family: var(--font-display); font-size: 0.85rem; font-weight: 700; border: none; border-radius: 10px; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 12px rgba(234, 179, 8, 0.4);">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            Simpan Guru
                        </button>
                    </div>
                </div>
            </div>
        </template>

    <script>
        // Store selected data
        let selectedGuru = null;

        // Toggle ASN fields visibility based on status for Guru
        function toggleAsnFieldsGuru(status, prefix = '') {
            const nikField = document.getElementById((prefix || '') + 'nik-field');

            const isHonor = status === 'Honorer';

            // Hide separate NIK for Honor
            if (nikField) nikField.style.display = isHonor ? 'none' : '';
        }

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

        function openEditGuru(data, hasUserId = false) {
            selectedGuru = data;
            selectedGuru._hasUserId = hasUserId;
            const modal = document.getElementById('editGuruModal');
            if (modal) {
                const setVal = (name, value) => {
                    const el = modal.querySelector(`[name="${name}"]`);
                    if (el) el.value = value || '';
                };
                setVal('edit_id', data.id);
                setVal('edit_name', data.nama);
                setVal('edit_status', data.status);
                // Toggle ASN fields based on status
                toggleAsnFieldsGuru(data.status, 'edit_');
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

                // If has user_id: make Nama, Status, NIP read-only
                const readOnlyFields = ['edit_name', 'edit_status', 'edit_nomor_induk'];
                readOnlyFields.forEach(name => {
                    const el = modal.querySelector(`[name="${name}"]`);
                    if (el) {
                        el.readOnly = hasUserId;
                        el.disabled = hasUserId;
                        el.style.opacity = hasUserId ? '0.6' : '1';
                        el.style.cursor = hasUserId ? 'not-allowed' : '';
                    }
                });

                // Update modal title
                const title = modal.querySelector('h3');
                if (title) {
                    title.innerHTML = hasUserId
                        ? '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> Edit Data Pendukung Guru'
                        : '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> Edit Guru';
                }

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

        function submitFormGuru() {
            const form = document.getElementById('guruForm');
            const formData = new FormData(form);

            // If status is Honorer, copy nomor_induk to nik (both columns get same value)
            const status = formData.get('status');
            if (status === 'Honorer') {
                const nomorInduk = formData.get('nomor_induk');
                if (nomorInduk) {
                    formData.set('nik', nomorInduk);
                }
            }

            fetch('{{ route("madrasah.guru.save") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Guru berhasil ditambahkan!');
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

        function submitEditGuruForm() {
            const modal = document.getElementById('editGuruModal');
            const form = modal.querySelector('form');
            const formData = new FormData(form);
            formData.append('_token', '{{ csrf_token() }}');

            // If hasUserId: re-add read-only fields that FormData skips
            if (selectedGuru && selectedGuru._hasUserId) {
                const nameEl = modal.querySelector('[name="edit_name"]');
                const statusEl = modal.querySelector('[name="edit_status"]');
                const nipEl = modal.querySelector('[name="edit_nomor_induk"]');
                if (nameEl && !formData.has('edit_name')) formData.append('edit_name', nameEl.value);
                if (statusEl && !formData.has('edit_status')) formData.append('edit_status', statusEl.value);
                if (nipEl && !formData.has('edit_nomor_induk')) formData.append('edit_nomor_induk', nipEl.value);
            }

            // If status is Honorer, copy nomor_induk to nik (both columns get same value)
            const rawStatus = formData.get('edit_status') || '';
            if (rawStatus === 'Honorer') {
                const nomorInduk = formData.get('edit_nomor_induk');
                if (nomorInduk) {
                    formData.set('edit_nik', nomorInduk);
                }
            }

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
                    <button type="button" onclick="closeGuruModal('viewGuruModal')" style="background:rgba(255,255,255,0.1);border:none;border-radius:8px;color:#94a3b8;cursor:pointer;padding:8px 12px;font-size:14px;font-weight:600;">âœ•</button>
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
                    <button type="button" onclick="closeGuruModal('editGuruModal')" style="background:rgba(255,255,255,0.1);border:none;border-radius:8px;color:#94a3b8;cursor:pointer;padding:8px 12px;font-size:14px;font-weight:600;">âœ•</button>
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
                                <select name="edit_status" required onchange="toggleAsnFieldsGuru(this.value, 'edit_')" style="width:100%;padding:10px 12px;border:2px solid #e2e8f0;border-radius:8px;font-size:14px;background:#fff">
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
                            <div id="edit_nip-field">
                                <label style="display:block;font-size:12px;font-weight:600;color:#475569;margin-bottom:4px">NIP</label>
                                <input type="text" name="edit_nomor_induk" style="width:100%;padding:10px 12px;border:2px solid #e2e8f0;border-radius:8px;font-size:14px;font-family:monospace">
                            </div>
                            <div id="edit_nik-field">
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
                    <button type="button" onclick="closeGuruModal('deleteGuruModal')" style="background:rgba(255,255,255,0.1);border:none;border-radius:8px;color:#fecaca;cursor:pointer;padding:8px 12px;font-size:14px;font-weight:600;">âœ•</button>
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
