# CmsForNerd v3.5.1 Task Tracker

## [PHASE: ARCHITECTURE] - COMPLETED ✅

* [x] **Zero-Global Refactor**: Removed `global` keywords; implemented `CmsContext`.
* [x] **Baseline Sync**: Synchronized `index.php` and `template.php` logic.
* [x] **Dual-View Pager**: Integrated `renderStandardLayout` and `renderAmpLayout` in `pager.php`.
* [x] **Modular AMP Header**: Created `pageheader_amp()` in `nav-helper.inc.php`.

## [PHASE: MOBILE MASTERY & UI] - IN PROGRESS 🏗️

### 🌑 Module 7: Dark Mode Engineering (v3.5.1)

* [ ] **CSS Variable Foundation**: Define laboratory color tokens (Background, Surface, Primary, Text) in `amp.css` and standard CSS.
* [ ] **System-Level Detection**: Implement `@media (prefers-color-scheme: dark)` to ensure zero-JS dark mode activation.
* [ ] **AMP Sidebar Toggle**:
* [ ] Research `amp-bind` for a manual dark-mode toggle that persists without standard JS.
* [ ] Add the "Laboratory Dimmer" icon to `amp-sidebar.tpl`.


* [ ] **Accessibility Audit**: Verify contrast ratios for `#8e44ad` (Primary Purple) against dark backgrounds.

### 🤖 Module 9: Automated Lab-Bench

* [ ] **CI Pipeline**: Create `.github/workflows/compliance.yml`.
* [ ] **Enforcement**: Configure GitHub Action to run `composer lab-check` (PHPStan Level 8) on every push.
* [ ] **Reporting**: Setup automated feedback for PRs that violate PSR-12.

## [PHASE: DOCUMENTATION] - IN PROGRESS 🏗️

* [x] **Project Rules Update**: Integrated AMP Content Parity rules.
* [x] **README Sync**: Added "State of Mind" Master Context Block and SoM Protocol.
* [ ] **SOP Verification**: Run `composer compliance` on all new helpers.
* [ ] **Discovery Logic**: Add `<link rel="amphtml">` to standard `pageheader()`.

---

# 🧠 State of Mind Persistence (Session v3.5.1)

### 🌅 Next Session Morning Ritual

1. **Manual Handshake**: Paste the **Master Context Block** from `README.md`.
2. **Environment Check**: Run `composer lab-check` to confirm the baseline is still Level 8.
3. **Objective**: Focus on **Module 7 (Dark Mode)** CSS variables, implementing `@media (prefers-color-scheme: dark)` and updating `amp.css`.

### 🌇 Last Session Snapshot (2026-03-24)

* **Accomplishments**: Successfully implemented Phase 4 of the PWA Architecture (bfcache optimization) via `AbortController` in `router.js`. Created `docs/bfcache-testing-guide.md` and modernized `SECURITY.md` for PWA SPA-Hybrid standards.
* **Mental Anchor**: The Application is fully PWA-capable with instantaneous memory history navigation. The "State of Mind" protocol remains intact and the Git repository is perfectly clean and synchronized.

---

# Task: Sync GitBook Documentation with v3.5

- [x] Research & Audit
    - [x] Analyze `.gitbook.yaml` and `docs/SUMMARY.md`
    - [x] Identify stale version references (v3.4 -> v3.5)
    - [x] Map `docs/*.md` files to `contents/*.inc` functionality
- [x] Update Documentation Content
    - [x] Bump version to v3.5 in all `docs/` files
    - [x] Update "Pair Logic" descriptions to reflect `CmsContext` factory patterns
    - [x] Align architecture diagrams/descriptions with v3.5
- [x] Align Table of Contents
    - [x] Verify all new features (e.g., Turnstile, Hybrid Security) are documented
    - [x] Update `docs/SUMMARY.md` if new pages were added
- [x] Final Review & Notify
    - [x] Report major changes to user
    - [x] Final compliance check

# Task: Sitemap & SEO Synchronization (v3.5)
- [x] Sitemap Logic Sync
    - [x] Audit `sitemap.php` and `sitemap-generator.php`
    - [x] Refactor `sitemap-generator.php` to use "Pair Logic"
    - [x] Sync exclusion lists between generators
- [x] Metadata & Protocol Update
    - [x] Audit `robots.txt` and `labels.rdf`
    - [x] Bump versions to v3.5
    - [x] Link `common-headertag.inc` to dynamic RSS/ROR generators
- [x] Implement Syndication (RSS/ROR)
    - [x] Create `rss.php` (RSS 2.0)
    - [x] Create `ror.php` (ROR XML)
    - [x] Ensure "Pair Logic" consistency across all XML outputs
- [x] Verification
    - [x] Verify XML/HTML page list parity
    - [x] confirm no 404s for SEO assets

# Task: AI Agent Synchronization (v3.5)
- [x] Audit instruction sets
    - [x] Review `.github/copilot-instructions.md`
    - [x] Review `ai-dev.php` and `ai-sop.php`
    - [x] Identify outdated `AI-STATE-SYNC.md` (v3.1.3)
