# CHANGELOG

All notable changes to this project are documented in this file.
Entries are grouped by date (most recent first).

---

## [3.6.0] - 2026-03-30 (Semantic Evolution & Zero-Global)

### 🧠 Semantic Architecture (v3.6)

- **Centralized Intelligence**: Refactored `CmsContext` to include a `readonly schemaType` property.
- **Automated Content Sniffer**: Implemented detection logic in `includes/bootstrap.php` that scans body fragments for
  technical signatures (`<?php`, `<code>`) to dynamically classify content as `TechArticle`, `Course`, or `WebPage`.
- **Zero-Global Registry**: Created `src/Registry.php` and refactored the bootstrap layer to eliminate all `global`
  keywords, reaching 100% architectural compliance.

### 🛡️ Documentation Hardening

- **Zero-Debt Compliance**: Conducted a global audit and remediation of all Markdown artifacts (`README.md`, `docs/`).
- **Lint Resolution**: Resolved MD013, MD022, MD024, and MD032 violations across the documentation suite.

---

## [3.5.9] - 2026-03-30 (EOD Stabilization & Tagging)

**"State of Mind Snapshot for Laboratory Review."**

### 🧠 Cognitive Sync

- **Brain Artifacts**: Synchronized all `.agent/brain/` artifacts with the latest session intelligence.
- **Release Baseline**: Established `v3.5.9` as the official "Nerd-Lab" baseline for the end of March 2026.

---

## [3.5.8] - 2026-03-30 (JSON-LD Structured Data Audit)

### 🤖 AI-Native Optimization

- **JSON-LD Verification**: Formally audited the structured data engine in `common-headertag.inc`.
- **Dynamic Type-Switching**: Verified that the CMS accurately identifies `Course` vs `WebPage` schemas for enhanced
  indexing.

---

## [3.5.7] - 2026-03-26 (Laboratory & UI Stabilization)

**"Advanced Diagnostics & Workbox Caching."**

### 🧪 Laboratory & UI Audit

- **UI Audit Kit**: Introduced `ui-kit.php` as a technical testing ground for CSS tokens, glassmorphism, and contrast levels.
- **AMP Acceleration Guide**: Deployed `amp-acceleration.php` to document the 75KB CSS budget and AMP-bind state management.
- **Absolute Path Security**: Synchronized `CmsContext` to mandate `baseUrl` resolution, fixing asset loading in nested PWA areas.

### 📱 PWA Performance (Workbox)

- **Workbox Migration**: Upgraded `sw.js` from vanilla JS to **Workbox v6**, implementing Stale-While-Revalidate strategies.
- **Offline Resilience**: Pre-cached `offline.php` via Workbox to ensure reliable fallback during network failures.

---

## [3.5.1] - 2026-03-25 (PWA Engine & CSP Hardening)

**"Zero-Refresh Navigation & Absolute Security Alignment."**

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

**"Global-Free Architecture & Persistent AI Symbiosis."**

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

**"Engineering Excellence & Static Analysis."**

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

**"Refining the Nerd Experience."**

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

**"The Great Migration from PHP 4 to PHP 8.4."**

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
