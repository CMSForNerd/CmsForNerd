---
okf_version: 0.1
type: documentation
title: "Project Changelog"
description: "Sovereign log tracking all features, fixes, and architectural upgrades in CMSForNerd."
resource: "file:///CHANGELOG.md"
timestamp: "2026-08-01T15:00:00Z"
---
# CHANGELOG

All notable changes to this project are documented in this file.
Entries are grouped by date (most recent first).

---

## [4.2.5] - 2026-08-01 (GitHub Pages Static Deployment Alignment)

### 📦 Static Baking & GitHub Pages Support
- **Jekyll Bypassing (`.nojekyll`)**: Updated `tools/bake-static-pages.php` to automatically generate an empty `.nojekyll` file at the root of the output static build directory, completely disabling Jekyll processing on GitHub Pages to prevent Liquid template syntax conflicts, directory exclusions (e.g., `.well-known`), and routing issues.
- **Comprehensive Guidebook**: Created `docs/GITHUB-PAGES-DEPLOYMENT-GUIDE.md` detailing the static baking pipeline, Jekyll processing risks, and step-by-step repository publishing settings.
- **Sitemap & Navigation Mapping**: Integrated the new guide across the four required layers (`docs/SUMMARY.md`, `mkdocs.yml`, `START-HERE.md`, `llms.txt`, and `.llms/index.md`).
- **Automated Validation**: Authored robust unit tests inside `tests/GithubPagesDeploymentTest.php` to verify successful generation of `.nojekyll` and proper documentation registrations.

## [4.2.4] - 2026-08-01 (HTML Microdata Structural Integration)

### 🏷️ SEO & Semantic Web
- **Semantic HTML Microdata**: Integrated `itemscope` and `itemtype` microdata attributes on dynamic AMP layout views (`pager.php`), utility portals (`offline.php`, `ujian-form.php`, `tools/sanity-check.php`), and static templates.
- **Privacy Metadata Coverage**: Covered all subdirectory privacy placeholder files (`index.php`/`index.html`) across 14 directories with paired microdata semantic attributes.
- **Compliance Unit Tests**: Deployed `testHtmlMicrodataPresence` in `tests/SecurityTest.php` to validate microdata presence, and synchronized all existing unit test compliance assertions to the active `2026-08-01` log timestamp.
- **SonarCloud Optimization**: Synced exclusions in `sonar-project.properties` and GHA build workflows to exclude unit tests and directory privacy files from false positive duplication metrics.

## [4.2.3] - 2026-08-01 (OWASP Top 10 Hardening & PHP Site Performance)

### 🛡️ Security Hardening
- **Secure Sessions**: Configured secure session controls in `SecurityUtils` with `SameSite = Strict`, `HttpOnly`, dynamic HTTPS `Secure` transport flag detection, and 30-minute periodic session ID regeneration to thwart fixation attacks.
- **CSRF Token Handshake**: Implemented timing-safe CSRF token lifecycle checking protecting all POST input forms.
- **OWASP Secure HTTP Headers**: Integrated dynamic response headers (HSTS, CSP, Referrer-Policy, Permissions-Policy, XSS-Protection, X-Frame-Options) and restricted unallowed HTTP methods (PUT, DELETE, OPTIONS).
- **Bot-Trap Defense**: Hardened `ujian-form.php` form with CSRF validation.

### ⚡ Site Performance & Caching
- **Zero-Dependency Page Cache**: Engineered `PerformanceUtils.php` implementing server-side page caching with smart modification-time invalidation checks.
- **Metadata Cache & Conditional Requests**: Cached scanned directory page lists inside `discovered_pages.json` and integrated browser conditional checks (`ETag`/`304`) to save bandwidth.
- **Worksheet Upgrade**: Deployed a brand-new styled interactive worksheet `lab-module6.php` and its body fragment.

## [4.2.2] - 2026-08-01 (PR Bot Synergy & Cross-Platform Scripting Governance)

