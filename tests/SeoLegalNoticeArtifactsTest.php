<?php

/**
 * ==========================================================================
 * FILE: tests/SeoLegalNoticeArtifactsTest.php
 * ROLE: Regression suite for the Legal Notice page's SEO footprint.
 * Covers the tools/generate-seo-files.php autoloader fix and the generated
 * sitemap.txt, sitemap.xml, rss.xml, and ror.xml entries for legal-notice.php.
 * ==========================================================================
 */

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

final class SeoLegalNoticeArtifactsTest extends TestCase
{
    private string $rootDir;

    protected function setUp(): void
    {
        $this->rootDir = (string) realpath(__DIR__ . '/../');
    }

    // ---------------------------------------------------------------
    // tools/generate-seo-files.php
    // ---------------------------------------------------------------

    public function testGeneratorScriptLoadsComposerAutoloaderBeforeUsingSecurityUtils(): void
    {
        $path = $this->rootDir . '/tools/generate-seo-files.php';
        $this->assertFileExists($path);
        $content = (string) file_get_contents($path);

        $this->assertStringContainsString(
            "require_once __DIR__ . '/../vendor/autoload.php';",
            $content,
            'The generator must load the Composer autoloader so namespaced helpers resolve.'
        );

        $declarePosition = strpos($content, 'declare(strict_types=1);');
        $requirePosition = strpos($content, "require_once __DIR__ . '/../vendor/autoload.php';");
        $usagePosition = strpos($content, 'SecurityUtils::isValidPath');

        $this->assertNotFalse($declarePosition);
        $this->assertNotFalse($requirePosition);
        $this->assertNotFalse($usagePosition, 'Expected the script to call SecurityUtils::isValidPath().');

        $this->assertGreaterThan(
            $declarePosition,
            $requirePosition,
            'The autoloader require must come after the strict_types declaration.'
        );
        $this->assertLessThan(
            $usagePosition,
            $requirePosition,
            'The autoloader must be loaded before any \\CmsForNerd\\SecurityUtils usage, otherwise the class cannot be resolved.'
        );
    }

    public function testGeneratorScriptIsSyntacticallyValidPhp(): void
    {
        $this->assertPhpFileLintsCleanly($this->rootDir . '/tools/generate-seo-files.php');
    }

    // ---------------------------------------------------------------
    // sitemap.txt
    // ---------------------------------------------------------------

    public function testSitemapTxtListsLegalNoticeForAllThreePublishingTargets(): void
    {
        $path = $this->rootDir . '/sitemap.txt';
        $this->assertFileExists($path);
        $lines = array_values(array_filter(array_map('trim', explode("\n", (string) file_get_contents($path)))));

        $expectedUrls = [
            'https://cmsfornerd.onrender.com/legal-notice.php',
            'https://linuxmalaysia.github.io/CmsForNerd/legal-notice.html',
            'https://www.linuxmalaysia.com/legal-notice.php',
        ];

        foreach ($expectedUrls as $expectedUrl) {
            $this->assertContains($expectedUrl, $lines, "sitemap.txt must list {$expectedUrl}.");
        }
    }

    public function testSitemapTxtKeepsLegalNoticeInAlphabeticalOrderBetweenLabModule6AndLinuxSetup(): void
    {
        $path = $this->rootDir . '/sitemap.txt';
        $lines = array_values(array_filter(array_map('trim', explode("\n", (string) file_get_contents($path)))));

        $legalIndex = array_search('https://www.linuxmalaysia.com/legal-notice.php', $lines, true);
        $labModule6Index = array_search('https://www.linuxmalaysia.com/lab-module6.php', $lines, true);
        $linuxSetupIndex = array_search('https://www.linuxmalaysia.com/linux-setup.php', $lines, true);

        $this->assertNotFalse($legalIndex, 'legal-notice.php entry must exist for the custom domain.');
        $this->assertNotFalse($labModule6Index);
        $this->assertNotFalse($linuxSetupIndex);

        $this->assertGreaterThan($labModule6Index, $legalIndex, 'legal-notice.php must sort after lab-module6.php.');
        $this->assertLessThan($linuxSetupIndex, $legalIndex, 'legal-notice.php must sort before linux-setup.php.');
    }

    // ---------------------------------------------------------------
    // sitemap.xml
    // ---------------------------------------------------------------

    public function testSitemapXmlContainsLegalNoticeUrlNodesWithExpectedPriorityAndFrequency(): void
    {
        $path = $this->rootDir . '/sitemap.xml';
        $this->assertFileExists($path);
        $xml = simplexml_load_string((string) file_get_contents($path));
        $this->assertNotFalse($xml);

        $matches = [];
        foreach ($xml->url as $urlNode) {
            $loc = (string) $urlNode->loc;
            if (str_contains($loc, 'legal-notice')) {
                $matches[] = $urlNode;
            }
        }

        $this->assertCount(3, $matches, 'Expected exactly one legal-notice <url> node per publishing target.');

        foreach ($matches as $urlNode) {
            $loc = (string) $urlNode->loc;
            $this->assertSame('weekly', (string) $urlNode->changefreq, "Unexpected changefreq for {$loc}.");
            $this->assertSame('0.5', (string) $urlNode->priority, "Unexpected priority for {$loc}.");
            $this->assertMatchesRegularExpression(
                '/^\d{4}-\d{2}-\d{2}$/',
                (string) $urlNode->lastmod,
                "lastmod for {$loc} must be a YYYY-MM-DD date."
            );
        }
    }

