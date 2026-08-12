<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Validates that the new `asimp-ai-agents.php` page controller (and its
 * paired `docs/governance/ASIMP-FOR-AI-AGENTS.md` documentation) were
 * correctly registered across the statically generated SEO artefacts:
 * ror.xml, rss.xml, sitemap.txt, and sitemap.xml.
 *
 * These files are produced by tools/generate-seo-files.php; this suite
 * guards against regressions where the generator is re-run (or the files
 * are hand-edited) without keeping every publishing target in sync.
 */
final class SeoAsimpAiAgentsEntryTest extends TestCase
{
    private const CUSTOM_DOMAIN_URL = 'https://www.linuxmalaysia.com/asimp-ai-agents.php';

    private const RENDER_URL = 'https://cmsfornerd.onrender.com/asimp-ai-agents.php';

    private const GITHUB_PAGES_URL = 'https://linuxmalaysia.github.io/CmsForNerd/asimp-ai-agents.html';

    private const GITBOOK_URL
        = 'https://malaysia-open-source-community.gitbook.io/deep-state-of-mind-dsom-protocol-for-my-ai/docs/governance/asimp-for-ai-agents';

    private string $root;

    protected function setUp(): void
    {
        $this->root = dirname(__DIR__);
    }

    private function read(string $relativePath): string
    {
        $path = $this->root . '/' . $relativePath;
        $this->assertFileExists($path, "Expected '{$relativePath}' to exist.");

        return (string) file_get_contents($path);
    }

    // ---------------------------------------------------------------
    // ror.xml
    // ---------------------------------------------------------------

    public function testRorXmlContainsAsimpAiAgentsItem(): void
    {
        $content = $this->read('ror.xml');

        $this->assertStringContainsString('<link>' . self::CUSTOM_DOMAIN_URL . '</link>', $content);
        $this->assertStringContainsString('<title>Asimp ai agents</title>', $content);
    }

    public function testRorXmlAsimpItemHasResourceTypeAndUpdatedDate(): void
    {
        $content = $this->read('ror.xml');

        $linkPos = strpos($content, '<link>' . self::CUSTOM_DOMAIN_URL . '</link>');
        $this->assertNotFalse($linkPos, 'ror.xml must contain an <item> for the asimp-ai-agents.php link.');

        $itemEnd = strpos($content, '</item>', $linkPos);
        $this->assertNotFalse($itemEnd, 'Expected a closing </item> tag after the asimp-ai-agents.php link.');
        $itemBlock = substr($content, $linkPos, $itemEnd - $linkPos);

        $this->assertStringContainsString('<ror:type>resource</ror:type>', $itemBlock);
        $this->assertMatchesRegularExpression(
            '/<ror:updated>\d{4}-\d{2}-\d{2}<\/ror:updated>/',
            $itemBlock
        );
    }

    public function testRorXmlAsimpItemAppearsExactlyOnce(): void
    {
        $content = $this->read('ror.xml');

        $this->assertSame(
            1,
            substr_count($content, '<link>' . self::CUSTOM_DOMAIN_URL . '</link>'),
            'The asimp-ai-agents.php link must be registered exactly once in ror.xml.'
        );
    }

    // ---------------------------------------------------------------
    // rss.xml
    // ---------------------------------------------------------------

    public function testRssXmlContainsAsimpAiAgentsItem(): void
    {
        $content = $this->read('rss.xml');

        $this->assertStringContainsString('<title>Asimp ai agents</title>', $content);
        $this->assertStringContainsString('<link>' . self::CUSTOM_DOMAIN_URL . '</link>', $content);
        $this->assertStringContainsString(
            '<guid isPermaLink="true">' . self::CUSTOM_DOMAIN_URL . '</guid>',
            $content
        );
        $this->assertStringContainsString(
            '<description>Static details for the Asimp ai agents module.</description>',
            $content
        );
    }

    public function testRssXmlRemainsValidXmlWithChannelAndItems(): void
    {
        $xml = simplexml_load_string($this->read('rss.xml'));
        $this->assertNotFalse($xml, 'rss.xml must remain valid XML after the new item was added.');
        $this->assertGreaterThan(0, $xml->channel->item->count(), 'rss.xml must contain at least one <item>.');
    }

    public function testRssXmlLastBuildDateIsAValidRfc2822Timestamp(): void
    {
        $content = $this->read('rss.xml');

        $matched = preg_match('/<lastBuildDate>([^<]+)<\/lastBuildDate>/', $content, $matches);
        $this->assertSame(1, $matched, 'rss.xml must declare exactly one <lastBuildDate>.');

        $timestamp = strtotime($matches[1]);
        $this->assertNotFalse($timestamp, "lastBuildDate '{$matches[1]}' must be a parseable date string.");
    }

    // ---------------------------------------------------------------
    // sitemap.txt
    // ---------------------------------------------------------------