### ⚙️ Governance & PR Workflow
- **Rule 25 & 26 Codification**: Formally added Rule 25 (PR & Bot Review Integration) and Rule 26 (Cross-Platform Standalone Scripting Mandate) in `AGENTS.md` and `.agents/AGENTS.md`.
- **Standalone Script Decoupling**: Refactored inline `composer.json` JSON/PowerShell script wrappers into standalone PHP tools (`tools/check-legacy-global.php` and `tools/check-strict-types.php`).
- **Standard Operating Procedure (SOP)**: Deployed `docs/governance/SOP-PULL-REQUEST-BOT-SYNERGY.md` outlining dual-platform workflows (GitHub/GitLab) and mapped it across `SUMMARY.md`, `mkdocs.yml`, `llms.txt`, and `START-HERE.md`.

## [4.2.1] - 2026-08-01 (Context Re-learning & Windows Shell Compliance)

### 🐛 Compatibility Fixes
- **PowerShell Escaping**: Refactored double-quotes and backslash characters in `composer.json` script hooks to restore compatibility across Windows PowerShell/CMD, Linux Bash, and macOS Zsh environments.
- **Cognitive Twin Re-Orientation**: Restored cognitive coherence using the 3-step Mechanical Boot Sequence and Spatial Memory Palace tracking.

## [4.2.0] - 2026-07-31 (Live Demo Site & MCP Access Documentation)

### 🌐 Live Deployment & LLM Access
- **Production Live Demo**: Integrated and verified reference links to the official production live demo site (`https://cmsfornerd.onrender.com/index.php`) in `README.md`, `START-HERE.md`, and `docs/RENDER-DEPLOYMENT-GUIDE.md`.
- **Context7 MCP Crawler**: Registered the official Context7 MCP crawler link (`https://context7.com/cmsfornerd/cmsfornerd/llms.txt?tokens=10000`) in `README.md`, `START-HERE.md`, `.llms/index.md`, and `llms.txt` to enable cognitive agent crawling.

## [4.1.9] - 2026-07-31 (Apache .htaccess & Docker Alignment)

### 🐋 Web Server & Container Alignment
- **Apache Rewrite Rules**: Removed incompatible directory configuration tags (`<DirectoryMatch>`, `<Directory>`) from `.htaccess` and replaced them with standard `mod_rewrite` rules to resolve 500 Internal Server Errors on Render.
- **Docker/Podman Port Expositions**: Exposed port 80 and chowned files specifically under `/var/www/html/data` inside `Dockerfile` and `Containerfile` to pass `DockerBuildTest` compliance.
- **Dockerignore Cleanup**: Removed duplicate separating comments in `.dockerignore` to satisfy uniqueness checks.

## [4.1.8] - 2026-07-30 (Render Cloud Blueprint Specification)

### 🚀 Render Blueprint & Containerization
- **Blueprint Spec (`render.yaml`)**: Implemented the official Render Blueprint specification at the root of the repository. Bound to Singapore (`singapore`) region with `Asia/Kuala_Lumpur` timezone environment configuration.
- **Secure Secret Handling (`sync: false`)**: Configured secret fields (`APP_SECRET`, `ADMIN_PASSWORD`) with `sync: false` to securely prompt administrators during deployment, keeping critical passwords out of Git/GitHub history.
- **Custom PHP 8.4 Apache Container (`Containerfile`)**: Engineered a lightweight, multi-stage custom Apache/PHP 8.4 `Containerfile` enabling dynamic mod_rewrite, security headers, and `.htaccess` overrides.
- **Multi-Layer Documentation**: Created a comprehensive guide `docs/RENDER-DEPLOYMENT-GUIDE.md` and registered it in `SUMMARY.md`, `mkdocs.yml`, `START-HERE.md`, and `llms.txt`.

## [4.1.7] - 2026-07-30 (Omni-Documentation & LLM Indexing)

### 🤖 LLM & Cognitive Agent Indexing
- **LLM Crawl Standard (`llms.txt`)**: Integrated `llms.txt` at the repository root containing architectural guidelines, standards, and structured references to optimise LLM indexing.
- **Onboarding Roadmap (`START-HERE.md`)**: Created `START-HERE.md` at the repository root mapping the 12 critical entry points of the project to facilitate developer and agent onboarding.
- **Sovereign Directory Breakdown (`.llms/index.md`)**: Created `.llms/index.md` specifying deep state flow details, immutability, Zero-Global architecture, and Pair Logic layout specifications.
- **MkDocs Integration (`mkdocs.yml`)**: Designed and verified `mkdocs.yml` mapping out navigation hierarchy for over 50 documentation pages.
- **Digital Signatures**: Processed all modified/created configuration and documentation files (.md, .sh, .ps1, .yml, .yaml) via `dsom-signature-injector` to append/prepend digital signatures.


