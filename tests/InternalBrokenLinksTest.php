<?php

/**
 * ==========================================================================
 * FILE: tests/InternalBrokenLinksTest.php
 * ROLE: Unit Test Suite for Internal Page Links Validation (v4.3.1)
 * LICENSE: GNU General Public License v3.0
 * ==========================================================================
 */

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * InternalBrokenLinksTest
 *
 * Validates that all internal links between pages, menus, navigation headers,
 * footers, and content fragments in the project point to existing files.
 */
class InternalBrokenLinksTest extends TestCase
{
    private string $rootDir;

    protected function setUp(): void
    {
        $this->rootDir = (string) realpath(__DIR__ . '/../');
    }

    /**
     * Scans project files for internal links (href, src, action) and asserts
     * that all referenced internal local files exist on disk.
     */
    public function testInternalLinksBetweenPagesExistOnDisk(): void
    {
        $filesToScan = array_merge(
            glob($this->rootDir . '/*.php') ?: [],
            glob($this->rootDir . '/contents/*.inc') ?: [],
            glob($this->rootDir . '/includes/*.inc') ?: [],
            glob($this->rootDir . '/includes/*.php') ?: []
        );

        $this->assertNotEmpty($filesToScan, "Project files for link scanning MUST NOT be empty.");

        $internalLinks = [];

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

                // Ignore empty links, anchor-only (#), mailto, javascript, or PHP dynamic code tags
                if (
                    $url === '' ||
                    str_starts_with($url, '#') ||
                    str_starts_with($url, 'javascript:') ||
                    str_starts_with($url, 'mailto:') ||
                    str_contains($url, '<?php') ||
                    str_contains($url, '<?=') ||
                    str_contains($url, '{$')
                ) {
                    continue;
                }

                // If not an external HTTP/HTTPS or protocol-relative link, treat as internal link
                if (!preg_match('/^(https?:\/\/|\/\/)/i', $url)) {
                    $internalLinks[$url][] = basename($filePath);
                }
            }
        }

        $this->assertNotEmpty($internalLinks, "Internal links count MUST NOT be empty.");

        $brokenLinks = [];

        foreach ($internalLinks as $url => $sources) {
            $parsedPath = parse_url($url, PHP_URL_PATH);

            // Skip query-only URLs like "?view=amp"
            if (empty($parsedPath)) {
                continue;
            }

            $pathToCheck = ltrim((string) $parsedPath, '/');
            $targetFullPath = $this->rootDir . '/' . $pathToCheck;

            if (!file_exists($targetFullPath)) {
                $sourceFiles = implode(', ', array_unique($sources));
                $brokenLinks[] = "Broken internal link: '{$url}' (Referenced in: {$sourceFiles}) -> File not found at: {$targetFullPath}";
            }
        }

        $this->assertEmpty(
            $brokenLinks,
            "Found broken internal links between pages in project:\n" . implode("\n", $brokenLinks)
        );
    }
}
