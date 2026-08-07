---
okf_version: 0.1
type: documentation
title: "CmsForNerd Project State Sync (v4.0.0)"
description: "OKF-compliant documentation for AI-STATE-SYNC.md."
resource: "file:///docs/AI-STATE-SYNC.md"
timestamp: 2026-08-07T05:39:28Z
topics: [docs, state, sync, cmsfornerd, project]
---
# CmsForNerd Project State Sync (v4.0.0)

## 🎯 Current Mission
We are maintaining a **Modern PHP 8.4 Laboratory Environment**. The core engine is fully refactored, and we are now focused on documentation synchronization, AI agent discovery, and architectural finalization, focusing on **Glassmorphism** and **Zero-Global** architecture.

## 🚧 Architectural Decisions (MUST BE MAINTAINED)
1. **Master Protocol Governance:** The agent MUST strictly adhere to **[AI-MASTER-PROTOCOL.md](AI-MASTER-PROTOCOL.md)**. This is the governing document for all AI-human synergy.
2. **Intelligence Audit & Pre-Flight:** Mandates `composer audit-pre-flight` and `git status` check at every session start to prevent "Context Decay."
3. **Git-Delta Awareness:** Verify local vs. remote state via `git fetch` and `git log` before trusting any local "Brain" artifacts.
4. **Bootstrap Pattern:** Every root `.php` file is a Front Controller. It MUST NOT load library files directly. It MUST load `includes/bootstrap.php`.
5. **Zero-Global Architecture:** (Critical v3.5 Law)
   - **NO** `global` keywords are allowed in Page Controllers or Functions.
   - **ALL** core dependencies MUST be passed via `CmsContext` Factory Methods.
6. **Namespace:** Everything core is under `namespace CmsForNerd;`.
7. **Context Pattern:** Shared state is passed via the `CmsContext` object. Initialize via `createCmsContext()`.
8. **PSR Compliance:** - **PSR-12:** Formatting enforced via `composer fix-style`.
   - **PSR-1:** Separation of concerns (Logic vs. Symbols).
9. **Strict Typing:** `declare(strict_types=1);` is mandatory at the top of ALL PHP files.
10. **Pair Logic:** Every public page MUST consist of a `.php` controller and a `contents/*-body.inc` fragment.

## 🚦 Completed Refactors
- [x] **Zero-Global v3.5:** Refactored `includes/bootstrap.php` and 30+ controllers to eliminate implicit Global State.
- [x] **Master Protocol v3.5:** Formalized the "Intelligence Handshake" in `docs/AI-MASTER-PROTOCOL.md`.
- [x] `CmsContext.php` namespaced with Property Hooks (PHP 8.4).
- [x] **v3.5 Milestone:** Sitemap "Pair Logic" refactor, dynamic RSS 2.0, and ROR XML implemented.
- [x] **Nerd Lab Protocol:** Codified the persistent "State of Mind" workflow.

# Project State Sync (v4.0.0) - 2026-03-30

**Current Mission**: Finalizing AMP infrastructure and Baseline Synchronization.

**Architectural Updates**:
- **AMP Parity**: (NEW) Every page controller now supports an AMP view by appending `?view=amp`.
- **Baseline Sync**: `index.php` and `template.php` are confirmed mirror-images.
- **Rule Integration**: `PROJECT_RULES.md` now explicitly forbids separate mobile content files.

**Verification Status**: 
- PHPStan Level 8: Pending check on `nav-helper.inc.php`.
- PSR-12: Enforced via `renderAmpLayout` structure.

**Intelligence Handover**: 
The system is ready for automated SEO discovery. The next agent should focus on the `pageheader()` helper to link the Standard and AMP versions together.

## 🚧 Work In Progress
- **Documentation Finalization:** Syncing `docs/` with the latest v3.5 code examples.
- **GitBook Deployment:** Ensuring all markdown files reflect the "Laboratory" aesthetic.

## 🧪 Verification Commands
- `composer lab-check`: Verifies environment and strictness.
- `composer compliance`: Verifies PSR-12, Static Analysis (Level 8), and Unit Tests.
