---
okf_version: 0.1
type: skill
title: "Web Interface Guidelines Definition"
name: "web-design-guidelines"
description: "Review UI code for Web Interface Guidelines compliance."
topics: [web-design, ui-ux, accessibility, focus-states, frontend]
timestamp: 2026-08-01T09:00:00Z
metadata:
  author: vercel
  version: "1.0.0"
argument-hint: Web Interface Guidelines
---

# Web Interface Guidelines

Review files for compliance with Web Interface Guidelines.

## How It Works
1. Fetch latest guidelines from https://raw.githubusercontent.com/vercel-labs/web-interface-guidelines/main/command.md or reference local standards in `.agents/skills/web-design-guidelines/SKILL.md`.
2. Read target UI files (HTML, TPL, PHP, JS, CSS).
3. Evaluate against Web Interface Guidelines rules (Accessibility, Focus States, Forms, Typography, Performance, Motion).
4. Output findings using terse `file:line` format.

## Rule Categories Summary

### Accessibility (a11y)
- Icon-only buttons require `aria-label`.
- Form controls require `<label>` or `aria-label`.
- Use `<button>` for actions and `<a>` for navigation.
- Images require `alt` text and explicit `width` and `height` attributes.

### Forms & Input Control
- Inputs require `autocomplete` and meaningful `name`.
- Never block paste operations.
- Use unicode ellipsis (`…`) in placeholders.

### Focus & Touch States
- Interactive elements need visible focus (`focus-visible:ring-*` or `:focus-visible`).
- Touch targets should specify `touch-action: manipulation`.

### Typography & Motion
- Use `tabular-nums` for numerical comparisons.
- Honor `prefers-reduced-motion` CSS media queries.
