<?php
declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * UserManualDocsFrontmatterTest
 *
 * The repository-wide `MarkdownOkfComplianceTest` only asserts that every
 * Markdown file *has* the required OKF v0.1 frontmatter keys. This test
 * complements it by pinning the *exact values* of the frontmatter emitted
 * for the batch of Diátaxis documentation pages introduced by this PR
 * (docs/tutorials, docs/how-to, docs/reference, docs/explanation, and the
 * docs/user-manual/README.md hub page), guarding against accidental
 * regressions such as swapped titles, wrong quadrant "type" values, or
 * mismatched "resource" paths.
 */
final class UserManualDocsFrontmatterTest extends TestCase
{
    /**
     * Maps each new documentation file (relative to the repository root) to
     * its expected OKF frontmatter metadata.
     *
     * @return array<string, array{type: string, title: string, description: string, topics: array<int, string>}>
     */
    private function expectedDocs(): array
    {
        return [
            'docs/tutorials/quickstart-guide.md' => [
                'type' => 'tutorial',
                'title' => '🚀 Quickstart Guide: Install and Run CmsForNerd Locally',
                'description' => 'Clone the repo, install Composer dependencies, verify with lab-check, and have a working CmsForNerd site running locally in 5 minutes.',
                'topics' => ['quickstart', 'local-setup', 'composer', 'php84', 'tutorial'],
            ],
            'docs/tutorials/local-almalinux10-wsl2-podman-setup.md' => [
                'type' => 'tutorial',
                'title' => '🐧 Local Setup Guide: WSL2 + AlmaLinux 10 + Podman for CmsForNerd',
                'description' => 'Step-by-step tutorial to configure Windows Subsystem for Linux 2 with AlmaLinux 10 and Rootless Podman 5+ for CmsForNerd local development.',
                'topics' => ['wsl2', 'almalinux10', 'podman', 'containers', 'local-environment'],
            ],
            'docs/how-to/install-windows-herd.md' => [
                'type' => 'guide',
                'title' => '🛠️ How-To: Install CmsForNerd on Windows using Laravel Herd',
                'description' => 'Set up CmsForNerd v4 on Windows 10/11 using Laravel Herd for one-click PHP 8.4, Composer, and local flat-file development.',
                'topics' => ['windows', 'laravel-herd', 'php84', 'installation', 'how-to'],
            ],
            'docs/how-to/install-linux-native.md' => [
                'type' => 'guide',
                'title' => '🛠️ How-To: Native Linux Installation (Ubuntu, Debian, AlmaLinux)',
                'description' => 'Install CmsForNerd on Ubuntu, Debian, or AlmaLinux using PHP 8.4 from Ondřej Surý or Remi, with Nginx or Apache and correct file permissions.',
                'topics' => ['linux', 'nginx', 'apache', 'php84', 'ubuntu', 'almalinux'],
            ],
            'docs/how-to/run-podman-docker-containers.md' => [
                'type' => 'guide',
                'title' => '🛠️ How-To: Run CmsForNerd in Containers with Podman or Docker',
                'description' => 'Build and run CmsForNerd locally or in production using Docker or rootless Podman — utilizing Containerfile and Dockerfile coexistence.',
                'topics' => ['containers', 'podman', 'docker', 'containerfile', 'dockerfile'],
            ],
            'docs/how-to/deploy-cloud-render-github-pages.md' => [
                'type' => 'guide',
                'title' => '🛠️ How-To: Deploy CmsForNerd to Render.com and GitHub Pages',
                'description' => 'Deploy CmsForNerd to Render.com with a one-click render.yaml blueprint, or compile flat HTML static files for hosting on GitHub Pages.',
                'topics' => ['cloud-deployment', 'render', 'github-pages', 'static-baking', 'deployment'],
            ],
            'docs/how-to/create-manage-pages.md' => [
                'type' => 'guide',
                'title' => '🛠️ How-To: Creating Pages with the Pair Logic System',
                'description' => 'Learn how to create a new page in CmsForNerd using the Pair Logic pattern — pairing a PHP controller with an HTML body fragment.',
                'topics' => ['creating-pages', 'pair-logic', 'controllers', 'content-body', 'how-to'],
            ],
            'docs/how-to/customize-themes-navigation.md' => [
                'type' => 'guide',
                'title' => '🛠️ How-To: Customize Themes, Styling, and Site Navigation',
                'description' => 'Learn how to customize the CmsForNerd theme, update CSS, configure header and footer fragments, and manage dynamic navigation menus.',
                'topics' => ['theming', 'navigation', 'layout', 'css', 'amp'],
            ],
            'docs/how-to/manage-content-flatfiles.md' => [
                'type' => 'guide',
                'title' => '🛠️ How-To: Manage Flat-File Content and Git Workflows',
                'description' => 'Learn how CmsForNerd manages content as flat HTML files in contents/ with zero database overhead and full Git version control.',
                'topics' => ['content-management', 'flat-files', 'git', 'workflow', 'html'],
            ],
            'docs/how-to/configure-security-csrf-csp.md' => [
                'type' => 'guide',
                'title' => '🛠️ How-To: Configure Security, CSP Nonces, CSRF, and Bot Defenses',
                'description' => 'How to configure per-request CSP nonces, CSRF form tokens, and Cloudflare Turnstile bot protection in CmsForNerd.',
                'topics' => ['security', 'csp-nonce', 'csrf', 'bot-protection', 'turnstile'],
            ],
            'docs/how-to/configure-seo-sitemaps.md' => [
                'type' => 'guide',
                'title' => '🛠️ How-To: Configure SEO, Sitemaps, RSS, and Structured Data',
                'description' => 'How CmsForNerd handles SEO: dynamic XML/TXT sitemaps, RSS feeds, ROR XML, Schema.org JSON-LD, and per-page metadata.',
                'topics' => ['seo', 'sitemap', 'rss', 'schema-org', 'structured-data'],
            ],
            'docs/how-to/run-tests-static-analysis.md' => [
                'type' => 'guide',
                'title' => '🛠️ How-To: Run Unit Tests, Static Analysis, and composer lab-check',
                'description' => 'How to run Pest PHP unit tests, PHPStan Level 8 static analysis, and composer lab-check compliance audits for CmsForNerd.',
                'topics' => ['testing', 'pest', 'phpstan', 'lab-check', 'quality-assurance'],
            ],
            'docs/reference/system-requirements.md' => [
                'type' => 'reference',
                'title' => '📋 Reference: System Requirements for CmsForNerd v4.3.0',
                'description' => 'Review required PHP versions, extensions, supported web servers, operating systems, and optional packages needed for CmsForNerd.',
                'topics' => ['requirements', 'php84', 'web-servers', 'extensions', 'reference'],
            ],
            'docs/reference/cms-context-api.md' => [
                'type' => 'reference',
                'title' => '📋 API Reference: CmsContext Class and Factory Method',
                'description' => 'Complete API specification for the immutable CmsContext object carrying page metadata and nonces through the CmsForNerd render pipeline.',
                'topics' => ['cms-context', 'api-reference', 'immutability', 'factory-method', 'php84'],
            ],
            'docs/reference/registry-api.md' => [
                'type' => 'reference',
                'title' => '📋 API Reference: Registry Class (Zero-Global State)',
                'description' => 'Complete API specification for the Registry static key-value store that replaces PHP global variables in CmsForNerd.',
                'topics' => ['registry', 'zero-global', 'state-management', 'api-reference', 'php84'],
            ],
            'docs/reference/security-utils-api.md' => [
                'type' => 'reference',
                'title' => '📋 API Reference: SecurityUtils Class',
                'description' => 'Complete reference for SecurityUtils providing XSS escaping, CSRF protection, CSP nonces, session hardening, and page discovery.',
                'topics' => ['security-utils', 'xss', 'csrf', 'csp-nonce', 'api-reference'],
            ],
            'docs/reference/performance-utils-api.md' => [
                'type' => 'reference',
                'title' => '📋 API Reference: PerformanceUtils Class',
                'description' => 'Complete reference for PerformanceUtils, the 3-tier caching engine (Memory, APCu, Disk), max mtime calculator, and page baking utilities.',
                'topics' => ['performance-utils', 'caching', 'apcu', 'etag', 'api-reference'],
            ],
            'docs/reference/configuration-and-composer-scripts.md' => [
                'type' => 'reference',
                'title' => '📋 Reference: Configuration Files, .htaccess, and Composer Scripts',
                'description' => 'Reference guide for global-control.inc.php settings, Apache .htaccess rules, and built-in Composer automation scripts.',
                'topics' => ['configuration', 'htaccess', 'composer-scripts', 'global-control', 'reference'],
            ],
            'docs/reference/release-notes-changelog.md' => [
                'type' => 'reference',
                'title' => '📋 Reference: CmsForNerd Release Notes and Changelog',
                'description' => 'Full release history for CmsForNerd v4, covering architectural modernizations, security enhancements, and feature additions.',
                'topics' => ['changelog', 'release-notes', 'version-history', 'php84', 'modernization'],
            ],
            'docs/explanation/introduction-and-philosophy.md' => [
                'type' => 'explanation',
                'title' => '🧠 Explanation: Introduction to CmsForNerd and Design Philosophy',
                'description' => 'Understand why CmsForNerd exists, its database-free flat-file philosophy, and how it differs from traditional database-driven CMS platforms.',
                'topics' => ['introduction', 'philosophy', 'flat-file', 'database-free', 'architecture'],
            ],
            'docs/explanation/zero-global-architecture-pair-logic.md' => [
                'type' => 'explanation',
                'title' => '🧠 Explanation: Zero-Global Architecture & Pair Logic Pattern',
                'description' => 'Understand how CmsForNerd routes requests through its Zero-Global PHP 8.4 pipeline using the Pair Logic controller-body design.',
                'topics' => ['zero-global', 'pair-logic', 'architecture', 'cms-context', 'routing'],
            ],
            'docs/explanation/dual-view-amp-engine.md' => [
                'type' => 'explanation',
                'title' => '🧠 Explanation: Dual-View Rendering Engine & Google AMP Integration',
                'description' => 'Discover how CmsForNerd automatically serves standard HTML5 and validated Google AMP views from a single controller and content body.',
                'topics' => ['dual-view', 'amp', 'mobile-optimization', 'pager', 'rendering'],
            ],
            'docs/explanation/security-hardening-owasp.md' => [
                'type' => 'explanation',
                'title' => '🧠 Explanation: OWASP Top 10 Security Hardening',
                'description' => "A tour of CmsForNerd's built-in OWASP defenses: XSS prevention, CSRF tokens, CSP nonces, secure sessions, and hardened HTTP headers.",
                'topics' => ['security', 'owasp', 'csp-nonce', 'csrf', 'hardening'],
            ],
            'docs/explanation/three-tier-caching-pwa.md' => [
                'type' => 'explanation',
                'title' => '🧠 Explanation: Three-Tier Caching Pipeline & PWA Architecture',
                'description' => "Understand CmsForNerd's three-tier caching pipeline (Memory, APCu, Disk), ETag/304 handling, and Progressive Web App offline capabilities.",
                'topics' => ['caching', 'performance', 'pwa', 'apcu', 'etag'],
            ],
            'docs/user-manual/README.md' => [
                'type' => 'documentation',
                'title' => '📖 CmsForNerd Local User Manual',
                'description' => 'Master user manual for installing, configuring, running, and developing CmsForNerd locally using Diátaxis framework.',
                'topics' => ['user-manual', 'installation', 'diataxis', 'local-development', 'cmsfornerd'],
            ],
        ];
    }

