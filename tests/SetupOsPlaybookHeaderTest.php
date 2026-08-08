<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

final class SetupOsPlaybookHeaderTest extends TestCase
{
    private const RELATIVE_PATH = 'playbooks/roles/setup_os/tasks/main.yml';

    private string $path;
    private string $content;

    protected function setUp(): void
    {
        $this->path = dirname(__DIR__) . '/' . self::RELATIVE_PATH;
        $this->assertFileExists($this->path);
        $this->content = (string) file_get_contents($this->path);
    }

    public function testPlaybookStartsWithTheYamlDocumentMarker(): void
    {
        $this->assertTrue(
            str_starts_with($this->content, "---\n"),
            'main.yml must begin with the YAML document start marker on its own line.'
        );
    }

    public function testDsomProtocolHeaderBannerAppearsExactlyOnce(): void
    {
        $occurrences = substr_count($this->content, 'Protocol    : Deep State of Mind (DSOM) For My AI');
        $this->assertSame(
            1,
            $occurrences,
            'The DSOM protocol header banner must appear exactly once; duplicate banners must not be reintroduced.'
        );
    }

    public function testHeaderDelimiterLineAppearsExactlyTwice(): void
    {
        // The banner is bounded by a delimiter line above and below it: exactly two
        // occurrences confirms a single banner block, not several stacked copies.
        $delimiter = '# ==============================================================================';
        $occurrences = substr_count($this->content, $delimiter);
        $this->assertSame(
            2,
            $occurrences,
            'Expected exactly one top/bottom pair of header delimiter lines (a single banner block).'
        );
    }

    public function testHeaderBannerContainsExpectedAuthorAndLicenseMetadata(): void
    {
        foreach (
            [
                'Author      : Harisfazillah Jamel (LinuxMalaysia)',
                'License     : GNU General Public License v3.0',
                'Standard    : UK English | DBP-standard Bahasa Melayu Malaysia (Piawai)',
            ] as $expectedLine
        ) {
            $this->assertStringContainsString($expectedLine, $this->content);
        }
    }

    public function testPhaseOneSectionImmediatelyFollowsTheSingleHeaderBanner(): void
    {
        $this->assertMatchesRegularExpression(
            '/# =+\n\n# --- \[ PHASE 1: SYSTEM UPDATES \] ---\n/',
            $this->content,
            'Phase 1 must start directly after the header banner, with no leftover duplicate banners in between.'
        );
    }

    public function testFirstFoundationTaskIsStillPresentAndIntact(): void
    {
        $this->assertStringContainsString(
            '- name: "Foundation | Update OS package cache and upgrade (Debian/Ubuntu)"',
            $this->content
        );
        $this->assertStringContainsString('ansible.builtin.apt:', $this->content);
    }

    public function testPlaybookRemainsFreeOfTabCharacters(): void
    {
        $this->assertStringNotContainsString(
            "\t",
            $this->content,
            'Ansible YAML files must use spaces, not tabs, for indentation.'
        );
    }
}