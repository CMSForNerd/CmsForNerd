<?php
declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * UserManualTest
 *
 * Validates the new "Local User Manual" feature introduced by this PR:
 *   - The `user-manual.php` Pair Logic page controller.
 *   - The `contents/user-manual-body.inc` Diátaxis navigation body fragment.
 *   - The `docs/user-manual/README.md` companion overview document.
 */
final class UserManualTest extends TestCase
{
    // ---------------------------------------------------------------
    // File existence
    // ---------------------------------------------------------------

    public function testUserManualFilesExist(): void
    {
        $controller = dirname(__DIR__) . '/user-manual.php';
        $body = dirname(__DIR__) . '/contents/user-manual-body.inc';
        $readme = dirname(__DIR__) . '/docs/user-manual/README.md';

        $this->assertFileExists($controller, 'Page controller user-manual.php must exist.');
        $this->assertFileExists($body, 'Content body contents/user-manual-body.inc must exist.');
        $this->assertFileExists($readme, 'docs/user-manual/README.md must exist.');
    }

    // ---------------------------------------------------------------
    // user-manual.php controller
    // ---------------------------------------------------------------

    public function testStrictTypesInUserManualController(): void
    {
        $content = $this->controllerContent();

        $this->assertStringContainsString(
            'declare(strict_types=1);',
            $content,
            'user-manual.php must declare strict types.'
        );
    }

    public function testUserManualControllerDeclaresExpectedPageMetadata(): void
    {
        $content = $this->controllerContent();

        $this->assertStringContainsString(
            'Local User Manual | CMSForNerd v4.3.0 Laboratory',
            $content,
            'The title metadata must announce the Local User Manual page.'
        );
        $this->assertStringContainsString(
            "'author'      => \"CMSForNerd Engineering Team\"",
            $content,
            'The author metadata must be present.'
        );
        $this->assertStringContainsString(
            'Complete local user manual for CmsForNerd v4.3.0.',
            $content,
            'The meta description must summarize the user manual content.'
        );
        $this->assertStringContainsString(
            'WSL2, AlmaLinux 10, Podman, Diataxis, PHP 8.4, CmsForNerd',
            $content,
            'The SEO keywords metadata must be present.'
        );
        $this->assertStringContainsString(
            "'schemaType'  => \"HowTo\"",
            $content,
            'The schema.org type must be HowTo.'
        );
    }

    public function testUserManualControllerBootstrapsAndDispatchesThroughPager(): void
    {
        $content = $this->controllerContent();

        $this->assertStringContainsString(
            "require_once __DIR__ . '/includes/bootstrap.php';",
            $content,
            'user-manual.php must load the shared bootstrap engine.'
        );
        $this->assertStringContainsString(
            '\CmsForNerd\SecurityUtils::resolvePageName(pathinfo(basename(__FILE__), PATHINFO_FILENAME))',
            $content,
            'The page name must be resolved via the hardened SecurityUtils helper.'
        );
        $this->assertStringContainsString(
            "\$content['data'] = \$pageName;",
            $content,
            'The resolved page name must be used to select the -body.inc fragment.'
        );
        $this->assertStringContainsString(
            'createCmsContext(',
            $content,
            'The controller must build a CmsContext via the shared factory.'
        );
        $this->assertStringContainsString(
            'themes/{$ctx->themeName}/pager.php',
            $content,
            'The controller must dispatch rendering to the theme pager.'
        );
        $this->assertStringContainsString('pager($ctx);', $content, 'The controller must invoke the pager() dispatcher.');
    }

    public function testUserManualControllerEnablesGzipOutputBuffering(): void
    {
        $content = $this->controllerContent();

        $this->assertStringContainsString('ob_start("ob_gzhandler")', $content);
        $this->assertStringContainsString('ob_end_flush();', $content);
    }

    public function testUserManualControllerHandlesMissingPagerGracefully(): void
    {
        $content = $this->controllerContent();

        $this->assertStringContainsString("header('HTTP/1.1 500 Internal Server Error');", $content);
        $this->assertStringContainsString('Fatal Error: Theme engine missing in /themes/', $content);
    }

    public function testUserManualControllerIsSyntacticallyValidPhp(): void
    {
        $this->assertPhpFileLintsCleanly(dirname(__DIR__) . '/user-manual.php');
    }

    // ---------------------------------------------------------------
    // contents/user-manual-body.inc
    // ---------------------------------------------------------------

    public function testUserManualBodyContainsMainHeadingAndDiataxisMention(): void
    {
        $content = $this->bodyContent();

        $this->assertStringContainsString('<h1>📖 CmsForNerd Local User Manual</h1>', $content);
        $this->assertStringContainsString('Diátaxis Framework', $content);
        $this->assertStringContainsString('CmsForNerd v4.3.0', $content);
    }

