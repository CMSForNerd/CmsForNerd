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
This skill governs the standards for Git branch integration, merge conflict resolution, repository maintenance, clean submission checkpoints, and the strict requirement for incremental logical commits.

## When to use this skill
Use this skill during Git integrations, merge operations, conflict-handling sessions, prior to job submissions, and throughout active task workflows.

## Guidelines & Best Practices

### 1. Divergent Branch Merging
If a merge attempt with a divergent Jules branch fails with the Git error `'refusing to merge unrelated histories'`, bypass this protection using:
```bash
git merge origin/branch --allow-unrelated-histories
```
This forces integration when histories have diverged or been rewritten under different roots.

### 2. Independent Conflict Resolution
When resolving merge conflicts in 'jules' branches (submitted jobs):
- Proceed independently without waiting for master/main branch approvals.
- Exercise professional engineering judgment to determine the logical hierarchy of code changes.
- Revert or adjust conflicting blocks selectively to achieve a compilable, clean, and tested merge.

### 3. Version Alignment
Standardize all documentation versioning on the current major milestone (e.g., `v4.0.0-alpha` or higher) when resolving conflicts or updating markdown files, ensuring consistency with `README.md`, `CHANGELOG.md`, and master branch historical records.

### 4. Repository Cleanliness Before Submission
Prior to submitting your final changes:
- Remove all temporary artifacts generated during development.
- Clean up cache directories such as `.phpunit.cache/`.
- Remove custom benchmark scripts or test rigs.
- Delete temporary runtime cache files like `data/cache/*` to keep the repository history pristine.

### 5. Incremental Commit Mandate
The user explicitly requests making incremental Git commits for every single step taken during a task.
- Group file modifications by logical boundaries.
- Commit early and often, explaining exactly what each micro-step achieved.
- Avoid large monolithic or blanket commits (e.g., `git commit -am` or dumping unrelated files).


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-05*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
