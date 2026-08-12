<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Class PodmanComposeYamlTest
 *
 * Validates Podman defaults, tasks, configurations and compose templates under playbooks/roles.
 *
 * @package CmsForNerd\Tests
 */
final class PodmanComposeYamlTest extends TestCase
{
    /** @var array<int, string> Podman-related files to audit. */
    private array $podmanFiles = [];

    protected function setUp(): void
    {
        $rootDir = dirname(__DIR__);
        $rolesDir = $rootDir . '/playbooks/roles';

        // Dynamically discover all .yml, .yaml, and .j2 template files inside the podman roles
        $targetDirs = [
            $rolesDir . '/podman',
            $rolesDir . '/podman_prod',
        ];

        foreach ($targetDirs as $dir) {
            if (is_dir($dir)) {
                $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
                foreach ($iterator as $file) {
                    /** @var \SplFileInfo $file */
                    if ($file->isFile()) {
                        $ext = $file->getExtension();
                        if (in_array($ext, ['yml', 'yaml', 'j2'], true)) {
                            $this->podmanFiles[] = $file->getRealPath();
                        }
                    }
                }
            }
        }
    }

    /**
     * Verifies that each Podman YAML/template file exists and does not contain tabs.
     */
    public function testPodmanFilesAreTabFree(): void
    {
        $this->assertNotEmpty($this->podmanFiles, "Should discover at least some Podman role configuration files.");

        foreach ($this->podmanFiles as $path) {
            $this->assertFileExists($path);
            $content = (string) file_get_contents($path);
            $this->assertStringNotContainsString(
                "\t",
                $content,
                "Podman configuration file {$path} must not contain raw tabs."
            );
        }
    }

    /**
     * Verifies that the Podman Compose template contains necessary service declarations.
     */
    public function testPodmanComposeTemplateDeclarations(): void
    {
        $composeTemplate = dirname(__DIR__) . '/playbooks/roles/podman_prod/templates/compose.yml.j2';
        $this->assertFileExists($composeTemplate);
        $content = (string) file_get_contents($composeTemplate);

        $this->assertStringContainsString('services:', $content, "Compose template must define services.");
        $this->assertStringContainsString('nginx:', $content, "Compose template must configure Nginx service.");
        $this->assertStringContainsString('php:', $content, "Compose template must configure PHP container service.");
    }
}
