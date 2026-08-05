---
okf_version: 0.1
type: documentation
title: "Modernization History"
description: "Sovereign log tracking the strategic phases and release milestones of CMSForNerd."
resource: "file:///docs/HISTORY.md"
timestamp: "2026-08-05T10:00:00Z"
---
# 📜 Modernization History

Tracking the evolution of **CMSForNerd** from a legacy flat-file system to a modern educational powerhouse.

## Strategic Phases

### Phase 25: ASIMP OS Hardening & OpenSCAP Integration (v4.3.0)

- **ASIMP Alignment**: Imported and integrated the **Ansible System Integrity Management Platform (ASIMP)** to implement a complete "Measure, Harden, Re-Measure" security loop.
- **Google Jules Sandbox Support**: Designed and deployed a robust mock execution engine `tools/mock-asimp.sh` tailored for Google Jules unprivileged sandboxes. It automatically handles sandbox boundaries to write simulated JSON metrics and complete `SECURITY_AUDIT_REPORT.md` scorecards under unprivileged paths.
- **Unified Security Metrics Reporting**: Formulated deep OS-level scorecard parameters comparing baseline and post-hardening scores side-by-side for both Lynis Hardening Index and OpenSCAP CIS Level 2 profiles.
- **Production Host Execution**: Preserved direct compatibility with real Debian/RedHat host system execution under the unprivileged `dsom-admin:2001:2001` identity via the `setup_os` Ansible playbooks.

### Phase 24: GitHub Pages Static Deployment Alignment (v4.2.5)

- **Jekyll Bypassing (`.nojekyll`)**: Updated `tools/bake-static-pages.php` to automatically generate an empty `.nojekyll` file at the root of the output static build directory, completely disabling Jekyll processing on GitHub Pages to prevent Liquid template syntax conflicts, directory exclusions (e.g., `.well-known`), and routing issues.
- **Comprehensive Guidebook**: Created `docs/GITHUB-PAGES-DEPLOYMENT-GUIDE.md` detailing the static baking pipeline, Jekyll processing risks, and step-by-step repository publishing settings.
- **Sitemap & Navigation Mapping**: Integrated the new guide across the four required layers (`docs/SUMMARY.md`, `mkdocs.yml`, `START-HERE.md`, `llms.txt`, and `.llms/index.md`).
- **Automated Validation**: Authored robust unit tests inside `tests/GithubPagesDeploymentTest.php` to verify successful generation of `.nojekyll` and proper documentation registrations.

### Phase 23: HTML Microdata Structural Integration (v4.2.4)

- **Semantic HTML Microdata**: Upgraded standard template layouts, the dynamic AMP view (`pager.php`), standalone/utility interfaces (`offline.php`, `ujian-form.php`, `sanity-check.php`), and standard fallback scripts with Schema.org compliant `itemscope` and `itemtype` microdata attributes.
- **Privacy Metadata Coverage**: Automated the placement of paired microdata properties across all subdirectory privacy files, protecting index files across 14 directories from crawler confusion.
- **Compliance Integration**: Created explicit microdata presence unit tests under `tests/SecurityTest.php` and synchronized compliance assertions to enforce consistent static analysis results.

### Phase 22: OWASP Top 10 Hardening & Site Performance (v4.2.3)

- **Secure Session Transport**: Hardened the session lifecycle in `SecurityUtils::startSecureSession()` with explicit cookie flags (SameSite=Strict, HttpOnly, dynamic HTTPS detection) and periodic ID rotation.
- **CSRF Token Verification**: Implemented timing-safe token matches to defend all POST input fields from CSRF hijacking.
- **OWASP Secure HTTP Headers**: Configured rigid security headers (HSTS, CSP, Referrer-Policy, Permissions-Policy, X-Frame-Options) and restricted unallowed HTTP methods at the routing layer.
- **Zero-Dependency Static Page Caching**: Developed server-side GZIP output buffering and static page caches in `PerformanceUtils.php` with smart invalidation logic, as well as metadata directory scan caching to optimize response times.

