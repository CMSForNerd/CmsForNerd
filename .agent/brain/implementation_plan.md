# bfcache Integration Plan

The goal is to ensure CMSForNerd v3.5 fully supports Back/Forward Cache for instantaneous history navigation.

## Proposed Changes

### [MODIFY] router.js(file:///d:/Users/LinuxMalaysia/CMSForNerd-Project/CmsForNerd/assets/pwa/router.js)
- Add an `AbortController` to the `loadContent` fetch routine to cancel incomplete network requests.
- Bind `window.addEventListener('pagehide', ...)` to trigger the abort controller. This ensures no dangling connections prevent bfcache eligibility when the user navigates away.
- Bind `window.addEventListener('pageshow', ...)` to listen for `event.persisted`. If true, log the bfcache restoration to the console for monitoring.

### [MODIFY] task.md(file:///d:/Users/LinuxMalaysia/CMSForNerd-Project/CmsForNerd/.agent/brain/task.md)
- Append "Phase 4: Back/Forward Cache Optimization" to track implementation progress.

### [MODIFY] implementation_plan.md(file:///d:/Users/LinuxMalaysia/CMSForNerd-Project/CmsForNerd/.agent/brain/implementation_plan.md)
- Append Phase 4 details to the project's documentation.

## Verification Plan

### Automated Tests
- Run `composer lab-check` which executes PHPStan static analysis and syntax checks.

### Manual Verification
1. Start the local server (e.g., using Laravel Herd or `php -S localhost:8000`).
2. Open Chrome DevTools -> Application -> Background Services -> Back/forward cache.
3. Click "Test back/forward cache" to determine if the page is successfully cached.
4. Verify no `notRestoredReasons` exist after navigating away and pressing the back button.

---

# Module 7: Dark Mode Engineering (v3.5)

## User Review Required
> [!IMPORTANT]
> The implementation of a manual Dark Mode toggle in AMP requires using the `<amp-bind>` component to mutate a state variable (e.g., `themeState`) which dynamically updates a class on the `<body>` tag. Please confirm if you want full manual toggle functionality (via an icon in the sidebar) alongside the default system-level `prefers-color-scheme` implementation.

## Proposed Changes

### CSS Architecture
#### [MODIFY] style.css(file:///d:/Users/LinuxMalaysia/CMSForNerd-Project/CmsForNerd/themes/CmsForNerd/style.css)
#### [MODIFY] amp.css(file:///d:/Users/LinuxMalaysia/CMSForNerd-Project/CmsForNerd/themes/CmsForNerd/css/amp.css)
- Refactor all hardcoded colors (`#fff`, `#000`, `#f9f9f9`) to use CSS custom properties (`var(--lab-bg)`, `var(--lab-surface)`, etc.).
- Add a `@media (prefers-color-scheme: dark)` block that reassigns the root variables to a dark color palette.
- Add an explicit `.theme-dark` class that overrides the variables manually (for the toggle button functionality).

### AMP Interactive Logic
#### [MODIFY] nav-helper.inc.php(file:///d:/Users/LinuxMalaysia/CMSForNerd-Project/CmsForNerd/includes/nav-helper.inc.php)
- Inject `<script async custom-element="amp-bind" src="https://cdn.ampproject.org/v0/amp-bind-0.1.js" nonce="<?= $nonce ?>"></script>` to enable AMP state management.
- Update the `<body>` tag to bind a dynamic class based on `themeState`.

#### [MODIFY] amp-sidebar.tpl(file:///d:/Users/LinuxMalaysia/CMSForNerd-Project/CmsForNerd/themes/CmsForNerd/amp-sidebar.tpl)
- Add a "Laboratory Dimmer" (`🌙` / `☀️`) button that triggers `AMP.setState({themeState: themeState == 'theme-dark' ? 'theme-light' : 'theme-dark'})`.

## Verification Plan
1. Toggle the OS appearance settings (Mac/Windows) and verify if the background automatically shifts to dark mode.
2. Open the AMP view (`?view=amp`) and click the Laboratory Dimmer toggle to ensure `amp-bind` successfully overrides the system default without page reload.

---

# Module 8: PWA Architecture Documentation (v3.5)

## Proposed Changes

### [NEW] pwa-architecture.php(file:///d:/Users/LinuxMalaysia/CMSForNerd-Project/CmsForNerd/pwa-architecture.php)
- Serve as the HTTP controller/gateway.
- Include proper SEO metadata (Title, Description, Keywords) explaining CMSForNerd PWA capabilities.

### [NEW] pwa-architecture-body.inc(file:///d:/Users/LinuxMalaysia/CMSForNerd-Project/CmsForNerd/contents/pwa-architecture-body.inc)
- The raw HTML payload outlining how CMSForNerd leverages:
  - Service Workers (`sw.js`).
  - Manifest integration (`manifest.json`).
  - Offline mode resiliency.
  - Back/Forward Cache (`bfcache`) with `AbortController`.
  - Content Security Policy (Nonce injection) for PWA components.

