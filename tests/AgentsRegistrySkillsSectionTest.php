<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Validates the "Active AI Agent Skills & Custom Tooling" section entries
 * newly added to both `AGENTS.md` (root gateway) and `.agents/AGENTS.md`
 * (full rulebook), and the accompanying OKF `timestamp` bump from
 * `2026-08-01T08:30:00Z` to `2026-08-01T09:00:00Z` on both files.
 *
 * Per Rule 23 (Dual Agent Registry Mandate), both files must stay
 * synchronised whenever the skills registry changes.
 */
final class AgentsRegistrySkillsSectionTest extends TestCase
{
    private string $rootGatewayPath;
    private string $fullRulebookPath;

    /** @var array<int, string> */
    private const NEW_SKILL_BULLETS = [
        '- **Python Utility & Security (`.agents/skills/python-utility-and-security/`)**',
        '- **ASIMP and AI Agents Integration (`.agents/skills/asimp-and-ai-integration/`)**',
        '- **Telemetry and Bidirectional Feedback (`.agents/skills/telemetry-and-feedback-ops/`)**',
    ];

    protected function setUp(): void
    {
        $root = dirname(__DIR__);
        $this->rootGatewayPath = $root . '/AGENTS.md';
        $this->fullRulebookPath = $root . '/.agents/AGENTS.md';
    }

    /**
     * @return array<int, string>
     */
    private function bothAgentsFiles(): array
    {
        return [$this->rootGatewayPath, $this->fullRulebookPath];
    }

    private function read(string $path): string
    {
        $this->assertFileExists($path, "Expected '{$path}' to exist.");

        return (string) file_get_contents($path);
    }

    public function testBothAgentsMdFilesExist(): void
    {
        foreach ($this->bothAgentsFiles() as $path) {
            $this->assertFileExists($path);
        }
    }

    public function testBothAgentsMdFilesDeclareTheBumpedTimestamp(): void
    {
        foreach ($this->bothAgentsFiles() as $path) {
            $content = $this->read($path);

            $this->assertStringContainsString(
                'timestamp: 2026-08-01T09:00:00Z',
                $content,
                "'{$path}' must declare the bumped OKF timestamp 2026-08-01T09:00:00Z."
            );
        }
    }

    /**
     * Note: the stale '2026-08-01T08:30:00Z' value intentionally remains
     * elsewhere in both files as a static illustrative example within the
     * "Compliant YAML Frontmatter Example" documentation block, so this
     * assertion is scoped strictly to each file's own frontmatter block
     * (between the first pair of '---' delimiters) rather than the whole
     * file body.
     */
    public function testEachFilesOwnFrontmatterBlockDoesNotRetainTheStaleTimestamp(): void
    {
        foreach ($this->bothAgentsFiles() as $path) {
            $content = $this->read($path);
            $lines = explode("\n", $content);

            $this->assertSame('---', trim($lines[0] ?? ''), "'{$path}' must open with a '---' frontmatter delimiter.");

            $closingIndex = null;
            for ($i = 1; $i < count($lines); $i++) {
                if (trim($lines[$i]) === '---') {
                    $closingIndex = $i;
                    break;
                }
            }
            $this->assertNotNull($closingIndex, "'{$path}' frontmatter block must be closed with a second '---' line.");

            $frontmatter = implode("\n", array_slice($lines, 1, $closingIndex - 1));

            $this->assertStringContainsString('timestamp: 2026-08-01T09:00:00Z', $frontmatter);
            $this->assertStringNotContainsString(
                '2026-08-01T08:30:00Z',
                $frontmatter,
                "'{$path}' frontmatter block must not retain the stale OKF timestamp 2026-08-01T08:30:00Z."
            );
        }
    }

    public function testBothAgentsMdFilesDeclareTheActiveAiAgentSkillsHeading(): void
    {
        foreach ($this->bothAgentsFiles() as $path) {
            $this->assertStringContainsString(
                '## Active AI Agent Skills & Custom Tooling',
                $this->read($path),
                "'{$path}' must declare the '## Active AI Agent Skills & Custom Tooling' heading."
            );
        }
    }

    public function testBothAgentsMdFilesRegisterAllThreeNewSkillBullets(): void
    {
        foreach ($this->bothAgentsFiles() as $path) {
            $content = $this->read($path);

            foreach (self::NEW_SKILL_BULLETS as $bullet) {
                $this->assertStringContainsString($bullet, $content, "'{$path}' must register skill bullet: {$bullet}");
            }
        }
    }

