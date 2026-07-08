{{-- Cyberpunk Page Layout Component - Reusable across all pages --}}

{{-- Usage: <x-cyber.page title="Page Title">...content...</x-cyber.page> --}}

@props([
    'title' => '',
    'showParticles' => true,
    'particleCount' => 80,
])

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ? $title . ' | SILATAR' : 'SILATAR' }}</title>
        @fonts
        @vite([
            'resources/css/app.css',
            'resources/css/cyberpunk.css',
            'resources/js/app.js',
            'resources/js/cyber-particles.js'
        ])
    </head>
    <body class="min-h-full bg-slate-950 text-white antialiased">

        {{-- Global Particle System --}}
        @if($showParticles)
        <div class="fixed inset-0 pointer-events-none z-0">
            <canvas
                id="globalParticles"
                data-particle-canvas
                data-particle-count="{{ $particleCount }}"
                data-mouse-influence="true"
                class="w-full h-full"
            ></canvas>
        </div>
        @endif

        {{-- Floating Cyberpunk Icons --}}
        <div class="fixed inset-0 pointer-events-none z-[5] overflow-hidden">
            <div class="absolute top-[15%] left-6 w-14 h-14 animate-pulse" style="animation-duration: 4s;">
                <svg class="w-full h-full text-cyan-400 opacity-15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                    <path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
            </div>
            <div class="absolute top-[25%] right-12 w-10 h-10 animate-pulse" style="animation-duration: 5s; animation-delay: 1s;">
                <svg class="w-full h-full text-purple-400 opacity-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
                </svg>
            </div>
            <div class="absolute bottom-[30%] left-[20%] w-12 h-12 animate-pulse" style="animation-duration: 4.5s; animation-delay: 0.5s;">
                <svg class="w-full h-full text-cyan-500 opacity-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
            </div>
            <div class="absolute top-[40%] right-[25%] w-8 h-8 animate-pulse" style="animation-duration: 3.5s; animation-delay: 2s;">
                <svg class="w-full h-full text-cyan-400 opacity-15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                    <path d="M16 18l6-6-6-6M8 6l-6 6 6 6"/>
                </svg>
            </div>
            <div class="absolute bottom-[20%] right-[15%] w-10 h-10 animate-pulse" style="animation-duration: 3s; animation-delay: 1.5s;">
                <svg class="w-full h-full text-purple-500 opacity-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                    <ellipse cx="12" cy="5" rx="9" ry="3"/>
                    <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/>
                </svg>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="relative">
            {{ $slot }}
        </div>
    <!-- impeccable-live-start -->
<script src="http://localhost:8400/live.js"></script>
<!-- impeccable-live-end -->
</body>
</html>