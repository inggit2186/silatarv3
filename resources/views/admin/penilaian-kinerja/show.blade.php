<x-admin.layouts.app>
    @section('styles')
    <link rel="stylesheet" href="{{ asset('css/penilaian-kinerja.css') }}">
    @endsection

    {{-- Page Header --}}
    <div class="page-header-pk">
        <div class="page-info">
            <span class="page-label">// Penilaian Kinerja</span>
            <h1>Detail Penilaian</h1>
            <p>Lihat detail penilaian kinerja pejabat</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.penilaian-kinerja.edit', $penilaian->id) }}" class="btn btn-primary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Penilaian
            </a>
            <a href="{{ route('admin.penilaian-kinerja.index') }}" class="btn btn-secondary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    {{-- Summary Card --}}
    <div class="summary-card mb-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h3 class="text-lg font-semibold opacity-90">Ringkasan Penilaian</h3>
                <p class="opacity-75 text-sm">Triwulan {{ $penilaian->triwulan }} / {{ $penilaian->tahun }}</p>
            </div>
            <span class="badge bg-white/20 text-white border-0">
                {{ $penilaian->pejabat->kat_jabatan_label }}
            </span>
        </div>
        <div class="summary-stats">
            <div class="summary-stat positive">
                <div class="summary-stat-value">+{{ $penilaian->total_thumbs_up }}</div>
                <div class="summary-stat-label">Total Bagus</div>
            </div>
            <div class="summary-stat negative">
                <div class="summary-stat-value">-{{ $penilaian->total_thumbs_down }}</div>
                <div class="summary-stat-label">Total Kurang</div>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-white/20">
            <div class="flex items-center justify-between text-sm">
                <span class="opacity-75">Skor Net</span>
                <span class="font-bold text-xl @if($penilaian->net_score >= 0) text-green-200 @else text-red-200 @endif">
                    {{ $penilaian->net_score >= 0 ? '+' : '' }}{{ $penilaian->net_score }}
                </span>
            </div>
        </div>
    </div>

    {{-- Pejabat Info Card --}}
    <div class="card mb-6">
        <div class="card-body">
            <div class="flex items-center gap-4">
                <div class="pejabat-avatar" style="width: 60px; height: 60px; font-size: 1.25rem;">
                    {{ substr($penilaian->pejabat->name, 0, 2) }}
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold">{{ $penilaian->pejabat->name }}</h3>
                    <p class="text-muted">
                        @if($penilaian->pejabat->jabatan)
                            {{ $penilaian->pejabat->jabatan }} -
                        @endif
                        @if($penilaian->pejabat->nomor_induk)
                            NIP. {{ $penilaian->pejabat->nomor_induk }}
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Kriteria Cards --}}
    <div class="grid-2 gap-4 mb-6">
        @php
            // Convert existing kriterias to array
            $existingData = [];
            foreach($penilaian->kriterias as $kriteria) {
                $existingData[$kriteria->kriteria] = [
                    'thumbs_up' => $kriteria->thumbs_up,
                    'thumbs_down' => $kriteria->thumbs_down,
                    'catatan' => $kriteria->catatan,
                ];
            }

            // Icon colors
            $iconColors = ['cyan', 'emerald', 'amber', 'violet', 'rose', 'blue', 'indigo', 'slime'];
        @endphp

        @foreach($kriterias as $key => $kriteria)
            @php
                $data = $existingData[$key] ?? ['thumbs_up' => 0, 'thumbs_down' => 0, 'catatan' => ''];
                $colorIndex = array_search($key, array_keys($kriterias));
                $iconColor = $iconColors[$colorIndex] ?? 'cyan';
            @endphp

            <div class="kriteria-card" style="opacity: 1;">
                <div class="kriteria-card-header">
                    <div class="kriteria-icon {{ $iconColor }}">
                        {{-- Icons --}}
                        @switch($kriteria['icon'])
                            @case('heart-handshake')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                                    <path d="m12 13-1-1 2-2-3-3 2-2"/>
                                    <path d="m18 13 1-1-2-2 3-3-2-2"/>
                                </svg>
                            @break
                            @case('shield-check')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/>
                                    <path d="m9 12 2 2 4-4"/>
                                </svg>
                            @break
                            @case('award')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="8" r="6"/>
                                    <path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>
                                </svg>
                            @break
                            @case('users')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                    <circle cx="9" cy="7" r="4"/>
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                </svg>
                            @break
                            @case('flag')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
                                    <line x1="4" x2="4" y1="22" y2="15"/>
                                </svg>
                            @break
                            @case('refresh-cw')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/>
                                    <path d="M21 3v5h-5"/>
                                    <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/>
                                    <path d="M8 16H3v5"/>
                                </svg>
                            @break
                            @case('git-branch')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="6" x2="6" y1="3" y2="15"/>
                                    <circle cx="18" cy="6" r="3"/>
                                    <circle cx="6" cy="18" r="3"/>
                                    <path d="M18 9a9 9 0 0 1-9 9"/>
                                </svg>
                            @break
                            @default
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                </svg>
                        @endswitch
                    </div>
                    <div class="kriteria-info">
                        <h4>{{ $kriteria['nama'] }}</h4>
                        <p>{{ $kriteria['deskripsi'] }}</p>
                    </div>
                </div>

                {{-- Display Thumbs Score --}}
                <div class="flex items-center gap-4 mt-4 p-3 bg-stone-50 rounded-lg">
                    <div class="flex items-center gap-2">
                        <span class="score-badge positive">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/>
                            </svg>
                            {{ $data['thumbs_up'] }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="score-badge negative">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3zm7-13h2.67A2.31 2.31 0 0 1 22 4v7a2.31 2.31 0 0 1-2.33 2H17"/>
                            </svg>
                            {{ $data['thumbs_down'] }}
                        </span>
                    </div>
                    <div class="ml-auto text-sm">
                        <span class="text-muted">Net:</span>
                        <span class="font-semibold {{ $data['thumbs_up'] - $data['thumbs_down'] >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ $data['thumbs_up'] - $data['thumbs_down'] >= 0 ? '+' : '' }}{{ $data['thumbs_up'] - $data['thumbs_down'] }}
                        </span>
                    </div>
                </div>

                {{-- Catatan --}}
                @if($data['catatan'])
                    <div class="mt-4 p-3 bg-amber-50 rounded-lg border border-amber-100">
                        <p class="text-sm text-amber-800">
                            <svg class="w-4 h-4 inline-block mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
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
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="card-title">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Catatan Umum
                </h3>
            </div>
            <div class="card-body">
                <p class="text-stone-700">{{ $penilaian->catatan_umum }}</p>
            </div>
        </div>
    @endif

    {{-- Metadata --}}
    <div class="card">
        <div class="card-body">
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
</x-admin.layouts.app>
