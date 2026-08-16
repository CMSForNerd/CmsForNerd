<?php
declare(strict_types=1);

namespace CmsForNerd;

/**
 * CmsForNerd v4.3.0 - Local User Manual Entry Point Controller (user-manual.php)
 * Serves the Diátaxis-based user manual index view fragment.
 *
 * @package     linuxmalaysia/cmsfornerd
 * @author      Harisfazillah Jamel <linuxmalaysia@songketmail.org>
 * @copyright   2005 - 2026 Harisfazillah Jamel
 * @license     GPL-3.0-or-later
 */

// 1. [LAB] BOOTSTRAP PHASE - Must load bootstrap before executing buffering/logic
require_once __DIR__ . '/includes/bootstrap.php';

// 2. [PERFORMANCE] Enable GZIP and Output Buffering
$bufferStarted = false;
if (!ini_get('zlib.output_compression')) {
    $bufferStarted = ob_start("ob_gzhandler");
}
if (!$bufferStarted) {
    $bufferStarted = ob_start();
}

/**
 * 3. [SEO/AI] Page Metadata
 */
$content = [
    'title'       => "Local User Manual | CMSForNerd v4.3.0 Laboratory",
    'author'      => "CMSForNerd Engineering Team",
    'description' => "Complete local user manual for CmsForNerd v4.3.0. " .
                     "Step-by-step guides for WSL2, AlmaLinux, Podman, Herd, Pair Logic, and OWASP security.",
    'keywords'    => "User Manual, Local Setup, WSL2, AlmaLinux, Podman, Diataxis, PHP 8.4, CmsForNerd",
    'schemaType'  => "WebPage"
];

/**
 * 4. [LAB] ROUTING & SANITIZATION
 */
$pageName = SecurityUtils::resolvePageName(pathinfo(basename(__FILE__), PATHINFO_FILENAME));
$content['data'] = $pageName;

/**
 * 5. [MODERN PHP] CmsContext Initialization (Factory Method)
 */
$ctx = \createCmsContext(
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

if ($bufferStarted && ob_get_level() > 0) {
    ob_end_flush();
}
