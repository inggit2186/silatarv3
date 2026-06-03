# SILATAR Theme System

A comprehensive CSS custom property theming system supporting light and dark modes with WCAG AA compliant contrast ratios.

## Quick Start

### 1. Include the Theme CSS

Add to your main CSS import:

```css
@import 'themes.css';
/* or */
@import './resources/css/themes.css';
```

### 2. Add to Layout

In your main layout file (`resources/views/layouts/app.blade.php` or similar):

```html
<!DOCTYPE html>
<html lang="id" class="light"> <!-- Default to light mode -->
<head>
    <!-- ... -->
    <link rel="stylesheet" href="/css/themes.css">
</head>
<body>
    <!-- Your content -->

    <!-- Optional: Add dark mode toggle somewhere in your header -->
    <x-ui.dark-mode-toggle />
</body>
</html>
```

### 3. Toggle Dark Mode

Add the `dark` class to `<html>` for dark mode:

```html
<!-- Dark mode -->
<html class="dark">

<!-- Light mode (default) -->
<html class="light">
<!-- or just -->
<html>
```

## Theme Toggling

### JavaScript (Recommended)

```javascript
// Toggle dark mode
document.documentElement.classList.toggle('dark');

// Check if dark mode
document.documentElement.classList.contains('dark');

// Set specific theme
const html = document.documentElement;
html.classList.remove('light', 'dark');
html.classList.add('dark');
```

### Using the Toggle Component

```blade
{{-- Simple toggle button --}}
<x-ui.dark-mode-toggle />

{{-- Larger toggle with label --}}
<x-ui.dark-mode-toggle size="lg" show-label="true" />

{{-- Manual control with Alpine --}}
<button @click="$refs.themeBtn.click()">
    Toggle Theme
</button>
<x-ui.dark-mode-toggle x-ref="themeBtn" />
```

### Auto-Detection with Alpine

```blade
<div x-data="{ dark: window.matchMedia('(prefers-color-scheme: dark)').matches }">
    <button @click="dark = !dark; document.documentElement.classList.toggle('dark', dark)">
        Toggle
    </button>
</div>
```

## CSS Custom Properties Reference

### Semantic Color Tokens

| Property | Description | Light Value | Dark Value |
|----------|-------------|-------------|------------|
| `--text-primary` | Main text color | `slate-900` (15.3:1) | `slate-50` (14.1:1) |
| `--text-secondary` | Secondary text | `slate-600` (5.7:1) | `slate-300` (7.5:1) |
| `--text-muted` | Muted/placeholder | `slate-500` (4.6:1) | `slate-400` (4.7:1) |
| `--bg-primary` | Primary button bg | `cyan-500` | `cyan-500` |
| `--bg-secondary` | Secondary bg | `slate-100` | `slate-800` |
| `--surface-ground` | Main background | `#ffffff` | `slate-900` |
| `--surface-raised` | Elevated surfaces | `slate-50` | `slate-800` |
| `--border-default` | Default borders | `slate-200` (3.2:1) | `slate-700` (4.6:1) |

### Status Colors

| Property | Light BG | Light Text | Dark BG | Dark Text |
|----------|----------|------------|---------|-----------|
| `--status-success-*` | `green-50` | `green-900` (8.9:1) | Dark green | `green-100` (10.3:1) |
| `--status-warning-*` | `amber-50` | `orange-900` (7.3:1) | Dark orange | `amber-100` (9.1:1) |
| `--status-danger-*` | `rose-50` | `rose-900` (7.1:1) | Dark rose | `rose-100` (9.2:1) |
| `--status-info-*` | `blue-50` | `indigo-900` (8.5:1) | Dark blue | `blue-100` (10.4:1) |

### Shadow Tokens

| Property | Light | Dark |
|----------|-------|------|
| `--shadow-sm` | Light opacity | Darker opacity |
| `--shadow-md` | Default elevation | Darker elevation |
| `--shadow-lg` | High elevation | Darker + higher |
| `--shadow-xl` | Maximum elevation | Darker + maximum |

## Utility Classes

### Backgrounds

```html
<div class="bg-ground">Main content area</div>
<div class="bg-raised">Elevated card/surface</div>
<div class="bg-muted">Disabled/subtle areas</div>
<div class="bg-overlay">Overlays and modals</div>
<div class="bg-primary">Primary buttons</div>
```

### Text Colors

```html
<p class="text-primary">Main heading</p>
<p class="text-secondary">Subheading</p>
<p class="text-muted">Caption/hint</p>
<p class="text-inverse">White text on dark</p>
<a class="text-link">Clickable link</a>
```

### Borders

```html
<div class="border-default">Standard border</div>
<div class="border-hover">Hover state border</div>
<div class="border-subtle">Subtle dividers</div>
```

### Component Classes

