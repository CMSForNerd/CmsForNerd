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

        // Structured parsing to extract keys defined directly under the 'services:' block (at exactly 2 spaces of indentation)
        $lines = explode("\n", $content);
        $inServices = false;
        $serviceKeys = [];

        foreach ($lines as $line) {
            if (preg_match('/^services:\s*$/', $line)) {
                $inServices = true;
                continue;
            }
            if ($inServices) {
                // If we hit any non-indented root-level key after services, stop
                if (preg_match('/^[a-zA-Z0-9_\-]+:/', $line)) {
                    $inServices = false;
                    continue;
                }
                // Match lines that define a service key at exactly 2 spaces indentation
                if (preg_match('/^  ([a-zA-Z0-9_\-]+):\s*$/', $line, $matches)) {
                    $serviceKeys[] = $matches[1];
                }
            }
        }

        $this->assertNotEmpty($serviceKeys, "Compose template must define services in its services mapping block.");

        // Assert that nginx and php are validated as keys directly under the Compose services mapping
        $hasNginx = false;
        $hasPhp = false;
        foreach ($serviceKeys as $key) {
            if (str_contains($key, 'nginx')) {
                $hasNginx = true;
            }
            if (str_contains($key, 'php')) {
                $hasPhp = true;
            }
        }

        $this->assertTrue($hasNginx, "Compose services must configure an nginx-related service key.");
        $this->assertTrue($hasPhp, "Compose services must configure a php-related service key.");
    }
}
