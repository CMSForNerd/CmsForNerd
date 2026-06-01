<?php

declare(strict_types=1);

/**
 * ==========================================================================
 * FILE: /rss.php
 * ROLE: Dynamic RSS 2.0 Feed Generator (v4.0.0-alpha)
 * DESCRIPTION: Provides a synchronized content feed using Pair Logic.
 * ==========================================================================
 */

// 1. [SECURITY] Output Buffer Hardening
while (ob_get_level()) {
    ob_end_clean();
}

// 2. [EDUCATION] Auto-URL Detection
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$host     = $_SERVER['HTTP_HOST'] ?? 'localhost';
$dirPath  = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$baseUrl  = rtrim($protocol . $host . $dirPath, '/') . '/';

// 3. [SECURITY] Hardened Headers
header("Content-Type: application/rss+xml; charset=utf-8");
header("X-Content-Type-Options: nosniff");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");

// 4. [RSS START]
echo '<?xml version="1.0" encoding="UTF-8" ?>' . PHP_EOL;
echo '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">' . PHP_EOL;
echo '  <channel>' . PHP_EOL;
echo '    <title>CMSForNerd Laboratory v4.0.0-alpha</title>' . PHP_EOL;
echo '    <link>' . $baseUrl . 'index.php</link>' . PHP_EOL;
echo '    <description>Modern PHP 8.4+ educational CMS environment.</description>' . PHP_EOL;
echo '    <language>en-us</language>' . PHP_EOL;
echo '    <lastBuildDate>' . date(DATE_RSS) . '</lastBuildDate>' . PHP_EOL;
echo '    <atom:link href="' . $baseUrl . 'rss.php" rel="self" type="application/rss+xml" />' . PHP_EOL;

// 5. [AUTOMATION] The Scanning Engine (Pair Logic)
$fragmentDir = __DIR__ . '/contents/';

if (is_dir($fragmentDir)) {
    $fragments = glob($fragmentDir . '*-body.inc');

    foreach ($fragments as $file) {
        $slug = str_replace('-body.inc', '', basename($file));
        
        // Exclusion list (Rules compliance)
        $exclude = ['index', 'sitemap', 'empty', '403', '404', 'header', 'footer'];
        if (in_array($slug, $exclude, true)) {
            continue;
        }

        $masterFile = __DIR__ . '/' . $slug . '.php';

        if (file_exists($masterFile)) {
            $mTime = max(filemtime($masterFile), filemtime($file));
            $pubDate = date(DATE_RSS, $mTime);
            $title = ucfirst(str_replace('-', ' ', $slug));

            echo '    <item>' . PHP_EOL;
            echo '      <title>' . $title . '</title>' . PHP_EOL;
            echo '      <link>' . $baseUrl . $slug . '.php</link>' . PHP_EOL;
            echo '      <description>Updates for the ' . $title . ' module in the v4.0.0-alpha Laboratory.</description>' . PHP_EOL;
            echo '      <guid isPermaLink="true">' . $baseUrl . $slug . '.php</guid>' . PHP_EOL;
            echo '      <pubDate>' . $pubDate . '</pubDate>' . PHP_EOL;
            echo '    </item>' . PHP_EOL;
        }
    }
}

echo '  </channel>' . PHP_EOL;
echo '</rss>';

/**
 * [PERFORMANCE] Clean Exit
 */
exit;
