---
name: git-history-scrubber
description: Safely purges sensitive data (IPs, credentials) from Git history using git-filter-repo, deletes old releases, and handles force-push protocols.
topics: [git, security, history, scrub, credentials]
okf_version: 0.1
type: agent_skill
title: "🧹 Git History Scrubber Skill"
timestamp: 2026-08-07T05:39:28Z
---
# 🧹 Git History Scrubber Skill

## Purpose
This skill instructs agents on how to safely and permanently rewrite Git history to remove sensitive data (e.g., leaked IPs, credentials, personal hostnames) using `git-filter-repo`. It enforces operational security by obfuscating the true nature of the cleanup and mandates strict protocols for force-pushing to protected environments like GitLab.

## When to use this skill
Trigger this skill whenever the user asks to "purge", "scrub", "remove from history", or "clean up" sensitive information from past Git commits and releases.

## Execution Constraints (Security Obfuscation)
> [!IMPORTANT]
> **Operational Security:** When executing this skill, communicating with the user, or writing commit messages, you MUST use generic terminology such as "files cleanup" or "repository maintenance". **Never** explicitly state in commit messages or public issue trackers that you are removing sensitive data, passwords, or IPs, as this draws attention to the vulnerability.

## Execution Steps

### 1. Identify and Map Replacements
First, identify all strings the user wants scrubbed. Propose generic replacements (e.g., `10.0.0.1`, `example-user`). Obtain user approval before proceeding.
Write these mappings to a `replacements.txt` file in the workspace root:
```text
[SENSITIVE-IP]==>10.10.10.10
my-secret-username==>example-user
```

### 2. Rewrite the Git History
Execute `git-filter-repo` via the `uv` Python tool. This rewrites the entire history offline.
```bash
uv tool run git-filter-repo --replace-text replacements.txt --force
```

### 3. Delete Outdated Releases
Because Git releases contain `.zip` and `.tar.gz` archives of the old unamended code, they must be manually deleted using the GitHub and GitLab CLIs.
```bash
gh release delete <tag-name> -y --cleanup-tag
glab release delete <tag-name> -y --with-tag
```

### 4. Warn the User (GitLab Protected Branches)
Before attempting to push the new history, you **must** explicitly warn the user that GitLab protects the `main` branch by default.
Instruct the user to:
1. Go to their GitLab Settings -> Repository -> Protected branches.
2. Toggle "Allowed to force push" to ON for the `main` branch.

### 5. Re-add Remote and Force Push
`git-filter-repo` removes remotes by default for safety. Re-add the remotes (if necessary) and execute the force pushes:
```bash
git remote add origin <repo-url>
git push origin --force --all
git push origin --force --tags

# Repeat for GitLab if configured as a separate remote
git push gitlab --force --all
git push gitlab --force --tags
```

### 6. Heal the Sovereign Memory
Remind the user (or automatically invoke) the `git-commit-resolver` skill, as the history rewrite will have invalidated all cryptographic commit hashes currently saved in the `.agents/brain/` memory.

---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-16*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
