<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Validates the three new Agent Skill manuals introduced under
 * `.agents/skills/*\/SKILL.md`:
 *
 *  - asimp-and-ai-integration
 *  - python-utility-and-security
 *  - telemetry-and-feedback-ops
 *
 * Each SKILL.md is an OKF v0.1 "operational manual" consumed by AI agents:
 * a YAML frontmatter block (okf_version/type/title/name/description/topics/
 * timestamp) followed by structured Markdown guidance and a standard DSOM
 * footer dated 2026-08-01. These tests guard the structural contract of the
 * frontmatter, the required Markdown sections, the DSOM footer, the MD040
 * "fenced code blocks must declare a language" rule, and the specific
 * technical directives each manual is required to document.
 */
final class NewAgentSkillsTest extends TestCase
{
    private const FOOTER_TIMESTAMP_LINE
        = '*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-01*';

    private const FOOTER_STANDARD_LINE
        = '*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*';

    /** @var array<int, string> */
    private const SKILL_NAMES = [
        'asimp-and-ai-integration',
        'python-utility-and-security',
        'telemetry-and-feedback-ops',
    ];

    private string $skillsRoot;

    protected function setUp(): void
    {
        $this->skillsRoot = dirname(__DIR__) . '/.agents/skills';
    }

    private function skillPath(string $name): string
    {
        return $this->skillsRoot . '/' . $name . '/SKILL.md';
    }

    private function skillContent(string $name): string
    {
        $content = file_get_contents($this->skillPath($name));
        $this->assertIsString($content, "Unable to read SKILL.md for '{$name}'.");

        return $content;
    }

    // ---------------------------------------------------------------
    // Baseline structure shared by all three new skill manuals
    // ---------------------------------------------------------------

    public function testEverySkillFileExists(): void
    {
        foreach (self::SKILL_NAMES as $name) {
            $this->assertFileExists($this->skillPath($name), "Missing SKILL.md for skill '{$name}'.");
        }
    }

    public function testEverySkillFileHasAWellFormedYamlFrontmatterBlock(): void
    {
        foreach (self::SKILL_NAMES as $name) {
            $lines = explode("\n", $this->skillContent($name));

            $this->assertSame(
                '---',
                trim($lines[0] ?? ''),
                "Skill '{$name}' must open with a '---' frontmatter delimiter on line 1."
            );

            $closingIndex = null;
            for ($i = 1; $i < count($lines); $i++) {
                if (trim($lines[$i]) === '---') {
                    $closingIndex = $i;
                    break;
                }
            }

            $this->assertNotNull($closingIndex, "Skill '{$name}' frontmatter block must be closed with a second '---' line.");
            $this->assertGreaterThan(1, $closingIndex, "Skill '{$name}' frontmatter block must not be empty.");
        }
    }

    public function testEverySkillFileDeclaresOkfVersion01(): void
    {
        foreach (self::SKILL_NAMES as $name) {
            $this->assertStringContainsString(
                'okf_version: 0.1',
                $this->skillContent($name),
                "Skill '{$name}' must declare okf_version: 0.1."
            );
        }
    }

    public function testEverySkillFileDeclaresSkillType(): void
    {
        foreach (self::SKILL_NAMES as $name) {
            $this->assertStringContainsString(
                'type: skill',
                $this->skillContent($name),
                "Skill '{$name}' must declare type: skill."
            );
        }
    }

    public function testEverySkillFileNameFieldMatchesItsDirectoryName(): void
    {
        foreach (self::SKILL_NAMES as $name) {
            $this->assertStringContainsString(
                "name: \"{$name}\"",
                $this->skillContent($name),
                "Skill '{$name}' frontmatter 'name' field must exactly match its directory name."
            );
        }
    }

    public function testEverySkillFileHasANonEmptyQuotedTitle(): void
    {
        foreach (self::SKILL_NAMES as $name) {
            $content = $this->skillContent($name);
            $matched = preg_match('/^title:\s*"(.+)"\s*$/m', $content, $matches);

            $this->assertSame(1, $matched, "Skill '{$name}' must declare a quoted 'title' field.");
            $this->assertNotSame('', trim($matches[1] ?? ''), "Skill '{$name}' title must not be empty.");
        }
    }

    public function testEverySkillFileHasANonEmptyQuotedDescription(): void
    {
        foreach (self::SKILL_NAMES as $name) {
            $content = $this->skillContent($name);
            $matched = preg_match('/^description:\s*"(.+)"\s*$/m', $content, $matches);

            $this->assertSame(1, $matched, "Skill '{$name}' must declare a quoted 'description' field.");
            $this->assertNotSame('', trim($matches[1] ?? ''), "Skill '{$name}' description must not be empty.");
        }
    }

