---
okf_version: 0.1
type: guide
title: "🛠️ How-To: Configure SEO, Sitemaps, RSS, and Structured Data"
description: "How CmsForNerd handles SEO: dynamic XML/TXT sitemaps, RSS feeds, ROR XML, Schema.org JSON-LD, and per-page metadata."
resource: "file:///docs/how-to/configure-seo-sitemaps.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [seo, sitemap, rss, schema-org, structured-data]
---

# 🛠️ How-To: Configure SEO, Sitemaps, RSS, and Structured Data

CmsForNerd includes an automated SEO engine that programmatically compiles search engine discovery files and structured metadata.

---

## 🗺️ 1. Automated Sitemap & Feed Generation

Run the SEO generator script to automatically scan all PHP page controllers and GitBook documentation files:

```bash
php tools/generate-seo-files.php
```

This updates five root SEO distribution targets:
1. `sitemap.xml`: Standard XML sitemap protocol.
2. `sitemap.txt`: Plain text URL index for simple crawlers.
3. `rss.xml`: RSS 2.0 web feed listing content updates.
4. `ror.xml`: Resource-Of-a-Resource XML schema.
5. `schema-org.json`: Consolidated Schema.org JSON-LD graph.

---

## 🏷️ 2. Per-Page Metadata Configuration

Define unique page metadata inside your controller's `$content` array:

```php
$content = [
    'title'       => "Page Title | CmsForNerd",
    'author'      => "Author Name",
    'description' => "Detailed search snippet description.",
    'keywords'    => "keyword1, keyword2, PHP 8.4",
    'schemaType'  => "Article"  // TechArticle, HowTo, WebPage, Service, AboutPage
];
```

---

## 🤖 3. Robots.txt Configuration

The root `robots.txt` automatically points search crawlers to both XML and TXT sitemap files across primary and GitHub Pages deployment domains:

```text
User-agent: *
Allow: /

Sitemap: https://www.linuxmalaysia.com/sitemap.xml
Sitemap: https://linuxmalaysia.github.io/CmsForNerd/sitemap.xml
```

---

*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-15*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
