---
okf_version: 0.1
type: reference
title: "📋 CmsForNerd Comprehensive Technical Reference"
description: "Factual specifications for PHP 8.4 system requirements, CmsContext API, Registry, SecurityUtils, PerformanceUtils, and Composer scripts."
resource: "file:///docs/reference/cmsfornerd-technical-reference.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [reference, system-requirements, cms-context, registry, security-utils]
---

# 📋 CmsForNerd Comprehensive Technical Reference

Technical reference specifications for CmsForNerd v4.3.0 core APIs and configurations.

---

## 🐘 1. System Requirements Specification

* **PHP Engine:** PHP 8.4.0+ (requires property hooks and readonly classes).
* **Mandatory Extensions:** `mbstring`, `json`, `openssl`, `zip`, `curl` (optional: `apcu`).
* **Web Servers:** Nginx 1.20+, Apache 2.4+, LiteSpeed, IIS 10+, or PHP built-in CLI server.
* **Operating Systems:** Linux (Ubuntu/Debian/AlmaLinux/RHEL), Windows 10/11, macOS 13+.

---

## 🏛️ 2. CmsContext Class & Factory API

The `\CmsForNerd\CmsContext` object provides shallow immutability (its readonly properties cannot be reassigned, though fields on the nested `botCache` object are updated dynamically by `includes/is_bot.php`).

```php
namespace CmsForNerd;

readonly class CmsContext {
    public \stdClass $botCache;

    public function __construct(
        public array $content,
        public string $themeName = 'CmsForNerd',
        public string $cssPath = 'themes/CmsForNerd/style.css',
        public array $dataFile = [],
        public string $scriptName = 'index',
        public string $baseUrl = 'http://localhost:8000',
        public string $schemaType = 'WebPage',
        public string $cspNonce = '',
        ?\stdClass $botCache = null
    ) {
        $this->botCache = $botCache ?? new \stdClass();
    }
}
```

Helper function `createCmsContext(...)` returns a new `CmsContext` instance rather than registering global state.

---

## 🔑 3. Registry Static Key-Value Store API

The `\CmsForNerd\Registry` static class replaces legacy `$GLOBALS` usage within PHP's single-threaded request execution model.

* `Registry::set(string $key, mixed $value): void` — Stores state in key-value store.
* `Registry::get(string $key, mixed $default = null): mixed` — Retrieves stored state.
* `Registry::has(string $key): bool` — Verifies key presence.
* `Registry::clear(): void` — Resets registry state.

---

## 🛡️ 4. SecurityUtils API Reference

* `SecurityUtils::resolvePageName(string $defaultFallback, string $invalidFallback = 'index'): string` — Sanitises page slugs and blocks path traversal (`../`).
* `SecurityUtils::escapeHtml(string $val): string` — Escapes special characters via `ENT_QUOTES | ENT_SUBSTITUTE`.
* `SecurityUtils::generateNonce(): string` — Generates 128-bit random base64-encoded CSP nonce.
* `SecurityUtils::generateCsrfToken(): string` & `validateCsrfToken(?string $token): bool` — Manages CSRF tokens.
* `SecurityUtils::sendSecurityHeaders(): void` — Emits OWASP response headers (`X-Content-Type-Options`, `X-Frame-Options`, `Referrer-Policy`, `Permissions-Policy`).
* `SecurityUtils::discoverPages(string $fragmentDir, string $rootDir): array` — Scans page fragments and controllers.

---

## ⚡ 5. PerformanceUtils API Reference

* `PerformanceUtils::getSourceMaxMTime(): int` — Computes source modification timestamp derived from `dirname(__DIR__)` across checked paths (`contents`, `themes/CmsForNerd`, `includes/bootstrap.php`) using Memory, APCu, and file cache tiers.
* `PerformanceUtils::handleConditionalRequest(int $maxMtime): void` — Emits ETags and returns `304 Not Modified` on cache hits (avoiding transferring the response body while requiring request processing and response headers).
* `PerformanceUtils::clearCache(): void` — Flushes all cache tiers.
* `PerformanceUtils::bakePage(string $url, string $outputPath): bool` — Compiles dynamic route to static HTML.

---

## 📦 6. Configuration & Composer Commands

* **`includes/global-control.inc.php`:** Configures `$siteTitle`, `$themeName`, and `$enableCspNonce`.
* **`.htaccess`:** Denies access to `.inc` files and enforces HTTP security headers (`Content-Security-Policy`, `X-Content-Type-Options`, `X-Frame-Options`).
* **Composer Commands:** `composer test`, `composer stan`, `composer cs`, `composer lab-check`, `composer seo-gen`, `composer bake`.

---

*CmsForNerd Technical Reference Specification | DSOM Protocol 2026 | Harisfazillah Jamel (LinuxMalaysia)*