    public function testEverySkillFileDeclaresTopicsAsABracketedList(): void
    {
        foreach (self::SKILL_NAMES as $name) {
            $this->assertSame(
                1,
                preg_match('/^topics:\s*\[.+\]\s*$/m', $this->skillContent($name)),
                "Skill '{$name}' must declare 'topics' as a bracketed list."
            );
        }
    }

    public function testEverySkillFileDeclaresTheExpectedIso8601Timestamp(): void
    {
        foreach (self::SKILL_NAMES as $name) {
            $this->assertStringContainsString(
                'timestamp: 2026-08-01T09:00:00Z',
                $this->skillContent($name),
                "Skill '{$name}' must declare the timestamp: 2026-08-01T09:00:00Z."
            );
        }
    }

    public function testEverySkillFileHasAPurposeSection(): void
    {
        foreach (self::SKILL_NAMES as $name) {
            $this->assertStringContainsString(
                '## Purpose',
                $this->skillContent($name),
                "Skill '{$name}' must document a '## Purpose' section."
            );
        }
    }

    public function testEverySkillFileHasAWhenToUseThisSkillSection(): void
    {
        foreach (self::SKILL_NAMES as $name) {
            $this->assertStringContainsString(
                '## When to use this skill',
                $this->skillContent($name),
                "Skill '{$name}' must document a '## When to use this skill' section."
            );
        }
    }

    public function testEverySkillFileHasAGuidelinesAndBestPracticesSection(): void
    {
        foreach (self::SKILL_NAMES as $name) {
            $this->assertStringContainsString(
                '## Guidelines & Best Practices',
                $this->skillContent($name),
                "Skill '{$name}' must document a '## Guidelines & Best Practices' section."
            );
        }
    }

    public function testEverySkillFileHasTheDsomFooterTimestampLine(): void
    {
        foreach (self::SKILL_NAMES as $name) {
            $this->assertStringContainsString(
                self::FOOTER_TIMESTAMP_LINE,
                $this->skillContent($name),
                "Skill '{$name}' must carry the DSOM footer timestamp line dated 2026-08-01."
            );
        }
    }

    public function testEverySkillFileHasTheDsomFooterStandardLine(): void
    {
        foreach (self::SKILL_NAMES as $name) {
            $this->assertStringContainsString(
                self::FOOTER_STANDARD_LINE,
                $this->skillContent($name),
                "Skill '{$name}' must carry the DSOM footer standard line."
            );
        }
    }

    public function testEveryFencedCodeBlockDeclaresAnExplicitLanguage(): void
    {
        foreach (self::SKILL_NAMES as $name) {
            $lines = explode("\n", $this->skillContent($name));
            $inBlock = false;

            foreach ($lines as $index => $line) {
                $trimmed = trim($line);

                if (!str_starts_with($trimmed, '```')) {
                    continue;
                }

                if (!$inBlock) {
                    $language = substr($trimmed, 3);
                    $this->assertNotSame(
                        '',
                        $language,
                        "Skill '{$name}' opens a fenced code block without a language at line " . ($index + 1) .
                        ' (violates MD040 / php-quality-sonar-phpstan §5).'
                    );
                    $inBlock = true;
                } else {
                    $inBlock = false;
                }
            }

            $this->assertFalse($inBlock, "Skill '{$name}' has an unterminated fenced code block.");
        }
    }

    /**
     * Regression guard against copy-paste errors: since all three manuals
     * share an identical frontmatter template, it's easy to accidentally
     * duplicate a name/title/description across files. Each must be unique.
     */
    public function testEverySkillDeclaresAUniqueNameTitleAndDescription(): void
    {
        $names = [];
        $titles = [];
        $descriptions = [];

        foreach (self::SKILL_NAMES as $skill) {
            $content = $this->skillContent($skill);

            preg_match('/^name:\s*"(.+)"\s*$/m', $content, $nameMatch);
            preg_match('/^title:\s*"(.+)"\s*$/m', $content, $titleMatch);
            preg_match('/^description:\s*"(.+)"\s*$/m', $content, $descMatch);

            $names[] = $nameMatch[1] ?? null;
            $titles[] = $titleMatch[1] ?? null;
            $descriptions[] = $descMatch[1] ?? null;
        }

        $this->assertCount(count(self::SKILL_NAMES), array_unique($names), 'Every skill must declare a unique name.');
        $this->assertCount(count(self::SKILL_NAMES), array_unique($titles), 'Every skill must declare a unique title.');
        $this->assertCount(
            count(self::SKILL_NAMES),
            array_unique($descriptions),
            'Every skill must declare a unique description.'
        );
    }

