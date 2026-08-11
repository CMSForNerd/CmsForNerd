<?php

/**
 * Page Controller for the CMS Legal Notice & Disclaimer.
 * This file is compliant with PHP 8.4 strict types.
 */

declare(strict_types=1);

if (!ob_start("ob_gzhandler")) {
    ob_start();
}

require_once __DIR__ . '/includes/bootstrap.php';

$content = [
    'title'       => "Legal Notice & Disclaimer | CmsForNerd Laboratory",
    'author'      => "Harisfazillah Jamel",
    'description' => "Legal Notice, Privacy Policy, Critical Assumptions, and Disclaimer of Liability.",
    'keywords'    => "Legal Notice, Privacy Policy, Disclaimer, Assumptions",
    'schemaType'  => "WebPage",
    'data'        => "legal-notice"
];

$ctx = createCmsContext(
    content: $content,
    pageName: "legal-notice",
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
    exit("Fatal Error: Theme engine missing.");
}

ob_end_flush();