### Phase 21: PR Bot Synergy & Scripting Governance (v4.2.2)

- **Standard Operating Procedure (SOP)**: Deployed `docs/governance/SOP-PULL-REQUEST-BOT-SYNERGY.md` laying down strict guidelines for GitLab and GitHub PR workflows, bot-review wait-states, and clean single-branch workspace topologies.
- **Cross-Platform Scripting Decoupling**: Codified Rule 25 and 26 to restrict inline scripts from `composer.json`, refactoring configuration tools into dedicated, independent PHP script controllers (`tools/check-legacy-global.php` and `tools/check-strict-types.php`).

### Phase 20: Windows Shell & Context Re-learning (v4.2.1)

- **PowerShell Escaping Alignment**: Refactored complex inline double-quotes and backslash characters inside `composer.json` to prevent parsing failures across Windows CMD, PowerShell, Linux Bash, and macOS Zsh shells.
- **Cognitive Twin Re-Anchoring**: Performed systematic memory orientation using the 3-step Mechanical Boot Sequence to synchronize spatial knowledge files.

### Phase 19: Live Demo & Context7 MCP (v4.2.0)

- **Universal Accessibility Map**: Integrated references to the official live production URL (`https://cmsfornerd.onrender.com/index.php`) and the Context7 MCP crawler link (`https://context7.com/cmsfornerd/cmsfornerd/llms.txt?tokens=10000`) across all documentation landing zones.

### Phase 18: Apache .htaccess & Docker Alignment (v4.1.9)

- **Apache Rewrite Rules Compliance**: Removed forbidden `<DirectoryMatch>` and `<Directory>` tags inside directory-level configuration files (`.htaccess`), replacing them with secure rewrite rules to bypass 500 errors on Apache/Render.
- **Docker/Podman Expositions**: Aligned container build files with strict Pest PHP testing rules by restricting chown permissions strictly to data directory limits.

### Phase 17: Render Cloud Blueprint Specification (v4.1.8)

- **Blueprint Spec (`render.yaml`)**: Designed and implemented the official Render Blueprint specification at the root of the repository. Bound to Singapore (`singapore`) region with `Asia/Kuala_Lumpur` timezone environment configuration.
- **Secure Secret Handling (`sync: false`)**: Configured secret fields (`APP_SECRET`, `ADMIN_PASSWORD`) with `sync: false` to securely prompt administrators during deployment, keeping critical passwords out of Git/GitHub history.
- **Custom PHP 8.4 Apache Container (`Containerfile`)**: Engineered a lightweight, multi-stage custom Apache/PHP 8.4 `Containerfile` enabling dynamic mod_rewrite, security headers, and `.htaccess` overrides.
- **Multi-Layer Documentation**: Created a comprehensive guide `docs/RENDER-DEPLOYMENT-GUIDE.md` and registered it in `SUMMARY.md`, `mkdocs.yml`, `START-HERE.md`, and `llms.txt`.

### Phase 16: Root Workspace Cleanliness Mandate (v4.1.6)

- **Repository Root Cleanup**: Cleaned up the repository root directory by moving all unrelated markdown files to the `docs/` Palace.
- **Critical Entry Points**: Retained only core configuration files and critical entry-point documents (`README.md`, `CHANGELOG.md`, `SUMMARY.md`, `AGENTS.md`, `SECURITY.md`, `CONTRIBUTING.md`) in the root.
- **Duplicate Removal**: Deleted duplicate identical uppercase files (`DIRECTORY_SECURITY.md`, `DOCS_REQUIREMENTS.md`) inside `docs/` to eliminate redundancy and maintain a single source of truth.
- **Reference Updates**: Updated all internal documentation and sitemap links across `README.md` and `SUMMARY.md` to map cleanly to their new paths inside the `docs/` Palace.

