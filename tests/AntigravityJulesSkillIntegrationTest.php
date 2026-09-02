<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Guards the Antigravity/Jules skill integration documented by this PR.
 */
final class AntigravityJulesSkillIntegrationTest extends TestCase
{
    private const PROTOCOL_HEADING = '### Google Antigravity & Google Jules Skill Integration Protocol';

    /** @var array<string, string> */
    private const REGISTERED_SKILLS = [
        'Web Interface Guidelines & Accessibility' => 'web-design-guidelines',
        'Ansible & Podman Infrastructure Operations' => 'ansible-and-podman-ops',
        'CMS Security & Architectural Hardening' => 'cms-security-and-best-practices',
        'PHP Code Quality, SonarCloud & PHPStan' => 'php-quality-sonar-phpstan',
        'PHP Performance & Benchmarking' => 'php-performance-and-benchmarking',
        'Static Page Baking & Routing' => 'static-baking-and-routing',
        'CMS Documentation & Educational Alignment' => 'cms-documentation-and-education',
        'Sovereign Git Operations & Incremental Workflow' => 'sovereign-git-and-workflow',
        'Bot Detection & Network Operations' => 'bot-detection-and-network-ops',
    ];

    /** @var array<int, string> */
    private const CHANGED_SKILLS = [
        'ansible-and-podman-ops',
        'asimp-and-ai-integration',
        'cms-documentation-and-education',
        'cms-security-and-best-practices',
        'php-performance-and-benchmarking',
        'php-quality-sonar-phpstan',
        'python-utility-and-security',
        'sovereign-git-and-workflow',
        'static-baking-and-routing',
        'telemetry-and-feedback-ops',
        'web-design-guidelines',
    ];

    private string $repositoryRoot;

    protected function setUp(): void
    {
        $this->repositoryRoot = dirname(__DIR__);
    }

    /**
     * @return array<int, string>
     */
    private function registryPaths(): array
    {
        return [
            $this->repositoryRoot . '/AGENTS.md',
            $this->repositoryRoot . '/.agents/AGENTS.md',
        ];
    }

    private function read(string $path): string
    {
        $content = file_get_contents($path);
        $this->assertIsString($content, "Unable to read '{$path}'.");

        return $content;
    }

    public function testBothRegistriesListEveryNewSkillAndPointToExistingManuals(): void
    {
        foreach (self::REGISTERED_SKILLS as $label => $directory) {
            $manual = $this->repositoryRoot . "/.agents/skills/{$directory}/SKILL.md";
            $this->assertFileExists($manual, "Registered skill '{$directory}' must have a SKILL.md manual.");

            $bullet = "- **{$label} (`.agents/skills/{$directory}/`)**";
            foreach ($this->registryPaths() as $registry) {
                $this->assertStringContainsString(
                    $bullet,
                    $this->read($registry),
                    "'{$registry}' must register '{$directory}' using its canonical path."
                );
            }
        }
    }

    public function testNewRegistryEntriesRemainInTheDocumentedOrder(): void
    {
        foreach ($this->registryPaths() as $registry) {
            $content = $this->read($registry);
            $previousPosition = -1;

            foreach (self::REGISTERED_SKILLS as $directory) {
                $position = strpos($content, "`.agents/skills/{$directory}/`");
                $this->assertNotFalse($position, "'{$registry}' is missing '{$directory}'.");
                $this->assertGreaterThan(
                    $previousPosition,
                    $position,
                    "'{$directory}' is out of order in '{$registry}'."
                );
                $previousPosition = $position;
            }

            $protocolPosition = strpos($content, self::PROTOCOL_HEADING);
            $this->assertNotFalse($protocolPosition, "'{$registry}' is missing the integration protocol.");
            $this->assertGreaterThan(
                $previousPosition,
                $protocolPosition,
                "The integration protocol in '{$registry}' must follow the registered skill catalog."
            );
        }
    }

    public function testIntegrationProtocolIsIdenticalAcrossBothAgentRegistries(): void
    {
        [$rootRegistry, $fullRegistry] = $this->registryPaths();

        $this->assertSame(
            $this->extractProtocol($this->read($rootRegistry), $rootRegistry),
            $this->extractProtocol($this->read($fullRegistry), $fullRegistry),
            'Rule 23 requires the Antigravity/Jules protocol to stay synchronized across both AGENTS.md files.'
        );
    }

    public function testIntegrationProtocolDeclaresEachCompatibilityGuaranteeExactlyOnce(): void
    {
        $expectedClauses = [
            'Antigravity & AgentSkills.io Specification Compatibility',
            'Knowledge Interoperability',
            'Execution & Context Window Protection',
            'Digital Sovereignty Signatures',
        ];

        foreach ($this->registryPaths() as $registry) {
            $protocol = $this->extractProtocol($this->read($registry), $registry);

            foreach ($expectedClauses as $index => $clause) {
                $number = $index + 1;
                $this->assertSame(
                    1,
                    substr_count($protocol, "{$number}. **{$clause}:**"),
                    "'{$registry}' must contain protocol clause {$number} exactly once."
                );
            }
        }
    }

    public function testProtocolNamesTheCompleteFrontmatterContractAndVerificationTool(): void
    {
        foreach ($this->registryPaths() as $registry) {
            $protocol = $this->extractProtocol($this->read($registry), $registry);

            $this->assertStringContainsString(
                '(`okf_version`, `type`, `title`, `name`, `description`, `topics`, `timestamp`)',
                $protocol
            );
            $this->assertStringContainsString('strict 4,000-token limit', $protocol);
            $this->assertStringContainsString(
                '.agents/skills/dsom-token-calculator/scripts/calculate-tokens.py',
                $protocol
            );
            $this->assertStringContainsString('standard Deep State of Mind (DSOM) AI Protocol footer', $protocol);
        }
    }

