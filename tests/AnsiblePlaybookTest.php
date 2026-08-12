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
     * Parse each playbook to verify that every play block defined has a valid play-level 'hosts' key.
     */
    public function testPlaybooksHaveStructuredHostsKeyPerPlay(): void
    {
        $this->assertNotEmpty($this->playbookPaths, "Should discover at least some playbooks.");

        foreach ($this->playbookPaths as $path) {
            $content = (string) file_get_contents($path);
            $lines = explode("\n", $content);

            $plays = [];
            $currentPlay = null;

            // Structured indentation-scanner to group list of plays
            foreach ($lines as $line) {
                $trimmed = trim($line);
                if ($trimmed === '' || str_starts_with($trimmed, '#') || $trimmed === '---' || $trimmed === '...') {
                    continue;
                }

                // A play in Ansible YAML starts with an item indicator '-' at the root level (no leading indentation)
                if (preg_match('/^-\s+([a-zA-Z0-9_\-]+):/', $line, $matches) || preg_match('/^-\s+name:/', $line)) {
                    if ($currentPlay !== null) {
                        $plays[] = $currentPlay;
                    }
                    $currentPlay = [
                        'keys' => []
                    ];
                }

                if ($currentPlay !== null) {
                    if (preg_match('/^\s*([a-zA-Z0-9_\-]+):/', $line, $matches)) {
                        $currentPlay['keys'][] = $matches[1];
                    }
                }
            }

            if ($currentPlay !== null) {
                $plays[] = $currentPlay;
            }

            $this->assertNotEmpty($plays, "Playbook {$path} must define at least one structured play.");

            foreach ($plays as $index => $play) {
                $this->assertContains(
                    'hosts',
                    $play['keys'],
                    "Play #{$index} in playbook {$path} must define a play-level 'hosts:' key."
                );
            }
        }
    }
}
