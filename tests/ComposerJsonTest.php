<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Validates the "Module 22: Context Re-learning & Windows Shell Compliance
 * (v4.2.1)" fix to composer.json: the `check-strict` and `lab-check` inline
 * `@php -r "..."` scripts previously escaped PHP variable sigils with an
 * extra backslash (e.g. `\$dirs`, `\$f`), which — once decoded from the
 * JSON string — left a literal backslash immediately before the variable
 * name in the executed PHP source. A bare backslash outside of a string
 * literal is only valid as a namespace separator, so `\$dirs = [...]`
 * triggered a PHP parse error on Windows PowerShell.
 *
 * These tests assert both on the raw decoded script text (regression guard
 * against the stray backslashes reappearing) and on the *behaviour* of the
 * embedded regular expressions, so a future edit that reintroduces broken
 * escaping — in either direction — is caught.
 */
final class ComposerJsonTest extends TestCase
{
    private string $composerJsonPath;

    /** @var array<string, mixed> */
    private array $composerConfig;

    protected function setUp(): void
    {
        $this->composerJsonPath = dirname(__DIR__) . '/composer.json';
        $content = (string) file_get_contents($this->composerJsonPath);

        $decoded = json_decode($content, true);
        $this->assertIsArray($decoded, 'composer.json must decode to an array.');

        $this->composerConfig = $decoded;
    }

    // ---------------------------------------------------------------
    // Baseline structure
    // ---------------------------------------------------------------

    public function testComposerJsonExists(): void
    {
        $this->assertFileExists($this->composerJsonPath);
    }

    public function testComposerJsonIsValidJson(): void
    {
        $content = (string) file_get_contents($this->composerJsonPath);
        json_decode($content);

        $this->assertSame(
            JSON_ERROR_NONE,
            json_last_error(),
            'composer.json must remain syntactically valid JSON: ' . json_last_error_msg()
        );
    }

    public function testScriptsSectionDeclaresCheckStrictAndLabCheck(): void
    {
        $this->assertArrayHasKey('scripts', $this->composerConfig);
        $this->assertArrayHasKey('check-strict', $this->composerConfig['scripts']);
        $this->assertArrayHasKey('lab-check', $this->composerConfig['scripts']);
        $this->assertIsArray($this->composerConfig['scripts']['check-strict']);
        $this->assertIsArray($this->composerConfig['scripts']['lab-check']);
    }

    public function testCheckStrictStillDeclaresExactlyTwoInlineChecks(): void
    {
        $this->assertCount(
            2,
            $this->composerConfig['scripts']['check-strict'],
            'check-strict must still run exactly the Global Keyword and strict_types inline checks.'
        );
    }

    public function testLabCheckStillDeclaresExactlyEightSteps(): void
    {
        $this->assertCount(
            8,
            $this->composerConfig['scripts']['lab-check'],
            'Editing the inline PHP escaping must not accidentally add or remove lab-check pipeline steps.'
        );
    }

    // ---------------------------------------------------------------
    // check-strict[0] — Legacy Global Keyword detector
    // ---------------------------------------------------------------

    public function testGlobalKeywordScriptNoLongerEscapesVariableSigilsWithABackslash(): void
    {
        $script = $this->composerConfig['scripts']['check-strict'][0];

        foreach (['\$dirs', '\$found', '\$dir', '\$files', '\$file', '\$content'] as $staleEscape) {
            $this->assertStringNotContainsString(
                $staleEscape,
                $script,
                "Regression guard: '{$staleEscape}' must not reappear in the Global Keyword inline script, " .
                "since a literal backslash before a top-level PHP variable causes a parse error."
            );
        }
    }

    public function testGlobalKeywordScriptAssignsPlainUnescapedVariables(): void
    {
        $content = file_get_contents(__DIR__ . '/../tools/check-legacy-global.php');

        $this->assertStringContainsString("\$dirs = ['includes', 'src'];", $content);
        $this->assertStringContainsString('$found = false;', $content);
        $this->assertStringContainsString('foreach ($dirs as $dir)', $content);
    }

