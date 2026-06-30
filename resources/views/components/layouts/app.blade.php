<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    style="--paper: oklch(94% 0.035 78); --paper-soft: oklch(91% 0.045 78); --paper-deep: oklch(84% 0.06 73); --ink: oklch(18% 0.035 82); --ink-soft: oklch(32% 0.045 80); --ash: oklch(54% 0.04 80); --line: oklch(73% 0.055 77); --gold: oklch(68% 0.145 74); --gold-bright: oklch(76% 0.165 80); --sun: oklch(64% 0.19 43); --sun-deep: oklch(52% 0.17 38); --night: oklch(17% 0.035 185); --night-soft: oklch(24% 0.04 170); --rice: oklch(97% 0.02 82); --focus: oklch(58% 0.18 42); --shadow: 0 28px 90px oklch(24% 0.05 75 / 0.22); --ease: cubic-bezier(0.16, 1, 0.3, 1); --ease-quart: cubic-bezier(0.25, 1, 0.5, 1); --ease-quint: cubic-bezier(0.22, 1, 0.36, 1); --font-display: 'Chakra Petch', 'Noto Sans JP', sans-serif; --font-serif: 'Zen Old Mincho', 'Hiragino Mincho ProN', serif; --font-mono: 'Azeret Mono', 'SFMono-Regular', monospace;">
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
</body>
</html>
