{{-- Section: stats --}}
{{-- Stats grid with value, label, icon --}}
@php
    $stats = $section->metadata['stats'] ?? [];
@endphp
<div class="section-block" data-section-id="{{ $section->id }}"
    x-data="{
        stats: {{ Js::from($stats) }},
        addStat() {
            this.stats.push({ value: '', label: '', icon: '' });
        },
        removeStat(idx) {
            this.stats.splice(idx, 1);
        }
    }">

    <div class="ppid-editor-block-head">
        <span class="ppid-editor-block-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" aria-hidden="true">
                <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/>
                <line x1="6" y1="20" x2="6" y2="14"/>
            </svg>
            Statistik
        </span>
        <button type="button" @click="addStat()" class="btn btn-sm btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Tambah Stat
        </button>
    </div>

    <template x-for="(stat, idx) in stats" :key="idx">
        <div class="ppid-item">
            <div class="ppid-item-head">
                <span class="ppid-item-title">
                    <span class="ppid-item-num" x-text="idx + 1"></span>
                    Statistik <span x-text="idx + 1"></span>
                </span>
                <button type="button" @click="removeStat(idx)" class="ppid-remove-btn" title="Hapus statistik">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                        <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                    </svg>
                </button>
            </div>
            <div class="ppid-item-body">
                <div class="grid gap-3" style="grid-template-columns: 140px 1fr 110px;">
                    <div>
                        <label class="form-label text-xs">Nilai</label>
                        <input type="text" class="form-input"
                            :name="'sections[{{ $section->id }}][metadata][stats][' + idx + '][value]'"
                            x-model="stat.value" placeholder="150">
                    </div>
                    <div>
                        <label class="form-label text-xs">Label</label>
                        <input type="text" class="form-input"
                            :name="'sections[{{ $section->id }}][metadata][stats][' + idx + '][label]'"
                            x-model="stat.label" placeholder="Total Layanan">
                    </div>
                    <div>
                        <label class="form-label text-xs">Icon</label>
                        <input type="text" class="form-input"
                            :name="'sections[{{ $section->id }}][metadata][stats][' + idx + '][icon]'"
                            x-model="stat.icon" placeholder="📊">
                    </div>
                </div>
            </div>
        </div>
    </template>

    <p class="ppid-empty-hint" x-show="stats.length === 0">Belum ada statistik. Klik "Tambah Stat" untuk menambahkan angka sorotan.</p>
</div>
