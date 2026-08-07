---
okf_version: 0.1
type: documentation
title: "HOWTO: Upgrade and Audit DSOM (Scenario 2)"
description: "OKF-compliant documentation for HOWTO-UPGRADE-DSOM.md."
resource: "file:///docs/HOWTO-UPGRADE-DSOM.md"
timestamp: 2026-07-04T09:40:04Z
topics: [docs, upgrade, dsom, audit, scenario]
---
# HOWTO: Upgrade and Audit DSOM (Scenario 2)

**Author:** Harisfazillah Jamel
**Version:** 2.0 (DSOM v6.1 + Palace v1.0)
**License:** GPLv3

> **Scenario 2:** You have a project already running an older version of DSOM (e.g., v4.0/v5.x/v6.0). You want to upgrade to the latest v6.1 + Palace v1.0 features (Spatial Memory, Ansible SOD/EOD automation).

---

## 1. Introduction

This guide explains the safe procedure to upgrade the DSOM Protocol in a live project without losing your "Mental Anchor" or breaking existing context.

**Target Audience:** Digital Stewards, Maintainers.

## 2. Prerequisites

* **Existing DSOM Install:** A project with an `.agents/brain/` directory.
* **Clean Git State:** Commit all pending changes before starting.

## 3. The Procedure

### Step 1: Backup (Sovereign Safety)

Before overwriting tools, ensure your Brain artifacts are safe.

```bash
cp -r .agents/brain .agents/brain_backup_$(date +%F)
```

### Step 2: Update Tooling and Docs

You need to overwrite the `tools/` and `docs/` directories with the latest version from the master DSOM repository.

**If using Submodules:**

```bash
git submodule update --remote
cp -r .dsom-core/tools .
cp -r .dsom-core/docs .
```

**If Manual Copy:**

1. Download the latest release zip from GitHub.
2. Extract and overwrite the `tools/` and `docs/` folders in your project root.
3. **Critical:** Do NOT verify/overwrite `.agents/brain/` yet.

### Step 3: Protocol Injection (The Constitution)

The upgrade often involves new "Laws" in `AI-MASTER-PROTOCOL.md` (e.g., ITIL Service Alignment).

1. **Check `docs/AI-MASTER-PROTOCOL.md`:** Ensure the new file completely replaces the old one.
2. **Verify `SUMMARY.md`:** Ensure new documents (like `ITIL-ALIGNMENT.md`) are listed.

### Step 4: The Audit (Re-Calibration)

New versions might require new file structures or configs.

1. **Run the Initializer again:**

    ```bash
    bash tools/init-brain.sh
    ```

    *Why?* Newer versions of this script might check for new required files (like `DSOM_TEMPLATE.md`). It will skip existing files, so your `task.md` is safe.

2. **Run the Privacy Guardian:**

    ```bash
    bash tools/privacy-guardian.sh
    ```

    *Why?* New patterns (like AWS Keys) might be detected in your old manifests. Clean them up.

### Step 4b: Migrate to Palace (NEW in v6.1)

The Sovereign Markdown Palace is the key new feature in v6.1. Add it by backfilling from your Git history:

```bash
bash tools/palace-sync.sh --backfill
```

This generates `palace_update_proposal_YYYY-MM-DD.md`. Share it with your AI and create the initial closets in `.agents/brain/wings/`.

> **This is a one-time operation.** After this, the daily EOD playbook (`bash tools/eod-palace.sh  # (Windows: .\tools\eod-palace.ps1)`) handles incremental updates.

### Step 5: Context Re-Sync

Your AI agent might be confused by the sudden change in Protocol.

1. **Generate a fresh Manifest:**

    ```bash
    bash tools/reanimate.sh
    ```

    The manifest now includes **Section [14] Palace Registry** — the AI will walk it automatically.

2. **Upload to AI:**
    > *"I have upgraded the DSOM Protocol to v6.1 + Palace v1.0. Please analyse the attached manifest. Walk the Palace Registry in Section [14] and identify the relevant Rooms. Note the new Ansible SOD/EOD playbooks in `playbooks/dsom/`. State 'Sovereign State Synchronised' when ready."*

3. **Verify Ansible Palace automation:**

    ```bash
    # Test SOD
    bash tools/sod-palace.sh  # (Windows: .\tools\sod-palace.ps1)
    # Test EOD
    bash tools/eod-palace.sh  # (Windows: .\tools\eod-palace.ps1)
    ```

---

## 4. Troubleshooting

**Q: My `walkthrough.md` was overwritten!**
A: `init-brain.sh` checks if files exist before writing. If it was overwritten, you might have used a `cp` command that targeted the brain directory. Restore from `brain_backup`.

**Q: The AI refuses to acknowledge the new laws.**
A: The context window might be stale. Start a **New Chat Session** and perform the full Reanimation Ritual.

## 5. References

* [Changelog](../CHANGELOG.md)
* [Ritual of Transition](RITUAL-OF-TRANSITION.md)
* [HOWTO: Palace Onboarding](HOWTO-PALACE-ONBOARDING.md)
* [SOD-RITUAL.md](SOD-RITUAL.md) — Step 1a: Ansible Palace SOD
* [EOD-RITUAL.md](EOD-RITUAL.md) — Step 2a: Ansible Palace EOD
* [GITOPS-AIOPS-ANSIBLE-STRATEGY.md](GITOPS-AIOPS-ANSIBLE-STRATEGY.md)


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-04*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