    // ---------------------------------------------------------------
    // rss.xml
    // ---------------------------------------------------------------

    public function testRssXmlContainsLegalNoticeItemWithExpectedFields(): void
    {
        $path = $this->rootDir . '/rss.xml';
        $this->assertFileExists($path);
        $xml = simplexml_load_string((string) file_get_contents($path));
        $this->assertNotFalse($xml);

        $item = $this->findRssItemByTitle($xml, 'Legal notice');
        $this->assertNotNull($item, 'rss.xml must contain a <item> for the Legal notice page.');

        $this->assertSame('https://www.linuxmalaysia.com/legal-notice.php', (string) $item->link);
        $this->assertSame('https://www.linuxmalaysia.com/legal-notice.php', (string) $item->guid);
        $this->assertSame('true', (string) $item->guid['isPermaLink']);
        $this->assertStringContainsString('Legal notice module', (string) $item->description);
        $this->assertNotEmpty((string) $item->pubDate, 'The Legal notice RSS item must have a pubDate.');
    }

    // ---------------------------------------------------------------
    // ror.xml
    // ---------------------------------------------------------------

    public function testRorXmlContainsLegalNoticeResourceItem(): void
    {
        $path = $this->rootDir . '/ror.xml';
        $this->assertFileExists($path);
        $xml = simplexml_load_string((string) file_get_contents($path));
        $this->assertNotFalse($xml);

        $namespaces = $xml->getDocNamespaces();
        $this->assertArrayHasKey('ror', $namespaces, 'ror.xml must declare the ror namespace.');

        $item = null;
        foreach ($xml->channel->item as $candidate) {
            if ((string) $candidate->title === 'Legal notice') {
                $item = $candidate;
                break;
            }
        }

        $this->assertNotNull($item, 'ror.xml must contain an <item> for the Legal notice page.');
        $this->assertSame('https://www.linuxmalaysia.com/legal-notice.php', (string) $item->link);

        $rorChildren = $item->children($namespaces['ror']);
        $this->assertSame('resource', (string) $rorChildren->type);
        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}$/', (string) $rorChildren->updated);
    }

    // ---------------------------------------------------------------
    // Cross-artifact consistency
    // ---------------------------------------------------------------

    public function testAllSeoArtifactsAgreeOnTheLegalNoticeCanonicalUrl(): void
    {
        $canonicalUrl = 'https://www.linuxmalaysia.com/legal-notice.php';

        $sitemapXml = simplexml_load_string((string) file_get_contents($this->rootDir . '/sitemap.xml'));
        $rssXml = simplexml_load_string((string) file_get_contents($this->rootDir . '/rss.xml'));
        $rorXml = simplexml_load_string((string) file_get_contents($this->rootDir . '/ror.xml'));
        $this->assertNotFalse($sitemapXml);
        $this->assertNotFalse($rssXml);
        $this->assertNotFalse($rorXml);

        $sitemapHasCanonical = false;
        foreach ($sitemapXml->url as $urlNode) {
            if ((string) $urlNode->loc === $canonicalUrl) {
                $sitemapHasCanonical = true;
                break;
            }
        }
        $this->assertTrue($sitemapHasCanonical, 'sitemap.xml must include the canonical legal-notice.php URL.');

        $rssItem = $this->findRssItemByTitle($rssXml, 'Legal notice');
        $this->assertNotNull($rssItem);
        $this->assertSame($canonicalUrl, (string) $rssItem->link);

        $rorHasCanonical = false;
        foreach ($rorXml->channel->item as $candidate) {
            if ((string) $candidate->title === 'Legal notice' && (string) $candidate->link === $canonicalUrl) {
                $rorHasCanonical = true;
                break;
            }
        }
        $this->assertTrue($rorHasCanonical, 'ror.xml must include the canonical legal-notice.php URL.');
    }

    // ---------------------------------------------------------------
    // Helpers
    // ---------------------------------------------------------------

    private function findRssItemByTitle(\SimpleXMLElement $rss, string $title): ?\SimpleXMLElement
    {
        foreach ($rss->channel->item as $item) {
            if ((string) $item->title === $title) {
                return $item;
            }
        }

        return null;
    }

    private function skipIfExecUnavailable(): void
    {
        if (!function_exists('exec')) {
            self::markTestSkipped('The exec() function is unavailable in this environment.');
        }

        $disabled = array_map('trim', explode(',', (string) ini_get('disable_functions')));
        if (in_array('exec', $disabled, true)) {
            self::markTestSkipped('exec() has been disabled via php.ini disable_functions.');
        }
    }

    private function assertPhpFileLintsCleanly(string $path): void
    {
        $this->skipIfExecUnavailable();

        $output = [];
        $exitCode = 0;
        exec(escapeshellarg(PHP_BINARY) . ' -l ' . escapeshellarg($path) . ' 2>&1', $output, $exitCode);

        $this->assertSame(
            0,
            $exitCode,
            "'{$path}' failed 'php -l' syntax validation: " . implode("\n", $output)
        );
    }
}