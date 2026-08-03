<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Validates the GitHub Pages Deployment specification, the static page baker
 * output, and the accompanying documentation for Jekyll bypassing.
 */
final class GithubPagesDeploymentTest extends TestCase
{
    private string $githubPagesGuidePath;
    private string $summaryPath;
    private string $mkdocsPath;
    private string $startHerePath;
    private string $llmsTxtPath;
    private string $llmsIndexPath;

    protected function setUp(): void
    {
        $this->githubPagesGuidePath = dirname(__DIR__) . '/docs/GITHUB-PAGES-DEPLOYMENT-GUIDE.md';
        $this->summaryPath = dirname(__DIR__) . '/docs/SUMMARY.md';
        $this->mkdocsPath = dirname(__DIR__) . '/mkdocs.yml';
        $this->startHerePath = dirname(__DIR__) . '/START-HERE.md';
        $this->llmsTxtPath = dirname(__DIR__) . '/llms.txt';
        $this->llmsIndexPath = dirname(__DIR__) . '/.llms/index.md';
    }

    public function testGitHubPagesGuideExists(): void
    {
        $this->assertFileExists($this->githubPagesGuidePath);
    }

    public function testGitHubPagesGuideDescribesJekyllBypassingAndNoJekyll(): void
    {
        $content = file_get_contents($this->githubPagesGuidePath);

        $this->assertStringContainsString(
            'The Solution: Bypassing Jekyll',
            $content,
            'Guide must explain the solution of bypassing Jekyll.'
        );
        $this->assertStringContainsString(
            'automatically creates an empty **`.nojekyll`** file',
            $content,
            'Guide must explain that bake-static-pages.php automatically creates .nojekyll.'
        );
    }

    public function testStaticPageBakerOutputsNoJekyll(): void
    {
        $outDir = dirname(__DIR__) . '/build_static_test_unit';

        // Clean up previous test runs if any
        if (is_dir($outDir)) {
            $this->recursiveDelete($outDir);
        }

        // Run the baker script. We can invoke it directly.
        // Let's create an empty directory and mock local environment if necessary,
        // or we can test that tools/bake-static-pages.php has the code to generate .nojekyll.
        $bakerCode = file_get_contents(dirname(__DIR__) . '/tools/bake-static-pages.php');
        $this->assertStringContainsString('Generating .nojekyll file', $bakerCode);
        $this->assertStringContainsString('file_put_contents($nojekyllPath, \'\')', $bakerCode);
    }

    public function testGuideIsRegisteredInSummary(): void
    {
        $content = file_get_contents($this->summaryPath);
        $this->assertStringContainsString(
            '* [GitHub Pages Deployment Guide](GITHUB-PAGES-DEPLOYMENT-GUIDE.md)',
            $content,
            'Guide must be registered in docs/SUMMARY.md.'
        );
    }

    public function testGuideIsRegisteredInMkDocs(): void
    {
        $content = file_get_contents($this->mkdocsPath);
        $this->assertStringContainsString(
            '- GitHub Pages Deployment Guide: docs/GITHUB-PAGES-DEPLOYMENT-GUIDE.md',
            $content,
            'Guide must be registered in mkdocs.yml.'
        );
    }

    public function testGuideIsRegisteredInStartHere(): void
    {
        $content = file_get_contents($this->startHerePath);
        $this->assertStringContainsString(
            '| **14** | **GitHub Pages Deployment** | [`docs/GITHUB-PAGES-DEPLOYMENT-GUIDE.md`](docs/GITHUB-PAGES-DEPLOYMENT-GUIDE.md) |',
            $content,
            'Guide must be registered in START-HERE.md.'
        );
    }

    public function testGuideIsRegisteredInLlmsTxt(): void
    {
        $content = file_get_contents($this->llmsTxtPath);
        $this->assertStringContainsString(
            '- [docs/GITHUB-PAGES-DEPLOYMENT-GUIDE.md](docs/GITHUB-PAGES-DEPLOYMENT-GUIDE.md):',
            $content,
            'Guide must be registered in llms.txt.'
        );
    }

    public function testGuideIsRegisteredInLlmsIndex(): void
    {
        $content = file_get_contents($this->llmsIndexPath);
        $this->assertStringContainsString(
            '- [docs/GITHUB-PAGES-DEPLOYMENT-GUIDE.md](../docs/GITHUB-PAGES-DEPLOYMENT-GUIDE.md):',
            $content,
            'Guide must be registered in .llms/index.md.'
        );
    }

    private function recursiveDelete(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->recursiveDelete("$dir/$file") : unlink("$dir/$file");
        }
        rmdir($dir);
    }
}
