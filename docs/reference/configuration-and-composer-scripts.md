---
okf_version: 0.1
type: reference
title: "📋 Reference: Configuration Files, .htaccess, and Composer Scripts"
description: "Reference guide for global-control.inc.php settings, Apache .htaccess rules, and built-in Composer automation scripts."
resource: "file:///docs/reference/configuration-and-composer-scripts.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [configuration, htaccess, composer-scripts, global-control, reference]
---

# 📋 Reference: Configuration Files, .htaccess, and Composer Scripts

This reference covers the core runtime configuration files, Apache HTTP server rules, and built-in Composer automation commands in CmsForNerd v4.3.0.

---

## ⚙️ 1. Global Control Configuration (`includes/global-control.inc.php`)

The `includes/global-control.inc.php` file manages site-wide runtime settings:

```php
// Site Metadata
$siteTitle = "CmsForNerd v4.3.0";
$siteAuthor = "CMSForNerd Team & Gemini AI";

// Theme Selection
$themeName = "CmsForNerd";
$cssPath = "themes/{$themeName}/style.css";

// Security & Session Settings
$secureSession = true;
$enableCspNonce = true;
```

---

## 🌐 2. Apache Rewrite & Security Rules (`.htaccess`)

The root `.htaccess` file enforces HTTPS, sets security response headers, blocks direct access to template includes, and enables GZIP compression:

```apacheconf
# Protect sensitive include files and directories
<FilesMatch "\.(inc|json|lock|yml|yaml|sh|ps1|log|md)$">
    Require all denied
</FilesMatch>

# Enforce Security Headers
<IfModule mod_headers.c>
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-XSS-Protection "1; mode=block"
    Header set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>
```

---

## 📦 3. Composer Script Reference

CmsForNerd includes pre-configured Composer scripts in `composer.json`:

| Command | Purpose | Underlying Tool |
| :--- | :--- | :--- |
| `composer test` | Executes full unit test suite | `./vendor/bin/pest` |
| `composer stan` | Runs PHPStan Level 8 static analysis | `./vendor/bin/phpstan analyze` |
| `composer cs` | Checks PSR-12 code style compliance | `./vendor/bin/phpcs` |
| `composer cs-fix` | Automatically fixes PSR-12 formatting | `./vendor/bin/phpcbf` |
| `composer lab-check` | Runs PHPStan Level 8, style checks & tests | Custom laboratory audit sequence |
| `composer seo-gen` | Re-generates sitemaps, RSS, & Schema.org JSON | `php tools/generate-seo-files.php` |
| `composer bake` | Compiles site into flat HTML for GitHub Pages | `php tools/bake-static-pages.php` |

---

*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-15*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
