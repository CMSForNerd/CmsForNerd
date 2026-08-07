---
name: git-commit-resolver
description: Automatically resolves invalid or orphaned Git commit IDs referenced in the Agent Brain by searching the Git history for the matching commit message.
topics: [git, commit, history, brain, resolver]
okf_version: 0.1
type: agent_skill
title: "🔍 Git Commit Resolver Skill"
timestamp: 2026-08-07T05:39:28Z
---
# 🔍 Git Commit Resolver Skill

## Purpose
The DSOM repository has undergone Git history rewrites (e.g., using `git-filter-repo` for security sanitization). This process permanently alters cryptographic commit IDs (SHA-1 hashes) while leaving commit messages, dates, and authors intact.
As a result, some commit IDs stored in the `.agents/brain/` memory (e.g., in `palace_registry.md` or `HISTORY.md`) may become **invalid or orphaned**.

## When to use this skill
Trigger this skill whenever you attempt to reference, checkout, or view a Git commit ID (e.g., `git show <hash>`) and Git returns an error indicating the commit does not exist or is invalid.

## Execution Steps

### 1. Identify the Context
If a commit hash is invalid, do not assume the work was lost. Find the context of the commit by reading the surrounding text in the brain artifact or ledger to determine the **Commit Subject/Message** or **Date**.

### 2. Search Git Log by Message
Use the `git log` command with the `--grep` flag to search for the original commit message.

```bash
git log --grep="<part of the commit message>" --oneline
```
*Example:* If the old hash `abc1234` was for "docs: update gitbook architecture", run:
`git log --grep="update gitbook architecture" --oneline`

### 3. Extract the New Hash
The command will return the *new* valid commit hash associated with that exact same work. Use this new hash for your operations.

### 4. Self-Healing (Optional but Recommended)
If you are actively editing a brain artifact or ledger when you discover a broken hash, proactively update the document to replace the old broken hash with the newly resolved valid hash to heal the Sovereign Memory.

---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-16*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
