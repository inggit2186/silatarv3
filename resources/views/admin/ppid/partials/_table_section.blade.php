{{-- Section: table --}}
{{-- Table with headers and dynamic rows --}}
@php
    $headers = $section->metadata['headers'] ?? [];
    $rows = $section->metadata['rows'] ?? [];
@endphp
<div class="section-block" data-section-id="{{ $section->id }}"
    x-data="{
        headers: {{ Js::from($headers) }},
        rows: {{ Js::from($rows) }},
        get numCols() { return Math.max(this.headers.length, 2); },
        addRow() {
            this.rows.push(Array(this.numCols).fill(''));
        },
        removeRow(idx) {
            this.rows.splice(idx, 1);
        },
        addCol() {
            this.headers.push('');
            this.rows.forEach(r => r.push(''));
        },
        removeHeader(idx) {
            this.headers.splice(idx, 1);
            this.rows.forEach(r => r.splice(idx, 1));
        }
    }">

    <div class="ppid-editor-block-head">
        <span class="ppid-editor-block-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" aria-hidden="true">
                <path d="M12 3v18M3 9h18M3 15h18M3 3h18v18H3z"/>
            </svg>
            Tabel Data
        </span>
        <button type="button" @click="addCol()" class="btn btn-sm btn-outline">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Tambah Kolom
        </button>
    </div>

    {{-- Header kolom --}}
    <div class="mb-4">
        <label class="form-label text-xs">Header Kolom</label>
        <div class="flex items-center gap-2 flex-wrap">
            <template x-for="(h, idx) in headers" :key="'h-' + idx">
                <div class="flex items-center gap-1">
                    <input type="text" class="form-input" style="width: 150px;"
                        :name="'sections[{{ $section->id }}][metadata][headers][' + idx + ']'"
                        x-model="headers[idx]" placeholder="Header...">
                    <button type="button" @click="removeHeader(idx)" class="ppid-remove-btn"
                            :disabled="headers.length <= 1" title="Hapus kolom">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12">
                            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                </div>
            </template>
            <p class="ppid-empty-hint" style="width: 100%;" x-show="headers.length === 0">
                Belum ada kolom. Klik "Tambah Kolom" untuk membuat kolom tabel.
            </p>
        </div>
    </div>

    {{-- Baris data --}}
    <div>
        <div class="flex items-center justify-between mb-2">
            <label class="form-label text-xs">Baris Data (<span x-text="rows.length"></span> baris)</label>
            <button type="button" @click="addRow()" class="btn btn-sm btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Tambah Baris
            </button>
        </div>

        <template x-for="(row, ri) in rows" :key="'row-' + ri">
            <div class="flex items-center gap-2 mb-2">
                <span class="ppid-item-num" x-text="ri + 1"></span>
                <template x-for="(cell, ci) in numCols" :key="'cell-' + ri + '-' + ci">
                    <input type="text" class="form-input"
                        :name="'sections[{{ $section->id }}][metadata][rows][' + ri + '][' + ci + ']'"
                        x-model="rows[ri][ci]" placeholder="...">
                </template>
                <button type="button" @click="removeRow(ri)" class="ppid-remove-btn" title="Hapus baris">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                        <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                    </svg>
                </button>
            </div>
        </template>

        <p class="ppid-empty-hint" x-show="rows.length === 0">Belum ada baris data. Klik "Tambah Baris" untuk mengisi tabel.</p>
    </div>
</div>
