---
okf_version: 0.1
type: skill
title: "Diagram Design Standards Definition"
name: "diagram-design-standards"
description: "Guidelines and visual design specifications for generating standalone production-ready SVG vector graphics, Git-native Mermaid diagrams, and summary routing tables."
topics: [diagrams, svg, mermaid, architecture, visual-design]
timestamp: 2026-08-01T09:00:00Z
metadata:
  author: dsom
  version: "1.0.0"
argument-hint: Diagram Design Standards
---

# Diagram Design Standards Tool

Executes visual and architectural diagram generation according to DSOM standards.

## Execution Procedure
1. Load guidelines from `.agents/skills/diagram-design-standards/SKILL.md`.
2. Generate standalone raw SVG vector graphic inside an `xml` code fence with slate canvas styling.
3. Generate Git-native Mermaid diagram inside a `mermaid` code fence directly beneath the SVG block.
4. Output 5-column Summary Interface Routing Table mapping network flows and security zones.
