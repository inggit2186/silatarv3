<!--
  DarkModeToggle - Standalone Version

  Copy-paste ready version with self-contained Alpine.js logic.
  No external dependencies required.

  Usage:
    <x-ui.dark-mode-toggle-standalone />

  Options:
    - data-size: sm | md | lg | xl (default: md)
    - data-label: Show text label (default: false)
    - data-tooltip: Show tooltip (default: true)
-->
<div
    x-data="{
        isDark: localStorage.getItem('silatar-theme') === 'dark' ||
                (!localStorage.getItem('silatar-theme') && window.matchMedia('(prefers-color-scheme: dark)').matches),

        init() {
            this.applyTheme();
        },

        applyTheme() {
            document.documentElement.classList.remove('light', 'dark');
            if (this.isDark) {
                document.documentElement.classList.add('dark');
            }
        },

        toggle() {
            this.isDark = !this.isDark;
            localStorage.setItem('silatar-theme', this.isDark ? 'dark' : 'light');
            this.applyTheme();
        }
    }"
    x-init="init()"
    @themechange.window="isDark = $event.detail.theme === 'dark'"
    {{ $attributes->only(['class']) }}
>
    <button
        type="button"
        @click="toggle()"
        :aria-label="isDark ? 'Aktifkan mode terang' : 'Aktifkan mode gelap'"
        :aria-pressed="isDark.toString()"
        class="relative inline-flex items-center justify-center rounded-full border border-slate-200 bg-white text-slate-600 shadow-sm transition-all duration-300 hover:border-cyan-300 hover:shadow-md hover:scale-105 focus:outline-none focus:ring-2 focus:ring-cyan-300 focus:ring-offset-2"
        :class="{
            'h-8 w-8': '{{ $attributes->get('data-size', 'md') }}' === 'sm',
            'h-10 w-10': '{{ $attributes->get('data-size', 'md') }}' === 'md' || !'{{ $attributes->get('data-size') }}',
            'h-12 w-12': '{{ $attributes->get('data-size') }}' === 'lg',
            'h-14 w-14': '{{ $attributes->get('data-size') }}' === 'xl',
        }"
    >
        <span class="relative h-5 w-5 overflow-hidden">
            {{-- Sun Icon --}}
            <svg
                x-show="isDark"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 rotate-90 scale-50"
                x-transition:enter-end="opacity-100 rotate-0 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 rotate-0 scale-100"
                x-transition:leave-end="opacity-0 -rotate-90 scale-50"
                class="absolute inset-0 text-amber-500 drop-shadow-[0_0_6px_rgba(251,191,36,0.5)]"
                viewBox="0 0 24 24"
                fill="currentColor"
            >
                <path d="M12 2.25a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zM7.5 12a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM18.894 6.166a.75.75 0 00-1.06-1.06l-1.591 1.59a.75.75 0 101.06 1.061l1.591-1.59zM21.75 12a.75.75 0 01-.75.75h-2.25a.75.75 0 010-1.5H21a.75.75 0 01.75.75zM17.834 18.894a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 10-1.061 1.06l1.59 1.591zM12 18a.75.75 0 01.75.75V21a.75.75 0 01-1.5 0v-2.25A.75.75 0 0112 18zM7.758 17.303a.75.75 0 00-1.061-1.06l-1.591 1.59a.75.75 0 001.06 1.061l1.591-1.59zM6 12a.75.75 0 01-.75.75H3a.75.75 0 010-1.5h2.25A.75.75 0 016 12zM6.697 7.757a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 00-1.061 1.06l1.59 1.591z" />
            </svg>

            {{-- Moon Icon --}}
            <svg
                x-show="!isDark"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 rotate-90 scale-50"
                x-transition:enter-end="opacity-100 rotate-0 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 rotate-0 scale-100"
                x-transition:leave-end="opacity-0 -rotate-90 scale-50"
                class="absolute inset-0 text-indigo-600 drop-shadow-[0_0_6px_rgba(99,102,241,0.4)]"
                viewBox="0 0 24 24"
                fill="currentColor"
            >
                <path fill-rule="evenodd" d="M9.528 1.718a.75.75 0 01.162.819A8.97 8.97 0 009 6a9 9 0 009 9 8.97 8.97 0 003.463-.69.75.75 0 01.981.98 10.503 10.503 0 01-9.694 6.46c-5.799 0-10.5-4.701-10.5-10.5 0-4.368 2.667-8.112 6.46-9.694a.75.75 0 01.818.162z" clip-rule="evenodd" />
            </svg>
        </span>

        {{-- Hover shine effect --}}
        <span class="pointer-events-none absolute inset-0 rounded-full bg-gradient-to-tr from-white via-transparent to-amber-100 opacity-0 transition-opacity duration-300 group-hover:opacity-30"></span>
    </button>

    {{-- Label --}}
    @if($attributes->get('data-label'))
    <span class="ml-2 text-sm font-medium text-slate-600" :class="isDark ? 'text-amber-600' : 'text-indigo-600'">
        <span x-text="isDark ? 'Mode Terang' : 'Mode Gelap'"></span>
    </span>
    @endif
</div>

@once
@push('styles')
<style>
.dark-mode-toggle button:active {
    transform: scale(0.95);
}
</style>
@endpush
@endonce