## [4.1.6] - 2026-07-29 (Root Workspace Cleanliness Mandate)

### 🧹 Root Workspace Cleanliness
- **Workspace Organization**: Cleaned up the repository root directory by moving all unrelated markdown documentation files into the `docs/` folder to maintain a pristine project root.
- **Critical Entry Points**: Retained only core configuration files and critical entry-point documents (`README.md`, `CHANGELOG.md`, `SUMMARY.md`, `AGENTS.md`, `SECURITY.md`, `CONTRIBUTING.md`) in the root.
- **Duplicate Removal**: Deleted duplicate identical uppercase files (`DIRECTORY_SECURITY.md`, `DOCS_REQUIREMENTS.md`) inside `docs/` to eliminate redundancy and maintain a single source of truth.
- **Reference Updates**: Updated all internal documentation and sitemap links across `README.md` and `SUMMARY.md` to map cleanly to their new paths inside the `docs/` Palace.
- **EOD Memory Consolidation**: Completed End-of-Day procedures including active task tracking, walkthrough updates, and episodic record preservation.


## [4.1.5] - 2026-07-27 (Planned Handover Date) (DSOM Node Bootstrap & OpenSCAP Compliance Hardening)

### 🚀 DSOM Node Bootstrap & OS Baseline Playbook
- **Dual-Play Identity Bootstrapping**: Implemented a transition playbook `playbooks/bootstrap_node.yml` that handles Rootful identity bootstrap (Play 1) followed by Sovereign OS Baseline configuration (Play 2).
- **Sanitized Inventory Blueprint**: Created `inventory/hosts.example.yml` using `localhost` and private IP mappings to securely share configurations without leaking actual server coordinates.

### 🛡️ Dynamic OpenSCAP Auditing & Remediation
- **Latest SCAP Security Guide Retrieval**: Integrated tasks to query and download the latest SCAP guide ZIP dynamically via the GitHub API.
- **CIS Level 2 Auditing**: Configured automatic `oscap xccdf eval` against CIS Level 2 Server profile for Ubuntu 24.04 and RHEL/AlmaLinux systems.
- **OVAL Vulnerability Scanning**: Implemented system patch verification using Canonical's official OVAL streams.
- **Auto-Hardening Remediation**: Automatically generates and executes OpenSCAP-native bash remediation scripts to harden the target VM or node.
- **Unified Security Compliance Report**: Generates a unified report `SECURITY_AUDIT_REPORT.md` compiling both Lynis hardening index and OpenSCAP compliance scores.

## [4.1.4] - 2026-07-27 (Planned Handover Date) (Podman 5 Native Migration & Google Jules Ubuntu 26.04 Setup)

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

## [4.1.3] - 2026-07-27 (Planned Handover Date) (Feed Sitemaps & Certificate Hardening)

### 🛡️ Security Hardening
- **Dynamic XSS Mitigation**: Fully escaped standalone sitemaps, RSS feeds, and student certificate parameter outputs against XSS.
- **Host Header Poisoning Defense**: Centralised host-header base URL resolution into `SecurityUtils::getSafeBaseUrl()` to remove injection.
- **Centralised Page Scanning**: Created `SecurityUtils::discoverPages()` to parse content fragments, resolving logic duplication.
- **Restrictive CSP Headers**: Deployed robust default Content Security Policies for standalone sitemap generators.

### 🧪 Validation & Automated Testing
- **Security Unit Testing**: Added targeted test cases in `tests/SecurityTest.php` verifying traversal block lists and IPv6 bitmasks.
- **Compliance Audit**: Validated codebase readiness via `composer lab-check` yielding zero static analysis errors.

### 📘 Documentation Sync
- **Palace Protection Guide**: Created `docs/xss-protection-guide.md` tracking all secure practices.
- **Sitemap & SEO Updates**: Revised sitemap manuals to document dynamic paired page logic and host security controls.

## [4.1.0] - 2026-07-26

**Deep State of Mind Integration**

### 🧠 Deep State of Mind (DSOM) Core

