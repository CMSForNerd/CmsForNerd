# 🚀 CMSForNerd Project Rules (Updated v3.6.0)

When generating or refactoring code for **CmsForNerd v3.6** and above, you **MUST** follow these rules:

### 1. The "Zero-Global" Mandate
* **Absolute Compliance**: The `global` keyword and `$GLOBALS` array are **STRICTLY PROHIBITED** in all `includes/`, `src/`, and controller files.
* **The Registry Pattern**: Use `\CmsForNerd\Registry` (static container) for theme-wide tokens (nonces, theme names) and the immutable `CmsContext` for request-specific state.
* **Verification**: Every refactor must pass the `composer check-strict` audit.

### 2. Semantic Content Autodetection (v3.6)
* **The Content Sniffer**: The engine now automatically classifies content. Do not manually hardcode `@type` in controllers.
* **TechArticle Promotion**: If a `-body.inc` fragment contains `<?php`, `<code>`, or `<pre>`, the `schemaType` MUST be dynamically upgraded to `TechArticle`.
* **Educational Metadata**: Ensure `dependencies` and `proficiencyLevel` are injected into the JSON-LD block for technical modules.

### 3. PSR-12 & PHP 8.4 Strictness
* **Strict Typing**: Every file **MUST** start with `declare(strict_types=1);`.
* **Property Hooks & Promotion**: Use PHP 8.4 property hooks for dynamic metadata and constructor property promotion for all DTOs/Value Objects.
* **Indentation**: **4 spaces** only. Tabs are a violation of the "Zero-Debt" policy.

### 4. Architectural Structure: "Pair Logic"
* **Controller (`.php`)**: Handles input validation and context hydration.
* **Fragment (`-body.inc`)**: Contains pure HTML/PHP for UI.
* **Dual-View Rule**: One fragment serves both Standard and AMP views. Use `pager.php` for the ⚡ AMP transformation.

### 5. Theme v4.0: Glassmorphism Standard
* **Visual Fidelity**: Use CSS Variables (`--lab-glass-bg`, `--lab-glass-border`, `--lab-blur`) for all UI surfaces.
* **Backdrop Filter**: Apply `backdrop-filter: blur(var(--lab-blur)) saturate(180%);` to sidebars and headers.
* **Contrast Guard**: Ensure a minimum 4.5:1 contrast ratio for text sitting on "Glass" surfaces.

### 6. PWA & bfcache Sovereignty
* **AbortController**: All async fetch routines in `router.js` MUST use an `AbortController`.
* **bfcache Eligibility**: Bind `.abort()` to the `pagehide` event to ensure the page remains eligible for memory caching.
* **Absolute URI**: Always use `$ctx->baseUrl` for assets to support deep-nested subdirectories.

### 7. Security & CSP
* **Nonce Synchronization**: Every script tag (including JSON-LD) **MUST** carry the `nonce="<?= $ctx->cspNonce ?>"`.
* **Safe Gateways**: Validate page names via `SecurityUtils::isValidPageName()`.

### 8. Automated Compliance (Phase 11)
* **Digital Sentry**: All code MUST pass the GitHub Actions `compliance.yml` workflow.
* **Zero-Debt Docs**: Maintain 100% Markdown hygiene in `docs/` using the rules in `lint-config.json`.

---

### 🧪 Verification Commands for the AI

1.  **Full Audit**: `composer lab-check` (Runs everything: PHPStan L8, PSR-12, Zero-Global check).
2.  **Style Fix**: `composer fix-style` (Runs `phpcbf` to align with PSR-12).
3.  **Semantic Test**: `php tools/audit-semantics.php --target=[file]`
4.  **Security Audit**: `composer audit`
