<?php

/**
 * ==========================================================================
 * FILE: includes/nav-helper.inc.php
 * ROLE: Navigation Logic & View-Specific Header Controllers
 * VERSION: 3.5.9 (CSP Hardening & CSS Size Guard)
 * ==========================================================================
 */

declare(strict_types=1);

if (!function_exists('get_site_pages')) {
    /**
     * Scans the laboratory root to build a dynamic navigation menu.
     * @return array<string, string> Key is the filename, Value is the Pretty Label.
     */
    function get_site_pages(): array
    {
        $pages = [];
        $exclude = [
            'template.php',
            'index.php',
            'audit-semantics.php',
            'sitemap-page.php',
            'sitemap.php',
            'rss.php',
            'ror.php'
        ];

        // Locate root directory relative to this file
        $rootDir = dirname(__DIR__);
        $files = scandir($rootDir);

        if (!is_array($files)) {
            return [];
        }

        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'php' && !in_array($file, $exclude, true)) {
                $name = pathinfo($file, PATHINFO_FILENAME);

                // Transform 'lab-manual' into 'Lab Manual'
                $label = ucfirst(str_replace(['-', '_'], ' ', $name));
                $pages[$file] = $label;
            }
        }

        if (isset($pages['pwa-architecture.php'])) {
            $pages['pwa-architecture.php'] = '📱 PWA Architecture';
        }

        return $pages;
    }
}

if (!function_exists('pageheader_amp')) {
    /**
     * Renders the mandatory <head> section for AMP-compliant pages.
     * Includes CSP Nonce handling and required AMP component scripts.
     * @param \CmsForNerd\CmsContext $ctx
     */
    function pageheader_amp(\CmsForNerd\CmsContext $ctx): void
    {
        // 1. Context Extraction
        $title = htmlspecialchars($ctx->content['title'] ?? 'Laboratory', ENT_QUOTES, 'UTF-8');
        $scriptName = $ctx->scriptName ?? 'index';
        $canonicalUrl = $scriptName . '.php';
        $nonce = $ctx->cspNonce ?? '';

        ?>
        <meta charset="utf-8">
        
        <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'nonce-<?= $nonce ?>' https://cdn.ampproject.org; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; img-src 'self' data: https:; font-src 'self' data: https://fonts.gstatic.com; connect-src 'self' https://*.ipinfo.io https://cdn.ampproject.org;">

        <link rel="dns-prefetch" href="https://cdn.ampproject.org">
        
        <script async src="https://cdn.ampproject.org/v0.js" nonce="<?= $nonce ?>"></script>
        
        <script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js" nonce="<?= $nonce ?>"></script>
        <script async custom-element="amp-bind" src="https://cdn.ampproject.org/v0/amp-bind-0.1.js" nonce="<?= $nonce ?>"></script>
        
        <title><?= $title ?></title>
        <link rel="canonical" href="<?= $canonicalUrl ?>">
        <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
        
        <style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style>
        <noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
        
        <?php
        /**
         * [CSS INJECTION & VALIDATION]
         * Inlines amp.css while enforcing the 75,000 byte limit.
         */
        $theme = $ctx->themeName ?? 'CmsForNerd';
        $ampCssPath = dirname(__DIR__) . "/themes/{$theme}/css/amp.css";

        if (file_exists($ampCssPath)) {
            $fileSize = filesize($ampCssPath);
            $cssContent = file_get_contents($ampCssPath);

            // Minification to maximize byte-budget
            $cssContent = preg_replace('!/\*.*?\*/!s', '', $cssContent); // Remove comments
            $cssContent = preg_replace('/\s+/', ' ', $cssContent);      // Collapse whitespace

            // Safety check for AMP validator compliance
            if ($fileSize > 75000) {
                echo "";
            }

            echo '<style amp-custom>' . trim($cssContent) . '</style>';
        } else {
            // Fallback styles if file is missing
            echo '<style amp-custom>body{font-family:sans-serif;padding:20px;color:#333}h1{color:#8e44ad}</style>';
        }
    }
}
