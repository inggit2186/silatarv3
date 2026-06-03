/**
 * SILATAR Theme Manager
 *
 * A lightweight JavaScript utility for managing theme state.
 * Works alongside the CSS custom property theming system.
 *
 * Features:
 * - Automatic dark mode detection via prefers-color-scheme
 * - Persistent theme preference via localStorage
 * - Smooth theme transitions
 * - System preference sync
 * - Theme change events
 */

class ThemeManager {
    constructor(options = {}) {
        this.options = {
            defaultTheme: 'light',
            persist: true,
            onThemeChange: null,
            ...options
        };

        this.storageKey = 'silatar-theme';
        this.transitionClass = 'theme-transition';
        this.transitionDuration = 200; // ms

        this.init();
    }

    /**
     * Initialize the theme manager
     */
    init() {
        // Get initial theme
        const savedTheme = this.getSavedTheme();
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const initialTheme = savedTheme || (systemPrefersDark ? 'dark' : this.options.defaultTheme);

        // Apply initial theme
        this.applyTheme(initialTheme, false);

        // Listen for system preference changes
        this.listenForSystemPreference();

        // Apply transition class after initial load
        requestAnimationFrame(() => {
            document.body.classList.add(this.transitionClass);
        });

        // Prevent flash by adding no-transitions during page load
        this.handleLoadTransition();
    }

    /**
     * Get saved prefers-dark from localStorage
     */
    getSavedTheme() {
        if (!this.options.persist) return null;

        try {
            const saved = localStorage.getItem(this.storageKey);
            if (saved && ['light', 'dark'].includes(saved)) {
                return saved;
            }
        } catch (e) {
            // localStorage may be blocked
            console.warn('ThemeManager: localStorage not available');
        }
        return null;
    }

    /**
     * Save theme preference to localStorage
     */
    saveTheme(theme) {
        if (!this.options.persist) return;

        try {
            localStorage.setItem(this.storageKey, theme);
        } catch (e) {
            console.warn('ThemeManager: Could not save theme preference');
        }
    }

    /**
     * Apply theme to the document
     */
    applyTheme(theme, save = true) {
        const html = document.documentElement;

        // Remove all theme classes
        html.classList.remove('light', 'dark');

        // Apply the theme
        if (theme === 'dark') {
            html.classList.add('dark');
        }
        // 'light' theme = no class needed (default)

        // Save preference
        if (save) {
            this.saveTheme(theme);
        }

        // Dispatch event
        this.dispatchThemeEvent(theme);

        // Call callback
        if (typeof this.options.onThemeChange === 'function') {
            this.options.onThemeChange(theme);
        }
    }

    /**
     * Toggle between light and dark themes
     */
    toggle() {
        const newTheme = this.isDark() ? 'light' : 'dark';
        this.applyTheme(newTheme);
        return newTheme;
    }

    /**
     * Set specific theme
     */
    setTheme(theme) {
        if (!['light', 'dark'].includes(theme)) {
            console.warn(`ThemeManager: Invalid theme "${theme}". Valid options: light, dark`);
            return;
        }
        this.applyTheme(theme);
    }

    /**
     * Check if dark theme is active
     */
    isDark() {
        return document.documentElement.classList.contains('dark');
    }

    /**
     * Get current theme
     */
    getCurrentTheme() {
        return this.isDark() ? 'dark' : 'light';
    }

    /**
     * Listen for system preference changes
     */
    listenForSystemPreference() {
        const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');

        mediaQuery.addEventListener('change', (e) => {
            // Only auto-switch if user hasn't set a preference
            if (!this.getSavedTheme()) {
                this.applyTheme(e.matches ? 'dark' : 'light', false);
            }
        });
    }

    /**
     * Dispatch custom event when theme changes
     */
    dispatchThemeEvent(theme) {
        const event = new CustomEvent('themechange', {
            detail: { theme }
        });
        document.dispatchEvent(event);
    }

    /**
     * Handle page load transitions (prevent flash)
     */
    handleLoadTransition() {
        // Remove no-transitions after fonts and critical resources load
        window.addEventListener('load', () => {
            // Small delay to ensure all styles are applied
            requestAnimationFrame(() => {
                document.body.classList.remove('no-transitions');
            });
        });
    }

    /**
     * Enable smooth transitions (call before theme change)
     */
    enableTransitions() {
        document.body.classList.add(this.transitionClass);
    }

    /**
     * Disable smooth transitions
     */
    disableTransitions() {
        document.body.classList.remove(this.transitionClass);
    }
}

/**
 * Dark Mode Toggle Component
 * Creates a accessible toggle button for theme switching
 */
class DarkModeToggle {
    constructor(button, themeManager) {
        this.button = button;
        this.themeManager = themeManager;
        this.isInitialized = false;

        this.init();
    }

    init() {
        if (!this.button) return;

        // Get or create icon containers
        this.lightIcon = this.button.querySelector('[data-theme-icon="light"]');
        this.darkIcon = this.button.querySelector('[data-theme-icon="dark"]');

        // Set initial state
        this.updateIcons();

        // Listen for theme changes
        document.addEventListener('themechange', () => this.updateIcons());

        // Click handler
        this.button.addEventListener('click', () => {
            this.themeManager.toggle();
        });

        // Keyboard accessibility
        this.button.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.themeManager.toggle();
            }
        });

        this.isInitialized = true;
    }

    updateIcons() {
        const isDark = this.themeManager.isDark();

        // Update button aria-label
        this.button.setAttribute('aria-label', isDark ? 'Switch to light mode' : 'Switch to dark mode');
        this.button.setAttribute('aria-pressed', isDark.toString());

        // Toggle icon visibility
        if (this.lightIcon && this.darkIcon) {
            this.lightIcon.style.display = isDark ? 'none' : 'block';
            this.darkIcon.style.display = isDark ? 'block' : 'none';
        }
    }
}

/**
 * Initialize theme system
 * Usage: Add this to your main JS or use via import
 */
function initThemeSystem(options = {}) {
    // Create global theme manager instance
    window.themeManager = new ThemeManager(options);

    // Initialize toggles
    document.querySelectorAll('[data-theme-toggle]').forEach(button => {
        new DarkModeToggle(button, window.themeManager);
    });

    return window.themeManager;
}

// Auto-initialize if DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => initThemeSystem());
} else {
    initThemeSystem();
}

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { ThemeManager, DarkModeToggle, initThemeSystem };
}
