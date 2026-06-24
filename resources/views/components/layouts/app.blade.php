<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/webp" href="{{ asset('favicon.webp') }}">
        <title>{{ config('app.name') }} {{ $title ? '| ' . $title : '' }}</title>

        <!-- Open Graph / Social Media Sharing -->
        <meta property="og:site_name" content="{{ config('app.name') }}">
        <meta property="og:title" content="{{ $ogTitle ?? $title ?? config('app.name') }}">
        <meta property="og:description" content="{{ $ogDescription ?? '' }}">
        <meta property="og:image" content="{{ $ogImage ?? asset('favicon.webp') }}">
        <meta property="og:url" content="{{ $ogUrl ?? url()->current() }}">
        <meta property="og:type" content="{{ $ogType ?? 'website' }}">

        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $ogTitle ?? $title ?? config('app.name') }}">
        <meta name="twitter:description" content="{{ $ogDescription ?? '' }}">
        <meta name="twitter:image" content="{{ $ogImage ?? asset('favicon.webp') }}">

        @vite([
            'resources/css/app.css',
            'resources/css/cyberpunk.css',
            'resources/js/app.js',
            'resources/js/cyber-particles.js'
        ])

        @stack('extraHead')
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
