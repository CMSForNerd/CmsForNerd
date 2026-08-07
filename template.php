<?php

/**
 * CmsForNerd v4.3.0 - Master Template Boilerplate
 * * This file serves as the "Master Controller" for the Lab v4 architecture.
 * INSTRUCTIONS FOR STUDENTS:
 * 1. Copy this file to create a new page (e.g., about.php).
 * 2. Update the $content array in Section 3 for SEO/AI metadata.
 * 3. The engine will automatically pair this file with contents/[filename]-body.inc.
 *
 * @package     linuxmalaysia/cmsfornerd
 * @author      Harisfazillah Jamel <linuxmalaysia@songketmail.org>
 * @copyright   2005 - 2026 Harisfazillah Jamel
 * @license     GPL-3.0-or-later
 */

declare(strict_types=1);

// 1. [PERFORMANCE] Enable GZIP and Output Buffering
// ob_gzhandler compresses the HTML before sending it to the browser,
// significantly reducing bandwidth and improving load speed for students.
if (!ob_start("ob_gzhandler")) {
    ob_start();
}

/**
 * 2. [LAB] BOOTSTRAP PHASE
 * Loads the core engine, Composer dependencies, and security constants.
 */
require_once __DIR__ . '/includes/bootstrap.php';

/**
 * 3. [SEO/AI] Page Metadata
 * STUDENTS: Adjust these values whenever you create a new page.
 * v4.3.0 includes schemaType to help AI crawlers (ChatGPT/Gemini) understand the page.
 */
$content = [
    'title'       => "New Lab Specimen | CmsForNerd v4.3.0",
    'author'      => "Harisfazillah Jamel",
    'description' => "A lightweight flat-file CMS modernized for PHP 8.4+ and PHP 9 readiness.",
    'keywords'    => "PHP 8.4, Flat-file CMS, Security Laboratory, PSR-12, Education",
    'schemaType'  => "WebPage" // Options: WebPage, Article, ContactPage, etc.
];

/**
 * 4. [LAB] ROUTING & SANITIZATION
 * v4.3.0 uses the 'match' expression—a modern, strict alternative to 'switch'.
 * It ensures that we handle the query string or default to 'index' cleanly.
 */
$pageName = \CmsForNerd\SecurityUtils::resolvePageName(pathinfo(basename(__FILE__), PATHINFO_FILENAME));

// This 'data' key tells the theme which -body.inc file to include.
$content['data'] = $pageName;

/**
 * 5. [MODERN PHP] CmsContext Initialization (Factory Method)
 * Instead of passing loose variables, we bundle everything into an
 * immutable CmsContext object for type-safe rendering.
 */
$ctx = createCmsContext(
    content: $content,
    pageName: $pageName,
    themeName: $themeName,
    cssPath: $cssPath,
    dataFile: $dataFile,
    nonce: $nonce
);

/**
 * [LAB] BOT DETECTION
 * If a search engine crawler is detected, we serve a lightweight text version
 * to prioritize indexing over visual effects.
 */

/**
 * 6. [RENDER] Theme Dispatcher (The "Pager")
 * This locates the pager.php inside your active theme and executes the UI.
 */
$pagerPath = __DIR__ . "/themes/{$ctx->themeName}/pager.php";
if (file_exists($pagerPath)) {
    require_once $pagerPath;
    // The pager() function receives the $ctx object as its single source of truth.
    pager($ctx);
} else {
    header('HTTP/1.1 500 Internal Server Error');
    echo "Fatal Error: Theme engine (pager.php) missing in /themes/{$ctx->themeName}/";
}

// Flush the buffer and send the compressed content to the user.
ob_end_flush();
