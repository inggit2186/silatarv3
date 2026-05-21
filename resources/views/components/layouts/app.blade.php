<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-full bg-[radial-gradient(circle_at_top,_rgba(14,165,233,0.09),_transparent_32%),radial-gradient(circle_at_bottom_right,_rgba(56,189,248,0.08),_transparent_24%),linear-gradient(180deg,_#f8fafc_0%,_#eff6ff_55%,_#f8fafc_100%)] text-slate-900 antialiased">
        @php
            $loadingRoutes = config('ui.loading.enabled_routes', []);
            $loadingEnabled = ! empty($loadingRoutes) && request()->route() && request()->routeIs(...$loadingRoutes);
        @endphp

        <script>
            window.silatarLoadingPageEnabled = {{ $loadingEnabled ? 'true' : 'false' }};
        </script>

        <div class="relative isolate overflow-hidden">
            <div class="pointer-events-none absolute inset-0 -z-10 opacity-70">
                <div class="absolute left-1/2 top-0 h-[34rem] w-[34rem] -translate-x-1/2 rounded-full bg-cyan-300/25 blur-3xl"></div>
                <div class="absolute bottom-0 right-0 h-[26rem] w-[26rem] rounded-full bg-sky-300/20 blur-3xl"></div>
            </div>

            <x-layouts.site-header />
            <x-ui.loading-indicator />

            {{ $slot }}
        </div>

        <?php
            $uiConfig = config('ui', []);
            $livewireConfig = [
                'csrf' => csrf_token(),
                'uri' => url(\Livewire\Livewire::getUpdateUri()),
                'moduleUrl' => url(\Livewire\Livewire::getUriPrefix()),
                'progressBar' => '',
                'nonce' => '',
            ];
        ?>
        <script>
            window.appUiConfig = {!! json_encode($uiConfig) !!};
            window.livewireScriptConfig = {!! json_encode($livewireConfig) !!};
        </script>
    </body>
</html>
