{{-- Section: form_fields --}}
{{-- Form fields definition with name, label, type, required, options --}}
@php
    $fields = $section->metadata['fields'] ?? [];
@endphp
<div class="section-block" data-section-id="{{ $section->id }}"
    x-data="{
        fields: {{ Js::from($fields) }},
        fieldTypes: ['text', 'email', 'textarea', 'select', 'checkbox', 'date', 'number', 'file'],
        addField() {
            this.fields.push({ name: '', label: '', type: 'text', required: false, options: [] });
        },
        removeField(idx) {
            this.fields.splice(idx, 1);
        },
        addOption(fi) {
            this.fields[fi].options.push('');
        },
        removeOption(fi, oi) {
            this.fields[fi].options.splice(oi, 1);
        }
    }">

    <div class="ppid-editor-block-head">
        <span class="ppid-editor-block-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" aria-hidden="true">
                <rect x="3" y="3" width="18" height="18" rx="2"/><path d="M7 7h10M7 12h10M7 17h6"/>
            </svg>
            Definisi Field Formulir
        </span>
        <button type="button" @click="addField()" class="btn btn-sm btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Tambah Field
        </button>
    </div>

    <template x-for="(field, fi) in fields" :key="fi">
        <div class="ppid-item">
            <div class="ppid-item-head">
                <span class="ppid-item-title">
                    <span class="ppid-item-num" x-text="fi + 1"></span>
                    Field <span x-text="fi + 1"></span>
                </span>
                <button type="button" @click="removeField(fi)" class="ppid-remove-btn" title="Hapus field">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                        <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                    </svg>
                </button>
            </div>
            <div class="ppid-item-body space-y-3">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="form-label text-xs">Nama Field (slug)</label>
                        <input type="text" class="form-input"
                            :name="'sections[{{ $section->id }}][metadata][fields][' + fi + '][name]'"
                            x-model="field.name" placeholder="nama_field">
                    </div>
                    <div>
                        <label class="form-label text-xs">Label Tampilan</label>
                        <input type="text" class="form-input"
                            :name="'sections[{{ $section->id }}][metadata][fields][' + fi + '][label]'"
                            x-model="field.label" placeholder="Nama Field">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="form-label text-xs">Tipe Input</label>
                        <select class="form-input"
                            :name="'sections[{{ $section->id }}][metadata][fields][' + fi + '][type]'"
                            x-model="field.type">
                            <template x-for="t in fieldTypes" :key="t">
                                <option :value="t" x-text="t"></option>
                            </template>
                        </select>
                    </div>
                    <div class="flex items-center gap-3 pt-5">
                        <label class="flex items-center gap-2">
                            <input type="checkbox"
                                :name="'sections[{{ $section->id }}][metadata][fields][' + fi + '][required]'"
                                :checked="field.required"
                                @change="field.required = $event.target.checked"
                                value="1">
                            <span class="text-sm">Wajib diisi</span>
                        </label>
                    </div>
                </div>

                {{-- Options for select type --}}
                <div x-show="field.type === 'select'">
                    <label class="form-label text-xs">Opsi (untuk select)</label>
                    <div class="flex flex-wrap gap-2 mb-2">
                        <template x-for="(opt, oi) in field.options" :key="oi">
                            <div class="flex items-center gap-1" style="border: 1px solid var(--border); padding: 0.25rem 0.5rem; border-radius: 0.375rem;">
                                <input type="text" class="form-input" style="border: none; background: transparent; padding: 0; width: 120px;"
                                    :name="'sections[{{ $section->id }}][metadata][fields][' + fi + '][options][' + oi + ']'"
                                    x-model="field.options[oi]" placeholder="Opsi...">
                                <button type="button" @click="removeOption(fi, oi)" class="ppid-remove-btn"
                                    style="width: 20px; height: 20px;" title="Hapus opsi">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12">
                                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>
                    <button type="button" @click="addOption(fi)" class="btn btn-sm btn-outline">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        Tambah Opsi
                    </button>
                </div>
            </div>
        </div>
    </template>

    <p class="ppid-empty-hint" x-show="fields.length === 0">Belum ada field. Klik "Tambah Field" untuk mendefinisikan input formulir.</p>
</div>
