<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Validates Google Antigravity & Google Jules Skill Integration across all skills under .agents/skills/.
 */
final class AntigravityJulesSkillIntegrationTest extends TestCase
{
    private const PROTOCOL_HEADING = '### Google Antigravity & Google Jules Skill Integration Protocol';

    private string $root;

    protected function setUp(): void
    {
        $this->root = dirname(__DIR__);
    }

    private function read(string $path): string
    {
        $this->assertFileExists($path, "Expected file {$path} to exist.");
        $content = file_get_contents($path);
        $this->assertIsString($content);

        return $content;
    }

    public function testIntegrationProtocolIsIdenticalAcrossBothAgentRegistries(): void
    {
        $rootRegistry = $this->root . '/AGENTS.md';
        $fullRegistry = $this->root . '/.agents/AGENTS.md';

        $rootProtocol = $this->extractProtocol($this->read($rootRegistry), $rootRegistry);
        $fullProtocol = $this->extractProtocol($this->read($fullRegistry), $fullRegistry);

        $this->assertSame(
            str_replace("\r\n", "\n", $rootProtocol),
            str_replace("\r\n", "\n", $fullProtocol),
            'Google Antigravity & Google Jules Skill Integration Protocol section must be identical across ' .
            'AGENTS.md and .agents/AGENTS.md'
        );
    }

    public function testAllSkillFilesPossessValidAntigravityOkfFrontmatter(): void
    {
        $skillsDir = $this->root . '/.agents/skills';
        $dirs = glob($skillsDir . '/*', GLOB_ONLYDIR);
        $this->assertIsArray($dirs);
        $this->assertNotEmpty($dirs);

        $requiredFields = ['okf_version', 'type', 'title', 'name', 'description', 'topics', 'timestamp'];

        foreach ($dirs as $dir) {
            $skill = basename($dir);
            $skillFile = $dir . '/SKILL.md';
            $this->assertFileExists($skillFile, "Skill directory {$skill} must contain SKILL.md");

            $content = $this->read($skillFile);

            // Strip UTF-8 BOM
            if (str_starts_with($content, "\xef\xbb\xbf")) {
                $content = substr($content, 3);
            }

            $matched = preg_match('/^---\s*\n(.*?)\n---\s*\n/s', $content, $matches);
            $this->assertSame(1, $matched, "SKILL.md for skill {$skill} must contain YAML frontmatter.");

            $frontmatter = $matches[1];

            foreach ($requiredFields as $field) {
                $hasField = preg_match_all('/^' . preg_quote($field, '/') . ':\s*.*$/m', $frontmatter);
                $this->assertSame(
                    1,
                    $hasField,
                    "Skill {$skill} SKILL.md frontmatter must contain required field '{$field}' exactly once."
                );
            }

            $this->assertMatchesRegularExpression(
                '/^name:\s*["\']?' . preg_quote($skill, '/') . '["\']?$/m',
                $frontmatter,
                "Skill {$skill} SKILL.md frontmatter 'name' field must match directory name '{$skill}'."
            );

            $this->assertMatchesRegularExpression(
                '/^timestamp:\s*\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}Z$/m',
                $frontmatter,
                "Skill {$skill} SKILL.md frontmatter 'timestamp' field must be an ISO 8601 UTC string."
            );

            $topicsMatched = preg_match('/^topics:\s*\[([^]]+)]$/m', $frontmatter, $topicMatches);
            $this->assertSame(1, $topicsMatched, "Skill {$skill} must declare topics as an inline YAML array.");

            $topics = array_map('trim', explode(',', $topicMatches[1]));
            $this->assertGreaterThanOrEqual(3, count($topics), "Skill {$skill} must declare at least three topics.");
            $this->assertLessThanOrEqual(5, count($topics), "Skill {$skill} must declare no more than five topics.");

            foreach ($topics as $topic) {
                $this->assertMatchesRegularExpression(
                    '/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                    $topic,
                    "Skill {$skill} topic '{$topic}' must be a lowercase keyword."
                );
            }
        }
    }

    private function extractProtocol(string $content, string $path): string
    {
        $pos = strpos($content, self::PROTOCOL_HEADING);
        $this->assertNotFalse($pos, "File {$path} must contain heading '" . self::PROTOCOL_HEADING . "'");

        $matched = preg_match(
            '/(' . preg_quote(self::PROTOCOL_HEADING, '/') . '\R\R.*?\R4\. .*?)(?=\R\R(?:---|## )|\z)/s',
            $content,
            $matches
        );

        $this->assertSame(1, $matched, "Unable to extract protocol section from {$path}");

        return trim($matches[1]);
    }
}
