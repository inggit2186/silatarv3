<x-layouts.app :title="$department['name'] . ' - SILATAR'">
    <x-ui.page-hero badge="Detail unit kerja" :title="$department['name']">
        <div class="mx-auto mt-4 max-w-3xl space-y-3 text-center text-sm">
            <p class="cyber-text-subtle">
                {{ $department['description'] ?: 'Detail pegawai pada unit kerja ini.' }}
            </p>
            <div class="flex flex-wrap items-center justify-center gap-2">
                <span class="cyber-badge-cyber">
                    Kode {{ $department['code'] }}
                </span>
                <span class="cyber-badge">
                    {{ strtoupper($department['category']) }}
                </span>
                <a href="{{ route('satuan-kerja', ['tab' => $department['category']]) }}" class="cyber-btn-secondary">
                    Kembali
                </a>
            </div>
        </div>
    </x-ui.page-hero>

    <section class="mx-auto max-w-6xl px-6 py-8 lg:px-8">
        @if ($leader)
            <div class="pb-10">
                <div class="cyber-leader-section justify-center">
                    <div class="cyber-leader-main flex flex-col items-center">
                        <p class="mb-4 text-sm font-semibold uppercase tracking-[0.28em] text-cyan-400">
                            {{ $leaderLabel }}
                        </p>
                        <x-ui.person-card :person="$leader" featured />
                    </div>

                    @if (count($kaurs))
                        <div class="cyber-leader-sidebar flex flex-col items-center">
                            <p class="mb-4 text-sm font-semibold uppercase tracking-[0.28em] text-amber-400">
                                Kaur
                            </p>
                            <div class="cyber-kaurs-grid">
                                @foreach ($kaurs as $kaur)
                                    <x-ui.person-card :person="$kaur" />
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <div class="mb-6 border-t border-cyan-500/20 pt-8 text-center">
            <p class="cyber-section-step">Daftar Pegawai</p>
            <h2 class="mt-2 text-2xl font-bold text-white">Pegawai unit kerja</h2>
        </div>

        @if ($people->count())
            <div class="cyber-people-grid">
                @foreach ($people as $person)
                    <x-ui.person-card :person="$person" />
                @endforeach
            </div>
        @else
            <div class="cyber-empty-state">
                <svg class="h-16 w-16 text-cyan-500/30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
                <p class="cyber-empty-title">Belum ada pegawai</p>
                <p class="cyber-empty-text">Belum ada pegawai lain pada unit kerja ini.</p>
            </div>
        @endif

        @if ($people->hasPages())
            <x-ui.pagination-shell :paginator="$people" />
        @endif
    </section>
</x-layouts.app>