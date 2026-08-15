---
okf_version: 0.1
type: explanation
title: "🧠 CmsForNerd Architecture & Design Rationale"
description: "Deep-dive architectural explanations covering flat-file philosophy, Zero-Global pipeline, Pair Logic, Dual-View Google AMP, OWASP security, and 3-tier caching."
resource: "file:///docs/explanation/cmsfornerd-architecture-explanation.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [architecture, zero-global, pair-logic, amp, owasp]
---

# 🧠 CmsForNerd Architecture & Design Rationale

Architectural overview and rationale behind CmsForNerd v4.3.0.

---

## 🎯 1. Flat-File Philosophy and SQL Query Scope

CmsForNerd stores 100% of content in flat HTML files in `contents/`. This design completely eliminates SQL query surface vulnerabilities (SQL Injection) within this application. It removes database maintenance overhead, enables native Git version control for all content, and delivers instant page responses directly from filesystem caches.

---

## 🏛️ 2. Zero-Global Pipeline & Pair Logic

CmsForNerd routes requests through a linear pipeline designed to avoid legacy global variables:
```text
HTTP Request -> Controller ([page].php) -> Bootstrap & Sanitization -> createCmsContext() -> Theme Pager (pager.php) -> Includes Fragment (contents/[page]-body.inc) -> HTTP Response
```

Request-scoped state is stored inside the shallow-immutable `CmsContext` object, while site-wide state is managed via `Registry`.

---

## ⚡ 3. Dual-View Google AMP Engine

`themes/CmsForNerd/pager.php` inspects the `?view=amp` query parameter:
* **Standard View:** Renders Glassmorphism layout with `style.css`, full JS, and canonical link pointing to self.
* **Google AMP View:** Renders AMP layout emitting header/footer inline via `pager.php`, calling `pageheader_amp($ctx)`, including `amp-sidebar.tpl`, and applying inline CSS from `amp.css`.
* **Discovery Links:** Bidirectional `<link rel="amphtml">` and `<link rel="canonical">` tags help search engines discover and associate both versions without guaranteeing specific AMP carousel indexing outcomes.

---

## 🛡️ 4. OWASP Security Hardening

* **Path Traversal / Input Validation:** `SecurityUtils::resolvePageName()` strips path manipulation characters (`../`, null bytes).
* **CSP Script Nonces:** Nonce-based script authorization (`$ctx->cspNonce`) restricts script execution while allowing configured external origins and styled inline components.
* **Timing-Attack-Resilient CSRF Guards:** Tokens validated using constant-time `hash_equals()`.
* **Strict Cookies:** Session cookies set with `SameSite=Strict` and `HttpOnly`.

---

## 📱 5. Three-Tier Caching & PWA Support

`PerformanceUtils::getSourceMaxMTime()` computes page modification time across 3 caching tiers (In-Memory, APCu, and persistent JSON file cache).

### Conditional 304 Handling:
Sends `HTTP/1.1 304 Not Modified` when ETag matches `If-None-Match`, avoiding transferring the response body while still requiring request processing and response headers.

### PWA Capabilities:
Service worker (`sw.js`) uses `StaleWhileRevalidate` for styles/scripts, `CacheFirst` for images, and `NetworkFirst` for navigation routes, precaching `/offline.php` to serve when navigation fails.

---

*CmsForNerd Architectural Explanation Deep-Dive | DSOM Protocol 2026 | Harisfazillah Jamel (LinuxMalaysia)*
