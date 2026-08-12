<?php

/**
 * ==========================================================================
 * FILE: src/PerformanceUtils.php
 * ROLE: Core Performance & Smart Caching Engine (v4.3.0)
 * DESCRIPTION: Provides high-performance static page caching, smart
 *              cache invalidation, metadata caches, and standard HTTP/1.1
 *              conditional requests processing (ETag, Last-Modified, 304).
 * ==========================================================================
 * Compliance: PHP 8.4+, PSR-12, PHPStan Level 8.
 */

declare(strict_types=1);

namespace CmsForNerd;

/**
 * Class PerformanceUtils
 *
 * Implements high-fidelity performance optimization protocols including
 * static page caching, directory iteration cache, and cache state purging.
 *
 * @package CmsForNerd
 */
final class PerformanceUtils
{
    /** @var int Time-to-Live (TTL) limit for runtime memory cached states. */
    private const CACHE_TTL = 5;

    /** @var bool Active caching loop tracker flag. */
    private static bool $cacheActive = false;

    /**
     * @var array<string, array<string, array{is_file: bool, mtime: int}>>
     *     In-memory cache for DirectoryIterator file entries.
     */
    private static array $dirCache = [];

    /** @var int|null Cached max mtime value of source dependencies. */
    private static ?int $sourceMaxMTime = null;

    /** @var int|null Unix epoch timestamp when the source max mtime was cached. */
    private static ?int $sourceMaxMTimeTimestamp = null;

    /**
     * Collects file metadata for a directory and caches the result.
     *
     * @param string $dirPath The directory path to inspect.
     * @return array<string, array{is_file: bool, mtime: int}> Metadata for directory entries, or an empty array when the directory is unavailable.
     */
    private static function getDirMetadata(string $dirPath): array
    {
        if (isset(self::$dirCache[$dirPath])) {
            return self::$dirCache[$dirPath];
        }

        $metadata = [];
        if (is_dir($dirPath)) {
            try {
                $dir = new \DirectoryIterator($dirPath);
                foreach ($dir as $fileinfo) {
                    if ($fileinfo->isDot()) {
                        continue;
                    }
                    $metadata[$fileinfo->getPathname()] = [
                        'is_file' => $fileinfo->isFile(),
                        'mtime'   => $fileinfo->getMTime(),
                    ];
                }
            } catch (\UnexpectedValueException $e) {
                // Fall back to empty metadata on unreadable directory
            }
        }

        self::$dirCache[$dirPath] = $metadata;
        return $metadata;
    }

    /**
     * Resolves the secure absolute path to the directory hosting compiled caches.
     *
     * Automatically creates the cache directory if it does not already exist.
     *
     * @return string Absolute directory path of data caches folder.
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
     * @return string The sanitized cache file path.
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
     * @return bool `true` if the request is a cacheable GET request
     *     without AJAX indicators or custom session state, `false` otherwise.
     */
    public static function isCacheable(): bool
    {
        // Cache GET requests only
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
            return false;
        }

        // Avoid caching AJAX requests as full pages
        // (one line to pass 4-space indent rule)
        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
        if ($isAjax) {
            return false;
        }

