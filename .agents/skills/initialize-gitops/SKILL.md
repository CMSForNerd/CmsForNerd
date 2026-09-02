---
okf_version: 0.1
type: skill
title: "Initialize GitOps"
name: "initialize-gitops"
description: "Establishes the foundational GitOps repository, configures the .gitignore, and commits the Genesis DSOM architecture for a new project."
topics: [gitops, init, git, sovereign, setup]
timestamp: 2026-06-19T14:00:00Z
---

# 🐙 Initialize GitOps Skill

## When to use this skill
Use this skill at the absolute beginning of a new project, immediately after scaffolding the Sovereign Workspace directory structure (`.agents/brain`, etc.). This is mandatory because the AI's memory palace relies entirely on Git version control. Without Git, you cannot perform Hibernation or trace your cognitive history.

## Instructions
1. Ensure you are in the project's root directory.
2. Check if a Git repository already exists. If not, run `git init`.
3. Create a `.gitignore` file to ensure ephemeral logs or sensitive secrets are not tracked. See the baseline `.gitignore` template below.
4. Execute `git add .` to stage the core `AGENTS.md`, `.agents/brain/`, and `.agents/skills/` structures.
5. Create the "Genesis Commit" with the message `git commit -m "chore: Genesis Commit - Initialize DSOM Cognitive Architecture"`.
6. Inform the user that the Immutable Ledger has been established and ask if there is a remote repository (like Gitea, GitLab, or GitHub) that they want you to push to.

## Baseline `.gitignore` Template
When creating the `.gitignore`, always include these baseline rules to prevent AI bloat:

```text
# Python
__pycache__/
*.pyc
.venv/
venv/

# Environment Variables
.env
.env.local

# AI Ephemeral Artifacts (DO NOT IGNORE THE ENTIRE .agents/ FOLDER)
# Only ignore temporary scratch files if they exist.
.agents/brain/scratch/
.agents/brain/.system_generated/
```

## Architectural Note
*   **Why is this a skill?** Because Git is not just version control for code; it is the physical "hippocampus" for the AI. Every action, artifact, and thought process is stored as a commit. Initializing it perfectly ensures the AI never suffers from amnesia.


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-06-19*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
