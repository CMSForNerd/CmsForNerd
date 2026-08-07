<?php

declare(strict_types=1);

/**
 * CmsForNerd v4.3.0 - Page Controller (about.php)
 * * ROLE: Mission statement and project philosophy.
 * This file is synchronized with the master template.php logic to ensure
 * total architectural consistency across the entire CMS.
 *
 * @package     linuxmalaysia/cmsfornerd
 * @author      Harisfazillah Jamel <linuxmalaysia@songketmail.org>
 * @copyright   2005 - 2026 Harisfazillah Jamel
 * @license     GPL-3.0-or-later
 */

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
 */
$content = [
    'title'       => "About CMSForNerd | The Human-AI Project",
    'author'      => "CMSForNerd Team & Google Gemini",
    'description' => "Discover the philosophy behind CMSForNerd: A project dedicated to educational empowerment through radical simplicity.",
    'keywords'    => "Open Source, Philosophy, PHP 8.4, Education, LinuxMalaysia, Google Gemini",
    'schemaType'  => "AboutPage"
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
