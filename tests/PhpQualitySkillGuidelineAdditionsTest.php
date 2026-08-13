<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Validates guidelines §6-§9 newly appended to
 * `.agents/skills/php-quality-sonar-phpstan/SKILL.md`:
 *
 *  6. SonarCloud Code Duplication & Configuration Exclusions
 *  7. Unused Variables in Theme Configurations
 *  8. Return Assignment Prevention
 *  9. Strict Type Docblock Formatting
 *
 * These directives were added on top of the pre-existing five guidelines
 * (§1-§5), which remain covered by AgentSkillsTest::
 * testPhpQualitySkillDocumentsStaticAnalysisAndTestingStandards(). This
 * test file exclusively guards the newly introduced content.
 */
final class PhpQualitySkillGuidelineAdditionsTest extends TestCase
{
    private string $skillPath;
    private string $content;

    protected function setUp(): void
    {
        $this->skillPath = dirname(__DIR__) . '/.agents/skills/php-quality-sonar-phpstan/SKILL.md';
        $content = file_get_contents($this->skillPath);
        $this->assertIsString($content, 'Unable to read php-quality-sonar-phpstan/SKILL.md.');
        $this->content = $content;
    }

    public function testSkillFileExists(): void
    {
        $this->assertFileExists($this->skillPath);
    }

    public function testSectionSixHeadingIsPresent(): void
    {
        $this->assertStringContainsString(
            '### 6. SonarCloud Code Duplication & Configuration Exclusions',
            $this->content
        );
    }

    public function testSectionSixDocumentsTheGuardedTestFilesAndConfigPaths(): void
    {
        foreach (
            [
                'SonarConfigurationTest.php',
                'CiWorkflowVersionPinTest.php',
                'sonar-project.properties',
                '.github/workflows/build.yml',
                'rephrase sentences and restructure layouts',
                'custom CSS grids',
            ] as $expected
        ) {
            $this->assertStringContainsString($expected, $this->content, "§6 must document: '{$expected}'.");
        }
    }

    public function testSectionSevenHeadingIsPresent(): void
    {
        $this->assertStringContainsString(
            '### 7. Unused Variables in Theme Configurations',
            $this->content
        );
    }

    public function testSectionSevenDocumentsThemeVariableGuardPattern(): void
    {
        foreach (
            [
                '$THEME_VERSION',
                '$THEME_AUTHOR',
                'themes/CmsForNerd/theme.php',
                'if (empty($THEME_VERSION)...)',
                'Registry keys',
            ] as $expected
        ) {
            $this->assertStringContainsString($expected, $this->content, "§7 must document: '{$expected}'.");
        }
    }

    public function testSectionEightHeadingIsPresent(): void
    {
        $this->assertStringContainsString(
            '### 8. Return Assignment Prevention',
            $this->content
        );
    }

    public function testSectionEightProhibitsInlineReturnAssignments(): void
    {
        foreach (
            [
                'return $var = val;',
                'Do not embed variable or property assignments directly within return statements',
                'Perform the assignment on a separate line first',
            ] as $expected
        ) {
            $this->assertStringContainsString($expected, $this->content, "§8 must document: '{$expected}'.");
        }
    }

    public function testSectionNineHeadingIsPresent(): void
    {
        $this->assertStringContainsString(
            '### 9. Strict Type Docblock Formatting',
            $this->content
        );
    }

    public function testSectionNineDocumentsDocblockOrderingRelativeToStrictTypes(): void
    {
        foreach (
            [
                'PSR-12',
                'phpcs violations',
                'declaring strict types',
                'follow the opening `<?php` tag immediately',
                'precede the `declare(strict_types=1);` statement',
            ] as $expected
        ) {
            $this->assertStringContainsString($expected, $this->content, "§9 must document: '{$expected}'.");
        }
    }

    /**
     * Regression guard: sections must be documented in ascending numeric
     * order (§1 through §9), with no gaps or duplicate numbering.
     */
    public function testAllNineGuidelineSectionsAppearInAscendingOrder(): void
    {
        $matched = preg_match_all('/^### (\d+)\./m', $this->content, $matches);
        $this->assertGreaterThan(0, $matched, 'Expected to find numbered "### N." guideline section headings.');

        $numbers = array_map('intval', $matches[1]);

        $this->assertSame(range(1, 9), $numbers, 'Guideline sections must be a contiguous 1..9 sequence with no gaps or duplicates.');
    }

    /**
     * Boundary case: the newly added guidelines must sit ahead of the
     * DSOM sovereign footer, not accidentally appended after it.
     */
    public function testNewGuidelinesAppearBeforeTheSovereignFooter(): void
    {
        $sectionSixPos = strpos($this->content, '### 6. SonarCloud Code Duplication & Configuration Exclusions');
        $sectionNinePos = strpos($this->content, '### 9. Strict Type Docblock Formatting');
        $footerPos = strpos(
            $this->content,
            '*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-05*'
        );

        $this->assertNotFalse($sectionSixPos);
        $this->assertNotFalse($sectionNinePos);
        $this->assertNotFalse($footerPos);
        $this->assertGreaterThan($sectionSixPos, $sectionNinePos);
        $this->assertGreaterThan($sectionNinePos, $footerPos, 'The DSOM footer must remain the final block after all nine guidelines.');
    }
}