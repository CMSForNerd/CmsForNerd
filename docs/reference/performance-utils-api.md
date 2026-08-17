---
okf_version: 0.1
type: reference
title: "📋 PerformanceUtils Class API Reference"
description: "API reference for PerformanceUtils 3-tier caching engine, max mtime computation, and static page baking."
resource: "file:///docs/reference/performance-utils-api.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [performance-utils, caching, apcu, etag, api-reference]
---

# 📋 PerformanceUtils Class API Reference

Performance utilities provided by `\CmsForNerd\PerformanceUtils`:

---

## 🛠️ Public Methods

* `getSourceMaxMTime(string $baseDir): int` — Computes source modification timestamp across Memory, APCu, and file cache.
* `handleConditionalRequest(int $maxMtime): void` — Emits ETags and returns `304 Not Modified` on cache hits.
* `clearCache(): void` — Flushes all cache tiers.
* `bakePage(string $url, string $outputPath): bool` — Compiles a dynamic route to static HTML.

---

*PerformanceUtils API Reference Specification | DSOM Protocol 2026 | Harisfazillah Jamel (LinuxMalaysia)*
