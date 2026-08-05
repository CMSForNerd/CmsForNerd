<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Validates the eight new DSOM Google Antigravity-compliant SKILL.md documents
 * added under `.agents/skills/`:
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
 * Every skill MUST expose a well-formed YAML front-matter block (okf_version,
 * type, title, name, description, topics, timestamp), a consistent body
 * structure (Purpose / When to use this skill / Guidelines & Best Practices),
 * language-tagged fenced code blocks (MD040 compliance, itself mandated by the
 * php-quality-sonar-phpstan skill), and the DSOM footer signature.
 */
final class NewSkillsDocumentationTest extends TestCase
{
    private const FOOTER_TIMESTAMP_LINE
        = '*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-05*';

    private const FOOTER_STANDARD_LINE
        = '*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*';

    /** @var array<int, string> Slugs of the skills introduced by this change set. */
    private const NEW_SKILL_SLUGS = [
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

    private function skillPath(string $slug): string
    {
        return $this->skillsRoot . '/' . $slug . '/SKILL.md';
    }

    private function readSkill(string $slug): string
    {
        $content = file_get_contents($this->skillPath($slug));
        $this->assertIsString($content, "SKILL.md for '{$slug}' must be readable.");

        return $content;
    }

    /**
     * Extracts the raw YAML front-matter block (without the surrounding '---' fences).
     */
    private function extractFrontMatter(string $content): string
    {
        $matched = preg_match('/^---\n(.*?)\n---\n/s', $content, $matches);
        $this->assertSame(1, $matched, 'Document must start with a valid YAML front-matter block delimited by ---.');

        return $matches[1];
    }

    /**
     * @return array<int, array{0: string}>
     */
    public static function skillSlugProvider(): array
    {
        return array_map(static fn (string $slug): array => [$slug], self::NEW_SKILL_SLUGS);
    }

    #[DataProvider('skillSlugProvider')]
    public function testSkillFileExists(string $slug): void
    {
        $this->assertFileExists($this->skillPath($slug));
    }

    #[DataProvider('skillSlugProvider')]
    public function testFrontMatterContainsAllRequiredFields(string $slug): void
    {
        $frontMatter = $this->extractFrontMatter($this->readSkill($slug));

        foreach (['okf_version', 'type', 'title', 'name', 'description', 'topics', 'timestamp'] as $field) {
            $this->assertMatchesRegularExpression(
                '/^' . preg_quote($field, '/') . ':/m',
                $frontMatter,
                "Front-matter for '{$slug}' must declare the '{$field}' field."
            );
        }
    }

    #[DataProvider('skillSlugProvider')]
    public function testOkfVersionIsZeroPointOne(string $slug): void
    {
        $frontMatter = $this->extractFrontMatter($this->readSkill($slug));

        $this->assertMatchesRegularExpression('/^okf_version:\s*0\.1\s*$/m', $frontMatter);
    }

    #[DataProvider('skillSlugProvider')]
    public function testTypeIsSkill(string $slug): void
    {
        $frontMatter = $this->extractFrontMatter($this->readSkill($slug));

        $this->assertMatchesRegularExpression('/^type:\s*skill\s*$/m', $frontMatter);
    }

    #[DataProvider('skillSlugProvider')]
    public function testNameFieldMatchesDirectorySlug(string $slug): void
    {
        $frontMatter = $this->extractFrontMatter($this->readSkill($slug));

        $this->assertMatchesRegularExpression(
            '/^name:\s*"' . preg_quote($slug, '/') . '"\s*$/m',
            $frontMatter,
            "The 'name' front-matter field for '{$slug}' must exactly match its containing directory name."
        );
    }

    #[DataProvider('skillSlugProvider')]
    public function testTitleAndDescriptionAreNonEmptyQuotedStrings(string $slug): void
    {
        $frontMatter = $this->extractFrontMatter($this->readSkill($slug));

        $this->assertMatchesRegularExpression('/^title:\s*"[^"]+"\s*$/m', $frontMatter);
        $this->assertMatchesRegularExpression('/^description:\s*"[^"]+"\s*$/m', $frontMatter);
    }

    #[DataProvider('skillSlugProvider')]
    public function testTopicsIsANonEmptyBracketedList(string $slug): void
    {
        $frontMatter = $this->extractFrontMatter($this->readSkill($slug));

        $matched = preg_match('/^topics:\s*\[([^\]]+)\]\s*$/m', $frontMatter, $matches);
        $this->assertSame(1, $matched, "The 'topics' field for '{$slug}' must be a bracketed list.");

        $topics = array_map('trim', explode(',', $matches[1]));
        $this->assertNotEmpty($topics);
        foreach ($topics as $topic) {
            $this->assertNotSame('', $topic, "Topics list for '{$slug}' must not contain empty entries.");
        }
    }

    #[DataProvider('skillSlugProvider')]
    public function testTimestampIsIso8601AndMatchesPublicationDate(string $slug): void
    {
        $frontMatter = $this->extractFrontMatter($this->readSkill($slug));

        $this->assertMatchesRegularExpression(
            '/^timestamp:\s*2026-08-01T09:00:00Z\s*$/m',
            $frontMatter,
            "All eight new skills in this change set share the same publication timestamp."
        );
    }

    #[DataProvider('skillSlugProvider')]
    public function testBodyContainsRequiredSections(string $slug): void
    {
        $content = $this->readSkill($slug);

        foreach (['## Purpose', '## When to use this skill', '## Guidelines & Best Practices'] as $heading) {
            $this->assertStringContainsString($heading, $content, "'{$slug}' must contain the '{$heading}' section.");
        }
    }

    #[DataProvider('skillSlugProvider')]
    public function testBodyContainsAnEmojiH1Heading(string $slug): void
    {
        $content = $this->readSkill($slug);

        $this->assertMatchesRegularExpression(
            '/^# \S.+$/m',
            $content,
            "'{$slug}' must contain a top-level H1 heading."
        );
    }

    #[DataProvider('skillSlugProvider')]
    public function testGuidelinesAreSequentiallyNumberedStartingAtOne(string $slug): void
    {
        $content = $this->readSkill($slug);

        preg_match_all('/^### (\d+)\./m', $content, $matches);
        $numbers = array_map('intval', $matches[1]);

        $this->assertNotEmpty($numbers, "'{$slug}' must declare at least one numbered guideline subsection.");
        $this->assertSame(1, $numbers[0], "'{$slug}' guideline numbering must start at 1.");

        $expected = range(1, count($numbers));
        $this->assertSame(
            $expected,
            $numbers,
            "'{$slug}' guideline subsections must be sequentially numbered without gaps or duplicates."
        );
    }

    #[DataProvider('skillSlugProvider')]
    public function testFencedCodeBlocksDeclareAnExplicitLanguage(string $slug): void
    {
        $content = $this->readSkill($slug);

        // Fences may be indented (e.g. nested under a bullet list item), so leading
        // whitespace is tolerated but not captured.
        preg_match_all('/^[ \t]*```(\S*)[ \t]*$/m', $content, $matches);
        $languages = $matches[1];

        // Fences must be balanced (every opening fence has a matching closing fence).
        $this->assertSame(0, count($languages) % 2, "'{$slug}' has an unbalanced number of code fences.");

        // Fences come in opening/closing pairs; only opening fences declare a language.
        // Closing fences are legitimately empty, so verify every *other* fence (0, 2, 4, ...) is non-empty.
        foreach ($languages as $index => $language) {
            if ($index % 2 === 0) {
                $this->assertNotSame(
                    '',
                    $language,
                    "'{$slug}' has a fenced code block (opening fence #" . ($index / 2 + 1) .
                    ') missing an explicit language tag, violating MD040.'
                );
            }
        }
    }

    public function testAtLeastOneNewSkillHasNoFencedCodeBlocksAtAll(): void
    {
        // static-baking-and-routing is purely procedural prose with no embedded
        // code samples; confirm the fenced-block test above tolerates this case
        // instead of only ever exercising the "has code" path.
        $content = $this->readSkill('static-baking-and-routing');

        $this->assertDoesNotMatchRegularExpression('/^[ \t]*```/m', $content);
    }

    #[DataProvider('skillSlugProvider')]
    public function testEveryOpeningFenceLanguageIsARecognizedTag(string $slug): void
    {
        $content = $this->readSkill($slug);

        preg_match_all('/^[ \t]*```(\S+)[ \t]*$/m', $content, $matches);

        foreach ($matches[1] as $language) {
            $this->assertContains(
                $language,
                ['php', 'bash'],
                "'{$slug}' uses an unexpected fenced code block language tag '{$language}'."
            );
        }
    }

    #[DataProvider('skillSlugProvider')]
    public function testFooterContainsDsomTimestampLine(string $slug): void
    {
        $this->assertStringContainsString(self::FOOTER_TIMESTAMP_LINE, $this->readSkill($slug));
    }

    #[DataProvider('skillSlugProvider')]
    public function testFooterContainsDsomStandardLine(string $slug): void
    {
        $this->assertStringContainsString(self::FOOTER_STANDARD_LINE, $this->readSkill($slug));
    }

    #[DataProvider('skillSlugProvider')]
    public function testFooterAppearsAfterAHorizontalRule(string $slug): void
    {
        $content = $this->readSkill($slug);

        $ruleBeforeFooter = strpos($content, "\n---\n" . self::FOOTER_TIMESTAMP_LINE);
        $this->assertNotFalse(
            $ruleBeforeFooter,
            "'{$slug}' footer must be preceded by a standalone '---' horizontal rule."
        );
    }

    public function testAllEightNewSkillNamesAreUnique(): void
    {
        $this->assertSame(
            count(self::NEW_SKILL_SLUGS),
            count(array_unique(self::NEW_SKILL_SLUGS)),
            'The set of newly added skill slugs must not contain duplicates.'
        );
    }

    public function testAllEightNewSkillDirectoriesContainExactlyOneMarkdownFile(): void
    {
        foreach (self::NEW_SKILL_SLUGS as $slug) {
            $files = glob($this->skillsRoot . '/' . $slug . '/*');
            $this->assertSame(
                [$this->skillPath($slug)],
                $files,
                "Skill directory '{$slug}' must contain exactly one file: SKILL.md."
            );
        }
    }

    // -------------------------------------------------------------------
    // Content-specific regression assertions (one per new skill), so that
    // each document's distinguishing guidance cannot silently regress.
    // -------------------------------------------------------------------

    public function testAnsiblePodmanSkillMandatesCapabilityDroppingAndRootlessOwnership(): void
    {
        $content = $this->readSkill('ansible-and-podman-ops');

        $this->assertStringContainsString('cap_drop: [all]', $content);
        $this->assertStringContainsString('security_opt: [no-new-privileges]', $content);
        $this->assertStringContainsString('become_user: cmsfornerd', $content);
        $this->assertStringContainsString('ansible.builtin.file', $content);
        $this->assertStringContainsString('containers.podman.podman_container', $content);
    }

    public function testBotDetectionSkillMandatesSafeRedirectLimitsAndSsrfValidation(): void
    {
        $content = $this->readSkill('bot-detection-and-network-ops');

        $this->assertStringContainsString('CURLOPT_MAXREDIRS, 5', $content);
        $this->assertStringContainsString('FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE', $content);
        $this->assertStringContainsString('curl_multi', $content);
        $this->assertStringContainsString('data/trusted-bots.json', $content);
    }

    public function testCmsDocumentationSkillMandatesDocsUpdatedLastAndIgnorePlatformReqs(): void
    {
        $content = $this->readSkill('cms-documentation-and-education');

        $this->assertStringContainsString('**last**', $content);
        $this->assertStringContainsString('composer install --ignore-platform-reqs', $content);
        $this->assertStringContainsString('v4.0.0-alpha', $content);
    }

    public function testCmsSecuritySkillMandatesCentralizedSecurityUtilsAndZeroGlobal(): void
    {
        $content = $this->readSkill('cms-security-and-best-practices');

        $this->assertStringContainsString('\CmsForNerd\SecurityUtils::getSafeBaseUrl();', $content);
        $this->assertStringContainsString('\CmsForNerd\SecurityUtils::resolvePageName(', $content);
        $this->assertStringContainsString('\CmsForNerd\SecurityUtils::escapeHtml(', $content);
        $this->assertStringContainsString('"Zero-Global"', $content);
        $this->assertStringContainsString('$fullBytes < 16', $content);
    }

    public function testPhpPerformanceSkillMandatesClearstatcacheAndO1Lookups(): void
    {
        $content = $this->readSkill('php-performance-and-benchmarking');

        $this->assertStringContainsString("php -r 'clearstatcache();'", $content);
        $this->assertStringContainsString('isset($search_array[$term])', $content);
        $this->assertStringContainsString('DirectoryIterator', $content);
    }

    public function testPhpQualitySkillMandatesNonFalsyStringAnnotationAndPhpUnitAttributes(): void
    {
        $content = $this->readSkill('php-quality-sonar-phpstan');

        $this->assertStringContainsString('non-falsy-string', $content);
        $this->assertStringContainsString('non-empty-string', $content);
        $this->assertStringContainsString("#[\\PHPUnit\\Framework\\Attributes\\DataProvider('additionProvider')]", $content);
        $this->assertStringContainsString('MD040', $content);
    }

    public function testSovereignGitSkillMandatesAllowUnrelatedHistoriesAndIncrementalCommits(): void
    {
        $content = $this->readSkill('sovereign-git-and-workflow');

        $this->assertStringContainsString('git merge origin/branch --allow-unrelated-histories', $content);
        $this->assertStringContainsString('.phpunit.cache/', $content);
        $this->assertStringContainsString('data/cache/*', $content);
    }

    public function testStaticBakingSkillMandatesNojekyllAndPwaRouterFallback(): void
    {
        $content = $this->readSkill('static-baking-and-routing');

        $this->assertStringContainsString('.nojekyll', $content);
        $this->assertStringContainsString('tools/bake-static-pages.php', $content);
        $this->assertStringContainsString('build_static/', $content);
        $this->assertStringContainsString('DOMParser', $content);
    }
}