        // Avoid caching if there is an active session indicating custom state
        if (session_status() === PHP_SESSION_ACTIVE && !empty($_SESSION)) {
            // Keep CSRF and session_created_at as exceptions (don't prevent caching just for CSRF)
            $diff = array_diff(array_keys($_SESSION), ['csrf_token', 'session_created_at']);
            if (!empty($diff)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Formulates the APCu namespace-isolated identification key.
     *
     * Uses SHA-256 validation to avoid collisions and comply with Sonar requirements.
     *
     * @return string A unique APCu cache key string.
     */
    private static function getApcuKey(): string
    {
        return 'cmsfornerd:source_max_mtime:' . hash('sha256', dirname(__DIR__));
    }

    /**
     * Determines the latest modification time among relevant source files.
     *
     * @return int The latest modification timestamp, or 0 if no relevant files exist.
     */
    public static function getSourceMaxMTime(): int
    {
        if (self::$sourceMaxMTime !== null && self::$sourceMaxMTimeTimestamp !== null) {
            if ((time() - self::$sourceMaxMTimeTimestamp) < self::CACHE_TTL) {
                return self::$sourceMaxMTime;
            }
        }

        $rootDir = dirname(__DIR__);
        $contentsDir = $rootDir . '/contents';
        $themeDir = $rootDir . '/themes/CmsForNerd';
        $bootstrapFile = $rootDir . '/includes/bootstrap.php';

        $currentMtimes = [
            'contents'  => is_dir($contentsDir) ? (int) filemtime($contentsDir) : 0,
            'theme'     => is_dir($themeDir) ? (int) filemtime($themeDir) : 0,
            'bootstrap' => file_exists($bootstrapFile) ? (int) filemtime($bootstrapFile) : 0,
        ];

        // 1. Check APCu cache first if available
        $apcuKey = self::getApcuKey();
        if (function_exists('apcu_fetch')) {
            $apcuData = apcu_fetch($apcuKey);
            if (
                is_array($apcuData)
                && isset($apcuData['max_mtime'], $apcuData['dir_mtimes'], $apcuData['timestamp'])
            ) {
                $mtimesMatch = ($apcuData['dir_mtimes'] === $currentMtimes);
                $isFresh = ((time() - (int) $apcuData['timestamp']) < self::CACHE_TTL);
                if ($mtimesMatch && $isFresh) {
                    self::$sourceMaxMTime = (int) $apcuData['max_mtime'];
                    self::$sourceMaxMTimeTimestamp = (int) $apcuData['timestamp'];
                    return self::$sourceMaxMTime;
                }
            }
        }

        // 2. Check persistent metadata file cache
        $cacheFile = self::getCacheDir() . '/source_max_mtime.json';
        if (file_exists($cacheFile)) {
            $cacheAge = time() - (int) filemtime($cacheFile);
            if ($cacheAge < self::CACHE_TTL) {
                $cachedJson = file_get_contents($cacheFile);
                if ($cachedJson !== false) {
                    $cachedData = json_decode($cachedJson, true);
                    if (is_array($cachedData) && isset($cachedData['max_mtime'], $cachedData['dir_mtimes'])) {
                        if ($cachedData['dir_mtimes'] === $currentMtimes) {
                            self::$sourceMaxMTime = (int) $cachedData['max_mtime'];
                            self::$sourceMaxMTimeTimestamp = (int) filemtime($cacheFile);
                            return self::$sourceMaxMTime;
                        }
                    }
                }
            }
        }

        $maxMTime = 0;

        // Scan contents directory
        $contentsMeta = self::getDirMetadata($contentsDir);
        foreach ($contentsMeta as $meta) {
            if ($meta['is_file']) {
                $maxMTime = max($maxMTime, $meta['mtime']);
            }
        }

        // Scan theme directory
        $themeMeta = self::getDirMetadata($themeDir);
        foreach ($themeMeta as $meta) {
            if ($meta['is_file']) {
                $maxMTime = max($maxMTime, $meta['mtime']);
            }
        }

        // Include bootstrap changes
        if ($currentMtimes['bootstrap'] > 0) {
            $maxMTime = max($maxMTime, $currentMtimes['bootstrap']);
        }

        $cacheData = [
            'max_mtime'  => $maxMTime,
            'dir_mtimes' => $currentMtimes,
            'timestamp'  => time(),
        ];

        file_put_contents($cacheFile, json_encode($cacheData), LOCK_EX);

        if (function_exists('apcu_store')) {
            apcu_store($apcuKey, $cacheData, self::CACHE_TTL);
        }

        self::$sourceMaxMTime = $maxMTime;
        self::$sourceMaxMTimeTimestamp = time();
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
     *
     * Resets memory, APCu, and file-based cache states.
     *
     * @return void
     */
    public static function clearCache(): void
    {
        self::$dirCache = [];
        self::$sourceMaxMTime = null;
        self::$sourceMaxMTimeTimestamp = null;
        if (function_exists('apcu_delete')) {
            apcu_delete(self::getApcuKey());
        }
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
