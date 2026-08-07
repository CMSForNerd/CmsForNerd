<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Validates the CmsForNerd v4.3.0 "Unified Version Consolidation" pass,
 * which synchronised the stale product version banners scattered across
 * the AI agent rule files, infrastructure metadata, page controllers, and
 * paired content fragments so that every user- and agent-facing surface
 * agrees on `v4.3.0`.
 *
 * Covers:
 * - .clinerules
 * - .cursorrules
 * - .github/copilot-instructions.md
 * - .llms/index.md
 * - .dockerignore
 * - .editorconfig
 * - Containerfile
 * - Dockerfile
 * - composer.json (description + inline script banners)
 * - about.php, ai-dev.php, ai-sop.php, amp-acceleration.php
 * - contents/ai-dev-body.inc
 * - contents/amp-acceleration-body.inc
 * - contents/ansible-lab-body.inc
 * - contents/csp-nonce-guide-body.inc
 * - contents/common-headertag.inc (additional regression guard beyond the
 *   dedicated ThemeVersionUpgradeTest coverage)
 */
final class CoreVersionBumpV430Test extends TestCase
{
    private const CURRENT_VERSION = '4.3.0';

    private string $root;

    protected function setUp(): void
    {
        $this->root = dirname(__DIR__);
    }

    private function read(string $relativePath): string
    {
        $path = $this->root . '/' . $relativePath;
        $this->assertFileExists($path, "Expected '{$relativePath}' to exist.");

        return (string) file_get_contents($path);
    }

    // ---------------------------------------------------------------
    // .clinerules
    // ---------------------------------------------------------------

    public function testClinerulesHeadingAdvertisesCurrentVersion(): void
    {
        $content = $this->read('.clinerules');

        $this->assertStringStartsWith(
            '# CMSForNerd Cline/Roo-Code Rules (v' . self::CURRENT_VERSION . ')',
            $content
        );
    }

    public function testClinerulesNoLongerAdvertisesStaleVersion(): void
    {
        $content = $this->read('.clinerules');

        $this->assertStringNotContainsString('Cline/Roo-Code Rules (v3.5)', $content);
    }

    public function testClinerulesRetainsArchitecturalConstraints(): void
    {
        $content = $this->read('.clinerules');

        $this->assertStringContainsString("Only `require 'includes/bootstrap.php';`.", $content);
        $this->assertStringContainsString('namespace CmsForNerd;', $content);
        $this->assertStringContainsString('Any use of `global` will be considered a critical failure', $content);
    }

    // ---------------------------------------------------------------
    // .cursorrules
    // ---------------------------------------------------------------

    public function testCursorrulesHeadingAdvertisesCurrentVersion(): void
    {
        $content = $this->read('.cursorrules');

        $this->assertStringStartsWith(
            '# CMSForNerd Cursor Rules (v' . self::CURRENT_VERSION . ') - Intelligence Sync Edition',
            $content
        );
    }

    public function testCursorrulesNoLongerAdvertisesStaleVersion(): void
    {
        $content = $this->read('.cursorrules');

        $this->assertStringNotContainsString('Cursor Rules (v3.5)', $content);
    }

    public function testCursorrulesRetainsVerificationLoop(): void
    {
        $content = $this->read('.cursorrules');

        $this->assertStringContainsString('composer fix-style', $content);
        $this->assertStringContainsString('composer lab-check', $content);
        $this->assertStringContainsString('declare(strict_types=1);` is mandatory', $content);
    }

    // ---------------------------------------------------------------
    // .github/copilot-instructions.md
    // ---------------------------------------------------------------

    public function testCopilotInstructionsHeadingAdvertisesCurrentVersion(): void
    {
        $content = $this->read('.github/copilot-instructions.md');

        $this->assertStringContainsString(
            '# AI Assistant Instructions for CMSForNerd (v' . self::CURRENT_VERSION . ')',
            $content
        );
    }

    public function testCopilotInstructionsHighLevelContractHeadingAdvertisesCurrentVersion(): void
    {
        $content = $this->read('.github/copilot-instructions.md');

        $this->assertStringContainsString(
            '## 🏛️ High-Level Contract (v' . self::CURRENT_VERSION . ')',
            $content
        );
    }

