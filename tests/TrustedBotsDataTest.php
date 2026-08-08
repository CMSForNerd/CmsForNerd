<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

require_once 'includes/is_bot.php';

/**
 * Validates data/trusted-bots.json (refreshed 2026-08-08) and, where
 * relevant, its consumption by the unchanged is_trusted_bot_ip()/
 * ip_in_range() helpers in includes/is_bot.php.
 *
 * Covers:
 *  - overall JSON/document structure produced by update_trusted_bot_ips()
 *  - CIDR well-formedness of every declared prefix (IPv4 and IPv6)
 *  - regression guards for the specific prefixes pruned/added by this
 *    refresh of the "ChatGPT-User" provider list
 */
final class TrustedBotsDataTest extends TestCase
{
    private string $dataPath;

    /** @var array{updated: string, bots: list<array{name: string, prefixes: list<string>}>} */
    private array $data;

    /** @var array<string, list<string>> */
    private array $botsByName;

    protected function setUp(): void
    {
        $this->dataPath = dirname(__DIR__) . '/data/trusted-bots.json';
        $this->assertFileExists($this->dataPath);

        $raw = (string) file_get_contents($this->dataPath);
        $decoded = json_decode($raw, true);
        $this->assertIsArray($decoded, 'data/trusted-bots.json must decode to an array.');

        /** @var array{updated: string, bots: list<array{name: string, prefixes: list<string>}>} $decoded */
        $this->data = $decoded;

        $this->botsByName = [];
        foreach ($this->data['bots'] as $bot) {
            $this->botsByName[$bot['name']] = $bot['prefixes'];
        }
    }

