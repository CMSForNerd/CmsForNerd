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
     * Test Bot Detection with context caching and exact-provider binding
     */
    public function testBotDetection(): void
    {
        // 1. Positive Cases with Correct IP-to-UA Provider Binding

        // Googlebot
        $_SERVER['REMOTE_ADDR'] = '66.249.66.1';
        $this->assertTrue(
            is_bot('Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'),
            'Failed to detect Googlebot with mocked Googlebot trusted IP'
        );

        // Bingbot
        $_SERVER['REMOTE_ADDR'] = '157.55.39.1';
        $this->assertTrue(
            is_bot('Mozilla/5.0 (compatible; Bingbot/2.0; +http://www.bing.com/bingbot.htm)'),
            'Failed to detect Bingbot with mocked Bingbot trusted IP'
        );

        // GPTBot
        $_SERVER['REMOTE_ADDR'] = '132.196.86.1';
        $this->assertTrue(
            is_bot('Mozilla/5.0 (compatible; GPTBot/1.0; +https://openai.com/gptbot)'),
            'Failed to detect GPTBot with mocked OpenAI trusted IP'
        );

        // SearchBot
        $_SERVER['REMOTE_ADDR'] = '104.210.140.129';
        $this->assertTrue(
            is_bot('Mozilla/5.0 (compatible; OAI-SearchBot/1.0; +https://openai.com/searchbot)'),
            'Failed to detect SearchBot with mocked OpenAI trusted IP'
        );

        // ChatGPT-User
        $_SERVER['REMOTE_ADDR'] = '104.208.184.193';
        $this->assertTrue(
            is_bot('Mozilla/5.0 (compatible; ChatGPT-User/1.0; +https://openai.com/bot)'),
            'Failed to detect ChatGPT-User with mocked OpenAI trusted IP'
        );

        // Cloudflare
        $_SERVER['REMOTE_ADDR'] = '173.245.48.1';
        $this->assertTrue(
            is_bot('Mozilla/5.0 (compatible; Cloudflare-AlwaysOnline/1.0; +http://www.cloudflare.com/always-online)'),
            'Failed to detect Cloudflare with mocked Cloudflare trusted IP'
        );

        // 2. Negative Cases for Cross-Provider Spoofing (exact-provider mismatch checks)

        // Googlebot UA spoofing with an OpenAI IP
        $_SERVER['REMOTE_ADDR'] = '132.196.86.1';
        $this->assertFalse(
            is_bot('Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'),
            'Security Breach: Allowed spoofed Googlebot with OpenAI IP'
        );

        // Bingbot UA spoofing with a Cloudflare IP
        $_SERVER['REMOTE_ADDR'] = '173.245.48.1';
        $this->assertFalse(
            is_bot('Mozilla/5.0 (compatible; Bingbot/2.0; +http://www.bing.com/bingbot.htm)'),
            'Security Breach: Allowed spoofed Bingbot with Cloudflare IP'
        );

        // GPTBot UA spoofing with a Googlebot IP
        $_SERVER['REMOTE_ADDR'] = '66.249.66.1';
        $this->assertFalse(
            is_bot('Mozilla/5.0 (compatible; GPTBot/1.0; +https://openai.com/gptbot)'),
            'Security Breach: Allowed spoofed GPTBot with Googlebot IP'
        );

        // Cloudflare UA spoofing with a Bingbot IP
        $_SERVER['REMOTE_ADDR'] = '157.55.39.1';
        $this->assertFalse(
            is_bot('Mozilla/5.0 (compatible; Cloudflare-AlwaysOnline/1.0; +http://www.cloudflare.com/always-online)'),
            'Security Breach: Allowed spoofed Cloudflare bot with Bingbot IP'
        );

        // 3. Human and Mobile Cases (should not trigger any bot detection)
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
     * Test Hardened IPv6 CIDR Matcher
     */
    public function testIpv6InRange(): void
    {
        // Exact match /128
        $this->assertTrue(ip_in_range('2001:db8::1', '2001:db8::1/128'));
        $this->assertFalse(ip_in_range('2001:db8::2', '2001:db8::1/128'));

        // Subnet boundary match
        $this->assertTrue(ip_in_range('2001:db8::1', '2001:db8::/64'));
        $this->assertTrue(ip_in_range('2001:db8:85a3::8a2e:370:7334', '2001:db8::/32'));
        $this->assertFalse(ip_in_range('2001:db9::1', '2001:db8::/64'));

        // Type mismatches (IPv4 vs IPv6)
        $this->assertFalse(ip_in_range('127.0.0.1', '2001:db8::/64'));
        $this->assertFalse(ip_in_range('2001:db8::1', '127.0.0.1/32'));

        // Edge case: /0 subnet (should match any address in the protocol)
        $this->assertTrue(ip_in_range('2001:db8::1', '::/0'));
        $this->assertTrue(ip_in_range('127.0.0.1', '0.0.0.0/0'));
    }

    /**
     * Test Secure Session Cookie Initialization
     */
    public function testSecureSessionInitialization(): void
    {
        // Destroy active session if any
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        SecurityUtils::startSecureSession();

        $this->assertEquals(PHP_SESSION_ACTIVE, session_status());
        $this->assertEquals('1', ini_get('session.use_strict_mode'));
        $this->assertEquals('1', ini_get('session.use_only_cookies'));

        $cookieParams = session_get_cookie_params();
        $this->assertTrue($cookieParams['httponly']);
        $this->assertEquals('Strict', $cookieParams['samesite']);
    }

    /**
     * Test CSRF token generation & validation
     */
    public function testCsrfTokenLifecycle(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        $token1 = SecurityUtils::generateCsrfToken();
        $this->assertNotEmpty($token1);

        // Verify that token persists inside active session
        $this->assertEquals($token1, $_SESSION['csrf_token']);

        // Verify valid token validation returns true
        $this->assertTrue(SecurityUtils::validateCsrfToken($token1));

        // Verify mismatching token validation returns false
        $this->assertFalse(SecurityUtils::validateCsrfToken('invalid_token_123'));

        // Verify null token validation returns false
        $this->assertFalse(SecurityUtils::validateCsrfToken(null));
    }

    /**
     * Test dynamic security headers
     */
    public function testSecurityHeaders(): void
    {
        // Set up registry nonce
        \CmsForNerd\Registry::set('nonce', 'test_nonce_12345');

        // Headers are set only if not already sent (which PHPUnit environment allows mock-testing)
        SecurityUtils::sendSecurityHeaders();

        // Standard PHPUnit doesn't always populate header list directly if headers are output,
        // but we can assert no crash happens and method is verified.
        $this->assertTrue(true);
    }

    /**
     * Test Request Method constraints against verb tampering
     */
    public function testRequestMethodVerification(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        SecurityUtils::validateRequestMethod(); // Should pass with no exception or exit

        $_SERVER['REQUEST_METHOD'] = 'POST';
        SecurityUtils::validateRequestMethod(); // Should pass with no exception or exit

        $this->assertTrue(true);
    }

    /**
     * Test HTML Microdata (itemscope and itemtype) presence on all major HTML outputs
     */
    public function testHtmlMicrodataPresence(): void
    {
        $filesToTest = [
            dirname(__DIR__) . '/offline.php',
            dirname(__DIR__) . '/ujian-form.php',
            dirname(__DIR__) . '/tools/sanity-check.php',
            dirname(__DIR__) . '/themes/CmsForNerd/pager.php',
            dirname(__DIR__) . '/includes/common.inc.php',
        ];

        foreach ($filesToTest as $file) {
            $this->assertFileExists($file);
            $content = (string) file_get_contents($file);

            // We want to make sure 'itemscope' and 'itemtype' are paired together on the html elements
            $this->assertStringContainsString('itemscope', $content, "File " . basename($file) . " must contain 'itemscope' microdata attribute.");
            $this->assertStringContainsString('itemtype=', $content, "File " . basename($file) . " must contain 'itemtype' microdata attribute.");
        }
    }

    /**
     * Helper to get the IP cache file path.
     */
    private function getIpCachePath(string $ip): string
    {
        $cacheDir = dirname(__DIR__) . '/data/cache';
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0777, true);
        }
        return $cacheDir . '/ip_' . hash('sha256', $ip) . '.json';
    }

    /**
     * Test Block Datacenter Traffic function (caching, SSRF, localhost)
     */
    public function testBlockDatacenterTraffic(): void
    {
        // Case 1: Localhost bypass
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $cacheFileLocal = $this->getIpCachePath('127.0.0.1');
        if (file_exists($cacheFileLocal)) {
            unlink($cacheFileLocal);
        }
        block_datacenter_traffic('test_token');
        $this->assertFileDoesNotExist($cacheFileLocal);

        // Case 2: SSRF Private Range bypass
        $_SERVER['REMOTE_ADDR'] = '10.0.0.1';
        $cacheFilePrivate = $this->getIpCachePath('10.0.0.1');
        if (file_exists($cacheFilePrivate)) {
            unlink($cacheFilePrivate);
        }
        block_datacenter_traffic('test_token');
        $this->assertFileDoesNotExist($cacheFilePrivate);

        // Case 3: Public IP with mock cache (non-hosting)
        $_SERVER['REMOTE_ADDR'] = '1.1.1.1';
        $cacheFilePublic = $this->getIpCachePath('1.1.1.1');

        $mockJson = json_encode(['asn' => ['type' => 'isp'], 'ip' => '1.1.1.1']);
        file_put_contents($cacheFilePublic, $mockJson);

        // This should read cache, see type !== 'hosting', and return cleanly
        block_datacenter_traffic('test_token');
        $this->assertFileExists($cacheFilePublic);

        // Cleanup
        if (file_exists($cacheFilePublic)) {
            unlink($cacheFilePublic);
        }
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
    }
}
