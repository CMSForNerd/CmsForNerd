<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Validates the new "ASIMP for AI Agents" guide introduced as the platform's
 * 16th documented Entry Point:
 *
 * - asimp-ai-agents.php: the Pair Logic page controller.
 * - contents/asimp-ai-agents-body.inc: the paired UI content fragment.
 * - docs/governance/ASIMP-FOR-AI-AGENTS.md: the governance protocol document.
 * - START-HERE.md, SUMMARY.md, mkdocs.yml, llms.txt: the four navigation
 *   layers that must register the new guide.
 */
final class AsimpAiAgentsGuideTest extends TestCase
{
    private string $root;
    private string $controllerPath;
    private string $bodyPath;
    private string $governanceDocPath;

    protected function setUp(): void
    {
        $this->root = dirname(__DIR__);
        $this->controllerPath = $this->root . '/asimp-ai-agents.php';
        $this->bodyPath = $this->root . '/contents/asimp-ai-agents-body.inc';
        $this->governanceDocPath = $this->root . '/docs/governance/ASIMP-FOR-AI-AGENTS.md';
    }

    private function read(string $path): string
    {
        $this->assertFileExists($path, "Expected '{$path}' to exist.");

        return (string) file_get_contents($path);
    }

    // ---------------------------------------------------------------
    // asimp-ai-agents.php controller
    // ---------------------------------------------------------------

    public function testControllerFileExists(): void
    {
        $this->assertFileExists($this->controllerPath, 'Page controller asimp-ai-agents.php must exist.');
    }

    public function testControllerDeclaresStrictTypesAndNamespace(): void
    {
        $content = $this->read($this->controllerPath);

        $this->assertStringContainsString('declare(strict_types=1);', $content);
        $this->assertStringContainsString('namespace CmsForNerd;', $content);
    }

    public function testControllerDeclaresExpectedPageMetadata(): void
    {
        $content = $this->read($this->controllerPath);

        $this->assertStringContainsString(
            '"ASIMP for AI Agents | CmsForNerd Laboratory"',
            $content,
            'The <title> metadata must announce the ASIMP for AI Agents page.'
        );
        $this->assertStringContainsString('"Harisfazillah Jamel"', $content, 'The author metadata must be present.');
        $this->assertStringContainsString(
            'Details how the Ansible System Integrity Management Platform (ASIMP) operates in alignment with AI agents, DSOM spatial memory protocols, and the OpenWiki emulator.',
            $content,
            'The meta description must summarise the ASIMP for AI Agents content.'
        );
        $this->assertStringContainsString(
            'ASIMP, AI Agents, DSOM, My AI Protocol, Hardening, OpenSCAP, Lynis, PHP 8.4, LinuxMalaysia',
            $content,
            'The SEO keywords metadata must be present.'
        );
        $this->assertStringContainsString("'schemaType'  => \"WebPage\"", $content, 'The schema.org type must be WebPage.');
    }