    private function assertValidCidrPrefix(string $prefix, string $context): void
    {
        $this->assertStringContainsString('/', $prefix, "{$context}: prefix '{$prefix}' must be in CIDR notation.");
        [$subnet, $bits] = explode('/', $prefix, 2);

        $isIpv4 = filter_var($subnet, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false;
        $isIpv6 = filter_var($subnet, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false;

        $this->assertTrue(
            $isIpv4 || $isIpv6,
            "{$context}: subnet '{$subnet}' in prefix '{$prefix}' is not a valid IPv4/IPv6 address."
        );

        $this->assertMatchesRegularExpression(
            '/^\d+$/',
            $bits,
            "{$context}: bit length in '{$prefix}' must be a non-negative integer."
        );

        $bitsInt = (int) $bits;
        $maxBits = $isIpv4 ? 32 : 128;

        $this->assertGreaterThanOrEqual(0, $bitsInt, "{$context}: prefix '{$prefix}' bit length must be >= 0.");
        $this->assertLessThanOrEqual(
            $maxBits,
            $bitsInt,
            "{$context}: prefix '{$prefix}' bit length must be <= {$maxBits}."
        );
    }

    public function testJsonFileIsValidJson(): void
    {
        $raw = (string) file_get_contents($this->dataPath);
        json_decode($raw);

        $this->assertSame(JSON_ERROR_NONE, json_last_error(), 'data/trusted-bots.json must be valid JSON.');
    }

    public function testTopLevelStructureHasUpdatedAndBotsKeys(): void
    {
        $this->assertArrayHasKey('updated', $this->data);
        $this->assertArrayHasKey('bots', $this->data);
        $this->assertIsString($this->data['updated']);
        $this->assertIsArray($this->data['bots']);
        $this->assertNotEmpty($this->data['bots']);
    }

    public function testUpdatedTimestampIsAValidIso8601Timestamp(): void
    {
        $updated = $this->data['updated'];
        $parsed = \DateTimeImmutable::createFromFormat(\DateTimeInterface::ATOM, $updated);

        $this->assertInstanceOf(
            \DateTimeImmutable::class,
            $parsed,
            "The 'updated' field '{$updated}' must be a valid ISO-8601 (ATOM) timestamp."
        );
    }

    public function testUpdatedTimestampReflectsThisRefreshAndIsNoLongerThePreviousStaleSnapshot(): void
    {
        $this->assertSame('2026-08-08T11:00:12+00:00', $this->data['updated']);
        $this->assertNotSame('2026-08-03T22:21:29+00:00', $this->data['updated']);
    }

    public function testAllExpectedProvidersArePresentExactlyOnce(): void
    {
        $names = array_column($this->data['bots'], 'name');

        $this->assertSame(
            count($names),
            count(array_unique($names)),
            'Bot provider names must be unique across the "bots" array.'
        );

        foreach (['Google', 'Bing', 'Cloudflare', 'GPTBot', 'SearchBot', 'ChatGPT-User'] as $expected) {
            $this->assertContains($expected, $names, "Expected bot provider '{$expected}' to be present.");
        }
    }

    public function testEveryBotHasANonEmptyNameAndNonEmptyPrefixList(): void
    {
        foreach ($this->data['bots'] as $bot) {
            $this->assertArrayHasKey('name', $bot);
            $this->assertArrayHasKey('prefixes', $bot);
            $this->assertNotSame('', $bot['name']);
            $this->assertIsArray($bot['prefixes']);
            $this->assertNotEmpty($bot['prefixes'], "Bot '{$bot['name']}' must declare at least one prefix.");
        }
    }

    public function testEveryPrefixInEveryBotIsAWellFormedCidrRange(): void
    {
        foreach ($this->data['bots'] as $bot) {
            foreach ($bot['prefixes'] as $prefix) {
                $this->assertIsString($prefix);
                $this->assertValidCidrPrefix($prefix, $bot['name']);
            }
        }
    }

    public function testNoBotDeclaresDuplicatePrefixEntries(): void
    {
        foreach ($this->data['bots'] as $bot) {
            $unique = array_unique($bot['prefixes']);

            $this->assertCount(
                count($bot['prefixes']),
                $unique,
                "Bot '{$bot['name']}' must not contain duplicate prefix entries."
            );
        }
    }

    public function testJsonFileUsesFourSpaceIndentationConsistentWithTheUpdateWriter(): void
    {
        $raw = (string) file_get_contents($this->dataPath);

        $this->assertStringStartsWith(
            "{\n    \"updated\":",
            $raw,
            'data/trusted-bots.json must retain the JSON_PRETTY_PRINT layout written by update_trusted_bot_ips().'
        );
    }

    public function testJsonFileEscapesForwardSlashesConsistentlyWithTheWriter(): void
    {
        $raw = (string) file_get_contents($this->dataPath);

        $this->assertStringContainsString(
            '\\/28',
            $raw,
            'CIDR slashes must remain escaped, matching json_encode() without JSON_UNESCAPED_SLASHES.'
        );
    }

    public function testChatGptUserPrefixListNoLongerContainsStalePrunedEntries(): void
    {
        $this->assertArrayHasKey('ChatGPT-User', $this->botsByName);
        $prefixes = $this->botsByName['ChatGPT-User'];

        foreach (
            [
                '104.208.184.208/28',
                '13.65.138.96/28',
                '13.70.107.160/28',
                '13.76.115.224/28',
                '13.76.115.240/28',
                '145.132.1.32/28',
                '145.133.0.176/28',
                '40.67.183.160/28',
                '40.67.183.176/28',
                '40.78.161.48/28',
                '40.81.134.128/28',
                '40.81.134.144/28',
                '40.84.181.32/28',
                '51.107.70.192/28',
                '9.160.128.32/28',
                '9.160.164.128/28',
                '9.235.40.32/28',
                '70.153.139.208/28',
                '70.153.190.16/28',
                '70.156.144.64/28',
                '70.156.152.80/28',
            ] as $stale
        ) {
            $this->assertNotContains(
                $stale,
                $prefixes,
                "ChatGPT-User must no longer contain the pruned stale prefix '{$stale}'."
            );
        }
    }

    public function testChatGptUserPrefixListContainsNewlyAddedEntries(): void
    {
        $this->assertArrayHasKey('ChatGPT-User', $this->botsByName);
        $prefixes = $this->botsByName['ChatGPT-User'];

        foreach (
            [
                '40.74.200.208/28',
                '70.153.32.16/28',
                '70.153.32.32/28',
                '85.211.128.16/28',
                '85.211.128.32/28',
                '9.205.128.32/28',
                '9.205.128.48/28',
                '9.205.30.128/28',
                '9.205.30.144/28',
                '9.205.30.176/28',
                '9.205.8.64/28',
            ] as $added
        ) {
            $this->assertContains(
                $added,
                $prefixes,
                "ChatGPT-User must contain the newly added prefix '{$added}'."
            );
        }
    }

    public function testChatGptUserPrefixListRetainsAPreExistingUnaffectedEntry(): void
    {
        // Regression/boundary guard: confirms the prune did not accidentally
        // remove an entry that was never touched by this refresh.
        $this->assertContains('132.196.82.48/28', $this->botsByName['ChatGPT-User']);
    }

    public function testIsTrustedBotIpNoLongerTrustsAnIpFromThePrunedChatGptUserRange(): void
    {
        // The pruned "104.208.184.208/28" block covered .208-.223. The
        // surviving adjacent block "104.208.184.192/28" only covers
        // .192-.207, so an IP inside the pruned range must now be rejected.
        $this->assertFalse(is_trusted_bot_ip('104.208.184.215', 'ChatGPT-User'));
    }

    public function testIsTrustedBotIpTrustsAnIpFromEachNewlyAddedChatGptUserRange(): void
    {
        $this->assertTrue(is_trusted_bot_ip('40.74.200.210', 'ChatGPT-User'));
        $this->assertTrue(is_trusted_bot_ip('85.211.128.20', 'ChatGPT-User'));
        $this->assertTrue(is_trusted_bot_ip('9.205.30.130', 'ChatGPT-User'));
    }

    public function testIsTrustedBotIpRejectsAnUnrelatedIpEvenAfterTheRefresh(): void
    {
        $this->assertFalse(is_trusted_bot_ip('8.8.8.8', 'ChatGPT-User'));
    }
}