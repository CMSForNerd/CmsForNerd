---
okf_version: 0.1
type: documentation
title: "📐 Diagram Design Standards Skill Manual"
description: "Human-readable explanation of the diagram-design-standards skill, its 3-part specification (SVG, Mermaid, Routing Table), and AI agent visual generation workflows."
timestamp: 2026-08-01T09:00:00Z
topics: [diagrams, svg, mermaid, architecture, visual-design]
---

# 📐 Diagram Design Standards Skill Manual

## Architectural Context
The `diagram-design-standards` skill establishes a uniform visual and structural specification for technical diagrams across CmsForNerd and DSOM documentation. It provides Google Jules, Google Antigravity, and autonomous developer tools with strict design guidelines for producing production-ready vector graphics, git-native Mermaid diagrams, and summary interface routing tables.

## Problem Addressed
In complex CMS architectures and containerized deployments, visual diagrams are often inconsistent, lack network/system identifiers, or fail to render cleanly across different markdown viewers. When diagram or visual-documentation work is requested, this skill guarantees inline outputs consisting of:
1. **Raw Vector Graphics** inside an `xml` code fence for high-resolution presentation and rendering.
2. **Git-Native Mermaid Blocks** inside a `mermaid` code fence immediately following the SVG block for seamless inline rendering across Markdown viewers.

## Agent Operational Workflow
- **Invocation**: Triggered whenever the user requests system architecture diagrams, network flows, sequence interactions, or infrastructure visual specifications.
- **Canvas & Palette Hygiene**: Enforces explicit SVG `xmlns`, `viewBox`, slate background canvas (`#F8FAFC`), rounded container cards (`rx="8"` / `rx="10"`), and monospace typography for network identifiers.
- **Dual Diagram Generation**: Produces character-exact SVG vector blocks in `xml` code fences followed immediately by equivalent Mermaid diagrams in `mermaid` code fences.
- **Routing Table Synthesis**: Concludes with a 5-column Markdown comparison table mapping source, target, port/protocol, security boundaries, and operational descriptions.

---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-01*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
