# 🚀 CmsForNerd v3.6.0 (2026 Laboratory Edition)

**CmsForNerd** is a Lightweight, Radically Simple, Database-Free PHP Laboratory CMS designed as a live learning
environment for modern developers. Version 3.6.0 marks the graduation of the **Zero-Global Architecture** and
**Semantic Content Autodetection**.

**Current Version:** 3.6.0 (Semantic Evolution Milestone)

**Changelog:** See [CHANGELOG.md](CHANGELOG.md) for v3.6.0 release details.

**Primary Maintainer:** Harisfazillah Jamel

**AI Architect:** Google Gemini & Google Antigravity

---

## 📱 AMP & Dual-View Architecture

Version 3.6 maintains the high-performance **Dual-View** engine:

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

1. **Sync Context**: Review `.agent/brain/task.md` to identify the current "Mental State" of the project.
2. **Verify Integrity**: Run `composer lab-check` to ensure the laboratory is "Green" before any new logic is injected.
3. **Baseline Push**: Ensure all previous work is committed and tagged to prevent state-drift.

### 🌇 End of Day: The Snapshot

1. **Update Brain**: Update `.agent/brain/walkthrough.md` with the day's logic changes to prepare for the next session.
2. **Log Milestones**: Finalize entries in `CHANGELOG.md`.
3. **Tag State**: Use `git tag -a v3.6.0` to create a permanent snapshot of the current "State of Mind."

---

## 📋 MASTER CONTEXT BLOCK (Copy/Paste for AI)

> **PROMPT:** Act as a Lead PHP Architect for **CmsForNerd v3.6**. Standards: PHP 8.4 strict types, PHPStan Level 8,
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
- **Testing & QA**: PHPUnit 11.5+ and PHP_CodeSniffer 3.11+ (PSR-12)

---

## 🚀 Installation & Laboratory Setup

### 🪟 Option 1: Microsoft Windows 11 (Nerd-Stack)

1. Install [Laravel Herd](https://herd.laravel.com) and select **PHP 8.4**.
2. Clone the repo into your Herd `sites` folder.
3. Run `composer install` and `composer lab-check`.

### 🐧 Option 2: Linux (Debian/AlmaLinux)

1. Use [Ondřej Surý's PPA](https://deb.sury.org/) or [Remi Repo](https://rpms.remirepo.net/).
2. Install `php8.4-cli`, `php8.4-mbstring`, `php8.4-xml`, and `php8.4-zip`.

---

## 🤖 AI-Assisted Development

1. **Google Gemini (The Architect)**: Use for planning logic and Level 8 PHPDoc refactoring.
2. **Google Antigravity (The Agent)**: Use for secure file writes, static analysis audits, and Git management.
3. **Workflow**: Describe changes → Review `implementation_plan.md` → Verify with `composer lab-check`.

---

## ⚖️ Standards (RFC 2119 & v3.6 Engineering)

- **MUST**: Begin all files with `declare(strict_types=1);`.
- **MUST**: All mobile output **MUST** pass the `AMP Validator`.
- **REQUIRED**: All code **MUST** pass `composer compliance` (PHPStan Level 8).
- **MUST**: Follow the "State of Mind" handshake for every session.
- **MUST**: Every `.php` controller MUST have a corresponding `-body.inc` fragment in the `contents/` directory.

---

### Credits

- **Author**: Harisfazillah Jamel (LinuxMalaysia)
- **Assistant**: Google Gemini & Google Antigravity (v3.6 "Semantic Evolution" Milestone)
- **Website**: [linuxmalaysia.com](https://www.linuxmalaysia.com)

*Modernization without loss of simplicity. Mobile excellence without the bloat.*