    /**
     * Verifies that every new documentation file declares the exact expected
     * OKF frontmatter values (okf_version, type, title, description,
     * resource, timestamp, and topics).
     */
    public function testNewDocumentationFrontmatterMatchesExpectedMetadata(): void
    {
        $rootDir = dirname(__DIR__);

        foreach ($this->expectedDocs() as $relativePath => $expected) {
            $absolutePath = $rootDir . '/' . $relativePath;
            $this->assertFileExists($absolutePath, "Expected documentation file missing: {$relativePath}");

            $frontmatter = $this->parseFrontmatter((string) file_get_contents($absolutePath), $relativePath);

            $this->assertSame('0.1', $frontmatter['okf_version'], "{$relativePath}: unexpected okf_version.");
            $this->assertSame($expected['type'], $frontmatter['type'], "{$relativePath}: unexpected type.");
            $this->assertSame($expected['title'], $frontmatter['title'], "{$relativePath}: unexpected title.");
            $this->assertSame($expected['description'], $frontmatter['description'], "{$relativePath}: unexpected description.");
            $this->assertSame(
                'file:///' . $relativePath,
                $frontmatter['resource'],
                "{$relativePath}: resource must be a file:/// URI matching its own repository-relative path."
            );
            $this->assertSame(
                '2026-08-15T12:00:00Z',
                $frontmatter['timestamp'],
                "{$relativePath}: unexpected timestamp."
            );
            $this->assertSame($expected['topics'], $frontmatter['topics'], "{$relativePath}: unexpected topics list.");
        }
    }

