<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Validates the "Module 22: Context Re-learning & Windows Shell Compliance
 * (v4.2.1)" version synchronisation across the CmsForNerd Laboratory theme
 * and the shared content fragments. These surfaces were still advertising
 * the stale "v3.5" product version and have been bumped to "v4.3.0" so
 * every user-facing location (theme metadata, AMP footer, stylesheet
 * banner, SEO/schema description, and homepage headline) agrees.
 */
final class ThemeVersionUpgradeTest extends TestCase
{
    private const CURRENT_VERSION = '4.3.0';

    private string $themePhpPath;
    private string $pagerPhpPath;
    private string $styleCssPath;
    private string $commonHeaderTagPath;
    private string $indexBodyPath;

    protected function setUp(): void
    {
        $root = dirname(__DIR__);

        $this->themePhpPath = $root . '/themes/CmsForNerd/theme.php';
        $this->pagerPhpPath = $root . '/themes/CmsForNerd/pager.php';
        $this->styleCssPath = $root . '/themes/CmsForNerd/style.css';
        $this->commonHeaderTagPath = $root . '/contents/common-headertag.inc';
        $this->indexBodyPath = $root . '/contents/index-body.inc';
    }

    // ---------------------------------------------------------------
    // themes/CmsForNerd/theme.php
    // ---------------------------------------------------------------

    public function testThemePhpDeclaresTheCurrentVersionConstant(): void
    {
        $content = (string) file_get_contents($this->themePhpPath);

        $this->assertStringContainsString('$THEME_VERSION = "' . self::CURRENT_VERSION . '";', $content);
    }

    public function testThemePhpNoLongerDeclaresTheStaleVersionConstant(): void
    {
        $content = (string) file_get_contents($this->themePhpPath);

        $this->assertStringNotContainsString('$THEME_VERSION = "3.5.0";', $content);
    }

    public function testThemeVersionConstantAppearsExactlyOnce(): void
    {
        $content = (string) file_get_contents($this->themePhpPath);

        $this->assertSame(1, substr_count($content, '$THEME_VERSION = '));
    }

    public function testIncludingThemePhpExposesTheUpdatedVersionAtRuntime(): void
    {
        $result = $this->includeThemeConfig();

        $this->assertSame(self::CURRENT_VERSION, $result['THEME_VERSION']);
        $this->assertSame('Harisfazillah Jamel', $result['THEME_AUTHOR']);
        $this->assertSame('CmsForNerd Laboratory', $result['THEME_NAME']);
        $this->assertSame('/themes/CmsForNerd/style.css', $result['CSSPATH']);
    }

    /**
     * theme.php guards against direct HTTP access by checking
     * `isset($themeName)` and returning a 403 otherwise. This helper
     * replicates the calling convention used by the bootstrap layer
     * (defining $themeName before including the file) so the variable
     * assignments — including the changed $THEME_VERSION line — are
     * exercised as real, executed PHP rather than only pattern-matched.
     *
     * @return array{THEME_VERSION: string, THEME_AUTHOR: string, THEME_NAME: string, CSSPATH: string}
     */
    private function includeThemeConfig(): array
    {
        $path = $this->themePhpPath;
        $capture = static function () use ($path): array {
            $themeName = 'CmsForNerd';
            include $path;

            return [
                'THEME_VERSION' => $THEME_VERSION,
                'THEME_AUTHOR' => $THEME_AUTHOR,
                'THEME_NAME' => $THEME_NAME,
                'CSSPATH' => $CSSPATH,
            ];
        };

        return $capture();
    }

    // ---------------------------------------------------------------
    // themes/CmsForNerd/pager.php
    // ---------------------------------------------------------------

    public function testPagerPhpAmpFooterAdvertisesTheCurrentVersion(): void
    {
        $content = (string) file_get_contents($this->pagerPhpPath);

        $this->assertStringContainsString(
            "<p>&copy; <?= date('Y') ?> CmsForNerd v" . self::CURRENT_VERSION . ' Laboratory</p>',
            $content
        );
    }

    public function testPagerPhpNoLongerAdvertisesTheStaleVersionInTheAmpFooter(): void
    {
        $content = (string) file_get_contents($this->pagerPhpPath);

        $this->assertStringNotContainsString('CmsForNerd v3.5 Laboratory', $content);
    }