    public function testAllSkillTopicsListsContainTheExpectedKeywords(): void
    {
        $expectedTopicsBySkill = [
            'asimp-and-ai-integration' => ['asimp', 'compliance', 'open-source', 'openscap', 'testing'],
            'python-utility-and-security' => ['python', 'security', 'path-traversal', 'redos', 'docstring'],
            'telemetry-and-feedback-ops' => ['telemetry', 'feedback', 'testing', 'ansible', 'wsl2'],
        ];

        foreach ($expectedTopicsBySkill as $name => $topics) {
            $expectedLine = 'topics: [' . implode(', ', $topics) . ']';

            $this->assertStringContainsString(
                $expectedLine,
                $this->skillContent($name),
                "Skill '{$name}' must declare {$expectedLine}."
            );
        }
    }

    // ---------------------------------------------------------------
    // Skill-specific technical directives
    // ---------------------------------------------------------------

    public function testAsimpSkillDocumentsOmniDocumentationAndSandboxMockRequirements(): void
    {
        $content = $this->skillContent('asimp-and-ai-integration');

        foreach (
            [
                '`START-HERE.md` (Entry Point 16)',
                '`SUMMARY.md`',
                '`mkdocs.yml`',
                '`llms.txt`',
                'asimp-ai-agents.php',
                'contents/asimp-ai-agents-body.inc',
                'docs/governance/ASIMP-FOR-AI-AGENTS.md',
                '.agents/brain/',
                'the `asimp/` directory',
                'Lynis and OpenSCAP',
                'tools/mock-asimp.sh',
                'data/asimp_mock/',
                'tests/AnsiblePlaybookTest.php',
                'tests/PodmanComposeYamlTest.php',
                'tests/MarkdownOkfComplianceTest.php',
            ] as $expected
        ) {
            $this->assertStringContainsString($expected, $content, "asimp-and-ai-integration must document: '{$expected}'.");
        }
    }

    public function testPythonUtilitySkillDocumentsSecurityAndDocstringRequirements(): void
    {
        $content = $this->skillContent('python-utility-and-security');

        foreach (
            [
                'CWE-22 / S2083',
                'os.path.abspath()',
                'is_safe_path()',
                'ReDoS',
                'nested quantifiers or open-ended backtracking',
                '"http://"',
                "'http'` + `'://'",
                'Place all Python imports at the top of the file',
                '__pycache__/',
                '*.pyc`, `*.pyo`, `*.pyd`',
                'tests/test_validate_inventory.py',
                'python3 -m unittest',
                'Google-style docstrings',
            ] as $expected
        ) {
            $this->assertStringContainsString($expected, $content, "python-utility-and-security must document: '{$expected}'.");
        }
    }

    public function testTelemetrySkillDocumentsExecutionModesAndMatrixTesting(): void
    {
        $content = $this->skillContent('telemetry-and-feedback-ops');

        foreach (
            [
                'docs/governance/SOP-TELEMETRY-FEEDBACK-PIPELINE.md',
                '`START-HERE.md` (Entry Point 15)',
                '`docs/SUMMARY.md`',
                '`mkdocs.yml`',
                '`llms.txt`',
                'execution_mode` configuration flag',
                'feedback_collector` role',
                '/tmp/jules_telemetry.json',
                'scripts/jules_gh_feedback.sh',
                'jules feed',
                'gh pr comment',
                'playbooks/matrix_test.yml',
                'WSL2 host running Podman 5+',
                'Ubuntu 24.04, Ubuntu 26.04, AlmaLinux 9, and Debian 12',
            ] as $expected
        ) {
            $this->assertStringContainsString($expected, $content, "telemetry-and-feedback-ops must document: '{$expected}'.");
        }
    }

    // ---------------------------------------------------------------
    // Negative / boundary case
    // ---------------------------------------------------------------

    /**
     * Boundary/negative check: none of these operational manuals should
     * contain unresolved placeholder markers left over from templating
     * (e.g. TODO/TBD/FIXME or literal "{{ }}" mustache placeholders), which
     * would indicate an incomplete skill definition slipping into the repo.
     */
    public function testNoSkillFileContainsUnresolvedPlaceholderMarkers(): void
    {
        foreach (self::SKILL_NAMES as $name) {
            $content = $this->skillContent($name);

            foreach (['TODO', 'TBD', 'FIXME', '{{', '}}'] as $placeholder) {
                $this->assertStringNotContainsString(
                    $placeholder,
                    $content,
                    "Skill '{$name}' must not contain an unresolved placeholder marker '{$placeholder}'."
                );
            }
        }
    }
}