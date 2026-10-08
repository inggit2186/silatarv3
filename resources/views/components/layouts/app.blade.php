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
                @auth
                <button @click="$dispatch('open-apk-modal')" type="button" class="neo-app-promo-btn">
                    <svg class="neo-app-promo-btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/>
                    </svg>
                    Download Sekarang
                </button>
                @else
                <a href="{{ route('login') }}" class="neo-app-promo-btn">
                    <svg class="neo-app-promo-btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5M15 12H3"/>
                    </svg>
                    Login untuk Download
                </a>
                @endauth
                <button @click="dismiss()" type="button" class="neo-app-promo-close" aria-label="Tutup pemberitahuan">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="neo-app-promo-glow" aria-hidden="true"></div>


        <!-- APK Download Info Modal -->
        <div x-data="{ show: false }" @open-apk-modal.window="show = true" @keydown.escape.window="show = false" x-show="show" x-transition class="neo-app-modal-backdrop" style="display: none;">
            <div class="neo-app-modal" @click.outside="show = false">
                <div class="neo-app-modal-header">
                    <div class="neo-app-modal-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/>
                            <path d="M8 12l2 2 4-4"/><path d="M7 16.5c0 .83.67 1.5 1.5 1.5h7c.83 0 1.5-.67 1.5-1.5V14l-2 2.5h-6l-2-2.5v2.5z"/>
                        </svg>
                    </div>
                    <div><h3 class="neo-app-modal-title">Download SILATAR Android</h3><p class="neo-app-modal-subtitle">Kantor Kementerian Agama Kab. Tanah Datar</p></div>
                    <button @click="show = false" type="button" class="neo-app-modal-close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
                <div class="neo-app-modal-body">
                    <div class="neo-app-modal-section">
                        <h4 class="neo-app-modal-section-title"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2v-4M9 21H5a2 2 0 01-2-2v-4"/></svg>System Requirements</h4>
                        <div class="neo-app-modal-req-grid">
                            <div class="neo-app-modal-req-item"><span class="neo-app-modal-req-label">OS Android</span><span class="neo-app-modal-req-value">7.0 (Nougat) ke atas</span></div>
                            <div class="neo-app-modal-req-item"><span class="neo-app-modal-req-label">minSdk</span><span class="neo-app-modal-req-value">24</span></div>
                            <div class="neo-app-modal-req-item"><span class="neo-app-modal-req-label">Flutter SDK</span><span class="neo-app-modal-req-value">3.12+</span></div>
                            <div class="neo-app-modal-req-item"><span class="neo-app-modal-req-label">Java/Kotlin</span><span class="neo-app-modal-req-value">17</span></div>
                        </div>
                        <div class="neo-app-modal-coverage"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span>~99.9% device Android didukung</span></div>
                    </div>
                    <div class="neo-app-modal-section">
                        <h4 class="neo-app-modal-section-title"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>Petunjuk Instalasi</h4>
                        <ol class="neo-app-modal-steps">
                            <li>Download file APK</li>
                            <li>Aktifkan <strong>Sumber Tidak Dikenal</strong> di Pengaturan > Keamanan</li>
                            <li>Install file APK</li>
                            <li>Buka aplikasi & Login</li>
                        </ol>
                    </div>
                    <div class="neo-app-modal-warning">
                        <div class="neo-app-modal-warning-header"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg><span>Peringatan Penting</span></div>
                        <p><strong>JANGAN</strong> install APK ini jika didownload dari sumber tidak resmi!</p>
                        <p>HANYA install dari Website Resmi SILATAR atau link resmi dari nomor resmi.</p>
                        <div class="neo-app-modal-contact"><span>Hubungi Official Number:</span> <a href="https://wa.me/6289509007078" target="_blank" class="neo-app-modal-contact-link"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>0895 0900 7078</a></div>
                    </div>
                </div>
                <div class="neo-app-modal-footer"><a href="{{ route('apk.download') }}" class="neo-app-modal-download-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>Kirim ke WhatsApp Saya</a></div>
            </div>
        </div>


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
