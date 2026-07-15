<x-layouts.app title="Buat Penilaian Kinerja - SILATAR">

@push('styles')
<link rel="stylesheet" href="{{ asset('css/penilaian-kinerja-neo.css') }}">
@endpush

    <main class="neo-mirai">
        <x-layouts.site-header />

        <section class="hero-page bg-cover bg-center" style="background-image: url('/assets/img/template/kinerja-bg.webp'); padding: 2rem 2rem 4rem; min-height: 280px;">
            <div class="hero-page-content" style="padding-top: 80px;">
                <p class="section-label-gold section-label-sm">
                    <a href="{{ route('penilaian-kinerja.index') }}" class="hover:underline flex items-center gap-1">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Penilaian Kinerja
                    </a>
                    <span>/</span>
                    <span>Buat Baru</span>
                </p>
                <h1 class="section-title-gold" style="font-size: clamp(1.5rem, 4vw, 2.5rem);">Buat Penilaian Baru</h1>
                <p class="section-subtitle-gold">Berikan penilaian kinerja pejabat struktural</p>
            </div>
        </section>

        <div class="container mx-auto px-4 -mt-8 relative z-10 pb-12">
            @if($pejabats->isEmpty())
                <div class="neo-card text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-muted mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <h3 class="text-lg font-semibold mb-2">Tidak Ada Pejabat</h3>
                    <p class="text-muted mb-4">Tidak ada pejabat yang tersedia untuk dinilai.</p>
                    <a href="{{ route('penilaian-kinerja.index') }}" class="neo-btn-primary inline-flex items-center gap-2">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Kembali
                    </a>
                </div>
            @else
                @php
                    $pejabatsJson = $pejabats->keyBy('id')->map(function($item) {
                        return [
                            'id' => (string) $item->id,
                            'name' => $item->name,
                            'kat_jabatan' => $item->kat_jabatan,
                            'nomor_induk' => (string) $item->nomor_induk,
                            'dept_nama' => $item->dept_nama ?? null,
                            'pp' => $item->pp,
                        ];
                    })->toArray();
                @endphp

                <script>
                    window.pejabatsMap = @json($pejabatsJson);
                    document.addEventListener('alpine:init', function() {
                        Alpine.data('penilaianApp', function() {
                            return {
                                selectedId: null,
                                get pejabat() {
                                    if (!this.selectedId) return null;
                                    return window.pejabatsMap[this.selectedId] || null;
                                },
                                get initials() {
                                    if (!this.pejabat || !this.pejabat.name) return '??';
                                    var words = this.pejabat.name.split(' ');
                                    return words.slice(0, 2).map(function(w) { return w[0] || ''; }).join('').toUpperCase();
                                },
                                get photoUrl() {
                                    if (!this.pejabat || !this.pejabat.pp || !this.pejabat.nomor_induk) return null;
                                    return '/assets/img/users/' + this.pejabat.nomor_induk + '/' + this.pejabat.pp;
                                }
                            };
                        });
                    });
                </script>

                <div class="mb-6">
                    <a href="{{ route('penilaian-kinerja.index') }}" class="neo-btn-secondary inline-flex items-center gap-2">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali ke Daftar
                    </a>
                </div>

                <div x-data="penilaianApp()">
                    <div class="neo-card mb-8 bg-gradient-to-r from-amber-50 to-orange-50 border-2 border-amber-200">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-amber-500 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-amber-700 font-medium">Periode Penilaian</p>
                                    <p class="text-2xl font-bold text-amber-900">Triwulan {{ $filters['triwulan'] }} / {{ $filters['tahun'] }}</p>
                                </div>
                            </div>
                            @if(count($sudahDinilai) > 0)
                                <div class="text-right">
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-amber-100 text-amber-800 rounded-full text-sm font-medium">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ count($sudahDinilai) }} sudah dinilai
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="neo-card mb-8">
                        <h2 class="text-lg font-semibold mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Pilih Pejabat
                        </h2>

                        <select x-model="selectedId" class="neo-input mb-6">
                            <option value="">-- Pilih Pejabat --</option>
                            @foreach($pejabats as $p)
                                <option value="{{ $p->id }}" {{ in_array($p->id, $sudahDinilai) ? 'disabled' : '' }}>
                                    {{ in_array($p->id, $sudahDinilai) ? '[SUDAH] ' : '' }}{{ $p->name }} - {{ ucfirst($p->kat_jabatan) }}
                                    {{ $p->nomor_induk ? '| NIP: ' . $p->nomor_induk : '' }}
                                    {{ $p->dept_nama ? '| ' . $p->dept_nama : '' }}
                                </option>
                            @endforeach
                        </select>

                        <div x-show="pejabat" x-transition class="p-6 bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl border-2 border-amber-200">
                            <p class="text-sm text-amber-700 font-semibold mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Pejabat yang dipilih:
                            </p>
                            <div class="flex items-start gap-6">
                                <div class="w-24 h-24 rounded-2xl overflow-hidden bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-white font-bold text-3xl flex-shrink-0 shadow-lg ring-4 ring-amber-200">
                                    <template x-if="photoUrl">
                                        <img :src="photoUrl" class="w-full h-full object-cover" alt="Foto Pejabat">
                                    </template>
                                    <template x-if="!photoUrl">
                                        <span x-text="initials"></span>
                                    </template>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-xl text-gray-900" x-text="pejabat ? pejabat.name : ''"></h4>
                                    <div class="flex flex-wrap items-center gap-2 mt-2">
                                        <span class="neo-badge neo-badge-amber text-sm" x-text="pejabat ? pejabat.kat_jabatan : ''"></span>
                                    </div>
                                    <div class="text-sm text-gray-600 mt-3 space-y-1">
                                        <p x-show="pejabat && pejabat.nomor_induk"><strong class="text-gray-700">NIP:</strong> <span x-text="pejabat ? pejabat.nomor_induk : ''"></span></p>
                                        <p x-show="pejabat && pejabat.dept_nama"><strong class="text-gray-700">Tempat Tugas:</strong> <span x-text="pejabat ? pejabat.dept_nama : ''"></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form x-show="pejabat" action="{{ route('penilaian-kinerja.store') }}" method="POST" x-transition>
                        @csrf
                        <input type="hidden" name="tahun" value="{{ $filters['tahun'] }}">
                        <input type="hidden" name="triwulan" value="{{ $filters['triwulan'] }}">
                        <input type="hidden" name="pejabat_id" x-model="selectedId">

                        @if($errors->any())
                            <div class="neo-card mb-6 bg-red-50 border-2 border-red-200">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <h4 class="font-semibold text-red-700 mb-1">Terjadi Kesalahan:</h4>
                                        <ul class="text-sm text-red-600 list-disc list-inside space-y-0.5">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="neo-card mb-8">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-lg font-semibold flex items-center gap-2">
                                    <svg class="w-5 h-5 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    7 Kriteria Penilaian
                                </h2>
                                <span class="neo-badge neo-badge-amber">Triwulan {{ $filters['triwulan'] }} / {{ $filters['tahun'] }}</span>
                            </div>

                            <div class="grid-2 gap-6">
                                @foreach($kriterias as $key => $kriteria)
                                    <input type="hidden" name="kriteria[{{ $key }}][thumbs_up]" value="0">
                                    <input type="hidden" name="kriteria[{{ $key }}][thumbs_down]" value="0">
                                    @include('penilaian-kinerja._partials.kriteria-item', ['kriteriaKey' => $key, 'kriteriaInfo' => $kriteria])
                                @endforeach
                            </div>
                        </div>

                        <div class="neo-card mb-8">
                            <h2 class="text-lg font-semibold mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Catatan Umum
                            </h2>
                            <textarea name="catatan_umum" class="neo-input" rows="3" placeholder="Catatan umum penilaian (opsional)...">{{ old('catatan_umum') }}</textarea>
                        </div>

                        <div class="flex justify-end gap-4">
                            <a href="{{ route('penilaian-kinerja.index') }}" class="neo-btn neo-btn-secondary">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Kembali
                            </a>
                            <button type="submit" class="neo-btn neo-btn-primary">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M5 13l4 4L19 7"/>
                                </svg>
                                Simpan Penilaian
                            </button>
                        </div>
                    </form>

                    <div x-show="!selectedId" class="neo-card text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-stone-300 mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M15 15l-2 5L9 9l11 4-5 2m0 0l5 5"/>
                        </svg>
                        <h3 class="text-lg font-semibold mb-2">Pilih Pejabats</h3>
                        <p class="text-muted mb-6">Pilih pejabat dari dropdown untuk memulai penilaian</p>
                        <a href="{{ route('penilaian-kinerja.index') }}" class="neo-btn-secondary inline-flex items-center gap-2">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </main>
</x-layouts.app>
