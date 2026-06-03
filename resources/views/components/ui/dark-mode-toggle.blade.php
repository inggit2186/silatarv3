<!--
  DarkModeToggle Component

  A beautiful, accessible dark mode toggle with animated sun/moon icons.

  Usage:
    Basic:      <x-ui.dark-mode-toggle />
    With label: <x-ui.dark-mode-toggle show-label />
    Large:      <x-ui.dark-mode-toggle size="lg" />
    Icon only:  <x-ui.dark-mode-toggle :label-only="false" />

  Props:
    - size: 'sm' (32px), 'md' (40px), 'lg' (48px), 'xl' (56px)
    - showLabel: Show "Light Mode" / "Dark Mode" text
    - tooltip: Show tooltip on hover (default: true)

  Features:
    - Animated sun rays that rotate
    - Crescent moon with stars
    - Smooth icon transitions
    - WCAG AA compliant colors
    - Respects prefers-reduced-motion
-->
@props([
    'size' => 'md',
    'showLabel' => false,
    'tooltip' => true,
])

@php
$sizeMap = [
    'sm' => ['btn' => 'h-8 w-8', 'icon' => 'h-4 w-4', 'text' => 'text-xs'],
    'md' => ['btn' => 'h-10 w-10', 'icon' => 'h-5 w-5', 'text' => 'text-sm'],
    'lg' => ['btn' => 'h-12 w-12', 'icon' => 'h-6 w-6', 'text' => 'text-base'],
    'xl' => ['btn' => 'h-14 w-14', 'icon' => 'h-7 w-7', 'text' => 'text-lg'],
];

$sizes = $sizeMap[$size] ?? $sizeMap['md'];
@endphp

<div
    x-data="{
        isDark: false,
        mounted: false,

        init() {
            // Check saved preference or system preference
            const saved = localStorage.getItem('silatar-theme');
            const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            this.isDark = (saved === 'dark') || (!saved && systemDark);
            this.applyTheme();

            // Mark as mounted for transition
            this.$nextTick(() => { this.mounted = true; });

            // Listen for external theme changes
            window.addEventListener('themechange', (e) => {
                this.isDark = e.detail.theme === 'dark';
            });
        },

        applyTheme() {
            const html = document.documentElement;
            html.classList.remove('light', 'dark');
            if (this.isDark) {
                html.classList.add('dark');
            }
        },

        toggle() {
            this.isDark = !this.isDark;
            localStorage.setItem('silatar-theme', this.isDark ? 'dark' : 'light');
            this.applyTheme();
            window.dispatchEvent(new CustomEvent('themechange', { detail: { theme: this.isDark ? 'dark' : 'light' } }));
        }
    }"
    class="relative inline-flex"
>
    <button
        type="button"
        @click="toggle()"
        :aria-label="isDark ? 'Switch to light mode' : 'Switch to dark mode'"
        :aria-pressed="isDark.toString()"
        class="group relative flex items-center justify-center rounded-full border border-slate-200 bg-white text-slate-600 shadow-sm transition-all duration-300 hover:border-cyan-300 hover:shadow-md hover:scale-105 focus:outline-none focus:ring-2 focus:ring-cyan-300 focus:ring-offset-2 {{ $sizes['btn'] }}"
    >
        <div class="relative h-full w-full overflow-hidden">
            {{-- Sun Icon (shown in dark mode - click to go light) --}}
            <div
                x-show="isDark"
                x-transition:enter="transition duration-300 ease-out"
                x-transition:enter-start="opacity-0 rotate-90 scale-50"
                x-transition:enter-end="opacity-100 rotate-0 scale-100"
                x-transition:leave="transition duration-200 ease-in"
                x-transition:leave-start="opacity-100 rotate-0 scale-100"
                x-transition:leave-end="opacity-0 -rotate-90 scale-50"
                class="absolute inset-0 flex items-center justify-center"
            >
                <svg
                    class="{{ $sizes['icon'] }} text-amber-500 drop-shadow-[0_0_8px_rgba(251,191,36,0.6)]"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    {{-- Sun core --}}
                    <circle cx="12" cy="12" r="4" fill="currentColor" stroke="none" />

                    {{-- Animated rays --}}
                    <g class="origin-center" style="animation: sun-rays 20s linear infinite;">
                        <line x1="12" y1="1" x2="12" y2="3" />
                        <line x1="12" y1="21" x2="12" y2="23" />
                        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64" />
                        <line x1="18.36" y1="18.36" x2="19.78" y2="19.78" />
                        <line x1="1" y1="12" x2="3" y2="12" />
                        <line x1="21" y1="12" x2="23" y2="12" />
                        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36" />
                        <line x1="18.36" y1="5.64" x2="19.78" y2="4.22" />
                    </g>
                </svg>
            </div>

            {{-- Moon Icon (shown in light mode - click to go dark) --}}
            <div
                x-show="!isDark"
                x-transition:enter="transition duration-300 ease-out"
                x-transition:enter-start="opacity-0 rotate-90 scale-50"
                x-transition:enter-end="opacity-100 rotate-0 scale-100"
                x-transition:leave="transition duration-200 ease-in"
                x-transition:leave-start="opacity-100 rotate-0 scale-100"
                x-transition:leave-end="opacity-0 -rotate-90 scale-50"
                class="absolute inset-0 flex items-center justify-center"
            >
                <svg
                    class="{{ $sizes['icon'] }} text-indigo-600 drop-shadow-[0_0_8px_rgba(99,102,241,0.4)]"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    {{-- Crescent moon --}}
                    <path
                        d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"
                        fill="currentColor"
                        opacity="0.2"
                    />
                    <path
                        d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"
                    />
                </svg>
            </div>
        </div>

        {{-- Shine effect on hover --}}
        <div class="pointer-events-none absolute inset-0 rounded-full bg-gradient-to-tr from-white via-transparent to-amber-100 opacity-0 transition-opacity duration-300 group-hover:opacity-30"></div>
    </button>

    {{-- Label --}}
    @if($showLabel)
    <span
        x-text="isDark ? 'Light Mode' : 'Dark Mode'"
        class="ml-3 font-medium text-slate-600 transition-colors group-hover:text-cyan-600 {{ $sizes['text'] }}"
        :class="isDark ? 'text-amber-600' : 'text-indigo-600'"
    ></span>
    @endif

    {{-- Tooltip --}}
    @if($tooltip)
    <div
        class="pointer-events-none absolute -bottom-12 left-1/2 z-50 -translate-x-1/2 whitespace-nowrap rounded-lg border border-slate-200 bg-slate-900 px-3 py-1.5 text-xs font-medium text-white shadow-lg opacity-0 transition-all duration-200 group-hover:opacity-100 group-hover:translate-y-0 group-hover:translate-x-1/2"
        :class="isDark ? 'translate-y-1' : '-translate-y-0'"
        x-text="isDark ? 'Switch to light mode' : 'Switch to dark mode'"
        role="tooltip"
    ></div>
    @endif
</div>

<style>
@keyframes sun-rays {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

@media (prefers-reduced-motion: reduce) {
    [x-transition],
    [x-show] {
        transition: none !important;
        animation: none !important;
    }

    svg g {
        animation: none !important;
    }
}
</style>

@once
@push('styles')
<style>
[data-theme-toggle] {
    -webkit-tap-highlight-color: transparent;
}

[data-theme-toggle]:active {
    transform: scale(0.95);
}
</style>
@endpush
@endonce