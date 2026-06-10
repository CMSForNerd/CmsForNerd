<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;
use CmsForNerd\SecurityUtils;

require_once 'includes/is_bot.php';

final class SecurityTest extends TestCase
{
    /**
     * Test Input Validation (Directory Traversal Protection)
     */
    public function testPageNameValidation(): void
    {
        $this->assertTrue(SecurityUtils::isValidPageName('about'));
        $this->assertTrue(SecurityUtils::isValidPageName('my-page_123'));

        // Security checks
        $this->assertFalse(
            SecurityUtils::isValidPageName('../etc/passwd'),
            'Directory traversal should fail'
        );
        $this->assertFalse(
            SecurityUtils::isValidPageName('page.php'),
            'Extensions should fail validation if not allowed'
        );
        $this->assertFalse(
            SecurityUtils::isValidPageName('page?id=1'),
            'Query characters should fail'
        );
    }

    /**
     * Test Bot Detection
     */
    public function testBotDetection(): void
    {
        // Mock a trusted Googlebot IP to bypass Hybrid Intelligence "Trust but Verify"
        $_SERVER['REMOTE_ADDR'] = '66.249.66.1';

        // Known Bots
        $this->assertTrue(
            is_bot('Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'),
            'Failed to detect Googlebot with mocked trusted IP'
        );
        $this->assertTrue(
            is_bot('Mozilla/5.0 (compatible; Bingbot/2.0; +http://www.bing.com/bingbot.htm)'),
            'Failed to detect Bingbot with mocked trusted IP'
        );

        // Known Humans
        $humanUa = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) ' .
                   'AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';
        $this->assertFalse(is_bot($humanUa), 'Human UA incorrectly flagged as bot');

        $mobileUa = 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) ' .
                    'AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1';
        $this->assertFalse(is_bot($mobileUa), 'Mobile Human UA incorrectly flagged as bot');

        // Cleanup
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
    }

    /**
     * Test CIDR matching for IPv4 and IPv6
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('ipInRangeProvider')]
    public function testIpInRange(string $ip, string $range, bool $expected): void
    {
        $this->assertSame($expected, ip_in_range($ip, $range), "Failed for IP: $ip, Range: $range");
    }

    /**
     * Data provider for testIpInRange
     * @return array<string, array{string, string, bool}>
     */
    public static function ipInRangeProvider(): array
    {
        return [
            // IPv4
            'ipv4_in_range'          => ['192.168.1.1', '192.168.1.0/24', true],
            'ipv4_out_of_range'      => ['192.168.2.1', '192.168.1.0/24', false],
            'ipv4_large_subnet'      => ['10.0.0.1', '10.0.0.0/8', true],

            // IPv6
            'ipv6_in_range'          => ['2001:db8::1', '2001:db8::/32', true],
            'ipv6_in_range_deeper'   => ['2001:db8:1::1', '2001:db8::/32', true],
            'ipv6_out_of_range'      => ['2001:def::1', '2001:db8::/32', false],

            // Edge cases
            'ipv6_localhost'         => ['::1', '::1/128', true],
            'ipv6_localhost_mismatch'=> ['::1', '::/128', false],
            'ipv6_full_address'      => ['2001:db8::8a2e:370:7334', '2001:db8::/64', true],

            // Non-multiples of 8
            'ipv6_121_bit_mismatch'  => ['2001:db8::80', '2001:db8::/121', false],
            'ipv6_121_bit_match'     => ['2001:db8::7f', '2001:db8::/121', true],
            'ipv6_121_bit_both_set'  => ['2001:db8::80', '2001:db8::80/121', true],
            'ipv6_121_bit_subnet_set'=> ['2001:db8::7f', '2001:db8::80/121', false],

            'ipv6_122_bit_mismatch_1'=> ['2001:db8::c0', '2001:db8::80/122', false],
            'ipv6_122_bit_mismatch_2'=> ['2001:db8::80', '2001:db8::c0/122', false],
            'ipv6_122_bit_match_exact'=> ['2001:db8::c0', '2001:db8::c0/122', true],
            'ipv6_122_bit_match_extra'=> ['2001:db8::e0', '2001:db8::c0/122', true],
        ];
    }
}
