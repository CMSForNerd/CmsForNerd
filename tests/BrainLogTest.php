<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Validates the ".agents/brain" session log files (task.md and
 * walkthrough.md) that record the "Apache .htaccess & Docker Alignment"
 * work (Module 20, v4.1.9).
 *
 * These are DSOM protocol journals rather than executable code, but the
 * project's convention (see RenderDeploymentTest) is to assert that
 * documentation stays consistent with the change it describes and that the
 * two logs do not drift out of sync with one another.
 */
final class BrainLogTest extends TestCase
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

    public function testBrainLogFilesExist(): void
    {
        $this->assertFileExists($this->taskPath);
        $this->assertFileExists($this->walkthroughPath);
    }

    public function testTaskLogDocumentsModule20WithAllItemsChecked(): void
    {
        $this->assertStringContainsString(
            '## [x] Module 20: Apache .htaccess & Docker Alignment (v4.1.9)',
            $this->taskContent,
            'task.md must record Module 20 as a completed (checked) heading.'
        );

        $moduleStart = strpos($this->taskContent, '## [x] Module 20:');
        $this->assertNotFalse($moduleStart);

        $sectionEnd = strpos($this->taskContent, '---', $moduleStart);
        $section = $sectionEnd !== false
            ? substr($this->taskContent, $moduleStart, $sectionEnd - $moduleStart)
            : substr($this->taskContent, $moduleStart);

        $itemCount = preg_match_all('/^- \[x\] /m', $section);
        $this->assertGreaterThanOrEqual(
            5,
            $itemCount,
            'Module 20 must list its checklist items as completed ("- [x] ").'
        );
        $this->assertSame(
            0,
            preg_match_all('/^- \[ \] /m', $section),
            'Module 20 must not contain unchecked ("- [ ] ") checklist items.'
        );
    }

    public function testTaskLogFooterTimestampMatchesLatestSession(): void
    {
        $this->assertStringContainsString(
            'Harisfazillah Jamel (LinuxMalaysia) | 2026-08-01',
            $this->taskContent,
            'The DSOM footer timestamp must reflect the date of the latest recorded session.'
        );
    }

    public function testWalkthroughLogDocumentsModule20SessionAnchor(): void
    {
        $this->assertStringContainsString(
            '## 🏁 Session Anchor: 2026-07-31 — Apache .htaccess & Docker Alignment (v4.1.9)',
            $this->walkthroughContent,
            'walkthrough.md must record a Session Anchor heading for the Module 20 work.'
        );
        $this->assertStringContainsString('### Accomplishments', $this->walkthroughContent);
        $this->assertStringContainsString('### Why', $this->walkthroughContent);
        $this->assertStringContainsString('### Mental Anchor', $this->walkthroughContent);
    }

    public function testWalkthroughLogExplainsWhyDirectoryDirectivesWereRemoved(): void
    {
        $this->assertStringContainsString(
            '<DirectoryMatch>',
            $this->walkthroughContent,
            'The walkthrough must reference the removed <DirectoryMatch> directive for future maintainers.'
        );
        $this->assertStringContainsString(
            '<Directory>',
            $this->walkthroughContent,
            'The walkthrough must reference the removed <Directory> directive for future maintainers.'
        );
        $this->assertStringContainsString('mod_rewrite', $this->walkthroughContent);
    }

    public function testWalkthroughLogFooterTimestampMatchesLatestSession(): void
    {
        $this->assertStringContainsString(
            'Harisfazillah Jamel (LinuxMalaysia) | 2026-08-01',
            $this->walkthroughContent,
            'The DSOM footer timestamp must reflect the date of the latest recorded session.'
        );
    }

    public function testTaskAndWalkthroughLogsReferenceTheSameModuleVersion(): void
    {
        $this->assertStringContainsString(
            'Apache .htaccess & Docker Alignment (v4.1.9)',
            $this->taskContent,
            'task.md must tag the module with the "Apache .htaccess & Docker Alignment (v4.1.9)" label.'
        );
        $this->assertStringContainsString(
            'Apache .htaccess & Docker Alignment (v4.1.9)',
            $this->walkthroughContent,
            'walkthrough.md must use the identical "Apache .htaccess & Docker Alignment (v4.1.9)" label so the two logs stay in sync.'
        );
    }
}
