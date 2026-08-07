---
okf_version: 0.1
type: documentation
title: "🛡️ GitHub Copilot Integration Protocol (v6.1 + Palace v1.0)"
description: "OKF-compliant documentation for COPILOT-SETUP.md."
resource: "file:///docs/COPILOT-SETUP.md"
timestamp: 2026-07-04T09:40:04Z
topics: [docs, copilot, github, integration, protocol]
---
# 🛡️ GitHub Copilot Integration Protocol (v6.1 + Palace v1.0)

## docs/COPILOT-SETUP.md

> **"Advisory over Execution. Context over Command. Sovereign Continuity across every AI."**
> **Standard: DSOM Protocol v6.1 + Palace v1.0 | GitOps · AIOps · Ansible**

---

## 🏛️ 1. The Copilot Strategy

GitHub Copilot is integrated into the DSOM framework as a **Tier 1 Co-Pilot** operating within the IDE. Because Copilot lacks a persistent Project-level memory (like Claude Projects), the DSOM framework injects context through two mechanisms:

1. **Repository-level Instructions** — `.github/copilot-instructions.md` — permanent Sovereign Law.
2. **Chat-level File Mentions** — explicit `#file:` references — session-level context injection.

The Three-Pillar loop applies to Copilot equally:

```
AIOps (Copilot proposes) → GitOps (Git records) → Ansible (Human executes)
```

### The Three Hard Rules (apply to Copilot equally)

1. **Copilot never runs commands directly on remote nodes.** It proposes code and playbooks. You run them.
2. **No manual edits to production servers.** If it is not committed to Git, it does not exist.
3. **Every proposal must be idempotent.** Safe to re-run at any time.

---

## ⚙️ 2. One-Time Setup

### Step 1 — Create the Copilot Instructions File

Create or update `.github/copilot-instructions.md` in your repository root with the following block. Copilot reads this file automatically for all chats in the workspace.

```markdown
# DSOM Sovereign Instructions for GitHub Copilot (v6.1 + Palace v1.0)

You are a DSOM-Compliant Cognitive Co-Pilot. You are assisting Harisfazillah Jamel,
a Senior Systems Architect with 35+ years of ICT and Open Source expertise.

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
5. Pedagogical Logic — Explain the 'Why' before the 'What'.
6. GitOps Rule — If it is not committed to Git, it does not exist.
7. Ansible-First Execution — No ad-hoc commands on target nodes. Playbooks only.

BRAIN SYNC:
Before answering any architecture question, read:
- .agents/brain/palace_registry.md (Walk the Palace)
- .agents/brain/task.md
- .agents/brain/walkthrough.md
- docs/AI-MASTER-PROTOCOL.md

MIRROR LAW:
If instructions lack architectural clarity, challenge the human and ask for the
missing 'Why' before acting. Silence is the only failure.
```

> ✅ **Once placed in `.github/copilot-instructions.md`, Copilot will read these rules for every session in this repository automatically.**

### Step 2 — Create the Reanimation Prompt File

Create `.github/prompts/dsom-reanimate.prompt.md` to enable quick SOD context injection via the `/` slash command in Copilot Chat:

```markdown
---
mode: 'agent'
description: 'DSOM SOD Reanimation — load current brain and Mental Anchor'
---

Read the following brain artifacts and summarise the current status:
1. `.agents/brain/palace_registry.md` — identify the current spatial mapping.
2. `.agents/brain/task.md` — list all incomplete `[ ]` items.
3. `.agents/brain/walkthrough.md` — state the last Mental Anchor.
4. `docs/AI-COGNITIVE-TWIN-PROTOCOL.md` — confirm the 4-Tier environment map.

Then state: 'Sovereign State Synchronised — [PROJECT NAME] is live.'
Operate under DSOM v6.1 + Palace v1.0: Advisory Mode, UK English, Git-first, Ansible-only execution.
```

---

## 🌅 3. Start-of-Day (SOD) Ritual for Copilot

### Step 1-2 — Automated Ansible Check

If using the T2 Dev Bridge (Recommended):

```bash
bash tools/sod-palace.sh  # (Windows: .\tools\sod-palace.ps1)
```

### Step 1-2 — Manual Fallback

```bash
./tools/git-ritual.sh sod      # WSL2 / Linux
./tools/audit-pre-flight.sh    # All checks must [PASS]
```

All checks must show `[PASS]`. Fix any `[FAIL]` before proceeding.

### Step 3 — The Cognitive Handshake (Two Options)

**Option A — Use the `/dsom-reanimate` Slash Command:**

1. Open **Copilot Chat** in VS Code.
2. Type `/` → select `dsom-reanimate`.
3. Copilot reads all brain artifacts and states the current Mental Anchor.

**Option B — Manual File Mention Injection:**

