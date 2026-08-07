---
okf_version: 0.1
type: documentation
title: "🤝 HUMAN-HANDOVER-CONTEXT.md"
description: "OKF-compliant documentation for HUMAN-HANDOVER-CONTEXT.md."
resource: "file:///docs/HUMAN-HANDOVER-CONTEXT.md"
timestamp: 2026-07-04T09:40:04Z
topics: [docs, human, handover, context, path]
---
# 🤝 HUMAN-HANDOVER-CONTEXT.md

# Path: docs/HUMAN-HANDOVER-CONTEXT.md

> **DSOM For My AI — SESSION HANDOVER TEMPLATE**
> Adapt this file for every new project cloned from the DSOM skeleton.
> Fill in every `[PLACEHOLDER]` with your project-specific values.
> This file is the **first document** you paste into a new AI session.

---

### ⚡ SESSION HANDOVER PROMPT

*(Copy everything below this line and paste as your first AI message)*

---

I am starting a new AI session. Synchronise your state with the following:

1. **READ** the project brief: `docs/` directory and the DSOM brain files.
2. **SYNC** with the task tracker: `.agents/brain/task.md`
3. **READ** brain artifacts: `.agents/brain/walkthrough.md` and `.agents/brain/implementation_plan.md`
4. **VERIFY** `docs/AI-COGNITIVE-TWIN-PROTOCOL.md` — confirm all `[PLACEHOLDER]` fields are filled.
5. **READ** `README.md`, `SUMMARY.md`, `CHANGELOG.md`
6. **RUN** `git log --oneline -20` to read recent commit history.
7. **WALK THE PALACE** — read `.agents/brain/palace_registry.md` (Section [14] of the SOD manifest) and identify the relevant Wings and Rooms for today's work.

Run **Start of Day (SOD)** as per DSOM For My AI Protocol v6.1 + Palace v1.0.

> **T2 shortcut:** `bash tools/sod-palace.sh  # (Windows: .\tools\sod-palace.ps1)` automates steps 1–6 + palace check automatically. Upload the manifest and use the handshake phrase below.

---

#### 🏗️ Project Identity

- **Project Name:** `[YOUR_PROJECT_NAME]`
- **Stack:** `[YOUR_STACK e.g. Logstash + Kafka + Podman / ELK / Ansible roles]`
- **Purpose:** `[ONE_LINE_PURPOSE]`
- **Repo:** `[YOUR_GITHUB_REPO_URL]`
- **Protocol Version:** DSOM v6.1 + Palace v1.0 | GitOps · AIOps · Ansible

---

#### 🗺️ 4-Tier Command Highway

We strictly follow the **4-Tier Command Highway** (T1→T2→T3→T4):

| Tier | Name | Environment | Path |
|:---|:---|:---|:---|
| **T1** | Command Centre | Windows 11 + PowerShell + Google Antigravity | `[T1_WINDOWS_PATH]` |
| **T2** | Dev Bridge | WSL2: `dsom-control-almalinux10` | `/mnt/[T2_MOUNTED_PATH]` |
| **T3** | Jump Host / Ansible Control | `[T3_HOST_IP_OR_NAME]` | `/opt/[YOUR_PROJECT_NAME]/` |
| **T4** | Production Fabric | `[T4_NODE_IPS_OR_NAMES]` | `[T4_TARGET_PATH]` |

**Code is promoted:** T1 → Git → T2 → T3 → T4. No shortcuts.

---

#### 🛡️ Security Doctrine: "Rootful Control, Rootless Application"

- **Rootful Control:** Ansible/sudo manages OS-level tasks — systemd, networking, filesystem.
- **Rootless Application:** All containers/services run as `[APP_USER]` (UID `[APP_UID]` GID `[APP_GID]`).
- **Sovereign Identity:** `dsom-admin:2001:2001` on target nodes.
- **Dev Identity:** `[DEV_USERNAME]:[DEV_UID]:[DEV_GID]` on WSL2.

---

#### ⚙️ Environment Rules

- **AI Partner:** Google Antigravity on Windows 11 (PowerShell).
- **Ansible:** Runs from **T2 WSL2** (`dsom-control-almalinux10`). NOT from Windows directly.
- **Git operations:** All `git add`, `git commit`, `git push` done on **T1 Windows/PowerShell**.
- **Dev testing:** Human runs scripts inside **T2 WSL2** (`dsom-control-almalinux10`). AI guides and waits for output.
- **Production:**`hosts.prod.yml` is in `.gitignore`. Human must supply it for any production Ansible run.

