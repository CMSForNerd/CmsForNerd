---
okf_version: 0.1
type: documentation
title: "The Agent Registry & DSOM Gateway"
description: "Sovereign entry point instructing AI Agents to look up rules and memory under .agents/."
resource: "file:///AGENTS.md"
timestamp: 2026-08-01T06:43:00Z
---
# AI Agent Registry & Sovereign Gateway

This file adds and describes agents or tools in our codebase, such as what they do, how to interact with them, or any input and output
conventions. Jules or any AI agents uses this file to better understand our code and generate more relevant plans and completions.

> **Entry Point:** This is the root-level gateway file for AI agents such as Google Jules, Cursor, and GitHub Copilot. The **full
> cognitive rulebook** and all spatial memory reside in `.agents/AGENTS.md` and `.agents/brain/`. You MUST navigate there immediately.

Welcome to the **Deep State of Mind (DSOM)** Sovereign AI Agent Workspace. If you are an AI assistant of any kind, you MUST read this
document and follow its instructions to align with this repository's architecture and the DSOM protocol.

---

## Critical Instructions for AI Agents

1. **Read the Full Rulebook:** Navigate immediately to [`.agents/AGENTS.md`](.agents/AGENTS.md). This is the **Cognitive Entry Point** —
   the Master Rulebook containing the absolute laws, operational persona, writing styles, and all 22+ detailed rules governing this
   project. The file you are reading now is a summary gateway only.

2. **Access Spatial Memory:** Extend your search to all brain files located in `.agents/brain/`. Read the following files to restore
   full operational context:
   - `task.md` — Active and completed task list (present state).
   - `walkthrough.md` — Session history and Mental Anchors (past state).
   - `palace_registry.md` — The Sovereign Markdown Palace spatial index.
   - `active_context_manifest.md` — Live list of files currently in scope.

3. **Execute the Mechanical Boot Sequence:** Upon initialization, the AI must orient itself in this strict order:
   1. **Genesis Read:** `.agents/AGENTS.md` (Establish identity and laws).
   2. **Memory Restoration:** `.agents/brain/` (Restore active task and session state).
   3. **Master Onboarding Map:** `START-HERE.md` (Understand global topology and entry points).

4. **Execute the 5-Step Knowledge-First Discovery Flow:** Before issuing any terminal command or probing external APIs, the AI must:
   1. Search OKF frontmatter (`topics:` / `description:`) in `.agents/brain/` and `docs/`.
   2. Target specific line ranges via `view_file`.
   3. Verify the OKF `timestamp` — if stale, compare with external sources and consult the human.
   4. Obtain human consensus if the local knowledge needs updating.
   5. Only then may the AI execute physical terminal commands.

5. **Synchronise & Maintain:** Keep both `AGENTS.md` files in sync — this root gateway and `.agents/AGENTS.md`. This ensures agents
   like Jules and teammates always have a consistent, up-to-date view of the project's operational state.

---

## Deep State of Mind (DSOM) Core Principles

The DSOM framework operates on digital sovereignty, structured metacognition, and Git-native operational management. Key principles
embedded in `.agents/AGENTS.md` include:

| Principle | Description |
|:---|:---|
| **Zero-Global / Spatial Memory** | No global state. Operational memory lives in `.agents/brain/`. |
| **Open Knowledge Format (OKF)** | All `.md` documents use OKF v0.1 YAML frontmatter (`okf_version`, `type`, `title`, `timestamp`). |
| **Atomic Git Commits** | Every logical action results in a discrete, semantically named Git commit. No monolithic commits. |
| **Omni-Documentation Sync** | New documents must be registered in `SUMMARY.md`, `mkdocs.yml`, `START-HERE.md`, and `llms.txt`. |
| **Sovereign Signatures** | Every markdown or readable script modified by an AI must be processed via `dsom-signature-injector`. |
| **Ansible Legacy** | Modular executors: `ansible-playbook`, `uv run`, `npm run`, or `pandoc` — governed by strict idempotency. |
| **Command-First Architecture** | Prose instructions are converted into exact, executable, byte-capped terminal invocations. |
| **Session End Consolidation**| Review past memories, git log, and details of this task to record the completed work in `.agents/brain/task.md` and `.agents/brain/walkthrough.md`, and then end the session with EOD. When staging and committing, limit staging to only files changed during the current session (avoid blanket `git add -A` or `git add .` style sweeps) and obtain explicit operator/user approval before executing any commit or push. Note that `git status` only reflects local state, not remote sync. |
| **Branching & PR Flow** | All edits occur on dedicated feature branches (`feat/*`, `fix/*`). Push branch and open GitHub PR (`gh pr create`), wait for automated bot reviews (e.g. CodeRabbit, SonarQube), fix suggestions locally, and re-push until all bot checks pass before merging to `master`/`main`. |
| **Cross-Platform Scripting** | No complex inline scripts in `composer.json`. Extract to `tools/` script files for OS-agnostic execution. |

