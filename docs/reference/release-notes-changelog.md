---
okf_version: 0.1
type: reference
title: "📋 Reference: CmsForNerd Release Notes and Changelog"
description: "Full release history for CmsForNerd v4, covering architectural modernizations, security enhancements, and feature additions."
resource: "file:///docs/reference/release-notes-changelog.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [changelog, release-notes, version-history, php84, modernization]
---

# 📋 Reference: CmsForNerd Release Notes and Changelog

This document tracks all notable changes, security enhancements, and architectural modernizations across major releases of CmsForNerd.

---

## 🚀 Version 4.3.0 (Current Stable Release)

### Key Highlights:
* **PHP 8.4 Architecture Upgrade:** Adopted PHP 8.4 property hooks, constructor property promotion, and strict scalar types across the codebase.
* **Zero-Global Architecture:** Eliminated legacy `$GLOBALS` dependencies by introducing `\CmsForNerd\Registry` key-value store and immutable `\CmsForNerd\CmsContext`.
* **Diátaxis User Manual:** Added comprehensive local user manual covering WSL2 + AlmaLinux 10 + Rootless Podman 5+ workflows.
* **Glassmorphism Theme UI & AMP Acceleration:** Consolidated styles into global stylesheets (`style.css` and `amp.css`) providing high-fidelity Glassmorphism UI with dual Google AMP mobile rendering.
* **OWASP Hardening:** Implemented per-request Content Security Policy nonces, timing-attack resilient CSRF tokens, and path traversal defenses.
* **3-Tier Performance Caching:** Introduced multi-tier caching (In-Memory, APCu, Disk) in `PerformanceUtils` with conditional HTTP 304 ETag support.
* **Omni-Documentation Synchronization:** Fully synchronized all documentation layers across `START-HERE.md`, `SUMMARY.md`, `mkdocs.yml`, `llms.txt`, and `.gitbook.yaml`.

---

## 📜 Version 4.2.0

* Introduced automated sitemap suite generator (`tools/generate-seo-files.php`).
* Added static HTML baking script (`tools/bake-static-pages.php`) for GitHub Pages deployment.
* Integrated Pest PHP test runner replacing legacy PHPUnit setups.

---

## 📜 Version 4.0.0

* Complete architecture redesign transitioning CmsForNerd from PHP 7.x legacy structures to flat-file Modern PHP.

---

*Deep State of Mind (DSOM) For My AI Protocol | CmsForNerd Release Notes & Version History | Harisfazillah Jamel (LinuxMalaysia)*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
