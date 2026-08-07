<?php

declare(strict_types=1);

/**
 * ==========================================================================
 * FILE: /rss.php
 * ROLE: Dynamic RSS 2.0 Feed Generator (v4.3.0)
 * DESCRIPTION: Provides a synchronized content feed using Pair Logic.
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
header("Content-Type: application/rss+xml; charset=utf-8");
header("X-Content-Type-Options: nosniff");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");

// 4. [RSS START]
echo '<?xml version="1.0" encoding="UTF-8" ?>' . PHP_EOL;
echo '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">' . PHP_EOL;
echo '  <channel>' . PHP_EOL;
echo '    <title>CMSForNerd Laboratory v4.3.0</title>' . PHP_EOL;
echo '    <link>' . \CmsForNerd\SecurityUtils::escapeHtml($baseUrl) . 'index.php</link>' . PHP_EOL;
echo '    <description>Modern PHP 8.4+ educational CMS environment.</description>' . PHP_EOL;
echo '    <language>en-us</language>' . PHP_EOL;
echo '    <lastBuildDate>' . date(DATE_RSS) . '</lastBuildDate>' . PHP_EOL;
echo '    <atom:link href="' . \CmsForNerd\SecurityUtils::escapeHtml($baseUrl) . 'rss.php" rel="self" type="application/rss+xml" />' . PHP_EOL;

// 5. [AUTOMATION] The Scanning Engine (Pair Logic)
$pages = \CmsForNerd\SecurityUtils::discoverPages(__DIR__ . '/contents/', __DIR__);

foreach ($pages as $page) {
    $slug    = $page['slug'];
    $title   = $page['title'];
    $pubDate = date(DATE_RSS, $page['mtime']);

    echo '    <item>' . PHP_EOL;
    echo '      <title>' . \CmsForNerd\SecurityUtils::escapeHtml($title) . '</title>' . PHP_EOL;
    echo '      <link>' . \CmsForNerd\SecurityUtils::escapeHtml($baseUrl . $slug) . '.php</link>' . PHP_EOL;
    echo '      <description>Updates for the ' . \CmsForNerd\SecurityUtils::escapeHtml($title) . ' module in the v4.3.0 Laboratory.</description>' . PHP_EOL;
    echo '      <guid isPermaLink="true">' . \CmsForNerd\SecurityUtils::escapeHtml($baseUrl . $slug) . '.php</guid>' . PHP_EOL;
    echo '      <pubDate>' . $pubDate . '</pubDate>' . PHP_EOL;
    echo '    </item>' . PHP_EOL;
}

echo '  </channel>' . PHP_EOL;
echo '</rss>';

/**
 * [PERFORMANCE] Clean Exit
 */
exit;
