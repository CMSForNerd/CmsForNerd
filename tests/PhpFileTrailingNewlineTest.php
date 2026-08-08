<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

final class PhpFileTrailingNewlineTest extends TestCase
{
    /**
     * @return list<string>
     */
    private function normalizedTestFiles(): array
    {
        return [
            'tests/AgentSkillsTest.php',
            'tests/AmpCssHeaderLayoutTest.php',
            'tests/BrainDocumentationTest.php',
            'tests/BrainLogTest.php',
            'tests/BrainMemoryModule22Test.php',
            'tests/BrainMemoryTest.php',
            'tests/CiWorkflowVersionPinTest.php',
            'tests/ComposerJsonTest.php',
            'tests/CoreVersionBumpV430Test.php',
            'tests/DockerBuildTest.php',
            'tests/HtaccessTest.php',
            'tests/LabGatewayFallbackV430Test.php',
            'tests/LiveDemoMcpDocumentationTest.php',
            'tests/RenderDeploymentTest.php',
            'tests/SonarConfigurationTest.php',
            'tests/ThemeVersionUpgradeTest.php',
        ];
    }

    public function testFormerlyNewlineFreeTestFilesNowEndWithASingleTrailingNewline(): void
    {
        $root = dirname(__DIR__);
        foreach ($this->normalizedTestFiles() as $relativePath) {
            $path = $root . '/' . $relativePath;
            $this->assertFileExists($path);
            $content = (string) file_get_contents($path);

            $this->assertTrue(
                str_ends_with($content, "\n"),
                "'{$relativePath}' must end with a trailing newline."
            );
            $this->assertFalse(
                str_ends_with($content, "\n\n"),
                "'{$relativePath}' must not introduce a stray blank line at the end of the file."
            );
        }
    }

    public function testNormalizedTestFilesStillEndWithAClosingClassBrace(): void
    {
        $root = dirname(__DIR__);
        foreach ($this->normalizedTestFiles() as $relativePath) {
            $content = (string) file_get_contents($root . '/' . $relativePath);
            $this->assertTrue(
                str_ends_with($content, "}\n"),
                "'{$relativePath}' is expected to end with a closing brace followed by a single newline."
            );
        }
    }

    public function testAllNormalizedFilesAreListedUnderTheTestsDirectory(): void
    {
        foreach ($this->normalizedTestFiles() as $relativePath) {
            $this->assertStringStartsWith('tests/', $relativePath);
            $this->assertStringEndsWith('Test.php', $relativePath);
        }
    }
}