---
okf_version: 0.1
type: documentation
title: "Sitemap & Discovery Guide"
description: "Educational guide for dynamic sitemap and discovery pair logic with XSS protection."
resource: "file:///docs/sitemap-guide.md"
timestamp: "2026-07-27T12:00:00Z (Planned Handover Date)"
topics: [docs, sitemap, discovery, logic, centralized]
---
# 🗺️ Sitemap & Discovery (v4.0.0)

The Sitemap in **CmsForNerd v4.0.0** serves as the navigational backbone for both human visitors and search engine crawlers. It automatically
reflects the state of your flat-file laboratory.

---

## 📂 The Sitemap Logic

The sitemap uses the same **Pair Logic** as the rest of the CMS:
* **Entry Point:** `sitemap-page.php` (Root)
* **Content Body:** `contents/sitemap-page-body.inc` (Data source)

### Centralized Page Discovery
To avoid code duplication (and SonarCloud warnings), file pairing and scanning logic are centralized in
`CmsForNerd\SecurityUtils::discoverPages()`. It scans the `contents/` folder for valid page pairs, skipping private/system files.

### Host Header Injection Protection
To prevent Host Header injection attacks, `_SERVER['HTTP_HOST']` detection and trusted-host validation are fully centralized and sanitized within
the `SecurityUtils::getSafeBaseUrl()` method. Any untrusted Host headers will be rejected.

### Defensive XML Content Security Policy
XML Sitemaps (`sitemap.php`) are locked down with a strict CSP header:
```http
Content-Security-Policy: default-src 'none'; style-src 'none';
```
This blocks all external resources and dynamically loaded scripts, eliminating potential browser-level XML/HTML cross-site scripting (XSS).

## ⚖️ Standards (RFC 2119)

* **MUST:** The sitemap MUST include all core laboratory modules (`lab-module1.php`, etc.).
* **SHOULD:** Include the `lastmod` (last modified) date to help search engines prioritize crawling.
* **MUST NOT:** List private include files or vendor directories.

## 🚀 SEO Best Practices
CmsForNerd v4.0.0 generates two types of sitemaps:
1. **HTML Sitemap:** For user navigation (`sitemap-page.php`).
2. **XML Sitemap:** Located at `sitemap.php` for Google Search Console and Bing Webmaster Tools, escaped safely using
   `SecurityUtils::escapeHtml()`.


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-27 (Planned Handover Date)*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
