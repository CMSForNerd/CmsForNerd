<?php

/**
 * CmsForNerd v4.3.0 - Page Controller (legal-notice.php)
 * * ROLE: Legal notice, privacy policy, assumptions and liability disclaimer.
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
 */
$content = [
    'title'       => "Legal Notice & Disclaimer | CmsForNerd Laboratory",
    'author'      => "Harisfazillah Jamel",
    'description' => "Legal Notice, Privacy Policy, Critical Assumptions, and Disclaimer of Liability for the CmsForNerd Laboratory.",
    'keywords'    => "Legal Notice, Privacy Policy, Disclaimer, Assumptions, PHP 8.4, Education, LinuxMalaysia",
    'schemaType'  => "WebPage"
];

/**
 * 4. [LAB] ROUTING & SANITIZATION
 */
$pageName = \CmsForNerd\SecurityUtils::resolvePageName(pathinfo(basename(__FILE__), PATHINFO_FILENAME));

// This 'data' key tells the theme which -body.inc file to include.
$content['data'] = $pageName;

/**
 * 5. [MODERN PHP] CmsContext Initialization (Factory Method)
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
 * 6. [RENDER] Theme Dispatcher (The "Pager")
 */
$pagerPath = __DIR__ . "/themes/{$ctx->themeName}/pager.php";
if (file_exists($pagerPath)) {
    require_once $pagerPath;
    pager($ctx);
} else {
    header('HTTP/1.1 500 Internal Server Error');
    echo "Fatal Error: Theme engine (pager.php) missing in /themes/{$ctx->themeName}/";
}

// Flush the buffer and send the compressed content to the user.
ob_end_flush();
