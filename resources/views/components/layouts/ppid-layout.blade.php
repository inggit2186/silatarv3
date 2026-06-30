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
    <meta property="og:title" content="{{ $ogTitle ?? $title ?? 'PPID - Pejabat Pengelola Informasi dan Dokumentasi' }}">
    <meta property="og:description" content="{{ $ogDescription ?? 'Portal PPID Kementerian Agama Kabupaten Tanah Datar' }}">
    <meta property="og:image" content="{{ $ogImage ?? asset('favicon.webp') }}">
    <meta property="og:url" content="{{ $ogUrl ?? url()->current() }}">
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $ogTitle ?? $title ?? 'PPID' }}">
    <meta name="twitter:description" content="{{ $ogDescription ?? '' }}">
    <meta name="twitter:image" content="{{ $ogImage ?? asset('favicon.webp') }}">

    @vite([
        'resources/css/app.css',
        'resources/css/cyberpunk.css',
        'resources/css/neo-mirai-home.css',
        'resources/css/ppid.css',
        'resources/js/app.js',
        'resources/js/cyber-particles.js'
    ])

    @stack('extraHead')
</head>
<body class="neo-mirai neo-ppid min-h-full text-slate-900 antialiased">
    <!-- Page Content -->
    <div class="ppid-layout">
        {{ $slot }}
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Reveal animations
            var revealElements = document.querySelectorAll('[data-reveal]');
            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                    }
                });
            }, { threshold: 0.1 });

            revealElements.forEach(function(el) {
                observer.observe(el);
                var rect = el.getBoundingClientRect();
                if (rect.top < window.innerHeight) {
                    el.classList.add('is-visible');
                }
            });
        });
    </script>
</body>
</html>
