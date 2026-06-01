<?php

declare(strict_types=1);

/**
 * ==========================================================================
 * FILE: /sitemap.php
 * ROLE: Standalone XML Sitemap Generator (v4.0.0-alpha)
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
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$host     = $_SERVER['HTTP_HOST'] ?? 'localhost';
$dirPath  = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$baseUrl  = rtrim($protocol . $host . $dirPath, '/') . '/';

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
header("Content-Security-Policy: default-src 'none'; style-src 'self';");

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
echo "    <loc>{$baseUrl}index.php</loc>" . PHP_EOL;
echo "    <priority>1.0</priority>" . PHP_EOL;
echo "  </url>" . PHP_EOL;

/**
 * 6. [AUTOMATION] THE SCANNING ENGINE
 * Scans the laboratory contents directory for valid page pairs.
 */
$fragmentDir = __DIR__ . '/contents/';

if (is_dir($fragmentDir)) {
    // Look for all Slave Fragments
    $fragments = glob($fragmentDir . '*-body.inc');

    foreach ($fragments as $file) {
        /**
         * [EDUCATION] SLUG EXTRACTION
         * Extracting the page name (e.g., 'about') from 'about-body.inc'.
         */
        $slug = str_replace('-body.inc', '', basename($file));

        /**
         * [SECURITY] RULE #8 COMPLIANCE
         * Prevent sensitive system files or error pages from being indexed.
         */
        $exclude = ['index', 'sitemap', 'empty', '403', '404', 'header', 'footer'];
        if (in_array($slug, $exclude, true)) {
            continue;
        }

        /**
         * [SECURITY] PAIR LOGIC VERIFICATION
         * Only index the page if the Master Controller (.php) exists in the root.
         */
        $masterFile = __DIR__ . '/' . $slug . '.php';

        if (file_exists($masterFile)) {
            /**
             * [LAB] DATE SYNCHRONIZATION
             * We find the NEWEST modification date between the logic (.php)
             * and the content (-body.inc) for SEO accuracy.
             */
            $mTime = max(filemtime($masterFile), filemtime($file));
            $isoDate = date("c", $mTime); // ISO 8601 format

            echo "  <url>" . PHP_EOL;
            echo "    <loc>{$baseUrl}{$slug}.php</loc>" . PHP_EOL;
            echo "    <lastmod>{$isoDate}</lastmod>" . PHP_EOL;
            echo "    <priority>0.8</priority>" . PHP_EOL;
            echo "  </url>" . PHP_EOL;
        }
    }
}

// 7. [XML END]
echo '</urlset>';

/**
 * [PERFORMANCE] CLEAN EXIT
 * Terminates script execution to ensure no accidental trailing spaces
 * in the file are appended to the XML stream.
 */
exit;
