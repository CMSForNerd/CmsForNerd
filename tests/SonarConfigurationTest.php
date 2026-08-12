<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Validates the SonarCloud analysis configuration: the local
 * sonar-project.properties file and the equivalent flags passed to the
 * SonarCloud GitHub Action in .github/workflows/build.yml.
 *
 * Both places must exclude Dockerfile/Containerfile from analysis, since
 * they are infrastructure definitions rather than PHP source code.
 */
final class SonarConfigurationTest extends TestCase
{
    private string $sonarPropertiesPath;
    private string $buildWorkflowPath;

    protected function setUp(): void
    {
        $this->sonarPropertiesPath = dirname(__DIR__) . '/sonar-project.properties';
        $this->buildWorkflowPath = dirname(__DIR__) . '/.github/workflows/build.yml';
    }

    private function extractExclusionList(string $content, string $key): array
    {
        $matched = preg_match('/' . preg_quote($key, '/') . '=([^\r\n]+)/', $content, $matches);
        $this->assertSame(1, $matched, "Expected to find a single '{$key}=' entry.");

        return array_map('trim', explode(',', $matches[1]));
    }

    public function testSonarPropertiesFileExists(): void
    {
        $this->assertFileExists($this->sonarPropertiesPath);
    }

    public function testSonarPropertiesExcludesDockerfileAndContainerfile(): void
    {
        $content = file_get_contents($this->sonarPropertiesPath);
        $exclusions = $this->extractExclusionList($content, 'sonar.exclusions');

        $this->assertContains('**/Dockerfile', $exclusions);
        $this->assertContains('**/Containerfile', $exclusions);
    }

    public function testSonarPropertiesRetainsPreExistingExclusions(): void
    {
        $content = file_get_contents($this->sonarPropertiesPath);
        $exclusions = $this->extractExclusionList($content, 'sonar.exclusions');

        foreach (['**/*.css', '**/*.js', '**/*.html', '**/*.xml', '**/*.txt', '**/LICENSE', '**/README.md', '**/.git/**', 'tests/**'] as $expected) {
            $this->assertContains($expected, $exclusions, "Adding Dockerfile/Containerfile exclusions must not drop the pre-existing '{$expected}' exclusion.");
        }
    }

    public function testSonarPropertiesExclusionsMatchExactExpectedSetAndOrder(): void
    {
        $content = file_get_contents($this->sonarPropertiesPath);
        $exclusions = $this->extractExclusionList($content, 'sonar.exclusions');

        $this->assertSame(
            [
                '**/Dockerfile',
                '**/Containerfile',
                '**/*.css',
                '**/*.js',
                '**/*.html',
                '**/*.xml',
                '**/*.txt',
                '**/LICENSE',
                '**/README.md',
                '**/.git/**',
                'tests/**',
            ],
            $exclusions,
            'sonar.exclusions must contain exactly the Docker-aligned exclusion list, with Dockerfile/Containerfile leading the pre-existing entries.'
        );
    }

    public function testSonarPropertiesHasNoDuplicateExclusionEntries(): void
    {
        $content = file_get_contents($this->sonarPropertiesPath);
        $exclusions = $this->extractExclusionList($content, 'sonar.exclusions');

        $this->assertSame(
            count($exclusions),
            count(array_unique($exclusions)),
            'sonar.exclusions should not list the same pattern twice.'
        );
    }

    public function testSonarPropertiesDockerAndContainerfileExclusionsAppearExactlyOnce(): void
    {
        $content = file_get_contents($this->sonarPropertiesPath);
        $exclusions = $this->extractExclusionList($content, 'sonar.exclusions');

        $this->assertSame(
            1,
            count(array_filter($exclusions, static fn (string $entry): bool => $entry === '**/Dockerfile')),
            'The **/Dockerfile exclusion must appear exactly once.'
        );
        $this->assertSame(
            1,
            count(array_filter($exclusions, static fn (string $entry): bool => $entry === '**/Containerfile')),
            'The **/Containerfile exclusion must appear exactly once.'
        );
    }

    public function testSonarPropertiesPhpExclusionsUnaffected(): void
    {
        $content = file_get_contents($this->sonarPropertiesPath);
        $exclusions = $this->extractExclusionList($content, 'sonar.php.exclusions');

        $this->assertSame(['**/vendor/**', '**/tests/**', '**/node_modules/**'], $exclusions);
    }

    public function testSonarPropertiesCpdExclusionsUseBlanketDoubleStarSlashStarGlob(): void
    {
        $content = file_get_contents($this->sonarPropertiesPath);

        $matched = preg_match('/sonar\.cpd\.exclusions=([^\r\n]+)/', $content, $matches);
        $this->assertSame(1, $matched, 'Expected to find a single sonar.cpd.exclusions= entry.');

        $this->assertSame(
            '**/*',
            trim($matches[1]),
            'sonar.cpd.exclusions must be the blanket "**/*" glob, not a bare "**" or a partial list.'
        );
    }

