# 🏁 Walkthrough: Mobile Mastery & State of Mind (v3.5.0)

I have completed the integration of the **AMP Dual-View Engine** and the formalization of the **"State of Mind" (SoM) Protocol**. This milestone moves CMSForNerd from a desktop-centric laboratory to a high-performance, mobile-validated environment with a robust human-AI synchronization workflow.

## 🚀 Key Accomplishments (v3.5.0 Update)

### 1. Dual-View Architecture (AMP Integration)

---

* **The Router**: Rebuilt `themes/CmsForNerd/pager.php` as a decision engine. It now detects `?view=amp` and routes the request through a specialized mobile pipeline while reusing the same `-body.inc` content fragments.
* **Fragment Transformation**: Implemented an Output Buffer (OB) processor that dynamically swaps `<img>` tags for `<amp-img layout="responsive">` tags, satisfying strict AMP validation without manual content duplication.
* **The Interactive Sidebar**: Created `themes/CmsForNerd/amp-sidebar.tpl`. This provides a zero-JavaScript mobile navigation experience using the `amp-sidebar` component, featuring active-page highlighting.

### 2. Defensive Front-End Guardrails

* **CSS Size Guard**: Integrated a file-size validator in `nav-helper.inc.php`. It monitors `amp.css` and injects an HTML warning if the code exceeds the strict 75KB AMP limit.
* **CSP Nonce Propagation**: Hardened the security layer to ensure that the `amp-sidebar` script and the core AMP library are explicitly authorized via cryptographic nonces, preventing XSS even in mobile view.
* **Z-Index Layering**: Resolved "Ghost Clicks" by establishing a strict stacking context for the hamburger menu and the sidebar overlay.

### 3. "State of Mind" (SoM) Formalization

---

* **The Master Context Block**: Defined a "Golden Handshake" for manual AI sessions. This block includes the Master Prompt and the core architecture files (`bootstrap.php`, `pager.php`, `nav-helper.inc.php`) to instantly synchronize any LLM with the project’s strict standards.
* **Manual Handshake Protocol**: Established the "Morning Ritual" (loading MVC context) and "Evening Snapshot" (generating walkthrough summaries) as standard operating procedures.
* **Root README Update**: Successfully integrated the SoM protocol into the main `README.md` to ensure any contributor (human or AI) understands the entry requirements.

### 4. Cleanup & Repository Hygiene

* **Legacy Purge**: Successfully removed `themes/CmsForNerdOld-20260102` and verified that no hardcoded paths remain in the core logic.
* **Versioning**: Finalized the `v3.5.0` milestone with an annotated Git tag and synchronized the `CHANGELOG.md` and `RELEASE_NOTES.md`.

## 🛠️ Verification Results (v3.5.0)

* **AMP Validation**: Passed via `amp-validator` CLI. All images and sidebar components are compliant.
* **PHPStan Level 8**: Verified. The new Dual-View routing logic preserves strict type-safety and null-safety.
* **Manual Sync Test**: Verified that the "Master Context Block" successfully allows a fresh AI instance to identify "Pair Logic" and "Context Factory" patterns without errors.

---

## 🔬 Architectural Deep-Dive: SoM Persistence

**The Problem**: Manual AI chats often "forget" the project's strict architectural boundaries (e.g., trying to use `global` variables or standard HTML in an AMP view).
**The Solution**: The **MVC (Minimum Viable Context)** approach. By providing the AI with the three core engine files at the start of every session, we force the LLM to stay within the "Laboratory Bounds."

---

> **Final Sync Check (2026-01-05):** Verified and committed the `v3.5.0` documentation suite. The "State of Mind" protocol is now the official gateway for all development sessions.
> **Mental Anchor (Session End):** The mobile engine is interactive, the security headers are robust, and the AI synchronization protocol is finalized. The repository is in a "Clean & Green" state, ready for next task.

---

# 🏁 Walkthrough: GitBook Documentation Sync (v3.5)

I have completed the total synchronization of the **CMSForNerd v3.5** documentation (`docs/` directory). Every manual, laboratory worksheet, and setup guide is now architecturally aligned with the latest PHP 8.4 codebase.

## 🚀 Key Accomplishments

### 1. Core Manual Synchronization
* **[INSTALLATION.md](../../docs/INSTALLATION.md)**: Updated all requirements to PHP 8.4+ and synchronized with `contents/installation-body.inc`.
* **[lab-manual.md](../../docs/lab-manual.md)**: Fully expanded with all six laboratory modules, providing a comprehensive narrative for students.
* **[welcome-kit.md](../../docs/welcome-kit.md)**: Updated "Nerd Stack" tables and troubleshooting guides.

