---
okf_version: 0.1
type: explanation
title: "🧠 Explanation: Three-Tier Caching Pipeline & PWA Architecture"
description: "Understand CmsForNerd's three-tier caching pipeline (Memory, APCu, Disk), ETag/304 handling, and Progressive Web App offline capabilities."
resource: "file:///docs/explanation/three-tier-caching-pwa.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [caching, performance, pwa, apcu, etag]
---

# 🧠 Explanation: Three-Tier Caching Pipeline & PWA Architecture

CmsForNerd combines a high-performance **Three-Tier Caching Pipeline** with a zero-configuration **Progressive Web App (PWA)** architecture.

---

## ⚡ 1. The Three-Tier Caching Pipeline

To determine page modification state without scanning disk folders on every request, `PerformanceUtils::getSourceMaxMTime()` uses a 3-tier caching hierarchy:

```text
Incoming Request
       │
       ▼
[ Tier 1: In-Memory Static Property Cache ] ──(Hit)──> Return $mtime
       │ (Miss)
       ▼
[ Tier 2: APCu Shared Memory Cache ]        ──(Hit)──> Return $mtime
       │ (Miss)
       ▼
[ Tier 3: Persistent JSON File Cache ]       ──(Hit)──> Return $mtime
  (data/cache/source_max_mtime.json)
       │ (Miss)
       ▼
[ Disk Traversal & File Write (LOCK_EX) ]
```

### Conditional HTTP 304 Responses:
If the computed ETag matches the client's `If-None-Match` header, `PerformanceUtils::handleConditionalRequest()` sends `HTTP/1.1 304 Not Modified` and terminates early, reducing server CPU and bandwidth consumption to near-zero.

---

## 📱 2. Progressive Web App (PWA) Capabilities

CmsForNerd includes PWA capabilities built directly into the engine:

* **Web App Manifest (`manifest.json`):** Configures app name, icons, theme colors, and standalone display mode.
* **Service Worker (`sw.js`):** Implements network-first caching for dynamic pages and cache-first strategy for static assets (`style.css`, images).
* **Offline Page Controller (`offline.php`):** Renders a custom offline fallback view when network connectivity is unavailable.

---

*Deep State of Mind (DSOM) For My AI Protocol | 3-Tier Caching & PWA Architecture | Harisfazillah Jamel (LinuxMalaysia)*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
