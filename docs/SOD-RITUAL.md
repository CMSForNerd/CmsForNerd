---
okf_version: 0.1
type: documentation
title: "🌅 SOD-RITUAL.md — Start-of-Day Ritual"
description: "OKF-compliant documentation for SOD-RITUAL.md."
resource: "file:///docs/SOD-RITUAL.md"
timestamp: 2026-07-04T09:40:04Z
topics: [docs, sod, ritual, start, day]
---
# 🌅 SOD-RITUAL.md — Start-of-Day Ritual

# docs/SOD-RITUAL.md

> **"To lose context is to lose the project. To reanimate is to remember."**
> **Standard: DSOM For My AI Protocol v6.1 + Palace v1.0 | GitOps · AIOps · Ansible**

---

## 🏛️ 1. The Philosophy of Reanimation

The **Start-of-Day (SOD)** is not just a technical boot sequence — it is a **Cognitive Handshake** between the Lead Architect (Human) and the Cognitive Digital Twin (AI).

Since AI has amnesia between sessions, the SOD Ritual transfers the **Deep State of Mind** — the full context, history, architectural intent, environment map, and security doctrine — from the repository's permanent storage (`.agents/brain/`) back into the AI's active working memory.

**The Three-Pillar Loop always starts with SOD:**

```
SOD (Re-sync) → AI Proposes → Git Records → Ansible Executes → AI Verifies → EOD (Save)
```

---

## 🗺️ 2. Environment Map (Fill in per project)

Before starting, know your tiers:

| Tier | Name | Environment |
|:---|:---|:---|
| **T1** | Command Centre | Windows 11 + PowerShell + Google Antigravity |
| **T2** | Dev Bridge | WSL2 `dsom-control-almalinux10` (AlmaLinux 10) |
| **T3** | Jump Host / Ansible Control | Remote orchestrator node |
| **T4** | Production Fabric | Target VMs / containers |

---

## 🔄 3. The SOD Workflow — Step by Step

### ⚡ Step 1 — Git Sync (T1: Windows PowerShell)

Pull the latest state from the remote before doing anything:

```powershell
# T1 — Git Ritual: SOD Pull
.\tools\git-ritual.ps1 sod
```

Or on T2 (WSL2):

```bash
# T2 — Git Ritual: SOD Pull
./tools/git-ritual.sh sod
```

**Why:** Ensures you start from the true current state — not yesterday's stale copy.

---

### 🏛️ Step 1a — [RECOMMENDED] Ansible Palace SOD (T2: Linux/WSL2 Only)

> **If you are on T2, use this instead of Steps 1–3.** It runs git pull, audit, palace check, and reanimate in a single command.

```bash
# T2 (Linux/WSL2) — Full automated SOD
bash tools/sod-palace.sh  # (Windows: .\tools\sod-palace.ps1)
```

**What it runs automatically:**

| Step | Action |
|---|---|
| 1 | `git pull --rebase origin main` |
| 2 | `tools/audit-pre-flight.sh` |
| 3 | Verify Palace Registry exists |
| 4 | `tools/reanimate.sh` (generates manifest with Section [14] Palace Registry) |
| 5 | Prints manifest path and handshake instructions |

**After it completes — Manual Handshake (Step 4 still applies):**

1. Upload the printed manifest file path to your AI
2. Say: *"Initialise DSOM Protocol v6.1 + Palace v1.0. Read the manifest. Walk the Palace Registry in Section [14]. State: 'Sovereign State Synchronised' when ready."*

> **T1 (Windows):** Steps 1–3 remain manual. Use `reanimate.ps1` which includes Section [14] Palace Registry natively.

---

### 🔍 Step 2 — Intelligence Audit (T1 or T2)

Run the pre-flight audit to verify the physical workspace is ready:

```powershell
# T1 (Windows PowerShell)
.\tools\audit-pre-flight.ps1
```

```bash
# T2 (WSL2 AlmaLinux 10)
./tools/audit-pre-flight.sh

# Or via Ansible (checks localhost + all inventory nodes simultaneously)
ansible-playbook playbooks/dsom/audit-preflight.yml -i localhost,
```

**The audit checks (v5.0):**

1. ✅ Brain artifacts exist (`task.md`, `walkthrough.md`)
2. ✅ Git is in sync with `origin/main`
3. ✅ Language/environment detected
4. ✅ `docs/AI-COGNITIVE-TWIN-PROTOCOL.md` — no unfilled `[PLACEHOLDER]` values
5. ✅ Ansible baseline — `ansible.cfg`, `inventory/hosts.yml`

