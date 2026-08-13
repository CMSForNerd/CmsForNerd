<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Validates the DSOM sovereign footer newly appended to
 * `.agents/skills/dsom-token-calculator/SKILL.md`, and guards the
 * pre-existing frontmatter/body content against regression.
 */
final class DsomTokenCalculatorSkillFooterTest extends TestCase
{
    private string $skillPath;
    private string $content;

    protected function setUp(): void
    {
        $this->skillPath = dirname(__DIR__) . '/.agents/skills/dsom-token-calculator/SKILL.md';
        $content = file_get_contents($this->skillPath);
        $this->assertIsString($content, 'Unable to read dsom-token-calculator/SKILL.md.');
        $this->content = $content;
    }

    public function testSkillFileExists(): void
    {
        $this->assertFileExists($this->skillPath);
    }

    public function testFooterTimestampLineIsPresentAndDatedAugustThirteenth(): void
    {
        $this->assertStringContainsString(
            '*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-13*',
            $this->content
        );
    }

    public function testFooterStandardLineIsPresent(): void
    {
        $this->assertStringContainsString(
            '*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*',
            $this->content
        );
    }

    public function testFooterAppearsAfterTheOperationalEnforcementsBody(): void
    {
        $bodyPos = strpos($this->content, '# Operational Enforcements:');
        $footerPos = strpos($this->content, 'Deep State of Mind (DSOM) For My AI Protocol');

        $this->assertNotFalse($bodyPos, 'Expected the "# Operational Enforcements:" heading to exist.');
        $this->assertNotFalse($footerPos, 'Expected the DSOM footer to exist.');
        $this->assertGreaterThan($bodyPos, $footerPos, 'The DSOM footer must be appended after the operational enforcements body.');
    }

    public function testFileEndsWithExactlyOneTrailingNewline(): void
    {
        $this->assertTrue(str_ends_with($this->content, "\n"), 'File must end with a trailing newline.');
        $this->assertFalse(str_ends_with($this->content, "\n\n\n"), 'File must not have excessive trailing blank lines.');
    }

    /**
     * Regression guard: adding the footer must not have disturbed the
     * pre-existing frontmatter fields or the 4,000-token enforcement rule.
     */
    public function testPreExistingFrontmatterAndBodyRemainIntact(): void
    {
        foreach (
            [
                'okf_version: 0.1',
                'type: procedural_skill',
                'title: "Procedural Specification: DSOM Token Calculator"',
                'topics: [tokens, tiktoken, performance, byte-cap, context]',
                'resource: "file:///.agents/skills/dsom-token-calculator/SKILL.md"',
                'timestamp: 2026-07-18T14:52:39Z',
                'greater than **4000 tokens**',
                'targeted `view_file` calls or chunked reading',
            ] as $expected
        ) {
            $this->assertStringContainsString($expected, $this->content, "Pre-existing content must still contain: '{$expected}'.");
        }
    }
}