    public function testGlobalKeywordScriptDelegatesToExternalScriptFile(): void
    {
        $script = $this->composerConfig['scripts']['check-strict'][0];

        $this->assertSame('@php tools/check-legacy-global.php', $script);
        $this->assertFileExists(__DIR__ . '/../tools/check-legacy-global.php');
    }

    public static function globalKeywordDetectionCases(): array
    {
        return [
            'bare global keyword' => ['global $legacyVar;', true],
            'GLOBALS superglobal access' => ["\$GLOBALS['legacyVar'] = 1;", true],
            'normal local variable assignment' => ["\$dirs = ['includes', 'src'];", false],
            'function name merely containing the word global' => ['function isGlobalScope(): bool {}', false],
        ];
    }

    // ---------------------------------------------------------------
    // check-strict[1] — declare(strict_types=1) detector
    // ---------------------------------------------------------------

    public function testStrictTypesScriptNoLongerEscapesVariableSigilsWithABackslash(): void
    {
        $script = $this->composerConfig['scripts']['check-strict'][1];

        foreach (['\$dirs', '\$dir', '\$files', '\$file', '\$content'] as $staleEscape) {
            $this->assertStringNotContainsString($staleEscape, $script);
        }
    }

    public function testStrictTypesRegexPatternMatchesDeclareStrictTypesStatement(): void
    {
        $pattern = $this->extractPregMatchPattern($this->composerConfig['scripts']['check-strict'][1]);

        $this->assertSame('/declare\s*\(\s*strict_types\s*=\s*1\s*\)\s*;/', $pattern);
    }

    #[DataProvider('strictTypesDetectionCases')]
    public function testStrictTypesRegexDetectsDeclareStatementRegardlessOfSpacing(string $subject, bool $shouldMatch): void
    {
        $pattern = $this->extractPregMatchPattern($this->composerConfig['scripts']['check-strict'][1]);

        $this->assertSame($shouldMatch ? 1 : 0, preg_match($pattern, $subject));
    }

    public static function strictTypesDetectionCases(): array
    {
        return [
            'compact form' => ['declare(strict_types=1);', true],
            'spaced form' => ['declare( strict_types = 1 );', true],
            'wrong value' => ['declare(strict_types=0);', false],
            'missing semicolon' => ['declare(strict_types=1)', false],
        ];
    }

    // ---------------------------------------------------------------
    // lab-check — file existence probes ($f = '...')
    // ---------------------------------------------------------------

    public function testLabCheckFileExistenceScriptsNoLongerEscapeTheFVariableSigil(): void
    {
        foreach ([3, 4, 5] as $index) {
            $script = $this->composerConfig['scripts']['lab-check'][$index];

            $this->assertStringNotContainsString(
                '\$f',
                $script,
                "lab-check step {$index} must not contain a literal backslash before the \$f variable sigil."
            );
        }
    }

    public function testLabCheckFileExistenceScriptsStartWithAPlainAssignment(): void
    {
        $this->assertStringStartsWith(
            "@php -r \"\$f='contents/installation-body.inc';",
            $this->composerConfig['scripts']['lab-check'][3]
        );
        $this->assertStringStartsWith(
            "@php -r \"\$f='includes/bootstrap.php';",
            $this->composerConfig['scripts']['lab-check'][4]
        );
        $this->assertStringStartsWith(
            "@php -r \"\$f='src/CmsContext.php';",
            $this->composerConfig['scripts']['lab-check'][5]
        );
    }

    public function testLabCheckFileExistenceScriptsPrintRealNewlinesRatherThanEscapedLiterals(): void
    {
        // Regression guard: the previous doubled-backslash JSON escaping
        // (`\\n`) decoded to a literal two-character "\n" sequence, so the
        // laboratory readiness output printed a visible backslash-n instead
        // of breaking the line. The fix collapses this to a single JSON
        // escape, which decodes to a genuine line-feed character.
        $this->assertMessageEndsWithRealNewline(
            $this->composerConfig['scripts']['lab-check'][3],
            '[OK] Installation Body found.'
        );
        $this->assertMessageEndsWithRealNewline(
            $this->composerConfig['scripts']['lab-check'][3],
            '[FAIL] Missing contents/installation-body.inc'
        );
        $this->assertMessageEndsWithRealNewline(
            $this->composerConfig['scripts']['lab-check'][4],
            '[OK] Bootstrap Layer found.'
        );
        $this->assertMessageEndsWithRealNewline(
            $this->composerConfig['scripts']['lab-check'][5],
            '[OK] CmsContext Core found.'
        );
    }

