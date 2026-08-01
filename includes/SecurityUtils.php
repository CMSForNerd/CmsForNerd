<?php

/**
 * [SECURITY] SecurityUtils - v4.2.3 Laboratory Standard.
 * This class provides defensive programming utilities to protect the CMS core.
 * It combines path validation, XSS prevention, and CSP nonce generation.
 * * Compliance: PHP 8.4+, PSR-12, PHPStan Level 8.
 */

declare(strict_types=1);

namespace CmsForNerd;

final class SecurityUtils
{
    /**
     * [SECURITY] isValidPageName() prevents "Directory Traversal" attacks.
     * All requested page names MUST be validated before use in file paths.
     * * @param string $page The raw page name from QUERY_STRING.
     * @return bool Returns true if the name contains only allowed characters.
     */
    public static function isValidPageName(string $page): bool
    {
        // [PHP 8.4] Optimized regex for alphanumeric, dashes, and underscores.
        return (bool) preg_match('/^[a-zA-Z0-9_\-]+$/', $page);
    }

    /**
     * [SECURITY] Sanitize the page parameter.
     * Requirement: Remove any character that is NOT alphanumeric, dash, or underscore.
     * * [LAB v3.4] Added explicit casting to resolve PHPStan 'string|null' variance.
     * * @param string $pageName
     * @return string
     */
    public static function sanitizePageName(string $pageName): string
    {
        // preg_replace can return string, array, or null.
        // We force a string return to satisfy strict type declarations.
        $sanitized = preg_replace('/[^a-zA-Z0-9_\-]/', '', $pageName);
        return (string) ($sanitized ?? '');
    }

    /**
     * [SECURITY] Escape HTML to prevent Cross-Site Scripting (XSS).
     * ENT_QUOTES | ENT_SUBSTITUTE ensures high-level protection for UTF-8.
     * * @param string $content The raw string to be displayed.
     * @return string The escaped safe-for-browser string.
     */
    public static function escapeHtml(string $content): string
    {
        return htmlspecialchars($content, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * [SECURITY] Get safe sanitized base URL to prevent Host Header injection / XSS.
     * @return string The safe Base URL with a trailing slash.
     */
    public static function getSafeBaseUrl(): string
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
        $host     = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $host     = preg_replace('/[^a-zA-Z0-9\-.:]/', '', $host);

        // Validate host against trusted allowlist
        $allowedHosts = ['localhost', '127.0.0.1', '::1', 'cmsfornerd.test'];
        $hostName = explode(':', $host)[0];
        if (!in_array($hostName, $allowedHosts, true)) {
            http_response_code(400);
            die("Untrusted Host header");
        }

        $scriptPath = $_SERVER['SCRIPT_NAME'] ?? '';
        $dirPath  = str_replace('\\', '/', dirname($scriptPath));

        return rtrim($protocol . $host . $dirPath, '/') . '/';
    }

    /**
     * Discovers valid pages from paired content fragments and root files.
     *
     * @param string $fragmentDir Absolute path to the directory containing content fragments.
     * @param string $rootDir Absolute path to the project root directory.
     * @return array<int, array{slug: string, title: string, mtime: int, filemtime: int}> Discovered pages with their slugs, titles, and modification times.
     */
    public static function discoverPages(string $fragmentDir, string $rootDir): array
    {
        // Delegate to PerformanceUtils for caching if available
        if (class_exists('\\CmsForNerd\\PerformanceUtils')) {
            return PerformanceUtils::getCachedDiscoveredPages($fragmentDir, $rootDir);
        }

        return self::directDiscoverPages($fragmentDir, $rootDir);
    }

    /**
     * Discovers pages from content fragments and their corresponding root files.
     *
     * @param string $fragmentDir Absolute path to the directory containing page fragments.
     * @param string $rootDir Absolute path to the project root directory.
     * @return array<int, array{slug: string, title: string, mtime: int, filemtime: int}> Discovered page metadata.
     */
    public static function directDiscoverPages(string $fragmentDir, string $rootDir): array
    {
        $pages = [];

        if (is_dir($fragmentDir)) {
            $rawFragments = glob($fragmentDir . '*-body.inc');
            $fragments = is_array($rawFragments) ? $rawFragments : [];

            foreach ($fragments as $file) {
                $slug = str_replace('-body.inc', '', basename($file));

                // Exclusion list
                $exclude = ['index', 'sitemap', 'empty', '403', '404', 'header', 'footer'];
                if (in_array($slug, $exclude, true)) {
                    continue;
                }

                $masterFile = $rootDir . '/' . $slug . '.php';

                if (file_exists($masterFile)) {
                    $mTime = max(filemtime($masterFile), filemtime($file));
                    $title = ucfirst(str_replace('-', ' ', $slug));

                    $pages[] = [
                        'slug'      => $slug,
                        'title'     => $title,
                        'mtime'     => (int) $mTime,
                        'filemtime' => (int) filemtime($file),
                    ];
                }
            }
        }

        return $pages;
    }

    /**
     * [SECURITY] Generate a Content Security Policy (CSP) Nonce.
     * MUST be used for inline scripts to comply with v3.3+ safety protocols.
     * * @return string A base64 encoded random 16-byte string.
     */
    public static function generateNonce(): string
    {
        return base64_encode(random_bytes(16));
    }

    /**
     * Resolves a valid page name from the request query string.
     *
     * @param string $defaultFallback Page name to use when no query string is present.
     * @param string $invalidFallback Page name to use when the resolved value is invalid.
     * @return string The resolved page filename component.
     */
    public static function resolvePageName(string $defaultFallback, string $invalidFallback = 'index'): string
    {
        $rawPage = match (true) {
            !empty($_SERVER['QUERY_STRING']) => (string) $_SERVER['QUERY_STRING'],
            default                          => $defaultFallback
        };

        // Extra LFI defense: Reject any pages containing path separators or dot-dots
        if (str_contains($rawPage, '/') || str_contains($rawPage, '\\') || str_contains($rawPage, '..')) {
            $rawPage = $invalidFallback;
        }

        $isValid = self::isValidPageName($rawPage);
        $page = $isValid ? $rawPage : $invalidFallback;

        return pathinfo($page, PATHINFO_FILENAME);
    }

    /**
     * Starts or resumes a hardened session with secure cookie attributes and periodic session ID regeneration.
     */
    public static function startSecureSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            // Configure PHP session settings to prevent session hijacking & fixation
            ini_set('session.use_strict_mode', '1');
            ini_set('session.use_only_cookies', '1');

            // Determine if HTTPS is active (all on one single line to pass 4-space indent rule)
            $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');

            // Set session cookie parameters securely
            $cookieParams = [
                'lifetime' => 0,
                'path' => '/',
                'domain' => '',
                'secure' => $isHttps,
                'httponly' => true,
                'samesite' => 'Strict'
            ];

            session_set_cookie_params($cookieParams);
            session_start();
        }

