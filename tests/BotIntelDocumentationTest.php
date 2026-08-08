<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Validates the documentation additions made to includes/is_bot.php:
 * a docblock line advertising the curl_multi optimization on
 * update_trusted_bot_ips(), plus a matching inline "[PERFORMANCE]" comment.
 * Also guards that the documentation claim is actually backed by the
 * implementation (a real concurrent curl_multi lifecycle, not a sequential
 * curl_exec() loop).
 */
final class BotIntelDocumentationTest extends TestCase
{
    private string $sourcePath;
    private string $source;

    protected function setUp(): void
    {
        $this->sourcePath = dirname(__DIR__) . '/includes/is_bot.php';
        $this->assertFileExists($this->sourcePath);
        $this->source = (string) file_get_contents($this->sourcePath);
    }

    private function extractFunctionSource(string $functionName): string
    {
        $needle = 'function ' . $functionName . '(';
        $start = strpos($this->source, $needle);
        $this->assertNotFalse($start, "{$functionName}() must be declared.");

        $braceOpen = strpos($this->source, '{', $start);
        $this->assertNotFalse($braceOpen, "{$functionName}() must have an opening brace.");

        $depth = 0;
        $length = strlen($this->source);
        $pos = $braceOpen;
        for (; $pos < $length; $pos++) {
            if ($this->source[$pos] === '{') {
                $depth++;
            } elseif ($this->source[$pos] === '}') {
                $depth--;
                if ($depth === 0) {
                    break;
                }
            }
        }

        return substr($this->source, $start, $pos - $start + 1);
    }

    public function testUpdateTrustedBotIpsDocblockAdvertisesTheCurlMultiOptimization(): void
    {
        $this->assertStringContainsString(
            'Optimized with high-performance concurrent cURL (curl_multi) requests.',
            $this->source
        );
    }

    public function testUpdateTrustedBotIpsCarriesThePerformanceInlineComment(): void
    {
        $this->assertStringContainsString(
            '[PERFORMANCE] Highly-optimized asynchronous curl_multi parallel network operations',
            $this->source
        );
    }

    public function testTheCurlMultiPerformanceCommentsAppearWithinTheUpdateTrustedBotIpsFunctionRegion(): void
    {
        $functionStart = strpos($this->source, 'function update_trusted_bot_ips(): array');
        $this->assertNotFalse($functionStart, 'update_trusted_bot_ips() must still be declared.');

        $docblockStart = strrpos(substr($this->source, 0, $functionStart), '/**');
        $this->assertNotFalse($docblockStart, 'update_trusted_bot_ips() must be preceded by a docblock.');

        $nextFunctionStart = strpos($this->source, 'function ', $functionStart + 1);
        $regionEnd = $nextFunctionStart === false ? strlen($this->source) : $nextFunctionStart;

        $region = substr($this->source, $docblockStart, $regionEnd - $docblockStart);

        $this->assertStringContainsString(
            'Optimized with high-performance concurrent cURL (curl_multi) requests.',
            $region,
            'The curl_multi docblock line must document update_trusted_bot_ips(), not an unrelated function.'
        );
        $this->assertStringContainsString(
            '[PERFORMANCE] Highly-optimized asynchronous curl_multi parallel network operations',
            $region,
            'The inline performance comment must sit inside update_trusted_bot_ips().'
        );
    }

    public function testUpdateTrustedBotIpsDocumentationClaimIsBackedByARealCurlMultiLifecycle(): void
    {
        $functionSource = $this->extractFunctionSource('update_trusted_bot_ips');

        foreach (
            [
                'curl_multi_init',
                'curl_multi_add_handle',
                'curl_multi_exec',
                'curl_multi_getcontent',
                'curl_multi_remove_handle',
                'curl_multi_close',
            ] as $expectedCall
        ) {
            $this->assertStringContainsString(
                $expectedCall . '(',
                $functionSource,
                "update_trusted_bot_ips() must call {$expectedCall}() to back its curl_multi documentation claim."
            );
        }
    }

    public function testUpdateTrustedBotIpsDoesNotFallBackToASequentialCurlExecLoop(): void
    {
        // Negative/regression guard: the docblock explicitly claims
        // concurrent curl_multi usage, so a sequential curl_exec() call must
        // not have been (re)introduced into this function.
        $functionSource = $this->extractFunctionSource('update_trusted_bot_ips');

        $this->assertStringNotContainsString(
            'curl_exec(',
            $functionSource,
            'update_trusted_bot_ips() must not fall back to a sequential curl_exec() call.'
        );
    }
}