**If anything shows `[FAIL]`** — fix it before proceeding. Do not reanimate a broken state.

---

### 🚀 Step 3 — Generate Reanimation Manifest (T1 or T2)

Generate the full context manifest for AI upload:

```bash
# T2 (WSL2)
bash tools/reanimate.sh
```

```powershell
# T1 (Windows PowerShell)
.\tools\reanimate.ps1
```

**What the manifest contains (v2.2 — Palace-aware):**

| Section | Content |
|:---|:---|
| `[1]` Identity | `README.md` — who we are |
| `[2]` Law | `AI-MASTER-PROTOCOL.md` — governance rules |
| `[3]` Present | `.agents/brain/task.md` — today's work |
| `[4]` Past | `.agents/brain/walkthrough.md` — full history |
| `[5]` Future | `.agents/brain/implementation_plan.md` — roadmap |
| `[6]` Git Log | Last 30 commits |
| `[7]` Tree | Project structure |
| `[8]` SUMMARY | Navigation index |
| `[9]` CHANGELOG | Version history |
| `[10]` Env | OS, user and environment telemetry |
| `[11]` Cognitive Twin | `AI-COGNITIVE-TWIN-PROTOCOL.md` |
| `[12]` Ansible | `inventory/hosts.yml` topology |
| `[13]` GitOps | `GITOPS-AIOPS-ANSIBLE-STRATEGY.md` |
| **`[14]`** | **`palace_registry.md` — AI spatial map (Palace v1.0)** |

**Run privacy scan before sharing:**

```bash
./tools/privacy-guardian.sh
# Or via Ansible:
ansible-playbook playbooks/dsom/privacy-scan.yml -i localhost,
```

---

### 🤝 Step 4 — The Cognitive Handshake (Human → AI)

Upload the generated `sod_manifest_YYYY-MM-DD.txt` to your AI chat.

**Option A — Reanimate from manifest file (Palace-aware):**
> *"Initialise DSOM Protocol v6.1 + Palace v1.0. Read the uploaded manifest. Walk the Palace Registry in Section [14] — identify relevant Rooms for today's work. Summarise the current Mental Anchor from `.agents/brain/walkthrough.md`. Confirm the 4-Tier environment map from `AI-COGNITIVE-TWIN-PROTOCOL.md`. State: 'Sovereign State Synchronised' when ready."*

**Option B — Use the Human Handover Context (for new sessions):**
> Copy the prompt from `docs/HUMAN-HANDOVER-CONTEXT.md` and paste as your first message. Fill in the **Current State** section before sending.

**Option C — Feed Yesterday's Hibernation Notes (EOD → SOD Continuity):**
> Use this when you saved a Hibernation Notes block from last night's EOD Step 1b. This is the fastest and most precise reanimation method — no tool required.

---

### 📥 Step 4b — SOD Reanimation Prompt (Feed Yesterday's Hibernation Notes)

This is the **mirror prompt** to the EOD Step 1b Hibernation Notes Export. It feeds everything saved last night directly back to the AI at the start of today — creating a **day-to-day continuity loop** that both humans and AI agents can rely on.