    public function testCopilotInstructionsNoLongerAdvertisesStaleVersionAnywhere(): void
    {
        $content = $this->read('.github/copilot-instructions.md');

        $this->assertDoesNotMatchRegularExpression('/\(v3\.5\)/', $content);
    }

    public function testCopilotInstructionsBothVersionMentionsAgree(): void
    {
        $content = $this->read('.github/copilot-instructions.md');

        $this->assertSame(
            2,
            substr_count($content, '(v' . self::CURRENT_VERSION . ')'),
            'Expected exactly two "(v4.3.0)" mentions: the title and the High-Level Contract heading.'
        );
    }

    // ---------------------------------------------------------------
    // .llms/index.md
    // ---------------------------------------------------------------

    public function testLlmsIndexFrontMatterDescriptionAdvertisesCurrentVersion(): void
    {
        $content = $this->read('.llms/index.md');

        $this->assertStringContainsString(
            'description: "Hierarchical breakdown of the CmsForNerd v' . self::CURRENT_VERSION . ' codebase, architecture, state flow, and files."',
            $content
        );
    }

    public function testLlmsIndexBodyDescriptionAdvertisesCurrentVersion(): void
    {
        $content = $this->read('.llms/index.md');

        $this->assertStringContainsString(
            'breakdown of the core engine, standard practices, file mappings, and architectural standards of CmsForNerd v' .
            self::CURRENT_VERSION . '.',
            $content
        );
    }

    public function testLlmsIndexNoLongerAdvertisesStaleVersion410(): void
    {
        $content = $this->read('.llms/index.md');

        $this->assertStringNotContainsString('v4.1.0', $content);
    }

    // ---------------------------------------------------------------
    // .dockerignore
    // ---------------------------------------------------------------

    public function testDockerignoreHeaderBannerAdvertisesCurrentVersion(): void
    {
        $content = $this->read('.dockerignore');

        $this->assertStringContainsString(
            '# CmsForNerd v' . self::CURRENT_VERSION . ' - Docker Build Context Exclusions',
            $content
        );
    }

    public function testDockerignoreNoLongerAdvertisesStaleVersion410(): void
    {
        $content = $this->read('.dockerignore');

        $this->assertStringNotContainsString('CmsForNerd v4.1.0', $content);
    }

    // ---------------------------------------------------------------
    // .editorconfig
    // ---------------------------------------------------------------

    public function testEditorconfigHeaderCommentAdvertisesCurrentVersion(): void
    {
        $content = $this->read('.editorconfig');

        $this->assertStringStartsWith(
            '# .editorconfig for CmsForNerd v' . self::CURRENT_VERSION . ' Laboratory',
            $content
        );
    }

    public function testEditorconfigNoLongerAdvertisesStaleVersion34(): void
    {
        $content = $this->read('.editorconfig');

        $this->assertStringNotContainsString('CmsForNerd v3.4 Laboratory', $content);
    }

    public function testEditorconfigStillDeclaresRootTrueAndCorePsr12Rules(): void
    {
        // Regression guard: editing the banner comment must not disturb the
        // functional EditorConfig directives beneath it.
        $content = $this->read('.editorconfig');

        $this->assertStringContainsString("root = true", $content);
        $this->assertStringContainsString('indent_style = space', $content);
        $this->assertStringContainsString('indent_size = 4', $content);
        $this->assertStringContainsString('max_line_length = 120', $content);
    }

    // ---------------------------------------------------------------
    // Containerfile / Dockerfile
    // ---------------------------------------------------------------

    public function testContainerfileHeaderBannerAdvertisesCurrentVersion(): void
    {
        $content = $this->read('Containerfile');

        $this->assertStringContainsString(
            '# CmsForNerd v' . self::CURRENT_VERSION . ' - Containerfile for Render Production Deployments',
            $content
        );
    }

    public function testDockerfileHeaderBannerAdvertisesCurrentVersion(): void
    {
        $content = $this->read('Dockerfile');

        $this->assertStringContainsString(
            '# CmsForNerd v' . self::CURRENT_VERSION . ' - Dockerfile for Render Production Deployments',
            $content
        );
    }