### Phase 15: DSOM Node Bootstrap & OpenSCAP Compliance Hardening

- **Dual-Play Transition Playbook**: Developed `playbooks/bootstrap_node.yml` to orchestrate secure rootful user provisioning and baseline setup.
- **Dynamic SCAP Content Retrieval**: Implemented auto-download of the latest ComplianceAsCode definitions.
- **CIS Level 2 Auditing and OVAL Scanning**: Configured multi-platform compliance auditing for Ubuntu 24.04 and RHEL family.
- **Unified Compliance reporting**: Set up automatic extraction of Lynis and OpenSCAP compliance scores to build a unified report.

### Phase 14: Podman 5+ Native Migration & Google Jules Setup

- **Ubuntu 26.04 Standardisation**: Integrated support for Ubuntu 26.04 (Plucky Puffin), the first stable OS natively shipping
  with Podman 5.x.
- **Pure Podman Migration**: Renamed and migrated all legacy Dockerfile and docker-compose assets to Podman-native Containerfile and
  compose.yml configurations.
- **Asynchronous Jules Guide**: Penned a comprehensive, OKF-compliant guide detailing how to set up and run Google Jules asynchronously
  with Podman 5.
- **Systemd & HA Recovery**: Patched systemd user service and playbooks to enforce non-Docker, daemon-independent containers.

### Phase 13: Security Hardening & Duplication Cleanups

- **Sitemap & Feed Sanitization**: Secured dynamically generated XML and RSS payloads against cross-site scripting (XSS).
- **Host Header Injection Protection**: Centralized host header parsing into `SecurityUtils::getSafeBaseUrl()` using dynamic safe filter boundaries.
- **Logic Centralization**: Centralized directory file scanner and pairing in `SecurityUtils::discoverPages()` to eliminate duplication.
- **Unit Verification Suite**: Expanded `tests/SecurityTest.php` with robust directory validation and bot detection rules.

### Phase 12: Deep State of Mind (DSOM) Integration

- **Workspace Pluralisation**: Successfully migrated the `.agent` singular workspace to `.agents` plural.
- **Sovereign Rulebook**: Integrated the core rulebook (`AGENTS.md`) enforcing Zero-Global memory, Open Knowledge Format (OKF), and Sovereign Signatures.
- **Sovereign Agent Skills**: Deployed all 25 OKF-compliant sovereign skills inside `.agents/skills/`.
- **Spatial Memory Framework**: Configured the Sovereign Markdown Palace Wings and Registry under `.agents/brain/`.
- **DSOM Tooling Suite**: Deployed high-fidelity scripts (`eod-palace.sh`, `git-ritual.sh`, etc.) to the `tools/` directory.
- **Unified Validation**: Overwrote and merged `tools/audit-pre-flight.sh` to include both PHP 8.4/8.3 environment checks and DSOM validations.

### Phase 11: Automated Trust, Theme v4.0 & Orchestration

- **CI/CD Intelligence**: Implemented GitHub Actions for absolute compliance enforcement (Digital Sentry).
- **High-Fidelity Visuals**: Successfully integrated Glassmorphism tokens (--lab-glass-bg, --lab-blur) across both Standard and AMP views.
- **Global Variable Union**: Synchronized all color tokens to `--lab-purple`, achieving 100% parity between core CSS and the UI Audit Kit.
- **Cache-Busting Sentinel**: Implemented CSS versioning (?v=4.0.0) to force high-fidelity rendering across all laboratory interfaces.
- **Rootless Container Orchestration**: Deployed production-grade Ansible playbooks and Dockerfiles for rootless Podman deployment.
- **Adaptive Dark Mode Contrast**: Implemented adaptive CSS custom variables ensuring perfect contrast globally across all documentation.
- **Centralized Security checks**: Restructured request routing logic under `SecurityUtils::resolvePageName` to eliminate duplicates.