> [!IMPORTANT]
> Copy the prompt below verbatim. Then **paste your Hibernation Notes block** (saved from last night's EOD) immediately after the `[PASTE HIBERNATION NOTES BLOCK HERE]` placeholder.

**Copy this prompt verbatim:**

```
I am starting a new session for DSOM Protocol. I am your human Lead Architect.
I have the Hibernation Notes from our last session. Please read them carefully
and use them to fully restore our working context.

--- BEGIN HIBERNATION NOTES ---
[PASTE HIBERNATION NOTES BLOCK HERE]
--- END HIBERNATION NOTES ---

After reading the Hibernation Notes above, do the following:

1. Confirm you have read and understood all entries in the Hibernation Notes.
2. State the last Mental Anchor — the exact point where we stopped.
3. Confirm the 4-Tier environment map (T1, T2, T3, T4) and the project identity.
4. List the top 3 tasks from .agents/brain/task.md that are pending.
5. State: "Sovereign State Restored — [PROJECT NAME] is live." to confirm you are ready.

From this point, operate under DSOM Protocol v6.1:
- Advisory Mode only. You propose, I approve, Ansible executes, you verify.
- UK English in all responses and documentation.
- Every change goes to Git before execution.
- Ansible is the only executor for OS-level tasks.
- Wait for my output after each step before proceeding.
```

**What happens next:**

- AI reads the Hibernation Notes block and reconstructs full context
- AI confirms the Mental Anchor — the exact last stopping point
- AI confirms the environment map — which tier you are on
- AI lists today's pending tasks
- You begin active work immediately — no cold start, no repeated explanations

**The day-to-day continuity loop:**

```
DAY N EOD:           SOD Step 1 → 1b (Hibernation Notes saved)
                              ↓
                     [You sleep. Git holds the state.]
                              ↓
DAY N+1 SOD:         Step 4b (Hibernation Notes fed back to AI)
                              ↓
                     AI resumes with exact context. No decay.
                              ↓
DAY N+1 EOD:         Step 1b (new Hibernation Notes saved)
                              ↓  [repeats forever]
```

**Where to find yesterday's Hibernation Notes:**

- `.agents/brain/hibernation-notes-YYYY-MM-DD.txt` (if you saved it)
- Or the code block you copied to your notebook/notes app

---

### 🧠 Step 5 — What the AI Must Know After SOD

The SOD is **COMPLETE** only when the AI can confirm all of the following:

| Check | Content |
|:---|:---|
| Mental Anchor | Exact last stopping point from `walkthrough.md` |
| Environment | T1/T2/T3/T4 tiers and identities |
| Active Tasks | Current `task.md` checklist items |
| Roadmap | Active phase from `implementation_plan.md` |
| Doctrine | Advisory Mode, Ansible-only execution, no ad-hoc changes |
| Secrets | Ansible Vault only, no plaintext |

---

## 🛑 4. SOD Stop Conditions (FAILED States)

The SOD is **FAILED** if the AI:

| Failure | Meaning |
|:---|:---|
| Asks "What are we working on?" | Context was not loaded — re-run reanimate.sh |
| Proposes to run commands on production nodes directly | Identity Failure — re-read AI-MASTER-PROTOCOL.md |
| Ignores `task.md` checklist | Operational Failure — explicitly send the task list |
| References wrong environment (wrong OS, wrong user) | Environment Failure — re-read AI-COGNITIVE-TWIN-PROTOCOL.md |
| Shows unfilled `[PLACEHOLDER]` values | Protocol Failure — fix AI-COGNITIVE-TWIN-PROTOCOL.md first |

**If the SOD fails, do not proceed. Re-run from Step 2.**

---

## ✅ 5. SOD Completion Checklist

```
[ ] (RECOMMENDED T2) ansible-playbook sod-palace.yml — git pull + audit + palace check + reanimate
[ ]   OR (Manual T1/T2) git-ritual.sh sod — pulled latest from origin/main
[ ]   OR (Manual T1/T2) audit-pre-flight.sh — all steps PASS
[ ]   OR (Manual T1/T2) reanimate.sh — manifest generated (includes Section [14] Palace Registry)
[ ] privacy-guardian.sh — manifest scanned as CLEAN (if manifest will be shared)
[ ] Manifest uploaded to AI chat
[ ] AI walked Palace Registry (Section [14]) — relevant Rooms identified
[ ] AI confirmed Mental Anchor
[ ] AI confirmed 4-Tier environment map
[ ] AI stated "Sovereign State Synchronised"
```

**You are now cleared to begin active work.**

---

## 🔗 6. Related Documents

| Document | Purpose |
|:---|:---|
| [`docs/EOD-RITUAL.md`](EOD-RITUAL.md) | End-of-Day hibernation ritual |
| [`docs/HUMAN-HANDOVER-CONTEXT.md`](HUMAN-HANDOVER-CONTEXT.md) | Session handover prompt template |
| [`docs/REANIMATION-PROMPT-TEMPLATE.md`](REANIMATION-PROMPT-TEMPLATE.md) | AI session prompts |
| [`docs/AI-COGNITIVE-TWIN-PROTOCOL.md`](AI-COGNITIVE-TWIN-PROTOCOL.md) | Project Identity Card |
| [`docs/AI-MASTER-PROTOCOL.md`](AI-MASTER-PROTOCOL.md) | The Sovereign Constitution |
| [`docs/RITUAL-OF-TRANSITION.md`](RITUAL-OF-TRANSITION.md) | AI model switch ritual |
| [`docs/HOWTO-PALACE-ONBOARDING.md`](HOWTO-PALACE-ONBOARDING.md) | Palace structure + AI Walk protocol |
| [`playbooks/dsom/sod-palace.yml`](../playbooks/dsom/sod-palace.yml) | Ansible SOD automation (T2) |

---

*Standard: DSOM For My AI Protocol v6.1 + Palace v1.0 | Harisfazillah Jamel | LinuxMalaysia*
*This is the **baseline SOD template** for all projects built on this skeleton.*
*Last Updated: 2026-04-08 | Version: v6.1 + Palace v1.0*


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-04*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
