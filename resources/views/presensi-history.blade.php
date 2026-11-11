<x-layouts.app title="Laporan Presensi - SILATAR">

    <main class="neo-mirai min-h-screen bg-[var(--paper)]">
        <!-- Hero Section -->
        <section class="hero-page bg-cover bg-center" style="background-image: url('/assets/img/template/bg2.webp'); padding: 100px 2rem 3rem; min-height: 250px;">
            <div class="news-article-container article-hero">
                <p class="section-label-gold section-label-sm">Presensi</p>
                <h1 class="article-hero-title">Laporan Presensi</h1>
                <p class="article-hero-subtitle">Riwayat presensi Anda. Lihat laporan presensi error maupun seluruh riwayat kehadiran.</p>
                <div class="hero-actions">
                    <a href="{{ route('presensi-error') }}" class="neo-hero-cta neo-hero-cta-primary">Ambil Presensi Error</a>
                    <a href="{{ url('/') }}" class="neo-hero-cta">Kembali ke beranda</a>
                </div>
            </div>
        </section>

        <section class="page-content px-4 py-6 lg:px-6">
            <div class="max-w-4xl mx-auto">

                <!-- Tab Navigation -->
                <div class="silatar-report-tabs mb-6">
                    <div class="silatar-report-tab-list md:grid-cols-2">
                        <a href="{{ route('presensi-history', ['tab' => 'error', 'month' => $month, 'year' => $year]) }}"
                           class="silatar-report-tab {{ $tab === 'error' ? 'silatar-report-tab-active' : 'silatar-report-tab-inactive' }}">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                            Laporan Presensi Error
                        </a>
                        <a href="{{ route('presensi-history', ['tab' => 'riwayat', 'month' => $month, 'year' => $year]) }}"
                           class="silatar-report-tab {{ $tab === 'riwayat' ? 'silatar-report-tab-active' : 'silatar-report-tab-inactive' }}">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Riwayat Presensi
                        </a>
                    </div>
                </div>

                <!-- Filter -->
                <div class="neo-card p-4 mb-6">
                    <form method="GET" action="{{ route('presensi-history') }}" class="flex flex-wrap items-end gap-3">
                        <input type="hidden" name="tab" value="{{ $tab }}">
                        <div class="flex-1 min-w-[140px]">
                            <label class="block text-sm font-medium text-[var(--ink)] mb-1">Bulan</label>
                            <select name="month" class="w-full px-3 py-2 rounded-xl border border-[var(--line)] bg-[var(--paper)] text-[var(--ink)] text-sm focus:outline-none focus:border-[var(--gold)]">
                                @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->locale('id')->isoFormat('MMMM') }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-1 min-w-[100px]">
                            <label class="block text-sm font-medium text-[var(--ink)] mb-1">Tahun</label>
                            <select name="year" class="w-full px-3 py-2 rounded-xl border border-[var(--line)] bg-[var(--paper)] text-[var(--ink)] text-sm focus:outline-none focus:border-[var(--gold)]">
                                @foreach($tab === 'riwayat' ? range(date('Y') - 5, date('Y') + 1) : range(date('Y') - 2, date('Y') + 1) as $y)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="px-5 py-2 rounded-xl text-sm font-medium text-white transition-colors" style="background: linear-gradient(135deg, var(--gold) 0%, var(--sun-deep) 100%);">
                            Filter
                        </button>
                    </form>
                </div>

                @if($tab === 'riwayat')
                    @include('presensi-history.tab-riwayat')
                @else
                    @include('presensi-history.tab-error')
                @endif

            </div>
        </section>

        <!-- Footer -->
        <footer class="border-t border-[var(--line)] bg-[var(--paper-soft)] py-8 px-4 mt-8">
            <div class="max-w-4xl mx-auto text-center">
                <div class="flex items-center justify-center gap-2 mb-3">
                    <svg class="w-5 h-5 text-[var(--gold)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span class="text-sm font-semibold text-[var(--ink)]">Kantor Kementerian Agama Kabupaten Tanah Datar</span>
                </div>
                <p class="text-xs text-[var(--ink-soft)]">SILATAR V2 &mdash; Sistem Informasi Layanan Terpadu</p>
            </div>
        </footer>
    </main>

</x-layouts.app>
