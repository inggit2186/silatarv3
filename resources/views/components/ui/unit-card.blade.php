@props([
    'card' => [],
    'sectionLabel' => '',
    'chipClass' => 'bg-cyan-600',
    'coverBg' => '',
    'href' => null,
])

@if ($href)
<a href="{{ $href }}" class="block h-full w-full">
    <article class="silatar-unit-card h-full">
@else
    <article class="silatar-unit-card h-full">
@endif
    <div class="silatar-unit-cover {{ $coverBg }}">
        <img
            src="{{ $card['cover_path'] }}"
            alt="{{ $card['title'] }}"
            loading="lazy"
            decoding="async"
            class="silatar-unit-cover-img"
            onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');"
        >

        <div class="silatar-unit-cover-overlay">
            <div class="absolute -left-14 top-8 h-44 w-44 rounded-full bg-white/55 blur-3xl"></div>
            <div class="absolute -right-10 top-24 h-52 w-52 rounded-full bg-white/40 blur-3xl"></div>
            <div class="absolute bottom-0 left-1/2 h-40 w-[120%] -translate-x-1/2 rounded-[100%_100%_0_0] bg-white/35 blur-2xl"></div>
        </div>

        <div class="silatar-unit-cover-fallback">
            <div class="silatar-unit-cover-fallback-chip">
                {{ $card['cover'] }}
            </div>
        </div>

        <div class="silatar-unit-cover-chip {{ $chipClass }}">
            {{ $sectionLabel }}
        </div>

        <div class="silatar-unit-cover-star">
            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 17s-6-3.5-6-8.5A3.5 3.5 0 0 1 10 5a3.5 3.5 0 0 1 6 3.5C16 13.5 10 17 10 17Z" />
            </svg>
        </div>

        <div class="silatar-unit-cover-label">
            <div class="silatar-unit-cover-label-chip">
                <p class="silatar-unit-cover-label-text">
                    {{ $card['title'] }}
                </p>
            </div>
        </div>
    </div>

    <div class="silatar-unit-body">
        <div class="silatar-unit-info">
            <span class="inline-flex items-center gap-2 font-medium text-slate-500">
                <svg class="h-4 w-4 text-cyan-600" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 4a3 3 0 0 1 3 3v1a3 3 0 0 1-6 0V7a3 3 0 0 1 3-3Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16a6 6 0 0 1 12 0" />
                </svg>
                Pegawai
            </span>
            <span class="max-w-[11rem] truncate rounded-full bg-white px-3 py-1 text-right font-semibold text-slate-800 shadow-sm">
                {{ $card['extra_value'] }}
            </span>
        </div>

        @if (! empty($card['head_value']))
            <div class="silatar-unit-head">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-white text-cyan-700 shadow-sm">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 10.5a3 3 0 1 0-3-3 3 3 0 0 0 3 3Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16a6 6 0 0 1 12 0" />
                    </svg>
                </span>
                <div class="min-w-0">
                    <p class="text-[0.6rem] font-semibold uppercase tracking-[0.28em] text-cyan-600">
                        {{ $card['head_label'] }}
                    </p>
                    <p class="truncate font-semibold text-cyan-950">
                        {{ $card['head_value'] }}
                    </p>
                </div>
            </div>
        @else
            <div class="silatar-unit-head-placeholder" aria-hidden="true">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-transparent text-transparent">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 10.5a3 3 0 1 0-3-3 3 3 0 0 0 3 3Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16a6 6 0 0 1 12 0" />
                    </svg>
                </span>
                <div class="min-w-0">
                    <p class="text-[0.6rem] font-semibold uppercase tracking-[0.28em] text-transparent">
                        Placeholder
                    </p>
                    <p class="truncate font-semibold text-transparent">
                        -
                    </p>
                </div>
            </div>
        @endif

        <div class="flex items-center justify-between gap-3 text-sm text-slate-500">
            <span class="inline-flex items-center gap-2">
                <span class="silatar-unit-meta-chip bg-cyan-50 text-cyan-700">
                    {{ $sectionLabel }}
                </span>
                @if (($card['type'] ?? null) === 'user')
                    <span class="silatar-unit-meta-chip bg-amber-50 text-amber-700">
                        PP
                    </span>
                @endif
            </span>
            <span class="inline-flex items-center gap-2 font-medium text-slate-400">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 17s-5-3.2-5-7.4A5 5 0 1 1 15 9.6C15 13.8 10 17 10 17Z" />
                    <circle cx="10" cy="9.5" r="1.4" />
                </svg>
                Unit SILATAR
            </span>
        </div>
    </div>
@if ($href)
    </article>
</a>
@else
    </article>
@endif
