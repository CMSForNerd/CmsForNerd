<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Validates the CmsForNerd v4.3.0 CI workflow updates:
 *
 * - .github/workflows/build.yml: the SonarCloud scan action was bumped from
 *   `SonarSource/sonarqube-scan-action@v7` to `@v8`, and the Copy-Paste
 *   Detection (CPD) exclusion list was collapsed from an explicit
 *   comma-separated list (`**\/tests/**,tests/**,offline.php,ujian-form.php,
 *   tools/sanity-check.php`) down to a single blanket **-slash-* glob pattern, meaning CPD
 *   analysis is now disabled across the entire project rather than only for
 *   a curated set of paths.
 * - .github/workflows/php.yml: `actions/cache` was bumped from `@v3` to
 *   `@v4`.
 *
 * These tests guard both the intended new state and regressions back to the
 * stale pinned versions/exclusion list.
 */
final class CiWorkflowVersionPinTest extends TestCase
{
    private string $buildWorkflowPath;
    private string $phpWorkflowPath;
    private string $buildWorkflowContent;
    private string $phpWorkflowContent;

    protected function setUp(): void
    {
        $root = dirname(__DIR__);

        $this->buildWorkflowPath = $root . '/.github/workflows/build.yml';
        $this->phpWorkflowPath = $root . '/.github/workflows/php.yml';

        $this->buildWorkflowContent = (string) file_get_contents($this->buildWorkflowPath);
        $this->phpWorkflowContent = (string) file_get_contents($this->phpWorkflowPath);
    }

    // ---------------------------------------------------------------
    // .github/workflows/build.yml
    // ---------------------------------------------------------------

    public function testBuildWorkflowFileExists(): void
    {
        $this->assertFileExists($this->buildWorkflowPath);
    }

    public function testBuildWorkflowPinsSonarqubeScanActionToV8(): void
    {
        $this->assertStringContainsString(
            'uses: SonarSource/sonarqube-scan-action@v8',
            $this->buildWorkflowContent
        );
    }

    public function testBuildWorkflowNoLongerPinsSonarqubeScanActionToV7(): void
    {
        $this->assertStringNotContainsString(
            'sonarqube-scan-action@v7',
            $this->buildWorkflowContent,
            'Regression guard: the stale v7 pin of the SonarCloud scan action must not reappear.'
        );
    }

    public function testBuildWorkflowSonarqubeScanActionPinAppearsExactlyOnce(): void
    {
        $this->assertSame(
            1,
            substr_count($this->buildWorkflowContent, 'sonarqube-scan-action@'),
            'The SonarCloud scan action should only be referenced once in the workflow.'
        );
    }

    public function testBuildWorkflowCollapsesCpdExclusionsToBlanketGlob(): void
    {
        $this->assertStringContainsString(
            '-Dsonar.cpd.exclusions=**/*',
            $this->buildWorkflowContent
        );
    }

    public function testBuildWorkflowCpdExclusionsValueIsExactlyDoubleAsterisk(): void
    {
        $matched = preg_match('/-Dsonar\.cpd\.exclusions=([^\r\n]+)/', $this->buildWorkflowContent, $matches);
        $this->assertSame(1, $matched, 'Expected to find a single -Dsonar.cpd.exclusions= entry.');

        $this->assertSame(
            '**/*',
            trim($matches[1]),
            'sonar.cpd.exclusions must be collapsed to the blanket "**/*" glob, not a partial list.'
        );
    }

    public function testBuildWorkflowNoLongerReferencesTheOldCurdatedCpdExclusionList(): void
    {
        foreach (
            [
                '**/tests/**,tests/**,offline.php,ujian-form.php,tools/sanity-check.php',
                'offline.php',
                'ujian-form.php',
                'tools/sanity-check.php',
            ] as $staleEntry
        ) {
            $this->assertStringNotContainsString(
                $staleEntry,
                $this->buildWorkflowContent,
                "Regression guard: the stale curated CPD exclusion entry '{$staleEntry}' must not reappear " .
                'now that sonar.cpd.exclusions is a blanket "**/*" glob.'
            );
        }
    }

    public function testBuildWorkflowStillDeclaresSonarExclusionsSeparatelyFromCpdExclusions(): void
    {
        // Regression guard: collapsing sonar.cpd.exclusions to "**" must not
        // accidentally also collapse the unrelated sonar.exclusions list
        // (which controls what is analysed at all, not just CPD).
        $this->assertMatchesRegularExpression(
            '/-Dsonar\.exclusions=\*\*\/Dockerfile,\*\*\/Containerfile/',
            $this->buildWorkflowContent,
            'sonar.exclusions must remain the specific Dockerfile/Containerfile/etc. exclusion list.'
        );
    }

    public function testBuildWorkflowSonarStepRemainsGatedOnSecretPresence(): void
    {
        $this->assertMatchesRegularExpression(
            '/if:\s*\$\{\{\s*secrets\.SONAR_TOKEN\s*!=\s*\'\'\s*\}\}/',
            $this->buildWorkflowContent,
            'Bumping the scan action version must not remove the SONAR_TOKEN gating condition.'
        );
    }

    // ---------------------------------------------------------------
    // .github/workflows/php.yml
    // ---------------------------------------------------------------

    public function testPhpWorkflowFileExists(): void
    {
        $this->assertFileExists($this->phpWorkflowPath);
    }

    public function testPhpWorkflowPinsActionsCacheToV4(): void
    {
        $this->assertStringContainsString(
            'uses: actions/cache@v4',
            $this->phpWorkflowContent
        );
    }

    public function testPhpWorkflowNoLongerPinsActionsCacheToV3(): void
    {
        $this->assertStringNotContainsString(
            'actions/cache@v3',
            $this->phpWorkflowContent,
            'Regression guard: the stale v3 pin of actions/cache must not reappear.'
        );
    }

    public function testPhpWorkflowActionsCachePinAppearsExactlyOnce(): void
    {
        $this->assertSame(
            1,
            substr_count($this->phpWorkflowContent, 'actions/cache@'),
            'actions/cache should only be referenced once in the workflow.'
        );
    }

    public function testPhpWorkflowCacheStepStillCachesVendorDirectoryKeyedByComposerLock(): void
    {
        $this->assertStringContainsString('path: vendor', $this->phpWorkflowContent);
        $this->assertStringContainsString(
            "key: \${{ runner.os }}-php-\${{ hashFiles('**/composer.lock') }}",
            $this->phpWorkflowContent,
            'Bumping actions/cache must not change the cache key derivation.'
        );
    }

    public function testPhpWorkflowStillTargetsPhp84(): void
    {
        $this->assertStringContainsString("php-version: '8.4'", $this->phpWorkflowContent);
    }

    public function testPhpWorkflowUsesTabFreeIndentation(): void
    {
        $this->assertStringNotContainsString(
            "\t",
            $this->phpWorkflowContent,
            'YAML workflow files must use spaces, not tabs, for indentation.'
        );
    }

    // ---------------------------------------------------------------
    // Cross-workflow regression guard
    // ---------------------------------------------------------------

    public function testNeitherWorkflowStillReferencesAnyStalePinnedActionVersion(): void
    {
        foreach (
            [
                $this->buildWorkflowPath => $this->buildWorkflowContent,
                $this->phpWorkflowPath => $this->phpWorkflowContent,
            ] as $path => $content
        ) {
            $this->assertStringNotContainsString('sonarqube-scan-action@v7', $content, "Stale action pin found in '{$path}'.");
            $this->assertStringNotContainsString('actions/cache@v3', $content, "Stale action pin found in '{$path}'.");
        }
    }
}
