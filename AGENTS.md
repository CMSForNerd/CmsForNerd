---
okf_version: 0.1
type: documentation
title: "The Agent Registry & DSOM Gateway"
description: "Sovereign entry point instructing AI Agents to look up rules and memory under .agents/."
resource: "file:///AGENTS.md"
timestamp: 2026-08-01T09:00:00Z
topics: [gateway, registry, rulebook, dsom, agent]
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
| **Open Knowledge Format (OKF)** | All `.md` documents use OKF v0.1 YAML frontmatter (`okf_version`, `type`, `title`, `timestamp`, `topics`). |
| **Atomic Git Commits** | Every logical action results in a discrete, semantically named Git commit. No monolithic commits. |
| **Omni-Documentation Sync** | New documents must be registered in `SUMMARY.md`, `mkdocs.yml`, `START-HERE.md`, and `llms.txt`. |
| **Sovereign Signatures** | Every markdown or readable script modified by an AI must be processed via `dsom-signature-injector`. |
| **Ansible Legacy** | Modular executors: `ansible-playbook`, `uv run`, `npm run`, or `pandoc` — governed by strict idempotency. |
| **Command-First Architecture** | Prose instructions are converted into exact, executable, byte-capped terminal invocations. |
| **Session End Consolidation**| Review past memories, git log, and details of this task to record the completed work in `.agents/brain/task.md` and `.agents/brain/walkthrough.md`, and then end the session with EOD. When staging and committing, limit staging to only files changed during the current session (avoid blanket `git add -A` or `git add .` style sweeps) and obtain explicit operator/user approval before executing any commit or push. Note that `git status` only reflects local state, not remote sync. |
| **Branching & PR Flow** | All edits occur on dedicated feature branches (`feat/*`, `fix/*`). Push branch, open PR (`gh pr create`), fix bot suggestions, merge to `master`/`main`, and prune feature branches so only `master`/`main` remains. |
| **Cross-Platform Scripting** | No complex inline scripts in `composer.json`. Extract to `tools/` script files for OS-agnostic execution. |
| **Agent Sandbox Parity** | Use `git fetch` + `git rebase` instead of `git pull`, restore shallow clones with `git fetch --unshallow`, and guard `grep -c` with `\|\| true` under `set -e`. |

---

## Open Knowledge Format (OKF) Integration Details

This repository strictly implements the **Open Knowledge Format (OKF) v0.1** specification. OKF is a human- and agent-friendly format for representing knowledge: metadata, context, and curated insights.

All Markdown documents in this repository MUST contain YAML frontmatter with the following five required fields to pass continuous integration and compliance audits:

1. **`okf_version`**: The version of the OKF specification (set to `0.1`).
2. **`type`**: The kind of concept or category of document (e.g., `documentation`, `agent_skill`, `architecture_concept`, `automation_tool`, `infrastructure_playbook`, `governance_protocol`).
3. **`title`**: The display name of the document.
4. **`timestamp`**: The standard ISO 8601 UTC timestamp marking when the content was last updated (e.g., `2026-08-01T08:30:00Z`).
5. **`topics`**: A YAML array of 3 to 5 lowercase keyword strings representing critical tags (e.g., `[okf, frontmatter, yaml, compliance]`). This enables near-instant semantic routing for agent discovery without parsing full document bodies.

### Compliant YAML Frontmatter Example

```yaml
---
okf_version: 0.1
type: documentation
title: "Sovereign Rulebook"
description: "The core regulatory system governing AI agents."
resource: "file:///AGENTS.md"
timestamp: 2026-08-01T08:30:00Z
topics: [gateway, rulebook, compliance]
---
```

### Purpose and Consumption Flow

- **Discovery**: AI agents or platform engines scan file headers for the `topics` array to index documents without reading complete file bodies.
- **Trust Tiers & Staleness**: Metadata such as the `timestamp` allows agents to evaluate knowledge freshness and trigger alerts if documentation becomes outdated.
- **Automation**: The `okf-frontmatter-injector` skill automatically scans files and appends compliant OKF headers if any fields are missing.

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

- **Python Utility & Security (`.agents/skills/python-utility-and-security/`)**
  - *What it does:* Enforces path traversal boundaries (CWE-22) using `os.path.abspath`, blocks ReDoS via regex-free parsing, prevents insecure protocol triggers, and tests Google-style docstrings.
  - *How to interact:* Utilize when writing or validating any repository Python scripts.

- **ASIMP and AI Agents Integration (`.agents/skills/asimp-and-ai-integration/`)**
  - *What it does:* Directs automated OS-level security compliance audits via Lynis and OpenSCAP, simulates unprivileged mock auditing via `tools/mock-asimp.sh`, and validates YAML structures.
  - *How to interact:* Load on-demand for infrastructure compliance checks or automated YAML tests.

- **Telemetry and Bidirectional Feedback (`.agents/skills/telemetry-and-feedback-ops/`)**
  - *What it does:* Manages the local telemetry loop in `dev` execution mode to compile and dispatch formatted Markdown feedback reports back to Google Jules VM and active pull requests.
  - *How to interact:* Executed dynamically in dev mode or as part of WSL2 multi-distro matrix workflows.