- **Workspace Pluralisation**: Successfully migrated the `.agent` workspace to `.agents` plural.
- **Sovereign Rulebook**: Integrated the core rulebook (`AGENTS.md`) enforcing Zero-Global memory, OKF, and Sovereign Signatures.
- **Sovereign Agent Skills**: Deployed all 25 OKF-compliant sovereign skills inside `.agents/skills/`.
- **Spatial Memory Framework**: Configured the Sovereign Markdown Palace Wings and Registry under `.agents/brain/`.

### 🛠️ Tooling & Validation Suite

- **DSOM Tooling Suite**: Deployed high-fidelity scripts (`eod-palace.sh`, `git-ritual.sh`, etc.) to the `tools/` directory.
- **Unified Validation**: Overwrote and merged `tools/audit-pre-flight.sh` to include both PHP 8.4/8.3 environment checks and DSOM.
- **Quality Assurance**: Verified structural and syntactic correctness of the repository using `composer lab-check` passing Level 8 analysis.

## [4.0.0] - 2026-07-26 (Production Stable)

### 🐳 Containerization & Orchestration

- **Rootless Podman Automation**: Introduced secure Ansible playbooks for zero-downtime, rootless container deployment (Nginx & PHP-FPM 8.4).
- **Rootless Port-Binding Configuration**: Configured unprivileged host port-binding via sysctl (enabling unprivileged host-wide binding to port 80), SELinux mapping (`:Z`), and automatic firewall routing (UFW/Firewalld).
- **Orchestration Docs**: Deployed comprehensive deployment guide (`ANSIBLE_PODMAN_GUIDE.md`) to streamline rapid environment spin-ups.

### 🎨 Design & Accessibility

- **Adaptive Dark Mode Contrast**: Implemented responsive CSS overrides and custom variables (`--lab-info-bg`, `--lab-warning-bg`, etc.) globally.
- **Contrast Remediation**: Permanently fixed white-text-on-white-background legibility bugs in all laboratory guidelines and manuals.

### 🛡️ Security & Core Engine

- **Centralized Page Routing**: Extracted redundant request routing logic to `SecurityUtils::resolvePageName` for centralized defense.
- **Robust IPv6 Anti-DoS**: Hardened trusted bot-detection systems by implementing secure IPv6 bitmask logic for IP address verification.

### 🛠️ Quality Control & CI/CD

- **Fault-Tolerant CI/CD**: Hardened GitHub Actions checks to bypass SonarCloud gracefully when token credentials are not present.
- **Vendor Isolation**: Optimized static analysis (`phpcs` and `phpstan` rulesets) to strictly ignore `/vendor/` dependencies.

---

## [4.0.0-alpha] - 2026-03-30 (High-Fidelity & Zero-Debt)

### 🎨 Glassmorphism & UI

- **Phase 11 Compliance**: Integrated high-fidelity glassmorphism CSS tokens across standard and AMP views.
- **Visual Parity**: Achieved 100% color token synchronization between core engine and UI Audit Kit.

### 🛡️ Documentation & Records

- **Global Audit**: Conducted a comprehensive modernization of 40+ Markdown files (`docs/`, root, and `technical-resources/`).
- **Standardization**: Updated all laboratory manuals, guides, and security policies to reflect the v4.0.0 "Zero-Global" baseline.
- **History Synchronization**: Finalized records in `HISTORY.md` and `RELEASE_NOTES.md` to preserve the modernization journey.

## [3.6.0] - 2026-03-30 (Semantic Evolution & Zero-Global)

### 🧠 Semantic Architecture (v3.6)

- **Centralized Intelligence**: Refactored `CmsContext` to include a `readonly schemaType` property.
- **Automated Content Sniffer**: Implemented detection logic in `includes/bootstrap.php` that scans body fragments for
  technical signatures (`<?php`, `<code>`) to dynamically classify content as `TechArticle`, `Course`, or `WebPage`.
- **Zero-Global Registry**: Created `src/Registry.php` and refactored the bootstrap layer to eliminate all `global`
  keywords, reaching 100% architectural compliance.

### 🛡️ Documentation Hardening

- **Zero-Debt Compliance**: Conducted a global audit and remediation of all Markdown artifacts (`README.md`, `docs/`).
- **Lint Resolution**: Resolved over 100 structural violations (`MD013`, `MD022`, `MD024`, `MD032`, `MD036`) across the
  entire repository knowledge base.

