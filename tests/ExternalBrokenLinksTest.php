<?php

/**
 * ==========================================================================
 * FILE: tests/ExternalBrokenLinksTest.php
 * ROLE: Unit Test Suite for External Site Links Validation (v4.3.1)
 * LICENSE: GNU General Public License v3.0
 * ==========================================================================
 */

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * ExternalBrokenLinksTest
 *
 * Validates that all external links referencing third-party websites in menus,
 * footers, sidebars, and content fragments are reachable and free of broken (404/5xx) errors.
 */
class ExternalBrokenLinksTest extends TestCase
{
    private string $rootDir;

    protected function setUp(): void
    {
        $this->rootDir = (string) realpath(__DIR__ . '/../');
    }

    /**
     * Scans project files for external HTTP/HTTPS links and validates that
     * they respond with valid status codes (2xx, 3xx, or expected 403 blocks)
     * and are not broken (404 Not Found or unresolvable connection errors).
     */
    public function testExternalLinksReferringToOtherSitesAreReachable(): void
    {
        $filesToScan = array_merge(
            glob($this->rootDir . '/*.php') ?: [],
            glob($this->rootDir . '/contents/*.inc') ?: [],
            glob($this->rootDir . '/includes/*.inc') ?: [],
            glob($this->rootDir . '/includes/*.php') ?: []
        );

        $this->assertNotEmpty($filesToScan, "Project files for link scanning MUST NOT be empty.");

        $externalLinks = [];

        foreach ($filesToScan as $filePath) {
            // Exclude vendor directory and test files themselves
            if (str_contains($filePath, '/vendor/') || str_contains($filePath, '/tests/')) {
                continue;
            }

            $content = file_get_contents($filePath);
            if ($content === false) {
                continue;
            }

            preg_match_all('/(?:href|src|action)=["\']([^"\'>\s]+)["\']/i', $content, $matches);

            if (empty($matches[1])) {
                continue;
            }

            foreach ($matches[1] as $rawUrl) {
                $url = trim($rawUrl);

                // Check for external protocol (http://, https://, or //)
                if (preg_match('/^(https?:\/\/|\/\/)/i', $url)) {
                    $fullUrl = str_starts_with($url, '//') ? 'https:' . $url : $url;

                    // Exclude intentional security test trap URLs like evil.com
                    if (str_contains($fullUrl, 'evil.com')) {
                        continue;
                    }

                    $externalLinks[$fullUrl][] = basename($filePath);
                }
            }
        }

        $this->assertNotEmpty($externalLinks, "External links count MUST NOT be empty.");

        $mh = curl_multi_init();
        if ($mh === false) {
            $this->fail("Failed to initialize multi-cURL handle.");
        }

        $curlHandles = [];

        foreach (array_keys($externalLinks) as $url) {
            $ch = curl_init($url);
            if ($ch === false) {
                continue;
            }

            curl_setopt($ch, CURLOPT_HTTPGET, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
            curl_setopt($ch, CURLOPT_TIMEOUT, 6);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) CmsForNerdLinkChecker/1.0');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

            curl_multi_add_handle($mh, $ch);
            $curlHandles[$url] = $ch;
        }

        $active = null;
        do {
            $mrc = curl_multi_exec($mh, $active);
        } while ($mrc === CURLM_OK && $active > 0);

        while ($active && $mrc === CURLM_OK) {
            if (curl_multi_select($mh, 0.5) !== -1) {
                do {
                    $mrc = curl_multi_exec($mh, $active);
                } while ($mrc === CURLM_OK && $active > 0);
            }
        }

        $brokenLinks = [];

        foreach ($curlHandles as $url => $ch) {
            $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlErrno = curl_errno($ch);
            $curlError = curl_error($ch);

            curl_multi_remove_handle($mh, $ch);
            curl_close($ch);

            // Origin / CDN root domains (used for preconnect / dns-prefetch / CSP directives) return 400/404 when fetched without endpoint path
            $parsedUrlPath = parse_url($url, PHP_URL_PATH);
            $isOriginOnlyUrl = ($parsedUrlPath === null || $parsedUrlPath === '' || $parsedUrlPath === '/');

            if ($isOriginOnlyUrl && in_array($httpCode, [400, 404, 405], true)) {
                // Origin domain is reachable if DNS resolved and HTTP connection responded
                continue;
            }

            // Flag as broken if status is 404 (Not Found) or 410 (Gone) or if cURL failed due to unresolvable network/DNS error
            if ($httpCode === 404 || $httpCode === 410 || ($httpCode === 0 && $curlErrno !== 0)) {
                $sources = $externalLinks[$url] ?? [];
                $sourceFiles = implode(', ', array_unique($sources));
                $brokenLinks[] = "Broken external link: '{$url}' (HTTP Status: {$httpCode}, cURL Error: [{$curlErrno}] {$curlError}, Referenced in: {$sourceFiles})";
            }
        }

        curl_multi_close($mh);

        $this->assertEmpty(
            $brokenLinks,
            "Found broken external links in project contents or menus:\n" . implode("\n", $brokenLinks)
        );
    }
}
