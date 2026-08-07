---
okf_version: 0.1
type: documentation
title: "🤖 .llms/index: Comprehensive AI Agent Context Index"
description: "Hierarchical breakdown of the CmsForNerd v4.3.0 codebase, architecture, state flow, and files."
resource: "file:///.llms/index.md"
topics: [llm, indexing, architecture, code-structure, dsom]
timestamp: 2026-08-01T15:00:00Z
---
# 🤖 .llms/index: Comprehensive AI Agent Context Index

**Live Demo URL:** [CmsForNerd Live Demo](https://cmsfornerd.onrender.com/index.php)

**MCP Standard Index Context (Token Capped):** [Context7 MCP & LLM standard link](https://context7.com/cmsfornerd/cmsfornerd/llms.txt?tokens=10000)

This document is optimised for parsing by LLMs, cognitive agents, and NotebookLM indexing. It provides a technical
breakdown of the core engine, standard practices, file mappings, and architectural standards of CmsForNerd v4.3.0.

---

## 🛠️ Architectural Foundations & Standards

### 1. PHP 8.4 Strict Type Mandate
- Every PHP source file **MUST** begin with:
  ```php
  declare(strict_types=1);
  ```
- Strict typing applies to scalar type declarations at function/method call and return sites within the declaring file. The `declare(strict_types=1)` directive is file-scoped and affects type checking for calls made from that file.
- Note: PHP's strict_types mode still permits implicit int-to-float coercion (widening) as an exception even in strict mode.
- Expected to be compatible with the upcoming PHP 9.x specifications, subject to PHP 9.x specifications being finalised.

### 2. PHPStan Level 8 Enforcement
- The static analysis suite is run at the strict **Level 8** criteria.
- Run validation local check using:
  ```bash
  composer lab-check
  ```
- This ensures zero undefined parameters, precise type definitions for all properties, and total safety.

### 3. "Zero-Global" Architecture (`src/Registry.php`)
- Standard global states or the `global` and `$GLOBALS` keywords are completely forbidden.
- Shared settings and configuration properties (such as dynamic nonces, stylesheets, theme settings) are set and retrieved via the static `\CmsForNerd\Registry` class:
  ```php
  \CmsForNerd\Registry::set('key', $value);
  $value = \CmsForNerd\Registry::get('key', $default);
  ```

### 4. "Pair Logic" Pattern
- Separates controller logic from markup layout.
- The controller (e.g., `graduation.php` in root) defines page variables and metadata in an array:
  ```php
  $CONTENT = [
      'title' => 'Claim Your Certificate',
      'author' => 'Harisfazillah Jamel',
      ...
  ];
  ```
- The controller calls the bootstrap engine and pager.
- The UI presentation resides in a paired content file: `contents/graduation-body.inc`.

---

## ⚙️ Core Engine & Flow

### 1. Bootstrap Engine (`includes/bootstrap.php`)
- Sets strict HTTP headers (`X-Content-Type-Options`, `X-Frame-Options`).
- Automatically initializes the immutable **`CmsContext`** object via `createCmsContext()`.
- Incorporates an **Automated Semantic Content Sniffer** which parses the file contents to automatically assign a schema type (`Course` for lab modules, `TechArticle` for tech articles, `WebPage` for standard pages) to ensure 100% accurate search crawler indexing.

### 2. Immutable Context Object (`src/CmsContext.php`)
- Once instantiated inside `bootstrap.php`, state transitions are forbidden.
- Carries crucial parameters:
  - `$content`: Site/page metadata.
  - `$themeName`: Target directory within `/themes/`.
  - `$cssPath`: Active CSS path.
  - `$scriptName`: Current running script.
  - `$baseUrl`: Validated non-injectable base URL.
  - `$schemaType`: Automated microdata schema.
  - `$cspNonce`: Nonce for secure Content Security Policy.

### 3. Dual-View Router (`themes/CmsForNerd/pager.php`)
- Evaluates the query parameter: `$_GET['view']`.
- **Standard Layout:** Emits the standard HTML5 SPA-hybrid. Includes AJAX hydration handlers to serve content fragments without shell headers for instant page loads.
- **AMP Layout:** Emits high-performance Google AMP valid HTML.
  - Transforms all static images automatically from `<img>` to `<amp-img>` with custom dimensions.
  - Strips illegal inline layout style headers.
  - Restricts Content Security Policy via `'style-src none'`.

---

## 📂 Complete Documentation Map (Sovereign Markdown Palace)

The following index classifies the entire documentation palace of over 50 files for instant context loading.

### 1. General Setup & Manuals
- [README.md](../README.md): Master project summary.
- [docs/INSTALLATION.md](../docs/INSTALLATION.md): Complete guidelines for local and production deployments.
- [docs/lab-manual.md](../docs/lab-manual.md): Comprehensive curriculum lab handbook.
- [docs/template-guide.md](../docs/template-guide.md): The PHP-FPM theme structure and layout guide.
- [docs/GITHUB-PAGES-DEPLOYMENT-GUIDE.md](../docs/GITHUB-PAGES-DEPLOYMENT-GUIDE.md): Static baking and deployment guide on GitHub Pages (bypassing Jekyll).

### 2. Laboratory Learning Modules
- [docs/lab-module1.md](../docs/lab-module1.md): Module 1 — Architecture modernization.
- [docs/lab-module2.md](../docs/lab-module2.md): Module 2 — Coding standards (PSR-12) and static analysis.
- [docs/lab-module3.md](../docs/lab-module3.md): Module 3 — Defensive coding and security parameters.
- [docs/lab-module4.md](../docs/lab-module4.md): Module 4 — Unit testing using Pest and PHPUnit.
- [docs/lab-module5.md](../docs/lab-module5.md): Module 5 — Automated QA pipelines.

### 3. Security, Hardening & Defense
- [docs/security-policy.md](../docs/security-policy.md): Standard disclosures and security rules.
- [docs/xss-protection-guide.md](../docs/xss-protection-guide.md): Details Host Header validation and XSS prevention.
- [docs/directory-security.md](../docs/directory-security.md): Hardening instructions for hidden dotfiles and server configuration.
- [docs/bot-intelligence.md](../docs/bot-intelligence.md): Automatic search-bot crawler detection and lightweight routing.
- [docs/turnstile-protection.md](../docs/turnstile-protection.md): Cloudflare Turnstile CAPTCHA implementation.

### 4. Deep State of Mind (DSOM) Governance
- [AGENTS.md](../AGENTS.md): Lightweight discovery index.
- [.agents/AGENTS.md](../.agents/AGENTS.md): The absolute sovereign AI constitution.
- [START-HERE.md](../START-HERE.md): The master onboarding roadmap.
- [docs/governance/AI-INITIALIZATION-SEQUENCE.md](../docs/governance/AI-INITIALIZATION-SEQUENCE.md): Sequence mapping.
- [docs/governance/SOP-KNOWLEDGE-FIRST-DISCOVERY.md](../docs/governance/SOP-KNOWLEDGE-FIRST-DISCOVERY.md): Standard protocols.
- [docs/governance/PYTHON-UV-ENVIRONMENT-GUIDE.md](../docs/governance/PYTHON-UV-ENVIRONMENT-GUIDE.md): Strict python isolation.

---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-27*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
