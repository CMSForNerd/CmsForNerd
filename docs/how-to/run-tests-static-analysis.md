---
okf_version: 0.1
type: guide
title: "🛠️ How-To: Run Unit Tests, Static Analysis, and composer lab-check"
description: "How to run Pest PHP unit tests, PHPStan Level 8 static analysis, and composer lab-check compliance audits for CmsForNerd."
resource: "file:///docs/how-to/run-tests-static-analysis.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [testing, pest, phpstan, lab-check, quality-assurance]
---

# 🛠️ How-To: Run Unit Tests, Static Analysis, and composer lab-check

CmsForNerd enforces rigorous quality gates to maintain 100% code correctness and compliance.

---

## 🧪 1. Running Unit Tests with Pest PHP

CmsForNerd uses Pest PHP as its primary test runner.

To run the complete test suite:

```bash
./vendor/bin/pest
```

To run a specific test class:

```bash
./vendor/bin/pest tests/SecurityTest.php
```

To run tests with code coverage analysis:

```bash
./vendor/bin/pest --coverage
```

---

## 🔍 2. Static Analysis with PHPStan Level 8

Run PHPStan static analysis to verify strict typing, property hook safety, and eliminate undefined variable risks:

```bash
vendor/bin/phpstan analyze
```

For a clean build, PHPStan must output `[OK] No errors`.

---

## 🛡️ 3. PSR-12 Code Style Verification

Run PHP CodeSniffer to check formatting against PSR-12 standard rules:

```bash
vendor/bin/phpcs --standard=phpcs.xml src/ includes/ themes/ tests/
```

---

## 🎓 4. Full Compliance Audit: `composer lab-check`

Run the master laboratory compliance check script to execute all checks in sequence:

```bash
composer lab-check
```

This runs PHPStan Level 8, PSR-12 style analysis, OKF frontmatter compliance, and test suite execution in a single command.

---

*Deep State of Mind (DSOM) For My AI Protocol | CmsForNerd Pest PHP & PHPStan Audit Guide | Harisfazillah Jamel (LinuxMalaysia)*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
