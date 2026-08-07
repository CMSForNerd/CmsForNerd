---
okf_version: 0.1
type: documentation
title: "HOWTO: Clone a DSOM Project"
description: "OKF-compliant documentation for scaffolding a new project from an existing DSOM repository."
resource: "file:///docs/HOWTO-CLONE-DSOM-PROJECT.md"
timestamp: 2026-07-04T11:00:00Z
topics: [docs, clone, dsom, project, scaffolding]
---
# HOWTO: Clone a DSOM Project (Scaffolding Blueprint)

> **Entry Point 1:** This document serves as the Engineering Entry Point for the DSOM framework. See [START-HERE.md](../START-HERE.md) for the master onboarding roadmap.

**ATTENTION:** This document is the master blueprint for replicating the Deep State of Mind (DSOM) architecture, including highly customised AI Agent Skills and Sovereign Personas, into a completely new repository.

If you are building a *new* project based on this repository, you **MUST** follow this guide to ensure the AI's cognitive capabilities, tools, and constraints are correctly ported.

---

## 1. The Core Requirement: What Needs to be Copied?

When scaffolding a new repository based on this template, you do not just copy the code. You must copy the **Sovereign Engine**.

You must meticulously copy the following four structural pillars from the baseline repository into the root of your new project:

### Pillar A: The Sovereign Engine (`.agents/`)
This folder contains the AI's core rules and its working memory.
- **Copy:** `.agents/AGENTS.md` (This is the Core Rulebook and contains the Persona Matrix).
- **Create Structure:** Recreate the `.agents/brain/` directory, but do **not** copy the old session data (`palace_registry.md`, `task.md`, etc.). Start with an empty brain.

### Pillar B: The Intelligence Payload (`.agents/skills/`)
This is the most critical step. This repository contains custom AI skills (e.g., document compilation, OKF injection, automated onboarding) that the AI needs to function effectively.
- **Copy:** Recursively copy all folders inside `.agents/skills/` to your new project's `.agents/skills/` directory.
- *Verify:* Ensure each skill contains a `SKILL.md` file that is OKF v0.1 compliant.

### Pillar C: Governance & Configuration (`docs/`)
The AI needs its theoretical blueprints to self-heal and understand the protocol.
- **Copy:** `docs/agent-configs/` (Contains the `SOVEREIGN-PERSONA-TEMPLATE.md` for identity setup).
- **Copy:** `docs/governance/` (Contains OKF specifications and structural protocols).
  - *Critical Inclusion:* Ensure `docs/governance/SOP-KNOWLEDGE-FIRST-DISCOVERY.md` is preserved. This protocol acts as the foundational cognitive engine for how the AI interacts with the system.
- **Copy:** `docs/AI-AGENT-SKILLS-GUIDE.md` (The ledger of all active skills).
- **Copy:** `docs/HOWTO-*.md` (All instructional manuals, including this one).

### Pillar D: The Ritual Scripts (`tools/`)
These are the physical automation scripts the AI and the user execute to reanimate and hibernate the workspace.
- **Copy:** Recursively copy the entire `tools/` directory.

---

## 2. Step-by-Step Cloning Execution

If you are a human or an AI agent performing this scaffolding, execute the following commands in the root of your **NEW** project, pointing to the **OLD** baseline path.

```bash
# 1. Establish Directories
mkdir -p .agents/brain/wings
mkdir -p .agents/skills
mkdir -p docs/agent-configs
mkdir -p docs/governance
mkdir -p tools

# 2. Copy the Engine and Skills
cp [OLD_PATH]/.agents/AGENTS.md .agents/
cp -r [OLD_PATH]/.agents/skills/* .agents/skills/

# 3. Copy Governance and Documentation
cp -r [OLD_PATH]/docs/agent-configs/* docs/agent-configs/
cp -r [OLD_PATH]/docs/governance/* docs/governance/
cp [OLD_PATH]/docs/AI-AGENT-SKILLS-GUIDE.md docs/
cp [OLD_PATH]/docs/HOWTO-*.md docs/

# 4. Copy Ritual Toolchain
cp -r [OLD_PATH]/tools/* tools/
```

---

## 3. Post-Clone Initialization

Once the files are copied into the new repository, you must initialize the AI's identity, memory, and version control.

1. **Initialize Git Repository:** The ritual scripts rely on Git to determine branch and commit state. You must initialize Git first.
   ```bash
   git init
   git branch -M main
   ```
2. **Initialize Brain Artifacts (Optional but Recommended):** To prevent startup warnings, create empty brain artifacts.
   ```bash
   mkdir -p .agents/brain
   echo "# 📝 Task List" > .agents/brain/task.md
   echo "# 🚶 Session Walkthrough" > .agents/brain/walkthrough.md
   ```
3. **Persona Injection:** Run or ask the AI to execute the `persona-injector` skill to establish the new project owner's identity. (Alternatively, manually fill out `docs/agent-configs/SOVEREIGN-PERSONA-TEMPLATE.md` and append it to `.agents/AGENTS.md`).
4. **First Reanimation:** Run the Reanimate ritual to generate the first `sod_manifest.txt` and wake up the AI in its new home.
   ```bash
   bash tools/reanimate.sh  # or .\tools\reanimate.ps1
   ```
5. **Commit the Genesis State:**
   ```bash
   git add .
   git commit -m "chore(dsom): scaffold genesis dsom architecture and AI skills"
   ```

> [!TIP]
> **Automated Scaffolding:** Instead of running these steps manually, you can instruct your AI Agent to invoke the `dsom-project-cloner` skill. Simply provide the agent with the absolute path to your new, empty target directory (e.g., `D:\Projects\my-new-app`), and the AI will automatically copy the pillars, inject your persona, and execute the Genesis commit for you!

Your new project is now fully sentient and equipped with the exact same capabilities as the baseline DSOM repository.


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-09*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
