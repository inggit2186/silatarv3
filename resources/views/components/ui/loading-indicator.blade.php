@php
    $loadingConfig = config('ui.loading', []);
@endphp

<div x-data="globalLoading(@js($loadingConfig))" x-init="init()" x-cloak class="pointer-events-none fixed inset-0 z-[90]">
    <div
        x-show="active"
        x-transition.opacity
        class="absolute inset-x-0 top-0"
    >
        <div class="silatar-global-loading-bar-track">
            <div class="silatar-global-loading-bar" :class="variantBarClass"></div>
        </div>
    </div>

    <div
        x-show="active"
        x-transition.opacity
        class="absolute right-4 top-4 hidden sm:block sm:right-6 sm:top-6"
    >
        <div class="silatar-global-loading-toast" :class="variantToastClass">
            <span class="silatar-global-loading-spinner" :class="variantSpinnerClass"></span>
            <div class="min-w-0">
                <p class="text-sm font-semibold text-slate-950" x-text="title"></p>
                <p class="text-xs text-slate-500" x-text="message"></p>
            </div>
        </div>
    </div>
</div>
