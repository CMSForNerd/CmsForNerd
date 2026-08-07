<?php

declare(strict_types=1);

/**
 * CmsForNerd v4.3.0 - Page Controller (pwa-architecture.php)
 * * ROLE: PWA features and implementation details.
 * This file is synchronized with the master template.php logic to ensure
 * total architectural consistency across the entire CMS.
 *
 * @package     linuxmalaysia/cmsfornerd
 * @author      Harisfazillah Jamel <linuxmalaysia@songketmail.org>
 * @copyright   2005 - 2026 Harisfazillah Jamel
 * @license     GPL-3.0-or-later
 */

if (!ob_start("ob_gzhandler")) {
    ob_start();
}

require_once __DIR__ . '/includes/bootstrap.php';

$content = [
    'title'       => "PWA Architecture | CMSForNerd",
    'author'      => "CMSForNerd Team & Google Gemini",
    'description' => "Explore the technical details of progressive enhancements in CMSForNerd, including Service Workers, bfcache, and local first strategies.",
    'keywords'    => "PWA, Service Worker, bfcache, Progressive Web App, Offline, CMSForNerd",
    'schemaType'  => "TechArticle"
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
} else {
    header('HTTP/1.1 500 Internal Server Error');
    echo "Fatal Error: Theme engine (pager.php) missing in /themes/{$ctx->themeName}/";
}

ob_end_flush();
