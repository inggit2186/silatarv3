{{-- Section: card_grid --}}
{{-- Card grid with title, description, link, icon --}}
@php
    $cards = $section->metadata['cards'] ?? [];
@endphp
<div class="section-block" data-section-id="{{ $section->id }}"
    x-data="{
        cards: {{ Js::from($cards) }},
        addCard() {
            this.cards.push({ title: '', description: '', link: '', icon: '' });
        },
        removeCard(idx) {
            this.cards.splice(idx, 1);
        }
    }">

    <div class="ppid-editor-block-head">
        <span class="ppid-editor-block-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" aria-hidden="true">
                <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
            </svg>
            Kartu Grid
        </span>
        <button type="button" @click="addCard()" class="btn btn-sm btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Tambah Kartu
        </button>
    </div>

    <template x-for="(card, idx) in cards" :key="idx">
        <div class="ppid-item">
            <div class="ppid-item-head">
                <span class="ppid-item-title">
                    <span class="ppid-item-num" x-text="idx + 1"></span>
                    Kartu <span x-text="idx + 1"></span>
                </span>
                <button type="button" @click="removeCard(idx)" class="ppid-remove-btn" title="Hapus kartu">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                        <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                    </svg>
                </button>
            </div>
            <div class="ppid-item-body space-y-3">
                <div>
                    <label class="form-label text-xs">Judul Kartu</label>
                    <input type="text" class="form-input"
                        :name="'sections[{{ $section->id }}][metadata][cards][' + idx + '][title]'"
                        x-model="card.title" placeholder="Judul kartu...">
                </div>
                <div>
                    <label class="form-label text-xs">Deskripsi</label>
                    <textarea class="form-input" rows="2"
                        :name="'sections[{{ $section->id }}][metadata][cards][' + idx + '][description]'"
                        x-model="card.description" placeholder="Deskripsi singkat..."></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="form-label text-xs">Link (URL)</label>
                        <input type="url" class="form-input"
                            :name="'sections[{{ $section->id }}][metadata][cards][' + idx + '][link]'"
                            x-model="card.link" placeholder="https://...">
                    </div>
                    <div>
                        <label class="form-label text-xs">Icon (emoji/nama)</label>
                        <input type="text" class="form-input"
                            :name="'sections[{{ $section->id }}][metadata][cards][' + idx + '][icon]'"
                            x-model="card.icon" placeholder="📋">
                    </div>
                </div>
            </div>
        </div>
    </template>

    <p class="ppid-empty-hint" x-show="cards.length === 0">Belum ada kartu. Klik "Tambah Kartu" untuk membuat kartu.</p>
</div>