    // ---------------------------------------------------------------
    // themes/CmsForNerd/style.css
    // ---------------------------------------------------------------

    public function testStyleCssHeaderBannerAdvertisesTheCurrentVersion(): void
    {
        $content = (string) file_get_contents($this->styleCssPath);

        $this->assertStringContainsString('THEME: Lab_v4 (CMSForNerd v' . self::CURRENT_VERSION . ')', $content);
    }

    public function testStyleCssHeaderBannerNoLongerAdvertisesTheStaleVersion(): void
    {
        $content = (string) file_get_contents($this->styleCssPath);

        $this->assertStringNotContainsString('Lab_v3 (CMSForNerd v3.5)', $content);
    }

    // ---------------------------------------------------------------
    // contents/common-headertag.inc
    // ---------------------------------------------------------------

    public function testCommonHeaderTagSchemaDescriptionFallbackAdvertisesTheCurrentVersion(): void
    {
        $content = (string) file_get_contents($this->commonHeaderTagPath);

        $this->assertStringContainsString(
            "A modern PHP 8.4+ educational CMS environment (v" . self::CURRENT_VERSION . ').',
            $content
        );
    }

    public function testCommonHeaderTagNoLongerAdvertisesTheStaleVersion(): void
    {
        $content = (string) file_get_contents($this->commonHeaderTagPath);

        $this->assertStringNotContainsString(
            'A modern PHP 8.4+ educational CMS environment (v3.5).',
            $content
        );
    }

    public function testCommonHeaderTagSchemaDescriptionFallbackRemainsHtmlEscaped(): void
    {
        $content = (string) file_get_contents($this->commonHeaderTagPath);

        $this->assertStringContainsString(
            "<?= htmlspecialchars(\$ctx->content['description'] ?? 'A modern PHP 8.4+ educational CMS environment (v"
            . self::CURRENT_VERSION . ").') ?>",
            $content,
            'The version bump must not have removed the htmlspecialchars() XSS-prevention wrapper.'
        );
    }

    // ---------------------------------------------------------------
    // contents/index-body.inc
    // ---------------------------------------------------------------

    public function testIndexBodyHeroHeadlineAdvertisesTheCurrentVersion(): void
    {
        $content = (string) file_get_contents($this->indexBodyPath);

        $this->assertStringContainsString(
            '<h1>Welcome to CMSForNerd v' . self::CURRENT_VERSION . ': The Secure Coding Laboratory</h1>',
            $content
        );
    }

    public function testIndexBodyNoLongerAdvertisesTheStaleVersionInTheHeadline(): void
    {
        $content = (string) file_get_contents($this->indexBodyPath);

        $this->assertStringNotContainsString(
            'Welcome to CMSForNerd v3.5: The Secure Coding Laboratory',
            $content
        );
    }

    public function testIndexBodyHeadlineAppearsExactlyOnce(): void
    {
        $content = (string) file_get_contents($this->indexBodyPath);

        $this->assertSame(1, substr_count($content, 'Welcome to CMSForNerd v'));
    }

    // ---------------------------------------------------------------
    // Cross-file consistency
    // ---------------------------------------------------------------

    public function testEveryUpgradedSurfaceAdvertisesTheSameCurrentVersion(): void
    {
        foreach (
            [
                $this->themePhpPath,
                $this->pagerPhpPath,
                $this->styleCssPath,
                $this->commonHeaderTagPath,
                $this->indexBodyPath,
            ] as $path
        ) {
            $content = (string) file_get_contents($path);

            $this->assertStringContainsString(
                self::CURRENT_VERSION,
                $content,
                "Expected '{$path}' to reference the current version '" . self::CURRENT_VERSION . "'."
            );
        }
    }

    public function testNoUpgradedSurfaceStillReferencesTheStaleV35Version(): void
    {
        foreach (
            [
                $this->themePhpPath,
                $this->pagerPhpPath,
                $this->styleCssPath,
                $this->commonHeaderTagPath,
                $this->indexBodyPath,
            ] as $path
        ) {
            $content = (string) file_get_contents($path);

            $this->assertDoesNotMatchRegularExpression(
                '/\bv3\.5\b/',
                $content,
                "Regression guard: '{$path}' must not still reference the stale v3.5 product version."
            );
        }
    }
}
