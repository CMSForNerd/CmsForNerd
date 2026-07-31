---
okf_version: 0.1
type: documentation
title: "📍 START-HERE: Master Onboarding Map"
description: "Sovereign roadmap with 12 defined Entry Points mapping code, deployment, rules, and governance."
resource: "file:///START-HERE.md"
topics: [onboarding, entrypoints, map, dsom]
timestamp: 2026-07-27T12:00:00Z
---
# 📍 START-HERE: Master Onboarding Map

Welcome to the **CmsForNerd v4.1.0** Sovereign AI & Human Onboarding Map. View the [Live Demo](https://cmsfornerd.onrender.com/index.php) or access via our [Context7 MCP & LLM standard link](https://context7.com/cmsfornerd/cmsfornerd/llms.txt?tokens=10000). This file serves as the master blueprint
linking to all 12 major Entry Points of the platform, enabling instant orientation and semantic traversal.

---

## 🏛️ The 12 Defined Entry Points

| Entry Point | Name | File Path / Link | Semantic Purpose |
| :---: | :--- | :--- | :--- |
| **1** | **Engineering Entry Point** | [`docs/HOWTO-CLONE-DSOM-PROJECT.md`](docs/HOWTO-CLONE-DSOM-PROJECT.md) | Standard developer setup, cloning, and local environments. |
| **2** | **Cognitive Entry Point** | [`.agents/AGENTS.md`](.agents/AGENTS.md) | Full AI Constitution, operational laws, rules, and persona DNA. |
| **3** | **Master Onboarding Map** | [`START-HERE.md`](START-HERE.md) | This index — the global directory and subsystem topological map. |
| **4** | **Daily Operations Entry Point** | [`docs/HOWTO-PALACE-ONBOARDING.md`](docs/HOWTO-PALACE-ONBOARDING.md) | Guided walk of the Sovereign Markdown Palace Wings and Halls. |
| **5** | **Legacy Upgrade Entry Point** | [`docs/HOWTO-UPGRADE-LEGACY-DSOM.md`](docs/HOWTO-UPGRADE-LEGACY-DSOM.md) | Protocols to port and modernise legacy PHP setups to v4+. |
| **6** | **Subagent Swarm Entry Point** | [`docs/governance/MULTI-AGENT-PROTOCOLS.md`](docs/governance/MULTI-AGENT-PROTOCOLS.md) | Orchestration protocols for multi-agent workflows. |
| **7** | **Procedural Skill Entry Point** | [`docs/governance/AI-SKILL-ARCHITECTURE.md`](docs/governance/AI-SKILL-ARCHITECTURE.md) | Structuring of standard operating procedures in `.agents/skills/`. |
| **8** | **Sovereign Knowledge Entry Point**| [`docs/governance/LLM-WIKI-ADOPTION.md`](docs/governance/LLM-WIKI-ADOPTION.md) | Guidelines for Knowledge Compounding and LLM-Wiki workflows. |
| **9** | **Security & Audit Entry Point** | [`docs/governance/GITOPS-AIOPS-ANSIBLE-STRATEGY.md`](docs/governance/GITOPS-AIOPS-ANSIBLE-STRATEGY.md) | Hardening, OpenSCAP auditing, and host compliance. |
| **10** | **Infrastructure Orchestration** | [`playbooks/deploy_prod_compose.yml`](playbooks/deploy_prod_compose.yml) <br> [`docs/RENDER-DEPLOYMENT-GUIDE.md`](docs/RENDER-DEPLOYMENT-GUIDE.md) | Ansible playbooks for rootless Podman 5, and Render Cloud Deployment Guide. |
| **11** | **Local Development Setup** | [`technical-resources/installation-guide.md`](technical-resources/installation-guide.md) | PHP 8.4 runtime config, local composer setup, server execution. |
| **12** | **Core Routing & Theme** | [`themes/CmsForNerd/pager.php`](themes/CmsForNerd/pager.php) | Central routing, Standard vs AMP view, layout controller. |
| **13** | **Pull Request & Bot Synergy SOP** | [`docs/governance/SOP-PULL-REQUEST-BOT-SYNERGY.md`](docs/governance/SOP-PULL-REQUEST-BOT-SYNERGY.md) | End-to-end GitHub/GitLab PR workflow & bot synergy SOP. |

---

## 🧠 AI Agent Boot Sequence

For AI agents and cognitive assistants initialising in this environment, the following reading order is required:

1. **[`.agents/AGENTS.md`](.agents/AGENTS.md)** — AI Constitution, operational laws, rules, and persona DNA
2. **[`.agents/brain/task.md`](.agents/brain/task.md)** — Current mental state and active task tracking
3. **[`.agents/brain/walkthrough.md`](.agents/brain/walkthrough.md)** — Episodic memory and session accomplishments
4. **[`.agents/brain/palace_registry.md`](.agents/brain/palace_registry.md)** — Sovereign Markdown Palace spatial memory registry
5. **[`.agents/brain/active_context_manifest.md`](.agents/brain/active_context_manifest.md)** — Active context manifest for current working state
6. **[`docs/AI-STATE-SYNC.md`](docs/AI-STATE-SYNC.md)** — AI state synchronisation protocols and handshake procedures
7. **[`START-HERE.md`](START-HERE.md)** — This master onboarding map (you are here)

---

## 🏗️ Structural Map

### 1. The Core Codebase (The Web Stack)
- **`includes/bootstrap.php`:** Core engine. Initializes the immutable `CmsContext` with automated content sniffer.
- **`src/Registry.php`:** Zero-Global registry storage to completely replace the legacy `global` keyword.
- **`src/CmsContext.php`:** Immutability state container holding page parameters, theme metadata, and security nonces.
- **`themes/CmsForNerd/pager.php`:** Front Controller router that splits rendering between Desktop/Standard and AMP views.
- **`contents/`:** UI presentation fragments paired with their controller logic (e.g., `about-body.inc` for `about.php`).

### 2. The Cognitive Workspace (Deep State of Mind - DSOM)
- **`AGENTS.md` (root):** Discovery gateway for platform assistants (Jules, Cursor, Copilot).
- **`.agents/AGENTS.md`:** Sovereign constitution, architectural rules, and writing persona.
- **`.agents/brain/`:** Spatial and chronological memory repository (`task.md`, `walkthrough.md`, `palace_registry.md`).
- **`.agents/skills/`:** Reusable, executable SOP directories providing specialized tools to automation scripts.

### 3. Documentation (The Sovereign Markdown Palace)
- **`.llms/index.md`:** Comprehensive AI agent context index for LLM parsing and NotebookLM indexing.
- **`docs/`:** Master directory containing guides, tutorials, and security checklists.
- **`docs/governance/`:** Rigid theoretical frameworks establishing absolute operational sovereignty.

---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-27*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