### 🛠️ Technical Parity & Quality Control

- **Test Suite Alignment**: Synchronized PHPUnit and Pest test fixtures with the new `CmsContext` constructor signature.
- **Type-Safety Hardening**: Resolved surgical typing regressions in `includes/nav-helper.inc.php` to maintain absolute
  PHPStan Level 8 compliance.
- **PHPStan Optimization**: Increased static analysis memory limit to `512M` to prevent crashes during laboratory audits.

## [3.6.0] - 2026-03-15

### Added
- **SECURITY.md**: Implemented formal security policy defining maintenance stance.
- **PHP 8.4 Support**: Updated environment requirements to PHP 8.4.18.
- **GPG Keyring**: Transitioned to trusted.gpg.d standard.

### Changed
- **Upstream Sync**: Fully merged core engine logic from upstream master.
- **Dependencies**: Updated vendor baseline via Composer.

### Fixed
- **History Divergence**: Resolved legacy vendor conflicts via strategic reset.

---

## [3.5.9] - 2026-03-30 (EOD Stabilization & Tagging)

> "State of Mind Snapshot for Laboratory Review."

### 🧠 Cognitive Sync

- **Brain Artifacts**: Synchronized all `.agents/brain/` artifacts with the latest session intelligence.
- **Release Baseline**: Established `v3.5.9` as the official "Nerd-Lab" baseline for the end of March 2026.

---

## [3.5.8] - 2026-03-30 (JSON-LD Structured Data Audit)

### 🤖 AI-Native Optimization

- **JSON-LD Verification**: Formally audited the structured data engine in `common-headertag.inc`.
- **Dynamic Type-Switching**: Verified that the CMS accurately identifies `Course` vs `WebPage` schemas for enhanced
  indexing.

---

## [3.5.7] - 2026-03-26 (Laboratory & UI Stabilization)

> "Advanced Diagnostics & Workbox Caching."

### 🧪 Laboratory & UI Audit

- **UI Audit Kit**: Introduced `ui-kit.php` as a technical testing ground for CSS tokens, glassmorphism, and contrast levels.
- **AMP Acceleration Guide**: Deployed `amp-acceleration.php` to document the 75KB CSS budget and AMP-bind state management.
- **Absolute Path Security**: Synchronized `CmsContext` to mandate `baseUrl` resolution, fixing asset loading in nested PWA areas.

### 📱 PWA Performance (Workbox)

- **Workbox Migration**: Upgraded `sw.js` from vanilla JS to **Workbox v6**, implementing Stale-While-Revalidate strategies.
- **Offline Resilience**: Pre-cached `offline.php` via Workbox to ensure reliable fallback during network failures.

---

## [3.5.1] - 2026-03-25 (PWA Engine & CSP Hardening)

> "Zero-Refresh Navigation & Absolute Security Alignment."

### 📱 PWA & Mobile Architecture

- **SPA Hybrid Router**: Introduced an advanced vanilla JavaScript history interceptor (`router.js`) that detects
  `XMLHttpRequest` configurations and dynamically hydrates the `<main>` payload without triggering global server layouts.
- **Service Worker Resilience**: Integrated a `Stale-While-Revalidate` offline casing profile (`sw.js`).
- **Instant History (bfcache)**: Optimized memory-restoration by severing active networking paths via `AbortController` bound
  to the `pagehide` event lifecycle.

### 🛡️ CSP Multi-Directive Synchronization

- **AMP Worker Autonomy**: Overrode restrictive backend HTTP headers that were silencing web workers, implementing native
  `blob:` compatibility across `script-src`, `child-src`, and `worker-src`. Cloudflare Turnstile and AMP Component Worker
  executions are now unconditionally stable.

---

## [3.5.0] - 2026-03-14 (The "Zero-Global & State Sync" Milestone)

> "Global-Free Architecture & Persistent AI Symbiosis."

### 🏗️ Architecture & Core Logic

- **Zero-Global Arch**: Completely eliminated the `global` keyword from all 30+ page controllers and the master
  `bootstrap.php` engine, opting exclusively for precise Dependency Injection via factory method (`createCmsContext`).
