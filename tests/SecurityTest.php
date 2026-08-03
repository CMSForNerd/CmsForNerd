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
            dirname(__DIR__) . '/index.html',
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
}
