---
okf_version: 0.1
type: documentation
title: "🌐 Web Interface & Design Guidelines for CmsForNerd"
description: "Comprehensive Web Interface Guidelines, UX principles, accessibility (WCAG) standards, dark mode practices, and design audit roadmap for CmsForNerd."
resource: "file:///docs/WEB-DESIGN-GUIDELINES.md"
timestamp: 2026-08-01T09:00:00Z
topics: [web-design, accessibility, ui-ux, frontend, best-practices]
---

# 🌐 Web Interface & Design Guidelines for CmsForNerd

This document outlines the core UI/UX design standards, accessibility principles, and performance guidelines for the CmsForNerd web platform. Adherence to these standards ensures that our High-Fidelity Glassmorphism UI and Dual-View AMP views provide an inclusive, performant, and delightful user experience across all devices and screen reader environments.

---

## 📐 Core Design Standards & Principles

### 1. Accessibility (a11y) & Semantic HTML
Accessibility is foundational to CmsForNerd. All components must pass WCAG 2.1 AA standards.
- **Icon-Only Buttons:** Every icon-only button must carry an explicit `aria-label` attribute describing its function (e.g., `aria-label="Toggle navigation menu"`).
- **Form Controls:** All inputs, textareas, and select elements must be explicitly associated with a `<label>` via `for`/`id` or wrapped within a `<label>` tag with an `aria-label`.
- **Keyboard Navigation:** Interactive elements must be accessible via keyboard navigation. Use native `<button>` and `<a>` tags for clickable actions rather than non-semantic `<div onclick>`.
- **Media & Images:** All decorative icons must be marked with `aria-hidden="true"`. Content images must declare meaningful `alt` text.
- **Heading Hierarchy:** Headings (`<h1>` through `<h6>`) must strictly maintain hierarchical order without skipping levels.

### 2. Focus States & Touch Interaction
- **Focus Rings:** Interactive elements must preserve high-contrast, visible focus indicators. Use `:focus-visible` or Tailwind `focus-visible:ring-2` to avoid focus rings triggering on mouse clicks while ensuring full keyboard visibility.
- **Touch Targets:** Minimum touch target size for interactive controls is `44x44px` on mobile screens. Touch operations should specify `touch-action: manipulation` to prevent double-tap zoom delays.

### 3. Typography & Microcopy
- **Tabular Numbers:** All numerical tables, counters, and statistics must use `font-variant-numeric: tabular-nums` to ensure fixed digit widths during data updates.
- **Heading Balance:** Apply `text-wrap: balance` or `text-pretty` on main headings to prevent single-word orphan lines (widows).
- **Typographic Precision:** Use standard ellipses (`…`) rather than triple periods (`...`) and curly quotes (`“` `”`) in prose.

### 4. Performance & Motion Safety
- **Reduced Motion:** Respect `prefers-reduced-motion` CSS media queries by disabling non-essential CSS transitions or offering alternative static states.
- **Layout Stability (CLS):** Explicit `width` and `height` attributes must be present on `<img>` tags to reserve layout space and prevent Cumulative Layout Shift.
- **Compositor Animations:** Restrict CSS transitions to GPU-accelerated properties (`transform` and `opacity`) instead of using `transition: all`.

### 5. Dark Mode & High-Fidelity Glassmorphism
- **Color Scheme:** Declare `color-scheme: dark` on the `<html>` root for dark themes to align native browser controls and scrollbars.
- **Theme Color Meta:** Keep `<meta name="theme-color">` synchronized with page backgrounds to create seamless mobile address bar integration.

---

## 🔍 UI Code Audit & Improvement Roadmap

| Target Area | Current State | Improvement Action | Status |
| :--- | :--- | :--- | :--- |
| **Navigation Header** | Toggle button lacks aria label | Add `aria-label="Toggle main menu"` to header toggle | Implemented |
| **Footer Links** | Social/RSS icons missing label | Add `aria-label` and `aria-hidden="true"` to SVG icons | Implemented |
| **Data Tables** | Standard font variant | Enforce `tabular-nums` class on statistics tables | Implemented |
| **Form Fields** | Default focus ring | Enhance focus visibility with `focus-visible:ring-2` | Implemented |

---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-05*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