Paste this prompt into Copilot Chat with explicit file references:

> *"Based on **#file:.agents/brain/palace_registry.md**, **#file:.agents/brain/task.md** and **#file:.agents/brain/walkthrough.md**, summarise the current spatial room, Mental Anchor, and list today's pending tasks. Confirm the 4-Tier environment from **#file:docs/AI-COGNITIVE-TWIN-PROTOCOL.md**. State: 'Sovereign State Synchronised' when ready."*

### ✅ SOD is Complete When Copilot Can Confirm

| Check | Content |
|:---|:---|
| Mental Anchor | Exact last stopping point from `walkthrough.md` |
| Environment | T1/T2/T3/T4 tiers and identities |
| Active Tasks | Current `task.md` incomplete `[ ]` items |
| Doctrine | Advisory Mode, Ansible-only execution, no ad-hoc changes |

---

## 🛑 4. SOD Stop Conditions (FAILED States)

The SOD is **FAILED** if Copilot:

| Failure | Meaning |
|:---|:---|
| Cannot find `task.md` or `walkthrough.md` | Brain not loaded — use `#file:` mention explicitly |
| Proposes to run commands on remote nodes directly | Identity Failure — re-read `.github/copilot-instructions.md` |
| Ignores the `task.md` checklist | Operational Failure — explicitly send the task list in chat |
| Uses American English spelling | Linguistic Failure — remind of UK English mandate |
| References wrong environment or tier | Re-send `#file:docs/AI-COGNITIVE-TWIN-PROTOCOL.md` |

**If the SOD fails, do not proceed. Re-run from Step 3.**

---

## 💬 5. Workspace Context Techniques

### Using File Mentions (`#file:`)

Explicitly attach brain artifacts for precise context:

```
Based on #file:.agents/brain/task.md and #file:.agents/brain/walkthrough.md,
what is the next logical step for the current implementation phase?
```

### Using Workspace Context (`@workspace`)

Query the entire project structure:

```
@workspace verify if the current directory structure adheres to the
DSOM Sovereign Portability law and Clean Architecture layers.
```

### Referencing Playbooks

When working on Ansible:

```
Based on #file:playbooks/dsom/site.yml and #file:docs/AI-COGNITIVE-TWIN-PROTOCOL.md,
propose an idempotent role for the T4 Production baseline.
```

---

## 🌙 6. End-of-Day (EOD) Ritual for Copilot

Because Copilot has no persistent memory, the EOD ritual focuses on ensuring the **brain artifacts** are correctly updated and committed before ending the session.

### Step 1 — Context Consolidation

Ask Copilot in Chat:
> *"Based on **#file:.agents/brain/task.md**, mark all completed tasks `[x]` and set tomorrow's targets `[ ]`. Update **#file:.agents/brain/walkthrough.md** with a new Mental Anchor summarising today's session."*

Review the proposed changes, apply them, then commit:

### Step 2 — Sovereign Save

```bash
# Recommended on T2
bash tools/eod-palace.sh  # (Windows: .\tools\eod-palace.ps1)

# Or manual fallback
./tools/git-ritual.sh          # guided EOD commit and push
```

---

## 🔀 7. Cross-AI Handover (Switching from Copilot to Another AI)

The `.agents/brain/` directory is the **Single Source of Truth** regardless of which AI you are using. When switching to Claude or Gemini:

1. Ensure brain artifacts are committed and pushed.
2. Run `bash tools/reanimate.sh` to generate the context manifest.
3. Follow the SOD ritual for the target AI provider.

See [`docs/RITUAL-OF-TRANSITION.md`](RITUAL-OF-TRANSITION.md) for the full handover prompt.

---

## 🔗 8. Related Documents

| Document | Purpose |
|:---|:---|
| [`docs/SOD-RITUAL.md`](SOD-RITUAL.md) | Full Start-of-Day ritual guide |
| [`docs/EOD-RITUAL.md`](EOD-RITUAL.md) | Full End-of-Day hibernation ritual |
| [`docs/CLAUDE-SETUP.md`](CLAUDE-SETUP.md) | Claude.ai integration protocol |
| [`docs/RITUAL-OF-TRANSITION.md`](RITUAL-OF-TRANSITION.md) | Switching AI providers |
| [`docs/MULTI-AGENT-PROTOCOLS.md`](MULTI-AGENT-PROTOCOLS.md) | Universal multi-agent injection method |
| [`docs/AI-MASTER-PROTOCOL.md`](AI-MASTER-PROTOCOL.md) | The Sovereign Constitution |

---

*Standard: DSOM For My AI Protocol v6.1 | Harisfazillah Jamel | LinuxMalaysia*
*Last Updated: 2026-03-29 | Version: v6.1*


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-04*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
