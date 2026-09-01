---
okf_version: 0.1
type: documentation
title: "🛠️ Web Design Guidelines Skill Operational Guide"
description: "Human-readable operational manual for the web-design-guidelines agent skill, explaining usage, rule execution, guidelines fetching, and Playwright integration."
resource: "file:///docs/skills/WEB-DESIGN-GUIDELINES-SKILL.md"
timestamp: 2026-08-01T09:00:00Z
topics: [skill, web-design, agent-skills, testing, guidelines]
---

# 🛠️ Web Design Guidelines Skill Operational Guide

This document provides a human-readable operational overview of the **`web-design-guidelines`** agent skill deployed in CmsForNerd under `.agents/skills/web-design-guidelines/`.

---

## 🚀 Overview & Problem Solved
Evaluating web interfaces for WCAG accessibility compliance, keyboard focus visibility, typographic correctness, and responsive layout standards can be tedious when performed manually.

The `web-design-guidelines` skill automates design audits for developers and AI agents (such as Jules and Antigravity) by checking source files against predefined rules fetched live from the upstream Vercel Web Interface Guidelines repository.

---

## ⚙️ How the Skill Works

```
[ User Request ]
       │
       ▼
[ Fetch Live Guidelines ] ──► https://raw.githubusercontent.com/.../command.md
       │
       ▼
[ Parse Candidate UI Files ] ──► (HTML, PHP, TPL, CSS)
       │
       ▼
[ Rule Evaluation ] ──► Check Accessibility, Focus, Forms, Typography, Motion
       │
       ▼
[ Output Terse Findings ] ──► file:line format for rapid fixes
```

### 1. Dynamic Rule Fetching
Before conducting an audit, the skill fetches the latest guidelines from:
`https://raw.githubusercontent.com/vercel-labs/web-interface-guidelines/main/command.md`

### 2. File Pattern Scanning
Agents scan specified paths (e.g., `themes/CmsForNerd/`, `contents/`, `about.php`) or prompt the user for specific files to inspect.

### 3. Output Format
Findings are reported in concise `file:line` format to enable direct VS Code jumping and rapid remediation:

```text
## themes/CmsForNerd/header.tpl

themes/CmsForNerd/header.tpl:14 - icon button missing aria-label
themes/CmsForNerd/header.tpl:28 - input control lacks associated label
```

---

## 🧪 Integration with Playwright E2E Testing
To complement static code auditing with runtime verification, this skill interfaces with our Playwright E2E interactive test suite (`tests/playwright/e2e-interactive.spec.js`).

While `web-design-guidelines` performs static checks on HTML/PHP templates, Playwright verifies interactive behavior:
- Client-side router navigation without page reloads.
- Session cache persistence across page transitions.
- Interactive widget updates (e.g., live charts, counters).
- Accessibility keyboard focus traversal and ARIA attribute presence in the browser DOM.

---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-05*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
