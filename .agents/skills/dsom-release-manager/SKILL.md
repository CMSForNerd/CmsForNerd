---
okf_version: 0.1
type: skill
title: "DSOM Release Manager"
name: "dsom-release-manager"
description: "Cuts a formal DSOM release, updates ledgers, tags the repository, and deploys to GitHub/GitLab."
topics: [release, git, tagging, changelog, deployment]
timestamp: 2026-07-12T09:50:00Z
---
# 🚀 DSOM Release Manager

## When to use this skill
Trigger this skill when the user asks to "cut a release", "create release notes", or "do a release for github/gitlab".

## Execution Steps
1. **Changelog Promotion:** Modify `CHANGELOG.md` to promote the `[Unreleased]` section into a formal versioned release block (e.g., `## [10.4.0-governance] - YYYY-MM-DD`). Add an empty `[Unreleased]` block above it.
2. **Ledger Sync:** Ensure the version footer in `HISTORY.md` reflects the new version string.
3. **Mental Anchor:** Create an EOD-style mental anchor in `.agents/brain/checkpoint_summary.txt` summarizing the release.
4. **GitOps Stage:** Commit all changes via `git commit -m "docs: cut release <VERSION>"`.
5. **Tagging:** Create a Git tag via `git tag -a v<VERSION> -m "Release <VERSION>"` and push to all remotes (`git push origin main`, `git push origin v<VERSION>`, `git push gitlab main`, `git push gitlab v<VERSION>`).
6. **Release Notes Generation:** Extract the specific release notes from `CHANGELOG.md` and write them to a temporary file (`.agents/brain/scratch/release_notes.txt`).
7. **Platform Deployment:**
   - Deploy to GitHub: `gh release create v<VERSION> -F .agents/brain/scratch/release_notes.txt --title "v<VERSION>"`
   - Deploy to GitLab: `glab release create v<VERSION> --name "v<VERSION>" --notes-file .agents/brain/scratch/release_notes.txt`

---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-12*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
