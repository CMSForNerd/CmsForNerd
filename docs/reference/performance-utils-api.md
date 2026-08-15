---
okf_version: 0.1
type: reference
title: "📋 API Reference: PerformanceUtils Class"
description: "Complete reference for PerformanceUtils, the 3-tier caching engine (Memory, APCu, Disk), max mtime calculator, and page baking utilities."
resource: "file:///docs/reference/performance-utils-api.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [performance-utils, caching, apcu, etag, api-reference]
---

# 📋 API Reference: PerformanceUtils Class

The `\CmsForNerd\PerformanceUtils` class provides performance optimization services, including 3-tier caching (In-Memory, APCu, persistent JSON file), ETag generation, conditional HTTP 304 response handling, and static HTML page baking.

---

## 🏛️ Public Static Methods

### `PerformanceUtils::getSourceMaxMTime(string $baseDir): int`
Computes the maximum file modification timestamp across source content directories (`contents/`, `themes/`, `includes/`). Utilizes a 3-tier caching strategy:
1. **Tier 1:** Static in-memory class cache (`self::$sourceMaxMTime`).
2. **Tier 2:** APCu shared memory cache with installation-specific key hashing (`hash('sha256', $baseDir)`).
3. **Tier 3:** Persistent JSON file cache (`data/cache/source_max_mtime.json`) written with exclusive file locking (`LOCK_EX`).

```php
$maxMtime = PerformanceUtils::getSourceMaxMTime(__DIR__);
```

---

### `PerformanceUtils::handleConditionalRequest(int $maxMtime): void`
Generates an HTTP `ETag` and `Last-Modified` header based on `$maxMtime`. If the client sends matching `If-None-Match` or `If-Modified-Since` headers, it emits an `HTTP/1.1 304 Not Modified` header and immediately terminates execution to save bandwidth and CPU cycles.

```php
PerformanceUtils::handleConditionalRequest($maxMtime);
```

---

### `PerformanceUtils::clearCache(): void`
Resets memory, APCu, and file-based caches.

```php
PerformanceUtils::clearCache();
```

---

### `PerformanceUtils::bakePage(string $url, string $outputPath): bool`
Fetches a dynamic PHP page route via local HTTP buffer or CLI execution and bakes it into a static HTML file at `$outputPath`.

```php
PerformanceUtils::bakePage('http://localhost:8000/user-manual.php', 'build_static/user-manual.html');
```

---

*Deep State of Mind (DSOM) For My AI Protocol | PerformanceUtils API Reference Specification | Harisfazillah Jamel (LinuxMalaysia)*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
