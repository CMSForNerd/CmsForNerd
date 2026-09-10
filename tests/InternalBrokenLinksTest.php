<?php

declare(strict_types=1);

/**
 * ==========================================================================
 * FILE: tests/InternalBrokenLinksTest.php
 * ROLE: Unit Test Suite for Internal Page Links Validation (v4.3.1)
 * LICENSE: GNU General Public License v3.0
 * ==========================================================================
 */

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
        $filesToScan = $this->getProjectFilesToScan();
        $this->assertNotEmpty($filesToScan, "Project files for link scanning MUST NOT be empty.");

        $internalLinks = $this->extractInternalLinks($filesToScan);
        $this->assertNotEmpty($internalLinks, "Internal links count MUST NOT be empty.");

        $brokenLinks = $this->validateInternalLinkTargets($internalLinks);

        $this->assertEmpty(
            $brokenLinks,
            "Found broken internal links between pages in project:\n" . implode("\n", $brokenLinks)
        );
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
     * Extracts internal links and their source files.
     *
     * @param array<string> $filesToScan
     * @return array<string, array<string>>
     */
    private function extractInternalLinks(array $filesToScan): array
    {
        $internalLinks = [];

        foreach ($filesToScan as $filePath) {
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

                if ($this->shouldIgnoreUrl($url)) {
                    continue;
                }

                if (!preg_match('/^(https?:\/\/|\/\/)/i', $url)) {
                    $internalLinks[$url][] = basename($filePath);
                }
            }
        }

        return $internalLinks;
    }

    /**
     * Checks if URL should be excluded from internal validation.
     */
    private function shouldIgnoreUrl(string $url): bool
    {
        return $url === '' ||
            str_starts_with($url, '#') ||
            str_starts_with($url, 'javascript:') ||
            str_starts_with($url, 'mailto:') ||
            str_contains($url, '<?php') ||
            str_contains($url, '<?=') ||
            str_contains($url, '{$');
    }

    /**
     * Validates target local file existence for extracted internal links.
     *
     * @param array<string, array<string>> $internalLinks
     * @return array<string>
     */
    private function validateInternalLinkTargets(array $internalLinks): array
    {
        $brokenLinks = [];

        foreach ($internalLinks as $url => $sources) {
            $parsedPath = parse_url($url, PHP_URL_PATH);

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

        return $brokenLinks;
    }
}
