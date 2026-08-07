<?php

declare(strict_types=1);

/**
 * ==========================================================================
 * FILE: /sitemap.php
 * ROLE: Standalone XML Sitemap Generator (v4.3.0)
 * DESCRIPTION: Operates independently of the core engine to prevent
 * HTML/XML header conflicts and 500 errors.
 * SECURITY: Implements Buffer Hardening, Strict CSP, and Pair Logic.
 * ==========================================================================
 */

/**
 * 1. [SECURITY] OUTPUT BUFFER HARDENING
 * Clears any buffers to prevent server-level HTML injection or
 * auto-prepended code from corrupting the XML structure.
 */
while (ob_get_level()) {
    ob_end_clean();
}

/**
 * 2. [EDUCATION] AUTO-URL DETECTION
 * Manually calculates the Base URL to ensure compatibility across
 * localhost, development domains (.test), and production servers.
 */
require_once __DIR__ . '/vendor/autoload.php';
$baseUrl = \CmsForNerd\SecurityUtils::getSafeBaseUrl();

/**
 * 3. [SECURITY] HARDENED HEADERS
 * We apply strict headers to ensure the browser treats this as pure data.
 */
header("Content-Type: application/xml; charset=utf-8");
header("X-Content-Type-Options: nosniff"); // Prevents MIME-type sniffing
header("X-Frame-Options: DENY");           // Prevents clickjacking

/**
 * [SECURITY] RESTRICTIVE CSP
 * Since this is XML, we disable all external resources (scripts, styles, etc.)
 * to prevent any form of XSS or injection.
 */
header("Content-Security-Policy: default-src 'none'; style-src 'none';");

/**
 * [SECURITY] CACHE CONTROL
 * Ensures search engines and browsers always fetch the latest version
 * from the server rather than relying on an outdated cached copy.
 */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

/**
 * 4. [XML START]
 */
echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

/**
 * 5. [SEO] PRIMARY ENTRY POINT
 * The root index.php is the first 'specimen' to be indexed.
 */
echo "  <url>" . PHP_EOL;
echo "    <loc>" . \CmsForNerd\SecurityUtils::escapeHtml($baseUrl . 'index.php') . "</loc>" . PHP_EOL;
echo "    <priority>1.0</priority>" . PHP_EOL;
echo "  </url>" . PHP_EOL;

/**
 * 6. [AUTOMATION] THE SCANNING ENGINE
 * Scans the laboratory contents directory for valid page pairs.
 */
$pages = \CmsForNerd\SecurityUtils::discoverPages(__DIR__ . '/contents/', __DIR__);

foreach ($pages as $page) {
    $slug    = $page['slug'];
    $isoDate = date("c", $page['mtime']);

    echo "  <url>" . PHP_EOL;
    echo "    <loc>" . \CmsForNerd\SecurityUtils::escapeHtml($baseUrl . $slug . '.php') . "</loc>" . PHP_EOL;
    echo "    <lastmod>{$isoDate}</lastmod>" . PHP_EOL;
    echo "    <priority>0.8</priority>" . PHP_EOL;
    echo "  </url>" . PHP_EOL;
}

// 7. [XML END]
echo '</urlset>';

/**
 * [PERFORMANCE] CLEAN EXIT
 * Terminates script execution to ensure no accidental trailing spaces
 * in the file are appended to the XML stream.
 */
exit;