    public function testPythonUtilitySkillBulletDescribesItsSecurityScope(): void
    {
        foreach ($this->bothAgentsFiles() as $path) {
            $content = $this->read($path);

            foreach (
                [
                    'Enforces path traversal boundaries (CWE-22) using `os.path.abspath`',
                    'blocks ReDoS via regex-free parsing',
                    'prevents insecure protocol triggers',
                    'tests Google-style docstrings',
                    'Utilize when writing or validating any repository Python scripts.',
                ] as $expected
            ) {
                $this->assertStringContainsString($expected, $content, "'{$path}' Python Utility & Security bullet must document: '{$expected}'.");
            }
        }
    }

    public function testAsimpSkillBulletDescribesItsComplianceScope(): void
    {
        foreach ($this->bothAgentsFiles() as $path) {
            $content = $this->read($path);

            foreach (
                [
                    'Directs automated OS-level security compliance audits via Lynis and OpenSCAP',
                    'simulates unprivileged mock auditing via `tools/mock-asimp.sh`',
                    'validates YAML structures',
                    'Load on-demand for infrastructure compliance checks or automated YAML tests.',
                ] as $expected
            ) {
                $this->assertStringContainsString($expected, $content, "'{$path}' ASIMP bullet must document: '{$expected}'.");
            }
        }
    }

    public function testTelemetrySkillBulletDescribesItsFeedbackScope(): void
    {
        foreach ($this->bothAgentsFiles() as $path) {
            $content = $this->read($path);

            foreach (
                [
                    'Manages the local telemetry loop in `dev` execution mode',
                    'compile and dispatch formatted Markdown feedback reports back to Google Jules VM and active pull requests',
                    'Executed dynamically in dev mode or as part of WSL2 multi-distro matrix workflows.',
                ] as $expected
            ) {
                $this->assertStringContainsString($expected, $content, "'{$path}' Telemetry bullet must document: '{$expected}'.");
            }
        }
    }

    /**
     * The three new bullets must be appended after the pre-existing
     * "End-of-Day (EOD) Palace Synchronization" bullet and in the exact
     * order: Python Utility & Security, ASIMP and AI Agents Integration,
     * Telemetry and Bidirectional Feedback.
     */
    public function testNewSkillBulletsAppearInOrderAfterTheEodPalaceSyncBullet(): void
    {
        foreach ($this->bothAgentsFiles() as $path) {
            $content = $this->read($path);

            $eodPos = strpos($content, 'End-of-Day (EOD) Palace Synchronization');
            $this->assertNotFalse($eodPos, "'{$path}' must still document the End-of-Day (EOD) Palace Synchronization bullet.");

            $previousPos = $eodPos;
            foreach (self::NEW_SKILL_BULLETS as $bullet) {
                $position = strpos($content, $bullet);
                $this->assertNotFalse($position, "'{$path}' is missing bullet: {$bullet}");
                $this->assertGreaterThan(
                    $previousPos,
                    $position,
                    "'{$path}': bullet '{$bullet}' must appear after the preceding registered skill bullet."
                );
                $previousPos = $position;
            }
        }
    }

    /**
     * Regression guard: the newly added bullets must be byte-identical
     * across both AGENTS.md files, preserving the Dual Agent Registry
     * synchronisation mandate (Rule 23).
     */
    public function testNewSkillBulletsAreIdenticalAcrossBothAgentsMdFiles(): void
    {
        $rootContent = $this->read($this->rootGatewayPath);
        $fullContent = $this->read($this->fullRulebookPath);

        foreach (self::NEW_SKILL_BULLETS as $bullet) {
            $rootPos = strpos($rootContent, $bullet);
            $fullPos = strpos($fullContent, $bullet);

            $this->assertNotFalse($rootPos);
            $this->assertNotFalse($fullPos);

            // Compare the bullet heading line plus its two immediately
            // following description/how-to-interact lines verbatim.
            $rootBlock = $this->extractThreeLineBlock($rootContent, $rootPos);
            $fullBlock = $this->extractThreeLineBlock($fullContent, $fullPos);

            $this->assertSame(
                $fullBlock,
                $rootBlock,
                "Skill bullet block for '{$bullet}' must be identical in AGENTS.md and .agents/AGENTS.md."
            );
        }
    }

    private function extractThreeLineBlock(string $content, int $startPos): string
    {
        $slice = substr($content, $startPos);
        $lines = explode("\n", $slice);

        return implode("\n", array_slice($lines, 0, 3));
    }

    public function testBothFilesStillCarryTheSovereignFooter(): void
    {
        foreach ($this->bothAgentsFiles() as $path) {
            $content = $this->read($path);

            $this->assertStringContainsString('Harisfazillah Jamel', $content);
            $this->assertStringContainsString('GNU General Public License v3.0', $content);
        }
    }
}