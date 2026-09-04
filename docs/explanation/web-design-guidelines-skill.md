---
okf_version: 0.1
type: documentation
title: "🎨 Web Design Guidelines Skill Manual"
description: "Human-readable explanation of the web-design-guidelines skill, its rules, accessibility standards, and Jules/Antigravity integration."
timestamp: 2026-08-01T09:00:00Z
topics: [web-design, ui-ux, accessibility, focus-states, frontend]
---

# 🎨 Web Design Guidelines Skill Manual

## Architectural Context
The `web-design-guidelines` skill establishes automated UI verification within CMSForNerd. Derived from Vercel's design guidelines, it provides Google Jules and Google Antigravity with a static auditing pipeline.

## Problem Addressed
Manual UI inspection frequently misses WCAG gaps, missing input autocomplete hints, or layout shift triggers. This skill continuously audits components during development, producing actionable `file:line` diagnostic logs.

## Agent Workflow
- **Invocation**: Triggered on request for UI review, accessibility checks, or design audits.
- **Rule Verification**: Checks input labels, button accessibility, image aspect ratios, and prefers-reduced-motion queries.
- **Digest Verification**: Ensures external rule updates match trusted hashes before execution.
