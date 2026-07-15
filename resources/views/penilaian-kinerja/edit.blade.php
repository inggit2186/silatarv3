<x-layouts.app title="Edit Penilaian Kinerja - SILATAR">
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
                    <span>Edit</span>
                </p>
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="section-title-gold" style="font-size: clamp(1.5rem, 4vw, 2.5rem);">Edit Penilaian</h1>
                        <p class="section-subtitle-gold">Edit penilaian kinerja pejabat struktural</p>
                    </div>
                    <a href="{{ route('penilaian-kinerja.show', $penilaian->id) }}" class="neo-btn bg-white/20 text-white hover:bg-white/30 inline-flex items-center gap-2 px-4 py-2 rounded-full self-start">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Lihat Detail
                    </a>
                </div>
            </div>
        </section>

        <div class="container mx-auto px-4 -mt-8 relative z-10 pb-12">
            {{-- Pejabat Info Card --}}
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
                    <span class="neo-badge neo-badge-amber text-lg px-4 py-2">
                        Triwulan {{ $penilaian->triwulan }} / {{ $penilaian->tahun }}
                    </span>
                </div>
            </div>

            {{-- Form Edit --}}
            <form action="{{ route('penilaian-kinerja.update', $penilaian->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Kriteria Cards --}}
                <div class="neo-card mb-8">
                    <h2 class="text-lg font-semibold mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        7 Kriteria Penilaian
                    </h2>

                    <div class="grid-2 gap-6">
                        @php
                            $existingData = [];
                            foreach($penilaian->kriterias as $kriteria) {
                                $existingData[$kriteria->kriteria] = [
                                    'thumbs_up' => $kriteria->thumbs_up,
                                    'thumbs_down' => $kriteria->thumbs_down,
                                    'catatan' => $kriteria->catatan,
                                ];
                            }
                        @endphp

                        @foreach($kriterias as $key => $kriteria)
                            @php
                                $data = $existingData[$key] ?? ['thumbs_up' => 0, 'thumbs_down' => 0, 'catatan' => ''];
                            @endphp
                            @include('penilaian-kinerja._partials.kriteria-item-edit', [
                                'kriteriaKey' => $key,
                                'kriteriaInfo' => $kriteria,
                                'data' => $data
                            ])
                        @endforeach
                    </div>
                </div>

                {{-- Catatan Umum --}}
                <div class="neo-card mb-8">
                    <h2 class="text-lg font-semibold mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Catatan Umum
                    </h2>
                    <textarea name="catatan_umum" class="neo-input" rows="3" placeholder="Catatan umum penilaian (opsional)...">{{ old('catatan_umum', $penilaian->catatan_umum) }}</textarea>
                </div>

                {{-- Submit --}}
                <div class="flex justify-end gap-4">
                    <a href="{{ route('penilaian-kinerja.index') }}" class="neo-btn-secondary">Batal</a>
                    <button type="submit" class="neo-btn-primary inline-flex items-center gap-2">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Penilaian
                    </button>
                </div>
            </form>
        </div>
    </main>
</x-layouts.app>