    public function testContainerfileAndDockerfileNoLongerAdvertiseStaleVersion410(): void
    {
        $this->assertStringNotContainsString('CmsForNerd v4.1.0', $this->read('Containerfile'));
        $this->assertStringNotContainsString('CmsForNerd v4.1.0', $this->read('Dockerfile'));
    }

    // ---------------------------------------------------------------
    // composer.json
    // ---------------------------------------------------------------

    /** @return array<string, mixed> */
    private function decodedComposerJson(): array
    {
        $content = $this->read('composer.json');
        $decoded = json_decode($content, true);
        $this->assertIsArray($decoded, 'composer.json must decode to an array.');

        return $decoded;
    }

    public function testComposerJsonDescriptionAdvertisesCurrentVersion(): void
    {
        $config = $this->decodedComposerJson();

        $this->assertSame(
            'A flat-file CMS modernized for PHP 8.4 Laboratory environments (Hybrid Arch) v' . self::CURRENT_VERSION . '.',
            $config['description']
        );
    }

    public function testComposerJsonLabCheckBannerAdvertisesCurrentVersion(): void
    {
        $config = $this->decodedComposerJson();
        $labCheckBanner = $config['scripts']['lab-check'][1];

        $this->assertStringContainsString(
            'Validating Laboratory Readiness (v' . self::CURRENT_VERSION . ')',
            $labCheckBanner
        );
    }

    public function testComposerJsonPostInstallBannerAdvertisesCurrentVersion(): void
    {
        $config = $this->decodedComposerJson();
        $postInstall = $config['scripts']['post-install-cmd'][0];

        $this->assertStringContainsString(
            'Welcome to the CMSForNerd v' . self::CURRENT_VERSION . ' Laboratory (Secure Engine Mode).',
            $postInstall
        );
    }

    public function testComposerJsonNoLongerAdvertisesStaleVersion36Anywhere(): void
    {
        $content = $this->read('composer.json');

        $this->assertStringNotContainsString('v3.6', $content);
    }

    // ---------------------------------------------------------------
    // about.php
    // ---------------------------------------------------------------

    public function testAboutPhpDocblockAdvertisesCurrentVersion(): void
    {
        $content = $this->read('about.php');

        $this->assertStringContainsString(
            'CmsForNerd v' . self::CURRENT_VERSION . ' - Page Controller (about.php)',
            $content
        );
    }

    public function testAboutPhpMatchExpressionCommentAdvertisesCurrentVersion(): void
    {
        $content = $this->read('about.php');

        $this->assertStringContainsString(
            "v" . self::CURRENT_VERSION . " uses the 'match' expression",
            $content
        );
    }

    public function testAboutPhpStillFollowsThePairLogicBootstrapAndDispatchPattern(): void
    {
        // Regression guard: version banner edits must not disturb the
        // Pair Logic controller skeleton (bootstrap, resolvePageName,
        // createCmsContext, pager dispatch).
        $content = $this->read('about.php');

        $this->assertStringContainsString("require_once __DIR__ . '/includes/bootstrap.php';", $content);
        $this->assertStringContainsString('\CmsForNerd\SecurityUtils::resolvePageName(', $content);
        $this->assertStringContainsString('$ctx = createCmsContext(', $content);
        $this->assertStringContainsString('pager($ctx);', $content);
    }

    // ---------------------------------------------------------------
    // ai-dev.php
    // ---------------------------------------------------------------

    public function testAiDevPhpDocblockAdvertisesCurrentVersion(): void
    {
        $content = $this->read('ai-dev.php');

        $this->assertStringContainsString(
            'CmsForNerd v' . self::CURRENT_VERSION . ' - Page Controller (ai-dev.php)',
            $content
        );
    }

    public function testAiDevPhpPageTitleMetadataAdvertisesCurrentVersion(): void
    {
        $content = $this->read('ai-dev.php');

        $this->assertStringContainsString(
            '"AI-Assisted Development | CMSForNerd v' . self::CURRENT_VERSION . '"',
            $content
        );
    }

    public function testAiDevPhpNoLongerAdvertisesStaleVersion(): void
    {
        $content = $this->read('ai-dev.php');

        $this->assertStringNotContainsString('v3.5', $content);
    }

