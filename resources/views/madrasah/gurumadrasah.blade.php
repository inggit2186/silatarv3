<x-layouts.app title="Guru Madrasah - SILATAR">
    @php
        $stats = $stats ?? ['total' => 0, 'sertifikasi' => 0, 'belum_sertifikasi' => 0];
        $deptName = $deptName ?? 'Madrasah';
    @endphp

    <main class="neo-mirai madrasah-guru madrasah-fullwidth" x-data="{ expandedRows: [], showModal: false }">
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
                                                        <img src="{{ $guru->photo_url }}" alt="{{ $guru->name }}" onerror="this.parentElement.innerHTML = '<span class=\'neo-avatar-initials\'>{{ $guru->initials }}</span>'">
                                                    @else
                                                        <span class="neo-avatar-initials">{{ $guru->initials }}</span>
                                                    @endif
                                                </div>
                                                <div class="neo-user-info">
                                                    <p class="neo-user-name">{{ $guru->name ?? '-' }}</p>
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
                                            <button type="button" class="neo-action-btn neo-action-btn-primary"
                                                title="Detail" @click.stop="expandedRows.includes({{ $guru->id }}) ? expandedRows = expandedRows.filter(id => id !== {{ $guru->id }}) : expandedRows.push({{ $guru->id }})">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </button>
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

    <!-- Page Styles -->
    <style>
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
            color: white;
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

        <!-- Modal Tambah Guru -->
        <div class="modal-overlay" x-show="showModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click.self="showModal = false" @keydown.escape.window="showModal = false">
            <div class="modal-content" x-show="showModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                <div class="modal-header">
                    <h3>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                        Tambah Guru Baru
                    </h3>
                    <button type="button" @click="showModal = false" style="background: transparent; border: none; color: white; cursor: pointer; padding: 0.5rem; border-radius: 0.5rem;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form @submit.prevent="submitForm">
                    <div class="modal-body">
                        <!-- Section 1: Identitas Guru (Wajib) -->
                        <div class="form-section">
                            <h4 class="form-section-title">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4"/></svg>
                                1. Identitas Guru
                                <span class="form-required-badge">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L2 22h20L12 2z"/></svg>
                                    Wajib
                                </span>
                            </h4>
                            <div class="form-grid-2">
                                <div class="neo-field-group form-full">
                                    <label class="neo-field-label">Nama Lengkap <span class="required">*</span></label>
                                    <input type="text" name="nama" class="neo-field-input" placeholder="Masukkan nama lengkap" required>
                                </div>
                                <div class="neo-field-group">
                                    <label class="neo-field-label">Kategori Jabatan <span class="required">*</span></label>
                                    <select name="kat_jabatan" class="neo-field-select" required>
                                        <option value="">Pilih Jabatan</option>
                                        <option value="guru">Guru</option>
                                        <option value="kepala">Kepala Sekolah</option>
                                    </select>
                                </div>
                                <div class="neo-field-group">
                                    <label class="neo-field-label">Status Kepegawaian <span class="required">*</span></label>
                                    <select name="status" class="neo-field-select" required>
                                        <option value="">Pilih Status</option>
                                        <option value="PNS">PNS</option>
                                        <option value="PPPK">PPPK</option>
                                        <option value="HONOR">HONOR</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Data Guru & SIMPATIKA -->
                        <div class="form-section">
                            <h4 class="form-section-title">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4"/><path d="M7 20h10"/><rect x="5" y="3" width="14" height="16" rx="2"/></svg>
                                2. Data Guru & SIMPATIKA
                            </h4>
                            <div class="form-grid-3">
                                <div class="neo-field-group">
                                    <label class="neo-field-label">Bidang Studi Yang Diajar</label>
                                    <input type="text" name="bidang_studi_diajar" class="neo-field-input" placeholder="Contoh: Matematika">
                                </div>
                                <div class="neo-field-group">
                                    <label class="neo-field-label">Bidang Studi Sertifikasi</label>
                                    <input type="text" name="bidang_sertifikasi" class="neo-field-input" placeholder="Contoh: Pendidikan Matematika">
                                </div>
                                <div class="neo-field-group">
                                    <label class="neo-field-label">Sertifikasi</label>
                                    <select name="serdik" class="neo-field-select">
                                        <option value="">Pilih Status</option>
                                        <option value="sertifikasi">Sudah Sertifikasi</option>
                                        <option value="non-sertifikasi">Belum Sertifikasi</option>
                                    </select>
                                </div>
                                <div class="neo-field-group">
                                    <label class="neo-field-label">NUPTK</label>
                                    <input type="text" name="nuptk" class="neo-field-input" placeholder="Nomor NUPTK">
                                </div>
                                <div class="neo-field-group">
                                    <label class="neo-field-label">NPK</label>
                                    <input type="text" name="npk" class="neo-field-input" placeholder="Nomor NPK">
                                </div>
                                <div class="neo-field-group">
                                    <label class="neo-field-label">NRG</label>
                                    <input type="text" name="nrg" class="neo-field-input" placeholder="Nomor NRG">
                                </div>
                                <div class="neo-field-group">
                                    <label class="neo-field-label">Nama Gadis Ibu Kandung</label>
                                    <input type="text" name="nama_ibu" class="neo-field-input" placeholder="Nama ibu kandung">
                                </div>
                            </div>
                        </div>

                        <!-- Section 3: Data Pribadi (Opsional) -->
                        <div class="form-section">
                            <h4 class="form-section-title">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a4 4 0 004 4z"/><circle cx="12" cy="7" r="4"/></svg>
                                3. Data Pribadi (Opsional)
                            </h4>
                            <div class="form-grid-3">
                                <div class="neo-field-group">
                                    <label class="neo-field-label">NIP / NUPTK</label>
                                    <input type="text" name="nomor_induk" class="neo-field-input" placeholder="NIP atau NUPTK">
                                </div>
                                <div class="neo-field-group">
                                    <label class="neo-field-label">NIK / No. KTP</label>
                                    <input type="text" name="nik" class="neo-field-input" placeholder="Nomor KTP">
                                </div>
                                <div class="neo-field-group">
                                    <label class="neo-field-label">NPWP</label>
                                    <input type="text" name="npwp" class="neo-field-input" placeholder="Nomor NPWP">
                                </div>
                                <div class="neo-field-group">
                                    <label class="neo-field-label">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" class="neo-field-input" placeholder="Kota kelahiran">
                                </div>
                                <div class="neo-field-group">
                                    <label class="neo-field-label">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" class="neo-field-input">
                                </div>
                                <div class="neo-field-group">
                                    <label class="neo-field-label">Jenis Kelamin</label>
                                    <div class="radio-group">
                                        <label class="radio-item">
                                            <input type="radio" name="jenis_kelamin" value="L"> Laki-laki
                                        </label>
                                        <label class="radio-item">
                                            <input type="radio" name="jenis_kelamin" value="P"> Perempuan
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 4: Data Kepegawaian (Opsional) -->
                        <div class="form-section">
                            <h4 class="form-section-title">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                4. Data Kepegawaian (Opsional)
                            </h4>
                            <div class="form-grid-4">
                                <div class="neo-field-group">
                                    <label class="neo-field-label">Golongan</label>
                                    <select name="golongan" class="neo-field-select">
                                        <option value="">Pilih Golongan</option>
                                        <option value="I/a">I/a - Juru</option>
                                        <option value="I/b">I/b - Juru Tk.I</option>
                                        <option value="I/c">I/c - Pengatur</option>
                                        <option value="I/d">I/d - Pengatur Tk.I</option>
                                        <option value="II/a">II/a - Penata Muda</option>
                                        <option value="II/b">II/b - Penata Muda Tk.I</option>
                                        <option value="II/c">II/c - Penata</option>
                                        <option value="II/d">II/d - Penata Tk.I</option>
                                        <option value="III/a">III/a - Pembina</option>
                                        <option value="III/b">III/b - Pembina Tk.I</option>
                                        <option value="III/c">III/c - Pembina Utama</option>
                                        <option value="III/d">III/d - Pembina Utama Muda</option>
                                        <option value="IV/a">IV/a - Pembina Utama</option>
                                    </select>
                                </div>
                                <div class="neo-field-group">
                                    <label class="neo-field-label">Jabatan</label>
                                    <input type="text" name="jabatan" class="neo-field-input" placeholder="Nama jabatan">
                                </div>
                                <div class="neo-field-group">
                                    <label class="neo-field-label">TMT Tempat Tugas</label>
                                    <input type="date" name="tmt_tugas" class="neo-field-input">
                                </div>
                                <div class="neo-field-group">
                                    <label class="neo-field-label">KGB</label>
                                    <input type="date" name="kgb" class="neo-field-input">
                                </div>
                            </div>
                        </div>

                        <!-- Section 5: Pendidikan (Opsional) -->
                        <div class="form-section">
                            <h4 class="form-section-title">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                                5. Data Pendidikan (Opsional)
                            </h4>
                            <div class="form-grid-3">
                                <div class="neo-field-group">
                                    <label class="neo-field-label">Pendidikan Terakhir</label>
                                    <select name="pendidikan" class="neo-field-select">
                                        <option value="">Pilih Pendidikan</option>
                                        <option value="SMA/SMK">SMA/SMK</option>
                                        <option value="D1">D1</option>
                                        <option value="D2">D2</option>
                                        <option value="D3">D3</option>
                                        <option value="D4">D4</option>
                                        <option value="S1">S1 - Sarjana</option>
                                        <option value="S2">S2 - Magister</option>
                                        <option value="S3">S3 - Doktor</option>
                                    </select>
                                </div>
                                <div class="neo-field-group">
                                    <label class="neo-field-label">Jurusan</label>
                                    <input type="text" name="jurusan" class="neo-field-input" placeholder="Nama jurusan">
                                </div>
                                <div class="neo-field-group">
                                    <label class="neo-field-label">Fakultas</label>
                                    <input type="text" name="fakultas" class="neo-field-input" placeholder="Nama fakultas">
                                </div>
                                <div class="neo-field-group">
                                    <label class="neo-field-label">Universitas</label>
                                    <input type="text" name="universitas" class="neo-field-input" placeholder="Nama universitas">
                                </div>
                                <div class="neo-field-group">
                                    <label class="neo-field-label">Tahun Lulus</label>
                                    <input type="number" name="tahun_lulus" class="neo-field-input" placeholder="2020" min="1970" max="2030">
                                </div>
                            </div>
                        </div>

                        <!-- Section 6: Kontak & Alamat (Opsional) -->
                        <div class="form-section">
                            <h4 class="form-section-title">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                6. Kontak & Alamat (Opsional)
                            </h4>
                            <div class="form-grid-2">
                                <div class="neo-field-group">
                                    <label class="neo-field-label">Email</label>
                                    <input type="email" name="email" class="neo-field-input" placeholder="email@contoh.com">
                                </div>
                                <div class="neo-field-group">
                                    <label class="neo-field-label">No. HP</label>
                                    <input type="tel" name="telp" class="neo-field-input" placeholder="08xxxxxxxxxx">
                                </div>
                                <div class="neo-field-group form-full">
                                    <label class="neo-field-label">Alamat</label>
                                    <textarea name="alamat" class="neo-field-input" rows="2" placeholder="Alamat lengkap"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="neo-btn-modal-cancel" @click="showModal = false">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                            Batal
                        </button>
                        <button type="submit" class="neo-btn-modal-save">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><path d="M17 21v-8H7v8M7 3v5h8"/></svg>
                            Simpan Guru
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function submitForm() {
                const form = event.target;
                const formData = new FormData(form);
                const data = Object.fromEntries(formData.entries());
                console.log("Data yang akan disimpan:", data);
                alert("Fitur simpan belum diimplementasikan. Data:\n" + JSON.stringify(data, null, 2));
            }
        </script>
    </main>
</x-layouts.app>