### Phase 7: Semantic Evolution & Zero-Global (v3.6.0)

- **Zero-Global Registry**: Reached 100% architectural compliance by eliminating the `global` keyword through the
  implementation of `src/Registry.php`.
- **Automated Content Sniffer**: Implemented detection logic that dynamically classifies content as `TechArticle`,
  `Course`, or `WebPage` based on technical signatures.
- **AI-Readiness**: Expanded the `CmsContext` state machine to support automated Schema.org tagging.
- **Zero-Debt Audit**: Global remediation of over 100 Markdown lint violations to ensure high-fidelity rendering.

### Phase 6: Laboratory & UI Stabilization (v3.5.7)

- **UI Audit Laboratory**: Bootstrapped `ui-kit.php` for technical theme and contrast verification.
- **AMP Acceleration Documentation**: Implemented `amp-acceleration.php` detailing dual-view logic.
- **Workbox Integration**: Migrated vanilla Service Worker to Google Workbox v6 for optimized caching.
- **Path Sovereignty**: Enforced absolute URI resolution (`baseUrl`) site-site-wide for subdirectory support.

### Phase 5: Modernization Mastery (v3.5.1)

- **Zero-Global Architecture**: Complete elimination of `global` state in 30+ controllers.
- **PWA SPA-Hybrid**: Introduced `Stale-While-Revalidate` caching and memory-instant `bfcache` routing for desktop and
  mobile.
- **CSP Standardization**: Achieved synchronized `nonce` tracking across standard SSR and AMP Blob workers.
- **Factory Pattern**: Refactored context initialization to `createCmsContext()` with explicit dependency injection.

### Phase 4: The Semantic Alignment (v3.4)

- **AI Readiness**: Modernized metadata layer with JSON-LD and Schema.org.
- **Theme Correction**: Standardized theme paths to `themes/CmsForNerd`.
- **Security Auditor**: Introduction of semantic audit tools for students.

## [4.1.8] - 2026-07-30 (Render Cloud Blueprint Specification)

**"Official Render Blueprint Infrastructure Setup with Secure Multi-Stage Containerization"**

### 🚀 Render Blueprint & Containerization
- **Blueprint Spec (`render.yaml`)**: Implemented the official Render Blueprint specification at the root of the repository. Bound to Singapore (`singapore`) region with `Asia/Kuala_Lumpur` timezone environment configuration.
- **Secure Secret Handling (`sync: false`)**: Configured secret fields (`APP_SECRET`, `ADMIN_PASSWORD`) with `sync: false` to securely prompt administrators during deployment, keeping critical passwords out of Git/GitHub history.
- **Custom PHP 8.4 Apache Container (`Containerfile`)**: Engineered a lightweight, multi-stage custom Apache/PHP 8.4 `Containerfile` enabling dynamic mod_rewrite, security headers, and `.htaccess` overrides.
- **Multi-Layer Documentation**: Created a comprehensive guide `docs/RENDER-DEPLOYMENT-GUIDE.md` and registered it in `SUMMARY.md`, `mkdocs.yml`, `START-HERE.md`, and `llms.txt`.

## [4.1.6] - 2026-07-29 (Root Workspace Cleanliness Mandate)

**"Complete Repository Root Cleanup & Reference Synchronisation"**

### 🧹 Root Workspace Cleanliness
- **Workspace Organization**: Cleaned up the repository root directory by moving all unrelated markdown documentation files into the `docs/` folder to maintain a pristine project root.
- **Critical Entry Points**: Retained only core configuration files and critical entry-point documents (`README.md`, `CHANGELOG.md`, `SUMMARY.md`, `AGENTS.md`, `SECURITY.md`, `CONTRIBUTING.md`) in the root.
- **Duplicate Removal**: Deleted duplicate identical uppercase files (`DIRECTORY_SECURITY.md`, `DOCS_REQUIREMENTS.md`) inside `docs/` to eliminate redundancy and maintain a single source of truth.
- **Reference Updates**: Updated all internal documentation and sitemap links across `README.md` and `SUMMARY.md` to map cleanly to their new paths inside the `docs/` Palace.
- **EOD Memory Consolidation**: Completed End-of-Day procedures including active task tracking, walkthrough updates, and episodic record preservation.

