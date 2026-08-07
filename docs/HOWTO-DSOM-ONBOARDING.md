---
okf_version: 0.1
type: documentation
title: "🚀 HOWTO: Adopt DSOM Using Automated Onboarding"
description: "OKF-compliant documentation for HOWTO-DSOM-ONBOARDING.md."
resource: "file:///docs/HOWTO-DSOM-ONBOARDING.md"
timestamp: 2026-07-04T09:40:04Z
topics: [docs, dsom, onboarding, adopt, using]
---
# 🚀 HOWTO: Adopt DSOM Using Automated Onboarding

Incorporating the Deep State of Mind (DSOM) framework into an existing project used to involve a manual process of cloning, copying, and aligning files. This is now fully automated via the **DSOM Onboarding Automation**.

If you have an existing Git repository and want to "DSOM-ify" it by pulling in all our standard brain folders, Playbooks, and Git hooks, follow these steps.

## The Goal

- Inject the standard DSOM `tools/` and `docs/` directly into your repo.
- Safely resolve any conflicting files (by renaming the incoming DSOM template rather than overwriting your local configuration).
- Do everything in a safe, reviewable temporary Git branch.

## Prerequisites

1. Your target project must be a Git repository (`git init` already run).
2. You must have `ansible-playbook` installed (or accessible via WSL on Windows).
3. Ensure you have committed any pending changes in your repository.

---

## Step-by-Step Onboarding Execution

### Option 1: Linux, macOS, or WSL2

If you are native on Linux/macOS or inside a WSL2 shell, use the bash bootstrap.

```bash
# 1. Download the safe bootstrap script to the root of your repository
curl -O https://raw.githubusercontent.com/linuxmalaysia/deep-state-of-mind-for-my-ai/main/tools/dsom-onboard.sh

# 2. Add execution permissions
chmod +x dsom-onboard.sh

# 3. Run the onboarding process
./dsom-onboard.sh
```

### Option 2: Windows (PowerShell)

If you are using Windows natively but have WSL configured, the PowerShell script bridges the command execution automatically.

```powershell
# 1. Download the safe bootstrap script to the root of your repository
Invoke-WebRequest -Uri "https://raw.githubusercontent.com/linuxmalaysia/deep-state-of-mind-for-my-ai/main/tools/dsom-onboard.ps1" -OutFile "dsom-onboard.ps1"

# 2. Run the onboarding process
.\dsom-onboard.ps1
```

---

## Post-Onboarding Steps

### 1. Review the Work

The script isolated all changes in a new branch (e.g., `dsom-onboarding-YYYY-MM-DD-HHMMSS`). Run `git status` to see what was brought in.

### 2. Handle File Conflicts

The automated backend uses **Non-Destructive GitOps Verification**. If you already had a file (like `README.md` or `.markdownlint.json`) that collided with the DSOM standards, the system did **not** overwrite yours.

Instead, the DSOM configuration was downloaded and suffixed with today's date (e.g., `README-2026-04-08.md`).

Review these files, merge any useful new standards from the DSOM copy into your master copy, and delete the suffixed DSOM duplicates.

### 3. Commit and Merge

Once satisfied, merge the onboarding branch into your main development stream:

```bash
git add .
git commit -m "chore: onboard DSOM architectural standards"
git checkout main
git merge dsom-onboarding-YYYY-MM-DD-HHMMSS
```

### 4. Continue Normal Setup

Proceed to adapt the `docs/AI-COGNITIVE-TWIN-PROTOCOL.md` file as outlined in the core README. Your repository is now a fully sovereign DSOM environment!


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-04*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
