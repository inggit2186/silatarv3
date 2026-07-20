<x-admin.layouts.app>
    @section('styles')
    <link rel="stylesheet" href="{{ asset('css/penilaian-kinerja.css') }}">
    @endsection

    {{-- Page Header --}}
    <div class="page-header-pk">
        <div class="page-info">
            <span class="page-label">// Penilaian Kinerja</span>
            <h1>Edit Penilaian</h1>
            <p>Edit penilaian kinerja pejabat struktural</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.penilaian-kinerja.show', $penilaian->id) }}" class="btn btn-secondary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Lihat Detail
            </a>
            <a href="{{ route('admin.penilaian-kinerja.index') }}" class="btn btn-secondary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    {{-- Pejabat Info Card --}}
    <div class="card mb-6">
        <div class="card-body">
            <div class="flex items-center gap-4">
                <div class="pejabat-avatar" style="width: 60px; height: 60px; font-size: 1.25rem;">
                    {{ substr($penilaian->pejabat->name, 0, 2) }}
                </div>
                <div>
                    <h3 class="text-lg font-semibold">{{ $penilaian->pejabat->name }}</h3>
                    <p class="text-muted">
                        <span class="badge badge-{{ $penilaian->pejabat->kat_jabatan === 'kepala' ? 'amber' : ($penilaian->pejabat->kat_jabatan === 'kasubbag' ? 'cyan' : 'violet') }}">
                            {{ ucfirst($penilaian->pejabat->kat_jabatan) }}
                        </span>
                        @if($penilaian->pejabat->jabatan)
                            {{ $penilaian->pejabat->jabatan }}
                        @endif
                        @if($penilaian->pejabat->nomor_induk)
                            - {{ $penilaian->pejabat->nomor_induk }}
                        @endif
                    </p>
                </div>
                <div class="ml-auto text-right">
                    <span class="badge badge-primary text-lg px-3 py-2">
                        Triwulan {{ $penilaian->triwulan }} / {{ $penilaian->tahun }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Form Edit --}}
    <form action="{{ route('admin.penilaian-kinerja.update', $penilaian->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Form Kriteria --}}
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="card-title">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    7 Kriteria Penilaian
                </h3>
            </div>
            <div class="card-body">
                <div class="grid-2 gap-4">
                    @php
                        // Convert existing kriterias to array for easy access
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
                        @include('admin.penilaian-kinerja._partials.kriteria-item', [
                            'kriteria' => [
                                'key' => $key,
                                'info' => $kriteria
                            ],
                            'existingData' => $existingData
                        ])
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Catatan Umum --}}
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
                <textarea name="catatan_umum" class="form-input" rows="3" placeholder="Catatan umum penilaian (opsional)...">{{ old('catatan_umum', $penilaian->catatan_umum) }}</textarea>
            </div>
        </div>

        {{-- Submit Buttons --}}
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.penilaian-kinerja.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                Update Penilaian
            </button>
        </div>
    </form>
</x-admin.layouts.app>
