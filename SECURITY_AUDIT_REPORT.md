# CmsForNerd Security Audit Report (v3.5)

## 1. Enumeration Table

| File | Purpose | Auth Required? | Expected Role | Input Vectors Checked | Risk Level | Notes |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| `index.php` | Main entry / Routing | No | Guest | `QUERY_STRING` | Low | Uses `SecurityUtils` for safe routing. |
| `about.php` | About page | No | Guest | None | Low | Static content wrapper. |
| `history.php` | History page | No | Guest | None | Low | Static content wrapper. |
| `sitemap.php` | XML Sitemap | No | Guest | None | Low | Hardened headers and restricted CSP. |
| `ai-dev.php` | AI Dev Docs | No | Guest | None | Low | Documentation. |
| `ai-sop.php` | AI SOP Docs | No | Guest | None | Low | Documentation. |
| `lab-manual.php`| Lab Manual | No | Guest | None | Low | Documentation. |
| `lab-module1.php`| Lab Module 1 | No | Guest | None | Low | Worksheet. |
| `lab-module2.php`| Lab Module 2 | No | Guest | None | Low | Worksheet. |
| `lab-module3.php`| Lab Module 3 | No | Guest | None | Low | Worksheet. |
| `lab-module4.php`| Lab Module 4 | No | Guest | None | Low | Worksheet. |
| `lab-module5.php`| Lab Module 5 | No | Guest | None | Low | Worksheet. |
| `final-exam.php` | Final Exam | No | Guest | None | Medium | The "Break-Fix" challenge page. |
| `exam-answers.php`| Exam Answers | No (Now Yes) | Guest (Instructor) | `$_GET['key']` | **High** | Exposed sensitive answers to all. |
| `graduation.php` | Graduation | No (Now Yes) | Student | `$_GET['student_id']`| Medium | Accessible without completing modules. |
| `installation.php`| Setup Guide | No | Guest | None | Low | Documentation. |
| `linux-setup.php` | Linux Setup | No | Guest | None | Low | Documentation. |
| `windows-setup.php`| Windows Setup | No | Guest | None | Low | Documentation. |
| `ujian-form.php` | Turnstile Test | No | Guest | `$_POST` | Low | Protected by Turnstile. |

## 2. Executive Summary
The CmsForNerd codebase is a well-structured educational platform utilizing modern PHP 8.4 features. It emphasizes a "Zero-Global" architecture and implements strong security headers (CSP, X-Frame-Options, etc.). However, it lacks a formal authentication/authorization layer, which is critical for sensitive pages like exam answers.

## 3. Critical Issues
- **Authentication Bypass (Exam Answers):** The `exam-answers.php` file was publicly accessible, allowing anyone to view answers to the final exam.
    - *Status:* Remedied with a basic "Instructor Key" check.

## 4. High Issues
- **Lack of RBAC:** No session management or user roles are enforced across the system.
- **Exposure of Technical Files:** Direct access to `.inc` files in subdirectories was possible if server configuration (like `.htaccess`) failed.
    - *Status:* Remedied via `boot_security()` improvements.

## 5. Medium Issues
- **Incomplete Output Escaping:** The `baseUrl` in some templates was rendered without explicit escaping.
    - *Status:* Remedied via `htmlspecialchars()` in `common-headertag.inc`.
- **CSRF Risk:** While Turnstile protects POST requests, there is no per-session CSRF token mechanism for state-changing operations.

## 6. Low/Informational Issues
- **Hardcoded Instructor Key:** The current "Instructor Key" is hardcoded in the script, which is a risk if the source is leaked.
- **Directory Discovery:** Subdirectories use `index.php` to prevent listing, but some might still be discoverable.

## 7. Safe Zones
- **Core Routing:** `SecurityUtils::resolvePageName` provides robust protection against Path Traversal.
- **Headers:** Centralized security headers in `bootstrap.php` and `.htaccess` provide strong baseline protection.

## 8. Top 5 Prioritized Security Fixes
1. **Restrict Access to Answers:** Implement a gate for `exam-answers.php`. (DONE)
2. **Harden Fragment Protection:** Block direct URL access to `.inc` files. (DONE)
3. **Consistent Escaping:** Ensure all dynamic variables in templates are escaped. (DONE)
4. **Implement Session-based CSRF:** Move beyond just Turnstile for state protection.
5. **Secure Configuration:** Move secrets (keys, API tokens) to a secure, non-versioned config file.

## 9. Code Patch Recommendations (Examples)

### A. Escaping Base URL
**Before:**
```html
<meta itemprop="image" content="<?= $ctx->baseUrl ?>images/cmsfornerd-logo.png">
```
**After:**
```html
<meta itemprop="image" content="<?= htmlspecialchars($ctx->baseUrl, ENT_QUOTES, 'UTF-8') ?>images/cmsfornerd-logo.png">
```

### B. Direct Access Protection
**Before:**
```php
if ($currentFile === 'global-control.inc.php') {
    die("Direct access forbidden.");
}
```
**After:**
```php
if ($currentFile === 'global-control.inc.php' || str_ends_with($currentFile, '.inc')) {
    http_response_code(403);
    die("Access Denied.");
}
```
