---
okf_version: 0.1
type: documentation
title: "🛠️ Diagram Design Standards Skill Operational Guide"
description: "Human-readable operational manual for the diagram-design-standards agent skill, detailing SVG canvas hygiene, Mermaid syntax, routing table structure, and automated testing."
resource: "file:///docs/skills/DIAGRAM-DESIGN-STANDARDS-SKILL.md"
timestamp: 2026-08-01T09:00:00Z
topics: [skill, diagrams, svg, mermaid, architecture]
---

# 🛠️ Diagram Design Standards Skill Operational Guide

This document provides a human-readable operational overview of the **`diagram-design-standards`** agent skill deployed under `.agents/skills/diagram-design-standards/` and `skills/diagram-design-standards/`.

---

## 🚀 Overview & Problem Solved
Technical documentation often suffers from inconsistent diagram styles, missing port or protocol callouts, or diagrams that render poorly across dark and light themes.

The `diagram-design-standards` skill equips developers and AI agents (such as Jules and Antigravity) with precise rules to generate self-contained, publication-grade diagrams using a standardized 3-part format.

---

## ⚙️ How the Skill Works

```
[ Diagram Request / Architectural Task ]
                   │
                   ▼
[ 1. Standalone Raw SVG Vector Graphic ] ──► xml code fence with explicit viewBox & palette
                   │
                   ▼
[ 2. Git-Native Mermaid Diagram ]       ──► mermaid code fence with subgraphs & HTML break tags
                   │
                   ▼
[ 3. Summary Interface Routing Table ]   ──► Markdown comparison table with ports & security zones
```

### 1. SVG Vector Graphic Requirements
- Code fence: `xml`
- Canvas attributes: `xmlns="http://www.w3.org/2000/svg"`, explicit `viewBox`, `width="100%"`, `height="100%"`
- Palette: Off-white canvas (`#F8FAFC` or `#FFFFFF`), rounded cards (`rx="8"` or `rx="10"`), header bands (`#EFF6FF`, `#F1F5F9`, `#DCFCE7`)
- Typography: Sans-serif font stack for titles/labels; Monospace (`Consolas`, `Monaco`) for IPs, CIDRs, file paths, and ports.

### 2. Git-Native Mermaid Diagram Requirements
- Code fence: `mermaid`
- Layout: `graph TD`, `graph LR`, or `sequenceDiagram`
- Grouping: `subgraph` blocks for security boundaries and VLANs
- Connector Labels: Explicit protocol/port annotations (e.g., `-->|"TCP 5432 / mTLS"|`)
- Line Wrapping: HTML `<br/>` tags inside node labels.

### 3. Summary Interface & Routing Table Requirements
A Markdown table with 5 standard columns:
1. `Source Component`
2. `Target Component`
3. `Port / Protocol / API Ingress`
4. `Security Boundary / Trust Zone / Access Key`
5. `Operational Significance / Flow Description`

---

## 🧪 Automated Testing & Verification
The skill contract is verified via automated PHPUnit tests in `tests/DiagramDesignStandardsSkillTest.php`. The test suite validates:
- Existence of `.agents/skills/diagram-design-standards/SKILL.md` and `skills/diagram-design-standards/SKILL.md`.
- OKF v0.1 frontmatter metadata compliance (`okf_version`, `type`, `title`, `name`, `description`, `topics`, `timestamp`).
- Presence of all required SVG attributes, Mermaid syntax directives, and routing table columns.
- Registration across omni-documentation layers (`SUMMARY.md`, `mkdocs.yml`, `START-HERE.md`, `llms.txt`, and `docs/AI-AGENT-SKILLS-GUIDE.md`).

---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-01*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
