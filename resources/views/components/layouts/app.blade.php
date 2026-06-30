<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/webp" href="{{ asset('favicon.webp') }}">
        <title>{{ config('app.name') }} {{ $title ? '| ' . $title : '' }}</title>

        <!-- Google Fonts - NEO MIRAI Style -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Azeret+Mono:wght@300;400;500;600;700&family=Chakra+Petch:wght@300;400;500;600;700&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
            'resources/css/neo-mirai-home.css',
            'resources/js/app.js',
            'resources/js/cyber-particles.js'
        ])

        @stack('extraHead')
    </head>
    <body class="neo-mirai min-h-full text-slate-900 antialiased">
        <!-- Page Content -->
        <div class="relative">
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
    <!-- impeccable-live-start -->
<script src="http://localhost:8400/live.js"></script>
<!-- impeccable-live-end -->
</body>
</html>
