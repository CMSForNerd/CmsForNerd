<?php
declare(strict_types=1);

/**
 * Page Controller for the ASIMP for AI Agents Guide.
 * This file is compliant with PHP 8.4 strict types.
 */

namespace CmsForNerd;

// 1. [BOOTSTRAP] Load core engine as the first functional statement
require_once __DIR__ . '/includes/bootstrap.php';

// 2. [PERFORMANCE] Enable GZIP and Output Buffering
if (!ob_start("ob_gzhandler")) {
    ob_start();
}

/**
 * 3. [SEO/AI] Page Metadata
 */
$content = [
    'title'       => "ASIMP for AI Agents | CmsForNerd Laboratory",
    'author'      => "Harisfazillah Jamel",
    'description' => "Details how the Ansible System Integrity Management Platform (ASIMP) operates in alignment with AI agents, DSOM spatial memory protocols, and the OpenWiki emulator.",
    'keywords'    => "ASIMP, AI Agents, DSOM, My AI Protocol, Hardening, OpenSCAP, Lynis, PHP 8.4, LinuxMalaysia",
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
$ctx = \createCmsContext(
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
    \pager($ctx);
} else {
    header('HTTP/1.1 500 Internal Server Error');
    exit("Fatal Error: Theme engine (pager.php) missing in /themes/{$ctx->themeName}/");
}

ob_end_flush();
