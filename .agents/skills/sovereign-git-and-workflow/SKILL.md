---
okf_version: 0.1
type: skill
title: "Sovereign Git Operations and Incremental Workflow"
name: "sovereign-git-and-workflow"
description: "Guidelines and procedures for divergent branch merging, conflict resolution, version alignment, repository cleanup, and incremental commits."
topics: [git, merge, conflict-resolution, clean, commits]
timestamp: 2026-08-01T09:00:00Z
---

# 🐙 Sovereign Git Operations and Incremental Workflow

## Purpose
This skill governs the standards for Git branch integration, merge conflict resolution, repository maintenance, clean submission checkpoints, Rule 24 session recording, and incremental logical commit protocols.

## When to use this skill
Use this skill during Git integrations, merge operations, conflict-handling sessions, prior to job submissions, and throughout active task workflows.

## Guidelines & Best Practices

### 1. Divergent Branch Merging, Shallow Clones & Sandbox Controls
Standard branch divergence must be handled via standard merging or rebasing.
- **Sandbox Command Guard:** The execution sandbox environment blocks shell scripts containing the literal command `git pull`. Code synchronization must be performed using `git fetch` followed by `git merge` or `git rebase` instead.
- **Shallow Clone Restoration:** The development environment's Git repository is checked out as a shallow clone by default. Before performing branch merges or operations requiring full history, run `git fetch --unshallow` to restore the complete commit history and avoid 'unrelated histories' errors.
- **Unrelated Histories:** If a merge attempt with a divergent branch fails with `'refusing to merge unrelated histories'`, verify remote identities before executing:
```bash
git merge origin/<branch> --allow-unrelated-histories
```
- **Staging Across Interaction Boundaries:** To preserve progress and modified files across user interaction boundaries (when executing `request_user_input`), changes must be staged (e.g., `git add`) as the sandbox resets unstaged changes between turns.

### 2. Independent Conflict Resolution
When resolving merge conflicts in 'jules' branches (submitted jobs):
- Proceed independently without waiting for master/main branch approvals.
- Exercise professional engineering judgment to determine the logical hierarchy of code changes.
- Revert or adjust conflicting blocks selectively to achieve a compilable, clean, and tested merge.

### 3. Version Alignment & Spatial Memory
- **Version Alignment:** Standardize all documentation versioning on the current major milestone (e.g., `v4.0.0-alpha` or higher) when resolving conflicts or updating markdown files, ensuring consistency with `README.md`, `CHANGELOG.md`, and master branch historical records.
- **Rule 24 Session Recording:** Defined in `AGENTS.md` and `.agents/AGENTS.md`, Rule 24 instructs the AI agent (Jules) to review past memories, git log, and active task details to record all completed work within `.agents/brain/task.md` and `.agents/brain/walkthrough.md` at the end of every operational session, finishing with EOD rituals and git status verification.
- **Spatial Memory:** The project maintains spatial memory via a Sovereign Markdown Palace Registry (`.agents/brain/palace_registry.md`) and manages context window loads using `.agents/brain/active_context_manifest.md`. Persistent AI agent knowledge is stored under `.agents/brain/wings/wing_dsom_core/hall_facts/room_jules_knowledge/closet.md`.
- **Universal Project Ledgers:** Universal ledgers (`CHANGELOG.md`, `docs/HISTORY.md`, `.agents/brain/wings/wing_dsom_core/hall_events/room_ledger/closet.md`, and `.agents/brain/checkpoint_summary.txt`) are fully updated and synchronized to reflect all releases and milestones completed up to v4.2.4 (Module 25).
- **Compliance Test Timestamp Alignment:** Compliance tests (e.g., `BrainMemoryTest`, `BrainLogTest`, `BrainDocumentationTest`, `LiveDemoMcpDocumentationTest`, `BrainMemoryModule22Test`) assert the presence of specific date timestamps (such as 2026-08-01) in the footers of `.agents/brain/task.md` and `.agents/brain/walkthrough.md`. Whenever these documentation files are updated during a session, the corresponding string assertions in test files must be updated to match the active timestamp.
- **Live Demo & MCP Crawler:** The production live demo is hosted at `https://cmsfornerd.onrender.com/index.php` and its Context7 MCP / LLM index crawler link is `https://context7.com/cmsfornerd/cmsfornerd/llms.txt?tokens=10000`.

### 4. Repository Cleanliness & Formatting Rules
Prior to submitting your final changes:
- Remove all temporary artifacts generated during development.
- Clean up cache directories such as `.phpunit.cache/`.
- Remove custom benchmark scripts or test rigs.
- Delete temporary runtime cache files like `data/cache/*` to keep the repository history pristine.
- **Markdown Line Length:** Markdown files in the repository are configured with a 140-character line length limit inside `.markdownlint.json`, but `walkthrough.md` and active constraints specify a target of 120-character line-length limit in documentation.

### 5. Incremental Commit Mandate
Incremental Git commits are required during a task only when the current user explicitly requests them.
- Avoid the unconditional assumption that every task step must be committed automatically.
- When incremental commits are requested or approved by the user, group file modifications strictly by logical boundaries and keep commits highly focused.
- Avoid large monolithic or blanket commits (such as `git commit -am` or dumping unrelated files).


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-05*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
