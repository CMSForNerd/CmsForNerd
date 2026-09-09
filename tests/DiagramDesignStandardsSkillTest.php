<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Validates the diagram-design-standards agent skill under
 * `.agents/skills/diagram-design-standards/SKILL.md` and
 * `skills/diagram-design-standards/SKILL.md`.
 */
final class DiagramDesignStandardsSkillTest extends TestCase
{
    private string $root;
    private string $skillPath;

    protected function setUp(): void
    {
        $this->root = dirname(__DIR__);
        $this->skillPath = $this->root . '/.agents/skills/diagram-design-standards/SKILL.md';
    }

    public function testSkillFilesExistInBothSkillDirectories(): void
    {
        $this->assertFileExists($this->skillPath, 'Missing .agents/skills/diagram-design-standards/SKILL.md.');
        $this->assertFileExists(
            $this->root . '/skills/diagram-design-standards/SKILL.md',
            'Missing skills/diagram-design-standards/SKILL.md.'
        );
    }

    public function testRootSkillFileReferencesAgentsSkillFile(): void
    {
        $rootSkillContent = file_get_contents($this->root . '/skills/diagram-design-standards/SKILL.md');
        $this->assertIsString($rootSkillContent);

        $this->assertStringContainsString(
            '.agents/skills/diagram-design-standards/SKILL.md',
            $rootSkillContent,
            'skills/diagram-design-standards/SKILL.md must reference .agents/skills/diagram-design-standards/SKILL.md.'
        );
    }

    public function testSkillFileStructureAndParsedFrontmatter(): void
    {
        $content = file_get_contents($this->skillPath);
        $this->assertIsString($content);

        preg_match('/^---\s*\n(.*?)\n---/s', $content, $matches);
        $this->assertNotEmpty($matches[1] ?? '', 'Skill file must contain a valid leading frontmatter block.');

        $frontmatterText = $matches[1];
        $this->assertStringContainsString('okf_version: 0.1', $frontmatterText);
        $this->assertStringContainsString('type: skill', $frontmatterText);
        $this->assertStringContainsString('name: "diagram-design-standards"', $frontmatterText);
        $this->assertStringContainsString('title: "Diagram Design Standards and Visual Specifications"', $frontmatterText);
        $this->assertStringContainsString('timestamp: 2026-08-01T09:00:00Z', $frontmatterText);

        $this->assertStringContainsString('## Purpose', $content);
        $this->assertStringContainsString('## When to use this skill', $content);
        $this->assertStringContainsString('## Guidelines & Best Practices', $content);
        $this->assertStringContainsString('Deep State of Mind (DSOM) For My AI Protocol', $content);
    }

    public function testSkillDocumentsSvgConstraints(): void
    {
        $content = file_get_contents($this->skillPath);
        $this->assertIsString($content);

        foreach (
            [
                'xmlns="http://www.w3.org/2000/svg"',
                'viewBox',
                'width="100%"',
                'height="100%"',
                '#F8FAFC',
                'rx="8"',
                '<marker>',
                '<defs>',
            ] as $expectedSvgRequirement
        ) {
            $this->assertStringContainsString(
                $expectedSvgRequirement,
                $content,
                "Skill must mandate SVG requirement: '{$expectedSvgRequirement}'."
            );
        }
    }

    public function testSkillDocumentsMermaidConstraints(): void
    {
        $content = file_get_contents($this->skillPath);
        $this->assertIsString($content);

        foreach (
            [
                'graph TD',
                'graph LR',
                'subgraph',
                '<br/>',
            ] as $expectedMermaidRequirement
        ) {
            $this->assertStringContainsString(
                $expectedMermaidRequirement,
                $content,
                "Skill must mandate Mermaid requirement: '{$expectedMermaidRequirement}'."
            );
        }
    }

    public function testSkillDocumentsSummaryRoutingTableColumns(): void
    {
        $content = file_get_contents($this->skillPath);
        $this->assertIsString($content);

        foreach (
            [
                'Source Component',
                'Target Component',
                'Port / Protocol / API Ingress',
                'Security Boundary / Trust Zone / Access Key',
                'Operational Significance / Flow Description',
            ] as $expectedColumn
        ) {
            $this->assertStringContainsString(
                $expectedColumn,
                $content,
                "Skill must mandate Summary Table column: '{$expectedColumn}'."
            );
        }
    }

    public function testHumanDocumentationFilesExist(): void
    {
        $this->assertFileExists($this->root . '/docs/explanation/diagram-design-standards-skill.md');
        $this->assertFileExists($this->root . '/docs/skills/DIAGRAM-DESIGN-STANDARDS-SKILL.md');
    }

    public function testOmniDocumentationRegistrations(): void
    {
        $summaryContent = file_get_contents($this->root . '/SUMMARY.md');
        $mkdocsContent = file_get_contents($this->root . '/mkdocs.yml');
        $startHereContent = file_get_contents($this->root . '/START-HERE.md');
        $llmsContent = file_get_contents($this->root . '/llms.txt');
        $agentsRootContent = file_get_contents($this->root . '/AGENTS.md');
        $agentsSubContent = file_get_contents($this->root . '/.agents/AGENTS.md');

        $this->assertIsString($summaryContent);
        $this->assertIsString($mkdocsContent);
        $this->assertIsString($startHereContent);
        $this->assertIsString($llmsContent);
        $this->assertIsString($agentsRootContent);
        $this->assertIsString($agentsSubContent);

        $this->assertStringContainsString('docs/explanation/diagram-design-standards-skill.md', $summaryContent);
        $this->assertStringContainsString('docs/skills/DIAGRAM-DESIGN-STANDARDS-SKILL.md', $summaryContent);

        $this->assertStringContainsString('docs/explanation/diagram-design-standards-skill.md', $mkdocsContent);
        $this->assertStringContainsString('docs/skills/DIAGRAM-DESIGN-STANDARDS-SKILL.md', $mkdocsContent);

        $this->assertStringContainsString('docs/AI-AGENT-SKILLS-GUIDE.md', $startHereContent);

        $this->assertStringContainsString('docs/explanation/diagram-design-standards-skill.md', $llmsContent);
        $this->assertStringContainsString('docs/skills/DIAGRAM-DESIGN-STANDARDS-SKILL.md', $llmsContent);

        $this->assertStringContainsString('diagram-design-standards', $agentsRootContent);
        $this->assertStringContainsString('diagram-design-standards', $agentsSubContent);
    }
}
