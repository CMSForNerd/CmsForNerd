<?php

declare(strict_types=1);

/**
 * CmsForNerd v4.3.0 - Local User Manual Entry Point Controller (user-manual.php)
 * Serves the Diátaxis-based user manual index view fragment.
 *
 * @package     linuxmalaysia/cmsfornerd
 * @author      Harisfazillah Jamel <linuxmalaysia@songketmail.org>
 * @copyright   2005 - 2026 Harisfazillah Jamel
 * @license     GPL-3.0-or-later
 */

// Initialize GZIP response compression buffer
if (!ob_start("ob_gzhandler")) {
    ob_start();
}

// Bootstrap CmsContext and autoload core utilities
require_once __DIR__ . '/includes/bootstrap.php';

/**
 * 3. [SEO/AI] Page Metadata
 */
$content = [
    'title'       => "Local User Manual | CMSForNerd v4.3.0 Laboratory",
    'author'      => "CMSForNerd Engineering Team",
    'description' => "Complete local user manual for CmsForNerd v4.3.0. Step-by-step guides for WSL2, AlmaLinux 10, Podman, Herd, Pair Logic, and OWASP security.",
    'keywords'    => "User Manual, Local Setup, WSL2, AlmaLinux 10, Podman, Diataxis, PHP 8.4, CmsForNerd",
    'schemaType'  => "HowTo"
];

/**
 * 4. [LAB] ROUTING & SANITIZATION
 */
$pageName = \CmsForNerd\SecurityUtils::resolvePageName(pathinfo(basename(__FILE__), PATHINFO_FILENAME));

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
 * 6. [RENDER] Theme Dispatcher
 */
$pagerPath = __DIR__ . "/themes/{$ctx->themeName}/pager.php";
if (file_exists($pagerPath)) {
    require_once $pagerPath;
    pager($ctx);
} else {
    header('HTTP/1.1 500 Internal Server Error');
    echo "Fatal Error: Theme engine missing in /themes/{$ctx->themeName}/";
}

ob_end_flush();