    public function testSonarPropertiesCpdExclusionsNoLongerUseTheBareDoubleStarGlob(): void
    {
        $content = file_get_contents($this->sonarPropertiesPath);

        $this->assertDoesNotMatchRegularExpression(
            '/sonar\.cpd\.exclusions=\*\*\s*(\r?\n|$)/',
            $content,
            'Regression guard: sonar.cpd.exclusions must not regress to the bare "**" glob, which SonarCloud ' .
            'interprets differently from "**/*" for directory matching.'
        );
    }

    public function testSonarPropertiesCpdExclusionsCommentExplainsIntent(): void
    {
        $content = file_get_contents($this->sonarPropertiesPath);

        $this->assertStringContainsString(
            '# Copy-Paste Detection (CPD) Exclusions to avoid false positive duplications on tests and static views',
            $content
        );
    }

    public function testBuildWorkflowFileExists(): void
    {
        $this->assertFileExists($this->buildWorkflowPath);
    }

    public function testBuildWorkflowContainsDsomHeaderMetadataBlock(): void
    {
        $content = file_get_contents($this->buildWorkflowPath);

        $this->assertStringContainsString('Protocol    : Deep State of Mind (DSOM) For My AI Protocol', $content);
        $this->assertStringContainsString('Author      : Harisfazillah Jamel (LinuxMalaysia)', $content);
        $this->assertStringContainsString('License     : GNU General Public License v3.0', $content);
    }

    public function testBuildWorkflowHeaderIsCommentedOutAndDoesNotBreakYamlStructure(): void
    {
        $content = file_get_contents($this->buildWorkflowPath);

        // Every line of the header banner must be a YAML comment.
        $headerBlock = substr($content, 0, (int) strpos($content, 'name: Build'));
        foreach (array_filter(explode("\n", $headerBlock)) as $line) {
            $this->assertStringStartsWith('#', trim($line), 'Header banner lines must be YAML comments so they do not interfere with workflow parsing.');
        }

        $this->assertStringContainsString("\nname: Build\n", $content);
    }

    public function testBuildWorkflowSonarArgsExcludeDockerfileAndContainerfile(): void
    {
        $content = file_get_contents($this->buildWorkflowPath);
        $exclusions = $this->extractExclusionList($content, '-Dsonar.exclusions');

        $this->assertContains('**/Dockerfile', $exclusions);
        $this->assertContains('**/Containerfile', $exclusions);
    }

    public function testBuildWorkflowSonarExclusionsMatchPropertiesFileExclusions(): void
    {
        $workflowContent = file_get_contents($this->buildWorkflowPath);
        $propertiesContent = file_get_contents($this->sonarPropertiesPath);

        $workflowExclusions = $this->extractExclusionList($workflowContent, '-Dsonar.exclusions');
        $propertiesExclusions = $this->extractExclusionList($propertiesContent, 'sonar.exclusions');

        sort($workflowExclusions);
        sort($propertiesExclusions);

        $this->assertSame(
            $propertiesExclusions,
            $workflowExclusions,
            'The CI-driven SonarCloud scan and the local sonar-project.properties must exclude the same set of paths.'
        );
    }

    public function testBuildWorkflowCpdExclusionsMatchPropertiesFileCpdExclusions(): void
    {
        $workflowContent = file_get_contents($this->buildWorkflowPath);
        $propertiesContent = file_get_contents($this->sonarPropertiesPath);

        $workflowMatched = preg_match('/-Dsonar\.cpd\.exclusions=([^\r\n]+)/', $workflowContent, $workflowMatches);
        $propertiesMatched = preg_match('/sonar\.cpd\.exclusions=([^\r\n]+)/', $propertiesContent, $propertiesMatches);

        $this->assertSame(1, $workflowMatched, 'Expected a single -Dsonar.cpd.exclusions= entry in build.yml.');
        $this->assertSame(1, $propertiesMatched, 'Expected a single sonar.cpd.exclusions= entry in sonar-project.properties.');

        $this->assertSame(
            trim($propertiesMatches[1]),
            trim($workflowMatches[1]),
            'The CI-driven SonarCloud scan and the local sonar-project.properties must use the same sonar.cpd.exclusions glob.'
        );
    }

    public function testBuildWorkflowStillGatesSonarScanOnSecretPresence(): void
    {
        $content = file_get_contents($this->buildWorkflowPath);

        $this->assertMatchesRegularExpression(
            '/if:\s*\$\{\{\s*secrets\.SONAR_TOKEN\s*!=\s*\'\'\s*\}\}/',
            $content,
            'SonarCloud step must remain conditional on SONAR_TOKEN being configured, so forks without the secret do not fail the build.'
        );
    }

    public function testBuildWorkflowUsesTabFreeIndentation(): void
    {
        $content = file_get_contents($this->buildWorkflowPath);

        $this->assertStringNotContainsString("\t", $content, 'YAML workflow files must use spaces, not tabs, for indentation.');
    }
}
