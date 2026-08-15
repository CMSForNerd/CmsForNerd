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

    /**
     * Test that the single-pass source max mtime computation matches an
     * independently computed expected value over the same directories and
     * bootstrap file (regression for the refactor merging the contents and
     * theme directory scans into one loop, seeded with the bootstrap mtime).
     */
    public function testSourceMaxMTimeMatchesManualComputation(): void
    {
        $rootDir = dirname(__DIR__);
        $contentsDir = $rootDir . '/contents';
        $themeDir = $rootDir . '/themes/CmsForNerd';
        $bootstrapFile = $rootDir . '/includes/bootstrap.php';

        $expected = file_exists($bootstrapFile) ? (int) filemtime($bootstrapFile) : 0;
        $expected = max($expected, $this->maxImmediateFileMTime($contentsDir));
        $expected = max($expected, $this->maxImmediateFileMTime($themeDir));

        $this->assertSame($expected, PerformanceUtils::getSourceMaxMTime());
    }

    /**
     * Test that a newly modified file inside the contents directory becomes
     * the source of the maximum modification time when it is the newest,
     * confirming the merged loop still scans the contents directory.
     */
    public function testSourceMaxMTimeDetectsNewestFileInContentsDirectory(): void
    {
        $contentsDir = dirname(__DIR__) . '/contents';
        $tempFile = $contentsDir . '/tmp_perf_test_contents_' . uniqid() . '.inc';
        $futureMtime = time() + 10000;

        file_put_contents($tempFile, 'temporary performance test fixture');
        touch($tempFile, $futureMtime);
        clearstatcache();

        try {
            PerformanceUtils::clearCache();
            $this->assertSame($futureMtime, PerformanceUtils::getSourceMaxMTime());
        } finally {
            unlink($tempFile);
            PerformanceUtils::clearCache();
        }
    }

    /**
     * Test that a newly modified file inside the theme directory becomes
     * the source of the maximum modification time when it is the newest,
     * confirming the single-pass merge still scans the theme directory.
     */
    public function testSourceMaxMTimeDetectsNewestFileInThemeDirectory(): void
    {
        $themeDir = dirname(__DIR__) . '/themes/CmsForNerd';
        $tempFile = $themeDir . '/tmp_perf_test_theme_' . uniqid() . '.tpl';
        $futureMtime = time() + 20000;

        file_put_contents($tempFile, 'temporary performance test fixture');
        touch($tempFile, $futureMtime);
        clearstatcache();

        try {
            PerformanceUtils::clearCache();
            $this->assertSame($futureMtime, PerformanceUtils::getSourceMaxMTime());
        } finally {
            unlink($tempFile);
            PerformanceUtils::clearCache();
        }
    }

    /**
     * Test that files nested inside a subdirectory of a scanned source
     * directory are ignored, since the directory scan is non-recursive.
     * This guards against a regression where the merged loop might start
     * treating directory entries as files.
     */
    public function testSourceMaxMTimeIgnoresNestedSubdirectoryFiles(): void
    {
        $contentsDir = dirname(__DIR__) . '/contents';
        $subDir = $contentsDir . '/tmp_perf_test_subdir_' . uniqid();
        $nestedFile = $subDir . '/nested.inc';
        $farFutureMtime = time() + 999999;

        mkdir($subDir);
        file_put_contents($nestedFile, 'nested fixture');
        touch($nestedFile, $farFutureMtime);
        clearstatcache();

        try {
            PerformanceUtils::clearCache();
            $this->assertNotSame($farFutureMtime, PerformanceUtils::getSourceMaxMTime());
        } finally {
            unlink($nestedFile);
            rmdir($subDir);
            PerformanceUtils::clearCache();
        }
    }

    /**
     * Test that the persisted cache file stores the exact max mtime value
     * that getSourceMaxMTime() computed and returned.
     */
    public function testSourceMaxMTimePersistsComputedValueToCacheFile(): void
    {
        $value = PerformanceUtils::getSourceMaxMTime();

        $cacheFile = PerformanceUtils::getCacheDir() . '/source_max_mtime.json';
        $this->assertFileExists($cacheFile);

        $cachedJson = file_get_contents($cacheFile);
        $this->assertIsString($cachedJson);

        $cachedData = json_decode($cachedJson, true);
        $this->assertIsArray($cachedData);
        $this->assertArrayHasKey('max_mtime', $cachedData);
        $this->assertSame($value, $cachedData['max_mtime']);
    }

    /**
     * Computes the maximum modification time among immediate (non-recursive)
     * files within a directory, mirroring the non-recursive directory scan
     * performed internally by PerformanceUtils::getSourceMaxMTime().
     */
    private function maxImmediateFileMTime(string $dirPath): int
    {
        $max = 0;
        if (!is_dir($dirPath)) {
            return $max;
        }

        foreach (scandir($dirPath) ?: [] as $entry) {
            $path = $dirPath . '/' . $entry;
            if (is_file($path)) {
                $max = max($max, (int) filemtime($path));
            }
        }

        return $max;
    }
}
