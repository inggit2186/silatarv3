<x-layouts.app title="Detail Penilaian Kinerja - SILATAR">

@push('styles')
<link rel="stylesheet" href="{{ asset('css/penilaian-kinerja-neo.css') }}">
@endpush

    <main class="neo-mirai">

        <!-- Site Header -->
        <x-layouts.site-header />

        <!-- Hero Section -->
        <section class="hero-page bg-cover bg-center" style="background-image: url('/assets/img/template/kinerja-bg.webp'); padding: 2rem 2rem 4rem; min-height: 280px;">
            <div class="hero-page-content" style="padding-top: 80px;">
                <p class="section-label-gold section-label-sm">
                    <a href="{{ route('penilaian-kinerja.index') }}" class="hover:underline flex items-center gap-1">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Penilaian Kinerja
                    </a>
                    <span>/</span>
                    <span>{{ $penilaian->pejabat->name }}</span>
                </p>
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="section-title-gold" style="font-size: clamp(1.5rem, 4vw, 2.5rem);">Detail Penilaian</h1>
                        <p class="section-subtitle-gold">Triwulan {{ $penilaian->triwulan }} / {{ $penilaian->tahun }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('penilaian-kinerja.edit', $penilaian->id) }}" class="neo-btn bg-white text-amber-700 hover:bg-amber-50 inline-flex items-center gap-2 px-4 py-2 rounded-full self-start">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                        <a href="{{ route('penilaian-kinerja.index') }}" class="neo-btn bg-white/20 text-white hover:bg-white/30 inline-flex items-center gap-2 px-4 py-2 rounded-full self-start">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <div class="container mx-auto px-4 -mt-8 relative z-10 pb-12">
            {{-- Summary Card --}}
            <div class="summary-card mb-8">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-xl font-semibold opacity-90">Ringkasan Penilaian</h3>
                        <p class="opacity-75 text-sm mt-1">{{ $penilaian->pejabat->kat_jabatan_label }}</p>
                    </div>
                </div>
                <div class="grid-2 gap-6">
                    <div class="text-center p-4 bg-white/10 rounded-xl">
                        <div class="text-4xl font-bold text-green-200">+{{ $penilaian->total_thumbs_up }}</div>
                        <div class="text-sm opacity-75 mt-1">Total Bagus</div>
                    </div>
                    <div class="text-center p-4 bg-white/10 rounded-xl">
                        <div class="text-4xl font-bold text-red-200">-{{ $penilaian->total_thumbs_down }}</div>
                        <div class="text-sm opacity-75 mt-1">Total Kurang</div>
                    </div>
                </div>
                <div class="mt-6 pt-6 border-t border-white/20">
                    <div class="flex items-center justify-between">
                        <span class="opacity-75">Skor Net</span>
                        <span class="text-2xl font-bold {{ $penilaian->net_score >= 0 ? 'text-green-200' : 'text-red-200' }}">
                            {{ $penilaian->net_score >= 0 ? '+' : '' }}{{ $penilaian->net_score }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Pejabat Info --}}
            <div class="neo-card mb-8">
                <div class="flex items-start gap-4">
                    <div class="w-20 h-20 rounded-xl bg-gradient-to-br from-amber-400 to-amber-500 flex items-center justify-center text-white font-bold text-2xl shadow-lg">
                        {{ substr($penilaian->pejabat->name, 0, 2) }}
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-semibold">{{ $penilaian->pejabat->name }}</h3>
                        <div class="flex flex-wrap items-center gap-2 mt-1">
                            <span class="neo-badge neo-badge-amber">{{ ucfirst($penilaian->pejabat->kat_jabatan) }}</span>
                            @if($penilaian->pejabat->jabatan)
                                <span class="text-muted">{{ $penilaian->pejabat->jabatan }}</span>
                            @endif
                        </div>
                        <div class="flex flex-wrap gap-x-4 gap-y-1 mt-2 text-sm text-muted">
                            @if($penilaian->pejabat->nomor_induk)
                                <span><strong>NIP:</strong> {{ $penilaian->pejabat->nomor_induk }}</span>
                            @endif
                            @if($penilaian->pejabat->dept_nama)
                                <span><strong>Tempat Tugas:</strong> {{ $penilaian->pejabat->dept_nama }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kriteria Cards --}}
            <div class="grid-2 gap-6 mb-8">
                @php
                    $existingData = [];
                    foreach($penilaian->kriterias as $kriteria) {
                        $existingData[$kriteria->kriteria] = [
                            'thumbs_up' => $kriteria->thumbs_up,
                            'thumbs_down' => $kriteria->thumbs_down,
                            'catatan' => $kriteria->catatan,
                        ];
                    }
                    $iconColors = ['cyan', 'emerald', 'amber', 'violet', 'rose', 'blue', 'indigo', 'slime'];
                @endphp

                @foreach($kriterias as $key => $kriteria)
                    @php
                        $data = $existingData[$key] ?? ['thumbs_up' => 0, 'thumbs_down' => 0, 'catatan' => ''];
                        $colorIndex = array_search($key, array_keys($kriterias));
                        $iconColor = $iconColors[$colorIndex] ?? 'cyan';
                    @endphp

                    <div class="neo-card">
                        <div class="flex items-start gap-4 mb-4">
                            <div class="kriteria-icon {{ $iconColor }}">
                                @switch($kriteria['icon'])
                                    @case('heart-handshake')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                                    @break
                                    @case('shield-check')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>
                                    @break
                                    @case('award')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>
                                    @break
                                    @case('users')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                    @break
                                    @case('flag')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" x2="4" y1="22" y2="15"/></svg>
                                    @break
                                    @case('refresh-cw')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/></svg>
                                    @break
                                    @case('git-branch')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="6" x2="6" y1="3" y2="15"/><circle cx="18" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><path d="M18 9a9 9 0 0 1-9 9"/></svg>
                                    @break
                                    @default
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>
                                @endswitch
                            </div>
                            <div>
                                <h4 class="font-semibold">{{ $kriteria['nama'] }}</h4>
                                <p class="text-sm text-muted">{{ $kriteria['deskripsi'] }}</p>
                            </div>
                        </div>

                        {{-- Score Display --}}
                        <div class="flex items-center gap-4 p-3 bg-stone-50 rounded-lg">
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-medium">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/>
                                </svg>
                                {{ $data['thumbs_up'] }}
                            </span>
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-red-100 text-red-600 text-sm font-medium">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3zM17 2h2.67A2.31 2.31 0 0 1 22 4v7a2.31 2.31 0 0 1-2.33 2H17"/>
                                </svg>
                                {{ $data['thumbs_down'] }}
                            </span>
                            <span class="ml-auto text-sm">
                                <span class="text-muted">Net:</span>
                                <span class="font-semibold {{ $data['thumbs_up'] - $data['thumbs_down'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $data['thumbs_up'] - $data['thumbs_down'] >= 0 ? '+' : '' }}{{ $data['thumbs_up'] - $data['thumbs_down'] }}
                                </span>
                            </span>
                        </div>

                        {{-- Catatan --}}
                        @if($data['catatan'])
                            <div class="mt-3 p-3 bg-amber-50 rounded-lg border border-amber-100">
                                <p class="text-sm text-amber-800 flex items-start gap-2">
                                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                    </svg>
                                    {{ $data['catatan'] }}
                                </p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Catatan Umum --}}
            @if($penilaian->catatan_umum)
                <div class="neo-card mb-8">
                    <h3 class="font-semibold mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Catatan Umum
                    </h3>
                    <p class="text-stone-700">{{ $penilaian->catatan_umum }}</p>
                </div>
            @endif

            {{-- Metadata --}}
            <div class="neo-card">
                <div class="flex flex-wrap gap-6 text-sm text-muted">
                    <div>
                        <span class="font-medium">Dibuat:</span>
                        {{ $penilaian->created_at->format('d/m/Y H:i') }}
                    </div>
                    <div>
                        <span class="font-medium">Diupdate:</span>
                        {{ $penilaian->updated_at->format('d/m/Y H:i') }}
                    </div>
                    <div>
                        <span class="font-medium">Penilai:</span>
                        {{ $penilaian->penilai->name }}
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-layouts.app>
