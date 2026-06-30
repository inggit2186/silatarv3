---
name: SILATAR V2 Design System
description: Government service portal for KEMENAG Tanjung Dinang with Modern Glass aesthetic
colors:
  primary: "#7c3aed"
  primary-light: "#8b5cf6"
  primary-dark: "#6d28d9"
  accent-cyan: "#0891b2"
  accent-cyan-light: "#06b6d4"
  success: "#10b981"
  warning: "#f59e0b"
  danger: "#ef4444"
  glass-bg: "rgba(255, 255, 255, 0.8)"
  glass-border: "rgba(255, 255, 255, 0.3)"
  surface-white: "#ffffff"
  surface-gray: "#f8fafc"
  surface-slate: "#f1f5f9"
  ink-primary: "#0f172a"
  ink-secondary: "#475569"
  ink-muted: "#94a3b8"
  glass-overlay: "rgba(255, 255, 255, 0.7)"
typography:
  display:
    fontFamily: "Instrument Sans, system-ui, sans-serif"
    fontSize: "3rem"
    fontWeight: 700
    lineHeight: 1.1
  headline:
    fontFamily: "Instrument Sans, system-ui, sans-serif"
    fontSize: "1.875rem"
    fontWeight: 600
    lineHeight: 1.2
  title:
    fontFamily: "Instrument Sans, system-ui, sans-serif"
    fontSize: "1.25rem"
    fontWeight: 600
    lineHeight: 1.3
  body:
    fontFamily: "Instrument Sans, system-ui, sans-serif"
    fontSize: "1rem"
    fontWeight: 400
    lineHeight: 1.6
  label:
    fontFamily: "Instrument Sans, system-ui, sans-serif"
    fontSize: "0.875rem"
    fontWeight: 500
    letterSpacing: "0.01em"
rounded:
  sm: "0.5rem"
  md: "0.75rem"
  lg: "1rem"
  xl: "1.5rem"
  "2xl": "2rem"
spacing:
  xs: "0.25rem"
  sm: "0.5rem"
  md: "1rem"
  lg: "1.5rem"
  xl: "2rem"
  "2xl": "3rem"
components:
  button-primary:
    backgroundColor: "{colors.primary}"
    textColor: "#ffffff"
    rounded: "{rounded.lg}"
    padding: "0.625rem 1.25rem"
  button-primary-hover:
    backgroundColor: "{colors.primary-dark}"
  button-secondary:
    backgroundColor: "transparent"
    textColor: "{colors.primary}"
    rounded: "{rounded.lg}"
    border: "1px solid {colors.primary}"
  button-ghost:
    backgroundColor: "transparent"
    textColor: "{colors.ink-secondary}"
  glass-card:
    backgroundColor: "{colors.glass-bg}"
    borderColor: "{colors.glass-border}"
    rounded: "{rounded.xl}"
    backdropFilter: "blur(12px)"
  input-field:
    backgroundColor: "#ffffff"
    borderColor: "#e2e8f0"
    rounded: "{rounded.lg}"
    textColor: "{colors.ink-primary}"
---

# Design System: SILATAR V2

## 1. Overview

**Creative North Star: "The Glass Pavilion"**

A government service portal that feels like a modern civic building — clean, trustworthy, and welcoming. The Glass Pavilion metaphor guides every decision: transparent surfaces that invite interaction, subtle depth that suggests competence, and warm accents that soften the formality of government. The design balances official authority with approachable accessibility.

This system serves two distinct surfaces with unified visual language:
- **Public Portal**: Indonesian citizens seeking government services — mobile-first, accessible, warm
- **Admin Portal**: Government employees managing requests — desktop-optimized, data-dense, efficient

The design explicitly rejects the cyberpunk aesthetic of the previous admin panel. Gone are the neon glows, grid lines, and glitch animations. In their place: soft glass surfaces, measured shadows, and professional restraint.

**Key Characteristics:**
- Soft glass surfaces with subtle backdrop blur
- Purple/violet as primary accent color
- Cyan as secondary accent for status and CTAs
- Generous whitespace and clear hierarchy
- Touch targets minimum 44px for accessibility
- Light backgrounds throughout (no dark mode for admin)

