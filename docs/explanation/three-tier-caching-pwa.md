---
okf_version: 0.1
type: explanation
title: "🧠 Three-Tier Caching Pipeline and PWA Offline Architecture"
description: "Explanation of PerformanceUtils 3-tier caching hierarchy and Progressive Web App service worker offline capabilities."
resource: "file:///docs/explanation/three-tier-caching-pwa.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [caching, performance, pwa, apcu, etag]
---

# 🧠 Three-Tier Caching Pipeline and PWA Offline Architecture

Performance optimization services in CmsForNerd.

---

## ⚡ Caching & PWA Architecture

1. **3-Tier Caching:** Memory Cache > APCu Shared Memory > Persistent JSON Cache (`data/cache/source_max_mtime.json`).
2. **Conditional 304 Handling:** Sends `HTTP/1.1 304 Not Modified` when ETag matches `If-None-Match`.
3. **PWA Offline Support:** Service worker (`sw.js`) and manifest (`manifest.json`) fallback to `offline.php` when disconnected.

---

*CmsForNerd Caching & PWA Explanation | DSOM Protocol 2026 | Harisfazillah Jamel (LinuxMalaysia)*
