<?php

declare(strict_types=1);

/**
 * ==========================================================================
 * FILE: tests/ExternalBrokenLinksTest.php
 * ROLE: Unit Test Suite for External Site Links Validation (v4.3.1)
 * LICENSE: GNU General Public License v3.0
 * ==========================================================================
 */

namespace CmsForNerd\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
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
     * and are not broken (404 Not Found, 5xx server error, or unresolvable connection errors).
     */
    public function testExternalLinksReferringToOtherSitesAreReachable(): void
    {
        $filesToScan = $this->getProjectFilesToScan();
        $this->assertNotEmpty($filesToScan, "Project files for link scanning MUST NOT be empty.");

        $externalLinks = $this->extractExternalLinks($filesToScan);
        $this->assertNotEmpty($externalLinks, "External links count MUST NOT be empty.");

        $brokenLinks = $this->checkExternalLinksReachability($externalLinks);

        $this->assertEmpty(
            $brokenLinks,
            "Found broken external links in project contents or menus:\n" . implode("\n", $brokenLinks)
        );
    }

    /**
     * @param bool $expected Whether the response must be classified as broken.
     */
    #[DataProvider('externalLinkStatusCases')]
    public function testBrokenExternalLinkClassification(
        string $url,
        int $httpCode,
        int $curlErrno,
        bool $expected
    ): void {
        $this->assertSame($expected, $this->isBrokenExternalLink($url, $httpCode, $curlErrno));
    }

    /**
     * @return array<string, array{string, int, int, bool}>
     */
    public static function externalLinkStatusCases(): array
    {
        return [
            'successful page' => ['https://example.com/docs', 200, 0, false],
            'redirected page' => ['https://example.com/docs', 301, 0, false],
            'forbidden page' => ['https://example.com/docs', 403, 0, false],
            'missing page' => ['https://example.com/missing', 404, 0, true],
            'gone page' => ['https://example.com/removed', 410, 0, true],
            'first server error boundary' => ['https://example.com/docs', 500, 0, true],
            'last server error boundary' => ['https://example.com/docs', 599, 0, true],
            'status above server error range' => ['https://example.com/docs', 600, 0, false],
            'transport failure without response' => ['https://example.com/docs', 0, 6, true],
            'no response and no cURL error' => ['https://example.com/docs', 0, 0, false],
            'origin-only bad request exemption' => ['https://example.com', 400, 0, false],
            'origin-only missing exemption' => ['https://example.com/', 404, 0, false],
            'origin-only method exemption' => ['https://example.com?preconnect=1', 405, 0, false],
            'origin-only gone response' => ['https://example.com/', 410, 0, true],
            'origin-only server error' => ['https://example.com/', 503, 0, true],
        ];
    }

    public function testExtractExternalLinksNormalizesProtocolRelativeUrlsAndTracksSources(): void
    {
        $fixture = tempnam(sys_get_temp_dir(), 'cmsfornerd-external-links-');
        $this->assertNotFalse($fixture, 'Failed to create the external-link fixture.');

        $markup = <<<'HTML'
            <a href="https://example.com/docs">Docs</a>
            <a href="https://example.com/docs">Docs again</a>
            <script src="//cdn.example.com/library.js"></script>
            <form action="HTTP://forms.example.com/submit"></form>
            <a href="about.php">Internal</a>
            <a href="mailto:maintainer@example.com">Email</a>
            <a href="https://evil.com/trap">Security fixture</a>
            HTML;

        try {
            $this->assertNotFalse(file_put_contents($fixture, $markup));

            $links = $this->extractExternalLinks([
                $fixture,
                sys_get_temp_dir() . '/vendor/not-read.php',
                sys_get_temp_dir() . '/tests/not-read.php',
            ]);

            $source = basename($fixture);
            $this->assertSame(
                [
                    'https://example.com/docs' => [$source, $source],
                    'https://cdn.example.com/library.js' => [$source],
                    'HTTP://forms.example.com/submit' => [$source],
                ],
                $links
            );
        } finally {
            unlink($fixture);
        }
    }

    public function testExternalReachabilityCheckAcceptsAnEmptyBatch(): void
    {
        $this->assertSame([], $this->checkExternalLinksReachability([]));
    }

    public function testCurlHandleInitializationPreservesEveryUrl(): void
    {
        $multiHandle = curl_multi_init();
        $this->assertNotFalse($multiHandle, 'Failed to initialize the test multi-cURL handle.');

        $urls = [
            'https://example.com/first',
            'https://example.org/second',
        ];
        $handles = $this->initCurlHandles($multiHandle, $urls);

        try {
            $this->assertSame($urls, array_keys($handles));

            foreach ($handles as $url => $handle) {
                $this->assertSame($url, curl_getinfo($handle, CURLINFO_EFFECTIVE_URL));
            }
        } finally {
            foreach ($handles as $handle) {
                curl_multi_remove_handle($multiHandle, $handle);
                curl_close($handle);
            }

            curl_multi_close($multiHandle);
        }
    }

    /**
     * Finds candidate PHP and template files in the project.
     *
     * @return array<string>
     */
    private function getProjectFilesToScan(): array
    {
        return array_merge(
            glob($this->rootDir . '/*.php') ?: [],
            glob($this->rootDir . '/contents/*.inc') ?: [],
            glob($this->rootDir . '/includes/*.inc') ?: [],
            glob($this->rootDir . '/includes/*.php') ?: []
        );
    }

    /**
     * Extracts external links and their source files.
     *
     * @param array<string> $filesToScan
     * @return array<string, array<string>>
     */
    private function extractExternalLinks(array $filesToScan): array
    {
        $externalLinks = [];

        foreach ($filesToScan as $filePath) {
            if (str_contains($filePath, '/vendor/') || str_contains($filePath, '/tests/')) {
                continue;
            }

            $this->processFileForExternalLinks($filePath, $externalLinks);
        }

        return $externalLinks;
    }

    /**
     * Processes a single file to extract external links.
     *
     * @param array<string, array<string>> $externalLinks
     */
    private function processFileForExternalLinks(string $filePath, array &$externalLinks): void
    {
        $content = file_get_contents($filePath);
        if ($content === false) {
            return;
        }

        preg_match_all('/(?:href|src|action)=["\']([^"\'>\s]+)["\']/i', $content, $matches);

        if (empty($matches[1])) {
            return;
        }

        foreach ($matches[1] as $rawUrl) {
            $this->addExternalUrlIfValid(trim($rawUrl), basename($filePath), $externalLinks);
        }
    }

    /**
     * Adds URL to externalLinks if it matches external protocol and is not a trap URL.
     *
     * @param array<string, array<string>> $externalLinks
     */
    private function addExternalUrlIfValid(string $url, string $filename, array &$externalLinks): void
    {
        if (preg_match('/^(https?:\/\/|\/\/)/i', $url)) {
            $fullUrl = str_starts_with($url, '//') ? 'https:' . $url : $url;

            if (!str_contains($fullUrl, 'evil.com')) {
                $externalLinks[$fullUrl][] = $filename;
            }
        }
    }

    /**
     * Checks reachability of extracted external URLs using multi-cURL handles.
     *
     * @param array<string, array<string>> $externalLinks
     * @return array<string>
     */
    private function checkExternalLinksReachability(array $externalLinks): array
    {
        $mh = curl_multi_init();
        if ($mh === false) {
            $this->fail("Failed to initialize multi-cURL handle.");
        }

        $curlHandles = $this->initCurlHandles($mh, array_keys($externalLinks));

        $this->executeMultiCurl($mh);

        $brokenLinks = $this->collectBrokenExternalLinks($mh, $curlHandles, $externalLinks);

        curl_multi_close($mh);

        return $brokenLinks;
    }

    /**
     * Initializes individual cURL handles and attaches them to multi-handle.
     *
     * @param resource $mh
     * @param array<string> $urls
     * @return array<string, \CurlHandle>
     */
    private function initCurlHandles($mh, array $urls): array
    {
        $curlHandles = [];

        foreach ($urls as $url) {
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

            curl_multi_add_handle($mh, $ch);
            $curlHandles[$url] = $ch;
        }

        return $curlHandles;
    }

    /**
     * Executes multi-cURL transfers.
     *
     * @param resource $mh
     */
    private function executeMultiCurl($mh): void
    {
        $active = null;
        do {
            $mrc = curl_multi_exec($mh, $active);
        } while ($mrc === CURLM_OK && $active > 0);

        while ($active && $mrc === CURLM_OK) {
            if (curl_multi_select($mh, 0.5) !== -1) {
                do {
                    $mrc = curl_multi_exec($mh, $active);
                } while ($mrc === CURLM_OK && $active > 0);
            } else {
                usleep(10000);
            }
        }
    }

    /**
     * Collects broken external links from completed cURL handles.
     *
     * @param resource $mh
     * @param array<string, \CurlHandle> $curlHandles
     * @param array<string, array<string>> $externalLinks
     * @return array<string>
     */
    private function collectBrokenExternalLinks($mh, array $curlHandles, array $externalLinks): array
    {
        $brokenLinks = [];

        foreach ($curlHandles as $url => $ch) {
            $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlErrno = curl_errno($ch);
            $curlError = curl_error($ch);

            curl_multi_remove_handle($mh, $ch);
            curl_close($ch);

            if ($this->isBrokenExternalLink($url, $httpCode, $curlErrno)) {
                $sources = $externalLinks[$url] ?? [];
                $sourceFiles = implode(', ', array_unique($sources));
                $brokenLinks[] = "Broken external link: '{$url}' (HTTP Status: {$httpCode}, cURL Error: [{$curlErrno}] {$curlError}, Referenced in: {$sourceFiles})";
            }
        }

        return $brokenLinks;
    }

    /**
     * Determines if response status indicates a broken external link.
     */
    private function isBrokenExternalLink(string $url, int $httpCode, int $curlErrno): bool
    {
        $parsedUrlPath = parse_url($url, PHP_URL_PATH);
        $isOriginOnlyUrl = ($parsedUrlPath === null || $parsedUrlPath === '' || $parsedUrlPath === '/');

        if ($isOriginOnlyUrl && in_array($httpCode, [400, 404, 405], true)) {
            return false;
        }

        return $httpCode === 404 ||
            $httpCode === 410 ||
            ($httpCode >= 500 && $httpCode <= 599) ||
            ($httpCode === 0 && $curlErrno !== 0);
    }
}
