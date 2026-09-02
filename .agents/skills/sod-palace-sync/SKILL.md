---
okf_version: 0.1
type: skill
title: "SOD Palace Sync"
name: "sod-palace-sync"
description: "The Reanimation (Start of Day) ritual to load the workspace context and establish the day's tasks."
topics: [sod, palace, sync, git, ritual]
timestamp: 2026-06-19T14:00:00Z
---

# 🌅 SOD Palace Sync (Reanimation)

## When to use this skill
Use this skill when the user declares "Start of Day" (SOD), "Wake up", "Reanimate", or initiates a new working session.

## Instructions
1. **Context Loading:** Immediately read `.agents/brain/palace_registry.md` to map the workspace.
2. **Anchor Retrieval:** Read `.agents/brain/checkpoint_summary.txt` to find the latest "[MENTAL ANCHOR]" left by the previous session.
3. **Task Initialization:** Create or update `.agents/brain/task.md` outlining the immediate objectives based on the Mental Anchor.
4. **Environment Check:** Ensure the daily mechanical scripts have been run by the user or run them if appropriate:
   ```powershell
   # On Windows
   .\tools\sod-palace.ps1
   ```
   ```bash
   # On Linux/macOS
   bash tools/sod-palace.sh
   ```
5. Acknowledge your Reanimation and state: "Sovereign State Synchronised. Awaiting instructions."

## Architecture & Implementation
If `tools/sod-palace.ps1` or `tools/sod-palace.sh` are missing, agents must reconstruct them with the following logic:
1. **Git Fetch & Rebase:** Perform `git fetch origin` followed by `git rebase origin/main` to sync local state without literal `git pull` blocks.
2. **Pre-flight Audit:** Execute `.tools/audit-pre-flight.ps1` (or the equivalent Linux checks).
3. **Registry Check:** Verify that `.agents/brain/palace_registry.md` exists.
4. **Reanimation Manifest:** Execute `.\tools\reanimate.ps1` to bundle the workspace context into a timestamped `sod_manifest_<date>.txt` file.
5. **Prompt User:** Instruct the human to upload the newly generated manifest and initialize the AI via the DSOM protocol.
*(Note: On Linux/macOS, `sod-palace.sh` simply wraps `ansible-playbook playbooks/dsom/sod-palace.yml` with `ANSIBLE_NOCOWS=1`)*


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-06-19*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
