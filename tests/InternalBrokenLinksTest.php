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

use PHPUnit\Framework\Attributes\DataProvider;
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
     * @param bool $expected Whether the URL must be excluded from file validation.
     */
    #[DataProvider('urlIgnoreCases')]
    public function testUrlIgnoreRules(string $url, bool $expected): void
    {
        $this->assertSame($expected, $this->shouldIgnoreUrl($url));
    }

    /**
     * @return array<string, array{string, bool}>
     */
    public static function urlIgnoreCases(): array
    {
        return [
            'empty URL' => ['', true],
            'fragment-only URL' => ['#installation', true],
            'JavaScript pseudo-link' => ['javascript:void(0)', true],
            'email link' => ['mailto:maintainer@example.com', true],
            'full PHP tag' => ['<?php echo $target; ?>', true],
            'short PHP echo tag' => ['<?= $target ?>', true],
            'interpolated template value' => ['pages/{$slug}.php', true],
            'relative page' => ['about.php', false],
            'root-relative asset' => ['/themes/CmsForNerd/style.css', false],
            'page with query and fragment' => ['about.php?view=full#team', false],
        ];
    }

    public function testExtractInternalLinksKeepsStaticTargetsAndSkipsUnsupportedUrls(): void
    {
        $fixture = tempnam(sys_get_temp_dir(), 'cmsfornerd-internal-links-');
        $this->assertNotFalse($fixture, 'Failed to create the internal-link fixture.');

        $markup = <<<'HTML'
            <a href="about.php">About</a>
            <a href="about.php">About again</a>
            <img src="/themes/CmsForNerd/style.css" alt="Theme">
            <form action="final-exam.php?attempt=1#questions"></form>
            <a href="https://example.com/docs">External</a>
            <a href="//cdn.example.com/library.js">CDN</a>
            <a href="#top">Fragment</a>
            <a href="javascript:void(0)">Script</a>
            <a href="mailto:maintainer@example.com">Email</a>
            <a href="<?= $dynamicTarget ?>">Dynamic</a>
            HTML;

        try {
            $this->assertNotFalse(file_put_contents($fixture, $markup));

            $links = $this->extractInternalLinks([
                $fixture,
                sys_get_temp_dir() . '/vendor/not-read.php',
                sys_get_temp_dir() . '/tests/not-read.php',
            ]);

            $source = basename($fixture);
            $this->assertSame(
                [
                    'about.php' => [$source, $source],
                    '/themes/CmsForNerd/style.css' => [$source],
                    'final-exam.php?attempt=1#questions' => [$source],
                ],
                $links
            );
        } finally {
            unlink($fixture);
        }
    }

    public function testValidateInternalTargetsResolvesPathsAndReportsUniqueSources(): void
    {
        $fixtureRoot = sys_get_temp_dir() . '/cmsfornerd-targets-' . bin2hex(random_bytes(8));
        $assetDirectory = $fixtureRoot . '/assets';

        $this->assertTrue(mkdir($assetDirectory, 0777, true), 'Failed to create the target fixture directory.');
        $this->assertNotFalse(file_put_contents($fixtureRoot . '/existing.php', '<?php'));
        $this->assertNotFalse(file_put_contents($assetDirectory . '/app.css', 'body {}'));

        $originalRoot = $this->rootDir;
        $this->rootDir = $fixtureRoot;

        try {
            $brokenLinks = $this->validateInternalLinkTargets([
                'existing.php?tab=details#summary' => ['menu.inc'],
                '/assets/app.css?v=1' => ['header.inc'],
                '?query=without-path' => ['ignored.inc'],
                'missing.php?tab=details#summary' => ['menu.inc', 'menu.inc', 'sidebar.inc'],
            ]);

            $this->assertSame(
                [
                    "Broken internal link: 'missing.php?tab=details#summary' " .
                    "(Referenced in: menu.inc, sidebar.inc) -> File not found at: {$fixtureRoot}/missing.php",
                ],
                $brokenLinks
            );
        } finally {
            $this->rootDir = $originalRoot;
            unlink($assetDirectory . '/app.css');
            unlink($fixtureRoot . '/existing.php');
            rmdir($assetDirectory);
            rmdir($fixtureRoot);
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

            $this->processFileForInternalLinks($filePath, $internalLinks);
        }

        return $internalLinks;
    }

    /**
     * Processes a single file to extract internal links.
     *
     * @param array<string, array<string>> $internalLinks
     */
    private function processFileForInternalLinks(string $filePath, array &$internalLinks): void
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
            $url = trim($rawUrl);

            if ($this->shouldIgnoreUrl($url)) {
                continue;
            }

            if (!preg_match('/^(https?:\/\/|\/\/)/i', $url)) {
                $internalLinks[$url][] = basename($filePath);
            }
        }
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
