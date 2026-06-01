<?php

/**
 * CmsForNerd v4.0.0-alpha - Primary Front Controller (index.php)
 * * ROLE: The main entrance to the Laboratory.
 * This file is synchronized with the master template.php logic to ensure
 * total architectural consistency across the entire CMS.
 *
 * @package     linuxmalaysia/cmsfornerd
 * @author      Harisfazillah Jamel <linuxmalaysia@songketmail.org>
 * @copyright   2005 - 2026 Harisfazillah Jamel
 * @license     GPL-3.0-or-later
 */

declare(strict_types=1);

// 1. [PERFORMANCE] Enable GZIP and Output Buffering
// Compresses data for faster delivery and allows header manipulation mid-script.
if (!ob_start("ob_gzhandler")) {
    ob_start();
}

/**
 * 2. [LAB] BOOTSTRAP PHASE
 * Loads the environment, autoloader, and core Nerd-Stack functions.
 */
require_once __DIR__ . '/includes/bootstrap.php';

/**
 * 3. [SEO/AI] Page Metadata
 * Custom values for the Homepage. In Lab v3, we use schemaType 'WebApplication'
 * for the root index to signify the CMS environment to search engines.
 */
$content = [
    'title'       => "CmsForNerd v4.0.0-alpha | The Developer’s Laboratory",
    'author'      => "Harisfazillah Jamel",
    'description' => "A lightweight flat-file CMS modernized for PHP 8.4+ and strict security standards.",
    'keywords'    => "PHP 8.4, Flat-file CMS, Security Laboratory, Zero-Debt CSS, PSR-12",
    'schemaType'  => "WebApplication"
];

/**
 * 4. [LAB] ROUTING & SANITIZATION
 * The 'match' expression handles two scenarios:
 * 1. A query string is provided (e.g., index.php?about).
 * 2. No query string, in which case it defaults to 'index'.
 */
$pageName = \CmsForNerd\SecurityUtils::resolvePageName('index');

// Link this controller to contents/[pageName]-body.inc
$content['data'] = $pageName;

/**
 * 5. [MODERN PHP] CmsContext Initialization
 * Creates the immutable $ctx object that carries our data into the theme.
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
 * 6. [PWA / SPA] Fragment Hydration
 * If a JavaScript router (Fetch/XHR) calls this URL, return purely the un-themed content fragment.
 */
$isAjax = (
    !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
);

if ($isAjax) {
    header('Content-Type: text/html; charset=utf-8');

    // We prioritize scriptName to maintain the 'index' -> 'index-body.inc' convention.
    $bodyFile = __DIR__ . "/contents/" . $ctx->scriptName . "-body.inc";

    if (file_exists($bodyFile)) {
        readfile($bodyFile);
    } else {
        $fallbackFile = __DIR__ . "/contents/" . ($ctx->dataFile[0] ?? 'index') . "-body.inc";
        if (file_exists($fallbackFile)) {
            readfile($fallbackFile);
        } else {
            http_response_code(404);
            echo "<div class='error-box'><strong>[LAB ERROR] Content File Missing</strong></div>";
        }
    }
    // Flush and send to browser immediately for PWA hydration
    ob_end_flush();
    exit;
}

/**
 * 7. [RENDER] Theme Dispatcher
 * Hands over control to the theme's pager.php to render the full Lab_v3 UI.
 */
$pagerPath = __DIR__ . "/themes/{$ctx->themeName}/pager.php";
if (file_exists($pagerPath)) {
    require_once $pagerPath;
    pager($ctx);
} else {
    header('HTTP/1.1 500 Internal Server Error');
    echo "Fatal Error: Theme engine missing in /themes/{$ctx->themeName}/";
}

// Flush and send to browser
ob_end_flush();
