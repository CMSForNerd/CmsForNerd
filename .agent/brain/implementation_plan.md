# Implementation Plan: Progressive Web App (PWA) Architecture 

## Objective
To outline the necessary phases, architectural changes, and tasks required to upgrade CMSForNerd v3.5 from a traditional server-rendered flat-file CMS into a **Progressive Web App (PWA) first** framework.

This plan serves as a blueprint to review the impact of the transition before execution.

## The "PWA-First" Philosophy in a PHP CMS
A PWA is designed to behave like a native application (installable, offline-capable, and fast) while still being accessible via a web browser. For CMSForNerd, this means preserving our "Zero-Global", "Pair Logic" PHP back-end, but supercharging the front-end delivery layer.

### Key PWA Requirements:
1. **HTTPS**: Mandatory for Service Workers (Already enforced via `SecurityUtils::checkHttps()`).
2. **Web App Manifest (`manifest.json`)**: Dictates how the app appears when installed (icons, colors, display mode).
3. **Service Worker (`sw.js`)**: A background JavaScript proxy that intercepts network requests, manages caching strategies (offline mode), and handles background sync.

---

## Proposed Phases & Tasks

If approved, the implementation would be executed in three distinct phases to ensure mathematical stability and zero cross-contamination.

### Phase 1: The PWA Foundation (Metadata & Manifest)
This phase introduces the core identity of the PWA without altering existing logic.

*   [ ] **Create `manifest.json`**: Generate the Web App Manifest defining the name, `start_url`, `display: "standalone"`, and theme colors.
*   [ ] **Generate PWA Assets**: Implement a suite of exact-dimension icons (192x192, 512x512) and Apple Touch Icons.
*   [ ] **Update `nav-helper.inc.php`**: Inject the `<link rel="manifest" href="manifest.json">` and vital `theme-color` meta tags into the `pageheader()` function.
*   [ ] **Validation**: Run Lighthouse Audits to ensure "Installability" criteria are met.

### Phase 2: The Service Worker (Offline Capability)
This is the most critical technical challenge. We must intercept PHP-rendered templates and cache them intelligently.

*   [ ] **Create `sw.js` (Service Worker)**: 
    *   *Install Event*: Pre-cache core structural assets (`themes/CmsForNerd/style.css`, fonts, and the `index.php` shell).
    *   *Fetch Event*: Implement a "Stale-While-Revalidate" or "Network-First (Fallback to Cache)" strategy for HTML pages.
*   [ ] **Register the Service Worker**: Add inline JavaScript to `includes/nav-helper.inc.php` to register `sw.js` safely on window load.
*   [ ] **Offline Fallback Page**: Create a dedicated `offline.html` or `offline.php` to display when the user has no connectivity and the requested page isn't cached.

### Phase 3: The "PWA-First" Architectural Shift (Optional / Advanced)
Currently, CMSForNerd relies on full-page reloads (Pair Logic `renderStandardLayout`). A true "PWA-First" framework minimizes reloads.

*   [ ] **App Shell Architecture**: Transition `themes/CmsForNerd/index.php` from rendering the whole page to strictly rendering an "App Shell" (Header, Nav, Footer).
*   [ ] **Content Hydration**: Modify the Controller (`index.php`) so that if a request sends an `HTTP_X_REQUESTED_WITH: XMLHttpRequest` header (or Custom PWA header), it returns *only* the `-body.inc` fragment as a JSON payload or raw DOM string, rather than the full HTML wrapper.
*   [ ] **JavaScript Router**: Add a minimal vanilla JS router to intercept `<a>` clicks, fetch the fragment, and update the `<main>` tag using the History API (eliminating page refreshes).

### Local Environment (Laravel Herd)
Service Workers **strict require** HTTPS (or `127.0.0.1` / `localhost`). Since the laboratory operates on Laravel Herd (`cmsfornerd.test`), the user **must** issue a self-signed certificate using the native Herd CLI.

*   **HTTPS Resolution Action**: Document the `herd secure <site>` command in `windows-setup.md` or a new `pwa-guide.md` so students can test the PWA Service Worker locally.

### Phase 4: Back/Forward Cache (bfcache) Optimization (v3.5.x)
Ensures rapid back/forward navigation history by freezing the memory state of the SPA router.
*   [ ] **Abort Pending Fetches**: Implement `AbortController` in `assets/pwa/router.js` coupled with the `pagehide` event to prevent dangling connections from invalidating bfcache.
*   [ ] **Lifecycle Logging**: Implement `pageshow` listeners in `router.js` caching `event.persisted` to log bfcache restorations for Developer Lab verifications.

