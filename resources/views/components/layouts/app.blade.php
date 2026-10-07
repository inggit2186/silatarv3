<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    style="
    --paper: #f7f6f3; --paper: oklch(94% 0.035 78);
    --paper-soft: #eceae6; --paper-soft: oklch(91% 0.045 78);
    --paper-deep: #d6d3cb; --paper-deep: oklch(84% 0.06 73);
    --ink: #2d2824; --ink: oklch(18% 0.035 82);
    --ink-soft: #514c46; --ink-soft: oklch(32% 0.045 80);
    --ash: #8a8580; --ash: oklch(54% 0.04 80);
    --line: #b8b5b0; --line: oklch(73% 0.055 77);
    --gold: #d4a853; --gold: oklch(68% 0.145 74);
    --gold-bright: #e4c078; --gold-bright: oklch(76% 0.165 80);
    --sun: #d68a3a; --sun: oklch(64% 0.19 43);
    --sun-deep: #b86e28; --sun-deep: oklch(52% 0.17 38);
    --night: #1a2c35; --night: oklch(17% 0.035 185);
    --night-soft: #283040; --night-soft: oklch(24% 0.04 170);
    --rice: #f8f7f5; --rice: oklch(97% 0.02 82);
    --focus: #d4763a; --focus: oklch(58% 0.18 42);
    --shadow: 0 28px 90px rgba(42, 38, 35, 0.22); --shadow: 0 28px 90px oklch(24% 0.05 75 / 0.22);
    --ease: cubic-bezier(0.16, 1, 0.3, 1);
    --ease-quart: cubic-bezier(0.25, 1, 0.5, 1);
    --ease-quint: cubic-bezier(0.22, 1, 0.36, 1);
    --font-display: 'Chakra Petch', 'Noto Sans JP', sans-serif;
    --font-body: 'Chakra Petch', 'Noto Sans JP', sans-serif;
    --font-mono: 'JetBrains Mono', 'Fira Code', 'SFMono-Regular', monospace;
    ">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/webp" href="{{ asset('favicon.webp') }}">
        <title>{{ config('app.name') }} {{ $title ? '| ' . $title : '' }}</title>

        <!-- Google Fonts - Chakra Petch Style -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

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
            'resources/css/neo-mirai-home.css',
            'resources/js/app.js'
        ])

        @stack('styles')
        @stack('extraHead')
    </head>
    <body class="neo-mirai min-h-full text-slate-900 antialiased">
        <!-- Site Header - Sticky Full Navigation -->
        <x-layouts.site-header />

        <!-- Page Content -->
        <div class="relative" style="padding-top: var(--header-height);">
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

        <!-- Android App Promo Banner -->
        <div x-data="{
            show: true,
            dismissed: false,
            init() {
                if (localStorage.getItem('android-promo-dismissed')) {
                    this.dismissed = true;
                    this.show = false;
                }
            },
            dismiss() {
                this.show = false;
                this.dismissed = true;
                localStorage.setItem('android-promo-dismissed', 'true');
            }
        }" x-show="show && !dismissed" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 -translate-y-full" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-full"
            class="neo-app-promo" style="display: none;">
            <div class="neo-app-promo-inner">
                <div class="neo-app-promo-badge">
                    <svg class="neo-app-promo-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/>
                        <path d="M8 12l2 2 4-4"/>
                        <path d="M7 16.5c0 .83.67 1.5 1.5 1.5h7c.83 0 1.5-.67 1.5-1.5V14l-2 2.5h-6l-2-2.5v2.5z"/>
                    </svg>
                    <span class="neo-app-promo-badge-text">BARU!</span>
                </div>
                <div class="neo-app-promo-content">
                    <h4 class="neo-app-promo-title">SILATAR Android App</h4>
                    <p class="neo-app-promo-text">Dapatkan aplikasi mobile SILATAR untuk kemudahan akses di mana saja!</p>
                </div>
                <a href="https://play.google.com/store/apps/details?id=com.silatar.app" target="_blank" rel="noopener noreferrer" class="neo-app-promo-btn">
                    <svg class="neo-app-promo-btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4"/>
                    </svg>
                    Download Sekarang
                </a>
                <button @click="dismiss()" type="button" class="neo-app-promo-close" aria-label="Tutup pemberitahuan">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="neo-app-promo-glow" aria-hidden="true"></div>
        </div>

        <!-- Toast Notification -->
        <div x-data="{
            show: false,
            message: '',
            type: 'success',
            init() {
                @if(session('success'))
                    this.message = {{ json_encode(session('success')) }};
                    this.type = 'success';
                    this.show = true;
                    setTimeout(() => this.show = false, 5000);
                @endif
                @if(session('error'))
                    this.message = {{ json_encode(session('error')) }};
                    this.type = 'error';
                    this.show = true;
                    setTimeout(() => this.show = false, 5000);
                @endif
                @if($errors->any())
                    this.message = {{ json_encode($errors->first()) }};
                    this.type = 'error';
                    this.show = true;
                    setTimeout(() => this.show = false, 5000);
                @endif
            }
        }" x-show="show" x-transition
            :class="type === 'success' ? 'bg-emerald-500' : 'bg-red-500'"
            class="fixed bottom-6 right-6 z-50 px-6 py-4 rounded-xl shadow-2xl text-white font-medium flex items-center gap-3 max-w-md"
            style="display: none;">
            <template x-if="type === 'success'">
                <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </template>
            <template x-if="type === 'error'">
                <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </template>
            <span x-text="message" class="flex-1"></span>
            <button @click="show = false" class="flex-shrink-0 hover:opacity-80 transition-opacity">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
</body>
</html>
