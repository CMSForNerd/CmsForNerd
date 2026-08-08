<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Validates the new tools/bench_is_bot.php benchmark script.
 *
 * The script performs real outbound HTTP requests to live third-party
 * endpoints (Google/Bing/Cloudflare/OpenAI), so it is intentionally never
 * executed here; instead these tests statically verify its structure,
 * standards compliance, and that its documented curl_multi optimization is
 * actually implemented (mirroring the pattern used for other tools/*.php
 * audit scripts in tests/ToolsStrictTypesOrderingTest.php).
 */
final class BenchIsBotToolTest extends TestCase
{
    private const SCRIPT = 'tools/bench_is_bot.php';

    private string $root;
    private string $path;
    private string $source;

    protected function setUp(): void
    {
        $this->root = dirname(__DIR__);
        $this->path = $this->root . '/' . self::SCRIPT;
        $this->assertFileExists($this->path);
        $this->source = (string) file_get_contents($this->path);
    }

    public function testFileDeclaresStrictTypesAfterItsDocblock(): void
    {
        $docblockPos = strpos($this->source, '/**');
        $declarePos = strpos($this->source, 'declare(strict_types=1);');

        $this->assertNotFalse($docblockPos, self::SCRIPT . ' must contain a docblock comment.');
        $this->assertNotFalse($declarePos, self::SCRIPT . ' must contain a declare(strict_types=1) statement.');
        $this->assertLessThan(
            $declarePos,
            $docblockPos,
            self::SCRIPT . ' must place its docblock comment before the declare(strict_types=1) statement.'
        );
    }

    public function testFileDeclaresStrictTypesExactlyOnce(): void
    {
        $this->assertSame(
            1,
            substr_count($this->source, 'declare(strict_types=1);'),
            self::SCRIPT . ' must declare strict_types exactly once.'
        );
    }

    public function testFileLintsCleanlyWithPhpDashL(): void
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
        exec(escapeshellarg(PHP_BINARY) . ' -l ' . escapeshellarg($this->path) . ' 2>&1', $output, $exitCode);

        $this->assertSame(
            0,
            $exitCode,
            self::SCRIPT . " failed 'php -l' syntax validation: " . implode("\n", $output)
        );
    }

    public function testSourcesArrayDeclaresAllSixOfficialBotProviderEndpoints(): void
    {
        $expected = [
            'Google'       => 'https://developers.google.com/search/apis/ipranges/googlebot.json',
            'Bing'         => 'https://www.bing.com/toolbox/bingbot.json',
            'Cloudflare'   => 'https://api.cloudflare.com/client/v4/ips',
            'GPTBot'       => 'https://openai.com/gptbot.json',
            'SearchBot'    => 'https://openai.com/searchbot.json',
            'ChatGPT-User' => 'https://openai.com/chatgpt-user.json',
        ];

        foreach ($expected as $name => $url) {
            $this->assertMatchesRegularExpression(
                '/[\'"]' . preg_quote($name, '/') . '[\'"]\s*=>\s*[\'"]' . preg_quote($url, '/') . '[\'"]/',
                $this->source,
                self::SCRIPT . " must declare source '{$name}' => '{$url}'."
            );
        }
    }

    public function testSourcesArrayStaysInSyncWithIncludesIsBotPhpSourcesList(): void
    {
        $isBotSource = (string) file_get_contents($this->root . '/includes/is_bot.php');

        preg_match_all(
            '~\'([A-Za-z\-]+)\'\s*=>\s*\'(https://[^\']+)\'~',
            $isBotSource,
            $isBotMatches,
            PREG_SET_ORDER
        );
        preg_match_all(
            '~\'([A-Za-z\-]+)\'\s*=>\s*\'(https://[^\']+)\'~',
            $this->source,
            $benchMatches,
            PREG_SET_ORDER
        );

        $isBotSourcesMap = [];
        foreach ($isBotMatches as $match) {
            $isBotSourcesMap[$match[1]] = $match[2];
        }

        $benchSourcesMap = [];
        foreach ($benchMatches as $match) {
            $benchSourcesMap[$match[1]] = $match[2];
        }

        $this->assertNotEmpty($isBotSourcesMap, 'Could not extract the sources map from includes/is_bot.php.');
        $this->assertNotEmpty($benchSourcesMap, 'Could not extract the sources map from ' . self::SCRIPT . '.');
        $this->assertSame(
            $isBotSourcesMap,
            $benchSourcesMap,
            self::SCRIPT . ' sources must stay in sync with the real endpoints defined in includes/is_bot.php.'
        );
    }

    public function testSynchronousBaselineUsesFileGetContents(): void
    {
        $this->assertStringContainsString('file_get_contents($url)', $this->source);
    }

    public function testAsynchronousBenchmarkUsesTheFullCurlMultiLifecycle(): void
    {
        foreach (
            [
                'curl_multi_init',
                'curl_multi_add_handle',
                'curl_multi_exec',
                'curl_multi_select',
                'curl_multi_getcontent',
                'curl_multi_remove_handle',
                'curl_multi_close',
            ] as $expectedCall
        ) {
            $this->assertStringContainsString(
                $expectedCall . '(',
                $this->source,
                self::SCRIPT . " must call {$expectedCall}()."
            );
        }
    }

    public function testCurlMultiInitFailureIsHandledDefensively(): void
    {
        $this->assertStringContainsString('if ($mh === false)', $this->source);
        $this->assertStringContainsString('Failed to initialize curl_multi', $this->source);
    }

    public function testEachCurlHandleEnforcesHttpsOnlyTransport(): void
    {
        $this->assertStringContainsString('CURLOPT_PROTOCOLS_STR', $this->source);
        $this->assertStringContainsString('CURLOPT_PROTOCOLS, CURLPROTO_HTTPS', $this->source);
    }

    public function testEachCurlHandleSetsATimeoutAndBoundedRedirects(): void
    {
        $this->assertStringContainsString('CURLOPT_TIMEOUT, 10', $this->source);
        $this->assertStringContainsString('CURLOPT_FOLLOWLOCATION, true', $this->source);
        $this->assertStringContainsString('CURLOPT_MAXREDIRS, 5', $this->source);
    }

    public function testSpeedupAndReductionMetricsGuardAgainstDivisionByZero(): void
    {
        // Boundary/negative case: if either timing measurement is zero
        // (e.g. mocked/instantaneous responses), the script must not
        // divide by zero when computing its comparison metrics.
        $this->assertStringContainsString(
            '$asyncTime > 0 ? ($syncTime / $asyncTime) : 0',
            $this->source
        );
        $this->assertStringContainsString(
            '$syncTime > 0 ? (($syncTime - $asyncTime) / $syncTime * 100) : 0',
            $this->source
        );
    }

    public function testBenchmarkPrintsTheExpectedReportSections(): void
    {
        foreach (
            [
                '=== BOT INTELLIGENCE BENCHMARK SUITE ===',
                '1. Running Synchronous Baseline (file_get_contents)...',
                '2. Running Asynchronous Optimization (curl_multi)...',
                '=== PERFORMANCE RESULTS ===',
                'Speedup Factor',
                'Latency Reduction',
            ] as $expected
        ) {
            $this->assertStringContainsString($expected, $this->source, self::SCRIPT . " must print '{$expected}'.");
        }
    }

    public function testEveryCurlHandleIsClosedAndRemovedFromTheMultiHandle(): void
    {
        // Regression guard against resource leaks: every added handle must
        // eventually be removed from the multi handle and closed.
        $this->assertStringContainsString('curl_multi_remove_handle($mh, $ch)', $this->source);
        $this->assertStringContainsString('curl_close($ch)', $this->source);
        $this->assertStringContainsString('curl_multi_close($mh)', $this->source);
    }

    public function testFileEndsWithASingleTrailingNewline(): void
    {
        $this->assertTrue(str_ends_with($this->source, "\n"), self::SCRIPT . ' must end with a trailing newline.');
        $this->assertFalse(
            str_ends_with($this->source, "\n\n"),
            self::SCRIPT . ' must not introduce a stray blank line at the end of the file.'
        );
    }
}