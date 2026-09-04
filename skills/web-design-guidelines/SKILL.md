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

# Web Interface Guidelines Audit Tool

Executes automated interface reviews across CMSForNerd frontend components.

## Execution Procedure
1. Load guidelines from `.agents/skills/web-design-guidelines/SKILL.md`.
2. Inspect template fragments, controllers, and styles.
3. Verify ARIA tags, focus indicators, form inputs, and image aspect ratios.
4. Output findings in `file:line` format.
