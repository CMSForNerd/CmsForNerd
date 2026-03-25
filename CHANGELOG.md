# CHANGELOG

All notable changes to this project are documented in this file.
Entries are grouped by date (most recent first).

---
## [3.5.1] - 2026-03-25 (PWA Engine & CSP Hardening)

**"Zero-Refresh Navigation & Absolute Security Alignment."**

### 📱 PWA & Mobile Architecture
* **SPA Hybrid Router**: Introduced an advanced vanilla JavaScript history interceptor (`router.js`) that detects `XMLHttpRequest` configurations and dynamically hydrates the `<main>` payload without triggering global server layouts.
* **Service Worker Resilience**: Integrated a `Stale-While-Revalidate` offline casing profile (`sw.js`).
* **Instant History (bfcache)**: Optimized memory-restoration by severing active networking paths via `AbortController` bound to the `pagehide` event lifecycle.

### 🛡️ CSP Multi-Directive Synchronization
* **AMP Worker Autonomy**: Overrode restrictive backend HTTP headers that were silencing web workers, implementing native `blob:` compatibility across `script-src`, `child-src`, and `worker-src`. Cloudflare Turnstile and AMP Component Worker executions are now unconditionally stable.

---
## [3.5.0] - 2026-03-14 (The "Zero-Global & State Sync" Milestone)

**"Global-Free Architecture & Persistent AI Symbiosis."**

### 🏗️ Architecture & Core Logic

* **Zero-Global Arch**: Completely eliminated the `global` keyword from all 30+ page controllers and the master `bootstrap.php` engine, opting exclusively for precise Dependency Injection via factory method (`createCmsContext`).
* **Dual-View Controller**: Implemented a mode-switching engine in `pager.php` that routes users between `standard` and `amp` layouts based on URL parameters.
* **AMP Image Processor**: Added a real-time output buffer in the pager to transform standard `<img>` tags into valid `<amp-img>` tags with automatic aspect ratio handling.
* **Enhanced Bootstrap**: Updated `includes/bootstrap.php` to support localized laboratory settings and environment-specific constants without bleeding scope.

### 📱 Theme & UI (Mobile First)

* **Interactive Sidebar**: Created `themes/CmsForNerd/amp-sidebar.tpl` using the `amp-sidebar` component for zero-JS mobile navigation.
* **Active Navigation**: Developed a PHP-driven highlighting system to visually mark the current page within the sidebar.
* **Layout Hardening**: Added z-index management and "tap-fix" styles to ensure the mobile hamburger menu remains interactive on all devices.

### 🛡️ Security & Performance

* **CSP Nonce Synchronization**: Updated `includes/nav-helper.inc.php` to propagate unique nonces to all AMP-specific script tags.
* **CSS Size Guard**: Integrated an automated check that validates `amp.css` against the 75,000-byte AMP limit during rendering.
* **Strict Headers**: Hardened the Content Security Policy to explicitly allow the `cdn.ampproject.org` domain for scripts and connectivity.

---

## [3.4.0] - 2026-01-01 (The "Zero-Debt" Milestone)
**"Engineering Excellence & Static Analysis."**

### 🏗️ Architecture & Core Logic
- **Context Factory**: Refactored `CmsContext` instantiation into a central factory method `createCmsContext()` in `bootstrap.php` to ensure consistent state across the engine.
- **Immutable State**: Upgraded `CmsContext` to a `readonly` class to prevent runtime state corruption.
- **Installation v3.4**: Redesigned `installation.php` and `contents/installation-body.inc` to focus on professional deployment (Composer, PHPStan, and Server Hardening).

### 🛡️ Security & Quality Control
- **PHPStan Level 8**: Integrated **Static Analysis** as a mandatory requirement. Configured `phpstan.neon` to enforce strict type-safety and null-checks.
- **Zero-Debt Enforcement**: Added `composer analyze` and `composer lab-check` scripts to automate logic audits.
- **CSP Evolution**: Finalized the Nonce-tracking engine for Content Security Policy, ensuring all laboratory themes are XSS-resistant by default.
- **Strict Compliance**: All core files now pass the "Zero-Global" check.

---

## [3.3.0] - 2025-12-30 (The Developer's Laboratory)
**"Refining the Nerd Experience."**

### 🚀 PHP 8.4 Deep Integration
- **Property Hooks**: Integrated PHP 8.4 property hooks for dynamic metadata handling within the `CmsContext`.
- **Constructor Promotion**: Refactored core classes to use constructor property promotion for reduced boilerplate.
- **Runtime Badge**: Added dynamic PHP version detection and badges to the Home and Installation pages.

### 🐧 Environment Optimization
- **Cross-Platform Parity**: Improved setup logic for Windows (Herd) and Linux (Nginx/FPM) environments.
- **Laboratory Manual**: Expanded the "Nerd-Stack" recommendation table, including Google Antigravity and Firefox Developer Edition.
- **Badge System**: Introduced status badges for Strict Mode and Static Analysis readiness.

## 2025-12-27 — merged updates

Summary

- Documentation and AI guidance improvements.
- Added a small example page and a content partial.
- Minor include/test fixes were applied.
- A repository normalization commit (line endings and whitespace) was merged.

