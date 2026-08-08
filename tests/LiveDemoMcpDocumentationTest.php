<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Validates "Module 21: Live Demo Site & MCP Access Documentation (v4.2.0)":
 * the production live demo link (https://cmsfornerd.onrender.com/index.php)
 * and the public Context7 MCP/LLM crawler link
 * (https://context7.com/cmsfornerd/cmsfornerd/llms.txt?tokens=10000) must be
 * consistently registered across README.md, START-HERE.md,
 * docs/RENDER-DEPLOYMENT-GUIDE.md, .llms/index.md, and llms.txt, and the
 * DSOM "brain" hand-over documents must accurately record the change set.
 */
final class LiveDemoMcpDocumentationTest extends TestCase
{
    private const LIVE_DEMO_URL = 'https://cmsfornerd.onrender.com/index.php';

    private const CONTEXT7_MCP_URL = 'https://context7.com/cmsfornerd/cmsfornerd/llms.txt?tokens=10000';

    private const FOOTER_TIMESTAMP_LINE
        = '*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-01*';

    private string $taskPath;
    private string $walkthroughPath;
    private string $llmsIndexPath;
    private string $readmePath;
    private string $startHerePath;
    private string $renderGuidePath;
    private string $llmsTxtPath;

    protected function setUp(): void
    {
        $root = dirname(__DIR__);

        $this->taskPath = $root . '/.agents/brain/task.md';
        $this->walkthroughPath = $root . '/.agents/brain/walkthrough.md';
        $this->llmsIndexPath = $root . '/.llms/index.md';
        $this->readmePath = $root . '/README.md';
        $this->startHerePath = $root . '/START-HERE.md';
        $this->renderGuidePath = $root . '/docs/RENDER-DEPLOYMENT-GUIDE.md';
        $this->llmsTxtPath = $root . '/llms.txt';
    }

    // ---------------------------------------------------------------
    // .agents/brain/task.md — Module 21 checklist
    // ---------------------------------------------------------------

    public function testTaskFileRecordsModule21Heading(): void
    {
        $content = file_get_contents($this->taskPath);

        $this->assertStringContainsString(
            '## [x] Module 21: Live Demo Site & MCP Access Documentation (v4.2.0)',
            $content
        );
    }

    public function testTaskFileModule21ChecklistItemsAreAllMarkedComplete(): void
    {
        $content = file_get_contents($this->taskPath);

        $moduleStart = strpos($content, '## [x] Module 21: Live Demo Site & MCP Access Documentation (v4.2.0)');
        $this->assertNotFalse($moduleStart, 'Module 21 heading must be present before asserting on its checklist body.');

        $nextHeadingPos = strpos($content, "\n## ", $moduleStart + 1);
        $moduleBody = $nextHeadingPos === false
            ? substr($content, $moduleStart)
            : substr($content, $moduleStart, $nextHeadingPos - $moduleStart);

        $checklistLines = array_values(array_filter(
            explode("\n", $moduleBody),
            static fn (string $line): bool => str_starts_with(trim($line), '- [')
        ));

        $this->assertNotEmpty($checklistLines, 'Module 21 must declare at least one checklist item.');

        foreach ($checklistLines as $line) {
            $this->assertStringStartsWith(
                '- [x]',
                trim($line),
                "Every Module 21 checklist item must be marked complete: '{$line}'."
            );
        }
    }

    public function testTaskFileModule21MentionsLiveDemoUrlAndItsThreeDocumentationTargets(): void
    {
        $content = file_get_contents($this->taskPath);

        $moduleStart = strpos($content, '## [x] Module 21: Live Demo Site & MCP Access Documentation (v4.2.0)');
        $this->assertNotFalse($moduleStart);

        $line = $this->extractLineContaining($content, $moduleStart, 'production live demo site');

        $this->assertStringContainsString('`' . self::LIVE_DEMO_URL . '`', $line);
        $this->assertStringContainsString('`README.md`', $line);
        $this->assertStringContainsString('`START-HERE.md`', $line);
        $this->assertStringContainsString('`docs/RENDER-DEPLOYMENT-GUIDE.md`', $line);
    }

    public function testTaskFileModule21MentionsContext7McpUrlAndItsFourDocumentationTargets(): void
    {
        $content = file_get_contents($this->taskPath);

        $moduleStart = strpos($content, '## [x] Module 21: Live Demo Site & MCP Access Documentation (v4.2.0)');
        $this->assertNotFalse($moduleStart);

        $line = $this->extractLineContaining($content, $moduleStart, 'Context7 MCP and LLM crawler link');

        $this->assertStringContainsString('`' . self::CONTEXT7_MCP_URL . '`', $line);
        $this->assertStringContainsString('`README.md`', $line);
        $this->assertStringContainsString('`START-HERE.md`', $line);
        $this->assertStringContainsString('`.llms/index.md`', $line);
        $this->assertStringContainsString('`llms.txt`', $line);
    }

    public function testTaskFileModule21MentionsSignatureInjectorAndComplianceSteps(): void
    {
        $content = file_get_contents($this->taskPath);

        foreach (
            [
                'Process all modified documents via `dsom-signature-injector` and verify OKF v0.1 compliance.',
                'Execute standard development checks to ensure 100% test pass rate and clean static analysis.',
            ] as $expected
        ) {
            $this->assertStringContainsString($expected, $content, "Module 21 checklist must mention: '{$expected}'.");
        }
    }

    public function testTaskFileFooterTimestampRemainsUnchanged(): void
    {
        $content = file_get_contents($this->taskPath);

        $this->assertStringContainsString(self::FOOTER_TIMESTAMP_LINE, $content);
    }

    // ---------------------------------------------------------------
    // .agents/brain/walkthrough.md — Session Anchor
    // ---------------------------------------------------------------

    public function testWalkthroughFileRecordsLiveDemoSessionAnchorHeading(): void
    {
        $content = file_get_contents($this->walkthroughPath);

        $this->assertStringContainsString(
            '## 🏁 Session Anchor: 2026-07-31 — Live Demo Site & MCP Access Documentation (v4.2.0)',
            $content
        );
    }

    public function testWalkthroughFileNewSessionAnchorIsPlacedBeforeThePreviousOne(): void
    {
        $content = file_get_contents($this->walkthroughPath);

        $newAnchorPos = strpos(
            $content,
            '## 🏁 Session Anchor: 2026-07-31 — Live Demo Site & MCP Access Documentation (v4.2.0)'
        );
        $previousAnchorPos = strpos(
            $content,
            '## 🏁 Session Anchor: 2026-07-30 — Render Cloud Blueprint Specification (v4.1.8)'
        );

        $this->assertNotFalse($newAnchorPos);
        $this->assertNotFalse($previousAnchorPos);
        $this->assertLessThan(
            $previousAnchorPos,
            $newAnchorPos,
            'The newest session anchor must be recorded above older session anchors so the log reads most-recent-first.'
        );
    }

    public function testWalkthroughFileAccomplishmentsDescribeLiveDemoLinkAcrossThreeDocuments(): void
    {
        $content = file_get_contents($this->walkthroughPath);

        $this->assertStringContainsString(
            '**Production Live Demo Link**: Added reference links to the official production live demo site ' .
            '(' . self::LIVE_DEMO_URL . ') in the introductory and deployment sections of all key project ' .
            'documents, including README.md, START-HERE.md, and docs/RENDER-DEPLOYMENT-GUIDE.md.',
            $content
        );
    }

    public function testWalkthroughFileAccomplishmentsDescribeContext7McpLinkAcrossFourDocuments(): void
    {
        $content = file_get_contents($this->walkthroughPath);

        $this->assertStringContainsString(
            '**Context7 MCP & LLM Crawler Access**: Formally registered the dedicated LLM indexing reference link ' .
            '(' . self::CONTEXT7_MCP_URL . ') within README.md, START-HERE.md, .llms/index.md, and llms.txt.',
            $content
        );
    }

    public function testWalkthroughFileWhySectionExplainsRationale(): void
    {
        $content = file_get_contents($this->walkthroughPath);

        $this->assertStringContainsString(
            'Integrating direct, standard links to the live environment and the public Context7 MCP endpoint ' .
            'reduces cognitive onboarding friction and enables automated crawls to index the latest ' .
            'state-of-mind ruleset.',
            $content
        );
    }

    public function testWalkthroughFileMentalAnchorQuoteIsPresent(): void
    {
        $content = file_get_contents($this->walkthroughPath);

        $this->assertStringContainsString(
            '> The production live demo and MCP crawl standard links are successfully published and fully ' .
            'documented across all five major topology and navigation hubs of the platform.',
            $content
        );
    }

    public function testWalkthroughFileFooterTimestampRemainsUnchanged(): void
    {
        $content = file_get_contents($this->walkthroughPath);

        $this->assertStringContainsString(self::FOOTER_TIMESTAMP_LINE, $content);
    }

    // ---------------------------------------------------------------
    // .llms/index.md
    // ---------------------------------------------------------------

    public function testLlmsIndexContainsLiveDemoLink(): void
    {
        $content = file_get_contents($this->llmsIndexPath);

        $this->assertStringContainsString(
            '**Live Demo URL:** [CmsForNerd Live Demo](' . self::LIVE_DEMO_URL . ')',
            $content
        );
    }

    public function testLlmsIndexContainsContext7McpLink(): void
    {
        $content = file_get_contents($this->llmsIndexPath);

        $this->assertStringContainsString(
            '**MCP Standard Index Context (Token Capped):** [Context7 MCP & LLM standard link](' .
            self::CONTEXT7_MCP_URL . ')',
            $content
        );
    }

    public function testLlmsIndexLinksAppearBeforeMainDescriptionParagraph(): void
    {
        $content = file_get_contents($this->llmsIndexPath);

        $liveDemoPos = strpos($content, '**Live Demo URL:**');
        $mcpPos = strpos($content, '**MCP Standard Index Context');
        $descriptionPos = strpos($content, 'This document is optimised for parsing by LLMs');

        $this->assertNotFalse($liveDemoPos);
        $this->assertNotFalse($mcpPos);
        $this->assertNotFalse($descriptionPos);
        $this->assertLessThan($descriptionPos, $liveDemoPos);
        $this->assertLessThan($descriptionPos, $mcpPos);
    }

    // ---------------------------------------------------------------
    // README.md
    // ---------------------------------------------------------------

    public function testReadmeContainsLiveDemoLink(): void
    {
        $content = file_get_contents($this->readmePath);

        $this->assertStringContainsString(
            '**Live Demo:** [CmsForNerd Live Demo](' . self::LIVE_DEMO_URL . ')',
            $content
        );
    }

    public function testReadmeContainsSovereignContextIndexLink(): void
    {
        $content = file_get_contents($this->readmePath);

        $this->assertStringContainsString(
            '**Sovereign Context Index (LLM standard):** [Context7 MCP Access & LLM Index](' .
            self::CONTEXT7_MCP_URL . ')',
            $content
        );
    }

    public function testReadmeLiveDemoAndSovereignIndexLinksAppearBeforeChangelogLine(): void
    {
        $content = file_get_contents($this->readmePath);

        $liveDemoPos = strpos($content, '**Live Demo:**');
        $sovereignPos = strpos($content, '**Sovereign Context Index');
        $changelogPos = strpos($content, '**Changelog:**');

        $this->assertNotFalse($liveDemoPos);
        $this->assertNotFalse($sovereignPos);
        $this->assertNotFalse($changelogPos);
        $this->assertLessThan($changelogPos, $liveDemoPos);
        $this->assertLessThan($changelogPos, $sovereignPos);
    }

    public function testReadmeOption4StillReferencesDeploymentGuideAndNowLinksLiveDemo(): void
    {
        $content = file_get_contents($this->readmePath);

        $this->assertStringContainsString(
            'Learn how to deploy to Render Cloud in our [Render Deployment Guide]' .
            '(docs/RENDER-DEPLOYMENT-GUIDE.md). View the [Live Demo on Render](' . self::LIVE_DEMO_URL . ').',
            $content
        );
    }

    // ---------------------------------------------------------------
    // START-HERE.md
    // ---------------------------------------------------------------

    public function testStartHereIntroMentionsLiveDemoLink(): void
    {
        $content = file_get_contents($this->startHerePath);

        $this->assertStringContainsString(
            'View the [Live Demo](' . self::LIVE_DEMO_URL . ')',
            $content
        );
    }

    public function testStartHereIntroMentionsContext7McpLink(): void
    {
        $content = file_get_contents($this->startHerePath);

        $this->assertStringContainsString(
            'access via our [Context7 MCP & LLM standard link](' . self::CONTEXT7_MCP_URL . ')',
            $content
        );
    }

    public function testStartHereIntroRetainsOriginalOnboardingSentenceAroundNewLinks(): void
    {
        $content = file_get_contents($this->startHerePath);

        $this->assertStringContainsString(
            'Welcome to the **CmsForNerd v4.3.0** Sovereign AI & Human Onboarding Map.',
            $content,
            'Inserting the new links must not clobber the original welcome sentence.'
        );
        $this->assertStringContainsString(
            'This file serves as the master blueprint',
            $content,
            'Inserting the new links must not clobber the sentence describing the file purpose.'
        );
        $this->assertStringContainsString(
            'linking to all 15 major Entry Points of the platform, enabling instant orientation and semantic traversal.',
            $content
        );
    }

    // ---------------------------------------------------------------
    // docs/RENDER-DEPLOYMENT-GUIDE.md
    // ---------------------------------------------------------------

    public function testRenderDeploymentGuideIntroMentionsLiveDemoLink(): void
    {
        $content = file_get_contents($this->renderGuidePath);

        $this->assertStringContainsString(
            'view our [Live Demo](' . self::LIVE_DEMO_URL . ')',
            $content
        );
    }

    public function testRenderDeploymentGuideIntroMentionsContext7McpLink(): void
    {
        $content = file_get_contents($this->renderGuidePath);

        $this->assertStringContainsString(
            'access via our [Context7 MCP & LLM standard link](' . self::CONTEXT7_MCP_URL . ')',
            $content
        );
    }

    public function testRenderDeploymentGuideIntroStillDescribesDockerfileContainerfileCoexistence(): void
    {
        $content = file_get_contents($this->renderGuidePath);

        $this->assertStringContainsString(
            'co-existing `Dockerfile` and `Containerfile` files',
            $content,
            'Inserting the new links into the intro sentence must not drop the existing Dockerfile/Containerfile explanation.'
        );
        $this->assertStringContainsString(
            'using Infrastructure as Code (IaC) via the `render.yaml` Blueprint specification',
            $content
        );
    }

    // ---------------------------------------------------------------
    // llms.txt
    // ---------------------------------------------------------------

    public function testLlmsTxtContainsLiveDemoBullet(): void
    {
        $content = file_get_contents($this->llmsTxtPath);

        $this->assertStringContainsString(
            '- **Live Demo:** [' . self::LIVE_DEMO_URL . '](' . self::LIVE_DEMO_URL . ')',
            $content
        );
    }

    public function testLlmsTxtContainsMcpCrawlerAccessBullet(): void
    {
        $content = file_get_contents($this->llmsTxtPath);

        $this->assertStringContainsString(
            '- **MCP standard crawler Access:** [' . self::CONTEXT7_MCP_URL . '](' . self::CONTEXT7_MCP_URL . ')',
            $content
        );
    }

    public function testLlmsTxtBulletsAppearBeforeTheDescriptiveBlockquote(): void
    {
        $content = file_get_contents($this->llmsTxtPath);

        $liveDemoPos = strpos($content, '- **Live Demo:**');
        $mcpPos = strpos($content, '- **MCP standard crawler Access:**');
        $blockquotePos = strpos($content, '> CmsForNerd is a hardened, flat-file PHP laboratory CMS');

        $this->assertNotFalse($liveDemoPos);
        $this->assertNotFalse($mcpPos);
        $this->assertNotFalse($blockquotePos);
        $this->assertLessThan($blockquotePos, $liveDemoPos);
        $this->assertLessThan($blockquotePos, $mcpPos);
    }

    public function testLlmsTxtTitleHeadingIsUnaffectedByNewBullets(): void
    {
        $content = file_get_contents($this->llmsTxtPath);

        $this->assertStringStartsWith("# CmsForNerd v4.3.0\n", $content);
    }

    // ---------------------------------------------------------------
    // Cross-document consistency & regression guards
    // ---------------------------------------------------------------

    public function testLiveDemoUrlIsByteIdenticalAcrossEveryDocumentThatReferencesIt(): void
    {
        foreach (
            [
                $this->taskPath,
                $this->walkthroughPath,
                $this->llmsIndexPath,
                $this->readmePath,
                $this->startHerePath,
                $this->renderGuidePath,
                $this->llmsTxtPath,
            ] as $path
        ) {
            $content = file_get_contents($path);

            $this->assertStringContainsString(
                self::LIVE_DEMO_URL,
                $content,
                "Expected the canonical live demo URL to appear in '{$path}'."
            );
        }
    }

    public function testContext7McpUrlIsByteIdenticalAcrossEveryDocumentThatReferencesIt(): void
    {
        foreach (
            [
                $this->taskPath,
                $this->walkthroughPath,
                $this->llmsIndexPath,
                $this->readmePath,
                $this->startHerePath,
                $this->renderGuidePath,
                $this->llmsTxtPath,
            ] as $path
        ) {
            $content = file_get_contents($path);

            $this->assertStringContainsString(
                self::CONTEXT7_MCP_URL,
                $content,
                "Expected the canonical Context7 MCP URL to appear in '{$path}'."
            );
        }
    }

    public function testCanonicalUrlsUseHttpsSchemeOnly(): void
    {
        $this->assertStringStartsWith('https://', self::LIVE_DEMO_URL);
        $this->assertStringStartsWith('https://', self::CONTEXT7_MCP_URL);
        $this->assertStringNotContainsString('http://', self::LIVE_DEMO_URL);
        $this->assertStringNotContainsString('http://', self::CONTEXT7_MCP_URL);
    }

    public function testNoDocumentAccidentallyIntroducesAnHttpVariantOfTheCanonicalUrls(): void
    {
        $insecureLiveDemo = str_replace('https://', 'http://', self::LIVE_DEMO_URL);
        $insecureContext7 = str_replace('https://', 'http://', self::CONTEXT7_MCP_URL);

        foreach (
            [
                $this->taskPath,
                $this->walkthroughPath,
                $this->llmsIndexPath,
                $this->readmePath,
                $this->startHerePath,
                $this->renderGuidePath,
                $this->llmsTxtPath,
            ] as $path
        ) {
            $content = file_get_contents($path);

            $this->assertStringNotContainsString(
                $insecureLiveDemo,
                $content,
                "Document '{$path}' must not reference an insecure http:// variant of the live demo URL."
            );
            $this->assertStringNotContainsString(
                $insecureContext7,
                $content,
                "Document '{$path}' must not reference an insecure http:// variant of the Context7 MCP URL."
            );
        }
    }

    /**
     * Finds the first line, starting the search from $offset, whose trimmed
     * content contains $needle. Fails the test if no such line is found.
     */
    private function extractLineContaining(string $content, int $offset, string $needle): string
    {
        $remainder = substr($content, $offset);

        foreach (explode("\n", $remainder) as $line) {
            if (str_contains($line, $needle)) {
                return $line;
            }
        }

        $this->fail("Could not find a line containing '{$needle}' after offset {$offset}.");
    }
}
