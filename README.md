---
okf_version: 0.1
type: documentation
title: "CMSForNerd Readme"
timestamp: "2026-08-01T15:00:00Z"
---
# 🚀 CmsForNerd v4.3.0 (2026 Deep State of Mind Edition)

**CmsForNerd** is a Lightweight, Radically Simple, Database-Free PHP Laboratory CMS designed as a live learning
environment for modern developers. Version 4.3 features the **Deep State of Mind (DSOM) Cognitive Architecture**,
**Zero-Global** architecture, **Automated Compliance Validation**, and professional **Rootless Container Orchestration**.

**Current Version:** 4.3.0 (Production Stable)

**Live Demo:** [CmsForNerd Live Demo](https://cmsfornerd.onrender.com/index.php)

**Sovereign Context Index (LLM standard):** [Context7 MCP Access & LLM Index](https://context7.com/cmsfornerd/cmsfornerd/llms.txt?tokens=10000)

**Changelog:** See [CHANGELOG.md](CHANGELOG.md) for latest release details.

**Primary Maintainer:** Harisfazillah Jamel

**AI Architect:** Google Gemini & Google Antigravity

---

## 📱 AMP & Dual-View Architecture

Version 4.3.0 maintains the high-performance **Dual-View** engine:

- **AMP Hybrid Rendering**: Automatically detects `?view=amp` to serve Google-validated Accelerated Mobile Pages.
- **Zero-JS Interactivity**: Implementation of `amp-sidebar` for lightning-fast mobile navigation.
- **Automated Validation**: Integrated output buffering that transforms standard `<img>` tags into CLS-optimized
  `<amp-img>` components.
- **CSS Size Guard**: Automated monitoring to ensure mobile styles remain under the strict 75KB AMP threshold.

---

## 🧠 The "State of Mind" Protocol

To maintain synchronization between the Human Architect and the AI Agent, the following "State of Mind" (SoM)
handshake is **REQUIRED** for all laboratory sessions.

### 🌅 Start of Day: The Handshake

1. **Sync Context**: Review `.agents/brain/task.md` to identify the current "Mental State" of the project.
2. **Verify Integrity**: Run `composer lab-check` to ensure the laboratory is "Green" before any new logic is injected.
3. **Baseline Push**: Ensure all previous work is committed and tagged to prevent state-drift.

### 🌇 End of Day: The Snapshot

1. **Update Brain**: Update `.agents/brain/walkthrough.md` with the day's logic changes to prepare for the next session.
2. **Log Milestones**: Finalize entries in `CHANGELOG.md`.
3. **Tag State**: Check whether the `v4.3.0` tag already exists and verify that it references the intended commit before creating it; only create the annotated tag (e.g., `git tag -a v4.3.0`) when absent, and report or handle a mismatched existing tag without overwriting it.

---

## 📋 MASTER CONTEXT BLOCK (Copy/Paste for AI)

> **PROMPT:** Act as a Lead PHP Architect for **CmsForNerd v4.3.0**. Standards: PHP 8.4 strict types, PHPStan Level 8,
> Zero-Global variables (via `Registry`), and "Pair Logic" (Logic in `.php`, UI in `-body.inc`). This is a Dual-View CMS
> (Standard/AMP).
> **Core Engine (bootstrap.php):** Initializes Immutable `CmsContext` with automated `schemaType` detection.
> **Router (pager.php):** Handles Dual-View routing and AMP transformation.
> **Current Task:** [INSERT YOUR CURRENT GOAL HERE]

---

## 🛠️ Tech Stack & Development Environment

### Core Engine

- **Language**: PHP 8.4+ (Enforced Strict Types & Constructor Property Promotion)
- **Mobile Engine**: AMP ⚡ (Accelerated Mobile Pages HTML/CSS)
- **Architecture**: "Zero-Global" (Immutable **CmsContext** & **Registry** Pattern)
- **Security**: Native Content Security Policy (CSP) with Nonce-tracking

### Professional Tooling

- **IDE**: [Google Antigravity](https://deepmind.google/technologies/gemini/) (AI-native) or VS Code
- **Static Analysis**: **PHPStan Level 8** (The Laboratory Gold Standard)
- **Package Manager**: Composer 2.8+
- **Testing & QA**: Pest PHP, PHPUnit 11.5+, and PHP_CodeSniffer 3.11+ (PSR-12)

---

## 🚀 Installation & Laboratory Setup

### 🪟 Option 1: Microsoft Windows 11 (Nerd-Stack)

1. Install [Laravel Herd](https://herd.laravel.com) and select **PHP 8.4**.
2. Clone the repo into your Herd `sites` folder.
3. Run `composer install` and `composer lab-check`.

### 🐧 Option 2: Linux (Debian/AlmaLinux)

1. Use [Ondřej Surý's PPA](https://deb.sury.org/) or [Remi Repo](https://rpms.remirepo.net/).
2. Install `php8.4-cli`, `php8.4-mbstring`, `php8.4-xml`, and `php8.4-zip`.

### 🐳 Option 3: Rootless Container Orchestration (Ansible & Podman)

1. Deploys CmsForNerd within rootless containers (Nginx + PHP-FPM 8.4), offering rootless isolation benefits but requiring host-policy trade-offs (specifically, setting `net.ipv4.ip_unprivileged_port_start=80` changes host-wide port-binding policy for all unprivileged processes on the system, not just CmsForNerd).
2. See the comprehensive [Ansible-Podman Deployment Guide](docs/ANSIBLE_PODMAN_GUIDE.md) for automated setups on target hosts.
3. Learn how to set up Google Jules on Ubuntu 26.04 in our [Google Jules Ubuntu 26.04 Setup Guide](docs/HOWTO-SETUP-GOOGLE-JULES-UBUNTU-26-04.md).
4. Learn how to deploy to Render Cloud in our [Render Deployment Guide](docs/RENDER-DEPLOYMENT-GUIDE.md). View the [Live Demo on Render](https://cmsfornerd.onrender.com/index.php).

### 🚀 Option 4: DSOM Node Bootstrap, ASIMP OS Hardening & OpenSCAP Compliance (v4.2.0)

1. Use our secure, sanitised dual-play bootstrapping playbook to provision a new server, VM, or node with target unprivileged identity standard (`dsom-admin:2001:2001`) and lingering.
2. The `setup_os` role automatically handles essential software updates, kernel hardware-aware optimisations, and full security auditing via Lynis and OpenSCAP.
3. Integrates ASIMP (**Ansible System Integrity Management Platform**) principles to run dual-engine audits (Lynis + OpenSCAP CIS Level 2) before and after applying hardening policies to measure the security improvement.
4. Built-in **Google Jules Sandbox** support with `tools/mock-asimp.sh` to simulate full "Measure, Harden, Re-Measure" loops and scorecards without requiring privileged root access.
5. Execute the mock workflow inside the Google Jules sandbox:
   ```bash
   bash tools/mock-asimp.sh
   ```
6. Execute live playbooks in production on a real host:
   ```bash
   ansible-playbook -i inventory/hosts.example.yml playbooks/bootstrap_node.yml
   ```

### 📦 Option 5: GitHub Pages Static Deployment (With .nojekyll)

1. Bakes the dynamic PHP structure into absolute flat HTML/CSS/JS files inside the `build_static/` directory using our custom compilation tool.
2. The compilation pipeline is fully orchestrated and automated on every push to `master` via GitHub Actions (`static-build.yml`).
3. Automatically generates a `.nojekyll` file within the distribution folder to completely bypass Jekyll rendering, preventing Liquid conflicts and allowing uninhibited progressive web app and web worker support.
4. Read the complete setup steps in our [GitHub Pages Deployment Guide](docs/GITHUB-PAGES-DEPLOYMENT-GUIDE.md).

---

## 🤖 AI-Assisted Development

1. **Google Gemini (The Architect)**: Use for planning logic and Level 8 PHPDoc refactoring.
2. **Google Antigravity (The Agent)**: Use for secure file writes, static analysis audits, and Git management.
3. **Workflow**: Describe changes → Review `implementation_plan.md` → Verify with `composer lab-check`.

---

### ⚖️ Standards (RFC 2119 & v4.3.0 Engineering)

- **MUST**: Begin all files with `declare(strict_types=1);`.
- **MUST**: All mobile output **MUST** pass the `AMP Validator`.
- **REQUIRED**: All code **MUST** pass `composer lab-check` (PHPStan Level 8).
- **MUST**: Follow the "State of Mind" handshake for every session.
- **MUST**: Every `.php` controller MUST have a corresponding `-body.inc` fragment in the `contents/` directory.

---

### Credits

- **Author**: Harisfazillah Jamel (LinuxMalaysia)
- **Assistant**: Google Gemini & Google Antigravity (v3.6 "Semantic Evolution" Milestone)
- **Website**: [linuxmalaysia.com](https://www.linuxmalaysia.com)

*Modernization without loss of simplicity. Mobile excellence without the bloat.*


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-27*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