## 2. Colors

The palette combines government-appropriate restraint with modern glass aesthetics. Purple anchors the primary brand; cyan provides functional feedback; neutral scales handle text and surfaces.

### Primary
- **Violet Authority** (#7c3aed): Primary brand color for buttons, active states, and brand elements. Represents institutional trust with modern warmth.
- **Violet Light** (#8b5cf6): Hover states and lighter accents.
- **Violet Deep** (#6d28d9): Active states and emphasis.

### Secondary
- **Cyan Professional** (#0891b2): Secondary accent for status indicators, links, and functional CTAs. Provides contrast to violet in data visualization.
- **Cyan Light** (#06b6d4): Hover states for cyan elements.

### Semantic
- **Success Green** (#10b981): Positive status, completed states, confirmations.
- **Warning Amber** (#f59e0b): Pending states, caution indicators.
- **Danger Red** (#ef4444): Errors, rejections, destructive actions.

### Glass System
- **Glass Background** (rgba(255, 255, 255, 0.8)): Semi-transparent white for card surfaces.
- **Glass Border** (rgba(255, 255, 255, 0.3)): Subtle white borders on glass cards.
- **Glass Overlay** (rgba(255, 255, 255, 0.7)): Modal and dropdown backgrounds.

### Neutral
- **Surface White** (#ffffff): Primary content backgrounds.
- **Surface Gray** (#f8fafc): Alternate section backgrounds.
- **Surface Slate** (#f1f5f9): Tertiary surfaces, input backgrounds.
- **Ink Primary** (#0f172a): Primary text, headings.
- **Ink Secondary** (#475569): Body text, descriptions.
- **Ink Muted** (#94a3b8): Placeholder text, disabled states.

**The Glass Card Rule.** Glass surfaces use backdrop-filter: blur(12px) with rgba(255,255,255,0.8) backgrounds. Never use blur on content-heavy areas; reserve it for floating elements (cards, modals, dropdowns).

**The Purple Accent Rule.** Violet is the primary accent and should appear on ≤15% of any given screen. Its restraint is intentional — it marks important actions and brand moments, not decoration.

## 3. Typography

**Font Family:** Instrument Sans (with system-ui fallback)

Instrument Sans provides the professional, geometric clarity appropriate for government communications while remaining warm and approachable. It avoids the coldness of technical typefaces.

**Character:** Geometric precision with humanist warmth. The font reads as both modern and trustworthy — ideal for a government service portal that needs to feel both official and accessible.

### Hierarchy
- **Display** (700, 3rem, 1.1 line-height): Page titles and hero headings only. Used sparingly.
- **Headline** (600, 1.875rem, 1.2 line-height): Section headings, card titles.
- **Title** (600, 1.25rem, 1.3 line-height): Subsection headings, component titles.
- **Body** (400, 1rem, 1.6 line-height): General content. Max line length 65-75ch for readability.
- **Label** (500, 0.875rem, 0.01em tracking): Button text, form labels, badges.

**The Contrast Rule.** Never use Ink Secondary (#475569) for body text. Only Ink Primary (#0f172a) for readable content. Ink Secondary is reserved for supporting text and metadata.

**The Touch Target Rule.** Text interactive elements (links, buttons) must be at minimum 44px in height. Use padding to achieve this, not by reducing font size.

## 4. Elevation

The system uses **Shadow on Interaction** — surfaces are flat at rest, shadows appear only as a response to hover, focus, or elevation needs. This creates a clean, uncluttered feel while providing clear affordances when users need them.

### Shadow Vocabulary
- **Resting** (none): Cards and surfaces sit flat with subtle borders.
- **Hover Shadow** (0 4px 12px rgba(0,0,0,0.08)): Cards lift slightly on hover. 150ms transition.
- **Focus Ring** (0 0 0 3px rgba(124, 58, 237, 0.2)): Purple focus ring for keyboard navigation. Always present, never removed.
- **Modal Shadow** (0 25px 50px rgba(0,0,0,0.15)): Heavy shadow for overlaid elements.

### Glass Card Elevation
Glass cards use backdrop-filter blur in addition to subtle shadows:
- Background: rgba(255, 255, 255, 0.8)
- Border: 1px solid rgba(255, 255, 255, 0.3)
- Blur: backdrop-filter: blur(12px)
- Shadow: 0 4px 16px rgba(0,0,0,0.05)

**The Flat-By-Default Rule.** No shadows on initial render. Shadows appear only on interaction or when an element needs to float above others.

## 5. Components

### Buttons
- **Shape:** Large radius (0.75rem / 12px) for friendly, modern feel.
- **Primary:** Solid violet (#7c3aed) background, white text, full padding (0.625rem 1.25rem). Minimum height 44px.
- **Hover:** Darken to violet-deep (#6d28d9), subtle lift shadow.
- **Focus:** Purple focus ring (3px, 20% opacity).
- **Secondary:** Transparent background, violet border, violet text. Fills on hover.
- **Ghost:** No background or border. Subtle background on hover for click affordance.
- **Danger:** Red background (#ef4444) for destructive actions.

### Cards / Containers
- **Corner Style:** Extra large radius (1.5rem / 24px) for glass cards, large (1rem / 16px) for standard cards.
- **Background:** White or glass effect depending on context.
- **Border:** Subtle 1px border in rgba(255,255,255,0.3) for glass; #e2e8f0 for standard.
- **Internal Padding:** 1.5rem (24px) standard, 2rem (32px) for hero cards.
- **Hover State:** Subtle lift with shadow appearance.

### Inputs / Fields
- **Style:** White background, slate border (#e2e8f0), large radius (0.75rem).
- **Height:** Minimum 44px for touch accessibility.
- **Focus:** Violet border (#7c3aed), purple focus ring.
- **Error:** Red border (#ef4444), red focus ring, error message below.
- **Disabled:** Gray background (#f1f5f9), muted text.

### Navigation
- **Sidebar (Admin):** Fixed left, glass-effect background, violet active indicator.
- **Top Bar (Public):** Sticky, white background, subtle bottom border.
- **Mobile Nav:** Full-screen overlay with slide-in animation.

### Glass Card
- **Corner Style:** 1.5rem radius.
- **Background:** rgba(255, 255, 255, 0.8) with backdrop-filter: blur(12px).
- **Border:** 1px solid rgba(255, 255, 255, 0.3).
- **Shadow:** 0 4px 16px rgba(0,0,0,0.05).
- **Use When:** Floating above other content (modals, dropdowns, hover cards).

### Status Badges
- **Success:** Green background tint, green text.
- **Warning:** Amber background tint, amber text.
- **Error:** Red background tint, red text.
- **Info:** Cyan background tint, cyan text.
- **Shape:** Pill/rounded-full, small text (0.75rem).

## 6. Do's and Don'ts

### Do:
- **Do** use glass effects for floating elements (cards, modals, dropdowns) while maintaining solid backgrounds for main content areas.
- **Do** maintain minimum 44px touch targets for all interactive elements.
- **Do** use violet as the primary accent and cyan for functional feedback (links, status).
- **Do** keep shadows subtle — they enhance affordance, not create decoration.
- **Do** use generous whitespace to create clear hierarchy and visual breathing room.
- **Do** ensure 4.5:1 contrast ratio minimum for body text against backgrounds.
- **Do** use the Instrument Sans font family consistently throughout.

### Don't:
- **Don't** use cyberpunk aesthetic — neon colors, grid backgrounds, glowing borders, glitch animations.
- **Don't** use dark backgrounds for the admin panel — light mode is the standard.
- **Don't** use glassmorphism on content-heavy areas — only on floating/focus elements.
- **Don't** use gradient text for headings or body.
- **Don't** use border-left or border-right as colored accent stripes on cards.
- **Don't** use muted gray (#94a3b8) for body text — reserve for placeholders and disabled states.
- **Don't** use small text below 0.875rem for interactive labels.
- **Don't** create nested cards — if you need to nest, reconsider the layout.
- **Don't** use heavy shadows (deep blacks, large blurs) — keep elevation subtle.
- **Don't** add decorative motion or animations beyond functional state transitions.

<!-- DESIGN.md v2.0 — Captures SILATAR V2's Modern Glass aesthetic -->
