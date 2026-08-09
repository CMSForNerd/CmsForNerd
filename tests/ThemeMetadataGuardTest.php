<?php
declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Validates the "[LAB] Prevent unused variable warnings in static analysis"
 * guard added to themes/CmsForNerd/theme.php. The guard checks whether
 * $THEME_VERSION or $THEME_AUTHOR are empty() and, if so, records the
 * fact via error_log(). These tests exercise both the presence/shape of
 * the guard in the source file and its actual runtime behaviour when the
 * file is included with different metadata values.
 */
final class ThemeMetadataGuardTest extends TestCase
{
    private const GUARD_MESSAGE = 'Theme metadata is incomplete.';

    private string $themePhpPath;

    private string $logFile;

    private string $originalErrorLog;

    /** @var list<string> */
    private array $tempFiles = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->themePhpPath = dirname(__DIR__) . '/themes/CmsForNerd/theme.php';

        $this->originalErrorLog = (string) ini_get('error_log');

        $logFile = tempnam(sys_get_temp_dir(), 'theme_guard_log_');
        $this->assertNotFalse($logFile, 'Failed to create a temporary log file for the test.');
        $this->logFile = $logFile;

        ini_set('error_log', $this->logFile);
    }

    protected function tearDown(): void
    {
        ini_set('error_log', $this->originalErrorLog);

        if (is_file($this->logFile)) {
            unlink($this->logFile);
        }

        foreach ($this->tempFiles as $tempFile) {
            if (is_file($tempFile)) {
                unlink($tempFile);
            }
        }

        parent::tearDown();
    }

    // ---------------------------------------------------------------
    // Static source checks
    // ---------------------------------------------------------------

    public function testThemePhpContainsTheMetadataGuardBlock(): void
    {
        $content = (string) file_get_contents($this->themePhpPath);

        $this->assertStringContainsString(
            'if (empty($THEME_VERSION) || empty($THEME_AUTHOR)) {',
            $content
        );
        $this->assertStringContainsString(
            'error_log("' . self::GUARD_MESSAGE . '");',
            $content
        );
    }

    public function testGuardMessageAppearsExactlyOnce(): void
    {
        $content = (string) file_get_contents($this->themePhpPath);

        $this->assertSame(1, substr_count($content, self::GUARD_MESSAGE));
    }

    public function testGuardBlockIsPositionedAfterBothMetadataAssignments(): void
    {
        $content = (string) file_get_contents($this->themePhpPath);

        $versionPos = strpos($content, '$THEME_VERSION = "4.3.0";');
        $authorPos = strpos($content, '$THEME_AUTHOR  = "Harisfazillah Jamel";');
        $guardPos = strpos($content, 'if (empty($THEME_VERSION) || empty($THEME_AUTHOR))');

        $this->assertNotFalse($versionPos, 'Expected to find the $THEME_VERSION assignment.');
        $this->assertNotFalse($authorPos, 'Expected to find the $THEME_AUTHOR assignment.');
        $this->assertNotFalse($guardPos, 'Expected to find the metadata guard condition.');

        $this->assertGreaterThan(
            $versionPos,
            $guardPos,
            'The guard must be declared after $THEME_VERSION is assigned so both variables are defined.'
        );
        $this->assertGreaterThan(
            $authorPos,
            $guardPos,
            'The guard must be declared after $THEME_AUTHOR is assigned so both variables are defined.'
        );
    }

    // ---------------------------------------------------------------
    // Runtime behaviour: default (non-empty) metadata
    // ---------------------------------------------------------------

    public function testIncludingThemePhpWithDefaultMetadataDoesNotWriteToTheErrorLog(): void
    {
        $this->includeThemeConfig($this->themePhpPath);

        $this->assertSame(
            '',
            (string) file_get_contents($this->logFile),
            'The guard must not fire when both $THEME_VERSION and $THEME_AUTHOR are populated.'
        );
    }

    // ---------------------------------------------------------------
    // Runtime behaviour: missing metadata triggers error_log()
    // ---------------------------------------------------------------

    public function testGuardLogsWhenThemeVersionIsEmpty(): void
    {
        $path = $this->createTempThemeFile('""', '"Harisfazillah Jamel"');

        $this->includeThemeConfig($path);

        $this->assertStringContainsString(self::GUARD_MESSAGE, (string) file_get_contents($this->logFile));
    }

    public function testGuardLogsWhenThemeAuthorIsEmpty(): void
    {
        $path = $this->createTempThemeFile('"4.3.0"', '""');

        $this->includeThemeConfig($path);

        $this->assertStringContainsString(self::GUARD_MESSAGE, (string) file_get_contents($this->logFile));
    }

    public function testGuardLogsOnlyOnceWhenBothVersionAndAuthorAreEmpty(): void
    {
        $path = $this->createTempThemeFile('""', '""');

        $this->includeThemeConfig($path);

        $logContent = (string) file_get_contents($this->logFile);

        $this->assertSame(
            1,
            substr_count($logContent, self::GUARD_MESSAGE),
            'The guard uses a logical OR, so a single error_log() call must fire even when both values are empty.'
        );
    }

    // ---------------------------------------------------------------
    // Boundary / regression cases around PHP's empty() semantics
    // ---------------------------------------------------------------

    public function testGuardTreatsTheStringZeroAsEmptyPerPhpSemantics(): void
    {
        // PHP's empty() considers the string "0" to be empty, which is a
        // well-known gotcha. This locks in that the guard inherits that
        // behaviour rather than silently changing semantics later.
        $path = $this->createTempThemeFile('"0"', '"Harisfazillah Jamel"');

        $this->includeThemeConfig($path);

        $this->assertStringContainsString(self::GUARD_MESSAGE, (string) file_get_contents($this->logFile));
    }

    public function testGuardDoesNotTreatWhitespaceOnlyStringsAsEmpty(): void
    {
        // A non-empty (even if whitespace-only) string is not considered
        // empty() by PHP, so the guard must not fire in this case.
        $path = $this->createTempThemeFile('" "', '" "');

        $this->includeThemeConfig($path);

        $this->assertSame('', (string) file_get_contents($this->logFile));
    }

    // ---------------------------------------------------------------
    // Helpers
    // ---------------------------------------------------------------

    /**
     * Replicates the calling convention used by the bootstrap layer
     * (defining $themeName before including the file) so the guard is
     * exercised as real, executed PHP rather than only pattern-matched.
     */
    private function includeThemeConfig(string $path): void
    {
        $capture = static function () use ($path): void {
            $themeName = 'CmsForNerd';
            include $path;
        };

        $capture();
    }

    /**
     * Creates a temporary copy of themes/CmsForNerd/theme.php with the
     * $THEME_VERSION and $THEME_AUTHOR assignments replaced by the given
     * PHP literal expressions (e.g. '""' or '"4.3.0"'), leaving the rest
     * of the file — including the guard block under test — untouched.
     */
    private function createTempThemeFile(string $versionLiteral, string $authorLiteral): string
    {
        $content = (string) file_get_contents($this->themePhpPath);

        $content = preg_replace(
            '/\$THEME_VERSION = "[^"]*";/',
            '$THEME_VERSION = ' . $versionLiteral . ';',
            $content,
            1,
            $versionCount
        );
        $content = preg_replace(
            '/\$THEME_AUTHOR\s+= "[^"]*";/',
            '$THEME_AUTHOR = ' . $authorLiteral . ';',
            $content,
            1,
            $authorCount
        );

        $this->assertSame(1, $versionCount, 'Expected to replace exactly one $THEME_VERSION assignment.');
        $this->assertSame(1, $authorCount, 'Expected to replace exactly one $THEME_AUTHOR assignment.');

        $path = tempnam(sys_get_temp_dir(), 'theme_variant_');
        $this->assertNotFalse($path, 'Failed to create a temporary theme.php variant for the test.');

        file_put_contents($path, (string) $content);
        $this->tempFiles[] = $path;

        return $path;
    }
}