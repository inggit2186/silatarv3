@php
    $rincianData = $rincian;
    $totalSoal = count($rincianData['rincian']);
@endphp

<x-admin.layouts.app title="{{ $title ?? 'Jawaban Peserta' }}">
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// Ujian &mdash; Jawaban Peserta</span>
            <h1 class="page-title">{{ $peserta->user->name ?? '-' }}</h1>
            <p class="page-subtitle">
                {{ $peserta->user->nomor_induk ?? '-' }} &middot; {{ $ujian->judul }} ({{ $ujian->kode }})
            </p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.ujian.hasil', $ujian->id) }}" class="btn btn-secondary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5m7 7l-7-7 7-7"/></svg>
                Kembali ke Hasil
            </a>
            @if($peserta->status !== 'berjalan')
                <form method="POST" action="{{ route('admin.ujian.peserta.reset', [$ujian->id, $peserta->user_id]) }}"
                      onsubmit="return confirm('Hapus seluruh jawaban {{ $peserta->user->name ?? 'peserta ini' }}? Jawaban, nilai, dan riwayat pelanggaran akan dihapus sehingga peserta dapat mengulang ujian.')">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M8 6V4a1 1 0 011-1h6a1 1 0 011 1v2m1 0v14a2 2 0 01-2 2H8a2 2 0 01-2-2V6h12z"/></svg>
                        Reset Jawaban
                    </button>
                </form>
            @endif
        </div>
    </div>

    @include('admin.ujian._partials.flash')

    <div class="grid-4 mb-6">
        <div class="stat-card">
            <div class="stat-icon primary">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 15a3 3 0 100-6 3 3 0 000 6z"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 11-2.83 2.83l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 11-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 11-2.83-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 110-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 112.83-2.83l.06.06A1.65 1.65 0 009 4.6a1.65 1.65 0 001-1.51V3a2 2 0 114 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 112.83 2.83l-.06.06A1.65 1.65 0 0019.4 9c.14.36.43.64.79.79H21a2 2 0 110 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Nilai Akhir</span>
                <span class="stat-value">{{ number_format($rincianData['nilai'], 2) }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon emerald">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Jawaban Benar</span>
                <span class="stat-value">{{ $rincianData['benar'] }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon rose">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Jawaban Salah</span>
                <span class="stat-value">{{ $rincianData['salah'] }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon amber">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Tidak Dijawab</span>
                <span class="stat-value">{{ $rincianData['kosong'] }}</span>
            </div>
        </div>
    </div>

    <div class="card mb-6">
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <div class="text-xs text-muted uppercase tracking-wide">Status</div>
                    <div class="mt-1">
                        <span class="badge @if($peserta->status === 'selesai') badge-success @elseif($peserta->status === 'berjalan') badge-warning @else badge-gray @endif">
                            {{ ['belum_mulai' => 'Belum Mulai', 'berjalan' => 'Berjalan', 'selesai' => 'Selesai'][$peserta->status] ?? $peserta->status }}
                        </span>
                    </div>
                </div>
                <div>
                    <div class="text-xs text-muted uppercase tracking-wide">Waktu Mulai</div>
                    <div class="mt-1 table-time">{{ $peserta->started_at ? $peserta->started_at->format('d/m/Y H:i') : '-' }}</div>
                </div>
                <div>
                    <div class="text-xs text-muted uppercase tracking-wide">Waktu Selesai</div>
                    <div class="mt-1 table-time">{{ $peserta->submitted_at ? $peserta->submitted_at->format('d/m/Y H:i') : '-' }}</div>
                </div>
                <div>
                    <div class="text-xs text-muted uppercase tracking-wide">Skor / Total Poin</div>
                    <div class="mt-1 font-mono text-primary font-medium">
                        {{ number_format($rincianData['totalSkor'], 2) }} / {{ $rincianData['totalPoin'] }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($pelanggaran->isNotEmpty())
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="card-title">Riwayat Pelanggaran ({{ $pelanggaran->count() }})</h3>
            </div>
            <div class="card-body">
                <ul class="space-y-2">
                    @foreach($pelanggaran as $langgaran)
                        <li class="flex flex-wrap items-center gap-2 text-sm">
                            <span class="badge badge-warning">{{ $langgaran->label() }}</span>
                            <span class="font-mono text-xs text-muted">{{ $langgaran->created_at->format('d/m/Y H:i:s') }}</span>
                            @if($langgaran->ip)
                                <span class="font-mono text-xs text-muted">IP {{ $langgaran->ip }}</span>
                            @endif
                            @if($langgaran->keterangan)
                                <span class="text-xs text-muted">{{ $langgaran->keterangan }}</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="card">
        <div class="table-header">
            <div class="table-title-group">
                <h3 class="table-title">Rincian Jawaban</h3>
                <p class="table-subtitle">{{ $totalSoal }} soal &middot; {{ $rincianData['benar'] }} benar, {{ $rincianData['salah'] }} salah, {{ $rincianData['kosong'] }} tidak dijawab</p>
            </div>
        </div>
        <div class="card-body space-y-4">
            @forelse($rincianData['rincian'] as $item)
                <div class="p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex items-start gap-3">
                            <span class="font-mono text-xs text-muted mt-1">{{ str_pad((string) $item['nomor'], 2, '0', STR_PAD_LEFT) }}</span>
                            <div>
                                <p class="font-medium text-gray-800 dark:text-gray-100">{{ $item['pertanyaan'] }}</p>
                                @if($item['petunjuk'])
                                    <p class="text-xs text-muted mt-1">{{ $item['petunjuk'] }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <span class="badge @if($item['status'] === 'benar') badge-success @elseif($item['status'] === 'salah') badge-danger @elseif($item['status'] === 'kosong') badge-gray @else badge-info @endif">
                                {{ ['benar' => 'Benar', 'salah' => 'Salah', 'kosong' => 'Tidak Dijawab', 'esai' => 'Esai'][$item['status']] }}
                            </span>
                            <span class="badge badge-info">{{ $item['poin'] }} poin</span>
                        </div>
                    </div>

                    @if($item['tipe'] === 'pilihan_ganda')
                        <div class="space-y-2">
                            @foreach($item['pilihan'] as $pilihan)
                                @php
                                    $gaya = $pilihan['dipilih'] && $pilihan['benar']
                                        ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20'
                                        : ($pilihan['dipilih']
                                            ? 'border-rose-500 bg-rose-50 dark:bg-rose-900/20'
                                            : ($pilihan['benar'] ? 'border-emerald-300 border-dashed' : 'border-gray-200 dark:border-gray-700'));
                                @endphp
                                <div class="flex items-start gap-3 p-3 rounded-lg border {{ $gaya }}">
                                    <span class="w-6 h-6 rounded-full border border-gray-300 dark:border-gray-600 flex items-center justify-center text-xs font-mono shrink-0">
                                        {{ $pilihan['label'] }}
                                    </span>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-gray-700 dark:text-gray-200">{{ $pilihan['teks'] }}</p>
                                        <div class="flex flex-wrap gap-2 mt-1">
                                            @if($pilihan['dipilih'])
                                                <span class="badge badge-danger">Jawaban Peserta</span>
                                            @endif
                                            @if($pilihan['benar'])
                                                <span class="badge badge-success">Kunci Jawaban</span>
                                            @endif
                                        </div>
                                    </div>
                                    @if($pilihan['dipilih'] && $pilihan['benar'])
                                        <svg class="w-5 h-5 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                                    @elseif($pilihan['dipilih'])
                                        <svg class="w-5 h-5 text-rose-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
                                    @elseif($pilihan['benar'])
                                        <svg class="w-5 h-5 text-emerald-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 7a2 2 0 012 2m4 0a6 6 0 01-9.9 5.2L3 21l2.8-6.1A6 6 0 1119 9z"/></svg>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        @if($item['esai'])
                            <div class="p-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/40">
                                <p class="text-sm text-gray-700 dark:text-gray-200 whitespace-pre-line">{{ $item['esai'] }}</p>
                            </div>
                        @else
                            <p class="text-sm text-muted italic">Peserta tidak menjawab soal ini.</p>
                        @endif

                        @if($item['file_nama'])
                            <a href="{{ $item['file_url'] }}" target="_blank" rel="noopener"
                               class="inline-flex items-center gap-2 text-sm text-primary hover:underline mt-3">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48"/></svg>
                                {{ $item['file_nama'] }}
                            </a>
                        @endif
                    @endif

                    @if($item['tipe'] === 'essay' && $item['skor'] !== null)
                        <div class="mt-3 text-xs text-muted">
                            Skor dari admin: <span class="font-mono text-primary font-medium">{{ number_format((float) $item['skor'], 2) }}</span>
                            dari {{ $item['poin'] }} poin
                        </div>
                    @endif
                </div>
            @empty
                <div class="empty-state">
                    <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <p class="empty-state-title">Ujian belum memiliki soal</p>
                    <p class="empty-state-text">Tambahkan soal pada tab Soal di halaman kelola ujian.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-admin.layouts.app>