---

#### 🔄 The Safe Sync Ritual (Run After Every T1 Push)

Whenever changes are pushed from Windows (T1), perform this ritual to align all tiers:

**Step 1 — Pull on T2 (WSL2: `dsom-control-almalinux10`)**

```bash
cd /mnt/[T2_MOUNTED_PATH]
git pull origin main
```

**Step 2 — Promote to T3 Jump Host / Ansible Control (if applicable)**

```bash
# Run from T2 WSL2 — promote project to T3 orchestrator
rsync -avz --checksum --delete \
    --exclude '.git' --exclude '.gemini' --exclude '.agents' \
    ./ dsom-admin@[T3_IP]:/opt/[YOUR_PROJECT_NAME]/
```

**Step 3 — Verify T3 Ansible Connectivity**

```bash
# Run from T2 WSL2
ansible all -m ping -i inventory/hosts.yml
```

**Step 4 — Optional: Remote Audit from T1 via WSL**

```powershell
# Run from Windows PowerShell (T1) — bridges through T2 → T3
wsl -d dsom-control-almalinux10 -u dsom-admin -e bash -c `
    "ssh -t dsom-admin@[T3_IP] 'cd /opt/[YOUR_PROJECT_NAME] && bash tools/audit-pre-flight.sh'"
```

---

#### 🏁 Sovereign Directive (The Handshake Trigger)

After the AI reads all context, trigger the session with:

> *"Initialise DSOM Protocol v6.1 + Palace v1.0 for [YOUR_PROJECT_NAME]. Read the uploaded manifest or this handover file. Walk the Palace Registry in Section [14] — identify the relevant Rooms for today's work. Confirm the 4-Tier Command Highway is mapped. Verify `docs/AI-COGNITIVE-TWIN-PROTOCOL.md` is filled in. State the last Mental Anchor from `.agents/brain/walkthrough.md`. Respond in UK English. State: 'Sovereign State Synchronised — [YOUR_PROJECT_NAME] is live.' when ready."*

---

#### 📋 How to Provide Output for AI Review

| Method | Command |
|:---|:---|
| **Direct Paste** | Copy full terminal output into AI chat |
| **Log File** | `bash tools/audit-pre-flight.sh \| tee audit_result.log` — share log content |
| **Ansible Output** | `ansible-playbook playbooks/dsom/audit-preflight.yml -i localhost, \| tee audit_ansible.log` |

AI will **wait for your results** before proceeding. Never assume success.

---

#### 📌 Current State (Fill in at each session end)

```
CURRENT STATE: [DESCRIBE_CURRENT_STATE e.g. v1.0 — Ansible baseline deployed, Kafka brokers up]
Environment:   [e.g. 3-node test cluster]
Last Commit:   [PASTE LAST COMMIT HASH AND MESSAGE]
Mental Anchor: [ONE SENTENCE — exact state and where to resume]
Next Action:   [WHAT THE AI SHOULD DO FIRST IN THE NEW SESSION]
```

---

#### 🔑 Key Reminders to AI Agent

1. **Advisory Mode only.** Propose → Human approves → Ansible executes → AI verifies.
2. **Never run commands on production nodes.** Write Ansible playbooks. Human runs them.
3. **Every step is committed to Git** before execution. No Git = no change.
4. **Idempotency is mandatory.** Every playbook must be safe to re-run.
5. **All secrets via `ansible-vault`.** Never commit plaintext secrets.
6. **Detailed Git commit messages.** Format: `type(scope): description [Phase/vXXX]`
7. **UK English only** in all documentation and responses.
8. **Wait for human output** before proceeding with the next step.
9. **Control yourself during long debugging.** Emit one action at a time.
10. **Both T1 and T2 must be in sync** — always pull on T2 after T1 push.
11. **Walk the Palace Registry** at the start of every session — read `palace_registry.md` and load the relevant `closet.md` files before advising.
12. **Propose Palace closet updates** at EOD if new decisions or discoveries were made.

---

*Standard: DSOM For My AI Protocol v6.1 + Palace v1.0 | Harisfazillah Jamel | LinuxMalaysia*
*Template source: docs/HUMAN-HANDOVER-CONTEXT.md | Updated: 2026-04-08*
*Adapted from: DSOM elasticsearch-kibana-podman project (v9.4 Sovereign Recovery model)*


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-04*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
