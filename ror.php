<?php

declare(strict_types=1);

// [ROR START]
echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;

/**
 * ==========================================================================
 * FILE: /ror.php
 * ROLE: Dynamic ROR Sitemap Generator (v4.3.0)
 * DESCRIPTION: Provides a Resources of a Resource (ROR) map for educational 
 * semantic exploration.
 * ==========================================================================
 */

// 1. [SECURITY] Output Buffer Hardening
while (ob_get_level()) {
    ob_end_clean();
}

// 2. [EDUCATION] Auto-URL Detection
require_once __DIR__ . '/vendor/autoload.php';
$baseUrl = \CmsForNerd\SecurityUtils::getSafeBaseUrl();

// 3. [SECURITY] Hardened Headers
header("Content-Type: application/xml; charset=utf-8");
header("X-Content-Type-Options: nosniff");

// 4. [ROR START]
echo '<rss version="2.0" xmlns:ror="http://www.rorweb.com/0.1/">' . PHP_EOL;
echo '  <channel>' . PHP_EOL;
echo '    <title>ROR Sitemap for CMSForNerd Laboratory v4.3.0</title>' . PHP_EOL;
echo '    <link>' . \CmsForNerd\SecurityUtils::escapeHtml($baseUrl . 'index.php') . '</link>' . PHP_EOL;

// 5. [ITEM SCAN]
$pages = \CmsForNerd\SecurityUtils::discoverPages(__DIR__ . '/contents/', __DIR__);

foreach ($pages as $page) {
    $slug    = $page['slug'];
    $title   = $page['title'];
    $updated = date('Y-m-d', $page['filemtime']);

    echo '    <item>' . PHP_EOL;
    echo '      <link>' . \CmsForNerd\SecurityUtils::escapeHtml($baseUrl . $slug . '.php') . '</link>' . PHP_EOL;
    echo '      <title>' . \CmsForNerd\SecurityUtils::escapeHtml($title) . '</title>' . PHP_EOL;
    echo '      <ror:type>resource</ror:type>' . PHP_EOL;
    echo '      <ror:updated>' . $updated . '</ror:updated>' . PHP_EOL;
    echo '    </item>' . PHP_EOL;
}

echo '  </channel>' . PHP_EOL;
echo '</rss>';

exit;
