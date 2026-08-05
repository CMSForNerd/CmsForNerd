---
okf_version: 0.1
type: skill
title: "CMS Security and Architectural Hardening Standards"
name: "cms-security-and-best-practices"
description: "Sovereign security mandates, Host Header injection defense, path traversal prevention, Zero-Global design, and security unit-testing."
topics: [security, hardening, path-traversal, zero-global, escape]
timestamp: 2026-08-01T09:00:00Z
---

# 🛡️ CMS Security and Architectural Hardening Standards

## Purpose
This skill defines core security policies, defensive coding standards, and execution constraints to prevent common vulnerabilities (e.g., Host Header Injection, Path Traversal, SSRF, DoS, and Global State leakage) across the CMS.

## When to use this skill
Execute this skill whenever modifying security utilities, implementing new API or page controllers, handling user-supplied input/IP ranges, or writing integration security tests.

## Guidelines & Best Practices

### 1. Host Header Injection Prevention
Never construct links or absolute URLs using the unvalidated `$_SERVER['HTTP_HOST']` variable directly. Always call the centralized utility:
```php
$baseUrl = \CmsForNerd\SecurityUtils::getSafeBaseUrl();
```
This ensures the base URL is properly validated against white-listed host domains or safe configurations.

### 2. Path Traversal & Controller Page Resolution
Prevent Directory Traversal attacks by utilizing the centralized page resolver:
```php
$page = \CmsForNerd\SecurityUtils::resolvePageName($queryParams, $defaultFallback);
```
- If the query string contains extra, non-page parameters (such as `view=amp`), the method must fallback to `$defaultFallback` (the controller's unique safe page name) rather than the generic `'index'`. This ensures controllers maintain and render their correct specialized page content.

### 3. Escape Output Rendering
Always use the custom escaping function instead of standard PHP `htmlspecialchars()` when outputting variables in templates and themes:
```php
echo \CmsForNerd\SecurityUtils::escapeHtml($variable);
```

### 4. Zero-Global Mandate
The core architecture enforces a "Zero-Global" system.
- The use of the `global` keyword and the `$GLOBALS` array is strictly prohibited in `includes/`, `src/`, and any controller file.
- All state management must be securely handled using `\CmsForNerd\Registry` or `CmsContext`.

### 5. Access Protection and Authorization
- **Technical File Isolation:** Direct browser access to technical includes (such as `.inc` files and `bootstrap.php`) is explicitly blocked with a `403 Forbidden` status by the `boot_security()` function in `includes/global-control.inc.php`.
- **Centralized Security Components:** Do not manually include Cloudflare Turnstile (`turnstile.php`) or bot detection (`is_bot.php`) in root PHP controllers. These are already centralized in `includes/bootstrap.php`.
- **Graduation Certificate Authorization:** Access to `graduation.php` requires a `student_id` query parameter for authorization.
- **Instructor Panel Protection:** Access to `exam-answers.php` is restricted to authorized instructors and validated against a `key` parameter (corresponds to `INSTRUCTOR_KEY` config, overridable by `CMS_INSTRUCTOR_KEY` environment variable).

### 6. IPv6 Binary Hardening
For high-performance, critical tasks such as constructing IPv6 bitmasks:
- Use a literal pre-allocated 16-byte binary string and modify bytes by index to prevent denial-of-service (DoS) or uncontrolled resource allocation.
- Hardened implementations must include bounds checks (e.g., checking `$fullBytes < 16`) before performing index modifications for partial bits.
- Ensure CIDR matching unit tests in `tests/SecurityTest.php` include non-multiple-of-8 prefixes (e.g., `/25`, `/121`) to thoroughly validate partial-byte bitmask boundary correctness.

### 7. HTML Microdata Verification
The security test suite in `tests/SecurityTest.php` scans dynamic `.php` pages and core includes to ensure HTML microdata (`itemscope` and `itemtype`) is present for semantic integrity. The root `index.html` file is explicitly excluded from this requirement, as it serves strictly as a static fallback redirect.


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-05*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
