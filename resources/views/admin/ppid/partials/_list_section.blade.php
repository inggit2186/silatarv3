{{-- Section: list --}}
{{-- List items with add/remove rows --}}
@php
    $items = $section->metadata['items'] ?? [];
@endphp
<div class="section-block" data-section-id="{{ $section->id }}"
    x-data="{
        items: {{ Js::from($items) }},
        addItem() {
            this.items.push('');
        },
        removeItem(idx) {
            this.items.splice(idx, 1);
        }
    }">

    <div class="ppid-editor-block-head">
        <span class="ppid-editor-block-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" aria-hidden="true">
                <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/>
                <line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/>
                <line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>
            </svg>
            Daftar Item
        </span>
        <button type="button" @click="addItem()" class="btn btn-sm btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Tambah Item
        </button>
    </div>

    <template x-for="(item, idx) in items" :key="idx">
        <div class="flex items-center gap-2 mb-2">
            <span class="ppid-item-num" x-text="idx + 1"></span>
            <input type="text"
                class="form-input flex-1"
                :name="'sections[{{ $section->id }}][metadata][items][' + idx + ']'"
                x-model="items[idx]"
                placeholder="Ketik item...">
            <button type="button" @click="removeItem(idx)" class="ppid-remove-btn" :disabled="items.length <= 1"
                    title="Hapus item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
    </template>

    <p class="ppid-empty-hint" x-show="items.length === 0">Belum ada item. Klik "Tambah Item" untuk membuat daftar.</p>
</div>
