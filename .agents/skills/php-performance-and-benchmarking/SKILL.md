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

### 1. Directory Metadata Pre-scanning
When retrieving metadata (such as file existence or last modification times) across directories containing many files, avoid executing sequential `file_exists()` and `filemtime()` calls within a loop.
- Use `DirectoryIterator` or `FilesystemIterator` to perform a single pre-scan of the target folder.
- Cache the findings (such as filename as key, attributes as sub-values) in a local associative array for fast, subsequent lookups.

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

### 3. Stat Cache Resetting in Benchmarks
PHP caches filesystem metadata internally for optimization.
- Before running or measuring any PHP benchmarks involving filesystem I/O, always clear this internal cache to guarantee a level playing field and precise metrics:
  ```bash
  php -r 'clearstatcache();'
  ```

### 4. File-Based Performance Caching
Maintain and utilize the `data/cache/` directory for runtime caches (such as bot dynamic ranges or API responses).
- Cache structures must be serialized as JSON files.
- Ensure that the `data/cache/` directory is registered in `.gitignore` to prevent committing session cache files to the repository.


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-05*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
