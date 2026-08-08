<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Validates the Render Blueprint specification (render.yaml) and the
 * accompanying documentation after the switch from a single `Containerfile`
 * build to a co-existing `Dockerfile` + `Containerfile` setup.
 */
final class RenderDeploymentTest extends TestCase
{
    private string $renderYamlPath;
    private string $deploymentGuidePath;

    protected function setUp(): void
    {
        $this->renderYamlPath = dirname(__DIR__) . '/render.yaml';
        $this->deploymentGuidePath = dirname(__DIR__) . '/docs/RENDER-DEPLOYMENT-GUIDE.md';
    }

    public function testRenderYamlExists(): void
    {
        $this->assertFileExists($this->renderYamlPath);
    }

    public function testRenderYamlDefinesDockerRuntimeWebService(): void
    {
        $content = file_get_contents($this->renderYamlPath);

        $this->assertMatchesRegularExpression('/^\s*-?\s*type:\s*web$/m', $content);
        $this->assertMatchesRegularExpression('/^\s*name:\s*cmsfornerd$/m', $content);
        $this->assertMatchesRegularExpression('/^\s*runtime:\s*docker$/m', $content);
    }

    public function testRenderYamlPointsBuildAtDockerfile(): void
    {
        $content = file_get_contents($this->renderYamlPath);

        $this->assertMatchesRegularExpression(
            '/^\s*dockerfilePath:\s*Dockerfile\s*$/m',
            $content,
            'render.yaml must build using "Dockerfile" so Render\'s Docker-based build stage can find it.'
        );
    }

    public function testRenderYamlNoLongerPointsAtContainerfile(): void
    {
        $content = file_get_contents($this->renderYamlPath);

        $this->assertDoesNotMatchRegularExpression(
            '/^\s*dockerfilePath:\s*Containerfile\s*$/m',
            $content,
            'Regression guard: the build previously failed because Render looked for "Dockerfile" while ' .
            'dockerfilePath referenced "Containerfile".'
        );
    }

    public function testRenderYamlReferencedDockerfileExistsOnDisk(): void
    {
        $content = file_get_contents($this->renderYamlPath);

        $matched = preg_match('/^\s*dockerfilePath:\s*(\S+)\s*$/m', $content, $matches);
        $this->assertSame(1, $matched, 'render.yaml must declare a dockerfilePath.');
        $referencedFile = $matches[1];

        $this->assertFileExists(
            dirname(__DIR__) . '/' . $referencedFile,
            "render.yaml references '{$referencedFile}' via dockerfilePath, but that file does not exist at the repository root."
        );
    }

    public function testRenderYamlConfiguresSingaporeRegionAndKualaLumpurTimezone(): void
    {
        $content = file_get_contents($this->renderYamlPath);

        $this->assertMatchesRegularExpression('/^\s*region:\s*singapore\s*$/m', $content);
        $this->assertMatchesRegularExpression('/^\s*value:\s*Asia\/Kuala_Lumpur\s*$/m', $content);
    }

    public function testRenderYamlMarksSecretsAsNotSynced(): void
    {
        $content = file_get_contents($this->renderYamlPath);

        $this->assertMatchesRegularExpression(
            '/key:\s*APP_SECRET\s*\n\s*sync:\s*false/',
            $content,
            'APP_SECRET must never be synced/hardcoded and should prompt for manual entry in the Render dashboard.'
        );
        $this->assertMatchesRegularExpression(
            '/key:\s*ADMIN_PASSWORD\s*\n\s*sync:\s*false/',
            $content,
            'ADMIN_PASSWORD must never be synced/hardcoded and should prompt for manual entry in the Render dashboard.'
        );
    }

    public function testDeploymentGuideExists(): void
    {
        $this->assertFileExists($this->deploymentGuidePath);
    }

    public function testDeploymentGuideDescribesDockerfileContainerfileCoexistence(): void
    {
        $content = file_get_contents($this->deploymentGuidePath);

        $this->assertStringContainsString(
            'co-existing `Dockerfile` and `Containerfile` files',
            $content,
            'Guide must explain why both build files exist side by side.'
        );
        $this->assertStringContainsString('Co-existence & Cloud Compatibility', $content);
    }

    public function testDeploymentGuideDeployStepReferencesDockerfileNotContainerfile(): void
    {
        $content = file_get_contents($this->deploymentGuidePath);

        $this->assertStringContainsString(
            'Render will build using the custom `Dockerfile`',
            $content,
            'Step-by-step deployment instructions must match the actual dockerfilePath used by render.yaml.'
        );
        $this->assertStringNotContainsString(
            'Render will build the custom `Containerfile`',
            $content,
            'Stale instructions referencing the old Containerfile-based deploy step must be removed.'
        );
    }

    public function testDeploymentGuideFrontMatterDescriptionMentionsDockerfile(): void
    {
        $content = file_get_contents($this->deploymentGuidePath);

        $this->assertMatchesRegularExpression(
            '/^description:\s*".*Dockerfile\/Containerfiles.*"$/m',
            $content
        );
    }
}
