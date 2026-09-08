<?php

/**
 * CmsForNerd v4.3.0 - Page Controller (reactive-wasm-lab.php)
 * ROLE: HTMX, Alpine.js, and WebAssembly (Wasm) Cryptography & Document Processing Laboratory.
 *
 * @package     linuxmalaysia/cmsfornerd
 * @author      Harisfazillah Jamel <linuxmalaysia@songketmail.org>
 * @copyright   2005 - 2026 Harisfazillah Jamel
 * @license     GPL-3.0-or-later
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

// 1. [PERFORMANCE] Enable GZIP and Output Buffering
if (!ob_start("ob_gzhandler")) {
    ob_start();
}

/**
 * 3. [SEO/AI] Page Metadata
 */
$content = [
    'title'       => "Reactive UI & WebAssembly (Wasm) Cryptography Lab | CmsForNerd",
    'author'      => "Harisfazillah Jamel & CmsForNerd Team",
    'description' => "Explore HTMX/Alpine.js reactive interactions and WebAssembly (Wasm) client-side cryptographic " .
                     "and document processing while maintaining CmsForNerd's Zero-Global PHP 8.4 engine architecture.",
    'keywords'    => "HTMX, Alpine.js, WebAssembly, Wasm, Client-Side Cryptography, " .
                     "Document Processing, CT-Wasm, Zero-Global, PHP 8.4, CmsForNerd",
    'schemaType'  => "TechArticle"
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
 * 6. [RENDER] Theme Dispatcher (The "Pager")
 */
$pagerPath = __DIR__ . "/themes/{$ctx->themeName}/pager.php";
if (file_exists($pagerPath)) {
    require_once $pagerPath;
    pager($ctx);
} else {
    header('HTTP/1.1 500 Internal Server Error');
    exit("Fatal Error: Theme engine (pager.php) missing in /themes/{$ctx->themeName}/");
}

ob_end_flush();
