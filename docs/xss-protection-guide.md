---
okf_version: 0.1
type: documentation
title: "Cross-Site Scripting (XSS) & Host Header Injection Protection Guide"
description: "Sovereign guide to the XSS and Host Header defense architecture in CMSForNerd v4.0.0."
resource: "file:///docs/xss-protection-guide.md"
timestamp: "2026-07-27T12:00:00Z (Planned Handover Date)"
topics: [security, xss, host-header, dynamic-xml, csp]
---
# 🛡️ XSS & Host Header Injection Protection Guide

## Overview
In **CMSForNerd v4.0.0**, security is treated as a first-class citizen. This guide outlines the system's defenses against Cross-Site
Scripting (XSS), Host Header Injection, duplicate processing, and HTTP header protections.

---

## 🏛️ Centralized Security Architecture

To prevent common vulnerabilities and eliminate SonarCloud code duplication, core scanning and detection routines have been centralized in the
`CmsForNerd\SecurityUtils` class.

### 1. Host Header Injection Defense
Attackers often spoof the `Host` HTTP request header to inject arbitrary values into dynamic links, resulting in password reset poisonings or
XSS.
* **Solution**: `SecurityUtils::getSafeBaseUrl()` validates `$_SERVER['HTTP_HOST']` against an explicit allowlist of trusted hosts (e.g.,
  `localhost`, `127.0.0.1`, `::1`, `cmsfornerd.test`) and rejects any untrusted Host headers to construct a safe, predictable Base URL.
* **Usage**: Used consistently in `rss.php` and `sitemap.php` to calculate canonical links safely, rejecting untrusted requests immediately.

### 2. Centralized Page Discovery
* **Solution**: `SecurityUtils::discoverPages()` scans the laboratory content folder (`contents/`) for paired file structures, ensuring that only
  valid page pairs are indexed. This replaces redundant parsing loops in sitemap and feed scripts.

### 3. Unified HTML & XML Escaping
* **Solution**: `SecurityUtils::escapeHtml()` provides high-level UTF-8 escaping. It internally invokes `htmlspecialchars($content, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')`.
* **Standard**: Instead of writing redundant inline closure escaping in feed templates, developers MUST use `SecurityUtils::escapeHtml()` on
  all dynamic inputs and output nodes.

### 4. Content Security Policy (CSP) Headers
* **Solution**: `SecurityUtils::sendSecurityHeaders()` emits OWASP-compliant Content Security Policy headers on every HTTP request.
* **Nonce Integration**: Dynamically incorporates cryptographic script nonces (`nonce-<hash>`) generated via `Registry::get('nonce')` to restrict inline script execution while blocking untrusted object sources (`object-src 'none'`).

---

## 🔌 Securing Dynamic Feeds & Sitemaps

Dynamic generators like `rss.php` and `sitemap.php` are highly prone to tag injection. We protect them using a multi-layered defense.

### A. Output Buffer Hardening
Before rendering any XML payload, the script completely purges active output buffers to prevent server-level HTML injection or appended code from
corrupting the document structure:
```php
while (ob_get_level()) {
    ob_end_clean();
}
```

### B. Restrictive Content Security Policies (CSP)
For standalone XML files (like `sitemap.php`), we deliver a strict Content Security Policy to ensure browsers disable all scripts and styling:
```http
Content-Security-Policy: default-src 'none'; style-src 'none';
```

### C. Pure Encoding Declarations
Proper HTTP Headers are set to prevent MIME-type sniffing:
```php
header("Content-Type: application/xml; charset=utf-8"); // For sitemaps
header("Content-Type: application/rss+xml; charset=utf-8"); // For feeds
header("X-Content-Type-Options: nosniff");
```

---

## 🎓 Student Certificate XSS Defense

On the graduation certificate generator (`graduation.php`), students supply their names via the `student` parameter.
* **Vulnerability**: An unescaped query string parameter is a direct path to Reflected XSS.
* **Mitigation**: The certificate view (`contents/graduation-body.inc`) passes all dynamic parameters through the canonical
  `SecurityUtils::escapeHtml()` helper, and displays a SHA-256 checksum of the student ID. Note that this plain SHA-256 hash provides
  only an integrity checksum of the certificate data rather than an anti-forgery cryptographic signature, as it does not use a server-held secret.

---

## ⚖️ Retrieval & Reference
- [Security Policy](../SECURITY.md)
- [Sitemap & SEO Guide](sitemap-guide.md)
- [Directory Security Guide](directory-security.md)

---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-27 (Planned Handover Date)*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