---

## Key Files & Directories for AI Agents

| Path | Purpose |
|:---|:---|
| `.agents/AGENTS.md` | **Full Rulebook** — Core laws, persona, and all operational rules. |
| `.agents/brain/task.md` | Active task list for the current session. |
| `.agents/brain/walkthrough.md` | Session history and Mental Anchors (resume context). |
| `.agents/brain/palace_registry.md` | Spatial index of the Sovereign Markdown Palace. |
| `.agents/skills/` | OKF-compliant executable skill SOPs (e.g., `dsom-bootstrap`, `dsom-release-manager`). |
| `docs/governance/` | Theoretical blueprints, governance policies, and architectural guides. |
| `docs/governance/AI-INITIALIZATION-SEQUENCE.md` | The 5-step Mechanical Boot Sequence. |
| `docs/governance/SOP-KNOWLEDGE-FIRST-DISCOVERY.md`| The 5-step Knowledge-First Discovery Protocol. |
| START-HERE.md | Master onboarding map with 12 defined entry points. |
| llms.txt | AI Sitemap for external crawlers (NotebookLM, ChatGPT, etc.). |

---

## Active AI Agent Skills & Custom Tooling

To execute workflows autonomously and check compliance, the workspace provides programmatic tools and structured Agent Skills:

### Core DevOps Auditing and Testing

1. **Pre-flight Environmental Gateway (`tools/audit-pre-flight.sh`)**
   - *What it does:* Checks PHP 8.4/8.3 constraints, verifies lock files, and runs deep sanity checks for DSOM memory consistency.
   - *How to interact:* Execute via `bash tools/audit-pre-flight.sh` or through the automated `composer lab-check` suite.

2. **Compliance & Quality Check Suite (`composer lab-check`)**
   - *What it does:* Runs pre-flight checks, PHPStan Level 8 static analysis, style audits, Zero-Global checks, and Pest PHP tests.
   - *Input/Output:* Returns console report and exits with `0` on 100% compliance. Required before committing any major changes.

### Key AI Agent Skills (`.agents/skills/`)

- **Sovereign Signature Injector (`.agents/skills/dsom-signature-injector/`)**
  - *What it does:* Programmatically appends digital sovereignty headers, timestamps, and GPL v3.0 license info to modified files.
  - *How to interact:* Execute `uv run .agents/skills/dsom-signature-injector/scripts/inject.py <target_path>`.

- **Token Calculator Quality Gate (`.agents/skills/dsom-token-calculator/`)**
  - *What it does:* Enforces the strict 4,000-token limit for individual skill files (`SKILL.md`) to prevent context window bloat.
  - *How to interact:* Execute `uv run --with tiktoken .agents/skills/dsom-token-calculator/scripts/calculate-tokens.py .agents/skills/`.

- **End-of-Day (EOD) Palace Synchronization (`.agents/skills/eod-palace-sync/`)**
  - *What it does:* Externalises ephemeral conversational memory to walkthroughs, commits changes, and performs GitOps-safe rebasing.
  - *How to interact:* Follow instructions in `docs/EOD-RITUAL.md` or execute `bash tools/eod-palace.sh`.

---

> **Tip:** Keep both `AGENTS.md` files up to date — the root gateway and `.agents/AGENTS.md`. This helps Google Jules, Cursor, GitHub
> Copilot, other AI agents, and your human teammates work with this repository more effectively and in full alignment with the DSOM
> protocol.

---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-26*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
