<x-admin.layouts.app title="{{ $title ?? 'Hasil Ujian' }}">
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// Ujian</span>
            <h1 class="page-title">Hasil Ujian</h1>
            <p class="page-subtitle">{{ $ujian->judul }} &mdash; {{ $ujian->kode }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.ujian.edit', $ujian->id) }}" class="btn btn-secondary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Kembali
            </a>
        </div>
    </div>

    @include('admin.ujian._partials.flash')

    <div class="grid-4 mb-6">
        <div class="stat-card">
            <div class="stat-icon primary">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Total Peserta</span>
                <span class="stat-value">{{ $stats['total'] }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon emerald">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Selesai</span>
                <span class="stat-value">{{ $stats['selesai'] }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon amber">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Sedang Berjalan</span>
                <span class="stat-value">{{ $stats['berjalan'] }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon violet">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 17v-2m4 2v-2m4 2v-2M3 5h18v14H3z"/></svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Rata-rata Nilai</span>
                <span class="stat-value">{{ $stats['rata_rata'] !== null ? $stats['rata_rata'] : '-' }}</span>
            </div>
        </div>
    </div>

    <div class="card mb-6">
        <div class="table-header">
            <div class="table-title-group">
                <h3 class="table-title">Rekap Peserta</h3>
                <p class="table-subtitle">Total {{ $peserta->total() }} peserta terdaftar</p>
            </div>
        </div>
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Peserta</th>
                        <th>Status</th>
                        <th>Nilai</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peserta as $item)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(212,168,83,0.1); color: var(--gold);">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                </div>
                                <div>
                                    <span class="table-user-name">{{ $item->user->name ?? '-' }}</span>
                                    <span class="table-user-email">{{ $item->user->nip ?? '-' }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge @if($item->status === 'selesai') badge-success @elseif($item->status === 'berjalan') badge-warning @else badge-gray @endif">
                                {{ ['belum_mulai' => 'Belum Mulai', 'berjalan' => 'Berjalan', 'selesai' => 'Selesai'][$item->status] ?? $item->status }}
                            </span>
                        </td>
                        <td>
                            @if($item->nilai !== null)
                                <span class="text-primary font-medium font-mono">{{ number_format($item->nilai, 2) }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="table-time">
                                @if($item->submitted_at)
                                    {{ $item->submitted_at->format('d/m/Y H:i') }}
                                @elseif($item->started_at)
                                    Mulai {{ $item->started_at->format('d/m/Y H:i') }}
                                @else
                                    -
                                @endif
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">
                            <div class="empty-state">
                                <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/></svg>
                                <p class="empty-state-title">Belum ada peserta</p>
                                <p class="empty-state-text">Tambahkan peserta pada tab Peserta di halaman kelola ujian.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($soalEssay->isNotEmpty())
    <div class="card">
        <div class="table-header">
            <div class="table-title-group">
                <h3 class="table-title">Penilaian Esai</h3>
                <p class="table-subtitle">Total poin esai: {{ $soalEssay->sum('poin') }} dari {{ $totalPoin }} poin</p>
            </div>
        </div>
        <div class="card-body">
            @foreach($peserta as $item)
                @php
                    $jawabanPeserta = $jawabanEssay->get($item->user_id, collect());
                @endphp
                @if($jawabanPeserta->isNotEmpty())
                    <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-semibold text-gray-800 dark:text-gray-100">{{ $item->user->name ?? '-' }}</h4>
                            <span class="text-sm text-muted">Nilai: {{ $item->nilai !== null ? number_format($item->nilai, 2) : '-' }}</span>
                        </div>

                        <div class="grid gap-4">
                            @foreach($soalEssay as $soal)
                                @php
                                    $jawaban = $jawabanPeserta->firstWhere('soal_id', $soal->id);
                                @endphp
                                <div class="p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                                    <div class="flex items-start justify-between gap-3 mb-2">
                                        <p class="font-medium text-gray-800 dark:text-gray-100">Soal {{ $soal->urut }} &mdash; {{ $soal->pertanyaan }}</p>
                                        <span class="badge badge-info shrink-0">{{ $soal->poin }} poin</span>
                                    </div>

                                    @if($jawaban)
                                        @if($jawaban->esai)
                                            <p class="text-sm text-gray-600 dark:text-gray-300 whitespace-pre-line mb-3">{{ $jawaban->esai }}</p>
                                        @else
                                            <p class="text-sm text-muted italic mb-3">Peserta tidak menjawab soal ini.</p>
                                        @endif

                                        @if($jawaban->file_path)
                                            <a href="{{ route('ujian.berkas', $jawaban->id) }}"
                                               class="inline-flex items-center gap-2 text-sm text-primary hover:underline mb-3">
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48"/></svg>
                                                {{ $jawaban->file_nama }}
                                            </a>
                                        @endif

                                        <form method="POST" action="{{ route('admin.ujian.skor.update', $jawaban->id) }}"
                                              class="flex items-end gap-3">
                                            @csrf
                                            @method('PUT')
                                            <div class="form-group" style="max-width: 160px;">
                                                <label class="form-label">Skor</label>
                                                <input type="number" name="skor" step="0.01" min="0" max="{{ $soal->poin }}"
                                                       value="{{ $jawaban->skor }}" class="form-input" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Simpan Skor</button>
                                            @if($jawaban->skor === null)
                                                <span class="badge badge-warning">Menunggu penilaian</span>
                                            @endif
                                        </form>
                                    @else
                                        <p class="text-sm text-muted">Belum ada jawaban untuk soal ini.</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    @endif

    @if($peserta->hasPages())
    <div class="pagination">
        {{ $peserta->links() }}
    </div>
    @endif
</x-admin.layouts.app>