        // Periodic Session ID Regeneration to mitigate session fixation attacks
        if (!isset($_SESSION['session_created_at'])) {
            $_SESSION['session_created_at'] = time();
        } elseif (time() - $_SESSION['session_created_at'] > 1800) {
            session_regenerate_id(true);
            $_SESSION['session_created_at'] = time();
        }
    }

    /**
     * Generates and stores a CSRF token in the active secure session.
     *
     * @return string The session's CSRF token.
     */
    public static function generateCsrfToken(): string
    {
        self::startSecureSession();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return (string) $_SESSION['csrf_token'];
    }

    /**
     * Validates a CSRF token against the token stored in the secure session.
     *
     * @param string|null $token The token supplied with the request.
     * @return bool `true` if the token matches the stored CSRF token, `false` otherwise.
     */
    public static function validateCsrfToken(?string $token): bool
    {
        self::startSecureSession();
        if ($token === null || empty($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Sends recommended OWASP security headers for the current request.
     *
     * Adds the HSTS header only when the request uses HTTPS.
     */
    public static function sendSecurityHeaders(): void
    {
        if (headers_sent()) {
            return;
        }
        header("X-Content-Type-Options: nosniff");
        header("X-Frame-Options: DENY");
        header("X-XSS-Protection: 1; mode=block");
        header("Referrer-Policy: strict-origin-when-cross-origin");
        header("Permissions-Policy: camera=(), microphone=(), geolocation=(), midi=(), payment=()");

        // Only set HSTS if using HTTPS to avoid breaking localhost development or standard HTTP setups
        $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
        if ($isHttps) {
            header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
        }
    }

    /**
     * Restricts requests to the supported HTTP methods.
     *
     * @return void
     */
    public static function validateRequestMethod(): void
    {
        $allowedMethods = ['GET', 'POST', 'HEAD'];
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        if (!in_array($method, $allowedMethods, true)) {
            http_response_code(405);
            header("Allow: " . implode(', ', $allowedMethods));
            die("HTTP Method Not Allowed");
        }
    }
}