### [MODIFY] left-side.inc(file:///d:/Users/LinuxMalaysia/CMSForNerd-Project/CmsForNerd/contents/left-side.inc)
- Add `<a href="pwa-architecture.php">📱 PWA Implementation</a>` to the desktop navigation sidebar.

### [MODIFY] nav-helper.inc.php(file:///d:/Users/LinuxMalaysia/CMSForNerd-Project/CmsForNerd/includes/nav-helper.inc.php)
- Inject `'pwa-architecture.php' => '📱 PWA Implementation'` into `get_site_pages()` so it renders on the AMP mobile sidebar.

## Verification Plan
1. Navigate to `/pwa-architecture.php` and confirm it renders correctly with `pager.php`.
2. Inspect the AMP Sidebar and Desktop Sidebar to guarantee the "PWA Implementation" link functions correctly.

---

# Module 9: UI Audit Kit (v3.5)

To provide a centralized testing ground for theme elements, glassmorphism, and contrast levels.

## Proposed Changes

### [NEW] ui-kit.php(file:///d:/Users/LinuxMalaysia/CMSForNerd-Project/CmsForNerd/ui-kit.php)
- Serve as the HTTP controller for the UI Diagnostic Lab.
- Pair with `ui-kit-body.inc`.

### [NEW] ui-kit-body.inc(file:///d:/Users/LinuxMalaysia/CMSForNerd-Project/CmsForNerd/contents/ui-kit-body.inc)
- Comprehensive HTML fragment demonstrating:
  - **Color Palette**: Display of `--lab-purple`, `--lab-bg`, etc.
  - **Typography**: H1-H3, Paragraphs, and Monospaced blocks.
  - **Glassmorphism**: Transparent cards with `backdrop-filter`.
  - **Components**: Buttons, Badges, and Competency Tables.
  - **Multi-View**: Compatibility check for Standard vs AMP layouts.

### [MODIFY] index-body.inc(file:///d:/Users/LinuxMalaysia/CMSForNerd-Project/CmsForNerd/contents/index-body.inc)
- Update the "🧪 UI Audit" link to point to the new `ui-kit.php`.

## Verification Plan
1. Visit `/ui-kit.php` and verify all theme tokens render with correct contrast.
2. Toggle Dark Mode and confirm the "Glass Effects" remain legible.
3. Verify AMP view (`/ui-kit.php?view=amp`) passes validation.

---

# Module 10: AMP Acceleration (v3.5)

To document the technical implementation of Accelerated Mobile Pages within the CMSForNerd ecosystem.

## Proposed Changes

### [NEW] amp-acceleration.php(file:///d:/Users/LinuxMalaysia/CMSForNerd-Project/CmsForNerd/amp-acceleration.php)
- Serve as the HTTP controller for the AMP documentation.
- Leverage the `pager($ctx)` dispatcher to support dual-view (Standard/AMP).

### [NEW] amp-acceleration-body.inc(file:///d:/Users/LinuxMalaysia/CMSForNerd-Project/CmsForNerd/contents/amp-acceleration-body.inc)
- Content fragment explaining the AMP Validation, CSS Byte Budget, and Sidebar components.

### [MODIFY] nav-helper.inc.php(file:///d:/Users/LinuxMalaysia/CMSForNerd-Project/CmsForNerd/includes/nav-helper.inc.php)
- Add `'amp-acceleration.php' => '⚡ AMP Acceleration'` to the navigation mapping.

### [MODIFY] left-side.inc(file:///d:/Users/LinuxMalaysia/CMSForNerd-Project/CmsForNerd/contents/left-side.inc)
- Add `<a href="amp-acceleration.php">⚡ AMP Acceleration</a>` to the sidebar.

## Verification Plan
1. Navigate to `/amp-acceleration.php` and verify the standard view.
2. Navigate to `/amp-acceleration.php?view=amp` and verify AMP validation.

---

# Module 11: Workbox Integration (v3.5)

To upgrade the vanilla Service Worker to Google's Workbox library for optimized caching and performance.

## Proposed Changes

### [MODIFY] sw.js(file:///d:/Users/LinuxMalaysia/CMSForNerd-Project/CmsForNerd/sw.js)
- Import Workbox via CDN.
- Use `workbox.routing.registerRoute` for asset caching.
- Implement **Stale-While-Revalidate** for CSS and JS.
- Implement **Cache-First** for images.
- Implement **Network-First** for navigation (with offline fallback).

## Verification Plan
1. In Chrome DevTools, verify that Workbox is successfully loaded in the "Application" tab.
2. Confirm that assets are being cached by Workbox (check for `workbox-runtime` cache).
3. Test offline mode by disabling the network; ensure `/offline.php` still renders.

