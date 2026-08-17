---
okf_version: 0.1
type: guide
title: "🛠️ Pest Testing, PHPStan Level 8, and Compliance Audits"
description: "Run Pest PHP unit tests, PHPStan Level 8 static analysis, and composer lab-check quality gates."
resource: "file:///docs/how-to/run-tests-static-analysis.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [testing, pest, phpstan, lab-check, quality-assurance]
---

# 🛠️ Pest Testing, PHPStan Level 8, and Compliance Audits

Execute quality assurance tools to ensure code correctness.

---

## 🧪 Commands

* **Pest Unit Tests:** `./vendor/bin/pest`
* **PHPStan Level 8:** `vendor/bin/phpstan analyze --level=8 src/ includes/`
* **PSR-12 Code Style:** `vendor/bin/phpcs --standard=phpcs.xml src/ includes/`
* **Full Laboratory Check:** `composer lab-check`

---

*CmsForNerd Quality Assurance Guide | DSOM Protocol 2026 | Harisfazillah Jamel (LinuxMalaysia)*
