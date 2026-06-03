@props([
    'stats' => [],
])

<div class="grid grid-cols-2 gap-4 md:grid-cols-4">
    @foreach($stats as $stat)
        <div class="relative overflow-hidden rounded-2xl border border-cyan-500/20 bg-gradient-to-br from-slate-900/90 to-slate-950/90 p-5 text-center transition-all duration-500 hover:border-cyan-400/40 hover:shadow-[0_0_30px_rgba(0,212,255,0.15)]">
            <!-- Background Glow -->
            <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/5 to-transparent"></div>

            <!-- Icon -->
            @if(isset($stat['icon']) && $stat['icon'])
                <div class="mb-3 flex justify-center">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl border border-cyan-500/30 bg-cyan-500/10">
                        {!! $stat['icon'] !!}
                    </div>
                </div>
            @endif

            <!-- Value -->
            <div class="relative">
                <span class="font-mono text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-cyan-300">
                    {{ $stat['value'] ?? 0 }}{{ $stat['suffix'] ?? '' }}
                </span>
            </div>

            <!-- Label -->
            <p class="mt-2 text-sm font-medium text-slate-400">{{ $stat['label'] ?? '' }}</p>

            <!-- Animated Border on Hover -->
            <div class="absolute inset-0 rounded-2xl opacity-0 transition-opacity duration-300 hover:opacity-100">
                <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-transparent via-cyan-500 to-transparent"></div>
            </div>
        </div>
    @endforeach
</div>