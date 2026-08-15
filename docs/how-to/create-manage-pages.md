---
okf_version: 0.1
type: guide
title: "🛠️ Page Construction using the Pair Logic Pattern"
description: "Step-by-step instructions for building a new page by pairing a root PHP controller with a body fragment in contents/."
resource: "file:///docs/how-to/create-manage-pages.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [creating-pages, pair-logic, controllers, content-body, how-to]
---

# 🛠️ Page Construction using the Pair Logic Pattern

CmsForNerd pairs every root controller with a corresponding HTML body fragment in `contents/`.

---

## 🛠️ Step-by-Step Example (`portfolio.php`)

### 1. Root Controller (`portfolio.php`):
```php
<?php
declare(strict_types=1);

if (!ob_start("ob_gzhandler")) { ob_start(); }
require_once __DIR__ . '/includes/bootstrap.php';

$pageMeta = [
    'title'       => 'Our Portfolio | CmsForNerd',
    'author'      => 'Engineering Team',
    'description' => 'Showcase of zero-database PHP 8.4 web applications.',
    'keywords'    => 'Portfolio, Flat-File, PHP 8.4',
    'schemaType'  => 'WebPage'
];

$slug = \CmsForNerd\SecurityUtils::resolvePageName(pathinfo(basename(__FILE__), PATHINFO_FILENAME));
$pageMeta['data'] = $slug;

$ctx = createCmsContext(
    content: $pageMeta,
    pageName: $slug,
    themeName: $themeName,
    cssPath: "themes/{$themeName}/style.css",
    dataFile: "contents/{$slug}-body.inc",
    nonce: $nonce
);

require_once __DIR__ . "/themes/{$ctx->themeName}/pager.php";
pager($ctx);
ob_end_flush();
```

### 2. Body Fragment (`contents/portfolio-body.inc`):
```html
<h1>Project Showcase</h1>
<p>Explore our fast, database-free web applications.</p>
```

---

*CmsForNerd Pair Logic Page Construction Guide | DSOM Protocol 2026 | Harisfazillah Jamel (LinuxMalaysia)*
