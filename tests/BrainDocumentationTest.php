<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Validates the DSOM "brain" hand-over documents (.agents/brain/task.md and
 * .agents/brain/walkthrough.md) accurately record the "Module 20: Apache
 * .htaccess & Docker Alignment (v4.1.9)" change set, so future AI sessions
 * inherit an accurate history of the .htaccess/Dockerfile/Containerfile/
 * sonar-project.properties/.dockerignore fixes.
 */
final class BrainDocumentationTest extends TestCase
{
    private const FOOTER_TIMESTAMP_LINE
        = '*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-01*';

    private const FOOTER_STANDARD_LINE
        = '*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*';

    private string $taskPath;
    private string $walkthroughPath;

    protected function setUp(): void
    {
        $this->taskPath = dirname(__DIR__) . '/.agents/brain/task.md';
        $this->walkthroughPath = dirname(__DIR__) . '/.agents/brain/walkthrough.md';
    }

    public function testTaskFileExists(): void
    {
        $this->assertFileExists($this->taskPath);
    }

    public function testWalkthroughFileExists(): void
    {
        $this->assertFileExists($this->walkthroughPath);
    }

    public function testTaskFileRecordsModule20Heading(): void
    {
        $content = file_get_contents($this->taskPath);

        $this->assertStringContainsString(
            '## [x] Module 20: Apache .htaccess & Docker Alignment (v4.1.9)',
            $content
        );
    }

    public function testTaskFileModule20ChecklistItemsAreAllMarkedComplete(): void
    {
        $content = file_get_contents($this->taskPath);

        $moduleStart = strpos($content, '## [x] Module 20: Apache .htaccess & Docker Alignment (v4.1.9)');
        $this->assertNotFalse($moduleStart, 'Module 20 heading must be present before asserting on its checklist body.');

        $nextHeadingPos = strpos($content, "\n## ", $moduleStart + 1);
        $moduleBody = $nextHeadingPos === false
            ? substr($content, $moduleStart)
            : substr($content, $moduleStart, $nextHeadingPos - $moduleStart);

        $checklistLines = array_values(array_filter(
            explode("\n", $moduleBody),
            static fn (string $line): bool => str_starts_with(trim($line), '- [')
        ));

        $this->assertNotEmpty($checklistLines, 'Module 20 must declare at least one checklist item.');

        foreach ($checklistLines as $line) {
            $this->assertStringStartsWith(
                '- [x]',
                trim($line),
                "Every Module 20 checklist item must be marked complete: '{$line}'."
            );
        }
    }

    public function testTaskFileModule20MentionsAllFixesFromThisChangeSet(): void
    {
        $content = file_get_contents($this->taskPath);

        foreach (
            [
                'Fix `.htaccess` syntax errors by removing `<DirectoryMatch>` and `<Directory>` tags',
                'Align `Dockerfile` and `Containerfile` with PHPUnit DockerBuildTest requirements',
                'Synchronise `sonar-project.properties` exclusions with `.github/workflows/build.yml`',
                'Deduplicate header comments in `.dockerignore`',
                'Verify compliance using `composer lab-check`',
            ] as $expected
        ) {
            $this->assertStringContainsString($expected, $content, "Module 20 checklist must mention: '{$expected}'.");
        }
    }

    public function testTaskFileFooterTimestampWasUpdated(): void
    {
        $content = file_get_contents($this->taskPath);

        $this->assertStringContainsString(self::FOOTER_TIMESTAMP_LINE, $content);
    }

    public function testWalkthroughFileRecordsSessionAnchorHeading(): void
    {
        $content = file_get_contents($this->walkthroughPath);

        $this->assertStringContainsString(
            '## 🏁 Session Anchor: 2026-07-31 — Apache .htaccess & Docker Alignment (v4.1.9)',
            $content
        );
    }

    public function testWalkthroughFileExplainsWhyDirectoryDirectivesWereRemoved(): void
    {
        $content = file_get_contents($this->walkthroughPath);

        $this->assertStringContainsString(
            'Apache strictly forbids `<Directory>` and `<DirectoryMatch>` directives within directory-level ' .
            'configurations (`.htaccess`)',
            $content
        );
    }

    public function testWalkthroughFileDescribesDockerAndSonarAlignmentAccomplishments(): void
    {
        $content = file_get_contents($this->walkthroughPath);

        foreach (
            [
                'limiting `chown` recursively to only `/var/www/html/data`',
                'exposing both port 80 and port 8080',
                'Synced the local `sonar-project.properties` exclusions list with the GitHub Actions workflow ' .
                'definition file `.github/workflows/build.yml`',
                'Replaced duplicate separator comments in `.dockerignore`',
            ] as $expected
        ) {
            $this->assertStringContainsString($expected, $content, "Walkthrough accomplishments must mention: '{$expected}'.");
        }
    }

    public function testWalkthroughFileFooterTimestampWasUpdated(): void
    {
        $content = file_get_contents($this->walkthroughPath);

        $this->assertStringContainsString(self::FOOTER_TIMESTAMP_LINE, $content);
    }

    public function testBothBrainDocumentsShareIdenticalDsomFooterStandardLine(): void
    {
        $taskContent = file_get_contents($this->taskPath);
        $walkthroughContent = file_get_contents($this->walkthroughPath);

        $this->assertStringContainsString(self::FOOTER_STANDARD_LINE, $taskContent);
        $this->assertStringContainsString(self::FOOTER_STANDARD_LINE, $walkthroughContent);
    }
}