    /**
     * Verifies that the visible Markdown body of every new documentation
     * file opens with an H1 heading matching its frontmatter title, and
     * closes with the standard DSOM sovereignty/licensing footer.
     */
    public function testNewDocumentationBodyHeadingAndFooterConventions(): void
    {
        $rootDir = dirname(__DIR__);

        foreach (array_keys($this->expectedDocs()) as $relativePath) {
            $absolutePath = $rootDir . '/' . $relativePath;
            $content = (string) file_get_contents($absolutePath);
            $expected = $this->expectedDocs()[$relativePath];

            $this->assertStringContainsString(
                "\n# {$expected['title']}\n",
                $content,
                "{$relativePath}: body must contain an H1 heading matching the frontmatter title."
            );

            $this->assertStringContainsString(
                'Harisfazillah Jamel (LinuxMalaysia)',
                $content,
                "{$relativePath}: footer must credit the author."
            );
            $this->assertStringContainsString(
                'GNU General Public License v3.0',
                $content,
                "{$relativePath}: footer must declare the GPL v3.0 license."
            );
        }
    }

    /**
     * Negative/edge case: ensures none of the newly added documentation
     * files were left with obvious placeholder text or an empty topics
     * list, which would silently defeat the OKF discovery/indexing system.
     */
    public function testNewDocumentationHasNoPlaceholderContentOrEmptyTopics(): void
    {
        $rootDir = dirname(__DIR__);

        foreach ($this->expectedDocs() as $relativePath => $expected) {
            $absolutePath = $rootDir . '/' . $relativePath;
            $content = (string) file_get_contents($absolutePath);

            $this->assertNotEmpty($expected['topics'], "{$relativePath}: topics list must not be empty.");
            $this->assertStringNotContainsStringIgnoringCase('lorem ipsum', $content, "{$relativePath}: must not contain placeholder text.");
            $this->assertStringNotContainsStringIgnoringCase('TODO:', $content, "{$relativePath}: must not contain unresolved TODO markers.");
        }
    }

