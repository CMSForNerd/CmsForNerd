---
description: Daily Workflow for CMSForNerd Laboratory (v3.5)
okf_version: 0.1
type: documentation
title: "🧪 The Nerd Lab Protocol (v3.5)"
timestamp: 2026-08-07T05:39:28Z
topics: [workflows, nerd, lab, protocol, intelligence]
---
# 🧪 The Nerd Lab Protocol (v3.5)

This workflow defines the "State of Mind" and sequence of operations required to maintain the CMSForNerd Laboratory. Follow these steps every time you resume work or collaborate with other developers/agents.

## 1. Intelligence Audit (The "State of Mind")
// turbo
1. **Git Reality Check:**
    - Run `git fetch origin master` to sync remote metadata.
    - Run `git log master..origin/master --oneline` to see what other devs have pushed.
    - Run `git status` to identify local manual changes or untracked files.
2. **Brain Audit:**
    - Consult `task.md` and `walkthrough.md` in the `.agent/brain/` directory for persistent context.
    - Match the `git log` against these records; if they differ, the "Environment Logic" has drifted.
3. **Strategic Alignment:**
    - Read `docs/AI-STATE-SYNC.md` to understand the current architectural milestone.
    - Review `HISTORY.md` to track recent granular commits.

## 2. Structural Synchronization
1. **Baseline Check:** Ensure `index.php` and `template.php` are logically identical. They are the "Single Source of Truth."
2. **Controller Sync:** Verify all `.php` page controllers reflect the current version and security headers.
3. **Theme/CSS Check:** Audit `:root` variables in `themes/CmsForNerd/style.css` and ensures `theme.php` versioning matches the core.

## 3. The "Pair Logic" Audit
1. Verify that every `.php` controller has a corresponding `contents/*-body.inc` fragment.
2. Ensure `includes/sitemap_generator.php` correctly discovers these pairs.
3. Sync SEO protocols: `sitemap.php`, `rss.php`, `ror.php`, `robots.txt`, and `labels.rdf`.

## 4. Documentation & Educational Integrity
1. **GitBook Sync:** Every logical change in `contents/` MUST be reflected in the `docs/` markdown files.
2. **"The Why" Comments:** code MUST include "Laboratory-style" comments explaining PHP 8.4 patterns for educational purposes.

## 5. Agentic Verification
1. Run `composer fix-style` (PSR-12).
2. Run `composer compliance` (PHPUnit + PHPStan Level 8).
3. Ensure `declare(strict_types=1);` is present on all files.

## 6. Deployment & History
1. Perform **Granular Commits** (one file at a time or by logical group).
2. Use descriptive messages (e.g., `feat(seo): sync rss.php with pair logic`).
3. Update `walkthrough.md` to record the "State of Mind" for the next session.

---
*Created by Google Antigravity & LinuxMalaysia for the v3.5 release milestone.*
