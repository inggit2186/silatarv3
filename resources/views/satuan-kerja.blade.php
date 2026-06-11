<x-layouts.app title="Satuan Kerja - SILATAR">
    @php
        $covers = [
            'kantor' => ['chip' => 'bg-cyan-600', 'bg' => ''],
            'kua' => ['chip' => 'bg-rose-600', 'bg' => ''],
            'min' => ['chip' => 'bg-emerald-600', 'bg' => ''],
            'mtsn' => ['chip' => 'bg-amber-500', 'bg' => ''],
            'man' => ['chip' => 'bg-violet-600', 'bg' => ''],
            'swasta-lainnya' => ['chip' => 'bg-orange-500', 'bg' => ''],
            'pemerintah-daerah' => ['chip' => 'bg-slate-600', 'bg' => ''],
        ];
    @endphp

    <main
        class="relative cyber-bg"
        x-data="{
            active: '{{ request('tab', 'kantor') }}',
            setTab(key) {
                this.active = key;
                const url = new URL(window.location);
                url.searchParams.set('tab', key);
                history.replaceState({}, '', url);
            }
        }"
    >
        <x-ui.page-hero badge="Direktori satuan kerja" title="UNIT KERJA" />

        <section class="mx-auto max-w-6xl px-6 pb-4 lg:px-8">
            <div class="cyber-card">
                <div class="cyber-card-header !flex-row !gap-4">
                    <div class="flex flex-wrap gap-2">
                        @foreach ($sections as $section)
                            <button
                                type="button"
                                @click="setTab('{{ $section['key'] }}')"
                                class="cyber-tab-btn"
                                :class="active === '{{ $section['key'] }}' ? 'is-active' : ''"
                            >
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 5.5h4v4H4zM12 5.5h4v4h-4zM4 13.5h4v4H4zM12 13.5h4v4h-4z" />
                                </svg>
                                <span>{{ $section['label'] }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-6xl px-6 py-8 lg:px-8">
            @foreach ($sections as $section)
                <section id="{{ $section['key'] }}" class="scroll-mt-8" x-show="active === '{{ $section['key'] }}'" x-cloak>
                    <div class="cyber-unit-grid">
                        @forelse ($section['cards'] as $card)
                            <x-ui.unit-card
                                :card="$card"
                                :section-label="$section['label']"
                                :chip-class="$covers[$section['key']]['chip']"
                                :cover-bg="$covers[$section['key']]['bg']"
                                :href="$card['href'] ?? null"
                            />
                        @empty
                            <div class="cyber-empty-state col-span-full">
                                <svg class="h-16 w-16 text-cyan-500/30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H3a2 2 0 00-2 2v16m14 0H5m14 0h2m-2 0h-2M5 21h2m-2 0H3m14 0h2m-2 0h-2M7 7h10M7 11h10M7 15h6" />
                                </svg>
                                <p class="cyber-empty-title">Belum ada data</p>
                                <p class="cyber-empty-text">Belum ada data unit kerja untuk kategori ini.</p>
                            </div>
                        @endforelse
                    </div>

                    @if ($section['cards']->hasPages())
                        <x-ui.pagination-shell :paginator="$section['cards']" />
                    @endif
                </section>
            @endforeach
        </section>
    </main>
</x-layouts.app>