<?php
declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Validates the diagram-design-standards agent skill files and omni-documentation sync.
 */
final class DiagramDesignStandardsSkillTest extends TestCase
{
    private string $root;
    private string $agentsSkillFile;
    private string $rootSkillFile;

    protected function setUp(): void
    {
        $this->root = dirname(__DIR__);
        $this->agentsSkillFile = $this->root . '/.agents/skills/diagram-design-standards/SKILL.md';
        $this->rootSkillFile = $this->root . '/skills/diagram-design-standards/SKILL.md';
    }

    public function testSkillFilesExistAndReferenceCoreDefinition(): void
    {
        $this->assertFileExists($this->agentsSkillFile);
        $this->assertFileExists($this->rootSkillFile);

        $rootContent = (string) file_get_contents($this->rootSkillFile);
        $this->assertStringContainsString('.agents/skills/diagram-design-standards/SKILL.md', $rootContent);
        $this->assertStringContainsString('type: skill', $rootContent);
        $this->assertStringContainsString('name: "diagram-design-standards"', $rootContent);
        $this->assertStringContainsString('## Execution Procedure', $rootContent);
    }

    public function testAgentsSkillContentAndDirectives(): void
    {
        $content = (string) file_get_contents($this->agentsSkillFile);

        $requiredDirectives = [
            'okf_version: 0.1',
            'type: skill',
            'name: "diagram-design-standards"',
            'title: "Diagram Design Standards and Visual Specifications"',
            'timestamp: 2026-08-01T09:00:00Z',
            'xmlns="http://www.w3.org/2000/svg"',
            'viewBox',
            'width="100%"',
            'height="100%"',
            '#F8FAFC',
            'rx="8"',
            '<marker>',
            '<defs>',
            'graph TD',
            'graph LR',
            'subgraph',
            '<br/>',
            'Source Component',
            'Target Component',
            'Port / Protocol / API Ingress',
            'Security Boundary / Trust Zone / Access Key',
            'Operational Significance / Flow Description',
        ];

        foreach ($requiredDirectives as $directive) {
            $this->assertStringContainsString($directive, $content);
        }

        $this->assertMatchesRegularExpression(
            '/```xml\s*[\s\S]*?<svg[\s\S]*?<\/svg>\s*```\s*```mermaid\s*[\s\S]*?```/',
            $content,
            'Skill content must contain an xml fenced SVG block with an opening <svg tag followed immediately by a mermaid fenced block.'
        );
    }

    public function testHumanDocumentationFilesExist(): void
    {
        $this->assertFileExists($this->root . '/docs/explanation/diagram-design-standards-skill.md');
        $this->assertFileExists($this->root . '/docs/skills/DIAGRAM-DESIGN-STANDARDS-SKILL.md');
    }

    public function testOmniDocumentationRegistrations(): void
    {
        $registrations = [
            '/SUMMARY.md' => ['docs/explanation/diagram-design-standards-skill.md', 'docs/skills/DIAGRAM-DESIGN-STANDARDS-SKILL.md'],
            '/mkdocs.yml' => ['docs/explanation/diagram-design-standards-skill.md', 'docs/skills/DIAGRAM-DESIGN-STANDARDS-SKILL.md'],
            '/START-HERE.md' => ['docs/AI-AGENT-SKILLS-GUIDE.md'],
            '/llms.txt' => ['docs/explanation/diagram-design-standards-skill.md', 'docs/skills/DIAGRAM-DESIGN-STANDARDS-SKILL.md'],
            '/AGENTS.md' => ['diagram-design-standards'],
            '/.agents/AGENTS.md' => ['diagram-design-standards'],
        ];

        foreach ($registrations as $file => $tokens) {
            $text = (string) file_get_contents($this->root . $file);
            foreach ($tokens as $token) {
                $this->assertStringContainsString($token, $text);
            }
        }
    }
}
