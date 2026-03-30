# 📜 Modernization History

Tracking the evolution of **CMSForNerd** from a legacy flat-file system to a modern educational powerhouse.

## Strategic Phases

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
- **Path Sovereignty**: Enforced absolute URI resolution (`baseUrl`) site-wide for subdirectory support.

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