    public function testSitemapTxtContainsAllFourPublishingTargets(): void
    {
        $content = $this->read('sitemap.txt');

        foreach (
            [
                self::CUSTOM_DOMAIN_URL,
                self::RENDER_URL,
                self::GITHUB_PAGES_URL,
                self::GITBOOK_URL,
            ] as $url
        ) {
            $this->assertStringContainsString($url, $content, "sitemap.txt must list: {$url}");
        }
    }

    public function testSitemapTxtEntriesRemainAlphabeticallySortedAroundNewEntry(): void
    {
        $lines = array_values(array_filter(array_map('trim', explode("\n", $this->read('sitemap.txt')))));

        $index = array_search(self::CUSTOM_DOMAIN_URL, $lines, true);
        $this->assertNotFalse($index, 'Expected to find the custom-domain asimp-ai-agents.php URL in sitemap.txt.');

        $this->assertGreaterThan(0, $index, 'Expected a preceding line before the asimp-ai-agents.php URL.');
        $this->assertLessThan(count($lines) - 1, $index, 'Expected a following line after the asimp-ai-agents.php URL.');

        $this->assertLessThan($lines[$index], $lines[$index - 1], 'sitemap.txt lines must remain sorted (previous line).');
        $this->assertGreaterThan($lines[$index], $lines[$index + 1], 'sitemap.txt lines must remain sorted (next line).');
    }

    public function testSitemapTxtHasNoDuplicateAsimpEntries(): void
    {
        $content = $this->read('sitemap.txt');

        foreach ([self::CUSTOM_DOMAIN_URL, self::RENDER_URL, self::GITHUB_PAGES_URL, self::GITBOOK_URL] as $url) {
            $this->assertSame(1, substr_count($content, $url), "sitemap.txt must list '{$url}' exactly once.");
        }
    }

    // ---------------------------------------------------------------
    // sitemap.xml
    // ---------------------------------------------------------------

    public function testSitemapXmlContainsAllFourPublishingTargets(): void
    {
        $xml = simplexml_load_string($this->read('sitemap.xml'));
        $this->assertNotFalse($xml, 'sitemap.xml must be valid XML.');

        $locs = [];
        foreach ($xml->url as $urlNode) {
            $locs[] = (string) $urlNode->loc;
        }

        foreach (
            [
                self::CUSTOM_DOMAIN_URL,
                self::RENDER_URL,
                self::GITHUB_PAGES_URL,
                self::GITBOOK_URL,
            ] as $expected
        ) {
            $this->assertContains($expected, $locs, "sitemap.xml must contain a <url><loc> for: {$expected}");
        }
    }

    public function testSitemapXmlAsimpUrlNodesUseWeeklyChangefreqAndStandardPriority(): void
    {
        $xml = simplexml_load_string($this->read('sitemap.xml'));
        $this->assertNotFalse($xml);

        $checked = 0;
        foreach ($xml->url as $urlNode) {
            $loc = (string) $urlNode->loc;
            if (!in_array($loc, [self::CUSTOM_DOMAIN_URL, self::RENDER_URL, self::GITHUB_PAGES_URL], true)) {
                continue;
            }

            $this->assertSame('weekly', (string) $urlNode->changefreq, "Unexpected changefreq for {$loc}");
            $this->assertSame('0.5', (string) $urlNode->priority, "Unexpected priority for {$loc}");
            $checked++;
        }

        $this->assertSame(3, $checked, 'Expected to validate exactly the three page-controller <url> entries for asimp-ai-agents.');
    }

    // ---------------------------------------------------------------
    // Cross-file consistency
    // ---------------------------------------------------------------

    public function testSlugIsByteIdenticalAcrossPhpPublishingTargets(): void
    {
        // Regression guard against typos such as "asimp_ai_agents" or
        // "asimp-ai-agent" creeping into one file but not another.
        foreach (['ror.xml', 'rss.xml', 'sitemap.txt', 'sitemap.xml'] as $file) {
            $content = $this->read($file);
            $this->assertStringContainsString(
                'asimp-ai-agents',
                $content,
                "'{$file}' must reference the canonical 'asimp-ai-agents' slug."
            );
            $this->assertStringNotContainsString('asimp_ai_agents', $content, "'{$file}' must not contain an underscored slug variant.");
        }
    }

    public function testGitbookSlugUsesGovernanceDocFilenameLowercased(): void
    {
        // The governance doc is docs/governance/ASIMP-FOR-AI-AGENTS.md, and
        // GitBook URLs are derived by lowercasing the relative path.
        $sitemapTxt = $this->read('sitemap.txt');
        $sitemapXml = $this->read('sitemap.xml');

        $this->assertStringContainsString(self::GITBOOK_URL, $sitemapTxt);
        $this->assertStringContainsString(self::GITBOOK_URL, $sitemapXml);
        $this->assertStringNotContainsString(strtoupper(self::GITBOOK_URL), $sitemapTxt);
    }
}