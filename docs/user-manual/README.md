---
okf_version: 0.1
type: documentation
title: "📖 CmsForNerd Local User Manual"
description: "Master user manual for installing, configuring, running, and developing CmsForNerd locally using Diátaxis framework."
resource: "file:///docs/user-manual/README.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [user-manual, installation, diataxis, local-development, cmsfornerd]
---

# 📖 CmsForNerd Local User Manual

Welcome to the **CmsForNerd v4.3.0** Local User Manual. This manual provides a complete, step-by-step guide for developers, system administrators, and enthusiasts who want to install, run, and develop CmsForNerd locally on Windows, Linux, or containerized environments.

This documentation follows the **Diátaxis Documentation Framework**, categorising knowledge into four distinct quadrants based on user intent:

```
                  USER INTENT
        Learning                Practical
      +-----------------------+-----------------------+
      |      TUTORIALS        |     HOW-TO GUIDES     |
Acq.  |  (Learning-oriented   |  (Problem-oriented    |
      |   step-by-step)       |   task instructions)  |
      +-----------------------+-----------------------+
      |     EXPLANATION       |       REFERENCE       |
Und.  |  (Concept-oriented    |  (Information-oriented|
      |   deep-dives)         |   specifications)     |
      +-----------------------+-----------------------+
```

---

## 🎓 1. Tutorials (Learning-Oriented)

Step-by-step lessons designed for hands-on learning and onboarding:

* **[Quickstart Guide](../tutorials/quickstart-guide.md):** Get CmsForNerd up and running locally in under 5 minutes.
* **[WSL2 + AlmaLinux 10 + Podman Local Setup Guide](../tutorials/local-almalinux10-wsl2-podman-setup.md):** Prepare a production-grade Linux environment on Windows 11/10 using WSL2, AlmaLinux 10, and Rootless Podman 5+.

---

## 🛠️ 2. How-To Guides (Problem-Oriented)

Practical, task-focused guides for specific goals:

* **[Windows Setup with Laravel Herd](../how-to/install-windows-herd.md):** Install and run CmsForNerd on Windows using Laravel Herd and PHP 8.4.
* **[Native Linux Installation](../how-to/install-linux-native.md):** Install CmsForNerd on Ubuntu, Debian, or AlmaLinux using Nginx/Apache.
* **[Podman & Docker Container Deployment](../how-to/run-podman-docker-containers.md):** Run CmsForNerd in rootless Podman or Docker containers.
* **[Cloud Deployment & GitHub Pages](../how-to/deploy-cloud-render-github-pages.md):** Deploy to Render.com or static-bake HTML for GitHub Pages hosting.
* **[Creating Pages with Pair Logic](../how-to/create-manage-pages.md):** Step-by-step guide to building new pages using the PHP controller + body include pair logic.
* **[Theming & Navigation Customization](../how-to/customize-themes-navigation.md):** Customize themes, styling, layout fragments, and dynamic navigation.
* **[Flat-File Content Management](../how-to/manage-content-flatfiles.md):** Store, organize, and version control flat-file HTML content with Git.
* **[Security Hardening & Bot Protection](../how-to/configure-security-csrf-csp.md):** Configure CSP nonces, CSRF protection, and Turnstile bot defenses.
* **[SEO, Sitemaps & Structured Data](../how-to/configure-seo-sitemaps.md):** Generate dynamic XML sitemaps, RSS feeds, and Schema.org JSON-LD structured data.
* **[Testing & Quality Assurance](../how-to/run-tests-static-analysis.md):** Execute Pest unit tests, PHPStan Level 8 static analysis, and `composer lab-check`.

---

## 📋 3. Reference (Information-Oriented)

Factual descriptions, API signatures, and configuration specs:

* **[System Requirements](../reference/system-requirements.md):** PHP 8.4 runtime requirements, required extensions, web server configs, and OS support.
* **[CmsContext Class Reference](../reference/cms-context-api.md):** Immutable `CmsContext` state container and `createCmsContext()` factory method API.
* **[Registry Class Reference](../reference/registry-api.md):** Zero-Global `Registry` key-value store replacing global PHP state.
* **[SecurityUtils Reference](../reference/security-utils-api.md):** `SecurityUtils` API for XSS escaping, CSRF tokens, CSP nonces, and path sanitization.
* **[PerformanceUtils Reference](../reference/performance-utils-api.md):** `PerformanceUtils` API for 3-tier caching (Memory, APCu, Disk), max mtime, and static baking.
* **[Configuration & Composer Scripts](../reference/configuration-and-composer-scripts.md):** `global-control.inc.php`, `.htaccess`, and Composer audit automation.
* **[Release Notes & Changelog](../reference/release-notes-changelog.md):** Complete CmsForNerd v4 release history, security patches, and features.

---

## 🧠 4. Explanation (Concept-Oriented)

Architectural background and design rationale:

* **[Introduction & Philosophy](../explanation/introduction-and-philosophy.md):** Why CmsForNerd exists, database-free benefits, and Zero-Global principles.
* **[Zero-Global Architecture & Pair Logic](../explanation/zero-global-architecture-pair-logic.md):** Deep dive into request routing, Pair Logic, and CmsContext immutability.
* **[Dual-View & Google AMP Engine](../explanation/dual-view-amp-engine.md):** Architecture of single-source Standard HTML5 and Google AMP dual rendering.
* **[OWASP Security Hardening](../explanation/security-hardening-owasp.md):** Defense-in-depth security model: CSP nonces, session hardening, and path traversal mitigation.
* **[Three-Tier Caching & PWA Architecture](../explanation/three-tier-caching-pwa.md):** Multi-tier caching pipeline, conditional HTTP 304 responses, and offline PWA service workers.

---

*Deep State of Mind (DSOM) For My AI Protocol | CmsForNerd Local User Manual Overview | Harisfazillah Jamel (LinuxMalaysia)*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
