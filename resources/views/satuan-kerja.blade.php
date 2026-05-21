<x-layouts.app title="Satuan Kerja - SILATAR">
    @php
        $covers = [
            'kantor' => ['bg' => 'bg-[linear-gradient(135deg,_#eff6ff_0%,_#dbeafe_100%)]', 'chip' => 'bg-blue-600'],
            'kua' => ['bg' => 'bg-[linear-gradient(135deg,_#fff1f2_0%,_#ffe4e6_100%)]', 'chip' => 'bg-rose-600'],
            'min' => ['bg' => 'bg-[linear-gradient(135deg,_#f0fdf4_0%,_#dcfce7_100%)]', 'chip' => 'bg-emerald-600'],
            'mtsn' => ['bg' => 'bg-[linear-gradient(135deg,_#fefce8_0%,_#fef9c3_100%)]', 'chip' => 'bg-amber-500'],
            'man' => ['bg' => 'bg-[linear-gradient(135deg,_#f5f3ff_0%,_#ede9fe_100%)]', 'chip' => 'bg-violet-600'],
            'swasta-lainnya' => ['bg' => 'bg-[linear-gradient(135deg,_#fff7ed_0%,_#ffedd5_100%)]', 'chip' => 'bg-orange-500'],
            'pemerintah-daerah' => ['bg' => 'bg-[linear-gradient(135deg,_#f8fafc_0%,_#e2e8f0_100%)]', 'chip' => 'bg-slate-600'],
        ];
        $activeKey = $sections[0]['key'] ?? 'kantor';
    @endphp

    <main
        class="relative"
        x-data="{
            active: '{{ request('tab', $activeKey) }}',
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
            <div class="silatar-tab-shell">
                <div class="silatar-tab-list">
                    @foreach ($sections as $section)
                        <button
                            type="button"
                            @click="setTab('{{ $section['key'] }}')"
                            :class="active === '{{ $section['key'] }}'
                                ? 'bg-rose-700 text-white shadow-sm'
                                : 'bg-white text-slate-600 border border-slate-200 hover:border-slate-300'"
                            class="silatar-tab-button"
                        >
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 5.5h4v4H4zM12 5.5h4v4h-4zM4 13.5h4v4H4zM12 13.5h4v4h-4z" />
                            </svg>
                            <span>{{ $section['label'] }}</span>
                        </button>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-6xl px-6 py-8 lg:px-8">
            @foreach ($sections as $section)
                <section id="{{ $section['key'] }}" class="scroll-mt-8" x-show="active === '{{ $section['key'] }}'" x-cloak>
                    <div class="silatar-unit-grid">
                        @forelse ($section['cards'] as $card)
                            <x-ui.unit-card
                                :card="$card"
                                :section-label="$section['label']"
                                :chip-class="$covers[$section['key']]['chip']"
                                :cover-bg="$covers[$section['key']]['bg']"
                                :href="$card['href'] ?? null"
                            />
                        @empty
                            <div class="rounded-[1.5rem] border border-dashed border-slate-300 bg-white p-6 text-center text-sm text-slate-500">
                                Belum ada data unit kerja untuk kategori ini.
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
