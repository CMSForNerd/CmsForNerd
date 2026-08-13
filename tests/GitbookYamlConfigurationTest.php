<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Validates the GitBook configuration file (.gitbook.yaml).
 *
 * The PR under test adds an explicit `version` key and descriptive comments
 * on top of the pre-existing `root`, `structure`, `projects` and `redirects`
 * blocks. Since no YAML parser is available as a project dependency, the
 * file is validated via targeted string/regex assertions, matching the
 * conventions used by the other configuration tests in this suite (e.g.
 * PodmanComposeYamlTest, SonarConfigurationTest).
 */
final class GitbookYamlConfigurationTest extends TestCase
{
    private string $gitbookYamlPath;
    private string $content;

    protected function setUp(): void
    {
        $this->gitbookYamlPath = dirname(__DIR__) . '/.gitbook.yaml';
        $this->content = (string) file_get_contents($this->gitbookYamlPath);
    }

    public function testGitbookYamlFileExists(): void
    {
        $this->assertFileExists($this->gitbookYamlPath);
    }

    public function testDeclaresAVersionKeySetToOnePointZero(): void
    {
        $this->assertMatchesRegularExpression(
            '/^version:\s*"1\.0\.0"\s*$/m',
            $this->content,
            'Configuration must declare version: "1.0.0" at the top level.'
        );
    }

    public function testVersionKeyIsDocumentedWithAComment(): void
    {
        $this->assertStringContainsString(
            '# GitBook configuration version',
            $this->content,
            'The new version key must be documented with an explanatory comment.'
        );
    }

    public function testRootDirectiveIsPreservedAndDocumented(): void
    {
        $this->assertMatchesRegularExpression(
            '/^root:\s*\.\/\s*$/m',
            $this->content,
            'root: ./ must be preserved from the original configuration.'
        );
        $this->assertStringContainsString(
            '# Defines the root directory of your content',
            $this->content,
            'The root directive must be documented with an explanatory comment.'
        );
    }

    public function testStructureBlockDeclaresReadmeAndSummary(): void
    {
        $this->assertMatchesRegularExpression('/^structure:\s*$/m', $this->content);
        $this->assertMatchesRegularExpression('/^\s+readme:\s*README\.md\s*$/m', $this->content);
        $this->assertMatchesRegularExpression('/^\s+summary:\s*SUMMARY\.md\s*$/m', $this->content);
        $this->assertStringContainsString(
            '# Maps core structural files',
            $this->content,
            'The structure block must be documented with an explanatory comment.'
        );
    }

    public function testProjectsIgnoreListStillExcludesPhpAndVendorContent(): void
    {
        $this->assertStringContainsString('projects:', $this->content);
        $this->assertStringContainsString('vendor/*', $this->content);
        $this->assertStringContainsString('includes/*', $this->content);
        $this->assertStringContainsString('themes/*', $this->content);
        $this->assertStringContainsString('contents/*', $this->content);
        $this->assertStringContainsString('"*.php"', $this->content);
    }

    public function testRedirectsBlockMapsLabManualToLabGuide(): void
    {
        $this->assertMatchesRegularExpression(
            '/^redirects:\s*$/m',
            $this->content,
            'Configuration must retain the redirects block.'
        );
        $this->assertStringContainsString(
            '/lab-manual: /LAB-GUIDE.md',
            $this->content,
            'The /lab-manual redirect to /LAB-GUIDE.md must be preserved.'
        );
    }

    public function testTopLevelKeysAppearInTheExpectedOrder(): void
    {
        $versionPos = strpos($this->content, 'version:');
        $rootPos = strpos($this->content, 'root:');
        $structurePos = strpos($this->content, 'structure:');
        $projectsPos = strpos($this->content, 'projects:');
        $redirectsPos = strpos($this->content, 'redirects:');

        $this->assertNotFalse($versionPos, 'version key must be present.');
        $this->assertNotFalse($rootPos, 'root key must be present.');
        $this->assertNotFalse($structurePos, 'structure key must be present.');
        $this->assertNotFalse($projectsPos, 'projects key must be present.');
        $this->assertNotFalse($redirectsPos, 'redirects key must be present.');

        $this->assertLessThan($rootPos, $versionPos, 'version must appear before root.');
        $this->assertLessThan($structurePos, $rootPos, 'root must appear before structure.');
        $this->assertLessThan($projectsPos, $structurePos, 'structure must appear before projects.');
        $this->assertLessThan($redirectsPos, $projectsPos, 'projects must appear before redirects.');
    }

    public function testFileDoesNotContainRawTabsForYamlIndentation(): void
    {
        $this->assertStringNotContainsString(
            "\t",
            $this->content,
            '.gitbook.yaml must use spaces (not tabs) for indentation.'
        );
    }

    public function testVersionValueIsQuotedAsAString(): void
    {
        // Guard against a future edit accidentally turning "1.0.0" into an
        // unquoted YAML value, which some parsers could coerce unexpectedly.
        $this->assertMatchesRegularExpression('/version:\s*"[^"]+"/', $this->content);
    }
}