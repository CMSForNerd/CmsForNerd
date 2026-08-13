<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Validates the three new rows appended to the "DSOM Agent Skills Registry"
 * ledger table in `docs/AI-AGENT-SKILLS-GUIDE.md`:
 *
 *  - ASIMP and AI Agents Integration
 *  - Python Utility & Security
 *  - Telemetry & Feedback Operations
 */
final class AiAgentSkillsGuideRegistryTest extends TestCase
{
    private string $guidePath;
    private string $content;

    /** @var array<string, array{path: string, purpose: string}> */
    private const EXPECTED_ROWS = [
        'ASIMP and AI Agents Integration' => [
            'path' => '.agents/skills/asimp-and-ai-integration/SKILL.md',
            'purpose' => 'Integrates OS security compliance audits and YAML structures validation.',
        ],
        'Python Utility & Security' => [
            'path' => '.agents/skills/python-utility-and-security/SKILL.md',
            'purpose' => 'Restricts path traversal, prevents ReDoS, and validates Python structures.',
        ],
        'Telemetry & Feedback Operations' => [
            'path' => '.agents/skills/telemetry-and-feedback-ops/SKILL.md',
            'purpose' => 'Manages dev-mode telemetry log compiling and formatted Markdown feedback.',
        ],
    ];

    protected function setUp(): void
    {
        $this->guidePath = dirname(__DIR__) . '/docs/AI-AGENT-SKILLS-GUIDE.md';
        $content = file_get_contents($this->guidePath);
        $this->assertIsString($content, 'Unable to read docs/AI-AGENT-SKILLS-GUIDE.md.');
        $this->content = $content;
    }

    public function testGuideFileExists(): void
    {
        $this->assertFileExists($this->guidePath);
    }

    public function testRegistryTableHeaderIsPresent(): void
    {
        $this->assertStringContainsString('| Skill Name | Path | Purpose |', $this->content);
        $this->assertStringContainsString('| :--- | :--- | :--- |', $this->content);
    }

    public function testEachNewRowIsRegisteredWithItsExactPathAndPurpose(): void
    {
        foreach (self::EXPECTED_ROWS as $skillName => $row) {
            $expectedLine = sprintf(
                '| **%s** | `%s` | %s |',
                $skillName,
                $row['path'],
                $row['purpose']
            );

            $this->assertStringContainsString(
                $expectedLine,
                $this->content,
                "Registry table must contain the exact row: {$expectedLine}"
            );
        }
    }

    public function testEachNewRowReferencesASkillFileThatActuallyExistsOnDisk(): void
    {
        $root = dirname(__DIR__);

        foreach (self::EXPECTED_ROWS as $skillName => $row) {
            $this->assertFileExists(
                $root . '/' . $row['path'],
                "Registry row for '{$skillName}' must reference an existing SKILL.md file."
            );
        }
    }

    /**
     * The registry ledger is maintained in alphabetical order by skill
     * name. Verify the three new rows are slotted into their correct
     * alphabetical positions relative to their pre-existing neighbours.
     */
    public function testNewRowsAreInsertedInAlphabeticalOrderRelativeToNeighbours(): void
    {
        $crossPlatformPos = strpos($this->content, '**Cross-Platform Translator**');
        $asimpPos = strpos($this->content, '**ASIMP and AI Agents Integration**');

        $this->assertNotFalse($crossPlatformPos);
        $this->assertNotFalse($asimpPos);
        $this->assertLessThan(
            $crossPlatformPos,
            $asimpPos,
            "'ASIMP and AI Agents Integration' (A) must be listed before 'Cross-Platform Translator' (C)."
        );

        $proposalDocxPos = strpos($this->content, '**Proposal DOCX Formatter**');
        $pythonUtilityPos = strpos($this->content, '**Python Utility & Security**');
        $sodPalaceSyncPos = strpos($this->content, '**SOD Palace Sync**');

        $this->assertNotFalse($proposalDocxPos);
        $this->assertNotFalse($pythonUtilityPos);
        $this->assertNotFalse($sodPalaceSyncPos);
        $this->assertGreaterThan(
            $proposalDocxPos,
            $pythonUtilityPos,
            "'Python Utility & Security' (P) must be listed after 'Proposal DOCX Formatter' (P...roposal)."
        );
        $this->assertLessThan(
            $sodPalaceSyncPos,
            $pythonUtilityPos,
            "'Python Utility & Security' (P) must be listed before 'SOD Palace Sync' (S)."
        );

        $sshPasswordlessPos = strpos($this->content, '**SSH Passwordless Setup**');
        $telemetryPos = strpos($this->content, '**Telemetry & Feedback Operations**');

        $this->assertNotFalse($sshPasswordlessPos);
        $this->assertNotFalse($telemetryPos);
        $this->assertGreaterThan(
            $sshPasswordlessPos,
            $telemetryPos,
            "'Telemetry & Feedback Operations' (T) must be listed after 'SSH Passwordless Setup' (S)."
        );
    }

    /**
     * Regression guard: pre-existing rows untouched by this change must
     * remain present and untouched.
     */
    public function testPreExistingRegistryRowsRemainIntact(): void
    {
        foreach (
            [
                '| **DSOM Bootstrap** | `.agents/skills/dsom-bootstrap/SKILL.md` | Bootstraps DSOM architecture for new projects. |',
                '| **EOD Palace Sync** | `.agents/skills/eod-palace-sync/SKILL.md` | The Hibernation ritual to externalize memory into the Palace and push to Git. |',
                '| **SSH Passwordless Setup** | `.agents/skills/ssh-passwordless-setup/SKILL.md` | Configures passwordless, multi-hop SSH routing using `~/.ssh/config` to bypass agent limits. |',
            ] as $expectedRow
        ) {
            $this->assertStringContainsString($expectedRow, $this->content, "Pre-existing row must remain intact: {$expectedRow}");
        }
    }

    public function testGuideRetainsItsSovereignFooter(): void
    {
        $this->assertStringContainsString('Harisfazillah Jamel', $this->content);
        $this->assertStringContainsString('GNU General Public License v3.0', $this->content);
    }

    /**
     * Boundary case: every markdown table row in the registry must follow
     * the strict 3-column pipe-delimited format (no malformed rows
     * introduced by the new additions).
     */
    public function testAllRegistryTableRowsHaveExactlyThreeColumns(): void
    {
        $tableStart = strpos($this->content, '| Skill Name | Path | Purpose |');
        $this->assertNotFalse($tableStart);

        $tableSection = substr($this->content, $tableStart);
        $lines = explode("\n", $tableSection);

        foreach ($lines as $line) {
            $trimmed = trim($line);

            if ($trimmed === '' || !str_starts_with($trimmed, '|')) {
                break;
            }

            $columnCount = count(array_filter(explode('|', $trimmed), static fn (string $part): bool => $part !== ''));
            $this->assertSame(3, $columnCount, "Malformed table row (expected 3 columns): {$trimmed}");
        }
    }
}