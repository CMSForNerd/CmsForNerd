<?php

/**
 * [PERFORMANCE] PerformanceUtils - v4.2.3 Laboratory Standard.
 * This class provides advanced site performance optimizations for the CMS core.
 * It implements static page caching, smart cache invalidation, page metadata caching,
 * and HTTP conditional request headers (ETag, Last-Modified, 304 Not Modified).
 * * Compliance: PHP 8.4+, PSR-12, PHPStan Level 8.
 */

declare(strict_types=1);

namespace CmsForNerd;

final class PerformanceUtils
{
    private static bool $cacheActive = false;

    /**
     * Get the absolute path for the cache directory.
     */
    public static function getCacheDir(): string
    {
        $dir = dirname(__DIR__) . '/data/cache';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        return $dir;
    }

    /**
     * Builds the cache file path for a page and view.
     *
     * @param string $pageName The page name used in the cache filename.
     * @param string $view The view name used in the cache filename.
     * @return string The sanitized HTML cache file path.
     */
    public static function getCacheFilePath(string $pageName, string $view = 'standard'): string
    {
        $safeName = preg_replace('/[^a-zA-Z0-9_\-]/', '', $pageName) ?: 'index';
        $safeView = preg_replace('/[^a-zA-Z0-9_\-]/', '', $view) ?: 'standard';
        return self::getCacheDir() . '/page_' . $safeName . '_' . $safeView . '.html';
    }

    /**
     * Determines whether the current request is eligible for server-side page caching.
     *
     * @return bool `true` if the request is a cacheable GET request without AJAX indicators or custom
     *     session state, `false` otherwise.
     */
    public static function isCacheable(): bool
    {
        // Cache GET requests only
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
            return false;
        }

        // Avoid caching AJAX requests as full pages
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        if ($isAjax) {
            return false;
        }

        // Avoid caching if there is an active session indicating custom state
        if (session_status() === PHP_SESSION_ACTIVE && !empty($_SESSION)) {
            // Keep CSRF and session_created_at as exceptions (don't prevent caching just for CSRF)
            $keys = array_keys($_SESSION);
            $cleanSession = true;
            foreach ($keys as $key) {
                if ($key !== 'csrf_token' && $key !== 'session_created_at') {
                    $cleanSession = false;
                    break;
                }
            }
            if (!$cleanSession) {
                return false;
            }
        }

        return true;
    }

    /**
     * Determines the latest modification time among relevant source files.
     *
     * @return int The latest modification timestamp, or 0 when no applicable files exist.
     */
    public static function getSourceMaxMTime(): int
    {
        static $maxMTime = null;
        if ($maxMTime !== null) {
            return $maxMTime;
        }

        $maxMTime = 0;
        $rootDir = dirname(__DIR__);

        // Scan contents directory
        $contentsDir = $rootDir . '/contents';
        if (is_dir($contentsDir)) {
            $files = glob($contentsDir . '/*');
            if (is_array($files)) {
                foreach ($files as $file) {
                    if (is_file($file)) {
                        $maxMTime = max($maxMTime, (int) filemtime($file));
                    }
                }
            }
        }

        // Scan theme directory
        $themeDir = $rootDir . '/themes/CmsForNerd';
        if (is_dir($themeDir)) {
            $files = glob($themeDir . '/*');
            if (is_array($files)) {
                foreach ($files as $file) {
                    if (is_file($file)) {
                        $maxMTime = max($maxMTime, (int) filemtime($file));
                    }
                }
            }
        }

        // Include SecurityUtils and bootstrap changes
        $bootstrapFile = $rootDir . '/includes/bootstrap.php';
        if (file_exists($bootstrapFile)) {
            $maxMTime = max($maxMTime, (int) filemtime($bootstrapFile));
        }

        return $maxMTime;
    }

    /**
     * Serves a fresh cached page or begins buffering output for a new cache entry.
     *
     * @param string $pageName The page identifier used to locate its cache entry.
     * @param string $view The view variant associated with the page.
     */
    public static function startPageCache(string $pageName, string $view = 'standard'): void
    {
        if (!self::isCacheable()) {
            return;
        }

        $cacheFile = self::getCacheFilePath($pageName, $view);
        $sourceMaxMTime = self::getSourceMaxMTime();

        // If cache exists and is newer than all source modifications, serve it
        if (file_exists($cacheFile) && filemtime($cacheFile) >= $sourceMaxMTime) {
            // Send client conditional headers
            self::sendConditionalHeaders($cacheFile);

            header('X-Cache: HIT');
            header('Cache-Control: public, max-age=3600');
            readfile($cacheFile);
            exit;
        }

        // Cache miss: Start output buffering
        self::$cacheActive = true;
        ob_start();
    }

    /**
     * Stores the buffered page output in the cache and sends it to the client.
     *
     * @param string $pageName The page name used to identify the cache entry.
     * @param string $view The view name used to identify the cache entry.
     */
    public static function endPageCache(string $pageName, string $view = 'standard'): void
    {
        if (!self::$cacheActive) {
            return;
        }

        $html = ob_get_clean();
        if ($html === false) {
            return;
        }

        $cacheFile = self::getCacheFilePath($pageName, $view);
        file_put_contents($cacheFile, $html);

        header('X-Cache: MISS');
        echo $html;

        self::$cacheActive = false;
    }

    /**
     * Sends cache validators and terminates the request with a 304 response when the client cache is current.
     *
     * @param string $cacheFile Path to the cached response file.
     */
    private static function sendConditionalHeaders(string $cacheFile): void
    {
        $mtime = (int) filemtime($cacheFile);
        $etag = '"' . md5_file($cacheFile) . '"';

        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $mtime) . ' GMT');
        header('ETag: ' . $etag);

        $ifNoneMatch = $_SERVER['HTTP_IF_NONE_MATCH'] ?? '';
        $ifModifiedSince = $_SERVER['HTTP_IF_MODIFIED_SINCE'] ?? '';

        if (trim($ifNoneMatch) === $etag || trim($ifModifiedSince) === gmdate('D, d M Y H:i:s', $mtime) . ' GMT') {
            http_response_code(304);
            exit;
        }
    }

    /**
     * Retrieves discovered page metadata from a current cache or generates and stores it when unavailable.
     *
     * @param string $fragmentDir Absolute path to the contents directory.
     * @param string $rootDir Absolute path to the project root directory.
     * @return array<int, array{slug: string, title: string, mtime: int, filemtime: int}> Discovered page metadata.
     */
    public static function getCachedDiscoveredPages(string $fragmentDir, string $rootDir): array
    {
        $metaCacheFile = self::getCacheDir() . '/discovered_pages.json';
        $sourceMaxMTime = self::getSourceMaxMTime();

        if (file_exists($metaCacheFile) && filemtime($metaCacheFile) >= $sourceMaxMTime) {
            $cachedJson = file_get_contents($metaCacheFile);
            if ($cachedJson !== false) {
                $decoded = json_decode($cachedJson, true);
                if (is_array($decoded)) {
                    /** @var array<int, array{slug: string, title: string, mtime: int, filemtime: int}> $decoded */
                    return $decoded;
                }
            }
        }

        // Cache miss: Generate metadata
        $pages = SecurityUtils::directDiscoverPages($fragmentDir, $rootDir);
        file_put_contents($metaCacheFile, json_encode($pages));
        return $pages;
    }

    /**
     * Helper to clear all cache files (useful for administrative tasks).
     */
    public static function clearCache(): void
    {
        $dir = self::getCacheDir();
        $files = glob($dir . '/*');
        if (is_array($files)) {
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }
    }
}
