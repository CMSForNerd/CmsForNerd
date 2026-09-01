---
okf_version: 0.1
type: documentation
title: "🤖 AI Agent Skills Development Guide"
description: "OKF-compliant documentation for AI-AGENT-SKILLS-GUIDE.md."
resource: "file:///docs/AI-AGENT-SKILLS-GUIDE.md"
timestamp: 2026-07-04T09:40:04Z
topics: [docs, skills, development, what, skill]
---
# 🤖 AI Agent Skills Development Guide

This document serves as the central registry and learning guide for creating **AI Agent Skills** within our project infrastructure.

By packaging our operational knowledge into "Skills," we allow any autonomous AI agent (like Antigravity, OpenHands, Cursor, etc.) to instantly learn and execute complex, project-specific workflows without needing human supervision or long chat histories.

---

## 🏗️ What is an Agent Skill?
We adhere to the [AgentSkills.io Standard](https://agentskills.io). At its core, an Agent Skill is simply a folder containing a `SKILL.md` file.

This file acts as a "memory module" that the AI agent loads on-demand when a user asks a related question. It provides the agent with exact instructions, scripts, or architectural knowledge required to solve a problem safely.

## 📝 How to Create a New Skill

All custom skills for this project MUST reside in the `.agents/skills/` directory.

### Step 1: Create the Directory
Create a new, aptly named folder for your workflow:
```bash
mkdir -p .agents/skills/my-custom-skill
```

### Step 2: Create the `SKILL.md` File
Create a `SKILL.md` file inside that folder. It **must** begin with YAML frontmatter containing the `name` and `description`.

*Note: The `description` is critical—the AI agent reads this description secretly in the background to decide if it should load the skill to help the user.*

```yaml
---
name: my-custom-skill
description: "A very clear, 1-2 sentence description explaining exactly when the AI should load this skill. (e.g., 'Use this skill when the user asks to deploy HAProxy...')"
---
# My Custom Skill Guide

## Purpose
Briefly explain what this achieves.

## Execution Steps
Provide clear, step-by-step markdown instructions.
1. Run command A.
2. Verify output B.
3. If error C happens, do D.
```

### Step 3: Test It
Once saved, simply ask your AI agent to perform the task described in the skill, and watch it flawlessly execute your codified logic!

---


## 🌐 Compatibility with OKF (Open Knowledge Format)
AgentSkills.io is a conceptual workflow standard (packaging scripts and logic inside a folder). It **does not conflict** with the Open Knowledge Format (OKF). Instead, they are completely complementary. OKF simply enforces that the `SKILL.md` file contains standard YAML metadata so the skill can be programmatically indexed.

## 🗃️ DSOM Agent Skills Registry

This is the active ledger of all AI Agent Skills currently deployed in this repository.

| Skill Name | Path | Purpose |
| :--- | :--- | :--- |
| **ASIMP and AI Agents Integration** | `.agents/skills/asimp-and-ai-integration/SKILL.md` | Integrates OS security compliance audits and YAML structures validation. |
| **Cross-Platform Translator** | `.agents/skills/cross-platform-translator/SKILL.md` | Translates Shell/PowerShell scripts cross-platform. |
| **DSOM Bootstrap** | `.agents/skills/dsom-bootstrap/SKILL.md` | Bootstraps DSOM architecture for new projects. |
| **DSOM Project Cloner** | `.agents/skills/dsom-project-cloner/SKILL.md` | Scaffolds a new DSOM project by copying baseline assets to a new target path. |
| **EOD Palace Sync** | `.agents/skills/eod-palace-sync/SKILL.md` | The Hibernation ritual to externalize memory into the Palace and push to Git. |
| **Forensic Log Audit** | `.agents/skills/forensic-log-audit/SKILL.md` | Parses raw AI conversation transcripts to extract exact historical terminal commands. |
| **Initialize GitOps** | `.agents/skills/initialize-gitops/SKILL.md` | Establishes the foundational GitOps repository and Genesis DSOM architecture. |
| **LaTeX Proposal Compiler** | `.agents/skills/latex-proposal-compiler/SKILL.md` | Compiles a markdown proposal document into a PDF using Pandoc and XeLaTeX. |
| **Node Proposal Formatter** | `.agents/skills/node-proposal-formatter/SKILL.md` | Formats markdown proposals using Node wrappers. |
| **Node Slide Generator** | `.agents/skills/node-slide-generator/SKILL.md` | Generates HTML/PDF slide decks from Markdown via Node/Marp. |
| **ODP Slide Generator** | `.agents/skills/odp-slide-generator/SKILL.md` | Generates LibreOffice Impress (ODP) presentations from Markdown. |
| **OKF Frontmatter Injector** | `.agents/skills/okf-frontmatter-injector/SKILL.md` | Scans a directory and automatically injects OKF v0.1 YAML frontmatter into Markdown files. |
| **Palace Auditor** | `.agents/skills/palace-auditor/SKILL.md` | Crawls the workspace to verify links and maintain the Sovereign Memory Palace. |
| **PDF Text Extractor** | `.agents/skills/pdf-text-extractor/SKILL.md` | Extracts raw text from PDF files for agent analysis. |
| **Persona Injector** | `.agents/skills/persona-injector/SKILL.md` | Guides a user to define their Sovereign Persona and injects it into the core AGENTS.md rulebook. |
| **Proposal DOCX Formatter** | `.agents/skills/proposal-docx-formatter/SKILL.md` | Formats markdown proposals into Microsoft Word (DOCX) files. |
| **Python Utility & Security** | `.agents/skills/python-utility-and-security/SKILL.md` | Restricts path traversal, prevents ReDoS, and validates Python structures. |
| **SOD Palace Sync** | `.agents/skills/sod-palace-sync/SKILL.md` | The Reanimation ritual to load the workspace context and establish the day's tasks. |
| **SSH Passwordless Setup** | `.agents/skills/ssh-passwordless-setup/SKILL.md` | Configures passwordless, multi-hop SSH routing using `~/.ssh/config` to bypass agent limits. |
| **Telemetry & Feedback Operations** | `.agents/skills/telemetry-and-feedback-ops/SKILL.md` | Manages dev-mode telemetry log compiling and formatted Markdown feedback. |
| **Web Interface Guidelines** | `.agents/skills/web-design-guidelines/SKILL.md` | Automated UI review, accessibility checks (WCAG), focus ring verification, and UX guidelines enforcement. |


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-04*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
