@props([
    'badge' => null,
    'title' => null,
    'cyber' => true,
])

@if($cyber)
    <section class="bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 py-12 lg:py-16">
        <div class="mx-auto max-w-4xl px-6 lg:px-8 text-center">
            @if($badge)
                <span class="inline-flex items-center gap-2 rounded-full border border-cyan-500/30 bg-cyan-500/10 px-4 py-1.5 font-mono text-xs font-semibold uppercase tracking-widest text-cyan-400">
                    <span class="relative flex h-2 w-2">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-cyan-400 opacity-75"></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full bg-cyan-500"></span>
                    </span>
                    {{ $badge }}
                </span>
            @endif
            @if($title)
                <h1 class="mt-4 font-mono text-4xl font-black uppercase tracking-wider text-white lg:text-5xl">
                    {{ $title }}
                </h1>
            @endif
            {{ $slot }}
        </div>
    </section>
@else
    <section class="mx-auto max-w-6xl px-6 pb-8 pt-6 lg:px-8 lg:pb-10">
        <div class="space-y-5 text-center">
            @if($badge)
                <span class="inline-flex items-center gap-2 rounded-full border border-cyan-200 bg-cyan-50 px-4 py-2 text-sm font-medium text-cyan-700 shadow-sm">
                    <span class="h-2 w-2 rounded-full bg-cyan-500"></span>
                    {{ $badge }}
                </span>
            @endif
            @if($title)
                <h1 class="font-sans text-4xl font-semibold tracking-tight text-slate-900 sm:text-5xl">
                    {{ $title }}
                </h1>
            @endif
            {{ $slot }}
        </div>
    </section>
@endif