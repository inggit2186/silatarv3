@php
    $namaField = $namaField ?? 'file_soal';
    $catatan = $catatan ?? null;
@endphp

@if(session('import_errors'))
    <div class="alert alert-danger mb-4">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <p class="alert-title">Impor dibatalkan</p>
            <p class="alert-message">Perbaiki baris berikut pada file Excel, lalu unggah ulang. Tidak ada soal yang tersimpan.</p>
            <ul class="list-disc pl-5 mt-2 space-y-1 text-sm">
                @foreach(session('import_errors') as $pesan)
                    <li>{{ $pesan }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<div class="form-group">
    <label class="form-label">File Excel (.xlsx / .xls)</label>
    <input type="file" name="{{ $namaField }}" accept=".xlsx,.xls" class="form-input">
    <p class="form-hint text-sm text-muted">Maksimal 5 MB. Pastikan baris pertama berisi nama kolom sesuai template.</p>
    @error($namaField)
    <p class="form-error text-danger text-sm">{{ $message }}</p>
    @enderror
</div>

<div class="flex items-center gap-3 flex-wrap">
    <a href="{{ route('admin.ujian.soal.template') }}" class="btn btn-secondary btn-sm">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        Unduh Template Excel
    </a>
    @if($catatan)
        <span class="text-sm text-muted">{{ $catatan }}</span>
    @endif
</div>

<div class="mt-4 text-sm text-muted">
    <p class="font-medium text-sm mb-1">Format kolom pada file Excel:</p>
    <ul class="list-disc pl-5 space-y-1">
        <li><span class="font-mono">tipe</span> — <code>pilihan_ganda</code> atau <code>essay</code> (boleh dikosongkan, dideteksi otomatis dari isi kolom pilihan)</li>
        <li><span class="font-mono">pertanyaan</span> — isi soal (wajib)</li>
        <li><span class="font-mono">petunjuk</span> — petunjuk singkat (opsional)</li>
        <li><span class="font-mono">poin</span> — bobot soal, default <code>1</code></li>
        <li><span class="font-mono">pilihan_a</span> s.d. <span class="font-mono">pilihan_e</span> — teks opsi jawaban</li>
        <li><span class="font-mono">kunci</span> — huruf kunci <code>A</code>–<code>E</code> untuk pilihan ganda</li>
    </ul>
</div>
