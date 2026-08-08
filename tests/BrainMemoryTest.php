<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Validates the Sovereign Markdown Palace memory files (`.agents/brain/task.md`
 * and `.agents/brain/walkthrough.md`) updated as part of the "Apache
 * .htaccess & Docker Alignment" (v4.1.9) change set.
 *
 * These files are the project's DSOM session/task journal. This suite
 * verifies the Module 20 entries were recorded consistently in both files
 * and that the mandatory DSOM footer signature was refreshed, matching the
 * repository's established documentation-compliance testing pattern (see
 * RenderDeploymentTest and SonarConfigurationTest).
 */
final class BrainMemoryTest extends TestCase
{
    private string $taskPath;
    private string $walkthroughPath;
    private string $taskContent;
    private string $walkthroughContent;

    protected function setUp(): void
    {
        $this->taskPath = dirname(__DIR__) . '/.agents/brain/task.md';
        $this->walkthroughPath = dirname(__DIR__) . '/.agents/brain/walkthrough.md';
        $this->taskContent = (string) file_get_contents($this->taskPath);
        $this->walkthroughContent = (string) file_get_contents($this->walkthroughPath);
    }

    public function testTaskMdExists(): void
    {
        $this->assertFileExists($this->taskPath);
    }

    public function testWalkthroughMdExists(): void
    {
        $this->assertFileExists($this->walkthroughPath);
    }

    public function testTaskMdContainsModule20Heading(): void
    {
        $this->assertStringContainsString(
            '## [x] Module 20: Apache .htaccess & Docker Alignment (v4.1.9)',
            $this->taskContent
        );
    }

    public function testTaskMdModule20HeadingAppearsExactlyOnce(): void
    {
        $count = substr_count($this->taskContent, 'Module 20: Apache .htaccess & Docker Alignment (v4.1.9)');

        $this->assertSame(1, $count, 'Module 20 heading must not be duplicated in task.md.');
    }

    public function testTaskMdModule20ListsAllComplianceItems(): void
    {
        $expectedItems = [
            'Fix `.htaccess` syntax errors by removing `<DirectoryMatch>` and `<Directory>` tags',
            'Align `Dockerfile` and `Containerfile` with PHPUnit DockerBuildTest requirements',
            'Synchronise `sonar-project.properties` exclusions with `.github/workflows/build.yml`.',
            'Deduplicate header comments in `.dockerignore` to satisfy unique line constraints.',
            'Verify compliance using `composer lab-check` with 100% test passing rate.',
        ];

        foreach ($expectedItems as $item) {
            $this->assertStringContainsString($item, $this->taskContent, "Expected task.md Module 20 section to record: '{$item}'.");
        }
    }

    public function testTaskMdFooterSignatureIsUpdated(): void
    {
        $this->assertStringContainsString(
            '*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-01*',
            $this->taskContent
        );
    }

    public function testWalkthroughMdContainsModule20SessionAnchor(): void
    {
        $this->assertStringContainsString(
            '## 🏁 Session Anchor: 2026-07-31 — Apache .htaccess & Docker Alignment (v4.1.9)',
            $this->walkthroughContent
        );
    }

    public function testWalkthroughMdSessionAnchorAppearsExactlyOnce(): void
    {
        $count = substr_count($this->walkthroughContent, 'Session Anchor: 2026-07-31 — Apache .htaccess & Docker Alignment (v4.1.9)');

        $this->assertSame(1, $count, 'The 2026-07-31 session anchor must not be duplicated in walkthrough.md.');
    }

    public function testWalkthroughMdModule20DescribesEachAccomplishment(): void
    {
        $expectedFragments = [
            'Apache .htaccess Compliance',
            'Dockerfile & Containerfile Parity',
            'Sonar Exclusions Synchronization',
            'Dockerignore Deduplication',
            'Local Lab Check Success',
        ];

        foreach ($expectedFragments as $fragment) {
            $this->assertStringContainsString($fragment, $this->walkthroughContent, "Expected walkthrough.md Module 20 section to mention: '{$fragment}'.");
        }
    }

    public function testWalkthroughMdModule20HasMentalAnchor(): void
    {
        $this->assertStringContainsString(
            'The web server deployment configuration is fully secure and compatible with both Render cloud environment and local Pest PHP testing assertions.',
            $this->walkthroughContent
        );
    }

    public function testWalkthroughMdFooterSignatureIsUpdated(): void
    {
        $this->assertStringContainsString(
            '*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-01*',
            $this->walkthroughContent
        );
    }

    public function testTaskMdAndWalkthroughMdShareIdenticalFooterSignature(): void
    {
        $extractFooter = function (string $content): string {
            $lines = array_values(array_filter(explode("\n", trim($content))));
            $lastTwoLines = array_slice($lines, -2);

            return trim(implode("\n", $lastTwoLines));
        };

        $this->assertSame(
            $extractFooter($this->taskContent),
            $extractFooter($this->walkthroughContent),
            'task.md and walkthrough.md must carry the exact same two-line DSOM footer signature block.'
        );
    }

    public function testBothFilesUseStandardDsomFooterFormat(): void
    {
        $pattern = '/\*Deep State of Mind \(DSOM\) For My AI Protocol \| Harisfazillah Jamel \(LinuxMalaysia\) \| \d{4}-\d{2}-\d{2}[^*]*\*/';

        $this->assertMatchesRegularExpression($pattern, $this->taskContent);
        $this->assertMatchesRegularExpression($pattern, $this->walkthroughContent);
    }
}