### 2. Laboratory Worksheet Standardisation
All five laboratory modules were converted to Markdown and updated to v3.5 standards:
* **Module 1**: [Modern Architecture](../../docs/lab-module1.md) (Property Hooks).
* **Module 2**: [PSR-12 Standards](../../docs/lab-module2.md) (PHPCBF).
* **Module 3**: [Defensive Engineering](../../docs/lab-module3.md) (CSP Nonces & Turnstile).
* **Module 4**: [Automated Testing](../../docs/lab-module4.md) (PHPUnit 11/12).
* **Module 5**: [Coverage & QA](../../docs/lab-module5.md) (Xdebug & CRAP Index).

### 3. Graduation & Security
* **[NEW] [graduation.md](../../docs/graduation.md)**: Created the documentation for the certification process.
* **[final-exam.md](../../docs/final-exam.md)**: Integrated actual "Broken Code" snippets for student challenges.
* **Security Index Bumps**: Standardised `index.php` and `index.html` as v3.5 security gateways.

### 4. AI Agent Synchronization
* **[AI-STATE-SYNC.md](../../docs/AI-STATE-SYNC.md)**: Modernized the project state synchronization guide for v3.5.
* **Instruction Sets**: Consolidated rules for **Google Gemini**, **GitHub Copilot** (`.github/copilot-instructions.md`), and **Cursor** (`.cursorrules`).
* **SOP Sync**: Aligned the [AI Development Guide](../../docs/ai-dev.md) and [AI SOP](../../docs/ai-sop.md) with the v3.5 architecture.

### 5. Persistence of "State of Mind"
* **[NEW] [nerd-lab-protocol.md](../workflows/nerd-lab-protocol.md)**: Established a formal workflow for future sessions to ensure architectural and educational continuity.
* **[AI-STATE-SYNC.md](../../docs/AI-STATE-SYNC.md)**: Updated as the primary "Knowledge Hub" for resuming work.

### 6. Intelligence Infrastructure (v3.5 Expansion)

* **[NEW] [AI-MASTER-PROTOCOL.md](../../docs/AI-MASTER-PROTOCOL.md)**: Established a unified "Constitution" for AI synergy, consolidating the Morning Ritual (Audit) and Wrap-up Protocol.
* **Master Protocol Propagation**: Successfully updated `ai-dev.md`, `ai-sop.md`, `AI-STATE-SYNC.md`, and `bot-intelligence.md` to reference the Master Protocol, creating a complete knowledge graph for agents.
* **[NEW] `tools/audit-pre-flight.sh**`: Created a strict bash script to enforce state synchronization (Git Drift, Brain Artifacts, PHP 8.4 Env).
* **Composer Integration**: Integrated `@audit-pre-flight` into `composer compliance` and `composer lab-check` for a Fail-Fast mechanism.
* **Agent Protocol Update**: Mandated "Strict Pre-Flight" checks in `.cursorrules`, `.clinerules`, and `docs/AI-STATE-SYNC.md`.

### 7. Final Release (v3.5)
* **Root README Sync**: Bumped [README.md](../../README.md) to v3.5, describing the "Pair Logic" and AI synergy.
* **Tagging**: Successfully created and pushed the `v3.5` release tag to origin.
* **Release Readiness**: All internal guides (`sitemap-guide.md`, `csp-nonce.md`) are synchronized with the 2026 laboratory standards.
* **Security Documentation**: Hardened `INSTALLATION.md` by replacing broad `chmod` commands with secure, granular permission instructions for Linux environments.
* **Compliance Restoration**: Standardized 10+ PHP file headers to achieve "Zero-Error" PSR-12 status.
* **Structural Bugfix**: Resolved `Fatal error` in `ror.php` by moving XML declarations inside the PHP block.
* **Governance Expansion**: Updated `CONTRIBUTING.md` to include "State of Mind" guidelines for AI-assisted contributors.
* **State of Mind Documentation**: Published a new [blog post](../../docs/blog-state-sync.md) detailing the "Internal vs. External" Reality Check protocol for VCS synchronization.

### 7. Intelligence Audit Tooling
* **[NEW] `tools/audit-pre-flight.sh`**: Created a strict bash script to enforce state synchronization (Git Drift, Brain Artifacts, PHP 8.4 Env).
* **Composer Integration**: Integrated `@audit-pre-flight` into `composer compliance` and `composer lab-check` for a Fail-Fast mechanism.
* **Agent Protocol Update**: Mandated "Strict Pre-Flight" checks in `.cursorrules`, `.clinerules`, `.github/copilot-instructions.md`, and `docs/AI-STATE-SYNC.md`.
* **Protocol Refinement**: Formalized the Master Protocol with a "Core Commands Reference" and clear "Session Wrap-up" checklist.
* **Connected Intelligence**: Established bidirectional linking between `AI-MASTER-PROTOCOL.md` and the entire documentation ecosystem.

