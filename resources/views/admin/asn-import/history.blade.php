<x-admin.layouts.app>
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// Import ASN</span>
            <h1 class="page-title">Riwayat Import ASN</h1>
            <p class="page-subtitle">Daftar semua import data ASN yang pernah dilakukan</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.import-asn.index') }}" class="btn btn-secondary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- Alert -->
    @if(session('success'))
        <div class="alert alert-success">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span class="alert-message">{{ session('success') }}</span>
        </div>
    @endif

    <!-- History Table -->
    <div class="card">
        @if(count($history) > 0)
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tanggal Import</th>
                            <th>Imported By</th>
                            <th style="text-align: center">Total Record</th>
                            <th style="text-align: center">Di-update</th>
                            <th style="text-align: center">Dilewati</th>
                            <th style="text-align: center">Error</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($history as $item)
                            <tr>
                                <td>{{ $item['created_at'] }}</td>
                                <td>{{ $item['user_name'] }}</td>
                                <td style="text-align: center"><span class="badge badge-info">{{ $item['total_rows'] }}</span></td>
                                <td style="text-align: center"><span class="badge badge-success">{{ $item['updated'] }}</span></td>
                                <td style="text-align: center"><span class="badge badge-warning">{{ $item['skipped'] }}</span></td>
                                <td style="text-align: center">
                                    @if($item['errors'] > 0)
                                        <span class="badge badge-danger">{{ $item['errors'] }}</span>
                                    @else
                                        <span style="color: var(--text-muted)">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="empty-state-title">Belum ada riwayat import</h3>
                <p class="empty-state-text">Mulai import data ASN dari menu import.</p>
                <a href="{{ route('admin.import-asn.index') }}" class="btn btn-primary">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Import Data ASN
                </a>
            </div>
        @endif
    </div>
</x-admin.layouts.app>
