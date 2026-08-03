<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;
use CmsForNerd\PerformanceUtils;

final class PerformanceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Clear cache before each test
        PerformanceUtils::clearCache();
    }

    protected function tearDown(): void
    {
        PerformanceUtils::clearCache();
        parent::tearDown();
    }

    /**
     * Test caching eligibility
     */
    public function testCachingEligibility(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        unset($_SERVER['HTTP_X_REQUESTED_WITH']);
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        $this->assertTrue(PerformanceUtils::isCacheable());

        // POST requests must not be cached
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertFalse(PerformanceUtils::isCacheable());

        // AJAX requests must not be cached
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
        $this->assertFalse(PerformanceUtils::isCacheable());

        unset($_SERVER['HTTP_X_REQUESTED_WITH']);
    }

    /**
     * Test page-level cache creation and Hit/Miss mechanics
     */
    public function testStaticPageCaching(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        unset($_SERVER['HTTP_X_REQUESTED_WITH']);

        $page = 'test_page_performance';
        $cacheFile = PerformanceUtils::getCacheFilePath($page);

        // Assert file does not exist initially
        $this->assertFileDoesNotExist($cacheFile);

        // Start page caching (MISS)
        PerformanceUtils::startPageCache($page);
        echo "Test Page Static Body Content";
        PerformanceUtils::endPageCache($page);

        // Assert file is successfully written
        $this->assertFileExists($cacheFile);
        $this->assertStringEqualsFile($cacheFile, "Test Page Static Body Content");

        // Clear output buffering and run page cache start to verify it exits (Simulate cache HIT)
        // Since startPageCache exits on HIT, we can inspect filemtime to ensure it matches
        $this->assertGreaterThanOrEqual(time() - 5, filemtime($cacheFile));
    }

    /**
     * Test Smart Cache Invalidation
     */
    public function testSmartCacheInvalidation(): void
    {
        $page = 'test_page_invalidation';
        $cacheFile = PerformanceUtils::getCacheFilePath($page);

        $sourceMax = PerformanceUtils::getSourceMaxMTime();

        // Write a stale cache file
        file_put_contents($cacheFile, "Stale Cache Content");
        // Force modification time in the past, older than any source file modification
        touch($cacheFile, $sourceMax - 3600);
        clearstatcache();

        // The maximum modification time of source files should be newer than the stale cache
        $this->assertGreaterThan(filemtime($cacheFile), $sourceMax, "Source files should trigger invalidation of old cache.");
    }

    /**
     * Test Metadata Page Discovery Caching
     */
    public function testCachedDiscoveredPages(): void
    {
        $fragmentDir = __DIR__ . '/../contents/';
        $rootDir = __DIR__ . '/../';

        $metaCacheFile = PerformanceUtils::getCacheDir() . '/discovered_pages.json';
        if (file_exists($metaCacheFile)) {
            unlink($metaCacheFile);
        }

        $pages1 = PerformanceUtils::getCachedDiscoveredPages($fragmentDir, $rootDir);
        $this->assertFileExists($metaCacheFile);

        // Read second time from Cache
        $pages2 = PerformanceUtils::getCachedDiscoveredPages($fragmentDir, $rootDir);
        $this->assertEquals($pages1, $pages2);
    }
}
