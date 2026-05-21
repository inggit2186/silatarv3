<x-layouts.app :title="$department['name'] . ' - SILATAR'">
    <x-ui.page-hero badge="Detail unit kerja" :title="$department['name']">
        <div class="mx-auto mt-4 max-w-3xl space-y-3 text-center text-sm text-slate-600">
            <p>
                {{ $department['description'] ?: 'Detail pegawai pada unit kerja ini.' }}
            </p>
            <div class="flex flex-wrap items-center justify-center gap-2">
                <span class="rounded-full bg-cyan-50 px-3 py-1 font-semibold uppercase tracking-[0.2em] text-cyan-700">
                    Kode {{ $department['code'] }}
                </span>
                <span class="rounded-full bg-slate-100 px-3 py-1 font-semibold uppercase tracking-[0.2em] text-slate-700">
                    {{ strtoupper($department['category']) }}
                </span>
                <a href="{{ route('satuan-kerja', ['tab' => $department['category']]) }}" class="rounded-full bg-white px-3 py-1 font-semibold uppercase tracking-[0.2em] text-slate-700 shadow-sm ring-1 ring-slate-200 transition hover:text-slate-900">
                    Kembali
                </a>
            </div>
        </div>
    </x-ui.page-hero>

    <section class="silatar-detail-shell">
        @if ($leader)
            <div class="pb-10">
                <div class="mx-auto max-w-sm text-center">
                    <p class="mb-4 text-sm font-semibold uppercase tracking-[0.28em] text-cyan-700">
                        {{ $leaderLabel }}
                    </p>
                    <x-ui.person-card :person="$leader" featured />
                </div>
            </div>
        @endif

        <div class="mb-6 border-t border-slate-200 pt-8 text-center">
            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-cyan-700">Pegawai</p>
            <h2 class="mt-2 text-2xl font-semibold text-slate-900">Daftar pegawai unit kerja</h2>
        </div>

        @if ($people->count())
            <div class="silatar-people-grid">
                @foreach ($people as $person)
                    <x-ui.person-card :person="$person" />
                @endforeach
            </div>
        @else
            <div class="rounded-[1.5rem] border border-dashed border-slate-300 bg-white p-8 text-center text-sm text-slate-500">
                Belum ada pegawai lain pada unit kerja ini.
            </div>
        @endif

        @if ($people->hasPages())
            <x-ui.pagination-shell :paginator="$people" />
        @endif
    </section>
</x-layouts.app>