- **Web Interface Guidelines & Accessibility (`.agents/skills/web-design-guidelines/`)**
  - *What it does:* Conducts UI reviews, WCAG accessibility compliance audits, focus states verification, Glassmorphic CSS consolidation, and Playwright E2E testing.
  - *How to interact:* Load when auditing UI designs or reviewing Playwright frontend tests in `tests/playwright/`.

- **Ansible & Podman Infrastructure Operations (`.agents/skills/ansible-and-podman-ops/`)**
  - *What it does:* Orchestrates rootless Podman 5+ containers on Ubuntu 26.04, Cloud Workstations, Render blueprints, BunkerWeb SSL, and Ansible playbooks.
  - *How to interact:* Use when writing playbooks, configuring Podman containers, or editing Render deployment manifests.

- **CMS Security & Architectural Hardening (`.agents/skills/cms-security-and-best-practices/`)**
  - *What it does:* Enforces Host Header injection defense via `SecurityUtils::getSafeBaseUrl()`, CSP nonces, OWASP Top 10, Zero-Global design, and HTML Microdata.
  - *How to interact:* Utilize when modifying security utilities, session handling, or controller access controls.

- **PHP Code Quality, SonarCloud & PHPStan (`.agents/skills/php-quality-sonar-phpstan/`)**
  - *What it does:* Enforces PHPStan Level 8 SAST, SonarCloud quality gates, Pest PHP testing, PSR-12 docblock ordering, and indentation standards.
  - *How to interact:* Run during code reviews or static analysis compliance checks.

- **PHP Performance & Benchmarking (`.agents/skills/php-performance-and-benchmarking/`)**
  - *What it does:* Implements `getSourceMaxMTime()` hybrid APCu/JSON caching, SHA-256 cache keys, O(1) array lookups, and `tools/bench_is_bot.php` benchmarking.
  - *How to interact:* Execute when optimizing PHP I/O, loops, or caching routines.

- **Static Page Baking & Routing (`.agents/skills/static-baking-and-routing/`)**
  - *What it does:* Bakes dynamic PHP pages to `build_static/` with `.nojekyll`, copies SEO/LLM files (`llms.txt`, `sitemap.xml`), and handles SPA/PWA router integration.
  - *How to interact:* Execute when baking static releases or deploying to GitHub Pages.

- **CMS Documentation & Educational Alignment (`.agents/skills/cms-documentation-and-education/`)**
  - *What it does:* Enforces 4-layer Omni-Documentation Sync, Diátaxis framework structuring (`user-manual.php`), `/llms.txt` standards, and version alignment.
  - *How to interact:* Execute when creating technical documentation, tutorials, or user manuals.

- **Sovereign Git Operations & Incremental Workflow (`.agents/skills/sovereign-git-and-workflow/`)**
  - *What it does:* Manages divergent branch merges, shallow clone restoration, Rule 24 session recording in `task.md`/`walkthrough.md`, and clean submission checkpoints.
  - *How to interact:* Follow during Git branch operations, merge conflict resolutions, and EOD rituals.

- **Bot Detection & Network Operations (`.agents/skills/bot-detection-and-network-ops/`)**
  - *What it does:* Performs dynamic IP CIDR bot detection via `curl_multi`, `is_trusted_bot_ip()` static caching, SSRF prevention, and secure cURL handling.
  - *How to interact:* Use when modifying bot detectors or external network fetch utilities.

### Google Antigravity & Google Jules Skill Integration Protocol

Google Jules and Google Antigravity share a unified Agent Skill architecture under `.agents/skills/`. This bridges Google Jules' autonomous task execution with Google Antigravity's cognitive skill discovery engine:

1. **Antigravity & AgentSkills.io Specification Compatibility:** Each skill directory under `.agents/skills/` contains a `SKILL.md` file featuring combined Open Knowledge Format (OKF v0.1) and Google Antigravity YAML frontmatter (`okf_version`, `type`, `title`, `name`, `description`, `topics`, `timestamp`).
2. **Knowledge Interoperability:** All Google Jules operational and domain-specific knowledge—spanning Ansible rootless Podman deployment, ASIMP security auditing, telemetry feedback loops, PHP 8.4 performance benchmarking, SonarCloud/PHPStan quality gates, static page baking, and web design guidelines—is encapsulated as modular skills in `.agents/skills/`.
3. **Execution & Context Window Protection:** Skills adhere to a strict 4,000-token limit per `SKILL.md` file, verified by `.agents/skills/dsom-token-calculator/scripts/calculate-tokens.py`.
4. **Digital Sovereignty Signatures:** Every skill document concludes with the standard Deep State of Mind (DSOM) AI Protocol footer, ensuring provenance, licensing, and compliance across sessions.

---

> **Tip:** Keep both `AGENTS.md` files up to date — the root gateway and `.agents/AGENTS.md`. This helps Google Jules, Cursor, GitHub
> Copilot, other AI agents, and your human teammates work with this repository more effectively and in full alignment with the DSOM
> protocol.

---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-26*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