---

## [4.1.3] - 2026-07-27 (Planned Handover Date) (Sitemaps & Certificate Hardening against XSS)

**"Complete Security Hardening of Feeds, Sitemaps, and Graduation Certificates"**

### 🛡️ Secure Dynamic Output Generation
- **Reflected XSS Remediation**: Secured standalone RSS feeds (`rss.php`), standing sitemaps (`sitemap.php`), and student graduation certificates (`graduation.php` / `contents/graduation-body.inc`) with precise escaping via `SecurityUtils::escapeHtml()`.
- **Host Header Poisoning Protection**: Centralized and sanitized `$_SERVER['HTTP_HOST']` calculation within `SecurityUtils::getSafeBaseUrl()` to guarantee safe URL generation.
- **Centralized Page discovery**: Streamlined logic scanning for page pairs under `SecurityUtils::discoverPages()`, preventing SonarCloud warnings and code duplication.
- **Defensive CSP Headers**: Deployed strict Content Security Policy headers for dynamic standalone XML generation.

### 🧪 Static Analysis & Unit Verification
- **Verification Tests**: Built automated tests in `tests/SecurityTest.php` to analyze directory traversal boundaries, bot detection trust models, and IPv6 bitmasks.

---

## [4.1.5] - 2026-07-27 (Planned Handover Date) (DSOM Node Bootstrap & OpenSCAP Compliance Hardening)

**"Automated Node Identity Bootstrapping & Dynamic OpenSCAP Compliance Hardening"**

### 🚀 Dual-Play Bootstrapping & OS Baseline
- **Orchestrated Host Provisioning**: Created the secure transition playbook `playbooks/bootstrap_node.yml` deploying unprivileged `dsom-admin:2001:2001` identity mapping.
- **Sanitized Inventory Example**: Implemented `inventory/hosts.example.yml` with private IP boundaries and localhost configurations.

### 🛡️ Dynamic OpenSCAP CIS Level 2 Evaluation
- **On-Demand Content Fetching**: Tasks to fetch and unzip the latest ComplianceAsCode guides dynamically.
- **OVAL & CIS Scanner**: Automated multi-OS CIS Level 2 audits, Canonical OVAL vulnerability scans, and automatic bash remediation.
- **Integrated Audit Ledger**: Formulated tasks inside `playbooks/roles/setup_os/tasks/reporting.yml` to compile a unified report mapping Lynis and OpenSCAP compliance metrics.

## [4.1.4] - 2026-07-27 (Planned Handover Date) (Podman 5 Native Migration & Google Jules Ubuntu 26.04 Setup)

**"Complete Podman 5 Migration & Google Jules Ubuntu 26.04 Deployment Blueprint"**

### 🐳 Pure Podman 5 Engine & Configurations
- **Pure Naming Standard**: Fully migrated Dockerfile definitions to native `Containerfile` and Docker-Compose to `compose.yml` across both
  staging and production playbooks.
- **Podman 5+ Supporting Suite**: Upgraded Debian/Ubuntu package specifications to install `uidmap`, `dbus-user-session`, and `catatonit`
  natively on Ubuntu 26.04.
- **Version Reporting**: Integrated automated version printing (`podman --version`) into the playbook infrastructure.

### 🤖 Google Jules Setup Guide
- **Sovereign Guidebook**: Authored `docs/HOWTO-SETUP-GOOGLE-JULES-UBUNTU-26-04.md` detailing unprivileged setups, lingering settings, and
  Cloud SDK integrations.

---