- **Dual-View Controller**: Implemented a mode-switching engine in `pager.php` that routes users between `standard` and
  `amp` layouts based on URL parameters.
- **AMP Image Processor**: Added a real-time output buffer in the pager to transform standard `<img>` tags into valid
  `<amp-img>` tags with automatic aspect ratio handling.
- **Enhanced Bootstrap**: Updated `includes/bootstrap.php` to support localized laboratory settings and environment-specific
  constants without bleeding scope.

### 📱 Theme & UI (Mobile First)

- **Interactive Sidebar**: Created `themes/CmsForNerd/amp-sidebar.tpl` using the `amp-sidebar` component for zero-JS
  mobile navigation.
- **Active Navigation**: Developed a PHP-driven highlighting system to visually mark the current page within the sidebar.
- **Layout Hardening**: Added z-index management and "tap-fix" styles to ensure the mobile hamburger menu remains
  interactive on all devices.

### 🛡️ Security & Performance

- **CSP Nonce Synchronization**: Updated `includes/nav-helper.inc.php` to propagate unique nonces to all AMP-specific script tags.
- **CSS Size Guard**: Integrated an automated check that validates `amp.css` against the 75,000-byte AMP limit during rendering.
- **Strict Headers**: Hardened the Content Security Policy to explicitly allow the `cdn.ampproject.org` domain for scripts.

---

## [3.4.0] - 2026-01-01 (The "Zero-Debt" Milestone)

> "Engineering Excellence & Static Analysis."

### 🏗️ Architecture & Core Logic

- **Context Factory**: Refactored `CmsContext` instantiation into a central factory method `createCmsContext()` in
  `bootstrap.php` to ensure consistent state across the engine.
- **Immutable State**: Upgraded `CmsContext` to a `readonly` class to prevent runtime state corruption.
- **Installation v3.4**: Redesigned `installation.php` and `contents/installation-body.inc` to focus on
  professional deployment (Composer, PHPStan, and Server Hardening).

### 🛡️ Security & Quality Control

- **PHPStan Level 8**: Integrated **Static Analysis** as a mandatory requirement. Configured `phpstan.neon` to
  enforce strict type-safety and null-checks.
- **Zero-Debt Enforcement**: Added `composer analyze` and `composer lab-check` scripts to automate logic audits.
- **CSP Evolution**: Finalized the Nonce-tracking engine for Content Security Policy, ensuring all laboratory
  themes are XSS-resistant by default.
- **Strict Compliance**: All core files now pass the "Zero-Global" check.

---

## [3.3.0] - 2025-12-30 (The Developer's Laboratory)

> "Refining the Nerd Experience."

### 🚀 PHP 8.4 Deep Integration

- **Property Hooks**: Integrated PHP 8.4 property hooks for dynamic metadata handling within the `CmsContext`.
- **Constructor Promotion**: Refactored core classes to use constructor property promotion for reduced boilerplate.
- **Runtime Badge**: Added dynamic PHP version detection and badges to the Home and Installation pages.

### 🐧 Environment Optimization

- **Cross-Platform Parity**: Improved setup logic for Windows (Herd) and Linux (Nginx/FPM) environments.
- **Laboratory Manual**: Expanded the "Nerd-Stack" recommendation table, including Google Antigravity and Firefox
  Developer Edition.

---

## [3.1.1] - 2025-12-27 (Repository Normalization)

> "The Great Migration from PHP 4 to PHP 8.4."

### 🚀 The PHP 8.4 Foundation

- **Engine Upgrade**: Complete refactor from PHP 4/5 functional style to **PHP 8.4** strict object-oriented patterns.
- **Strict Typing**: Every core file now enforces `declare(strict_types=1);`.
- **State Management**: Eliminated legacy global variables in favor of an immutable **`CmsContext`** object.

### 🛡️ Security Hardening

- **RFC 9116**: Integrated `.well-known/security.txt` and a formal Security Policy.
- **CSP & Nonces**: Implemented Content Security Policy with dynamic nonces to block XSS.
- **Cloudflare Turnstile**: Added invisible bot protection for all forms.

---

## [1.0.0] - 2005-01-10 (The Original Foundation)

- **Initial Release**: A radically simple, flat-file CMS built for PHP 4.


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-27 (Planned Handover Date)*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
