---
name: dsom-bootstrap
description: Executes the DSOM bootstrapping process for a new or existing project by reading the HOWTO-DSOM-BASELINE.md guide and pulling files from the permanent baseline repository.
topics: [bootstrap, setup, onboarding, project-init, dsom]
okf_version: 0.1
type: agent_skill
title: "🚀 DSOM Bootstrap Skill"
timestamp: 2026-08-07T05:39:28Z
---
# 🚀 DSOM Bootstrap Skill

## When to use this skill
Use this skill when you are operating in a new or existing workspace that lacks the full DSOM (Digital Sovereign Operating Model) architecture, and the user asks to initialize, bootstrap, or port DSOM.

## Instructions
1. **Locate Blueprint:** Verify that `HOWTO-DSOM-BASELINE.md` exists in the current project root. If it does not exist, inform the user that they must copy this file from the baseline repository first.
2. **Read Blueprint:** Use your file reading tools to read `HOWTO-DSOM-BASELINE.md`.
3. **Assess Environment:** Analyze the current workspace. Determine if it is a NEW project (no `.agents` or `tools` directory) or an EXISTING project.
4. **Execute Path:**
   - Follow the precise instructions in the HOWTO document corresponding to your assessment (Section 3 for NEW, Section 4 for EXISTING).
   - Use your terminal execution tools (`run_command`) to perform the recursive copies (`cp -r` or `Copy-Item -Recurse`) from the defined Baseline Path.
   - For EXISTING projects, ensure you read existing files and perform intelligent merges using your file editing tools, rather than destructive overwrites.
5. **Sanitize (If New):** Ensure the `checkpoint_summary.txt`, `task.md`, and `walkthrough.md` in `.agents/brain/` are emptied of baseline history.
6. **Verify:** Run the `tools/diagnostic.ps1` or `tools/diagnostic.sh` script to confirm the port was successful.
7. **Report:** Output a success message to the user, confirming that the AI now possesses full DSOM capabilities, tools, and documentation.


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-04*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