## [4.1.0] - 2026-07-26 (Deep State of Mind Integration)

**"Full Integration of DSOM Sovereign Cognitive Architecture & Pluralised Workspace Tooling"**

### 🧠 Workspace & Core Rulebook
- **Pluralised Workspace**: Successfully migrated `.agent` singular references to `.agents` plural.
- **Sovereign Rulebook**: Integrated OKF v0.1 compliant `AGENTS.md` and 25 Sovereign Agent Skills.
- **Palace Wings & Registry**: Mapped spatial memory hierarchy under `.agents/brain/wings/`.

### 🛠️ Hybrid-Sovereign Validation
- **Unified Pre-flight**: Overwrote and merged `tools/audit-pre-flight.sh` to check PHP 8.4/8.3 constraints and DSOM validations.
- **Sovereign Tooling Suite**: Deployed robust automation scripts (`eod-palace.sh`, `git-ritual.sh`, etc.).

---

## [4.0.0] - 2026-07-26 (Production Stable)

**"Zero-Global Architecture with Containerized Orchestration & High-Contrast Design"**

### 🐳 Rootless Container Orchestration
- **Ansible & Podman Core**: Created secure container recipes supporting user namespace mapping and unprivileged host-wide port 80 binding policy changes and their security implications.
- **Enterprise Linux Support**: Tailored playbooks to run seamlessly on modern Enterprise Linux (AlmaLinux) and Debian/Ubuntu systems.

### 🎨 High-Contrast Adaptive Dark Mode
- **Accessibility Parity**: Resolved white-text-on-white-background legibility issues globally with targeted, high-specificity overrides.
- **Adaptive Variables**: Bound alerts and info panels to adaptive CSS properties (`--lab-info-bg`, `--lab-warning-bg`, etc.).

### 🛡️ Core Hardening & CI/CD Resilience
- **Centralized Routing**: Streamlined directory routing into `SecurityUtils::resolvePageName` to centralize path security.
- **IPv6 Security**: Hardened bot-detection algorithms with robust IPv6 bitmask operations.
- **CI/CD Resiliency**: Ensured GitHub Actions pipelines gracefully handle missing tokens or specific environment parameters.

---

## The v3.6 Journey: A Case Study

In 2026, **CMSForNerd v3.6** represents the pinnacle of "Radically Simple" engineering. This version finalizes the
transition into a fully synchronized, AI-native developer's laboratory.

### 1. The PHP 8.4/9 foundation

Refactored the 2005 foundation into PHP 8.4+ classes with PHP 9 readiness.
- **Strict Types**: Every file uses `declare(strict_types=1);`.
- **State Management**: **Zero global variable usage** (enforced by `Registry.php`).

### 2. Standards & Compliance

- **PSR-12**: Automated linting and formatting (verified by `composer compliance`).
- **Zero-Debt Docs**: 100% Markdown lint compliance reached on March 30, 2026.

---

## [3.6.0] - 2026-03-30 (Semantic Evolution)

**"Zero-Global & AI-Ready Baseline."**

### 🧠 Semantic Intelligence

- **Content Sniffer**: Automates the promotion of pages to `@type: TechArticle` or `Course` based on `<?php` or
  `<code>` detections.
- **CmsContext v3.6**: Enhanced with `schemaType` and `baseUrl` for absolute semantic fidelity.

### 🏗️ Global-Free Registry

- **Registry Design**: Centralized static store for laboratory tokens, replacing the legacy `global` scope.
- **Bootstrap v3.6**: Completely refactored `includes/bootstrap.php` for immutable state initialization.

### 🛡️ Documentation Hardening

- **Zero-Debt Compliance**: Global remediation of structural debt in `README.md`, `CHANGELOG.md`, and the `docs/` suite.

---

> "Modernization without loss of simplicity." — *Harisfazillah Jamel & Google Gemini, 2026.*


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-27 (Planned Handover Date)*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
