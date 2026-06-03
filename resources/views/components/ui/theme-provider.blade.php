<!--
  Theme Provider Component

  Wraps content with theme context and provides dark mode toggle functionality.

  Usage:
    <x-ui.theme-provider>
        <!-- Your content here -->
    </x-ui.theme-provider>

  Features:
    - Auto-detects system preference on first visit
    - Persists user preference in localStorage
    - Provides dark mode toggle button slot
    - Fires themechange event for reactive frameworks
-->
@props([
    'defaultTheme' => 'light', // 'light', 'dark', or 'system'
])

<div
    x-data="themeProvider()"
    x-init="init()"
    data-theme-provider
    {{ $attributes->class(['theme-transition' => true]) }}
>
    {{ $slot }}
</div>

@once
@push('scripts')
<script>
function themeProvider() {
    return {
        theme: 'light',
        toggleTimeout: null,

        init() {
            // Get saved preference or use default
            const saved = localStorage.getItem('silatar-theme');
            const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            if (saved && ['light', 'dark'].includes(saved)) {
                this.theme = saved;
            } else if ('{{ $defaultTheme }}' === 'dark' || ( '{{ $defaultTheme }}' === 'system' && systemDark)) {
                this.theme = 'dark';
            } else {
                this.theme = 'light';
            }

            this.applyTheme();

            // Listen for system preference changes
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                if (!localStorage.getItem('silatar-theme')) {
                    this.theme = e.matches ? 'dark' : 'light';
                    this.applyTheme();
                }
            });
        },

        applyTheme() {
            const html = document.documentElement;

            // Remove existing theme classes
            html.classList.remove('light', 'dark');

            // Apply new theme
            if (this.theme === 'dark') {
                html.classList.add('dark');
            }

            // Dispatch event for reactive updates
            window.dispatchEvent(new CustomEvent('themechange', { detail: { theme: this.theme } }));
        },

        toggle() {
            this.theme = this.theme === 'dark' ? 'light' : 'dark';
            localStorage.setItem('silatar-theme', this.theme);
            this.applyTheme();
        },

        setTheme(theme) {
            if (!['light', 'dark'].includes(theme)) return;
            this.theme = theme;
            localStorage.setItem('silatar-theme', theme);
            this.applyTheme();
        },

        isDark() {
            return this.theme === 'dark';
        }
    }
}
</script>
@endpush
@endonce