# 🎨 CmsForNerd v3.5 Laboratory Guide

### Mastering the "Pair Logic" & Context Engine

The `template.php` file acts as the **Master Controller**. In v3.5, you don't need to write new PHP logic for every page. You simply duplicate the template and pair it with a content fragment.

---

## 📂 Step 1: Locate the Folders

Focus on these key areas in the Laboratory environment:

* **Root Directory**: Public `.php` entry points (e.g., `index.php`, `about.php`).
* **contents/**: Raw "body" fragments (`-body.inc` files).
* **src/ & includes/**: The core engine, security utilities, and bootstrap logic.

---

## 📝 Step 2: Create Your Content (.inc file)

Code only the internal HTML structure for your page body inside the `contents/` folder.

**🔍 Content Checklist:**

* [ ] **Naming**: Must end in `-body.inc` (e.g., `services-body.inc`).
* [ ] **No Wrappers**: Do not include `<html>`, `<head>`, or `<body>` tags.
* [ ] **Scope**: Use semantic tags like `<section>`, `<article>`, or `<div>`.
* [ ] **Security**: If using inline scripts, ensure they are compatible with the site's Content Security Policy (CSP).

---

## 🚀 Step 3: Create Your Page (.php file)

In CMSForNerd, you never write new engine code. You simply copy, rename, and adjust metadata.

1. **Copy**: Duplicate `template.php` in the root folder.
2. **Rename**: Change the copy to match your content (e.g., `services.php`).
3. **Adjust**: Update the `$content` array metadata.

### 🔍 PHP Template Logic Breakdown

```php
<?php
declare(strict_types=1);

/**
 * 1. [PERFORMANCE] Enable GZIP
 * This reduces bandwidth for mobile lab students.
 */
if (!ob_start("ob_gzhandler")) { 
    ob_start(); 
}

/**
 * 2. [LAB] BOOTSTRAP PHASE
 */
require_once __DIR__ . '/includes/bootstrap.php';

/**
 * 3. [SEO/AI] Metadata
 * AI Crawlers use 'schemaType' to categorize your laboratory data.
 */
$content = [
    'title'       => "Service Lab | CmsForNerd v3.5",
    'author'      => "Harisfaz Jamal",
    'description' => "Lab description here.",
    'keywords'    => "PHP 8.4, Lab, CMS",
    'schemaType'  => "WebPage" 
];

/**
 * 4. [LAB] ROUTING & SANITIZATION
 * SecurityUtils::resolvePageName() prevents Path Traversal attacks
 * and safely determines the requested page.
 */
$pageName = \CmsForNerd\SecurityUtils::resolvePageName(
    pathinfo(basename(__FILE__), PATHINFO_FILENAME)
);

$content['data'] = $pageName;

/**
 * 5. [MODERN PHP] CmsContext Initialization (Factory Method)
 * Bundles all state into an immutable object for the theme.
 */
$ctx = createCmsContext(content: $content, pageName: $pageName);

/**
 * 6. [RENDER] Theme Dispatcher
 */
$pagerPath = __DIR__ . "/themes/{$ctx->themeName}/pager.php";
if (file_exists($pagerPath)) {
    require_once $pagerPath;
    pager($ctx);
}
```

---

## 🛡️ Step 4: Verify Safety & Compliance

Before moving to production, perform these three laboratory checks:

1. **Static Analysis**: Run `composer analyze`. Your new page must show 0 errors at PHPStan Level 8.
2. **CSP Nonce Verification**: If you add inline `<script>`, you must use `$ctx->cspNonce` to pass the security policy.
3. **Sanitization**: Ensure your page name is valid and safe using `SecurityUtils::isValidPageName()`.

---

## ⚖️ Laboratory Standards (v3.5 Update)

* **MUST**: Keep `declare(strict_types=1);` at the top of all .php files.
* **MUST NOT**: Modify the Routing Logic or Theme Execution blocks in copied files.
* **REQUIRED**: Use the `$ctx` object to access any page data within your theme.
