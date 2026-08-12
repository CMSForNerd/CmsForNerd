<?php
declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

final class LegalNoticeTest extends TestCase
{
    /**
     * Test existence of Legal Notice files
     */
    public function testLegalNoticeFilesExist(): void
    {
        $controller = dirname(__DIR__) . '/legal-notice.php';
        $body = dirname(__DIR__) . '/contents/legal-notice-body.inc';

        $this->assertFileExists($controller, 'Page controller legal-notice.php must exist.');
        $this->assertFileExists($body, 'Content body contents/legal-notice-body.inc must exist.');
    }

    /**
     * Test that legal-notice.php has declare(strict_types=1);
     */
    public function testStrictTypesInLegalNoticeController(): void
    {
        $controller = dirname(__DIR__) . '/legal-notice.php';
        $content = (string) file_get_contents($controller);

        $this->assertStringContainsString(
            'declare(strict_types=1);',
            $content,
            'legal-notice.php must declare strict types.'
        );
    }

    /**
     * Test contents of legal-notice-body.inc to ensure all key terms/phrases are included
     */
    public function testLegalNoticeBodyContainsRequiredTexts(): void
    {
        $body = dirname(__DIR__) . '/contents/legal-notice-body.inc';
        $content = (string) file_get_contents($body);

        // Required text checks from the user query
        $this->assertStringContainsString('All costs, designs, unit amounts, and scenarios', $content);
        $this->assertStringContainsString('based entirely on assumptions', $content);
        $this->assertStringContainsString('strictly for training, educational, and planning proposal purposes', $content);
        $this->assertStringContainsString('Use at your own risk', $content);
        $this->assertStringContainsString('The project contributors, authors, and organisations shall not be held liable or responsible', $content);
        $this->assertStringContainsString('We have done our best to protect anyone and organisation', $content);
        $this->assertStringContainsString('We are not going to be responsible', $content);
    }

    /**
     * Test that footers reference the Legal Notice page
     */
    public function testFootersContainLegalNoticeLinks(): void
    {
        $footerFile = dirname(__DIR__) . '/contents/footer.inc';
        $footerContent = (string) file_get_contents($footerFile);

        $this->assertStringContainsString('legal-notice.php', $footerContent);
        $this->assertStringContainsString('[ REGULATION: DISCLAIMER ] | [ PURPOSE: TRAINING ] | [ RISK: ASSUMED ]', $footerContent);

        $pagerFile = dirname(__DIR__) . '/themes/CmsForNerd/pager.php';
        $pagerContent = (string) file_get_contents($pagerFile);

        $this->assertStringContainsString('legal-notice.php?view=amp', $pagerContent);
    }

    // ---------------------------------------------------------------
    // legal-notice.php controller
    // ---------------------------------------------------------------

    public function testLegalNoticeControllerDeclaresExpectedPageMetadata(): void
    {
        $controller = dirname(__DIR__) . '/legal-notice.php';
        $content = (string) file_get_contents($controller);

        $this->assertStringContainsString(
            'Legal Notice & Disclaimer | CmsForNerd Laboratory',
            $content,
            'The <title> metadata must announce the Legal Notice page.'
        );
        $this->assertStringContainsString('Harisfazillah Jamel', $content, 'The author metadata must be present.');
        $this->assertStringContainsString(
            'Legal Notice, Privacy Policy, Critical Assumptions, and Disclaimer of Liability for the CmsForNerd Laboratory.',
            $content,
            'The meta description must summarize the legal notice content.'
        );
        $this->assertStringContainsString(
            'Legal Notice, Privacy Policy, Disclaimer, Assumptions, PHP 8.4, Education, LinuxMalaysia',
            $content,
            'The SEO keywords metadata must be present.'
        );
        $this->assertStringContainsString("'schemaType'  => \"WebPage\"", $content, 'The schema.org type must be WebPage.');
    }

