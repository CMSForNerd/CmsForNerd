<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Validates the web-design-guidelines agent skill under
 * `.agents/skills/web-design-guidelines/SKILL.md`.
 */
final class WebDesignGuidelinesSkillTest extends TestCase
{
    private string $skillPath;

    protected function setUp(): void
    {
        $this->skillPath = dirname(__DIR__) . '/.agents/skills/web-design-guidelines/SKILL.md';
    }

    public function testSkillFileExists(): void
    {
        $this->assertFileExists($this->skillPath, 'Missing web-design-guidelines SKILL.md.');
    }

    public function testSkillFileStructureAndFrontmatter(): void
    {
        $content = file_get_contents($this->skillPath);
        $this->assertIsString($content);

        $this->assertStringContainsString('okf_version: 0.1', $content);
        $this->assertStringContainsString('type: skill', $content);
        $this->assertStringContainsString('name: "web-design-guidelines"', $content);
        $this->assertStringContainsString('## Purpose', $content);
        $this->assertStringContainsString('## When to use this skill', $content);
        $this->assertStringContainsString('## Guidelines Source & Rule Enforcement', $content);
        $this->assertStringContainsString('Deep State of Mind (DSOM) For My AI Protocol', $content);
    }

    public function testHumanDocumentationFilesExist(): void
    {
        $this->assertFileExists(dirname(__DIR__) . '/docs/WEB-DESIGN-GUIDELINES.md');
        $this->assertFileExists(dirname(__DIR__) . '/docs/skills/WEB-DESIGN-GUIDELINES-SKILL.md');
    }
}