    public function testUserManualBodyContainsFourDiataxisCategoryCardsInOrder(): void
    {
        $content = $this->bodyContent();

        $headings = [
            '🎓 1. Tutorials',
            '🛠️ 2. How-To Guides',
            '📋 3. Reference',
            '🧠 4. Explanation',
        ];

        $previousPosition = -1;
        foreach ($headings as $heading) {
            $position = strpos($content, $heading);
            $this->assertNotFalse($position, "Missing Diátaxis quadrant heading: {$heading}");
            $this->assertGreaterThan(
                $previousPosition,
                $position,
                "Quadrant heading '{$heading}' must appear after the previously asserted quadrant, preserving document order."
            );
            $previousPosition = $position;
        }
    }

    public function testUserManualBodyTutorialsSectionListsExpectedLinks(): void
    {
        $content = $this->bodyContent();

        $this->assertStringContainsString('href="docs/tutorials/quickstart-guide.md"', $content);
        $this->assertStringContainsString(
            'href="docs/tutorials/local-almalinux10-wsl2-podman-setup.md"',
            $content
        );
    }

    public function testUserManualBodyHowToSectionListsAllTenLinksInOrder(): void
    {
        $content = $this->bodyContent();

        $expectedOrder = [
            'docs/how-to/install-windows-herd.md',
            'docs/how-to/install-linux-native.md',
            'docs/how-to/run-podman-docker-containers.md',
            'docs/how-to/deploy-cloud-render-github-pages.md',
            'docs/how-to/create-manage-pages.md',
            'docs/how-to/customize-themes-navigation.md',
            'docs/how-to/manage-content-flatfiles.md',
            'docs/how-to/configure-security-csrf-csp.md',
            'docs/how-to/configure-seo-sitemaps.md',
            'docs/how-to/run-tests-static-analysis.md',
        ];

        $this->assertLinksAppearInOrder($content, $expectedOrder);
    }

    public function testUserManualBodyReferenceSectionListsAllSevenLinksInOrder(): void
    {
        $content = $this->bodyContent();

        $expectedOrder = [
            'docs/reference/system-requirements.md',
            'docs/reference/cms-context-api.md',
            'docs/reference/registry-api.md',
            'docs/reference/security-utils-api.md',
            'docs/reference/performance-utils-api.md',
            'docs/reference/configuration-and-composer-scripts.md',
            'docs/reference/release-notes-changelog.md',
        ];

        $this->assertLinksAppearInOrder($content, $expectedOrder);
    }

    public function testUserManualBodyExplanationSectionListsAllFiveLinksInOrder(): void
    {
        $content = $this->bodyContent();

        $expectedOrder = [
            'docs/explanation/introduction-and-philosophy.md',
            'docs/explanation/zero-global-architecture-pair-logic.md',
            'docs/explanation/dual-view-amp-engine.md',
            'docs/explanation/security-hardening-owasp.md',
            'docs/explanation/three-tier-caching-pwa.md',
        ];

        $this->assertLinksAppearInOrder($content, $expectedOrder);
    }

    public function testUserManualBodyContainsNextStepsCallToAction(): void
    {
        $content = $this->bodyContent();

        $this->assertStringContainsString('Ready to get started locally?', $content);
        $this->assertStringContainsString(
            'href="docs/tutorials/local-almalinux10-wsl2-podman-setup.md" class="btn"',
            $content,
            'The call-to-action button must link to the WSL2 + AlmaLinux 10 + Podman guide.'
        );
    }

    /**
     * Regression guard: every relative markdown link referenced from the
     * user manual body must resolve to an actual file on disk, preventing
     * broken links inside the local documentation navigation hub.
     */
    public function testUserManualBodyAllLinkedDocFilesExistOnDisk(): void
    {
        $content = $this->bodyContent();

        $matchCount = preg_match_all('/href="(docs\/[^"]+\.md)"/', $content, $matches);
        $this->assertNotFalse($matchCount);
        $this->assertGreaterThan(0, $matchCount, 'Expected at least one docs/*.md link in the user manual body.');

        $rootDir = dirname(__DIR__);
        $missing = [];
        foreach (array_unique($matches[1]) as $relativePath) {
            if (!is_file($rootDir . '/' . $relativePath)) {
                $missing[] = $relativePath;
            }
        }

        $this->assertSame([], $missing, 'Broken documentation links found in user-manual-body.inc: ' . implode(', ', $missing));
    }

