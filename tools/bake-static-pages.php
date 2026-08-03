<?php

/**
 * CmsForNerd - Automated Static Page Baker
 * Compliance: PHP 8.4+, PSR-12, strict_types=1
 * * ROLE: Spins up or communicates with the local server to crawl and bake
 * all PHP pages to static HTML, translating navigation links to .html.
 */

declare(strict_types=1);

echo "🧪 Starting CmsForNerd Static Page Baker...\n";

$targetUrl = "http://127.0.0.1:8000";

// 1. Scan root for all php pages
$phpFiles = glob("*.php");
if ($phpFiles === false) {
    echo "❌ Failed to read php files in root.\n";
    exit(1);
}

$exclude = [
    'sitemap.php',
    'rss.php',
    'ror.php',
    'theme.php', // Theme configuration
];

foreach ($phpFiles as $file) {
    if (in_array($file, $exclude, true)) {
        echo "⏭️ Skipping utility/data script: $file\n";
        continue;
    }

    $basename = pathinfo($file, PATHINFO_FILENAME);
    $htmlFile = $basename . ".html";
    $url = "$targetUrl/$file";

    echo "🌐 Baking $file -> $htmlFile... ";

    // Retrieve content via cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $html = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if (!is_string($html) || $httpCode !== 200) {
        echo "❌ Failed (HTTP $httpCode / cURL error)\n";
        continue;
    }

    // 2. Rewrite internal navigation links from .php to .html
    // Match: href="some-page.php" or href="some-page.php?param=val" or href="some-page.php#hash"
    // Preserve base path, queries, and hash, but switch extension to .html
    $html = (string) preg_replace_callback(
        '/href=["\']([a-zA-Z0-9_\-]+)\.php(\?[^"\']*)?(#[^"\']*)?["\']/',
        function (array $matches): string {
            $base = $matches[1];
            $query = $matches[2] ?? '';
            $hash = $matches[3] ?? '';
            return 'href="' . $base . '.html' . $query . $hash . '"';
        },
        $html
    );

    // 3. Remove localhost:8000 absolute URLs to make all asset/canonical links beautifully relative
    $html = str_replace(
        ['http://localhost:8000/', 'http://127.0.0.1:8000/'],
        '',
        $html
    );

    // 4. Make absolute assets, CSS and PWA paths relative for GitHub Pages subdirectory hosting
    $html = str_replace(
        [
            'href="/favicon.ico"',
            'href="/labels.rdf"',
            'href="/manifest.json"',
            'src="/assets/',
            'navigator.serviceWorker.register(\'/sw.js\')',
            '@import "/themes/'
        ],
        [
            'href="favicon.ico"',
            'href="labels.rdf"',
            'href="manifest.json"',
            'src="assets/',
            'navigator.serviceWorker.register(\'sw.js\')',
            '@import "themes/'
        ],
        $html
    );

    // 5. Save to static html file
    $result = file_put_contents($htmlFile, $html);
    if ($result === false) {
        echo "❌ Failed to write $htmlFile\n";
    } else {
        echo "✅ Done (" . round(strlen((string)$html) / 1024, 2) . " KB)\n";
    }
}

echo "🎯 Static Baking completed successfully!\n";