- [x] Update Configuration & Documentation
    - [x] Bump `AI-STATE-SYNC.md` to v3.5
    - [x] Modernize Copilot instructions for v3.5 & Antigravity
    - [x] Sync `docs/ai-dev.md` and `docs/ai-sop.md` with v3.5 content
    - [x] Create `.cursorrules` targeting `PROJECT_RULES.md`
- [x] Final Verification
    - [x] Verify agent-specific triggers (e.g., Copilot @-tags)
    - [x] Ensure consistent "Pair Logic" references across all guides

# Task: Persistence & Protocol (v3.5)
- [x] Create `.agent/workflows/nerd-lab-protocol.md`
- [x] Finalize State of Mind documentation
- [x] Notify user of persistent state capture

# Task: Intelligence Audit & State Sync (v3.5)
- [x] Restore "State of Mind" from persistent artifacts
- [x] Identity PSR-12 compliance failures (File-level docblocks)
- [x] Standardize headers (Docblock -> Declare)
    - [x] index.php, template.php, bootstrap.php
    - [x] SecurityUtils.php, common.inc.php, global-control.inc.php
    - [x] is_bot.php, nav-helper.inc.php
    - [x] sitemap_generator.php, turnstile.php, User.php
    - [x] CmsContext.php and other src/ files
- [x] Expand Protocol for "External Reality Checks"
    - [x] Implement `tools/audit-pre-flight.sh` and `composer audit-pre-flight`
    - [x] Add Git fetch/diff/log to `nerd-lab-protocol.md`
    - [x] Update `blog-state-sync.md` with VCS sync logic
    - [x] **[NEW]** Refined & Formalized `docs/AI-MASTER-PROTOCOL.md` (v3.5)
    - [x] Propagated Master Protocol references to `ai-dev.md`, `ai-sop.md`, `AI-STATE-SYNC.md`, and `bot-intelligence.md`
- [x] Update `CONTRIBUTING.md` with "State of Mind" guidelines
- [x] Sync `DIRECTORY_SECURITY.md` and `DOCS_REQUIREMENTS.md` to v3.5 and GitBook
- [x] Debug & Fix `ror.php` (Fatal error: strict_types)
- [x] Final composer lab-check verification
    - [x] Fix `CmsContextTest` (ArgumentCountError)
    - [x] Fix `global-control.inc.php` indentation
    - [x] Fix `bootstrap.php` brace placement
# CmsForNerd v3.5 Task Tracker

## [PHASE: ARCHITECTURE] - COMPLETED ✅
- [x] **Zero-Global Refactor**: Removed `global` keywords; implemented `CmsContext`.
- [x] **Baseline Sync**: Synchronized `index.php` and `template.php` logic.
- [x] **Dual-View Pager**: Integrated `renderStandardLayout` and `renderAmpLayout` in `pager.php`.
- [x] **Modular AMP Header**: Created `pageheader_amp()` in `nav-helper.inc.php`.

# Task: Dual Testing Implementation (PHPUnit + Pest)
- [x] Configure `composer.json` for isolated testing environments (`test:phpunit`, `test:pest`)
- [x] Uninstall Laravel leftover `TestCase` bindings from Pest configuration
- [x] Apply Pest plugin bypass logic (`--filter CmsForNerd\\\\Tests`) for pure PHPUnit test retention
- [x] Create `docs/testing-guide.md` and link it in `SUMMARY.md`

## [PHASE: DOCUMENTATION] - IN PROGRESS 🏗️
- [x] **Project Rules Update**: Integrated AMP Content Parity rules.
- [ ] **SOP Verification**: Run `composer compliance` on all new helpers.
## [PHASE: PWA TRANSITION] - IN PROGRESS 🚀
- [x] **Phase 1: Manifest & Foundation**
    - [x] Provide Laravel Herd HTTPS (`herd secure`) instructions to user
    - [x] Create `manifest.json` and dummy icon assets (`192x192`, `512x512`)
    - [x] Inject `<link rel="manifest">` into `includes/common.inc.php` and `themes/CmsForNerd/pager.php`
- [x] **Phase 2: Service Worker & Offline Capability**
    - [x] Create `sw.js` (Install & Fetch events)
    - [x] Register `sw.js` via inline script in layout footer
    - [x] Design and implement `offline.php` fallback page
- [x] **Phase 3: SPA-Hybrid App Shell Architecture**
    - [x] Modify `index.php` (Controller) to detect `HTTP_X_REQUESTED_WITH` (fetch/ajax)
    - [x] Serve only `$dataFile` (`-body.inc`) fragment when PWA header is detected
    - [x] Build vanilla JS History API Router to intercept link clicks and hydrate `<main>` avoiding full reload

- [x] **Phase 4: Back/Forward Cache (bfcache) Optimization**
    - [x] Implement `AbortController` in `router.js` to cancel pending `fetch` requests during `pagehide`.
    - [x] Add `pageshow` and `pagehide` listeners to `router.js` to log and monitor bfcache status.
