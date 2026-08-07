---
okf_version: 0.1
type: document
category: blueprint
title: "DSOM Bootstrapping Guide"
description: "Agent-readable instructions for porting the DSOM architecture from the baseline repository to a new or existing workspace."
topics: [bootstrap, onboarding, baseline, dsom, setup]
timestamp: 2026-08-07T05:39:28Z
---
# DSOM Bootstrapping Guide (Agent-Readable)

**ATTENTION AI AGENT**: You have been asked by the user to read this file. Follow these explicit instructions to copy and merge the required intelligence, tools, and documentation from the permanent baseline repository into your current environment.

## 1. Context & Baseline
*   **Permanent Baseline Path:** `d:\Users\LinuxMalaysia\Projects\deep-state-of-mind-for-my-ai`
*   **Your Mission:** Replicate the structural intelligence of the baseline project into your current workspace.

## 2. Phase 0: Skill Initialization (Crucial First Step)
Before copying the entire workspace, you must first endow yourself with the bootstrap skill so you understand the DSOM protocol permanently.
1. Create the directory `.agents/skills/dsom-bootstrap/` in your current workspace if it does not exist.
2. Copy the file `[BASELINE_PATH]\.agents\skills\dsom-bootstrap\SKILL.md` into your local `.agents/skills/dsom-bootstrap/` directory.

## 3. Workspace Assessment
After acquiring the skill, analyze the current workspace to determine if it is a **NEW** project (mostly empty, no existing `.agents` or `tools`) or an **EXISTING** project (already has code, `tools/`, or `docs/`).

---

## 4. Execution Path: NEW Project

If this is a new project, execute a direct replication:

1.  **Copy Intelligence (`.agents/`):**
    *   Recursively copy `[BASELINE_PATH]\.agents` to the current workspace root.
    *   *Sanitization:* Clear out any old session data by emptying `.agents/brain/checkpoint_summary.txt`, `.agents/brain/task.md`, and `.agents/brain/walkthrough.md`. Leave `index.md` intact.
2.  **Copy Toolchain (`tools/`):**
    *   Recursively copy `[BASELINE_PATH]\tools` to the current workspace.
3.  **Copy Documentation (`docs/`):**
    *   Recursively copy `[BASELINE_PATH]\docs\tools` to the current workspace.
    *   Recursively copy `[BASELINE_PATH]\docs\governance` to the current workspace.
4.  **Finalise:** Initialize a new `sod_manifest_YYYY-MM-DD.txt` using the `reanimate` script in the newly copied `tools/` directory.

---

## 5. Execution Path: EXISTING Project

If this is an existing project, you must adopt and merge gracefully to avoid destructive overwrites:

1.  **Merge Intelligence (`.agents/`):**
    *   If `.agents/` does not exist, copy it fully.
    *   If it exists, merge the contents. Copy missing `skills/` and ensure the Constitution (`AGENTS.md`) is adopted (appended or intelligently merged with existing rules).
2.  **Merge Toolchain (`tools/`):**
    *   Copy all DSOM ritual scripts (`sod-palace.*`, `eod-palace.*`, `reanimate.*`, `palace-sync.*`, `diagnostic.*`).
    *   If there are existing scripts in `tools/` with the same name, compare their contents and merge updates carefully, preserving any project-specific logic.
3.  **Merge Documentation (`docs/tools/`):**
    *   Copy missing blueprint markdown files from the baseline to ensure the AI's self-healing capabilities work for the DSOM scripts.
4.  **Adopt/Merge Governance (`docs/governance/`):**
    *   Check for the existence of `docs/governance/`.
    *   If it does *not* exist, copy it from the baseline.
    *   If it *does* exist, read the existing markdown files. Intelligently merge the DSOM governance standards (like OKF enforcement, logging protocols) into the existing project documentation without deleting the project's native governance files.

## 6. Post-Execution Checklist
After executing the relevant path (New or Existing):
- Run `.\tools\diagnostic.ps1` (or `.sh`) to verify the workspace health.
- Inform the user that the DSOM framework has been successfully bootstrapped.

## 7. Token Performance Verification (Mandatory)

After bootstrapping, every new DSOM project must verify its token health baseline. This prevents context window collapse as the project grows.

**Step 7a — Skill token audit:**
```bash
uv run --with tiktoken .agents/skills/dsom-token-calculator/scripts/calculate-tokens.py .agents/skills/
```
Expected: all files `[OK]`, zero `[BLOCKED]`.

**Step 7b — Verify all skills have `topics:` tags:**
```powershell
Get-ChildItem -Recurse -Path ".agents/skills" -Filter "SKILL.md" | ForEach-Object {
    $content = Get-Content $_.FullName -Raw
    $hasTopics = $content -match "(?m)^topics:"
    [PSCustomObject]@{ Skill = $_.Directory.Name; Topics = if($hasTopics){"YES"}else{"MISSING"} }
} | Format-Table -AutoSize
```
All must return `YES`. If any return `MISSING`, the OKF Import Mandate (Rule 6) has been violated.

**Step 7c — Initialise active context manifest:**
Edit `.agents/brain/active_context_manifest.md` to declare your project's active session files and explicitly exclude any archival brain files exceeding 4,000 tokens.

**Reference:** [`docs/governance/DSOM-TOKEN-PERFORMANCE-PLAYBOOK.md`](governance/DSOM-TOKEN-PERFORMANCE-PLAYBOOK.md)

---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-19*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-04*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
