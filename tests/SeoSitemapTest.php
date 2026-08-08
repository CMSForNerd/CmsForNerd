<?php

/**
 * ==========================================================================
 * FILE: tests/SeoSitemapTest.php
 * ROLE: Integration Test Suite for SEO and Sitemap Generation (v4.3.1)
 * LICENSE: GNU General Public License v3.0
 * ==========================================================================
 */

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * SeoSitemapTest
 *
 * Validates that all programmatically generated SEO files exist, are
 * well-formed, compliant with web standards, and free of broken URLs.
 */
class SeoSitemapTest extends TestCase
{
    private string $rootDir;

    protected function setUp(): void
    {
        $this->rootDir = realpath(__DIR__ . '/../');
    }

    /**
     * Verifies that sitemap.txt exists, is non-empty, contains valid URLs,
     * and correctly covers all requested channels.
     */
    public function testSeoSitemapTxtExistsAndIsCorrect(): void
    {
        $file = $this->rootDir . '/sitemap.txt';
        $this->assertFileExists($file, "sitemap.txt MUST exist in root directory.");

        $content = file_get_contents($file);
        $this->assertNotEmpty($content, "sitemap.txt MUST NOT be empty.");

        $urls = array_filter(array_map('trim', explode("\n", $content)));
        $this->assertNotEmpty($urls, "sitemap.txt MUST contain at least one URL.");

        // Check for duplicates
        $uniqueUrls = array_unique($urls);
        $this->assertCount(count($urls), $uniqueUrls, "sitemap.txt MUST NOT contain duplicate URLs.");

        // Validate each URL
        foreach ($urls as $url) {
            $this->assertStringStartsWith('https://', $url, "URL '{$url}' MUST use secure https protocol.");
            $this->assertFalse(filter_var($url, FILTER_VALIDATE_URL) === false, "URL '{$url}' MUST be valid.");

            // Standardize checks across publishing targets
            if (str_contains($url, 'github.io')) {
                // Pages deployed to GitHub Pages must have been translated to .html (excluding sitemap, robots, etc)
                if (pathinfo(parse_url($url, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION) !== '') {
                    $this->assertStringNotContainsString('.php', $url, "GitHub Pages URL '{$url}' MUST NOT contain .php extension.");
                }
            }

            if (str_contains($url, 'gitbook.io')) {
                // GitBook documents shouldn't end in .md
                $this->assertStringNotContainsString('.md', $url, "GitBook URL '{$url}' MUST NOT end in .md.");
            }
        }

        // Verify the presence of all 4 major platforms
        $hasGitBook = false;
        $hasGitHubPages = false;
        $hasCustomDomain = false;
        $hasRender = false;

        foreach ($urls as $url) {
            if (str_contains($url, 'gitbook.io')) {
                $hasGitBook = true;
            }
            if (str_contains($url, 'github.io')) {
                $hasGitHubPages = true;
            }
            if (str_contains($url, 'linuxmalaysia.com')) {
                $hasCustomDomain = true;
            }
            if (str_contains($url, 'onrender.com')) {
                $hasRender = true;
            }
        }

        $this->assertTrue($hasGitBook, "sitemap.txt MUST contain GitBook URLs.");
        $this->assertTrue($hasGitHubPages, "sitemap.txt MUST contain GitHub Pages URLs.");
        $this->assertTrue($hasCustomDomain, "sitemap.txt MUST contain Custom Domain URLs.");
        $this->assertTrue($hasRender, "sitemap.txt MUST contain Render publishing URLs.");
    }

    /**
     * Verifies that sitemap.xml exists, is valid XML, and complies with sitemap.org protocol.
     */
    public function testSeoSitemapXmlExistsAndIsCorrect(): void
    {
        $file = $this->rootDir . '/sitemap.xml';
        $this->assertFileExists($file, "sitemap.xml MUST exist in root directory.");

        $content = file_get_contents($file);
        $this->assertNotEmpty($content, "sitemap.xml MUST NOT be empty.");

        // Parse XML
        $xml = simplexml_load_string($content);
        $this->assertNotFalse($xml, "sitemap.xml MUST be a valid XML document.");
        $this->assertEquals('urlset', $xml->getName(), "Root element of sitemap.xml MUST be urlset.");

        // Check namespaces
        $namespaces = $xml->getDocNamespaces();
        $this->assertArrayHasKey('', $namespaces, "Sitemap XML MUST declare a default namespace.");
        $this->assertEquals('http://www.sitemaps.org/schemas/sitemap/0.9', $namespaces[''], "Sitemap namespace MUST be sitemaps.org v0.9.");

        // Check urls inside xml
        $this->assertGreaterThan(0, $xml->url->count(), "sitemap.xml MUST contain at least one <url> element.");

        foreach ($xml->url as $urlNode) {
            $this->assertNotNull($urlNode->loc, "Each <url> node MUST contain a <loc> element.");
            $loc = (string)$urlNode->loc;
            $this->assertStringStartsWith('https://', $loc, "Location URL MUST start with https://.");

            if (isset($urlNode->priority)) {
                $priority = (float)$urlNode->priority;
                $this->assertGreaterThanOrEqual(0.0, $priority, "Priority MUST be >= 0.0.");
                $this->assertLessThanOrEqual(1.0, $priority, "Priority MUST be <= 1.0.");
            }

            if (isset($urlNode->changefreq)) {
                $freq = (string)$urlNode->changefreq;
                $this->assertContains($freq, ['always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'], "Change frequency MUST be valid.");
            }
        }
    }

    /**
     * Verifies that rss.xml exists, is valid XML, and matches RSS 2.0 specs.
     */
    public function testSeoRssXmlExistsAndIsCorrect(): void
    {
        $file = $this->rootDir . '/rss.xml';
        $this->assertFileExists($file, "rss.xml MUST exist in root directory.");

        $content = file_get_contents($file);
        $this->assertNotEmpty($content, "rss.xml MUST NOT be empty.");

        $xml = simplexml_load_string($content);
        $this->assertNotFalse($xml, "rss.xml MUST be a valid XML document.");
        $this->assertEquals('rss', $xml->getName(), "Root element MUST be rss.");
        $this->assertEquals('2.0', (string)$xml['version'], "RSS version MUST be 2.0.");
        $this->assertNotNull($xml->channel, "RSS MUST contain a <channel> element.");
    }

    /**
     * Verifies that ror.xml exists, is valid XML.
     */
    public function testSeoRorXmlExistsAndIsCorrect(): void
    {
        $file = $this->rootDir . '/ror.xml';
        $this->assertFileExists($file, "ror.xml MUST exist.");

        $content = file_get_contents($file);
        $this->assertNotEmpty($content, "ror.xml MUST NOT be empty.");

        $xml = simplexml_load_string($content);
        $this->assertNotFalse($xml, "ror.xml MUST be valid XML.");
    }

    /**
     * Verifies that schema-org.json exists, parses as valid JSON, and contains key author credentials.
     */
    public function testSeoSchemaOrgJsonExistsAndIsCorrect(): void
    {
        $file = $this->rootDir . '/schema-org.json';
        $this->assertFileExists($file, "schema-org.json MUST exist.");

        $content = file_get_contents($file);
        $this->assertNotEmpty($content, "schema-org.json MUST NOT be empty.");

        $data = json_decode($content, true);
        $this->assertIsArray($data, "schema-org.json MUST be valid JSON.");
        $this->assertEquals('https://schema.org', $data['@context'], "Schema context MUST be schema.org.");
        $this->assertEquals('WebSite', $data['@type'], "Schema type MUST be WebSite.");
        $this->assertEquals('Harisfazillah Jamel', $data['author']['name'], "Author MUST be Harisfazillah Jamel.");
        $this->assertContains('https://github.com/CMSForNerd/CmsForNerd', $data['sameAs'], "sameAs MUST contain GitHub link.");
    }
}
