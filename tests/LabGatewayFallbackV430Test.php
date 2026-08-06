<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Validates the CmsForNerd v4.3.0 "Directory Privacy Fallback" gateway
 * pages: `.agents/index.html`, `.agents/rules/index.html`, and
 * `.well-known/index.html`.
 *
 * These static HTML fallbacks are served when a directory listing is
 * requested on a server that either does not execute PHP or prioritises
 * `.html` over `.php` in its DirectoryIndex. Previously every copy of this
 * file carried the same stale `FILE:` comment
 * (`themes/CmsForNerdNew/index.html`), regardless of its actual location on
 * disk, and advertised `VERSION: 3.4`. The v4.3.0 pass corrected the `FILE:`
 * comment to match each file's real path and bumped the version banner to
 * `4.3.0`.
 *
 * Also covers the companion PHP-layer gateway `.vscode/index.php` ("Silent
 * Sentry"), which performs the equivalent 403 lockdown for the `.vscode`
 * directory.
 */
final class LabGatewayFallbackV430Test extends TestCase
{
    /** @var array<string, string> Relative path => absolute path */
    private array $htmlFallbackPaths;

    private string $vscodeIndexPath;

    protected function setUp(): void
    {
        $root = dirname(__DIR__);

        $this->htmlFallbackPaths = [
            '.agents/index.html' => $root . '/.agents/index.html',
            '.agents/rules/index.html' => $root . '/.agents/rules/index.html',
            '.well-known/index.html' => $root . '/.well-known/index.html',
        ];

        $this->vscodeIndexPath = $root . '/.vscode/index.php';
    }

    // ---------------------------------------------------------------
    // Static HTML "Directory Privacy Fallback" family
    // ---------------------------------------------------------------

    public function testEachFallbackHtmlFileExists(): void
    {
        foreach ($this->htmlFallbackPaths as $relativePath => $absolutePath) {
            $this->assertFileExists($absolutePath, "Expected fallback page to exist at '{$relativePath}'.");
        }
    }

    public function testEachFallbackHtmlFileDeclaresItsOwnRealPathInTheFileComment(): void
    {
        foreach ($this->htmlFallbackPaths as $relativePath => $absolutePath) {
            $content = (string) file_get_contents($absolutePath);

            $this->assertStringContainsString(
                "FILE: {$relativePath}",
                $content,
                "Expected '{$relativePath}' to self-identify with a matching FILE: comment instead of a copy-pasted path."
            );
        }
    }

    public function testEachFallbackHtmlFileNoLongerReferencesTheStaleSharedTemplatePath(): void
    {
        foreach ($this->htmlFallbackPaths as $relativePath => $absolutePath) {
            $content = (string) file_get_contents($absolutePath);

            $this->assertStringNotContainsString(
                'FILE: themes/CmsForNerdNew/index.html',
                $content,
                "Regression guard: '{$relativePath}' must not still reference the stale copy-pasted " .
                'themes/CmsForNerdNew/index.html FILE: comment.'
            );
        }
    }

    public function testEachFallbackHtmlFileAdvertisesVersion430(): void
    {
        foreach ($this->htmlFallbackPaths as $relativePath => $absolutePath) {
            $content = (string) file_get_contents($absolutePath);

            $this->assertStringContainsString('VERSION: 4.3.0', $content, "Expected '{$relativePath}' to advertise VERSION: 4.3.0.");
            $this->assertStringContainsString(
                'It uses the "Lab" aesthetic to match CMSForNerd v4.3.0.',
                $content,
                "Expected '{$relativePath}' to reference the v4.3.0 Lab aesthetic note."
            );
        }
    }

    public function testEachFallbackHtmlFileNoLongerAdvertisesTheStaleVersion34(): void
    {
        foreach ($this->htmlFallbackPaths as $relativePath => $absolutePath) {
            $content = (string) file_get_contents($absolutePath);

            $this->assertStringNotContainsString('VERSION: 3.4', $content, "Regression guard: '{$relativePath}' must not still advertise VERSION: 3.4.");
            $this->assertStringNotContainsString(
                'match CMSForNerd v3.4',
                $content,
                "Regression guard: '{$relativePath}' must not still reference the stale v3.4 Lab aesthetic note."
            );
        }
    }

    public function testEachFallbackHtmlFileIsWellFormedMinimalHtmlDocument(): void
    {
        foreach ($this->htmlFallbackPaths as $relativePath => $absolutePath) {
            $content = (string) file_get_contents($absolutePath);

            $this->assertStringStartsWith('<!DOCTYPE html>', $content, "Expected '{$relativePath}' to start with a DOCTYPE declaration.");
            $this->assertStringContainsString('<title>403 - Access Forbidden</title>', $content, "Expected '{$relativePath}' to declare the 403 title.");
            $this->assertStringContainsString(
                '<meta name="robots" content="noindex, nofollow">',
                $content,
                "Expected '{$relativePath}' to keep the noindex/nofollow directive so the 403 page itself is not indexed."
            );
        }
    }

    public function testAllThreeFallbackFilesAreIdenticalOnceTheirFileCommentIsNormalised(): void
    {
        // Aside from the intentionally distinct `FILE:` comment identifying
        // each file's own path, the three fallback pages must stay in sync
        // so future visual/security tweaks do not silently diverge between
        // copies.
        $normalisedContents = [];

        foreach ($this->htmlFallbackPaths as $relativePath => $absolutePath) {
            $content = (string) file_get_contents($absolutePath);
            $normalisedContents[$relativePath] = str_replace(
                "FILE: {$relativePath}",
                'FILE: __SELF__',
                $content
            );
        }

        $baseline = array_shift($normalisedContents);
        foreach ($normalisedContents as $relativePath => $content) {
            $this->assertSame(
                $baseline,
                $content,
                "Expected '{$relativePath}' to be byte-identical to the other fallback pages aside from its FILE: comment."
            );
        }
    }

    // ---------------------------------------------------------------
    // .vscode/index.php ("Silent Sentry")
    // ---------------------------------------------------------------

    public function testVscodeIndexPhpExists(): void
    {
        $this->assertFileExists($this->vscodeIndexPath);
    }

    public function testVscodeIndexPhpDocblockAdvertisesVersion430(): void
    {
        $content = (string) file_get_contents($this->vscodeIndexPath);

        $this->assertStringContainsString('CMSForNerd v4.3.0 - Silent Sentry', $content);
    }

    public function testVscodeIndexPhpNoLongerAdvertisesTheStaleVersion(): void
    {
        $content = (string) file_get_contents($this->vscodeIndexPath);

        $this->assertStringNotContainsString(
            'CMSForNerd v3.5 - Silent Sentry',
            $content,
            'Regression guard: .vscode/index.php must not still advertise the stale v3.5 docblock.'
        );
    }

    public function testVscodeIndexPhpDeclaresStrictTypes(): void
    {
        $content = (string) file_get_contents($this->vscodeIndexPath);

        $this->assertStringContainsString('declare(strict_types=1);', $content);
    }

    public function testVscodeIndexPhpSendsForbiddenHeaderAndDeniesAccessMessage(): void
    {
        $content = (string) file_get_contents($this->vscodeIndexPath);

        $this->assertStringContainsString("header('HTTP/1.1 403 Forbidden');", $content);
        $this->assertStringContainsString('exit("Access Denied: Laboratory Gateway Active.");', $content);
    }

    public function testVscodeIndexPhpIsSyntacticallyValidPhp(): void
    {
        $this->assertPhpFileLintsCleanly($this->vscodeIndexPath);
    }

    public function testExecutingVscodeIndexPhpViaCliPrintsTheDenialMessageAndExitsCleanly(): void
    {
        $this->skipIfExecUnavailable();

        $output = [];
        $exitCode = 0;
        exec(escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg($this->vscodeIndexPath) . ' 2>&1', $output, $exitCode);

        $this->assertSame(0, $exitCode, 'Running the gateway via CLI should terminate cleanly (exit code 0).');
        $this->assertSame(
            'Access Denied: Laboratory Gateway Active.',
            implode("\n", $output),
            'Running the gateway via CLI should print exactly the denial message with no warnings/errors.'
        );
    }

    // ---------------------------------------------------------------
    // Helpers
    // ---------------------------------------------------------------

    private function skipIfExecUnavailable(): void
    {
        if (!function_exists('exec')) {
            self::markTestSkipped('The exec() function is unavailable in this environment.');
        }

        $disabled = array_map('trim', explode(',', (string) ini_get('disable_functions')));
        if (in_array('exec', $disabled, true)) {
            self::markTestSkipped('exec() has been disabled via php.ini disable_functions.');
        }
    }

    private function assertPhpFileLintsCleanly(string $path): void
    {
        $this->skipIfExecUnavailable();

        $output = [];
        $exitCode = 0;
        exec(escapeshellarg(PHP_BINARY) . ' -l ' . escapeshellarg($path) . ' 2>&1', $output, $exitCode);

        $this->assertSame(
            0,
            $exitCode,
            "'{$path}' failed 'php -l' syntax validation: " . implode("\n", $output)
        );
    }
}