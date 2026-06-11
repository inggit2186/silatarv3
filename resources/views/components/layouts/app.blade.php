<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/webp" href="{{ asset('favicon.webp') }}">
        <title>{{ config('app.name') }} {{ $title ? '| ' . $title : '' }}</title>
        @vite([
            'resources/css/app.css',
            'resources/css/cyberpunk.css',
            'resources/js/app.js',
            'resources/js/cyber-particles.js'
        ])
    </head>
    <body class="min-h-full bg-slate-950 text-white antialiased">
        <!-- Global Particles -->
        <div class="fixed inset-0 pointer-events-none z-0">
            <canvas
                id="globalParticles"
                data-particle-canvas
                data-particle-count="60"
                data-mouse-influence="true"
                class="w-full h-full"
            ></canvas>
        </div>

        <!-- Page Content -->
        <div class="relative">
            @if(!request()->routeIs('home'))
                <x-layouts.site-header />
            @endif
            {{ $slot }}
        </div>

        @php
            $uiConfig = config('ui', []);
            $livewireConfig = [
                'csrf' => csrf_token(),
                'uri' => url(Livewire::getUpdateUri()),
                'moduleUrl' => url(Livewire::getUriPrefix()),
            ];
        @endphp
        <script>
            window.appUiConfig = {!! json_encode($uiConfig) !!};
            window.livewireScriptConfig = {!! json_encode($livewireConfig) !!};
        </script>
    </body>
</html>