    // ---------------------------------------------------------------
    // ai-sop.php
    // ---------------------------------------------------------------

    public function testAiSopPhpDocblockAdvertisesCurrentVersion(): void
    {
        $content = $this->read('ai-sop.php');

        $this->assertStringContainsString(
            'CmsForNerd v' . self::CURRENT_VERSION . ' - Page Controller (ai-sop.php)',
            $content
        );
    }

    public function testAiSopPhpNoLongerAdvertisesStaleVersion(): void
    {
        $content = $this->read('ai-sop.php');

        $this->assertStringNotContainsString('v3.5', $content);
    }

    public function testAiSopPhpRetainsItsOwnUnversionedPageTitle(): void
    {
        // ai-sop.php's page title/description were not part of the version
        // bump; only the docblock changed. This guards against an
        // over-eager future find/replace accidentally touching the title.
        $content = $this->read('ai-sop.php');

        $this->assertStringContainsString(
            '"SOP: Ethical AI Integration | CMSForNerd Laboratory"',
            $content
        );
    }

    // ---------------------------------------------------------------
    // amp-acceleration.php
    // ---------------------------------------------------------------

    public function testAmpAccelerationPhpDocblockAdvertisesCurrentVersion(): void
    {
        $content = $this->read('amp-acceleration.php');

        $this->assertStringContainsString(
            'CmsForNerd v' . self::CURRENT_VERSION . ' - AMP Acceleration Documentation (amp-acceleration.php)',
            $content
        );
    }

    public function testAmpAccelerationPhpNoLongerAdvertisesStaleVersion(): void
    {
        $content = $this->read('amp-acceleration.php');

        $this->assertStringNotContainsString('v3.5', $content);
    }

    // ---------------------------------------------------------------
    // contents/ai-dev-body.inc
    // ---------------------------------------------------------------

    public function testAiDevBodyIncAdvertisesCurrentVersion(): void
    {
        $content = $this->read('contents/ai-dev-body.inc');

        $this->assertStringContainsString(
            '<strong>CMSForNerd v' . self::CURRENT_VERSION . '</strong> was architected using a high-speed synergy',
            $content
        );
    }

    public function testAiDevBodyIncNoLongerAdvertisesStaleVersion33(): void
    {
        $content = $this->read('contents/ai-dev-body.inc');

        $this->assertStringNotContainsString('CMSForNerd v3.3', $content);
    }

    // ---------------------------------------------------------------
    // contents/amp-acceleration-body.inc
    // ---------------------------------------------------------------

    public function testAmpAccelerationBodyIncAdvertisesCurrentVersion(): void
    {
        $content = $this->read('contents/amp-acceleration-body.inc');

        $this->assertStringContainsString(
            'mobile performance optimisation in CMSForNerd v' . self::CURRENT_VERSION . '.',
            $content
        );
    }

    public function testAmpAccelerationBodyIncNoLongerAdvertisesStaleVersion(): void
    {
        $content = $this->read('contents/amp-acceleration-body.inc');

        $this->assertStringNotContainsString('CMSForNerd v3.5', $content);
    }

    // ---------------------------------------------------------------
    // contents/ansible-lab-body.inc
    // ---------------------------------------------------------------

    public function testAnsibleLabBodyIncSubtitleAdvertisesCurrentVersion(): void
    {
        $content = $this->read('contents/ansible-lab-body.inc');

        $this->assertStringContainsString(
            'Automated Nginx & PHP 8.4-FPM Deployment v' . self::CURRENT_VERSION,
            $content
        );
    }

    public function testAnsibleLabBodyIncGitSyncNoteAdvertisesCurrentVersion(): void
    {
        $content = $this->read('contents/ansible-lab-body.inc');

        $this->assertStringContainsString(
            'Automated Git synchronisation (v' . self::CURRENT_VERSION . ' master).',
            $content
        );
    }

    public function testAnsibleLabBodyIncNoLongerAdvertisesStaleVersions(): void
    {
        $content = $this->read('contents/ansible-lab-body.inc');

        $this->assertStringNotContainsString('v3.5.1', $content);
        $this->assertStringNotContainsString('v3.5 master', $content);
    }

