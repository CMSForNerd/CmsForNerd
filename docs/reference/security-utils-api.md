---
okf_version: 0.1
type: reference
title: "📋 SecurityUtils Class API Reference"
description: "API reference for SecurityUtils input sanitization, XSS escaping, CSRF tokens, CSP nonces, and security headers."
resource: "file:///docs/reference/security-utils-api.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [security-utils, xss, csrf, csp-nonce, api-reference]
---

# 📋 SecurityUtils Class API Reference

Static methods provided by `\CmsForNerd\SecurityUtils`:

---

## 🛠️ Public Methods

* `resolvePageName(string $input): string` — Strips path traversal sequences (`../`, null bytes).
* `escapeHtml(string $val): string` — Escapes special characters via `ENT_QUOTES | ENT_SUBSTITUTE`.
* `generateNonce(): string` — Generates 128-bit random CSP nonce.
* `getCsrfToken(): string` & `validateCsrfToken(string $token): bool` — Manages CSRF tokens.
* `sendSecurityHeaders(?string $nonce = null): void` — Emits OWASP response headers.
* `getDiscoveredPages(string $rootDir): array` — Scans root page controllers.

---

*SecurityUtils API Reference Specification | DSOM Protocol 2026 | Harisfazillah Jamel (LinuxMalaysia)*