    public function testUserManualBodyIsSyntacticallyValidPhp(): void
    {
        $this->assertPhpFileLintsCleanly(dirname(__DIR__) . '/contents/user-manual-body.inc');
    }

    // ---------------------------------------------------------------
    // docs/user-manual/README.md
    // ---------------------------------------------------------------

    public function testUserManualReadmeReferencesAllFourDiataxisQuadrants(): void
    {
        $readme = dirname(__DIR__) . '/docs/user-manual/README.md';
        $content = (string) file_get_contents($readme);

        $this->assertStringContainsString('## 🎓 1. Tutorials (Learning-Oriented)', $content);
        $this->assertStringContainsString('## 🛠️ 2. How-To Guides (Problem-Oriented)', $content);
        $this->assertStringContainsString('## 📋 3. Reference (Information-Oriented)', $content);
        $this->assertStringContainsString('## 🧠 4. Explanation (Concept-Oriented)', $content);
        $this->assertStringContainsString('Diátaxis Documentation Framework', $content);
    }

    // ---------------------------------------------------------------
    // Isolated HTTP-level rendering (CLI)
    // ---------------------------------------------------------------

    public function testUserManualHttpRenderingStandard(): void
    {
        $this->skipIfExecUnavailable();

        $output = [];
        $exitCode = 0;
        $cmd = escapeshellarg(PHP_BINARY) . ' -r ' . escapeshellarg(
            '$_GET = []; require_once ' . var_export(dirname(__DIR__) . '/user-manual.php', true) . ';'
        );
        exec($cmd, $output, $exitCode);

        $outputText = implode("\n", $output);
        $this->assertSame(0, $exitCode, 'standard page rendering CLI must exit with status 0: ' . $outputText);
        $this->assertStringContainsString('CmsForNerd Local User Manual', $outputText);
        $this->assertStringContainsString('Tutorials', $outputText);
    }

    public function testUserManualHttpRenderingAmp(): void
    {
        $this->skipIfExecUnavailable();

        $output = [];
        $exitCode = 0;
        $cmd = escapeshellarg(PHP_BINARY) . ' -r ' . escapeshellarg(
            '$_GET = ["view" => "amp"]; require_once ' . var_export(dirname(__DIR__) . '/user-manual.php', true) . ';'
        );
        exec($cmd, $output, $exitCode);

        $outputText = implode("\n", $output);
        $this->assertSame(0, $exitCode, 'AMP page rendering CLI must exit with status 0: ' . $outputText);
        $this->assertStringContainsString('⚡', $outputText, 'AMP rendering must output valid AMP indicators.');
        $this->assertStringContainsString('CmsForNerd Local User Manual', $outputText);
    }

    // ---------------------------------------------------------------
    // Helpers
    // ---------------------------------------------------------------

    private function controllerContent(): string
    {
        return (string) file_get_contents(dirname(__DIR__) . '/user-manual.php');
    }

    private function bodyContent(): string
    {
        return (string) file_get_contents(dirname(__DIR__) . '/contents/user-manual-body.inc');
    }

    /**
     * Asserts that each href target in $expectedOrder appears within $content,
     * strictly increasing in position, preserving the intended document order.
     *
     * @param array<int, string> $expectedOrder
     */
    private function assertLinksAppearInOrder(string $content, array $expectedOrder): void
    {
        $previousPosition = -1;
        foreach ($expectedOrder as $href) {
            $needle = 'href="' . $href . '"';
            $position = strpos($content, $needle);
            $this->assertNotFalse($position, "Missing expected link: {$needle}");
            $this->assertGreaterThan(
                $previousPosition,
                $position,
                "Link '{$href}' must appear after the previously asserted link, preserving document order."
            );
            $previousPosition = $position;
        }
    }

    private function skipIfExecUnavailable(): void
    {
        if (!function_exists('exec')) {
            self::markTestSkipped('The exec() function is unavailable in this environment.');
        }

        $disabled = array_map('trim', explode(',', (string) ini_get('disable_functions')));
        if (in_array('exec', $disabled, true)) {
            self::markTestSkipped('exec() has been disabled via php.ini disable_functions.');
        }
    }

    private function assertPhpFileLintsCleanly(string $path): void
    {
        $this->skipIfExecUnavailable();

        $output = [];
        $exitCode = 0;
        exec(escapeshellarg(PHP_BINARY) . ' -l ' . escapeshellarg($path) . ' 2>&1', $output, $exitCode);

        $this->assertSame(
            0,
            $exitCode,
            "'{$path}' failed 'php -l' syntax validation: " . implode("\n", $output)
        );
    }
}