Files and artifacts changed

- `.github/copilot-instructions.md` — added/updated with repository-specific guidance for AI assistants and contributor rules.

- `about.php` — example entrypoint following the project's paired-entry + partial pattern.

- `contents/about-body.inc` — example content partial for `about.php`.

- `contents/history-body.inc` — history page updated with a "Recent updates (2025-12-27)" changelog section.

- `tests/AboutPageTest.php` — small PHPUnit test added/updated to validate the example page and partial.

- `includes/*` — minor fixes and whitespace cleanups (PSR-12 / phpcbf auto-fixes).

- Line endings normalization: many `*.php`, `*.inc`, and `*.md` files were converted from CRLF to LF to ensure cross-platform consistency.

Verification performed

- `composer install` run successfully; no dependency changes.

- `composer fix-style` (phpcbf) applied automatic fixes for whitespace / end-of-file problems.

- `composer compliance` (style + tests) executed and passed on merge and post-merge checks.

  - PHPUnit: OK (7 or 8 tests depending on pre/post small test changes)

Important notes and guidance

- Vendor files: the LF normalization touched some files under `vendor/` (test fixtures etc.).

  These changes are harmless but noisy in diffs. If you prefer, we can revert vendor
  changes to match upstream vendor packages and keep only project source files normalized.

- Reverting vendor normalization (optional): to revert the vendor files changed by the normalization commit
  while keeping project files normalized, run these commands locally:

```powershell
# Reset vendor to the current remote state (safe, non-destructive to your working tree)
git fetch origin --depth=1
git checkout origin/master -- vendor
git add -A vendor
git commit -m "chore(vendor): revert vendor normalization to upstream state"
git push
```

- Rollback a specific merged branch/PR: If you need to revert the merge that introduced these changes, create a revert commit using:

```powershell
git checkout master
git pull origin master
git revert -m 1 <merge-commit-sha>
git push
```

Developer checklist (what was done)

- [x] Created/updated `.github/copilot-instructions.md` with repository guidance.

- [x] Added example page `about.php` and `contents/about-body.inc`.

- [x] Added/updated `tests/AboutPageTest.php`.

- [x] Fixed PSR-12 issues with `phpcbf` and verified style/test compliance.

- [x] Normalized line endings for project source files to LF and committed the change.

If you want this entry shortened or moved into a release-specific section (for example, "v3.1.1"),
tell me which release/tag to use and I will update this file accordingly.

---

Generated: 2025-12-27

# Changelog: The Great Migration (v1.0 → v3.1)

All notable changes to **CMSForNerd** documenting the journey from PHP 4 (2005) to PHP 8.4 (2025).

---

## [3.1.1] - 2025-12-27 (Merged Updates)

### Summary
- Documentation and AI guidance improvements.
- Added example page `about.php` and partial `contents/about-body.inc`.
- Line endings normalization (CRLF to LF) for repository consistency.

---

## [3.1.0] - 2025-12-28 (The "Modern Nerd" Release)
**"Modernization without loss of simplicity."**

**"Modernization without loss of simplicity."**

### 🚀 The PHP 8.4 Foundation (Migration from v1.0 / PHP 4)
- **Engine Upgrade**: Complete refactor from PHP 4/5 functional style to **PHP 8.4** strict object-oriented patterns.
- **Strict Typing**: Every core file now enforces `declare(strict_types=1);`.
- **State Management**: Eliminated legacy global variables in favor of an immutable **`CmsContext`** object.
- **Modern Syntax**: Implemented **Property Hooks**, **Readonly Classes**, and **Constructor Property Promotion**.

### 🛡️ Security Hardening (Anti-Fragility)
- **RFC 9116**: Integrated `.well-known/security.txt` and a formal Security Policy.
- **CSP & Nonces**: Implemented Content Security Policy with dynamic nonces to block XSS.
- **Cloudflare Turnstile**: Added invisible bot protection for all forms.
- **Input Sanitization**: Introduced `SecurityUtils` to prevent Directory Traversal and SQLi.

### 🤖 AI & Semantic Stack
- **AI-Ready Architecture**: Structured content for Gemini/LLM consumption via `.github/copilot-instructions.md`.
- **Triple-Layer Metadata**: Integrated **Microdata (Schema.org)**, **JSON-LD**, and **RDF** into every page.
- **Static Baking (SSG)**: Developed GitHub Actions (`static-build.yml`) to "bake" PHP into ultra-fast static HTML.

### 🧪 Quality Assurance
- **PHPUnit 12**: Integrated the latest testing framework using **PHP 8 Attributes** (`#[Test]`) for test discovery.
- **Semantic Auditor**: Created `tools/audit-semantics.php` to automate compliance checks.
- **PSR-12**: Enforced universal coding standards via `phpcbf` and `phpcs`.

---

## [2.0.0] - 2025-12-20 (Intermediate Architecture)
- **CSS Grid**: Replaced 2005-era float layouts.
- **Lab Manual**: Created initial training modules for students.

---

## [1.0.0] - 2005-01-10 (The Original Foundation)
- **Initial Release**: A radically simple, flat-file CMS built for PHP 4.
