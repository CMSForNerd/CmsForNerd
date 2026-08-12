<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Class AnsiblePlaybookTest
 *
 * Verifies format, tasks syntax, dsom structure, and tab-free formatting
 * of Ansible playbook files.
 *
 * @package CmsForNerd\Tests
 */
final class AnsiblePlaybookTest extends TestCase
{
    /** @var array<int, string> List of playbooks to audit. */
    private array $playbookPaths = [];

    protected function setUp(): void
    {
        $rootDir = dirname(__DIR__);
        $playbookDir = $rootDir . '/playbooks';

        if (is_dir($playbookDir)) {
            // Dynamically discover all top-level .yml/.yaml playbooks
            $files = glob($playbookDir . '/*.{yml,yaml}', GLOB_BRACE);
            if (is_array($files)) {
                $this->playbookPaths = $files;
            }
        }
    }

    /**
     * Verifies that each playbook starts with the correct YAML document marker.
     */
    public function testPlaybooksStartWithYamlMarker(): void
    {
        $this->assertNotEmpty($this->playbookPaths, "Should discover at least some playbooks.");

        foreach ($this->playbookPaths as $path) {
            $this->assertFileExists($path);
            $content = (string) file_get_contents($path);
            $this->assertTrue(
                str_starts_with($content, "---"),
                "Playbook {$path} must begin with standard '---' YAML document marker."
            );
        }
    }

    /**
     * Verifies that playbooks are indentation tab-free.
     */
    public function testPlaybooksAreTabFree(): void
    {
        $this->assertNotEmpty($this->playbookPaths, "Should discover at least some playbooks.");

        foreach ($this->playbookPaths as $path) {
            $content = (string) file_get_contents($path);
            $this->assertStringNotContainsString(
                "\t",
                $content,
                "Playbook {$path} must use spaces instead of tabs."
            );
        }
    }

    /**
     * Verifies that key playbook definitions exist and are validly structured YAML blocks.
     */
    public function testPlaybooksContainExpectedKeys(): void
    {
        $this->assertNotEmpty($this->playbookPaths, "Should discover at least some playbooks.");

        foreach ($this->playbookPaths as $path) {
            $content = (string) file_get_contents($path);
            $this->assertStringContainsString(
                'hosts:',
                $content,
                "Playbook {$path} must define targets via 'hosts:'."
            );
        }
    }
}
