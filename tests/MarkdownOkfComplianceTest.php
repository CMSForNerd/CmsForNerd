<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Class MarkdownOkfComplianceTest
 *
 * Audits markdown documentation files to assure 100% compliance with Open Knowledge Format (OKF) v0.1,
 * frontmatter rules, and digital sovereignty footer standards.
 *
 * @package CmsForNerd\Tests
 */
final class MarkdownOkfComplianceTest extends TestCase
{
    /** @var array<int, string> List of Markdown files found in the repository root and docs directory. */
    private array $markdownFiles = [];

    protected function setUp(): void
    {
        $rootDir = dirname(__DIR__);

        // Scan root directory and docs/ directory for markdown files
        $this->markdownFiles = $this->findMarkdownFiles($rootDir);
    }

    /**
     * Recursively finds markdown files to verify, excluding build or vendor directories.
     *
     * @param string $dir
     * @return array<int, string>
     */
    private function findMarkdownFiles(string $dir): array
    {
        $files = [];
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));

        foreach ($iterator as $file) {
            /** @var \SplFileInfo $file */
            if ($file->isFile() && $file->getExtension() === 'md') {
                $path = $file->getRealPath();

                // Exclude vendor, .git, node_modules, build, asimp, and data checks
                if (
                    str_contains($path, DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR) ||
                    str_contains($path, DIRECTORY_SEPARATOR . '.git' . DIRECTORY_SEPARATOR) ||
                    str_contains($path, DIRECTORY_SEPARATOR . 'node_modules' . DIRECTORY_SEPARATOR) ||
                    str_contains($path, DIRECTORY_SEPARATOR . 'asimp' . DIRECTORY_SEPARATOR) ||
                    str_contains($path, DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR) ||
                    str_contains($path, DIRECTORY_SEPARATOR . 'build' . DIRECTORY_SEPARATOR) ||
                    str_contains($path, DIRECTORY_SEPARATOR . 'build_static' . DIRECTORY_SEPARATOR)
                ) {
                    continue;
                }

                $files[] = $path;
            }
        }

        return $files;
    }

    /**
     * Verifies that each compliant Markdown file contains the correct OKF v0.1 frontmatter.
     */
    public function testMarkdownFilesHaveOkfFrontmatter(): void
    {
        $this->assertNotEmpty($this->markdownFiles, "Should have found Markdown files to test.");

        foreach ($this->markdownFiles as $path) {
            $content = (string) file_get_contents($path);

            // Strip UTF-8 BOM if present
            if (str_starts_with($content, "\xef\xbb\xbf")) {
                $content = substr($content, 3);
            }

            // Skip empty files or template/guideline files if they are not meant to be compliant
            if (trim($content) === '') {
                continue;
            }

            $lines = explode("\n", $content);
            $this->assertNotEmpty($lines);

            // Locate opening delimiter on the very first non-empty line
            $openingLineIndex = null;
            foreach ($lines as $idx => $line) {
                if (trim($line) !== '') {
                    $openingLineIndex = $idx;
                    break;
                }
            }

            $this->assertNotNull($openingLineIndex, "Markdown file {$path} is empty.");
            $this->assertSame(
                '---',
                trim($lines[$openingLineIndex]),
                "Markdown file {$path} must start with YAML frontmatter delimiters ('---')."
            );

            // Locate closing delimiter only on lines whose trimmed content is exactly '---'
            $closingLineIndex = null;
            for ($i = $openingLineIndex + 1; $i < count($lines); $i++) {
                if (trim($lines[$i]) === '---') {
                    $closingLineIndex = $i;
                    break;
                }
            }

            $this->assertNotNull(
                $closingLineIndex,
                "Markdown file {$path} must contain a closing YAML frontmatter delimiter ('---')."
            );

            // Parse keys into an associative mapping (accepting only entries with zero indentation)
            $parsedKeys = [];
            for ($i = $openingLineIndex + 1; $i < $closingLineIndex; $i++) {
                $line = $lines[$i];
                $trimmed = trim($line);

                if ($trimmed === '' || str_starts_with($trimmed, '#')) {
                    continue;
                }

                // Match only lines with zero indentation (must start with non-space/non-tab word characters and have a colon)
                if (preg_match('/^([a-zA-Z0-9_\-]+)\s*:/', $line, $matches)) {
                    $parsedKeys[$matches[1]] = true;
                }
            }

            // Validate the required OKF keys from that YAML mapping
            $requiredKeys = ['okf_version', 'type', 'title', 'timestamp', 'topics'];
            foreach ($requiredKeys as $key) {
                $this->assertArrayHasKey(
                    $key,
                    $parsedKeys,
                    "Markdown {$path} is missing required OKF frontmatter key: '{$key}'."
                );
            }
        }
    }

    /**
     * Verifies that main sovereign files have appropriate footer structures.
     */
    public function testCoreSovereignFilesHaveFooterStandard(): void
    {
        $coreFiles = [
            dirname(__DIR__) . '/AGENTS.md',
            dirname(__DIR__) . '/.agents/AGENTS.md',
        ];

        foreach ($coreFiles as $path) {
            if (file_exists($path)) {
                $content = (string) file_get_contents($path);

                $this->assertStringContainsString(
                    'Harisfazillah Jamel',
                    $content,
                    "Sovereign gate document {$path} must contain the author name in its footer/metadata."
                );

                $this->assertStringContainsString(
                    'GNU General Public License v3.0',
                    $content,
                    "Sovereign gate document {$path} must declare standard GNU GPL v3.0 license info."
                );
            }
        }
    }
}
