---
okf_version: 0.1
type: skill
title: "PHP Performance Optimization and Benchmarking Standards"
name: "php-performance-and-benchmarking"
description: "High-efficiency routines for directory pre-scanning, O(1) array lookups, stat cache clearing, and JSON caching."
topics: [php, performance, caching, benchmarking, optimization]
timestamp: 2026-08-01T09:00:00Z
---

# ⚡ PHP Performance Optimization and Benchmarking Standards

## Purpose
This skill provides specific algorithms and benchmarking techniques designed to optimize execution times, limit disk I/O, and maintain high-efficiency lookups within hot paths.

## When to use this skill
Execute this skill when writing loops, reading file attributes from directories, designing micro-caches, or benchmarking filesystem and network executions in PHP.

## Guidelines & Best Practices

### 1. Directory Metadata Pre-scanning & Recursive Traversals
When retrieving metadata (such as file existence or last modification times) across directories containing many files, avoid executing sequential `file_exists()` and `filemtime()` calls within a loop.
- Use `DirectoryIterator` or `FilesystemIterator` to perform a single pre-scan of the target folder.
- When initializing `DirectoryIterator` in PHP, wrap the instantiation and iteration in a try-catch block catching `UnexpectedValueException` to gracefully handle unreadable or inaccessible directories.
- To optimize recursive directory traversals in PHP and avoid the O(N^2) complexity of repeated `array_merge()` calls, utilize a standard recursive helper function that accumulates results in-place via a reference parameter (e.g., `array &$results`). In PHP, recursive helper functions are faster than recursive anonymous closures because they avoid closure instantiation and invocation overhead.

### 2. O(1) Array Membership Checks
Avoid using the O(N) sequential search of `in_array()` inside heavy loops.
- Convert your exclusion/search list into a `static` associative array where the search terms are stored as keys.
- Use `isset($search_array[$term])` to perform O(1) lookups:
  ```php
  static $excluded = [
      'index.html' => true,
      'error.log'  => true,
  ];
  if (isset($excluded[$filename])) { ... }
  ```

### 3. Stat Cache Resetting & Bot Benchmarking Tool
PHP caches filesystem metadata internally for optimization.
- To guarantee a level playing field and precise metrics, always invoke `clearstatcache()` programmatically within the PHP benchmark or setup script itself, rather than running a separate shell command.
- To establish a performance baseline and verify optimization gains, the project utilizes the benchmark script `tools/bench_is_bot.php` which compares synchronous file retrieval against parallel `curl_multi` fetches.
- The benchmark tool `tools/bench_is_bot.php` is OKF v0.1 compliant, incorporating YAML frontmatter and a DSOM digital signature block inside its opening file-level block comment.

### 4. Hybrid Caching in PerformanceUtils
The codebase implements static page caching, smart cache invalidation (tracking file/folder modification times), metadata caching (discovered pages JSON file), and HTTP conditional request handling (ETag / Last-Modified returning 304 responses) inside `PerformanceUtils` to optimize execution performance.
- **SHA-256 APCu Cache Keys:** Near `PerformanceUtils::getApcuKey()`, hashing the SHA-256 (`hash('sha256', ...)`) of the project root directory isolates installations for the global source-mtime metadata cache.
- **Hybrid Caching Layer:** The performance optimization for `getSourceMaxMTime()` in `PerformanceUtils.php` uses a hybrid caching mechanism: an installation-specific APCu memory cache layer falling back to a persistent JSON file (`data/cache/source_max_mtime.json`) written with exclusive locking (`LOCK_EX`). To ensure cache invalidation, it verifies both the directory modification times of parent source folders and a 5-second TTL fallback.
- **Private Static Properties & TTL Validation:** The static cache variable and its timestamp inside `getSourceMaxMTime()` are stored as class-level private static properties (`self::$sourceMaxMTime` and `self::$sourceMaxMTimeTimestamp`) so that `PerformanceUtils::clearCache()` can fully clear memory, APCu, and file-based cache states. To avoid serving stale file timestamps indefinitely in long-running processes (e.g., PHP-FPM, Task Runners), the static in-memory cached modification time in `PerformanceUtils::getSourceMaxMTime()` is validated against `CACHE_TTL` on early return.
- **PHP Compatibility:** To maintain compatibility with older PHP versions (pre-8.3), define class-level constants without explicit type hints (e.g., `private const CACHE_TTL = 5;` rather than `private const int CACHE_TTL = 5;`). Maintain and utilize the `data/cache/` directory for runtime caches. Ensure that the `data/cache/` directory is registered in `.gitignore` to prevent committing session cache files to the repository.


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-05*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
