---
okf_version: 0.1
type: skill
title: "EOD Palace Sync"
name: "eod-palace-sync"
description: "The Hibernation (End of Day) ritual to externalize memory into the Palace and push to Git."
topics: [eod, palace, sync, git, ritual]
timestamp: 2026-06-19T14:00:00Z
---

# 🌙 EOD Palace Sync (Hibernation)

## When to use this skill
Use this skill when the user declares "End of Day" (EOD), "Save memory", or requests to update the palace and hibernate the agent.

## Instructions
1. **Walkthrough Update:** Append the day's major accomplishments, tested commands, and E2E results to `.agents/brain/walkthrough.md`.
2. **Anchor Creation:** Update `.agents/brain/checkpoint_summary.txt` with a clear, concise "[MENTAL ANCHOR]" for the next agent to resume from.
3. **Palace Sync:** Update any modified architectural rules inside the specific `closet.md` spatial rooms.
4. **Memory Dump Generation:** Compile a comprehensive checkpoint summary documenting project details, instructions, frameworks, and active phases.
5. Save this summary directly to `.agents/brain/checkpoint_summary.txt`.
6. Ensure `HISTORY.md`, `CHANGELOG.md`, `README.md`, and `.agents/brain/task.md` are up to date.
7. Instruct the user to execute the final git commit and push via the EOD script:
   ```powershell
   # On Windows
   .\tools\eod-palace.ps1
   ```
   ```bash
   # On Linux/macOS
   bash tools/eod-palace.sh
   ```
8. Confirm to the user that state has been fully externalized and archived.

## Architecture & Implementation
If `tools/eod-palace.ps1` or `tools/eod-palace.sh` are missing, agents must reconstruct them with the following logic:
1. **Validation:** Check that `.agents/brain/task.md` has completed tasks (`[x]`), and `.agents/brain/walkthrough.md` contains today's `yyyy-MM-dd` anchor.
2. **Summary Verification:** Check that `.agents/brain/checkpoint_summary.txt` exists. If it does not, output a massive terminal warning instructing the user to prompt the AI to generate it.
3. **Palace Sync:** Run `.\tools\palace-sync.ps1` to generate any update proposals.
4. **Staging:** `git add` the core artifacts: `task.md`, `walkthrough.md`, `palace_registry.md`, `checkpoint_summary.txt`, and `implementation_plan.md`.
5. **Commit & Push:** Execute `git commit -m "eod: <contents of checkpoint_summary>"` and `git push origin main`.
*(Note: On Linux/macOS, `eod-palace.sh` simply wraps `ansible-playbook playbooks/dsom/eod-palace.yml` with `ANSIBLE_NOCOWS=1`)*


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-06-19*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
