---
okf_version: 0.1
type: documentation
title: "🎨 Web Design Guidelines Skill Manual"
description: "Human-readable explanation of the web-design-guidelines skill, its rules, accessibility standards, and Jules/Antigravity integration."
timestamp: 2026-08-01T09:00:00Z
topics: [web-design, ui-ux, accessibility, focus-states, frontend]
---

# 🎨 Web Design Guidelines Skill Manual

## Overview
The `web-design-guidelines` skill provides an automated framework for reviewing web interfaces against industry
standards, accessibility guidelines (WCAG 2.1 AA), responsive layout principles, and user experience (UX) best
practices. Originally inspired by Vercel's Web Interface Guidelines, this skill has been adopted and enhanced for
Google Jules and Google Antigravity within the CMSForNerd laboratory architecture.

## Purpose & Problem Solved
Evaluating user interfaces for accessibility, input semantics, focus management, typography, and motion safety is
often a manual, error-prone task. The `web-design-guidelines` skill automates design audits across HTML, PHP, CSS, and
JavaScript components, flagging non-compliant code using a concise `file:line` format for rapid remediation.

## How Jules and Antigravity Use This Skill
1. **Automated Code Reviews**: Executed when asked to "review UI", "check accessibility", "audit design", or "review UX".
2. **Pre-flight Verification**: Run prior to committing UI changes to ensure form controls, images, and buttons follow
   WCAG and CMSForNerd design standards.
3. **Untrusted Source Protection**: When fetching remote rule updates, agents pin and validate digests, preventing
   untrusted external rules from modifying core system behavior.

## Core Guideline Rules

### 1. Accessibility (a11y)
- **Buttons & Controls**: Icon buttons require explicit `aria-label` attributes.
- **Form Association**: Every `<input>`, `<select>`, and `<textarea>` must have an associated `<label>` or `aria-label`.
- **Semantic HTML**: Use `<button>` for actions and `<a>` for navigation. Avoid non-semantic `<div onClick>`.
- **Images**: Require descriptive `alt` text (or `alt=""` for decorative assets).
- **Headings**: Maintain hierarchical `<h1>` through `<h6>` structure.

### 2. Forms & Input Optimization
- **Input Attributes**: Inputs require valid `autocomplete` and `name` attributes.
- **Paste Preservation**: Never block user paste operations (`onPaste` with `preventDefault`).
- **Ellipsis Standard**: Placeholder text ending with ellipsis must use the unicode character (`…`), not triple dots (`...`).

### 3. Layout, Motion & Performance
- **Image Dimensions**: Every `<img>` tag must declare explicit `width` and `height` attributes to prevent CLS.
- **Focus States**: Never disable focus outlines (`outline: none`) without an explicit `:focus-visible` replacement.
- **Reduced Motion**: Animations and transitions must respect `prefers-reduced-motion` media queries.

---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-05*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