    public function testLegalNoticeControllerBootstrapsAndDispatchesThroughPager(): void
    {
        $controller = dirname(__DIR__) . '/legal-notice.php';
        $content = (string) file_get_contents($controller);

        $this->assertStringContainsString(
            "require_once __DIR__ . '/includes/bootstrap.php';",
            $content,
            'legal-notice.php must load the shared bootstrap engine.'
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
        $this->assertStringContainsString('createCmsContext(', $content, 'The controller must build a CmsContext via the shared factory.');
        $this->assertStringContainsString(
            'themes/{$ctx->themeName}/pager.php',
            $content,
            'The controller must dispatch rendering to the theme pager.'
        );
        $this->assertStringContainsString('pager($ctx);', $content, 'The controller must invoke the pager() dispatcher.');
    }

    public function testLegalNoticeControllerEnablesGzipOutputBuffering(): void
    {
        $controller = dirname(__DIR__) . '/legal-notice.php';
        $content = (string) file_get_contents($controller);

        $this->assertStringContainsString('ob_start("ob_gzhandler")', $content);
        $this->assertStringContainsString('ob_end_flush();', $content);
    }

    public function testLegalNoticeControllerHandlesMissingPagerGracefully(): void
    {
        $controller = dirname(__DIR__) . '/legal-notice.php';
        $content = (string) file_get_contents($controller);

        $this->assertStringContainsString("header('HTTP/1.1 500 Internal Server Error');", $content);
        $this->assertStringContainsString('Fatal Error: Theme engine (pager.php) missing', $content);
    }

    public function testLegalNoticeControllerIsSyntacticallyValidPhp(): void
    {
        $this->assertPhpFileLintsCleanly(dirname(__DIR__) . '/legal-notice.php');
    }

    // ---------------------------------------------------------------
    // contents/legal-notice-body.inc
    // ---------------------------------------------------------------

    public function testLegalNoticeBodyContainsAllFourSectionCardsInOrder(): void
    {
        $body = dirname(__DIR__) . '/contents/legal-notice-body.inc';
        $content = (string) file_get_contents($body);

        $sections = [
            'id="purpose"' => '1. Educational and Training Purpose',
            'id="assumptions"' => '2. Reliance on Critical Assumptions',
            'id="privacy"' => '3. Privacy Statement & Data Protection',
            'id="liability"' => '4. Assumption of Risk & Liability Disclaimer',
        ];

        $previousPosition = -1;
        foreach ($sections as $idAttribute => $heading) {
            $this->assertStringContainsString($idAttribute, $content, "Missing section anchor: {$idAttribute}");
            $this->assertStringContainsString($heading, $content, "Missing section heading: {$heading}");

            $position = strpos($content, $idAttribute);
            $this->assertNotFalse($position);
            $this->assertGreaterThan(
                $previousPosition,
                $position,
                "Section '{$idAttribute}' must appear after the previously asserted section, preserving document order."
            );
            $previousPosition = $position;
        }
    }

    public function testLegalNoticeBodyContainsClosingQuoteAndScopedStyleBlock(): void
    {
        $body = dirname(__DIR__) . '/contents/legal-notice-body.inc';
        $content = (string) file_get_contents($body);

        $this->assertStringContainsString(
            '"We have done our best to protect anyone and organisation. Use at your own risk,"',
            $content,
            'The closing blockquote reassurance must be present verbatim.'
        );

        // Verify that styling has been successfully relocated to stylesheets to preserve AMP validation
        $styleCss = (string) file_get_contents(dirname(__DIR__) . '/themes/CmsForNerd/style.css');
        $ampCss = (string) file_get_contents(dirname(__DIR__) . '/themes/CmsForNerd/css/amp.css');

        $this->assertStringContainsString('.legal-notice-page', $styleCss);
        $this->assertStringContainsString('.legal-notice-page', $ampCss);
    }

    public function testLegalNoticeBodyIsSyntacticallyValidPhp(): void
    {
        $this->assertPhpFileLintsCleanly(dirname(__DIR__) . '/contents/legal-notice-body.inc');
    }

    // ---------------------------------------------------------------
    // contents/footer.inc
    // ---------------------------------------------------------------

    public function testFooterIncPlacesLegalNoticeLinkAfterInfrastructureLinkAndBeforeCopyright(): void
    {
        $footerFile = dirname(__DIR__) . '/contents/footer.inc';
        $content = (string) file_get_contents($footerFile);

        $infraPosition = strpos($content, 'linuxmalaysia.com</a>');
        $legalPosition = strpos($content, 'href="legal-notice.php"');
        $copyrightPosition = strpos($content, 'Copyright');

        $this->assertNotFalse($infraPosition, 'Infrastructure link must be present.');
        $this->assertNotFalse($legalPosition, 'Legal notice link must be present.');
        $this->assertNotFalse($copyrightPosition, 'Copyright notice must be present.');

        $this->assertGreaterThan($infraPosition, $legalPosition, 'Legal notice link must follow the infrastructure link.');
        $this->assertLessThan($copyrightPosition, $legalPosition, 'Legal notice link must precede the copyright notice.');
    }

    public function testFooterIncSeparatesLegalNoticeLinkWithBulletGlyph(): void
    {
        $footerFile = dirname(__DIR__) . '/contents/footer.inc';
        $content = (string) file_get_contents($footerFile);

        $this->assertStringContainsString('• <a href="legal-notice.php"', $content, 'The legal notice link must be introduced by a bullet separator.');
        $this->assertStringContainsString('>Legal Notice & Disclaimer</a>', $content);
    }

    public function testFooterIncAdvertisesRegulationDisclaimerBannerAfterReleaseBanner(): void
    {
        $footerFile = dirname(__DIR__) . '/contents/footer.inc';
        $content = (string) file_get_contents($footerFile);

        $releasePosition = strpos($content, '[ REL: 3.5.1 ]');
        $regulationPosition = strpos($content, '[ REGULATION: DISCLAIMER ] | [ PURPOSE: TRAINING ] | [ RISK: ASSUMED ]');

        $this->assertNotFalse($releasePosition, 'Release banner must be present.');
        $this->assertNotFalse($regulationPosition, 'Regulation disclaimer banner must be present.');
        $this->assertGreaterThan($releasePosition, $regulationPosition, 'Regulation banner must follow the release banner.');
    }

    public function testFooterIncIsSyntacticallyValidPhp(): void
    {
        $this->assertPhpFileLintsCleanly(dirname(__DIR__) . '/contents/footer.inc');
    }

    // ---------------------------------------------------------------
    // themes/CmsForNerd/pager.php
    // ---------------------------------------------------------------

    public function testPagerAmpFooterListsLegalNoticeLinkBeforeStandardViewSwitchLink(): void
    {
        $pagerFile = dirname(__DIR__) . '/themes/CmsForNerd/pager.php';
        $content = (string) file_get_contents($pagerFile);

        $legalPosition = strpos($content, 'href="legal-notice.php?view=amp"');
        $switchPosition = strpos($content, 'Switch to Standard Desktop View');

        $this->assertNotFalse($legalPosition, 'AMP footer must link to the AMP legal notice page.');
        $this->assertNotFalse($switchPosition, 'AMP footer must retain the standard view switch link.');
        $this->assertLessThan($switchPosition, $legalPosition, 'The legal notice link must appear before the standard view switch link.');

        $this->assertStringContainsString('Legal Notice & Disclaimer', $content);
        $this->assertStringContainsString('margin-right:15px;', $content, 'The legal notice link must retain its spacing style.');
    }

    public function testPagerRenderAmpLayoutDocblockDocumentsCtxParameter(): void
    {
        $pagerFile = dirname(__DIR__) . '/themes/CmsForNerd/pager.php';
        $content = (string) file_get_contents($pagerFile);

        $this->assertMatchesRegularExpression(
            '/\/\*\*.*?@param CmsForNerd\\\\CmsContext \$ctx.*?\*\/\s*function renderAmpLayout\(CmsForNerd\\\\CmsContext \$ctx\): void/s',
            $content,
            'renderAmpLayout() must be documented with a PHPDoc @param tag for $ctx.'
        );
    }

    public function testPagerPhpIsSyntacticallyValidPhp(): void
    {
        $this->assertPhpFileLintsCleanly(dirname(__DIR__) . '/themes/CmsForNerd/pager.php');
    }

    /**
     * Isolated HTTP-level request for the standard view.
     */
    public function testLegalNoticeHttpRenderingStandard(): void
    {
        $this->skipIfExecUnavailable();

        $output = [];
        $exitCode = 0;
        $cmd = escapeshellarg(PHP_BINARY) . ' -r ' . escapeshellarg('$_GET = []; require_once ' . var_export(dirname(__DIR__) . '/legal-notice.php', true) . ';');
        exec($cmd, $output, $exitCode);

        $outputText = implode("\n", $output);
        $this->assertSame(0, $exitCode, 'standard page rendering CLI must exit with status 0');
        $this->assertStringContainsString('Legal Notice & Disclaimer', $outputText);
        $this->assertStringContainsString('Privacy Policy, Critical Assumptions & Disclaimer of Liability', $outputText);
    }

    /**
     * Isolated HTTP-level request for the AMP view.
     */
    public function testLegalNoticeHttpRenderingAmp(): void
    {
        $this->skipIfExecUnavailable();

        $output = [];
        $exitCode = 0;
        $cmd = escapeshellarg(PHP_BINARY) . ' -r ' . escapeshellarg('$_GET = ["view" => "amp"]; require_once ' . var_export(dirname(__DIR__) . '/legal-notice.php', true) . ';');
        exec($cmd, $output, $exitCode);

        $outputText = implode("\n", $output);
        $this->assertSame(0, $exitCode, 'AMP page rendering CLI must exit with status 0');
        $this->assertStringContainsString('⚡', $outputText, 'AMP rendering must output valid AMP indicators.');
        $this->assertStringContainsString('Legal Notice & Disclaimer', $outputText);
    }

    // ---------------------------------------------------------------
    // Helpers
    // ---------------------------------------------------------------

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
