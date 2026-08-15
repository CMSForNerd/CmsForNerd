---
okf_version: 0.1
type: reference
title: "📋 API Reference: SecurityUtils Class"
description: "Complete reference for SecurityUtils providing XSS escaping, CSRF protection, CSP nonces, session hardening, and page discovery."
resource: "file:///docs/reference/security-utils-api.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [security-utils, xss, csrf, csp-nonce, api-reference]
---

# 📋 API Reference: SecurityUtils Class

The `\CmsForNerd\SecurityUtils` static utility class provides security mechanisms for input sanitization, XSS escaping, CSRF token validation, HTTP header hardening, and dynamic page discovery.

---

## 🏛️ Public Static Methods

### `SecurityUtils::resolvePageName(string $input): string`
Sanitizes page name inputs to prevent Path Traversal (CWE-22) attacks. Strips path separators (`/`, `\`), null bytes, and non-alphanumeric characters.

```php
$page = SecurityUtils::resolvePageName($_GET['page'] ?? 'index');
```

---

### `SecurityUtils::escapeHtml(string $value): string`
Escapes HTML special characters using `ENT_QUOTES | ENT_SUBSTITUTE` with UTF-8 encoding to prevent Cross-Site Scripting (XSS).

```php
echo SecurityUtils::escapeHtml($userInput);
```

---

### `SecurityUtils::generateNonce(): string`
Generates a cryptographically secure 128-bit random base64-encoded string for Content Security Policy nonces.

```php
$nonce = SecurityUtils::generateNonce();
```

---

### `SecurityUtils::getCsrfToken(): string`
Retrieves or initializes the active session's CSRF protection token.

```php
$token = SecurityUtils::getCsrfToken();
```

---

### `SecurityUtils::validateCsrfToken(string $token): bool`
Validates a submitted CSRF token using `hash_equals()` to prevent timing attacks.

```php
if (!SecurityUtils::validateCsrfToken($_POST['csrf_token'] ?? '')) {
    // CSRF mismatch
}
```

---

### `SecurityUtils::sendSecurityHeaders(?string $nonce = null): void`
Emits OWASP-compliant HTTP security headers including `Content-Security-Policy`, `X-Content-Type-Options`, `X-Frame-Options`, `X-XSS-Protection`, `Referrer-Policy`, and `Permissions-Policy`.

```php
SecurityUtils::sendSecurityHeaders($nonce);
```

---

### `SecurityUtils::getDiscoveredPages(string $rootDir): array`
Scans the root directory for page controller files (`*.php`) and returns a sorted array of page names for navigation rendering.

```php
$pages = SecurityUtils::getDiscoveredPages(__DIR__);
```

---

*Deep State of Mind (DSOM) For My AI Protocol | SecurityUtils API Reference Specification | Harisfazillah Jamel (LinuxMalaysia)*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
