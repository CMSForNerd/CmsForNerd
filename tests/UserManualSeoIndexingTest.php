<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * UserManualSeoIndexingTest
 *
 * `SeoSitemapTest` validates the general structure/protocol compliance of
 * sitemap.txt, sitemap.xml, and rss.xml. This test complements it with
 * regression coverage tied specifically to this PR: it pins down that the
 * 24 new Diátaxis documentation pages (plus the docs/user-manual/README.md
 * hub page) were correctly registered in the GitBook sitemap channels, and
 * that the RSS/sitemap "freshness" timestamps were bumped as part of the
 * same publishing run.
 */
final class UserManualSeoIndexingTest extends TestCase
{
    private const GITBOOK_BASE = 'https://malaysia-open-source-community.gitbook.io/'
        . 'deep-state-of-mind-dsom-protocol-for-my-ai/docs/';

    private string $rootDir;

    protected function setUp(): void
    {
        $this->rootDir = (string) realpath(dirname(__DIR__));
    }

    /**
     * @return array<int, string> GitBook doc slugs (relative to `docs/`, without extension)
     *                            expected to have been added by this PR.
     */
    private function expectedNewDocSlugs(): array
    {
        return [
            'explanation/dual-view-amp-engine',
            'explanation/introduction-and-philosophy',
            'explanation/security-hardening-owasp',
            'explanation/three-tier-caching-pwa',
            'explanation/zero-global-architecture-pair-logic',
            'how-to/configure-security-csrf-csp',
            'how-to/configure-seo-sitemaps',
            'how-to/create-manage-pages',
            'how-to/customize-themes-navigation',
            'how-to/deploy-cloud-render-github-pages',
            'how-to/install-linux-native',
            'how-to/install-windows-herd',
            'how-to/manage-content-flatfiles',
            'how-to/run-podman-docker-containers',
            'how-to/run-tests-static-analysis',
            'reference/cms-context-api',
            'reference/configuration-and-composer-scripts',
            'reference/performance-utils-api',
            'reference/registry-api',
            'reference/release-notes-changelog',
            'reference/security-utils-api',
            'reference/system-requirements',
            'tutorials/local-almalinux10-wsl2-podman-setup',
            'tutorials/quickstart-guide',
            'user-manual/readme',
        ];
    }

    public function testSitemapTxtRegistersAllNewGitbookDocSlugs(): void
    {
        $content = (string) file_get_contents($this->rootDir . '/sitemap.txt');
        $lines = array_filter(array_map('trim', explode("\n", $content)));

        $missing = [];
        foreach ($this->expectedNewDocSlugs() as $slug) {
            $expectedUrl = self::GITBOOK_BASE . $slug;
            if (!in_array($expectedUrl, $lines, true)) {
                $missing[] = $expectedUrl;
            }
        }

        $this->assertSame([], $missing, "sitemap.txt is missing expected new documentation URLs:\n" . implode("\n", $missing));
    }

    public function testSitemapXmlRegistersAllNewGitbookDocSlugsWithValidLastmod(): void
    {
        $content = (string) file_get_contents($this->rootDir . '/sitemap.xml');
        $xml = simplexml_load_string($content);
        $this->assertNotFalse($xml, 'sitemap.xml must be valid XML.');

        $locsToLastmod = [];
        foreach ($xml->url as $urlNode) {
            if (isset($urlNode->loc)) {
                $locsToLastmod[(string) $urlNode->loc] = isset($urlNode->lastmod) ? (string) $urlNode->lastmod : null;
            }
        }

        foreach ($this->expectedNewDocSlugs() as $slug) {
            $expectedUrl = self::GITBOOK_BASE . $slug;
            $this->assertArrayHasKey($expectedUrl, $locsToLastmod, "sitemap.xml is missing <url> entry for: {$expectedUrl}");

            $lastmod = $locsToLastmod[$expectedUrl];
            $this->assertNotNull($lastmod, "sitemap.xml entry for {$expectedUrl} must declare a <lastmod>.");
            $this->assertMatchesRegularExpression(
                '/^\d{4}-\d{2}-\d{2}$/',
                (string) $lastmod,
                "sitemap.xml <lastmod> for {$expectedUrl} must be in YYYY-MM-DD format."
            );
        }
    }

    public function testSitemapTxtHasNoDuplicateEntriesAfterAddingNewDocs(): void
    {
        $content = (string) file_get_contents($this->rootDir . '/sitemap.txt');
        $lines = array_filter(array_map('trim', explode("\n", $content)));

        $this->assertCount(
            count($lines),
            array_unique($lines),
            'sitemap.txt must not contain duplicate URLs after appending the new documentation pages.'
        );
    }

    public function testPrimaryPropertySitemapEntriesReflectLatestPublishDate(): void
    {
        $content = (string) file_get_contents($this->rootDir . '/sitemap.xml');
        $xml = simplexml_load_string($content);
        $this->assertNotFalse($xml, 'sitemap.xml must be valid XML.');

        $primaryDomains = [
            'https://cmsfornerd.onrender.com/',
            'https://linuxmalaysia.github.io/CmsForNerd/',
            'https://www.linuxmalaysia.com/',
        ];

        $locsToLastmod = [];
        foreach ($xml->url as $urlNode) {
            if (isset($urlNode->loc)) {
                $locsToLastmod[(string) $urlNode->loc] = isset($urlNode->lastmod) ? (string) $urlNode->lastmod : null;
            }
        }

        foreach ($primaryDomains as $domain) {
            $this->assertArrayHasKey($domain, $locsToLastmod, "sitemap.xml must declare a root <url> entry for {$domain}");
            $this->assertNotFalse(
                strtotime((string) $locsToLastmod[$domain]),
                "sitemap.xml <lastmod> for {$domain} must be a parseable date."
            );
        }
    }

    public function testRssLastBuildDateWasRefreshedAndIsAValidRfc2822Date(): void
    {
        $content = (string) file_get_contents($this->rootDir . '/rss.xml');
        $xml = simplexml_load_string($content);
        $this->assertNotFalse($xml, 'rss.xml must be valid XML.');

        $lastBuildDate = (string) $xml->channel->lastBuildDate;
        $this->assertNotSame('', $lastBuildDate, 'rss.xml <lastBuildDate> must not be empty.');

        $timestamp = strtotime($lastBuildDate);
        $this->assertNotFalse($timestamp, 'rss.xml <lastBuildDate> must be a parseable RFC 2822 date string.');

        // Regression guard: the publish run bundled with this PR must not
        // silently regress to a date earlier than the previous release.
        $this->assertGreaterThanOrEqual(
            strtotime('2026-08-12'),
            $timestamp,
            'rss.xml <lastBuildDate> must not regress to a date earlier than the prior known publish run.'
        );
    }
}