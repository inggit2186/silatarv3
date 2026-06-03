@props([
    'title' => '',
    'subtitle' => '',
    'tag' => 'Section',
])

<section class="relative py-20 lg:py-28">
    <!-- Background -->
    <div class="absolute inset-0 bg-gradient-to-b from-slate-950 via-slate-900/50 to-slate-950"></div>
    <div class="absolute inset-0 cyber-grid opacity-20"></div>

    <!-- Content -->
    <div class="relative z-10 mx-auto max-w-7xl px-6 lg:px-8">
        <!-- Section Header -->
        <div class="mb-16 text-center">
            <div class="mb-4 flex justify-center">
                <span class="inline-flex items-center gap-2 rounded-full border border-cyan-500/30 bg-cyan-500/10 px-4 py-1.5 font-mono text-xs font-semibold uppercase tracking-widest text-cyan-400">
                    {{ $tag }}
                </span>
            </div>
            <h2 class="mb-4 font-mono text-3xl font-bold uppercase tracking-wider text-white sm:text-4xl lg:text-5xl">
                {{ $title }}
            </h2>
            @if($subtitle)
                <p class="mx-auto max-w-2xl text-base leading-relaxed text-slate-400 sm:text-lg">
                    {{ $subtitle }}
                </p>
            @endif
        </div>

        <!-- Content Slot -->
        {{ $slot }}
    </div>

    <!-- Divider -->
    <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-cyan-500/30 to-transparent"></div>
</section>