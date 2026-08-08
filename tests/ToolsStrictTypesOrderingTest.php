<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

final class ToolsStrictTypesOrderingTest extends TestCase
{
    private const LEGACY_GLOBAL_SCRIPT = 'tools/check-legacy-global.php';
    private const STRICT_TYPES_SCRIPT = 'tools/check-strict-types.php';

    private string $root;

    protected function setUp(): void
    {
        $this->root = dirname(__DIR__);
    }

    private function read(string $relativePath): string
    {
        $path = $this->root . '/' . $relativePath;
        $this->assertFileExists($path, "Expected '{$relativePath}' to exist.");

        return (string) file_get_contents($path);
    }

    private function assertScriptDeclaresStrictTypesAfterItsDocblock(string $relativePath): void
    {
        $content = $this->read($relativePath);

        $docblockPos = strpos($content, '/**');
        $declarePos = strpos($content, 'declare(strict_types=1);');

        $this->assertNotFalse($docblockPos, "'{$relativePath}' must contain a docblock comment.");
        $this->assertNotFalse($declarePos, "'{$relativePath}' must contain a declare(strict_types=1) statement.");
        $this->assertLessThan(
            $declarePos,
            $docblockPos,
            "'{$relativePath}' must place its docblock comment before the declare(strict_types=1) statement."
        );
    }

    private function assertScriptDoesNotDeclareStrictTypesImmediatelyAfterOpeningTag(string $relativePath): void
    {
        $content = $this->read($relativePath);
        $this->assertDoesNotMatchRegularExpression(
            '/^<\?php\s*\n\s*declare\(strict_types=1\);/',
            $content,
            "'{$relativePath}' must not declare strict_types directly after the opening PHP tag, ahead of its docblock."
        );
    }

    public function testCheckLegacyGlobalScriptDeclaresStrictTypesAfterItsDocblock(): void
    {
        $this->assertScriptDeclaresStrictTypesAfterItsDocblock(self::LEGACY_GLOBAL_SCRIPT);
    }

    public function testCheckStrictTypesScriptDeclaresStrictTypesAfterItsDocblock(): void
    {
        $this->assertScriptDeclaresStrictTypesAfterItsDocblock(self::STRICT_TYPES_SCRIPT);
    }

    public function testCheckLegacyGlobalScriptNoLongerDeclaresStrictTypesBeforeItsDocblock(): void
    {
        $this->assertScriptDoesNotDeclareStrictTypesImmediatelyAfterOpeningTag(self::LEGACY_GLOBAL_SCRIPT);
    }

    public function testCheckStrictTypesScriptNoLongerDeclaresStrictTypesBeforeItsDocblock(): void
    {
        $this->assertScriptDoesNotDeclareStrictTypesImmediatelyAfterOpeningTag(self::STRICT_TYPES_SCRIPT);
    }

    public function testBothAuditScriptsContainExactlyOneStrictTypesDeclaration(): void
    {
        foreach ([self::LEGACY_GLOBAL_SCRIPT, self::STRICT_TYPES_SCRIPT] as $relativePath) {
            $content = $this->read($relativePath);
            $this->assertSame(
                1,
                substr_count($content, 'declare(strict_types=1);'),
                "'{$relativePath}' must declare strict_types exactly once."
            );
        }
    }

    private function assertPhpFileLintsCleanly(string $path): void
    {
        if (!function_exists('exec')) {
            self::markTestSkipped('The exec() function is unavailable in this environment.');
        }

        $disabled = array_map('trim', explode(',', (string) ini_get('disable_functions')));
        if (in_array('exec', $disabled, true)) {
            self::markTestSkipped('exec() has been disabled via php.ini disable_functions.');
        }

        $output = [];
        $exitCode = 0;
        exec(escapeshellarg(PHP_BINARY) . ' -l ' . escapeshellarg($path) . ' 2>&1', $output, $exitCode);

        $this->assertSame(
            0,
            $exitCode,
            "'{$path}' failed 'php -l' syntax validation: " . implode("\n", $output)
        );
    }

    public function testBothAuditScriptsStillLintCleanlyAfterTheReorder(): void
    {
        foreach ([self::LEGACY_GLOBAL_SCRIPT, self::STRICT_TYPES_SCRIPT] as $relativePath) {
            $this->assertPhpFileLintsCleanly($this->root . '/' . $relativePath);
        }
    }

    /**
     * @return array{0: int, 1: string}
     */
    private function runPhpScript(string $relativePath): array
    {
        if (!function_exists('exec')) {
            self::markTestSkipped('The exec() function is unavailable in this environment.');
        }

        $disabled = array_map('trim', explode(',', (string) ini_get('disable_functions')));
        if (in_array('exec', $disabled, true)) {
            self::markTestSkipped('exec() has been disabled via php.ini disable_functions.');
        }

        $path = $this->root . '/' . $relativePath;
        $output = [];
        $exitCode = 0;
        exec('cd ' . escapeshellarg($this->root) . ' && ' . escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg($path) . ' 2>&1', $output, $exitCode);

        return [$exitCode, implode("\n", $output)];
    }

    public function testCheckLegacyGlobalScriptStillRunsSuccessfullyAfterTheReorder(): void
    {
        [$exitCode, $output] = $this->runPhpScript(self::LEGACY_GLOBAL_SCRIPT);
        $this->assertSame(0, $exitCode, "check-legacy-global.php exited with an error:\n{$output}");
        $this->assertStringContainsString('Checking for Legacy Global Keyword', $output);
    }

    public function testCheckStrictTypesScriptStillRunsSuccessfullyAfterTheReorder(): void
    {
        [$exitCode, $output] = $this->runPhpScript(self::STRICT_TYPES_SCRIPT);
        $this->assertSame(0, $exitCode, "check-strict-types.php exited with an error:\n{$output}");
        $this->assertStringContainsString('Checking for strict_types=1', $output);
    }
}