    public function testEveryChangedSkillUsesCanonicalAntigravityFrontmatter(): void
    {
        foreach (self::CHANGED_SKILLS as $skill) {
            $content = $this->read($this->skillPath($skill));
            $matched = preg_match('/\A---\R(.*?)\R---\R/s', $content, $matches);

            $this->assertSame(1, $matched, "Skill '{$skill}' must start with a YAML frontmatter block.");
            $frontmatter = $matches[1] ?? '';

            foreach (['okf_version', 'type', 'title', 'name', 'description', 'topics', 'timestamp'] as $field) {
                $this->assertSame(
                    1,
                    preg_match_all('/^' . preg_quote($field, '/') . ':\s*.+$/m', $frontmatter),
                    "Skill '{$skill}' must declare exactly one non-empty '{$field}' field."
                );
            }

            $this->assertStringContainsString("name: \"{$skill}\"", $frontmatter);
            $this->assertStringContainsString('type: skill', $frontmatter);
            $this->assertStringContainsString('timestamp: 2026-08-01T09:00:00Z', $frontmatter);
        }
    }

    public function testEveryChangedSkillEndsWithTheCompleteDsomSignature(): void
    {
        foreach (self::CHANGED_SKILLS as $skill) {
            $content = rtrim($this->read($this->skillPath($skill)));

            $this->assertMatchesRegularExpression(
                '/\*Deep State of Mind \(DSOM\) For My AI Protocol \| Harisfazillah Jamel '
                . '\(LinuxMalaysia\) \| \d{4}-\d{2}-\d{2}\*\R'
                . '\*Standard: UK English \| DBP-standard Bahasa Melayu Malaysia \(Piawai\) '
                . '\| GNU General Public License v3\.0\*\z/',
                $content,
                "Skill '{$skill}' must end with both DSOM signature lines."
            );
        }
    }

    /**
     * @param array<int, string> $expectedAdditions
     */
    #[DataProvider('changedSkillAdditionsProvider')]
    public function testChangedSkillDocumentsItsNewOperationalGuidance(
        string $skill,
        array $expectedAdditions
    ): void {
        $content = $this->read($this->skillPath($skill));

        foreach ($expectedAdditions as $expected) {
            $this->assertStringContainsString(
                $expected,
                $content,
                "Skill '{$skill}' must retain the PR guidance '{$expected}'."
            );
        }
    }

    /**
     * @return array<string, array{string, array<int, string>}>
     */
    public static function changedSkillAdditionsProvider(): array
    {
        return [
            'Ansible and Podman deployment invariants' => [
                'ansible-and-podman-ops',
                ['Ubuntu 26.04 & Podman 5+ Invariant', 'Render Blueprint (`render.yaml`)',
                    'Safe `grep -c` under `set -e`'],
            ],
            'ASIMP hardening workflow' => [
                'asimp-and-ai-integration',
                ['Measure, Harden, Re-Measure', 'CIS Level 2 Profile', 'data/asimp_mock/'],
            ],
            'documentation synchronization' => [
                'cms-documentation-and-education',
                ['Rule 14 (Omni-Documentation Sync)', 'Diátaxis documentation framework',
                    'composer check-platform-reqs'],
            ],
            'CMS security hardening' => [
                'cms-security-and-best-practices',
                ['SecurityUtils::discoverPages()', 'startSecureSession()',
                    'root `index.html` file is explicitly excluded'],
            ],
            'PHP performance caching' => [
                'php-performance-and-benchmarking',
                ['UnexpectedValueException', 'tools/bench_is_bot.php', 'self::$sourceMaxMTimeTimestamp'],
            ],
            'PHP quality gates' => [
                'php-quality-sonar-phpstan',
                ['.github/workflows/security-sast.yml', './vendor/bin/pest',
                    'indentation that is strictly a multiple of 4 spaces'],
            ],
            'Python path safety' => [
                'python-utility-and-security',
                ['os.path.realpath', 'os.O_NOFOLLOW', 'tests/test_generate_llms_files.py'],
            ],
            'sovereign Git workflow' => [
                'sovereign-git-and-workflow',
                ['`git fetch` followed by `git merge` or `git rebase`', 'Rule 24 Session Recording',
                    'Compliance Test Timestamp Alignment'],
            ],
            'static publishing pipeline' => [
                'static-baking-and-routing',
                ['llms-full.txt', 'tools/generate-seo-files.php', 'DOMParser'],
            ],
            'telemetry execution modes' => [
                'telemetry-and-feedback-ops',
                ['dev vs. user', 'permissions set to `0600`',
                    'Ubuntu 24.04, Ubuntu 26.04, AlmaLinux 9, and Debian 12'],
            ],
            'web interface verification' => [
                'web-design-guidelines',
                ['themes/CmsForNerd/css/amp.css', '--lab-section-bg', 'tests/playwright/'],
            ],
        ];
    }

    private function skillPath(string $skill): string
    {
        return $this->repositoryRoot . "/.agents/skills/{$skill}/SKILL.md";
    }

    private function extractProtocol(string $content, string $path): string
    {
        $matched = preg_match(
            '/(' . preg_quote(self::PROTOCOL_HEADING, '/') . '\R\R.*?\R4\. .*?)(?=\R\R(?:---|## ))/s',
            $content,
            $matches
        );
        $this->assertSame(1, $matched, "Unable to extract a four-clause integration protocol from '{$path}'.");

        return $matches[1] ?? '';
    }
}