    public function testControllerBootstrapsAndDispatchesThroughPager(): void
    {
        $content = $this->read($this->controllerPath);

        $this->assertStringContainsString(
            "require_once __DIR__ . '/includes/bootstrap.php';",
            $content,
            'asimp-ai-agents.php must load the shared bootstrap engine.'
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

    public function testControllerEnablesGzipOutputBuffering(): void
    {
        $content = $this->read($this->controllerPath);

        $this->assertStringContainsString('ob_start("ob_gzhandler")', $content);
        $this->assertStringContainsString('ob_end_flush();', $content);
    }

    public function testControllerHandlesMissingPagerGracefully(): void
    {
        $content = $this->read($this->controllerPath);

        $this->assertStringContainsString("header('HTTP/1.1 500 Internal Server Error');", $content);
        $this->assertStringContainsString('Fatal Error: Theme engine (pager.php) missing', $content);
    }

    public function testControllerIsSyntacticallyValidPhp(): void
    {
        $this->assertPhpFileLintsCleanly($this->controllerPath);
    }

    // ---------------------------------------------------------------
    // contents/asimp-ai-agents-body.inc
    // ---------------------------------------------------------------

    public function testBodyIncFileExists(): void
    {
        $this->assertFileExists($this->bodyPath, 'Content body contents/asimp-ai-agents-body.inc must exist.');
    }

    public function testBodyIncContainsHeaderAndSubtitle(): void
    {
        $content = $this->read($this->bodyPath);

        $this->assertStringContainsString('ASIMP-Driven Agent Security Compliance', $content);
        $this->assertStringContainsString('Orchestrating Autonomous Host Auditing and Hardening Safely', $content);
    }

    public function testBodyIncUsesTechArticleSchema(): void
    {
        $content = $this->read($this->bodyPath);

        $this->assertStringContainsString('itemtype="https://schema.org/TechArticle"', $content);
        $this->assertStringContainsString('itemprop="headline"', $content);
        $this->assertStringContainsString('itemprop="description"', $content);
    }

    public function testBodyIncDescribesBothCognitiveAndAsimpWorkflowCards(): void
    {
        $content = $this->read($this->bodyPath);

        $this->assertStringContainsString('Cognitive Governance (DSOM)', $content);
        $this->assertStringContainsString('Idempotent Execution (ASIMP)', $content);
        $this->assertStringContainsString('<code>.agents/AGENTS.md</code>', $content);
        $this->assertStringContainsString('<code>.agents/brain/task.md</code>', $content);
    }

    public function testBodyIncDescribesSandboxAndProductionModes(): void
    {
        $content = $this->read($this->bodyPath);

        $this->assertStringContainsString('Unprivileged Mocking', $content);
        $this->assertStringContainsString('Google Jules Sandbox Mode', $content);
        $this->assertStringContainsString('bash tools/mock-asimp.sh', $content);
        $this->assertStringContainsString('Target VM Provisioning', $content);
        $this->assertStringContainsString('ansible-playbook -i inventory/hosts.yml playbooks/bootstrap_node.yml --become', $content);
    }

    public function testBodyIncListsAllFourSovereignMemoryChannels(): void
    {
        $content = $this->read($this->bodyPath);

        foreach (['<strong>.agents/AGENTS.md</strong>', '<strong>.agents/brain/task.md</strong>', '<strong>.agents/brain/walkthrough.md</strong>', '<strong>.agents/brain/palace_registry.md</strong>'] as $channel) {
            $this->assertStringContainsString($channel, $content, "Sovereign memory channel section must reference {$channel}.");
        }
    }

    public function testBodyIncFooterNavLinksToSecurityPolicyAndHome(): void
    {
        $content = $this->read($this->bodyPath);

        $this->assertStringContainsString('href="security-policy.php"', $content);
        $this->assertStringContainsString('href="index.php"', $content);
    }

    public function testBodyIncFooterNavLinksExistOnDisk(): void
    {
        // Regression guard: the footer navigation must point at real,
        // existing page controllers rather than dangling references.
        $this->assertFileExists($this->root . '/security-policy.php');
        $this->assertFileExists($this->root . '/index.php');
    }

    public function testBodyIncScopesStylesToItsOwnRootVariablesAndClasses(): void
    {
        $content = $this->read($this->bodyPath);

        $this->assertStringContainsString('<style>', $content);
        $this->assertStringContainsString('</style>', $content);
        $this->assertStringContainsString('.ai-asimp-guide', $content);
        $this->assertStringContainsString('--dsom-purple', $content);
        $this->assertStringContainsString('--asimp-teal', $content);
    }

    public function testBodyIncHtmlSectionsAppearInDocumentOrder(): void
    {
        $content = $this->read($this->bodyPath);

        $sections = [
            'class="guide-header"',
            'class="workflow-grid"',
            'class="dual-mode-section"',
            'class="integration-details"',
            'class="footer-nav"',
        ];

        $previousPosition = -1;
        foreach ($sections as $marker) {
            $position = strpos($content, $marker);
            $this->assertNotFalse($position, "Missing expected section marker: {$marker}");
            $this->assertGreaterThan(
                $previousPosition,
                $position,
                "Section '{$marker}' must appear after the previously asserted section, preserving document order."
            );
            $previousPosition = $position;
        }
    }

    public function testBodyIncIsSyntacticallyValidPhp(): void
    {
        // The fragment contains no PHP tags, but must still lint cleanly as a
        // valid include target (no stray unbalanced braces/tags, etc.).
        $this->assertPhpFileLintsCleanly($this->bodyPath);
    }

    // ---------------------------------------------------------------
    // docs/governance/ASIMP-FOR-AI-AGENTS.md
    // ---------------------------------------------------------------

    public function testGovernanceDocExists(): void
    {
        $this->assertFileExists($this->governanceDocPath, 'docs/governance/ASIMP-FOR-AI-AGENTS.md must exist.');
    }

    public function testGovernanceDocHasCompliantOkfFrontmatter(): void
    {
        $content = $this->read($this->governanceDocPath);

        $this->assertStringStartsWith('---', $content);
        $this->assertStringContainsString('okf_version: 0.1', $content);
        $this->assertStringContainsString('type: governance_protocol', $content);
        $this->assertStringContainsString(
            'title: "ASIMP for AI Agents: Sovereign System Hardening & Metacognitive Auditing"',
            $content
        );
        $this->assertStringContainsString('topics: [asimp, dsom, agents, hardening, governance]', $content);
        $this->assertMatchesRegularExpression('/timestamp: \d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}Z/', $content);
    }

    public function testGovernanceDocDescribesTheFiveSopPhases(): void
    {
        $content = $this->read($this->governanceDocPath);

        foreach (
            [
                '**Orientation Phase:**',
                '**Local Discovery Phase:**',
                '**Execution Phase:**',
                '**Validation Phase:**',
                '**Consolidation Phase:**',
            ] as $phase
        ) {
            $this->assertStringContainsString($phase, $content, "SOP section must describe: {$phase}");
        }
    }

    public function testGovernanceDocDescribesSandboxAndProductionModes(): void
    {
        $content = $this->read($this->governanceDocPath);

        $this->assertStringContainsString('Unprivileged Sandbox Mode', $content);
        $this->assertStringContainsString('bash tools/mock-asimp.sh', $content);
        $this->assertStringContainsString('Production System Hardening', $content);
        $this->assertStringContainsString('ansible-playbook -i inventory/hosts.yml playbooks/bootstrap_node.yml --become', $content);
    }

    public function testGovernanceDocHasSovereignFooter(): void
    {
        $content = $this->read($this->governanceDocPath);

        $this->assertStringContainsString(
            '*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-11*',
            $content
        );
        $this->assertStringContainsString('GNU General Public License v3.0', $content);
    }

    // ---------------------------------------------------------------
    // Navigation registration: START-HERE.md
    // ---------------------------------------------------------------

    public function testStartHereHeadingAdvertisesSixteenEntryPoints(): void
    {
        $content = $this->read($this->root . '/START-HERE.md');

        $this->assertStringContainsString('## 🏛️ The 16 Defined Entry Points', $content);
        $this->assertStringNotContainsString('## 🏛️ The 15 Defined Entry Points', $content);
    }

    public function testStartHereRegistersAsimpAsEntryPointSixteen(): void
    {
        $content = $this->read($this->root . '/START-HERE.md');

        $this->assertStringContainsString(
            '| **16** | **ASIMP for AI Agents** | [`docs/governance/ASIMP-FOR-AI-AGENTS.md`](docs/governance/ASIMP-FOR-AI-AGENTS.md) |',
            $content
        );
    }

    public function testStartHereTableHasExactlySixteenNumberedRows(): void
    {
        $content = $this->read($this->root . '/START-HERE.md');

        $matched = preg_match_all('/^\| \*\*(\d+)\*\* \|/m', $content, $matches);
        $this->assertGreaterThan(0, $matched, 'Expected to find numbered Entry Point table rows.');

        $numbers = array_map('intval', $matches[1]);
        sort($numbers);

        $this->assertSame(range(1, 16), $numbers, 'Entry Point numbering must be a contiguous 1..16 sequence with no gaps or duplicates.');
    }

    // ---------------------------------------------------------------
    // Navigation registration: SUMMARY.md
    // ---------------------------------------------------------------

    public function testSummaryRegistersAsimpGuide(): void
    {
        $content = $this->read($this->root . '/SUMMARY.md');

        $this->assertStringContainsString(
            '* [🛡️ ASIMP for AI Agents Guide](docs/governance/ASIMP-FOR-AI-AGENTS.md)',
            $content
        );
    }

    public function testSummaryAsimpEntryIsPlacedWithinTechnicalResourcesSection(): void
    {
        $content = $this->read($this->root . '/SUMMARY.md');

        $sectionHeadingPos = strpos($content, '## 🛠️ Technical Resources');
        $entryPos = strpos($content, '[🛡️ ASIMP for AI Agents Guide]');
        $nextSectionPos = strpos($content, '## 🏁 Certification');

        $this->assertNotFalse($sectionHeadingPos);
        $this->assertNotFalse($entryPos);
        $this->assertNotFalse($nextSectionPos);
        $this->assertGreaterThan($sectionHeadingPos, $entryPos, 'The ASIMP guide entry must be listed under Technical Resources.');
        $this->assertLessThan($nextSectionPos, $entryPos, 'The ASIMP guide entry must be listed before the Certification section.');
    }

    // ---------------------------------------------------------------
    // Navigation registration: mkdocs.yml
    // ---------------------------------------------------------------

    public function testMkdocsRegistersAsimpGuide(): void
    {
        $content = $this->read($this->root . '/mkdocs.yml');

        $this->assertStringContainsString(
            'ASIMP for AI Agents Guide: docs/governance/ASIMP-FOR-AI-AGENTS.md',
            $content
        );
    }

    public function testMkdocsAsimpEntryIsNestedUnderEnvironmentSetup(): void
    {
        $content = $this->read($this->root . '/mkdocs.yml');

        $sectionPos = strpos($content, 'Environment Setup:');
        $telemetryPos = strpos($content, 'Telemetry & Bidirectional Feedback Pipeline SOP:');
        $entryPos = strpos($content, 'ASIMP for AI Agents Guide:');
        $graduationPos = strpos($content, 'Graduation:');

        $this->assertNotFalse($sectionPos);
        $this->assertNotFalse($telemetryPos);
        $this->assertNotFalse($entryPos);
        $this->assertNotFalse($graduationPos);

        $this->assertGreaterThan($sectionPos, $entryPos);
        $this->assertGreaterThan($telemetryPos, $entryPos, 'The ASIMP guide entry must follow the Telemetry SOP entry within Environment Setup.');
        $this->assertLessThan($graduationPos, $entryPos, 'The ASIMP guide entry must remain nested under Environment Setup, before Graduation.');
    }

    // ---------------------------------------------------------------
    // Navigation registration: llms.txt
    // ---------------------------------------------------------------

    public function testLlmsTxtRegistersAsimpGuideWithDescription(): void
    {
        $content = $this->read($this->root . '/llms.txt');

        $this->assertStringContainsString(
            '- [docs/governance/ASIMP-FOR-AI-AGENTS.md](docs/governance/ASIMP-FOR-AI-AGENTS.md): ASIMP for AI Agents detailing how the Ansible System Integrity Management Platform (ASIMP) aligns with AI agents, DSOM spatial memory, and the OpenWiki emulator.',
            $content
        );
    }

    public function testLlmsTxtStartHereBulletAdvertisesSixteenEntryPoints(): void
    {
        $content = $this->read($this->root . '/llms.txt');

        $this->assertStringContainsString(
            '- [START-HERE.md](START-HERE.md): Onboarding blueprint with 16 defined Entry Points.',
            $content
        );
        $this->assertStringNotContainsString('Onboarding blueprint with 15 defined Entry Points.', $content);
    }

    public function testLlmsTxtAsimpEntryAppearsWithinEssentialAiAndDsomSection(): void
    {
        $content = $this->read($this->root . '/llms.txt');

        $sectionPos = strpos($content, '## Essential AI & DSOM Entry Points');
        $entryPos = strpos($content, '[docs/governance/ASIMP-FOR-AI-AGENTS.md]');
        $nextSectionPos = strpos($content, '## Laboratory Modules');

        $this->assertNotFalse($sectionPos);
        $this->assertNotFalse($entryPos);
        $this->assertNotFalse($nextSectionPos);
        $this->assertGreaterThan($sectionPos, $entryPos);
        $this->assertLessThan($nextSectionPos, $entryPos);
    }

    // ---------------------------------------------------------------
    // Cross-document consistency
    // ---------------------------------------------------------------

    public function testGovernanceDocPathIsIdenticalAcrossAllFourNavigationLayers(): void
    {
        $canonicalPath = 'docs/governance/ASIMP-FOR-AI-AGENTS.md';

        foreach (
            [
                'START-HERE.md',
                'SUMMARY.md',
                'mkdocs.yml',
                'llms.txt',
            ] as $navFile
        ) {
            $content = $this->read($this->root . '/' . $navFile);
            $this->assertStringContainsString(
                $canonicalPath,
                $content,
                "Navigation file '{$navFile}' must reference the canonical governance doc path."
            );
        }
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