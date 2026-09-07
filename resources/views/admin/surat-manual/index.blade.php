<x-admin.layouts.app>
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// Surat Manual</span>
            <h1 class="page-title">Input Surat Manual</h1>
            <p class="page-subtitle">Kelola surat yang diinput secara manual oleh admin</p>
        </div>
        <div class="page-header-actions">
            <a href="{{ route('admin.surat-manual.create') }}" class="btn btn-primary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Input Surat Baru
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="alert-message">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('print_url'))
    <script>
        window.open('{{ session("print_url") }}', '_blank');
    </script>
    @endif

    <!-- Filter -->
    <div class="card mb-6">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.surat-manual.index') }}" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[200px]">
                    <label class="form-label">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-input" placeholder="Nomor surat, judul, no req...">
                </div>
                <div class="w-40">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input">
                </div>
                <div class="w-40">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.surat-manual.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No. Req</th>
                        <th>Pengirim</th>
                        <th>No. Surat</th>
                        <th>Tanggal</th>
                        <th>Judul</th>
                        <th>Unit Kerja</th>
                        <th>Layanan</th>
                        <th>File</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($surats as $surat)
                    <tr>
                        <td>{{ $surats->firstItem() + $loop->index }}</td>
                        <td><code class="text-xs">{{ $surat->no_req }}</code></td>
                        <td>{{ $surat->pemohon }}</td>
                        <td>{{ $surat->no_surat }}</td>
                        <td>{{ \Carbon\Carbon::parse($surat->tgl_surat)->format('d/m/Y') }}</td>
                        <td>{{ $surat->judul }}</td>
                        <td>{{ $surat->dept_name ?? '-' }}</td>
                        <td>{{ $surat->layanan_name ?? '-' }}</td>
                        <td>
                            @if($surat->file_surat)
                            <a href="{{ route('admin.surat-manual.download', $surat->id) }}" class="text-primary hover:underline text-sm">
                                <svg class="w-4 h-4 inline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Unduh
                            </a>
                            @else
                            <span class="text-muted text-sm">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex gap-1">
                                <a href="{{ route('admin.surat-manual.print', $surat->no_req) }}" class="btn btn-sm btn-primary" title="Cetak Bukti" target="_blank">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.surat-manual.show', $surat->id) }}" class="btn btn-sm btn-secondary" title="Lihat">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.surat-manual.edit', $surat->id) }}" class="btn btn-sm btn-secondary" title="Edit">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.surat-manual.destroy', $surat->id) }}" x-data
                                      @submit.prevent="if(confirm('Yakin ingin menghapus surat ini?')) $el.submit()">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-8 text-muted">
                            Belum ada surat manual.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($surats->hasPages())
        <div class="card-footer">
            {{ $surats->links() }}
        </div>
        @endif
    </div>
</x-admin.layouts.app>