```html
<!-- Card component -->
<div class="card">Card content</div>

<!-- Input component -->
<input class="input" placeholder="Enter text">

<!-- Buttons -->
<button class="btn">Primary</button>
<button class="btn-secondary">Secondary</button>

<!-- Navigation -->
<nav class="nav">Navigation items</nav>

<!-- Overlays -->
<div class="overlay">Modal backdrop</div>
<div class="modal">Modal content</div>
```

## Adding Custom Themes

### Via CSS Data Attribute

```css
[data-theme="ocean"] {
    /* Override primary colors */
    --color-primary-500: #0ea5e9;
    --color-primary-600: #0284c7;
    --accent-primary: #0ea5e9;
}
```

```html
<html data-theme="ocean">
```

### Via Class

```css
.theme-ocean {
    --color-primary-500: #0ea5e9;
    /* ... */
}
```

```html
<html class="theme-ocean">
```

### Full Custom Theme Example

```css
.theme-midnight {
    /* Deep blue theme */
    --color-primary-50: 239, 246, 255;
    --color-primary-100: 219, 234, 254;
    --color-primary-500: 59, 130, 246;
    --color-primary-600: 37, 99, 235;

    --surface-ground: 15, 23, 42;  /* slate-900 */
    --surface-raised: 30, 41, 59; /* slate-800 */
    --surface-muted: 51, 65, 85;  /* slate-700 */

    --text-primary: 248, 250, 252; /* slate-50 */
    --text-secondary: 203, 213, 225; /* slate-300 */
}
```

## WCAG Compliance

All color pairings in both light and dark themes meet WCAG AA requirements:

- **Text contrast**: Minimum 4.5:1 ratio
- **Large text**: Minimum 3:1 ratio
- **UI components**: Minimum 3:1 ratio

### High Contrast Mode

For enhanced accessibility, add `.theme-high-contrast` class:

```html
<html class="dark theme-high-contrast">
```

This provides:
- Pure black/white text in light mode
- Pure white/black text in dark mode
- Higher contrast borders
- Enhanced focus indicators

## System Preferences

### Respect `prefers-color-scheme`

The theme automatically respects the user's OS color scheme preference:

```css
@media (prefers-color-scheme: dark) {
    :root:not(.light) {
        /* Apply dark theme */
    }
}
```

### Respect `prefers-reduced-motion`

Animations are automatically reduced:

```css
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        transition-duration: 0.01ms !important;
    }
}
```

### Respect `prefers-contrast`

High contrast mode is auto-detected:

```css
@media (prefers-contrast: high) {
    /* Increase contrast across all themes */
}
```

## JavaScript API

### ThemeManager Class

```javascript
// Initialize
const tm = new ThemeManager({
    defaultTheme: 'light',
    persist: true,
    onThemeChange: (theme) => console.log(theme)
});

// Methods
tm.toggle();           // Switch between light/dark
tm.setTheme('dark');   // Set specific theme
tm.isDark();           // Returns boolean
tm.getCurrentTheme();  // Returns 'light' or 'dark'

// Events
document.addEventListener('themechange', (e) => {
    console.log(e.detail.theme);
});
```

### CSS Variables Access

```javascript
// Read current value
const primary = getComputedStyle(document.documentElement)
    .getPropertyValue('--accent-primary').trim();

// Set value dynamically
document.documentElement.style.setProperty('--accent-primary', '#ff0000');
```

## Tailwind Integration

### Using with Tailwind

The theme system is designed to work alongside Tailwind. Use semantic classes for theme-aware components:

```blade
{{-- Using Tailwind with theme colors --}}
<div class="bg-slate-900 dark:bg-slate-50">
    <p class="text-slate-50 dark:text-slate-900">Contrast maintained!</p>
</div>

{{-- Using semantic theme classes --}}
<div class="bg-ground">
    <p class="text-primary">Theme-aware text</p>
</div>
```

### Extending Tailwind Colors

```js
// tailwind.config.js
module.exports = {
    theme: {
        extend: {
            colors: {
                primary: {
                    50: 'rgb(var(--color-primary-50) / <alpha-value>)',
                    // ...
                }
            }
        }
    }
}
```

## Migration Guide

### From Inline Styles

Replace hardcoded colors:

```html
{{-- Before --}}
<div style="background-color: #ffffff; color: #0f172a;">

{{-- After --}}
<div class="bg-ground text-primary">
{{-- or --}}
<div style="background-color: rgb(var(--surface-ground)); color: rgb(var(--text-primary));">
```

### From Tailwind Color Classes

Replace Tailwind color classes with semantic equivalents:

| Tailwind | Semantic Class |
|----------|----------------|
| `bg-white` | `bg-ground` |
| `bg-slate-50` | `bg-raised` |
| `bg-slate-100` | `bg-muted` |
| `text-slate-900` | `text-primary` |
| `text-slate-600` | `text-secondary` |
| `border-slate-200` | `border-default` |

## Browser Support

- Chrome 88+
- Firefox 85+
- Safari 14+
- Edge 88+

CSS custom properties (variables) are supported in all modern browsers.