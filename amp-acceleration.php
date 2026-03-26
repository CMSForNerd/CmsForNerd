<?php

declare(strict_types=1);

/**
 * CmsForNerd v3.5 - AMP Acceleration Documentation (amp-acceleration.php)
 * * ROLE: Explanation of the dual-view (Standard/AMP) architecture.
 * This file is synchronized with the master template.php logic.
 */

if (!ob_start("ob_gzhandler")) {
    ob_start();
}

require_once __DIR__ . '/includes/bootstrap.php';

$content = [
    'title'       => "AMP Acceleration | CMSForNerd",
    'author'      => "CMSForNerd Team",
    'description' => "Technical guide on how CMSForNerd leverages Accelerated Mobile Pages (AMP) for mobile-first performance.",
    'keywords'    => "AMP, Mobile Performance, Dual-View, Web Vitals, CMSForNerd",
    'schemaType'  => "TechArticle"
];

$rawPage = match (true) {
    !empty($_SERVER['QUERY_STRING']) => (string) $_SERVER['QUERY_STRING'],
    default                          => pathinfo(basename(__FILE__), PATHINFO_FILENAME)
};

$isValid = \CmsForNerd\SecurityUtils::isValidPageName($rawPage);
$page = $isValid ? $rawPage : 'index';
$pageName = pathinfo($page, PATHINFO_FILENAME);

$content['data'] = $pageName;

$ctx = createCmsContext(
    content: $content, 
    pageName: $pageName,
    themeName: $themeName ?? null,
    cssPath: $cssPath ?? null,
    dataFile: $dataFile ?? null,
    nonce: $nonce ?? null
);

if (file_exists(__DIR__ . '/includes/turnstile.php')) {
    require_once __DIR__ . '/includes/turnstile.php';
}

if (file_exists(__DIR__ . '/includes/is_bot.php')) {
    require_once __DIR__ . '/includes/is_bot.php';
    if (is_bot()) {
        header('Content-Type: text/plain; charset=utf-8');
        echo "CmsForNerd v3.5 - AMP Acceleration Text Mode\n";
        exit;
    }
}

$pagerPath = __DIR__ . "/themes/{$ctx->themeName}/pager.php";
if (file_exists($pagerPath)) {
    require_once $pagerPath;
    pager($ctx);
} else {
    header('HTTP/1.1 500 Internal Server Error');
    echo "Fatal Error: Theme engine missing.";
}

ob_end_flush();
