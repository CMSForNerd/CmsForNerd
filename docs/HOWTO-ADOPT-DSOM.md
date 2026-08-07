---
okf_version: 0.1
type: documentation
title: "HOWTO: Adopt DSOM in Existing Projects (Brownfield)"
description: "OKF-compliant documentation for HOWTO-ADOPT-DSOM.md."
resource: "file:///docs/HOWTO-ADOPT-DSOM.md"
timestamp: 2026-07-04T09:40:04Z
topics: [docs, adopt, dsom, existing, projects]
---
# HOWTO: Adopt DSOM in Existing Projects (Brownfield)

**Author:** Harisfazillah Jamel
**Version:** 2.0 (DSOM v6.1 + Palace v1.0)
**License:** GPLv3

> **Scenario 1:** You have an active development project (PHP, Python, Node, etc.) and you want to install the DSOM Protocol to stop context decay.

---

## 1. Introduction

This guide explains how to "retro-fit" the Deep State of Mind (DSOM) framework into a running repository. It effectively transforms a standard code repo into a **Cognitive Digital Twin**.

**Target Audience:** Lead Architects, Senior Developers.

## 2. Prerequisites

* **Git Repository:** The project must be version-controlled.
* **Linux/WSL:** You need a Bash-compatible environment (or PowerShell 7+ on Windows).
* **Access:** Write permissions to the root of the repository.

## 3. The Procedure

### Step 1: Clone the Tooling

You need the `tools/` and `docs/` directories from the DSOM master repository.

**Option A: Submodule (Recommended)**
If you want to keep updated with DSOM core changes:

```bash
git submodule add https://github.com/linuxmalaysia/deep-state-of-mind-for-my-ai.git .dsom-core
cp -r .dsom-core/tools .
cp -r .dsom-core/docs .
```

**Option B: Direct Copy**
Clone DSOM elsewhere and copy the folders:

```bash
# In a temporary folder
git clone https://github.com/linuxmalaysia/deep-state-of-mind-for-my-ai.git dsom-temp
# In your target project
cp -r ../dsom-temp/tools .
cp -r ../dsom-temp/docs .
```

### Step 2: Initialize the Brain

Run the initializer to create the `.agents/brain/` structure. This script is **non-destructive**—it will not overwrite existing work, but since you are adopting, these files likely don't exist yet.

```bash
bash tools/init-brain.sh
```

*Output:* Created `task.md`, `walkthrough.md`, `implementation_plan.md`.

### Step 3: The First Context Injection

You must now manually populate the "Brain" with your project's current state.

1. **Edit `.agents/brain/implementation_plan.md`:**
    * Delete the boilerplate.
    * Write a high-level summary of your *current* project roadmap (Phases).
2. **Edit `.agents/brain/task.md`:**
    * List the immediate bugs or features you are working on *today*.
3. **Edit `.agents/brain/walkthrough.md`:**
    * Add a "Session Anchor" summarizing the recent history of the project so the AI knows "where we came from."

### Step 4: Security Hardening

Establish the security perimeter immediately.

1. **Run Privacy Guardian:**

    ```bash
    bash tools/privacy-guardian.sh
    ```

2. **Update .gitignore:**
    Ensure `.agents/brain/*.md` is **NOT IGNORED** (so you can sync context), but `.env` and `*.sql` **ARE IGNORED**.

```gitignore
!/.agents/brain/*.md
```

### Step 4b: Initialize the Palace

Backfill the Sovereign Markdown Palace from your existing Git history:

```bash
bash tools/palace-sync.sh --backfill
```

This generates `.agents/brain/palace_update_proposal_YYYY-MM-DD.md` — review it with your AI and create the closets in `.agents/brain/wings/`.

> **First time only.** After this, the daily EOD ritual (`bash tools/eod-palace.sh  # (Windows: .\tools\eod-palace.ps1)`) handles incremental Palace updates automatically.

### Step 5: The First Reanimation

Generate your first "Cognitive Handshake" manifest:

```bash
bash tools/reanimate.sh
```

**Action:** Upload the generated `sod_manifest_YYYY-MM-DD.txt` to your AI and say:
> *"Initialise DSOM Protocol v6.1 + Palace v1.0. Read the manifest. Walk the Palace Registry in Section [14]. Verify you understand this codebase structure. State: 'Sovereign State Synchronised' when ready."*

> **From this point:** Use the Ansible SOD/EOD playbooks for your daily ritual:
>
> ```bash
> bash tools/sod-palace.sh  # (Windows: .\tools\sod-palace.ps1)  # SOD
> bash tools/eod-palace.sh  # (Windows: .\tools\eod-palace.ps1)  # EOD
> ```

---

## 4. Troubleshooting

**Q: The script says "Not a Git Repository".**
A: Ensure you are running the command from the root of your project. Run `git init` if it's not versioned yet.

**Q: The AI thinks it's a new project.**
A: You skipped **Step 3**. The AI only knows what is in the `.agents/brain` files. If you leave them empty, it assumes a blank slate.

## 5. References

* [AI Master Protocol](AI-MASTER-PROTOCOL.md)
* [Operational Guide](OPERATIONAL-GUIDE.md)
* [HOWTO: Palace Onboarding](HOWTO-PALACE-ONBOARDING.md)
* [HOWTO: Migrate to Palace](HOWTO-MIGRATE-TO-PALACE.md)
* [SOD Ritual](SOD-RITUAL.md)
* [EOD Ritual](EOD-RITUAL.md)


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-04*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
