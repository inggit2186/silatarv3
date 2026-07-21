<x-layouts.app title="Guru Madrasah - SILATAR">
    @php
        $stats = $stats ?? ['total' => 0, 'sertifikasi' => 0, 'belum_sertifikasi' => 0];
        $deptName = $deptName ?? 'Madrasah';
    @endphp

    <main class="neo-mirai" x-data="{ expandedRows: [] }">
        <!-- Hero Section -->
        <section class="hero-page has-bg-image">
            <div class="content-centered" style="max-width: 36rem; text-align: center;">
                <p class="hero-label">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Data Guru
                </p>
                <h1 class="hero-title">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="1.5"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    GURU {{ strtoupper($deptName) }}
                </h1>
                <p class="hero-desc">Daftar guru yang tercatat dalam sistem berdasarkan unit kerja madrasah Anda.</p>
                <div class="hero-actions">
                    <a href="{{ route('madrasah.profil') }}" class="neo-btn-secondary">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Kembali ke Profil
                    </a>
                </div>
            </div>
        </section>

        <!-- Section Divider -->
        <div class="section-divider wave-rounded"></div>

        <!-- Content -->
        <section class="page-content">
            <div class="content-centered">

                <!-- Tab Navigation -->
                <div class="neo-tabs" style="margin-bottom: 2rem;">
                    <a href="{{ route('madrasah.profil') }}" class="neo-tab">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Profil Madrasah
                    </a>
                    <a href="{{ route('madrasah.pegawai') }}" class="neo-tab">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Pegawai
                    </a>
                    <a href="{{ route('madrasah.guru') }}" class="neo-tab is-active">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        Guru
                    </a>
                    <a href="{{ route('madrasah.laporan-semester') }}" class="neo-tab">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Laporan Semester
                    </a>
                </div>

                <!-- Stats Cards -->
                <div class="neo-grid neo-grid-3" style="margin-bottom: 2rem;">
                    <div class="neo-card">
                        <div class="neo-card-body neo-stat-card">
                            <div class="neo-stat-icon neo-stat-icon-gold">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                            <div class="neo-stat-info">
                                <p class="neo-stat-label">Total Guru</p>
                                <p class="neo-stat-value">{{ $stats['total'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="neo-card">
                        <div class="neo-card-body neo-stat-card">
                            <div class="neo-stat-icon neo-stat-icon-primary">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <div class="neo-stat-info">
                                <p class="neo-stat-label">Tersertifikasi</p>
                                <p class="neo-stat-value neo-stat-value-primary">{{ $stats['sertifikasi'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="neo-card">
                        <div class="neo-card-body neo-stat-card">
                            <div class="neo-stat-icon neo-stat-icon-warning">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div class="neo-stat-info">
                                <p class="neo-stat-label">Belum Sertifikasi</p>
                                <p class="neo-stat-value neo-stat-value-warning">{{ $stats['belum_sertifikasi'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table Section -->
                <div class="neo-card">
                    <div class="neo-card-header">
                        <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                        </div>
                        <div>
                            <h2 class="neo-card-title">Daftar Guru</h2>
                            <p class="neo-card-desc">Klik baris untuk melihat detail lengkap</p>
                        </div>
                    </div>
                    <div class="neo-table-wrapper">
                        <table class="neo-table">
                            <thead class="neo-table-header">
                                <tr>
                                    <th>Nama</th>
                                    <th>Mapel / Bidang</th>
                                    <th>Status</th>
                                    <th>Kontak</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($guruList as $guru)
                                    <tr class="neo-table-row"
                                        @click="expandedRows.includes({{ $guru->id }}) ? expandedRows = expandedRows.filter(id => id !== {{ $guru->id }}) : expandedRows.push({{ $guru->id }})">
                                        <td class="neo-table-cell">
                                            <div class="neo-user-cell">
                                                <div class="neo-avatar">
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
                                            @php $sertifVariant = ($guru->serdik ?? 'non-sertifikasi') === 'sertifikasi' ? 'neo-badge-primary' : 'neo-badge-warning'; @endphp
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
                                            @endif
                                        </td>
                                        <td class="neo-table-cell">
                                            <button type="button" class="neo-action-btn"
                                                title="Detail" @click.stop="expandedRows.includes({{ $guru->id }}) ? expandedRows = expandedRows.filter(id => id !== {{ $guru->id }}) : expandedRows.push({{ $guru->id }})">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="neo-table-cell">
                                            <div class="neo-empty-state">
                                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--ash)" stroke-width="1.5"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
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
</x-layouts.app>
