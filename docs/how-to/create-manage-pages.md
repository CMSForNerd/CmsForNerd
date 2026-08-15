---
okf_version: 0.1
type: guide
title: "🛠️ How-To: Creating Pages with the Pair Logic System"
description: "Learn how to create a new page in CmsForNerd using the Pair Logic pattern — pairing a PHP controller with an HTML body fragment."
resource: "file:///docs/how-to/create-manage-pages.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [creating-pages, pair-logic, controllers, content-body, how-to]
---

# 🛠️ How-To: Creating Pages with the Pair Logic System

Every web page in **CmsForNerd v4.3.0** is constructed using the **Pair Logic** pattern:
1. **Root Controller (`[page-name].php`):** Initializes CmsContext, defines page SEO metadata, and calls the theme pager.
2. **Body Fragment (`contents/[page-name]-body.inc`):** Contains pure HTML page content (the inner body content).

---

## 🛠️ Step-by-Step: Creating a New "Services" Page

### Step 1: Create the PHP Controller File (`services.php`)

Create `services.php` in the repository root directory:

```php
<?php

declare(strict_types=1);

/**
 * CmsForNerd v4.3.0 - Services Page Controller
 */

if (!ob_start("ob_gzhandler")) {
    ob_start();
}

require_once __DIR__ . '/includes/bootstrap.php';

$content = [
    'title'       => "Our Services | CmsForNerd",
    'author'      => "CmsForNerd Engineering",
    'description' => "Explore our development services and flat-file CMS solutions.",
    'keywords'    => "Services, Consulting, Flat-File CMS, PHP 8.4",
    'schemaType'  => "Service"
];

$pageName = \CmsForNerd\SecurityUtils::resolvePageName(pathinfo(basename(__FILE__), PATHINFO_FILENAME));
$content['data'] = $pageName;

$ctx = createCmsContext(
    content: $content,
    pageName: $pageName,
    themeName: $themeName,
    cssPath: $cssPath,
    dataFile: $dataFile,
    nonce: $nonce
);

$pagerPath = __DIR__ . "/themes/{$ctx->themeName}/pager.php";
if (file_exists($pagerPath)) {
    require_once $pagerPath;
    pager($ctx);
}

ob_end_flush();
```

---

### Step 2: Create the Content Body Include (`contents/services-body.inc`)

Create `contents/services-body.inc`:

```html
<h1>Our Services</h1>
<p>We specialize in ultra-fast, zero-database PHP 8.4 web applications.</p>

<div class="card-grid">
  <div class="card">
    <h3>Architecture Design</h3>
    <p>Zero-Global, immutable state management for modern PHP applications.</p>
  </div>
  <div class="card">
    <h3>Google AMP Acceleration</h3>
    <p>Dual-view rendering delivering instant mobile pages with validated AMP markup.</p>
  </div>
</div>
```

---

## 🌐 Step 3: Test and Index

1. Open `http://localhost:8000/services.php` in your browser.
2. Re-run SEO generator to automatically register `services.php` in sitemaps:
   ```bash
   php tools/generate-seo-files.php
   ```

---

*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-15*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