### 8. Testing Infrastructure Upgrade (Pest + PHPUnit)
* **[NEW] Dual Executor Strategy**: Reconfigured `composer.json` to natively support simultaneous testing frameworks.
* **Legacy Preservation**: The `composer test:phpunit` command isolates and executes only the original `CmsForNerd\Tests` namespace to guarantee exact backward compatibility.
* **Modern Expansion**: The `composer test:pest` command natively wraps PHPUnit, executing the entirety of the suite (both legacy tests and new Pest `Feature`/`Unit` syntax tests) simultaneously.
* **Compliance Integration**: Unified `composer compliance` to utilize the broad Pest test runner to validate the whole ecosystem.
* **Artifact Cleanup**: Safely purged conflicting Laravel scaffolding (e.g., `tests/TestCase.php`).

### 9. Progressive Web App (PWA) Architecture
* **[NEW] Phase 1: Installability**: Built `manifest.json` and automatically injected `<link rel="manifest">` into all layout headers alongside `theme-color` meta tags, backed by generated `192x192` and `512x512` placeholder icons.
* **[NEW] Phase 2: Offline Resilience**: Engineered `sw.js` (Service Worker) to intercept network requests. It actively precaches core styling (`style.css`), the manifest, and icons. In the event of a network failure, it serves a custom `offline.php` fallback page rather than the browser dinosaur.
* **[NEW] Phase 3: SPA-Hybrid Routing**: Rewired the core controller (`index.php`) to detect `XMLHttpRequest` calls headers. Interfaced with a custom Vanilla Javascript `History API` router (`assets/pwa/router.js`) that hijacks standard link clicks and fetches only the HTML fragment payload (`-body.inc`), completely eliminating full-page reloads and simulating a true Single Page Application.
* **[NEW] Phase 4: Back/Forward Cache (bfcache) Optimization**: Hardened the SPA router (`router.js`) for instantaneous history navigation. Engineered an `AbortController` coupled with the `pagehide` event to actively terminate pending network fetches during rapid navigation. Integrated `pageshow` listeners monitoring `event.persisted` to ensure seamless memory-state restorations without triggering hydration cycles.


> [!IMPORTANT]
> **Testing PWA Locally (Laravel Herd)**: Service Workers *strictly require* HTTPS to install. On your local Laravel Herd environment, you must open your terminal and run:
> ```bash
> herd secure cmsfornerd
> ```
> This will generate a local TLS certificate allowing you to access `https://cmsfornerd.test` and successfully trigger the PWA installation prompt and Service Worker registration.

## 🛠️ Verification Results

* **Link Audit**: `SUMMARY.md` was exhaustively checked to ensure all sections are navigable.
* **Architecture Check**: All documentation now correctly references the `CmsContext` factory pattern (`createCmsContext()`).
* **Environment Readiness**: Setup guides now include verified terminal commands for Debian/Ubuntu, AlmaLinux, and Windows (Laravel Herd).

# Architectural Walkthrough: AMP Integration

## The Problem
Maintaining separate HTML files for AMP and Standard views violates the "Pair Logic" and increases technical debt.

## The Solution: Fragment Transformation
We utilize PHP's output buffering to treat the `-body.inc` fragment as a raw data stream.
1. The Controller (`index.php`) initializes the `CmsContext`.
2. The Pager detects `$_GET['view'] === 'amp'`.
3. The Pager captures `pagecontent()` into a variable.
4. `str_replace()` is used to swap `<img>` with `<amp-img>` to satisfy AMP validation while reusing the exact same source fragment.

## Key Files
- `includes/nav-helper.inc.php`: Houses the strict AMP <head> boilerplate.
- `themes/CmsForNerd/pager.php`: The decision engine for view-switching.

The documentation suite is now **100% synchronized** and ready for the v3.5 release milestone.

> **Final Sync Check (2026-01-04):** Verified and committed `docs/AI-STATE-SYNC.md` and `docs/HISTORY.md` to ensure the repository reflects the exact "State of Mind" of the v3.5 release. Use `git log` to verify the commit `docs: sync AI state and history for v3.5 compliance`.

> **Mental Anchor (Session End):** We have fully formalized the "Intelligence Audit" ecosystem. The `master-protocol` is now the Single Source of Truth for agent behavior. The environment is clean, compliant (Level 8), and ready for future "happy flow."

> **Final Sync Check (2026-01-04):** Verified and committed the consolidated Master Protocol ecosystem. The repository now reflects the exact "State of Mind" required for high-availability agentic development.


