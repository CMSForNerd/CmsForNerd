---
okf_version: 0.1
type: skill
title: "Web Interface Guidelines and Accessibility Standards"
name: "web-design-guidelines"
description: "Review UI code for Web Interface Guidelines compliance, accessibility standards, focus states, typography, animation, and UX best practices."
topics: [web-design, ui-ux, accessibility, focus-states, frontend]
timestamp: 2026-08-01T09:00:00Z
---

# 🎨 Web Interface Guidelines and Accessibility Standards

## Purpose
The `web-design-guidelines` skill automates the review of web interfaces to ensure adherence to UI design guidelines, accessibility standards (WCAG), responsive behavior, and UX principles. It provides developers and AI agents (such as Jules and Antigravity) with a fast and reliable framework to audit frontend code against predefined rules.

## When to use this skill
Use this skill whenever asked to "review my UI", "check accessibility", "audit design", "review UX", or "check my site against best practices".

## Guidelines Source & Rule Enforcement

### Fetching Fresh Guidelines
Before conducting a full UI review, agents fetch fresh rule specifications from the upstream web interface guidelines repository:
`https://raw.githubusercontent.com/vercel-labs/web-interface-guidelines/main/command.md`

### Core Rule Categories

1. **Accessibility (a11y)**
   - Icon-only buttons require an explicit `aria-label`.
   - Form controls require `<label>` or `aria-label`.
   - Interactive elements require keyboard event handlers (`onKeyDown`/`onKeyUp` or native button/anchor tags).
   - Use `<button>` for actions and `<a>`/`<Link>` for navigation (never non-semantic `<div onClick>`).
   - Images require meaningful `alt` text (or `alt=""` for decorative images).
   - Decorative icons require `aria-hidden="true"`.
   - Dynamic updates (toasts, validation warnings) require `aria-live="polite"`.
   - Heading structure must follow a logical `<h1>`–`<h6>` hierarchy and include skip navigation links.

2. **Focus States & Touch**
   - Interactive elements must maintain visible focus rings (`focus-visible:ring-*` or CSS `:focus-visible`).
   - Never disable focus rings using `outline: none` without providing an explicit replacement.
   - Touch targets must meet minimum size requirements with `touch-action: manipulation`.

3. **Forms & Input Control**
   - Inputs require valid `autocomplete` and descriptive `name` attributes.
   - Use correct input types (`email`, `tel`, `url`, `number`) and proper `inputmode`.
   - Never block paste operations (`onPaste` with `preventDefault`).
   - Form labels must be clickable (`for` / `htmlFor` association).

4. **Typography & Layout**
   - Use ellipsis (`…`) rather than triple dots (`...`).
   - Use curly quotes (`“` `”`) instead of straight quotes in content blocks.
   - Use `font-variant-numeric: tabular-nums` for numerical comparisons and data tables.
   - Apply `text-wrap: balance` or `text-pretty` on headings to eliminate typographic widows.

5. **Performance & Motion**
   - Honor `prefers-reduced-motion` CSS media queries for animations and transitions.
   - Animate compositor-friendly properties (`transform` and `opacity`) rather than `transition: all`.
   - Ensure images declare explicit `width` and `height` attributes to prevent Cumulative Layout Shift (CLS).

## Output Format Specification
Findings should be reported using terse `file:line` syntax for direct developer navigation:

```text
## themes/CmsForNerd/header.tpl

themes/CmsForNerd/header.tpl:14 - icon button missing aria-label
themes/CmsForNerd/header.tpl:22 - input lacks label association

## contents/index-body.inc

✓ pass
```

---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-05*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