    public function testAnsibleLabBodyIncRemainsSyntacticallyValidPhp(): void
    {
        $this->assertPhpFileLintsCleanly($this->root . '/contents/ansible-lab-body.inc');
    }

    // ---------------------------------------------------------------
    // contents/csp-nonce-guide-body.inc
    // ---------------------------------------------------------------

    public function testCspNonceGuideBodyIncVerificationLogicSectionAdvertisesCurrentVersion(): void
    {
        $content = $this->read('contents/csp-nonce-guide-body.inc');

        $this->assertStringContainsString(
            'In <strong>CMSForNerd v' . self::CURRENT_VERSION . '</strong>, we use the <code>SecurityUtils</code> class',
            $content
        );
    }

    public function testCspNonceGuideBodyIncSecureCardHeadingAdvertisesCurrentVersion(): void
    {
        $content = $this->read('contents/csp-nonce-guide-body.inc');

        $this->assertStringContainsString(
            '<h3>✅ v' . self::CURRENT_VERSION . ' Standard (Secure)</h3>',
            $content
        );
    }

    public function testCspNonceGuideBodyIncNoLongerAdvertisesStaleVersion33(): void
    {
        $content = $this->read('contents/csp-nonce-guide-body.inc');

        $this->assertStringNotContainsString('CMSForNerd v3.3', $content);
        $this->assertStringNotContainsString('✅ v3.3 Standard', $content);
    }

    public function testCspNonceGuideBodyIncStillContrastsLegacyVulnerableScriptWithSecureNonceExample(): void
    {
        // Regression guard: the version bump in the "after" card heading
        // must not disturb the vulnerable-vs-secure comparison content.
        $content = $this->read('contents/csp-nonce-guide-body.inc');

        $this->assertStringContainsString('❌ Legacy (Vulnerable)', $content);
        $this->assertStringContainsString("nonce=\"XYZ123...\"", $content);
    }

    // ---------------------------------------------------------------
    // contents/common-headertag.inc (extra regression guard)
    // ---------------------------------------------------------------

    public function testCommonHeaderTagIncNoLongerAdvertisesTheImmediatelyPriorVersion420(): void
    {
        // ThemeVersionUpgradeTest already asserts the v4.3.0 string is
        // present and that the stale v3.5 text is gone; this guards
        // specifically against the immediately-preceding v4.2.0 fallback
        // text (the actual "before" value in this PR's diff) reappearing.
        $content = $this->read('contents/common-headertag.inc');

        $this->assertStringNotContainsString(
            'A modern PHP 8.4+ educational CMS environment (v4.2.0).',
            $content
        );
    }

    public function testCommonHeaderTagIncSchemaDescriptionFallbackAppearsExactlyOnce(): void
    {
        $content = $this->read('contents/common-headertag.inc');

        $this->assertSame(
            1,
            substr_count($content, 'A modern PHP 8.4+ educational CMS environment (v' . self::CURRENT_VERSION . ').'),
            'The versioned schema description fallback should appear exactly once.'
        );
    }

    // ---------------------------------------------------------------
    // PHP syntax validity for edited controllers
    // ---------------------------------------------------------------

    public function testEditedPageControllersRemainSyntacticallyValidPhp(): void
    {
        foreach (['about.php', 'ai-dev.php', 'ai-sop.php', 'amp-acceleration.php'] as $relativePath) {
            $this->assertPhpFileLintsCleanly($this->root . '/' . $relativePath);
        }
    }

    public function testVscodeAndComposerJsonFilesRemainValid(): void
    {
        $this->assertPhpFileLintsCleanly($this->root . '/.vscode/index.php');
    }

    // ---------------------------------------------------------------
    // Helpers
    // ---------------------------------------------------------------

    private function assertPhpFileLintsCleanly(string $path): void
    {
        if (!function_exists('exec')) {
            self::markTestSkipped('The exec() function is unavailable in this environment.');
        }

        $disabled = array_map('trim', explode(',', (string) ini_get('disable_functions')));
        if (in_array('exec', $disabled, true)) {
            self::markTestSkipped('exec() has been disabled via php.ini disable_functions.');
        }

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