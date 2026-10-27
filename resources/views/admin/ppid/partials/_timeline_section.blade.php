{{-- Section: timeline --}}
{{-- Timeline with number, title, description per step --}}
@php
    $steps = $section->metadata['steps'] ?? [];
@endphp
<div class="section-block" data-section-id="{{ $section->id }}"
    x-data="{
        steps: {{ Js::from($steps) }},
        addStep() {
            this.steps.push({ number: this.steps.length + 1, title: '', description: '' });
        },
        removeStep(idx) {
            this.steps.splice(idx, 1);
            // Re-number remaining steps
            this.steps.forEach((s, i) => { s.number = i + 1; });
        }
    }">

    <div class="ppid-editor-block-head">
        <span class="ppid-editor-block-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" aria-hidden="true">
                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
            </svg>
            Langkah Timeline
        </span>
        <button type="button" @click="addStep()" class="btn btn-sm btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Tambah Langkah
        </button>
    </div>

    <template x-for="(step, idx) in steps" :key="idx">
        <div class="ppid-item">
            <div class="ppid-item-head">
                <span class="ppid-item-title">
                    <span class="ppid-item-num" x-text="step.number"></span>
                    Langkah <span x-text="step.number"></span>
                </span>
                <button type="button" @click="removeStep(idx)" class="ppid-remove-btn" title="Hapus langkah">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                        <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                    </svg>
                </button>
            </div>
            <div class="ppid-item-body space-y-3">
                <div class="grid grid-cols-4 gap-3">
                    <div>
                        <label class="form-label text-xs">Nomor</label>
                        <input type="number" class="form-input"
                            x-model="step.number" min="1">
                        <input type="hidden"
                            :name="'sections[{{ $section->id }}][metadata][steps][' + idx + '][number]'"
                            :value="step.number">
                    </div>
                    <div class="col-span-3">
                        <label class="form-label text-xs">Judul</label>
                        <input type="text" class="form-input"
                            :name="'sections[{{ $section->id }}][metadata][steps][' + idx + '][title]'"
                            x-model="step.title" placeholder="Judul langkah...">
                    </div>
                </div>
                <div>
                    <label class="form-label text-xs">Deskripsi</label>
                    <textarea class="form-input" rows="2"
                        :name="'sections[{{ $section->id }}][metadata][steps][' + idx + '][description]'"
                        x-model="step.description" placeholder="Deskripsi langkah..."></textarea>
                </div>
            </div>
        </div>
    </template>

    <p class="ppid-empty-hint" x-show="steps.length === 0">Belum ada langkah. Klik "Tambah Langkah" untuk menyusun tahapan.</p>
</div>
