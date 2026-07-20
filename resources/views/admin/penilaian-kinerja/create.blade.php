<x-admin.layouts.app>
    @section('styles')
    <link rel="stylesheet" href="{{ asset('css/penilaian-kinerja.css') }}">
    @endsection

    {{-- Page Header --}}
    <div class="page-header-pk">
        <div class="page-info">
            <span class="page-label">// Penilaian Kinerja</span>
            <h1>Buat Penilaian Baru</h1>
            <p>Berikan penilaian kinerja pejabat struktural</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.penilaian-kinerja.index') }}" class="btn btn-secondary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    {{-- Alert --}}
    @if($pejabats->isEmpty())
        <div class="alert alert-warning">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <p>Tidak ada pejabat yang tersedia untuk dinilai. Pastikan ada pengguna dengan jabatan kasubbag, kasi, atau kepala selain Anda.</p>
        </div>
    @else
        {{-- Pejabat Selection Card --}}
        <div class="card mb-6" x-data="{ selectedPejabat: null }">
            <div class="card-header">
                <h3 class="card-title">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Pilih Pejabat yang Akan Dinilai
                </h3>
            </div>
            <div class="card-body">
                {{-- Filter Period --}}
                <form method="GET" action="{{ route('admin.penilaian-kinerja.create') }}" class="filter-form mb-6">
                    <div class="filter-group">
                        <label class="filter-label">Tahun</label>
                        <select name="tahun" class="form-select" onchange="this.form.submit()">
                            @foreach($tahunOptions as $tahun)
                                <option value="{{ $tahun }}" {{ $tahun == $filters['tahun'] ? 'selected' : '' }}>
                                    {{ $tahun }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Triwulan</label>
                        <select name="triwulan" class="form-select" onchange="this.form.submit()">
                            @foreach($triwulanOptions as $key => $label)
                                <option value="{{ $key }}" {{ $key == $filters['triwulan'] ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>

                {{-- Pejabats Grid --}}
                <div class="grid-3 gap-4">
                    @foreach($pejabats as $pejabat)
                        @php
                            $isRated = in_array($pejabat->id, $sudahDinilai);
                            $jabatanColor = $pejabat->kat_jabatan === 'kepala' ? 'amber' : ($pejabat->kat_jabatan === 'kasubbag' ? 'cyan' : 'violet');
                        @endphp
                        <div class="pejabat-card {{ $isRated ? 'sudah-dinilai' : '' }}"
                             x-data="{ selected: false }"
                             :class="{ 'ring-2 ring-primary': selected }"
                             @click="selected = !selected; document.getElementById('pejabat_id').value = selected ? {{ $pejabat->id }} : ''">
                            <input type="radio" name="pejabat_select" id="pejabats-{{ $pejabat->id }}" value="{{ $pejabat->id }}" class="hidden" @change="selectedPejabat = {{ $pejabat->id }}">

                            <div class="pejabat-info">
                                <div class="pejabat-avatar">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div class="pejabat-details">
                                    <h4>{{ $pejabat->name }}</h4>
                                    <p>
                                        <span class="badge badge-{{ $jabatanColor }}">{{ ucfirst($pejabat->kat_jabatan) }}</span>
                                        @if($pejabat->jabatan)
                                            - {{ $pejabat->jabatan }}
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="pejabat-score">
                                @if($isRated)
                                    <span class="badge badge-success">Sudah Dinilai</span>
                                @else
                                    <span class="badge badge-warning">Belum Dinilai</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                @if(count($sudahDinilai) > 0)
                    <p class="text-muted text-sm mt-4">
                        <svg class="w-4 h-4 inline-block mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Pejabat dengan status "Sudah Dinilai" sudah memiliki penilaian untuk periode ini. Anda bisa mengedit penilaian tersebut.
                    </p>
                @endif
            </div>
        </div>

        {{-- Form Penilaian --}}
        <form action="{{ route('admin.penilaian-kinerja.store') }}" method="POST" x-data="{
            selectedPejabat: null,
            showForm: false
        }" @submit.prevent="if (!document.getElementById('pejabat_id').value) { alert('Pilih pejabat yang akan dinilai'); return; } this.$el.submit();">
            @csrf

            <input type="hidden" name="tahun" value="{{ $filters['tahun'] }}">
            <input type="hidden" name="triwulan" value="{{ $filters['triwulan'] }}">
            <input type="hidden" name="pejabat_id" id="pejabat_id" value="">

            {{-- Form Kriteria --}}
            <div class="card mb-6" x-show="document.getElementById('pejabat_id').value || showForm">
                <div class="card-header">
                    <h3 class="card-title">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        7 Kriteria Penilaian
                    </h3>
                    <span class="badge">Triwulan {{ $filters['triwulan'] }} / {{ $filters['tahun'] }}</span>
                </div>
                <div class="card-body">
                    <div class="grid-2 gap-4">
                        @foreach($kriterias as $key => $kriteria)
                            <input type="hidden" name="kriteria[{{ $key }}][thumbs_up]" value="0">
                            <input type="hidden" name="kriteria[{{ $key }}][thumbs_down]" value="0">
                            @include('admin.penilaian-kinerja._partials.kriteria-item', [
                                'kriteria' => [
                                    'key' => $key,
                                    'info' => $kriteria
                                ],
                                'existingData' => []
                            ])
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Catatan Umum --}}
            <div class="card mb-6" x-show="document.getElementById('pejabat_id').value || showForm">
                <div class="card-header">
                    <h3 class="card-title">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Catatan Umum
                    </h3>
                </div>
                <div class="card-body">
                    <textarea name="catatan_umum" class="form-input" rows="3" placeholder="Catatan umum penilaian (opsional)...">{{ old('catatan_umum') }}</textarea>
                </div>
            </div>

            {{-- Submit Buttons --}}
            <div class="flex justify-end gap-3" x-show="document.getElementById('pejabat_id').value || showForm">
                <a href="{{ route('admin.penilaian-kinerja.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Penilaian
                </button>
            </div>
        </form>
    @endif

    @section('scripts')
    <script>
        // Show form when pejabat is selected
        document.querySelectorAll('.pejabat-card').forEach(card => {
            card.addEventListener('click', function() {
                const hiddenInput = document.getElementById('pejabat_id');
                const radio = this.querySelector('input[type="radio"]');
                if (radio.checked) {
                    hiddenInput.value = radio.value;
                }
            });
        });
    </script>
    @endsection
</x-admin.layouts.app>
