<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Validates the eight new Agent Skill manuals introduced under
 * `.agents/skills/*\/SKILL.md`:
 *
 *  - ansible-and-podman-ops
 *  - bot-detection-and-network-ops
 *  - cms-documentation-and-education
 *  - cms-security-and-best-practices
 *  - php-performance-and-benchmarking
 *  - php-quality-sonar-phpstan
 *  - sovereign-git-and-workflow
 *  - static-baking-and-routing
 *
 * Each SKILL.md is an OKF v0.1 "operational manual" consumed by AI agents:
 * a YAML frontmatter block (okf_version/type/title/name/description/topics/
 * timestamp) followed by structured Markdown guidance and a standard DSOM
 * footer. These tests guard the structural contract of the frontmatter, the
 * required Markdown sections, the DSOM footer, the self-imposed MD040
 * "fenced code blocks must declare a language" rule (defined by
 * php-quality-sonar-phpstan itself), and the specific technical directives
 * each manual is required to document.
 */
final class AgentSkillsTest extends TestCase
{
    private const FOOTER_TIMESTAMP_LINE
        = '*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-05*';

    private const FOOTER_STANDARD_LINE
        = '*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*';

    /** @var array<int, string> */
    private const SKILL_NAMES = [
        'ansible-and-podman-ops',
        'bot-detection-and-network-ops',
        'cms-documentation-and-education',
        'cms-security-and-best-practices',
        'php-performance-and-benchmarking',
        'php-quality-sonar-phpstan',
        'sovereign-git-and-workflow',
        'static-baking-and-routing',
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
    // Baseline structure shared by all eight new skill manuals
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

            $this->assertSame('---', trim($lines[0] ?? ''), "Skill '{$name}' must open with a '---' frontmatter delimiter on line 1.");

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
                "Skill '{$name}' must carry the DSOM footer timestamp line dated 2026-08-05."
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

    /**
     * Regression guard: php-quality-sonar-phpstan §5 mandates that "All
     * fenced code blocks in markdown files must contain explicit language
     * specifications ... to strictly comply with MD040 linting rules". This
     * asserts every one of the eight new manuals actually follows the rule
     * it (or its siblings) prescribes, and that no fence is left unclosed.
     */
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
     * Regression guard against copy-paste errors: since all eight manuals
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
            'ansible-and-podman-ops' => ['ansible', 'podman', 'rootless', 'security-hardening', 'fqcn'],
            'bot-detection-and-network-ops' => ['bot-detection', 'network', 'ssrf', 'curl', 'caching'],
            'cms-documentation-and-education' => ['documentation', 'architecture', 'composer', 'education', 'alignment'],
            'cms-security-and-best-practices' => ['security', 'hardening', 'path-traversal', 'zero-global', 'escape'],
            'php-performance-and-benchmarking' => ['php', 'performance', 'caching', 'benchmarking', 'optimization'],
            'php-quality-sonar-phpstan' => ['php', 'phpstan', 'sonarcloud', 'attributes', 'testing'],
            'sovereign-git-and-workflow' => ['git', 'merge', 'conflict-resolution', 'clean', 'commits'],
            'static-baking-and-routing' => ['static-baking', 'github-pages', 'pwa', 'router', 'nojekyll'],
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

    public function testAnsiblePodmanSkillDocumentsRootlessHardeningRequirements(): void
    {
        $content = $this->skillContent('ansible-and-podman-ops');

        foreach (
            [
                'security_opt: [no-new-privileges]',
                'cap_drop: [all]',
                'the `:Z` flag suffix for SELinux volume relabeling',
                'become: true` combined with `become_user: cmsfornerd`',
                'UID/GID 1501',
                'ansible.builtin.file` instead of `file`',
                'containers.podman.podman_container` instead of `podman_container`',
                'ansible-lint deploy.yml',
                'ansible-playbook --syntax-check deploy.yml',
            ] as $expected
        ) {
            $this->assertStringContainsString($expected, $content, "ansible-and-podman-ops must document: '{$expected}'.");
        }
    }

    public function testBotDetectionSkillDocumentsSsrfAndCachingRequirements(): void
    {
        $content = $this->skillContent('bot-detection-and-network-ops');

        foreach (
            [
                'fetched concurrently via `curl_multi`',
                'data/trusted-bots.json',
                'CURLOPT_FOLLOWLOCATION, true',
                'CURLOPT_MAXREDIRS, 5',
                'FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE',
                'CURLOPT_PROTOCOLS` to `CURLPROTO_HTTPS`',
                'SHA-256 for cache keys',
            ] as $expected
        ) {
            $this->assertStringContainsString($expected, $content, "bot-detection-and-network-ops must document: '{$expected}'.");
        }
    }

    public function testCmsDocumentationSkillDocumentsSequencingAndInstallationRules(): void
    {
        $content = $this->skillContent('cms-documentation-and-education');

        foreach (
            [
                'update core record-keeping files (`README.md`, `CHANGELOG.md`, `HISTORY.md`) **last**',
                'v4.0.0-alpha` (or higher)',
                'composer check-platform-reqs',
                'LAB-GUIDE.md` and `template-guide.md`',
            ] as $expected
        ) {
            $this->assertStringContainsString($expected, $content, "cms-documentation-and-education must document: '{$expected}'.");
        }
    }

    public function testCmsSecuritySkillDocumentsHardeningMandates(): void
    {
        $content = $this->skillContent('cms-security-and-best-practices');

        foreach (
            [
                '\CmsForNerd\SecurityUtils::getSafeBaseUrl()',
                '\CmsForNerd\SecurityUtils::resolvePageName($queryParams, $defaultFallback)',
                '\CmsForNerd\SecurityUtils::escapeHtml($variable)',
                'The use of the `global` keyword and the `$GLOBALS` array is strictly prohibited',
                '403 Forbidden` status by the `boot_security()` function',
                'INSTRUCTOR_KEY` config, overridable by `CMS_INSTRUCTOR_KEY` environment variable',
                'checking `$fullBytes < 16`',
                'itemscope` and `itemtype`',
            ] as $expected
        ) {
            $this->assertStringContainsString($expected, $content, "cms-security-and-best-practices must document: '{$expected}'.");
        }
    }

    public function testPhpPerformanceSkillDocumentsOptimizationTechniques(): void
    {
        $content = $this->skillContent('php-performance-and-benchmarking');

        foreach (
            [
                'DirectoryIterator` or `FilesystemIterator`',
                'isset($search_array[$term])` to perform O(1) lookups',
                'data/cache/` directory is registered in `.gitignore`',
            ] as $expected
        ) {
            $this->assertStringContainsString($expected, $content, "php-performance-and-benchmarking must document: '{$expected}'.");
        }

        $this->assertStringContainsString(
            "clearstatcache()",
            $content,
            'php-performance-and-benchmarking must instruct clearing the PHP stat cache before benchmarking.'
        );
    }

    public function testPhpQualitySkillDocumentsStaticAnalysisAndTestingStandards(): void
    {
        $content = $this->skillContent('php-quality-sonar-phpstan');

        foreach (
            [
                'use the `non-falsy-string` annotation',
                'annotate with `non-empty-string`',
                'SonarSource/sonarqube-scan-action@v8` or higher',
                'FORCE_JAVASCRIPT_ACTIONS_TO_NODE24: true',
                'pull-requests: read` permission scope',
                'sonar.projectKey` is strictly lowercase',
                'PHPUnit\Framework\Attributes\DataProvider',
                'clamp CIDR prefix lengths to `0-32` (for IPv4) or `0-128` (for IPv6)',
                'explicit language specifications',
            ] as $expected
        ) {
            $this->assertStringContainsString($expected, $content, "php-quality-sonar-phpstan must document: '{$expected}'.");
        }
    }

    public function testSovereignGitSkillDocumentsMergeAndCommitPolicies(): void
    {
        $content = $this->skillContent('sovereign-git-and-workflow');

        foreach (
            [
                'git merge origin/<branch> --allow-unrelated-histories',
                'Proceed independently without waiting for master/main branch approvals',
                'v4.0.0-alpha` or higher',
                '.phpunit.cache/',
                'data/cache/*',
                'Incremental Git commits are required during a task only when the current user explicitly requests them',
            ] as $expected
        ) {
            $this->assertStringContainsString($expected, $content, "sovereign-git-and-workflow must document: '{$expected}'.");
        }
    }

    public function testStaticBakingSkillDocumentsGithubPagesAndRouterBehaviour(): void
    {
        $content = $this->skillContent('static-baking-and-routing');

        foreach (
            [
                '.nojekyll` file must be automatically generated',
                '.github/workflows/static-build.yml',
                'tools/bake-static-pages.php',
                'build_static/` must be ignored in `.gitignore`',
                'DOMParser` to parse fetched HTML responses',
                'fall back to injecting the raw response as-is',
            ] as $expected
        ) {
            $this->assertStringContainsString($expected, $content, "static-baking-and-routing must document: '{$expected}'.");
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
