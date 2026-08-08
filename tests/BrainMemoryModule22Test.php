<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Validates the ".agents/brain/task.md" entry recorded for "Module 22:
 * Context Re-learning & Windows Shell Compliance (v4.2.1)", the DSOM
 * hand-over journal entry documenting the composer.json Windows
 * PowerShell escaping fix, so future AI sessions inherit an accurate
 * history of the change (see the repository's established
 * documentation-compliance testing pattern in BrainMemoryTest and
 * BrainDocumentationTest).
 */
final class BrainMemoryModule22Test extends TestCase
{
    private const MODULE_22_HEADING = '## [x] Module 22: Context Re-learning & Windows Shell Compliance (v4.2.1)';

    private const FOOTER_TIMESTAMP_LINE
        = '*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-01*';

    private string $taskPath;
    private string $taskContent;

    protected function setUp(): void
    {
        $this->taskPath = dirname(__DIR__) . '/.agents/brain/task.md';
        $this->taskContent = (string) file_get_contents($this->taskPath);
    }

    public function testTaskFileExists(): void
    {
        $this->assertFileExists($this->taskPath);
    }

    public function testTaskFileRecordsModule22Heading(): void
    {
        $this->assertStringContainsString(self::MODULE_22_HEADING, $this->taskContent);
    }

    public function testModule22HeadingAppearsExactlyOnce(): void
    {
        $this->assertSame(
            1,
            substr_count($this->taskContent, 'Module 22: Context Re-learning & Windows Shell Compliance (v4.2.1)'),
            'Module 22 heading must not be duplicated in task.md.'
        );
    }

    public function testModule22IsRecordedAfterModule21(): void
    {
        $module21Pos = strpos(
            $this->taskContent,
            '## [x] Module 21: Live Demo Site & MCP Access Documentation (v4.2.0)'
        );
        $module22Pos = strpos($this->taskContent, self::MODULE_22_HEADING);

        $this->assertNotFalse($module21Pos, 'Module 21 heading must still be present.');
        $this->assertNotFalse($module22Pos, 'Module 22 heading must be present.');
        $this->assertGreaterThan(
            $module21Pos,
            $module22Pos,
            'Module 22 must be recorded after Module 21 so the task log reads in chronological order.'
        );
    }

    public function testModule22ChecklistItemsAreAllMarkedComplete(): void
    {
        $section = $this->extractModule22Section();

        $checklistLines = array_values(array_filter(
            explode("\n", $section),
            static fn (string $line): bool => str_starts_with(trim($line), '- [')
        ));

        $this->assertNotEmpty($checklistLines, 'Module 22 must declare at least one checklist item.');

        foreach ($checklistLines as $line) {
            $this->assertStringStartsWith(
                '- [x]',
                trim($line),
                "Every Module 22 checklist item must be marked complete: '{$line}'."
            );
        }
    }

    public function testModule22ChecklistHasExactlyThreeItems(): void
    {
        $section = $this->extractModule22Section();

        $itemCount = preg_match_all('/^- \[x\] /m', $section);

        $this->assertSame(3, $itemCount);
    }

    public function testModule22DescribesTheCognitiveTwinReorientationStep(): void
    {
        $this->assertStringContainsString(
            'Re-orient AI Cognitive Twin memory using the 3-step Mechanical Boot Sequence and DSOM Spatial Memory.',
            $this->taskContent
        );
    }

    public function testModule22DescribesTheComposerJsonEscapingFix(): void
    {
        $this->assertStringContainsString(
            'Resolve Windows PowerShell escaping syntax errors in `composer.json` '
            . '(`check-strict` and `lab-check` inline PHP scripts).',
            $this->taskContent
        );
    }

    public function testModule22RecordsTheLabCheckComplianceResult(): void
    {
        $this->assertStringContainsString(
            'Run `composer lab-check` compliance suite — **100% PASS** (158 tests, 2333 assertions).',
            $this->taskContent
        );
    }

    public function testModule22DoesNotContainAnyUncheckedChecklistItems(): void
    {
        $section = $this->extractModule22Section();

        $this->assertSame(
            0,
            preg_match_all('/^- \[ \] /m', $section),
            'Module 22 must not contain unchecked ("- [ ] ") checklist items.'
        );
    }

    public function testFooterTimestampReflectsTheLatestSession(): void
    {
        $this->assertStringContainsString(self::FOOTER_TIMESTAMP_LINE, $this->taskContent);
    }

    public function testFooterTimestampAppearsAfterModule22(): void
    {
        $module22Pos = strpos($this->taskContent, self::MODULE_22_HEADING);
        $footerPos = strpos($this->taskContent, self::FOOTER_TIMESTAMP_LINE);

        $this->assertNotFalse($module22Pos);
        $this->assertNotFalse($footerPos);
        $this->assertGreaterThan(
            $module22Pos,
            $footerPos,
            'The DSOM footer signature must remain the final block of the document, after the Module 22 entry.'
        );
    }

    /**
     * Extracts the Module 22 section body: from its heading up to (but not
     * including) either the next "## " heading or the closing "---" footer
     * separator, whichever comes first.
     */
    private function extractModule22Section(): string
    {
        $moduleStart = strpos($this->taskContent, self::MODULE_22_HEADING);
        $this->assertNotFalse($moduleStart, 'Module 22 heading must be present before asserting on its checklist body.');

        $nextHeadingPos = strpos($this->taskContent, "\n## ", $moduleStart + 1);
        $footerSeparatorPos = strpos($this->taskContent, "\n---", $moduleStart + 1);

        $candidates = array_filter([$nextHeadingPos, $footerSeparatorPos], static fn ($pos): bool => $pos !== false);
        $sectionEnd = $candidates === [] ? false : min($candidates);

        return $sectionEnd === false
            ? substr($this->taskContent, $moduleStart)
            : substr($this->taskContent, $moduleStart, $sectionEnd - $moduleStart);
    }
}
