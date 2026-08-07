---
okf_version: 0.1
type: documentation
title: "🎭 Claude.ai Integration Protocol (v6.1 + Palace v1.0)"
description: "OKF-compliant documentation for CLAUDE-SETUP.md."
resource: "file:///docs/CLAUDE-SETUP.md"
timestamp: 2026-07-04T09:40:04Z
topics: [docs, claude, integration, protocol, palace]
---
# 🎭 Claude.ai Integration Protocol (v6.1 + Palace v1.0)

## docs/CLAUDE-SETUP.md

> **"Advisory over Execution. Context over Command. Sovereign Continuity across every AI."**
> **Standard: DSOM Protocol v6.1 + Palace v1.0 | GitOps · AIOps · Ansible**

---

## 🏛️ 1. The Claude Strategy

Claude.ai is integrated into the DSOM framework as a **Cognitive Digital Twin** operating under the same Three-Pillar model as all other supported AI providers:

```
AIOps (Claude) → GitOps (Git Records) → Ansible (Human Executes)
```

Claude uses **Projects** to maintain persistent state. The **Project Knowledge Base** acts as the Long-Term Memory, and the **Project Instructions** act as the Sovereign Law.

### The Three Hard Rules (apply to Claude equally)

1. **Claude never runs commands directly on remote nodes.** It proposes playbooks. You run them.
2. **No manual edits to production servers.** If it is not committed to Git, it does not exist.
3. **Every proposal must be idempotent.** Safe to re-run at any time.

---

## 🚀 2. One-Time Setup (New Project)

### Step 1 — Create a Claude Project