    /**
     * Parses the leading YAML-style frontmatter block of a Markdown file
     * into a small associative structure covering the OKF keys asserted by
     * this test suite.
     *
     * @return array{okf_version: string, type: string, title: string, description: string, resource: string, timestamp: string, topics: array<int, string>}
     */
    private function parseFrontmatter(string $content, string $relativePath): array
    {
        $this->assertStringStartsWith('---', $content, "{$relativePath}: must start with a YAML frontmatter delimiter.");

        $closingPos = strpos($content, "\n---", 3);
        $this->assertNotFalse($closingPos, "{$relativePath}: must contain a closing YAML frontmatter delimiter.");

        $block = substr($content, 0, $closingPos);
        $lines = explode("\n", $block);

        $result = [
            'okf_version' => '',
            'type' => '',
            'title' => '',
            'description' => '',
            'resource' => '',
            'timestamp' => '',
            'topics' => [],
        ];

        foreach ($lines as $line) {
            if (!preg_match('/^([a-zA-Z0-9_\-]+)\s*:\s*(.*)$/', $line, $matches)) {
                continue;
            }

            $key = $matches[1];
            $value = trim($matches[2]);

            if (!array_key_exists($key, $result)) {
                continue;
            }

            if ($key === 'topics') {
                $inner = trim($value, "[] \t");
                $result['topics'] = $inner === '' ? [] : array_map('trim', explode(',', $inner));
                continue;
            }

            // Strip a single layer of surrounding double quotes, if present.
            if (str_starts_with($value, '"') && str_ends_with($value, '"') && strlen($value) >= 2) {
                $value = substr($value, 1, -1);
            }

            $result[$key] = $value;
        }

        return $result;
    }
}