    public function testLabCheckStillReferencesTheThreeCoreArchitectureFiles(): void
    {
        $combined = implode(' ', $this->composerConfig['scripts']['lab-check']);

        foreach (['contents/installation-body.inc', 'includes/bootstrap.php', 'src/CmsContext.php'] as $expectedPath) {
            $this->assertStringContainsString(
                $expectedPath,
                $combined,
                "lab-check must still verify the existence of '{$expectedPath}'."
            );
        }
    }

    public function testLabCheckStillDelegatesToAuditPreFlightCheckVersionAndCompliance(): void
    {
        $steps = $this->composerConfig['scripts']['lab-check'];

        $this->assertSame('@audit-pre-flight', $steps[0]);
        $this->assertSame('@check-version', $steps[2]);
        $this->assertSame('@compliance', $steps[7]);
    }

    // ---------------------------------------------------------------
    // Extra confidence: lint the extracted PHP source when possible
    // ---------------------------------------------------------------

    public function testInlinePhpScriptsAreSyntacticallyValidPhp(): void
    {
        if (!function_exists('exec')) {
            self::markTestSkipped('The exec() function is unavailable in this environment.');
        }

        $disabled = array_map('trim', explode(',', (string) ini_get('disable_functions')));
        if (in_array('exec', $disabled, true)) {
            self::markTestSkipped('exec() has been disabled via php.ini disable_functions.');
        }

        $scripts = array_filter(
            array_merge(
                $this->composerConfig['scripts']['check-strict'],
                $this->composerConfig['scripts']['lab-check']
            ),
            static fn (string $script): bool => str_starts_with($script, '@php -r ')
        );

        foreach ($scripts as $script) {
            $source = $this->extractInlinePhpSource($script);
            $this->assertPhpSourceLintsCleanly($source, $script);
        }
    }

    // ---------------------------------------------------------------
    // Helpers
    // ---------------------------------------------------------------

    private function extractPregMatchPattern(string $script): string
    {
        $needle = "preg_match('";
        $start = strpos($script, $needle);
        $this->assertNotFalse($start, "Expected to find preg_match(' inside script: {$script}");

        $start += strlen($needle);
        $end = strpos($script, "'", $start);
        $this->assertNotFalse($end, "Expected a closing quote after the regex pattern in script: {$script}");

        return substr($script, $start, $end - $start);
    }

    private function assertMessageEndsWithRealNewline(string $script, string $message): void
    {
        $this->assertStringContainsString(
            "'" . $message . "\n'",
            $script,
            "Expected '{$message}' to be immediately followed by a real line-feed character before the closing quote."
        );
    }

    private function extractInlinePhpSource(string $script): string
    {
        $matched = preg_match('/^@php -r "(.*)"$/s', $script, $matches);
        $this->assertSame(1, $matched, "Expected an '@php -r \"...\"' inline script, got: {$script}");

        return $matches[1];
    }

    private function assertPhpSourceLintsCleanly(string $source, string $originalScript): void
    {
        $tmpFile = tempnam(sys_get_temp_dir(), 'composer-inline-php-');
        $this->assertNotFalse($tmpFile, 'Failed to create a temporary file for PHP lint validation.');

        file_put_contents($tmpFile, "<?php\n" . $source);

        $output = [];
        $exitCode = 0;
        exec(escapeshellarg(PHP_BINARY) . ' -l ' . escapeshellarg($tmpFile) . ' 2>&1', $output, $exitCode);

        unlink($tmpFile);

        $this->assertSame(
            0,
            $exitCode,
            "Inline PHP script failed 'php -l' syntax validation: {$originalScript}\n" . implode("\n", $output)
        );
    }
}