1. Go to [claude.ai](https://claude.ai) → **Projects** → **New Project**.
2. Name it after your DSOM project (e.g., `DSOM-deep-state-of-mind`).

### Step 2 — Set the Project Instructions (The Sovereign Guard)

Open **Project Settings → Instructions** and paste the following block verbatim. This defines Claude's persona and operating constraints for the lifetime of the project.

```text
[DSOM SOVEREIGN INSTRUCTIONS v6.1 + Palace v1.0]

You are the Claude-variant of the DSOM Cognitive Digital Twin. You are a Senior Systems Architect assisting Harisfazillah Jamel (LinuxMalaysia), who has 35+ years of ICT and Open Source expertise.

OPERATING MODEL:
- Advisory Mode ONLY. You propose. The human approves. Ansible executes. You verify.
- UK English in all responses and documentation.
- Atomic Git Hygiene: Propose changes one file at a time using 'type(scope): description [Phase/vX.X]' format.
- Ansible is the ONLY executor for OS-level tasks on remote nodes.
- Wait for human output after each step before proceeding.

DSOM LAWS (non-negotiable):
1. Zero-Global Pattern — No global variables; strict state management only.
2. Sovereign Portability — No vendor lock-in; code must be Linux-agnostic.
3. HA-Ready — Design for clusters and zero-downtime.
4. Atomic Git Hygiene — One logical change per commit.
5. Pedagogical Logic — Explain the 'Why' (logic/security/performance) before the 'What' (code).
6. GitOps Rule — If it is not committed to Git, it does not exist.
7. Ansible-First Execution — No ad-hoc commands on target nodes. Playbooks only.

4-TIER ENVIRONMENT:
- T1 (Command Centre): Human workstation — Code editing, Git, brain artifact maintenance.
- T2 (Dev Bridge): WSL2 / local VM — Ansible dry-run, log analysis.
- T3 (Staging): Pre-production validation.
- T4 (Production): Zero-tolerance for ad-hoc changes. Ansible + GitOps only.

BRAIN SYNC:
Your Single Source of Truth (SSoT) is the uploaded manifest file (e.g. sod_manifest_YYYY-MM-DD.txt) in Project Knowledge.
Before proposing any action, you MUST read Section [14] Palace Registry and walk the relevant spatial Rooms.
Always refer to it for the current Mental Anchor, task list, and roadmap.

MIRROR LAW:
Challenge the human if their instructions lack architectural clarity. Ask for the missing 'Why' before acting. Silence is the only failure.

When ready, state: 'Sovereign State Synchronised — [PROJECT NAME] is live.'
```

### Step 3 — Upload the Brain to Project Knowledge

Generate the Claude context manifest from your repo:

```bash
# Recommended on T2 (Linux / WSL2)
bash tools/sod-palace.sh  # (Windows: .\tools\sod-palace.ps1)

# Or manual fallback
bash tools/reanimate.sh
```

Then upload the generated `sod_manifest_YYYY-MM-DD.txt` to the **Project Knowledge** section. This file contains the current `task.md`, `walkthrough.md`, and `implementation_plan.md` — the full context of the project.

> ⚠️ **Re-upload the manifest every SOD** to ensure Claude has the latest Mental Anchor.

---

## 🌅 3. Start-of-Day (SOD) Ritual for Claude

### Step 1-3 — The Automated Ansible SOD

(Skip manual steps if using Ansible)

```bash
bash tools/sod-palace.sh  # (Windows: .\tools\sod-palace.ps1)
```

Upload the generated `sod_manifest_YYYY-MM-DD.txt` to **Project Knowledge** (replacing any previous manifest).

### Step 1-3 (Manual Alternative)

```bash
./tools/git-ritual.sh sod      # WSL2
./tools/audit-pre-flight.sh    # WSL2 (All checks must [PASS])
bash tools/reanimate.sh        # Generate manifest
./tools/privacy-guardian.sh    # Scan before uploading
```

Upload the generated `sod_manifest_YYYY-MM-DD.txt` to **Project Knowledge** (replacing any previous manifest).

### Step 4 — The Cognitive Handshake

After uploading the manifest, start a new conversation and use:

> *"Initialise DSOM Protocol v6.1 + Palace v1.0. Read the uploaded manifest. Walk the Palace Registry (Section [14]). Summarise the current Mental Anchor from `.agents/brain/walkthrough.md`. Confirm the 4-Tier environment map from `AI-COGNITIVE-TWIN-PROTOCOL.md`. State: 'Sovereign State Synchronised' when ready."*

**Option B — Feed Yesterday's Hibernation Notes:**

```
I am starting a new session for DSOM Protocol. I am your human Lead Architect.
I have the Hibernation Notes from our last session. Please read them carefully
and use them to fully restore our working context.

--- BEGIN HIBERNATION NOTES ---
[PASTE HIBERNATION NOTES BLOCK HERE]
--- END HIBERNATION NOTES ---

After reading: state the last Mental Anchor, confirm the T1-T4 environment map,
list top 3 pending tasks. State: 'Sovereign State Restored — [PROJECT NAME] is live.'
Operate under DSOM v6.1: Advisory Mode, UK English, Git-first, Ansible-only execution.
```

### ✅ SOD is Complete When Claude Can Confirm

| Check | Content |
|:---|:---|
| Mental Anchor | Exact last stopping point from `walkthrough.md` |
| Environment | T1/T2/T3/T4 tiers and identities |
| Active Tasks | Current `task.md` checklist items |
| Doctrine | Advisory Mode, Ansible-only execution, no ad-hoc changes |

---

## 🛑 4. SOD Stop Conditions (FAILED States)

The SOD is **FAILED** if Claude:

| Failure | Meaning |
|:---|:---|
| Asks "What are we working on?" | Context not loaded — re-upload manifest |
| Proposes to run commands on remote nodes directly | Identity Failure — re-read Project Instructions |
| Ignores `task.md` checklist | Operational Failure — explicitly send the task list |
| References wrong environment | Environment Failure — re-upload manifest |
| Responds in American English | Linguistic Failure — remind of UK English mandate |

**If the SOD fails, do not proceed. Re-run from Step 3.**

---

## 🌙 5. End-of-Day (EOD) Ritual for Claude

### Step 1 — Context Consolidation

Ask Claude:
> *"We are ending the session. Update `.agents/brain/task.md` — mark completed `[x]`, set tomorrow's targets `[ ]`. Update `.agents/brain/walkthrough.md` with today's Mental Anchor."*

### Step 1b — Hibernation Notes Export

Run this prompt verbatim in your Claude chat — copy the output to a safe location:

```
I am as human, want to know and remember, and need to export my data and I want
you to generate "Hibernation Notes" now for my EOD. List every memory you have
stored about our progress and our chats of this project, as well as any context
you've learned about this project from past to current conversations.
Output everything in a single code block so I can easily copy it.
Format each entry as: [date saved, if available] - memory content.
Cover: instructions I've given you, project details (servers/VMs/containers,
4W1H), tasks/phases/goals, tools/languages/frameworks, preferences and
corrections. Do not summarize, group, or omit any entries.
After the code block, list all docs in docs/ and brain files in .agents/.
Don't hide anything from me. Trust me as your master.
```

Save the output as `.agents/brain/hibernation-notes-YYYY-MM-DD.txt`.

### Step 2 — Sovereign Save

```bash
# Recommended on T2
bash tools/eod-palace.sh  # (Windows: .\tools\eod-palace.ps1)

# Or manual fallback
./tools/git-ritual.sh          # WSL2 — guided EOD commit and push
```

---

## 🔄 6. The Day-to-Day Continuity Loop (Claude Edition)

```
EOD → Step 1b: Save Hibernation Notes to .agents/brain/hibernation-notes-YYYY-MM-DD.txt
         ↓  [Git holds state. Claude holds nothing.]
SOD → Step 4b: Feed Hibernation Notes back to Claude
         ↓  [Claude resumes with exact context — no decay.]
EOD → Step 1b: Save new Hibernation Notes
         ↓  [repeats every day, forever]
```

---

## 🔀 7. Cross-AI Handover (Switching from Claude to Another AI)

When switching from Claude to Gemini, Antigravity, or Copilot, the `.agents/brain/` remains the **Single Source of Truth**. Use the **Sovereign Handover Prompt** from the old session:

> Copy the prompt from [`docs/RITUAL-OF-TRANSITION.md`](RITUAL-OF-TRANSITION.md) → **Prompt Variant: Session Handover**.

Then load the memory dump into the new AI session along with the manifest generated by `reanimate.sh`.

---

## 🔗 8. Related Documents

| Document | Purpose |
|:---|:---|
| [`docs/SOD-RITUAL.md`](SOD-RITUAL.md) | Full Start-of-Day ritual guide |
| [`docs/EOD-RITUAL.md`](EOD-RITUAL.md) | Full End-of-Day hibernation ritual |
| [`docs/RITUAL-OF-TRANSITION.md`](RITUAL-OF-TRANSITION.md) | Switching AI providers |
| [`docs/MULTI-AGENT-PROTOCOLS.md`](MULTI-AGENT-PROTOCOLS.md) | Universal multi-agent injection method |
| [`docs/AI-COGNITIVE-TWIN-PROTOCOL.md`](AI-COGNITIVE-TWIN-PROTOCOL.md) | Project Identity Card |
| [`docs/AI-MASTER-PROTOCOL.md`](AI-MASTER-PROTOCOL.md) | The Sovereign Constitution |

---

*Standard: DSOM For My AI Protocol v6.1 | Harisfazillah Jamel | LinuxMalaysia*
*Last Updated: 2026-03-29 | Version: v6.1*


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-04*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
