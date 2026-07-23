<x-layouts.app title="Pegawai Madrasah - SILATAR">
    @php
        $stats = $stats ?? ['total' => 0, 'asn' => 0, 'honorer' => 0];
        $deptName = $deptName ?? 'Madrasah';
    @endphp

    <main class="neo-mirai madrasah-pegawai madrasah-fullwidth" x-data="{ expandedRows: [], showModal: false }">
        <!-- Hero Section -->
        <section class="hero-page has-bg-image">
            <div class="hero-content-wrapper">
                <div class="hero-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Data Pegawai
                </div>
                <h1 class="hero-title">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="1.5"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    PEGAWAI {{ strtoupper($deptName) }}
                </h1>
                <p class="hero-subtitle">Daftar pegawai yang tercatat dalam sistem berdasarkan unit kerja madrasah Anda.</p>
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
                <a href="{{ route('madrasah.pegawai') }}" class="neo-tab is-active" role="tab">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Pegawai</span>
                </a>
                <a href="{{ route('madrasah.guru') }}" class="neo-tab" role="tab">
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
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div class="stat-info">
                            <span class="stat-label">Total Pegawai</span>
                            <strong class="stat-value">{{ $stats['total'] }}</strong>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon stat-icon-success">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div class="stat-info">
                            <span class="stat-label">ASN</span>
                            <strong class="stat-value stat-value-success">{{ $stats['asn'] }}</strong>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon stat-icon-warning">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="stat-info">
                            <span class="stat-label">Honorer</span>
                            <strong class="stat-value stat-value-warning">{{ $stats['honorer'] }}</strong>
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
                            <h2 class="neo-card-title">Daftar Pegawai</h2>
                            <p class="neo-card-desc">Klik baris untuk melihat detail lengkap</p>
                        </div>
                        <div class="neo-card-actions">
                            <button type="button" class="neo-btn-add" @click="showModal = true">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14m-7-7h14"/></svg>
                                Tambah Pegawai
                            </button>
                        </div>
                    </div>
                    <div class="neo-table-wrapper table-responsive">
                        <table class="neo-table">
                            <thead class="neo-table-header">
                                <tr>
                                    <th class="col-user">Nama & NIP</th>
                                    <th class="col-mapel">Jabatan / Posisi</th>
                                    <th class="col-status">Status</th>
                                    <th class="col-kontak">Kontak</th>
                                    <th class="col-aksi">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pegawaiList as $pegawai)
                                    <tr class="neo-table-row"
                                        @click="expandedRows.includes({{ $pegawai->id }}) ? expandedRows = expandedRows.filter(id => id !== {{ $pegawai->id }}) : expandedRows.push({{ $pegawai->id }})">
                                        <td class="neo-table-cell">
                                            <div class="neo-user-cell">
                                                <div class="neo-avatar neo-avatar-lg">
                                                    @if($pegawai->photo_url)
                                                        <img src="{{ $pegawai->photo_url }}" alt="{{ $pegawai->name }}" onerror="this.parentElement.innerHTML = '<span class=\'neo-avatar-initials\'>{{ $pegawai->initials }}</span>'">
                                                    @else
                                                        <span class="neo-avatar-initials">{{ $pegawai->initials }}</span>
                                                    @endif
                                                </div>
                                                <div class="neo-user-info">
                                                    <p class="neo-user-name">{{ $pegawai->name ?? '-' }}</p>
                                                    <p class="neo-user-nip">{{ $pegawai->nomor_induk ?? 'NIP belum terdaftar' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="neo-table-cell">
                                            <p class="neo-table-cell-primary">{{ $pegawai->jabatan ?? '-' }}</p>
                                            <p class="neo-table-cell-secondary">{{ $pegawai->pekerjaan ?? '-' }}</p>
                                        </td>
                                        <td class="neo-table-cell">
                                            <span class="neo-badge {{ $pegawai->asn === 'pns' || $pegawai->asn === 'cpns' || $pegawai->asn === 'pppk' ? 'neo-badge-success' : 'neo-badge-warning' }}">
                                                <span class="neo-badge-dot"></span>
                                                {{ strtoupper($pegawai->asn ?? 'Honorer') }}
                                            </span>
                                        </td>
                                        <td class="neo-table-cell">
                                            @if($pegawai->email || $pegawai->telp)
                                                <div class="neo-table-cell-stack">
                                                    @if($pegawai->email)
                                                        <p class="neo-table-cell-mono">{{ $pegawai->email }}</p>
                                                    @endif
                                                    @if($pegawai->telp)
                                                        <p class="neo-table-cell-mono">{{ $pegawai->telp }}</p>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="neo-text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="neo-table-cell">
                                            <button type="button" class="neo-action-btn neo-action-btn-primary"
                                                title="Detail" @click.stop="expandedRows.includes({{ $pegawai->id }}) ? expandedRows = expandedRows.filter(id => id !== {{ $pegawai->id }}) : expandedRows.push({{ $pegawai->id }})">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="neo-table-cell">
                                            <div class="neo-empty-state">
                                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--ash)" stroke-width="1.5"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                <p class="neo-empty-title">Belum ada data pegawai</p>
                                                <p class="neo-empty-text">Data pegawai akan ditampilkan di sini setelah ditambahkan.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($pegawaiList->hasPages())
                    <div class="neo-pagination-wrap">
                        <div class="neo-pagination-row">
                            <p class="neo-pagination-info">
                                Menampilkan {{ $pegawaiList->firstItem() ?? 0 }} - {{ $pegawaiList->lastItem() ?? 0 }} dari {{ $pegawaiList->total() }} data
                            </p>
                            <div class="neo-pagination-nav">
                                @if($pegawaiList->onFirstPage())
                                    <span class="neo-pagination-link is-disabled">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 19l-7-7 7-7"/></svg>
                                    </span>
                                @else
                                    <a href="{{ $pegawaiList->previousPageUrl() }}" class="neo-pagination-link">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 19l-7-7 7-7"/></svg>
                                    </a>
                                @endif

                                @foreach($pegawaiList->getUrlRange(max(1, $pegawaiList->currentPage() - 2), min($pegawaiList->lastPage(), $pegawaiList->currentPage() + 2)) as $page => $url)
                                    @if($page == $pegawaiList->currentPage())
                                        <span class="neo-pagination-link is-active">{{ $page }}</span>
                                    @else
                                        <a href="{{ $url }}" class="neo-pagination-link">{{ $page }}</a>
                                    @endif
                                @endforeach

                                @if($pegawaiList->hasMorePages())
                                    <a href="{{ $pegawaiList->nextPageUrl() }}" class="neo-pagination-link">
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

        <!-- Modal Tambah Pegawai -->
        <template x-if="showModal">
            <div style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(8px); z-index: 9999; display: flex; align-items: center; justify-content: center; animation: fadeIn 0.2s ease-out;"
                 @click.self="showModal = false"
                 @keydown.escape.window="showModal = false">
                <div style="display: flex; flex-direction: column; max-height: 90vh; background: var(--paper); border-radius: 16px; width: 95%; max-width: 650px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.1); animation: slideUp 0.3s ease-out;">
                    <!-- Header - Fixed -->
                    <div style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); padding: 1.25rem 1.5rem; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.1); flex-shrink: 0;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #eab308 0%, #ca8a04 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(234, 179, 8, 0.4);">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0f172a" stroke-width="2.5"><path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                            </div>
                            <div>
                                <h3 style="margin: 0; font-size: 1.1rem; font-weight: 700; color: #f8fafc; font-family: var(--font-display);">Tambah Pegawai Baru</h3>
                                <p style="margin: 0; font-size: 0.7rem; color: #94a3b8;">Lengkapi data pegawai baru</p>
                            </div>
                        </div>
                        <button type="button" @click="showModal = false" style="background: rgba(255,255,255,0.1); border: none; color: #94a3b8; cursor: pointer; padding: 0.5rem; border-radius: 8px; transition: all 0.2s;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <!-- Body - Scrollable -->
                    <div style="padding: 1.25rem; overflow-y: auto; flex: 1;">
                        <form @submit.prevent="submitForm" id="pegawaiForm">
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
                                        <select name="status" required style="width: 100%; padding: 0.6rem 0.75rem; border: 2px solid var(--line); border-radius: 8px; font-size: 0.85rem; background: var(--paper); color: var(--ink); transition: all 0.2s; box-sizing: border-box; cursor: pointer;">
                                            <option value="">Pilih Status</option>
                                            <option value="pns">PNS</option>
                                            <option value="pppk">PPPK</option>
                                            <option value="honor">HONOR</option>
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
                                        <label style="display: block; font-weight: 600; margin-bottom: 0.35rem; color: var(--ink); font-size: 0.8rem;">NIP</label>
                                        <input type="text" name="nomor_induk" placeholder="NIP" style="width: 100%; padding: 0.6rem 0.75rem; border: 2px solid var(--line); border-radius: 8px; font-size: 0.85rem; background: var(--paper); color: var(--ink); transition: all 0.2s; box-sizing: border-box; font-family: var(--font-mono);">
                                    </div>
                                    <div>
                                        <label style="display: block; font-weight: 600; margin-bottom: 0.35rem; color: var(--ink); font-size: 0.8rem;">NIK</label>
                                        <input type="text" name="nik" placeholder="NIK" style="width: 100%; padding: 0.6rem 0.75rem; border: 2px solid var(--line); border-radius: 8px; font-size: 0.85rem; background: var(--paper); color: var(--ink); transition: all 0.2s; box-sizing: border-box; font-family: var(--font-mono);">
                                    </div>
                                    <div>
                                        <label style="display: block; font-weight: 600; margin-bottom: 0.35rem; color: var(--ink); font-size: 0.8rem;">NPWP</label>
                                        <input type="text" name="npwp" placeholder="NPWP" style="width: 100%; padding: 0.6rem 0.75rem; border: 2px solid var(--line); border-radius: 8px; font-size: 0.85rem; background: var(--paper); color: var(--ink); transition: all 0.2s; box-sizing: border-box; font-family: var(--font-mono);">
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
                                            <option value="L">Laki-laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <br/>
                            <!-- Section 3: Data Kepegawaian -->
                            <div style="margin-bottom: 1rem;">
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem; padding-bottom: 0.5rem; border-bottom: 2px solid var(--gold);">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>
                                    <span style="font-family: var(--font-display); font-weight: 600; color: var(--ink); font-size: 0.85rem;">3. Data Kepegawaian</span>
                                    <span style="margin-left: auto; background: #e2e8f0; color: #64748b; font-size: 0.6rem; padding: 0.15rem 0.4rem; border-radius: 4px; font-weight: 600;">OPSIONAL</span>
                                </div>
                                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.75rem; margin-bottom: 0.75rem;">
                                    <div>
                                        <label style="display: block; font-weight: 600; margin-bottom: 0.35rem; color: var(--ink); font-size: 0.8rem;">Golongan</label>
                                        <select name="golongan" style="width: 100%; padding: 0.6rem 0.75rem; border: 2px solid var(--line); border-radius: 8px; font-size: 0.85rem; background: var(--paper); color: var(--ink); transition: all 0.2s; box-sizing: border-box; cursor: pointer;">
                                            <option value="">Pilih</option>
                                            <option value="I/a">I/a</option><option value="I/b">I/b</option><option value="I/c">I/c</option><option value="I/d">I/d</option>
                                            <option value="II/a">II/a</option><option value="II/b">II/b</option><option value="II/c">II/c</option><option value="II/d">II/d</option>
                                            <option value="III/a">III/a</option><option value="III/b">III/b</option><option value="III/c">III/c</option><option value="III/d">III/d</option>
                                            <option value="IV/a">IV/a</option><option value="IV/b">IV/b</option><option value="IV/c">IV/c</option><option value="IV/d">IV/d</option><option value="IV/e">IV/e</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label style="display: block; font-weight: 600; margin-bottom: 0.35rem; color: var(--ink); font-size: 0.8rem;">Jabatan</label>
                                        <input type="text" name="jabatan" placeholder="Jabatan" style="width: 100%; padding: 0.6rem 0.75rem; border: 2px solid var(--line); border-radius: 8px; font-size: 0.85rem; background: var(--paper); color: var(--ink); transition: all 0.2s; box-sizing: border-box;">
                                    </div>
                                    <div>
                                        <label style="display: block; font-weight: 600; margin-bottom: 0.35rem; color: var(--ink); font-size: 0.8rem;">TMT Tugas</label>
                                        <input type="date" name="tmt_tugas" style="width: 100%; padding: 0.6rem 0.75rem; border: 2px solid var(--line); border-radius: 8px; font-size: 0.85rem; background: var(--paper); color: var(--ink); transition: all 0.2s; box-sizing: border-box;">
                                    </div>
                                    <div>
                                        <label style="display: block; font-weight: 600; margin-bottom: 0.35rem; color: var(--ink); font-size: 0.8rem;">KGB</label>
                                        <input type="date" name="kgb" style="width: 100%; padding: 0.6rem 0.75rem; border: 2px solid var(--line); border-radius: 8px; font-size: 0.85rem; background: var(--paper); color: var(--ink); transition: all 0.2s; box-sizing: border-box;">
                                    </div>
                                </div>
                                <div style="display: grid; grid-template-columns: 1fr; gap: 0.75rem;">
                                    <div>
                                        <label style="display: block; font-weight: 600; margin-bottom: 0.35rem; color: var(--ink); font-size: 0.8rem;">Masa Kerja</label>
                                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                                            <input type="number" name="masa_kerja_tahun" placeholder="Tahun" min="0" style="width: 100%; padding: 0.6rem 0.75rem; border: 2px solid var(--line); border-radius: 8px; font-size: 0.85rem; background: var(--paper); color: var(--ink); transition: all 0.2s; box-sizing: border-box;">
                                            <input type="number" name="masa_kerja_bulan" placeholder="Bulan" min="0" max="11" style="width: 100%; padding: 0.6rem 0.75rem; border: 2px solid var(--line); border-radius: 8px; font-size: 0.85rem; background: var(--paper); color: var(--ink); transition: all 0.2s; box-sizing: border-box;">
                                        </div>
                                    </div>
                                </div>
                                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem;">
                                    <div>
                                        <label style="display: block; font-weight: 600; margin-bottom: 0.35rem; color: var(--ink); font-size: 0.8rem;">Jurusan</label>
                                        <input type="text" name="jurusan" placeholder="Jurusan" style="width: 100%; padding: 0.6rem 0.75rem; border: 2px solid var(--line); border-radius: 8px; font-size: 0.85rem; background: var(--paper); color: var(--ink); transition: all 0.2s; box-sizing: border-box;">
                                    </div>
                                    <div>
                                        <label style="display: block; font-weight: 600; margin-bottom: 0.35rem; color: var(--ink); font-size: 0.8rem;">Fakultas</label>
                                        <input type="text" name="fakultas" placeholder="Fakultas" style="width: 100%; padding: 0.6rem 0.75rem; border: 2px solid var(--line); border-radius: 8px; font-size: 0.85rem; background: var(--paper); color: var(--ink); transition: all 0.2s; box-sizing: border-box;">
                                    </div>
                                    <div>
                                        <label style="display: block; font-weight: 600; margin-bottom: 0.35rem; color: var(--ink); font-size: 0.8rem;">Universitas</label>
                                        <input type="text" name="universitas" placeholder="Universitas" style="width: 100%; padding: 0.6rem 0.75rem; border: 2px solid var(--line); border-radius: 8px; font-size: 0.85rem; background: var(--paper); color: var(--ink); transition: all 0.2s; box-sizing: border-box;">
                                    </div>
                                </div>
                            </div>
                            <br/>
                            
                            <!-- Section 4: Kontak dan Alamat -->
                            <div style="margin-bottom: 0;">
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem; padding-bottom: 0.5rem; border-bottom: 2px solid var(--gold);">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
                                    <span style="font-family: var(--font-display); font-weight: 600; color: var(--ink); font-size: 0.85rem;">4. Kontak dan Alamat</span>
                                    <span style="margin-left: auto; background: #e2e8f0; color: #64748b; font-size: 0.6rem; padding: 0.15rem 0.4rem; border-radius: 4px; font-weight: 600;">OPSIONAL</span>
                                </div>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 0.75rem;">
                                    <div>
                                        <label style="display: block; font-weight: 600; margin-bottom: 0.35rem; color: var(--ink); font-size: 0.8rem;">Email</label>
                                        <input type="email" name="email" placeholder="email@contoh.com" style="width: 100%; padding: 0.6rem 0.75rem; border: 2px solid var(--line); border-radius: 8px; font-size: 0.85rem; background: var(--paper); color: var(--ink); transition: all 0.2s; box-sizing: border-box;">
                                    </div>
                                    <div>
                                        <label style="display: block; font-weight: 600; margin-bottom: 0.35rem; color: var(--ink); font-size: 0.8rem;">No. HP</label>
                                        <input type="tel" name="telp" placeholder="08xxxxxxxxxx" style="width: 100%; padding: 0.6rem 0.75rem; border: 2px solid var(--line); border-radius: 8px; font-size: 0.85rem; background: var(--paper); color: var(--ink); transition: all 0.2s; box-sizing: border-box; font-family: var(--font-mono);">
                                    </div>
                                </div>
                                <div style="margin-bottom: 0.75rem;">
                                    <label style="display: block; font-weight: 600; margin-bottom: 0.35rem; color: var(--ink); font-size: 0.8rem;">Alamat Sesuai KTP</label>
                                    <textarea name="alamat_ktp" rows="2" placeholder="Alamat sesuai KTP" style="width: 100%; padding: 0.6rem 0.75rem; border: 2px solid var(--line); border-radius: 8px; font-size: 0.85rem; background: var(--paper); color: var(--ink); transition: all 0.2s; box-sizing: border-box; resize: vertical;"></textarea>
                                </div>
                                <div style="margin-bottom: 0.75rem;">
                                    <label style="display: block; font-weight: 600; margin-bottom: 0.35rem; color: var(--ink); font-size: 0.8rem;">Alamat Tempat Tinggal</label>
                                    <textarea name="alamat" rows="2" placeholder="Alamat tempat tinggal" style="width: 100%; padding: 0.6rem 0.75rem; border: 2px solid var(--line); border-radius: 8px; font-size: 0.85rem; background: var(--paper); color: var(--ink); transition: all 0.2s; box-sizing: border-box; resize: vertical;"></textarea>
                                </div>
                                <div>
                                    <label style="display: block; font-weight: 600; margin-bottom: 0.35rem; color: var(--ink); font-size: 0.8rem;">Keterangan</label>
                                    <textarea name="keterangan" rows="2" placeholder="Keterangan tambahan" style="width: 100%; padding: 0.6rem 0.75rem; border: 2px solid var(--line); border-radius: 8px; font-size: 0.85rem; background: var(--paper); color: var(--ink); transition: all 0.2s; box-sizing: border-box; resize: vertical;"></textarea>
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
                        <button type="button" @click="submitForm" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #eab308 0%, #ca8a04 100%); color: #0f172a; font-family: var(--font-display); font-size: 0.85rem; font-weight: 700; border: none; border-radius: 10px; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 12px rgba(234, 179, 8, 0.4);">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            Simpan Pegawai
                        </button>
                    </div>
                </div>
            </div>
        </template>

    </main>

    <!-- Page Styles -->
    <style>
        /* Modal Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Form Focus Styles */
        form input:focus,
        form select:focus,
        form textarea:focus {
            outline: none;
            border-color: var(--gold) !important;
            box-shadow: 0 0 0 3px rgba(234, 179, 8, 0.2);
        }

        /* Button Hover Effects */
        form + div button:hover {
            transform: translateY(-1px);
        }

        /* Madrasah Full Width Layout */
        .madrasah-fullwidth .page-content {
            padding: 0;
            max-width: none;
        }

        .madrasah-fullwidth .page-content-expanded {
            padding: 0;
        }

        .madrasah-fullwidth .content-inner {
            padding: 2rem;
            max-width: 100%;
            margin: 0 auto;
        }

        /* Large Tabs Navigation */
        .neo-tabs-large {
            display: flex;
            gap: 0;
            padding: 1rem 2rem;
            background: var(--paper);
            border-bottom: 2px solid var(--line);
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 0;
        }

        .neo-tabs-large .neo-tab {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 1.5rem;
            font-family: var(--font-display);
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--ink-soft);
            border-radius: 0.5rem;
            margin: 0 0.25rem;
            transition: all 200ms var(--ease);
            text-decoration: none;
        }

        .neo-tabs-large .neo-tab:hover {
            color: var(--ink);
            background: var(--paper-soft);
        }

        .neo-tabs-large .neo-tab.is-active {
            color: var(--gold);
            background: oklch(68% 0.145 74 / 0.1);
        }

        .neo-tabs-large .neo-tab svg {
            flex-shrink: 0;
        }

        .neo-tabs-large .neo-tab span {
            white-space: nowrap;
        }

        /* Space between tabs and content */
        .neo-tabs-large {
            margin-bottom: 1.5rem;
        }

        /* Stat Cards */
        .stat-grid {
            display: grid;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-grid-3 {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        }

        .stat-card {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            padding: 1.5rem;
            background: var(--paper);
            border: 1px solid var(--line);
            border-radius: 1rem;
            transition: all 200ms var(--ease);
        }

        .stat-card:hover {
            border-color: var(--gold);
            box-shadow: 0 4px 20px oklch(18% 0.03 76 / 0.08);
            transform: translateY(-2px);
        }

        .stat-card.stat-primary {
            background: linear-gradient(135deg, var(--ink) 0%, var(--night) 100%);
            border-color: var(--ink);
        }

        .stat-card.stat-primary .stat-label,
        .stat-card.stat-primary .stat-value {
            color: var(--paper);
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.75rem;
            background: oklch(68% 0.145 74 / 0.1);
            color: var(--gold);
            flex-shrink: 0;
        }

        .stat-icon.stat-icon-success {
            background: oklch(65% 0.15 145 / 0.1);
            color: var(--success);
        }

        .stat-icon.stat-icon-warning {
            background: oklch(60% 0.2 25 / 0.1);
            color: var(--warning);
        }

        .stat-info {
            flex: 1;
        }

        .stat-label {
            display: block;
            font-size: 0.8rem;
            color: var(--ink-soft);
            margin-bottom: 0.25rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--ink);
            font-family: var(--font-display);
            line-height: 1;
        }

        .stat-value-success {
            color: var(--success);
        }

        .stat-value-warning {
            color: var(--warning);
        }

        /* Table Card */
        .table-card {
            margin-bottom: 0;
        }

        .table-card .neo-card-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--line);
        }

        /* Table Styles */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-responsive .neo-table {
            min-width: 800px;
        }

        .neo-table .col-user {
            min-width: 250px;
            width: 25%;
        }

        .neo-table .col-mapel {
            min-width: 200px;
            width: 22%;
        }

        .neo-table .col-status {
            min-width: 130px;
            width: 13%;
            text-align: center;
        }

        .neo-table .col-kontak {
            min-width: 200px;
            width: 25%;
        }

        .neo-table .col-aksi {
            min-width: 80px;
            width: 8%;
            text-align: center;
        }

        /* User Cell */
        .neo-avatar-lg {
            width: 48px;
            height: 48px;
            font-size: 1rem;
        }

        .neo-user-cell {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .neo-user-name {
            font-weight: 600;
            color: var(--ink);
            font-size: 0.9rem;
        }

        .neo-user-nip {
            font-size: 0.75rem;
            color: var(--ink-soft);
            font-family: var(--font-mono);
        }

        /* Badge Styles */
        .neo-badge-success {
            background: var(--success);
            color: var(--night);
            font-weight: 600;
        }

        .neo-badge-warning {
            background: var(--warning);
            color: var(--night);
        }

        /* Action Button */
        .neo-action-btn-primary {
            background: oklch(68% 0.145 74 / 0.1);
            color: var(--gold);
            border: 1px solid var(--gold);
        }

        .neo-action-btn-primary:hover {
            background: var(--gold);
            color: var(--night);
        }

        /* Neo Empty State */
        .neo-empty-state {
            text-align: center;
            padding: 3rem 1rem;
        }

        .neo-empty-state svg {
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .neo-empty-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 0.5rem;
        }

        .neo-empty-text {
            font-size: 0.9rem;
            color: var(--ink-soft);
        }

        /* Neo Text Muted */
        .neo-text-muted {
            color: var(--ink-soft);
            font-style: italic;
        }

        /* Pagination */
        .neo-pagination-wrap {
            padding: 1.25rem 1.5rem;
            border-top: 1px solid var(--line);
        }

        .neo-pagination-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .neo-pagination-info {
            font-size: 0.85rem;
            color: var(--ink-soft);
        }

        .neo-pagination-nav {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .neo-pagination-link {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 0.5rem;
            border-radius: 0.5rem;
            font-size: 0.85rem;
            font-family: var(--font-mono);
            color: var(--ink);
            background: var(--paper);
            border: 1px solid var(--line);
            text-decoration: none;
            transition: all 180ms;
        }

        .neo-pagination-link:hover:not(.is-disabled):not(.is-active) {
            border-color: var(--gold);
            color: var(--gold);
        }

        .neo-pagination-link.is-active {
            background: var(--gold);
            color: var(--night);
            border-color: var(--gold);
            font-weight: 600;
        }

        .neo-pagination-link.is-disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .neo-tabs-large {
                padding: 0.5rem 1rem;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .neo-tabs-large .neo-tab {
                padding: 0.75rem 1rem;
                font-size: 0.85rem;
                gap: 0.5rem;
            }

            .neo-tabs-large .neo-tab svg {
                width: 20px;
                height: 20px;
            }

            .madrasah-fullwidth .content-inner {
                padding: 1rem 0.5rem;
            }

            .stat-grid-3 {
                grid-template-columns: 1fr;
            }

            .stat-card {
                padding: 1.25rem;
            }

            .stat-value {
                font-size: 1.5rem;
            }

            .neo-pagination-row {
                flex-direction: column;
                text-align: center;
            }

            /* Modal Styles */
            .modal-overlay {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.6);
                backdrop-filter: blur(4px);
                display: flex;
                align-items: flex-start;
                justify-content: center;
                z-index: 1000;
                padding: 2rem 1rem;
                overflow-y: auto;
            }

            .modal-content {
                background: var(--paper);
                border-radius: 1rem;
                width: 100%;
                max-width: 900px;
                max-height: calc(100vh - 4rem);
                overflow-y: auto;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            }

            .modal-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 1.5rem;
                border-bottom: 1px solid var(--line);
                background: linear-gradient(135deg, var(--ink) 0%, var(--night) 100%);
                color: var(--paper);
                position: sticky;
                top: 0;
                z-index: 10;
            }

            .modal-header h3 {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                margin: 0;
                font-family: var(--font-display);
                font-size: 1.1rem;
                font-weight: 600;
            }

            .modal-body {
                padding: 1.5rem;
            }

            .modal-footer {
                display: flex;
                justify-content: flex-end;
                gap: 1rem;
                padding: 1.5rem;
                border-top: 1px solid var(--line);
                background: var(--paper-soft);
                position: sticky;
                bottom: 0;
            }

            .form-section {
                margin-bottom: 2rem;
                padding-bottom: 1.5rem;
                border-bottom: 1px solid var(--line);
            }

            .form-section:last-child {
                margin-bottom: 0;
                padding-bottom: 0;
                border-bottom: none;
            }

            .form-section-title {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                font-family: var(--font-display);
                font-size: 0.95rem;
                font-weight: 600;
                color: var(--ink);
                margin-bottom: 1rem;
                padding-bottom: 0.5rem;
                border-bottom: 2px solid var(--gold);
            }

            .form-section-title svg {
                color: var(--gold);
            }

            .form-required-badge {
                display: inline-flex;
                align-items: center;
                gap: 0.25rem;
                padding: 0.2rem 0.5rem;
                background: var(--danger);
                color: white;
                font-size: 0.65rem;
                font-weight: 600;
                border-radius: 0.25rem;
                margin-left: auto;
            }

            .form-grid-2 {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .form-grid-3 {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 1rem;
            }

            .form-grid-4 {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 1rem;
            }

            .form-full {
                grid-column: 1 / -1;
            }

            .neo-field-group {
                display: flex;
                flex-direction: column;
                gap: 0.4rem;
            }

            .neo-field-label {
                display: flex;
                align-items: center;
                gap: 0.4rem;
                font-family: var(--font-display);
                font-size: 0.8rem;
                font-weight: 600;
                color: var(--ink);
            }

            .neo-field-label svg {
                color: var(--gold);
                opacity: 0.7;
            }

            .neo-field-label .required {
                color: var(--danger);
            }

            .neo-field-input,
            .neo-field-select {
                width: 100%;
                padding: 0.65rem 0.875rem;
                border: 1px solid var(--line);
                border-radius: 0.5rem;
                font-size: 0.85rem;
                background: var(--paper);
                color: var(--ink);
                transition: border-color 180ms, box-shadow 180ms;
            }

            .neo-field-input:focus,
            .neo-field-select:focus {
                outline: none;
                border-color: var(--gold);
                box-shadow: 0 0 0 3px oklch(68% 0.145 74 / 0.15);
            }

            .neo-field-input::placeholder {
                color: var(--ink-soft);
                opacity: 0.6;
            }

            .neo-field-select {
                appearance: none;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23666' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
                background-repeat: no-repeat;
                background-position: right 0.75rem center;
                padding-right: 2.5rem;
            }

            textarea.neo-field-input {
                resize: vertical;
                min-height: 60px;
            }

            .radio-group {
                display: flex;
                gap: 1rem;
                flex-wrap: wrap;
            }

            .radio-item {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.5rem 1rem;
                border: 1px solid var(--line);
                border-radius: 0.5rem;
                cursor: pointer;
                transition: all 180ms;
            }

            .radio-item:hover {
                border-color: var(--gold);
            }

            .radio-item input {
                accent-color: var(--gold);
            }

            .radio-item:has(input:checked) {
                border-color: var(--gold);
                background: oklch(68% 0.145 74 / 0.1);
            }

            .neo-btn-modal-cancel {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.75rem 1.5rem;
                background: transparent;
                color: var(--ink);
                font-family: var(--font-mono);
                font-size: 0.8rem;
                font-weight: 600;
                border: 1px solid var(--line);
                border-radius: 0.5rem;
                cursor: pointer;
                transition: all 180ms;
            }

            .neo-btn-modal-cancel:hover {
                border-color: var(--ink);
                background: var(--paper-soft);
            }

            .neo-btn-modal-save {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.75rem 1.5rem;
                background: var(--gold);
                color: var(--night);
                font-family: var(--font-mono);
                font-size: 0.8rem;
                font-weight: 700;
                border: none;
                border-radius: 0.5rem;
                cursor: pointer;
                transition: all 180ms;
            }

            .neo-btn-modal-save:hover {
                background: var(--gold-dark);
                transform: translateY(-1px);
            }

            .neo-card-actions {
                margin-left: auto;
            }

            .neo-btn-add {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.65rem 1rem;
                background: var(--gold);
                color: var(--night);
                font-family: var(--font-mono);
                font-size: 0.75rem;
                font-weight: 600;
                border: none;
                border-radius: 0.5rem;
                cursor: pointer;
                transition: all 180ms;
            }

            .neo-btn-add:hover {
                background: var(--gold-dark);
                transform: translateY(-1px);
            }

            @media (max-width: 768px) {
                .modal-overlay {
                    padding: 1rem 0.5rem;
                }

                .modal-content {
                    max-height: calc(100vh - 2rem);
                }

                .form-grid-2,
                .form-grid-3,
                .form-grid-4 {
                    grid-template-columns: 1fr;
                }

                .form-full {
                    grid-column: span 1;
                }

                .modal-footer {
                    flex-direction: column;
                }

                .modal-footer button {
                    width: 100%;
                    justify-content: center;
                }
            }
        </style>

    <script>
        function submitForm() {
            // Get form data
            const form = document.querySelector('.modal-content form');
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());
            console.log("Data pegawai:", data);
            alert("Fitur simpan pegawai akan segera diimplementasikan.\n\nData:\n" + JSON.stringify(data, null, 2));
            // showModal = false; // Uncomment when backend is ready
        }
    </script>
</x-layouts.app>
