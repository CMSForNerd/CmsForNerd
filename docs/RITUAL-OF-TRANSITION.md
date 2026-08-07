---
okf_version: 0.1
type: documentation
title: "🕯️ DSOM Ritual of Transition (v6.1 + Palace v1.0)"
description: "OKF-compliant documentation for RITUAL-OF-TRANSITION.md."
resource: "file:///docs/RITUAL-OF-TRANSITION.md"
timestamp: 2026-07-04T09:40:04Z
topics: [docs, ritual, transition, dsom, palace]
---
# 🕯️ DSOM Ritual of Transition (v6.1 + Palace v1.0)

# docs/RITUAL-OF-TRANSITION.md

> **"Intelligence is ephemeral; the Repository is eternal."**
> **Standard: DSOM For My AI Protocol v6.1 + Palace v1.0 | GitOps · AIOps · Ansible**

The **Ritual of Transition** governs movement between cognitive states (AI context) and physical states (Git, Ansible, infrastructure). It is the operating membrane of the three-pillar model.

---

## 🏛️ 1. The Three-Pillar Transition Loop

Every state transition in DSOM passes through this loop. There are no shortcuts.

```
┌─────────────────────────────────────────────────────────┐
│                  DSOM TRANSITION LOOP                   │
│                                                         │
│  [SOD: Reanimate] → [Propose] → [Git Record] →         │
│  [Ansible Execute] → [Verify] → [EOD: Hibernate]       │
│          ↑_______________________________________↓      │
│               (repeats every day, forever)              │
└─────────────────────────────────────────────────────────┘
```

| Phase | Pillar | Action |
|:---|:---|:---|
| **🌅 SOD Reanimation** | AIOps | Load context → Cognitive Handshake |
| **🛠️ Active Flow** | AIOps + GitOps | AI proposes → Human approves → Git records |
| **⚙️ Execution** | Ansible | Human runs Ansible → AI verifies output |
| **🌙 EOD Hibernation** | GitOps | Sovereign Save → Hibernation Notes → git push |

---

## 🌅 2. Phase 1: Reanimation (Start-of-Day Transition)

*Objective: Restore the Cognitive Twin's full context from Git and initiate the Sovereign Directive.*

**Sequence:**

```
OPTION A: The Ansible Shortcut (Recommended on T2)
Step 1: bash tools/sod-palace.sh  # (Windows: .\tools\sod-palace.ps1)
        → Pulls Git, audits workspace, generates manifest with Section [14] Palace Registry
Step 2: Upload manifest to AI      → Cognitive Handshake

OPTION B: Manual / Windows T1
Step 1: git-ritual.sh sod          → pull latest from origin/main
Step 2: audit-pre-flight.sh        → verify workspace (5 checks, all must PASS)
Step 3: reanimate.sh               → generate 14-section context manifest
Step 4: privacy-guardian.sh        → scan manifest before upload
Step 5: Upload manifest to AI      → Cognitive Handshake
```

**Stop Conditions — Reanimation FAILS if:**

- AI cannot state the correct Mental Anchor from `walkthrough.md`
- AI references wrong environment (wrong T-tier, wrong OS, wrong user)
- `audit-pre-flight.sh` shows any `[FAIL]` — fix first, reanimate after
- AI proposes to execute directly on production nodes without Ansible

**The Sovereign Directive (confirm readiness):**
> *"Sovereign State Synchronised — [PROJECT NAME] is live and ready."*

If the AI cannot say this correctly, the SOD is incomplete. Re-run from Step 3.

**Full SOD guide:** [`docs/SOD-RITUAL.md`](SOD-RITUAL.md)

---

## 🛠️ 3. Phase 2: Active Flow (Execution Guardrails)

*Objective: Maintain the three-pillar model and architectural integrity during active work.*

### 3.1 The Human-AI Division of Responsibility

| Human (Sovereign Architect) | AI (Cognitive Digital Twin) |
|:---|:---|
| Defines the mission | Proposes implementation plan |
| Approves all proposals | Explains Why before What |
| Commits to Git | Updates `.agents/brain/task.md` |
| Runs Ansible commands | Writes the exact command for human |
| Pastes output back to AI | Verifies output, proposes next step |

### 3.2 The Active Flow Rules

1. **One thing at a time** — AI proposes a single step. Human approves. Execute. Then next step.
2. **Git before execution** — every change is committed before `ansible-playbook` runs.
3. **Brain before code** — update `.agents/brain/task.md` to reflect what you're doing *before* starting.
4. **Output before proceeding** — AI does not assume success. Human pastes terminal output every time.
5. **Why before What** — AI always explains the architectural reason before showing the command.

### 3.3 Ansible-First Rule (OS-Level Changes)

| ✅ Correct | ❌ Wrong |
|:---|:---|
| `ansible-playbook playbooks/dsom/[name].yml -i inventory/hosts.yml` | `ssh root@node "systemctl restart service"` |
| `ansible-lint playbooks/dsom/[name].yml` before commit | Manual config edits directly on nodes |
| `--check` dry-run before real run | Ad-hoc `ansible -m shell -a 'cmd'` on production |

### 3.4 Stop Conditions During Active Flow

Immediately pause and re-read `docs/AI-MASTER-PROTOCOL.md` if:

- AI suggests running commands directly on production nodes
- AI suggests committing secrets or tokens to any file
- AI skips explaining the architectural reason for a change
- AI proposes a change that would affect more than one logical concern (not atomic)
- Human is about to `git push --force` to `main`

---

## 🌙 4. Phase 3: Hibernation (End-of-Day Transition)

*Objective: Crystallise the day's work into Git and preserve the Cognitive Twin context for tomorrow.*

**Sequence:**

```
Step 1:  AI updates task.md  → mark [x] done, [ ] tomorrow's targets
Step 1b: AI updates walkthrough.md → new Session Anchor with Mental Anchor sentence
Step 2:  Run EOD Hibernation Notes prompt → save output to hibernation-notes-YYYY-MM-DD.txt

OPTION A: The Ansible EOD (Recommended on T2)
Step 3:  bash tools/eod-palace.sh  # (Windows: .\tools\eod-palace.ps1)
         → Validates artifacts, runs palace-sync, semantic commit, and git push automatically

OPTION B: Manual / Windows T1
Step 3:  hibernation.sh      → safety check (dirty state, uncommitted files) + palace-sync.pl
Step 4:  privacy-guardian.sh → scan if manifest was generated today
Step 5:  git-ritual.sh       → interactive EOD commit (guided semantic commit + push)
Step 6:  git pull origin main → T2 WSL2 sync after T1 push

Step 7:  Write tomorrow's Mental Anchor in your physical/digital notebook
```

**The Sovereign Save Commit format:**

```
chore(brain): EOD sovereign save — [Short Anchor] [Phase/vX.X]

Example:
chore(brain): EOD sovereign save — AlmaLinux 10 WSL setup complete [Phase-1/v6.1.1]
```

**Stop Conditions — EOD INCOMPLETE if:**

- `task.md` has no `[x]` items for today
- `walkthrough.md` is missing a new Session Anchor
- `hibernation.sh` returns any non-GREEN status
- `git push` has not completed successfully
- T2 WSL2 has not pulled from T1 push

**Do not sleep until all stop conditions are resolved.**

**Full EOD guide:** [`docs/EOD-RITUAL.md`](EOD-RITUAL.md)

---

## 🔀 5. The AI Model Transition (Switching AI Providers)

*Objective: Transfer the Cognitive Twin state from one AI model to another without context loss.*

This is triggered when:

- The current AI model is degrading (context decay, repeated errors)
- Switching between AI providers (Gemini → Claude → Copilot → Antigravity)
- Starting a fresh conversation in the same session

**The Transition Sequence:**

```
Step 1: In the outgoing AI session — run EOD Hibernation Notes Export (Prompt 5)
Step 2: Copy the full Hibernation Notes code block
Step 3: Open the new AI session
Step 4: Paste Prompt 3 (Cognitive Twin Handover) + Hibernation Notes block
Step 5: AI confirms Mental Anchor, T-tier map, sovereign laws, top 3 tasks
Step 6: AI states: "Cognitive Twin Transfer Complete — [PROJECT NAME] Handover Successful."
```

**Why this works:** The Hibernation Notes block contains everything the AI needs — project identity, environment, history, pending tasks, and operating rules. No manifest upload needed. No file access needed. Just text.

**Full prompt:** [`docs/REANIMATION-PROMPT-TEMPLATE.md`](REANIMATION-PROMPT-TEMPLATE.md) → Prompt 3

---

## 🏛️ 6. The Sunday Human Refresh Protocol

Every **Sunday**, the Lead Architect performs a **Dry-Run Audit** — a full human re-read of the project state:

```bash
# Run the full DSOM audit — confirm all 5 checks pass
./tools/audit-pre-flight.sh

# Or via Ansible (checks all inventory nodes simultaneously)
ansible-playbook playbooks/dsom/audit-preflight.yml -i localhost,

# Review the last 10 commits
git log --oneline -10

# Read the current Mental Anchor
cat .agents/brain/walkthrough.md | tail -30
```

**The Human Re-Index Checklist:**

```
[ ] AI-MASTER-PROTOCOL.md — governance laws still apply
[ ] AI-COGNITIVE-TWIN-PROTOCOL.md — all [PLACEHOLDER] fields filled
[ ] task.md — accurate, no stale items
[ ] walkthrough.md — Mental Anchor is current and precise
[ ] implementation_plan.md — roadmap still reflects reality
[ ] audit-pre-flight.sh — all 5 checks [PASS]
[ ] CONTRIBUTING.md — new contributors can follow it
```

---

## 🔗 7. Related Documents (Read in This Order)

| Priority | Document | When |
|:---|:---|:---|
| 1 | [`docs/AI-MASTER-PROTOCOL.md`](AI-MASTER-PROTOCOL.md) | Every session |
| 2 | [`docs/SOD-RITUAL.md`](SOD-RITUAL.md) | Every morning |
| 3 | [`docs/EOD-RITUAL.md`](EOD-RITUAL.md) | Every evening |
| 4 | [`docs/REANIMATION-PROMPT-TEMPLATE.md`](REANIMATION-PROMPT-TEMPLATE.md) | When prompting AI |
| 5 | [`docs/HUMAN-HANDOVER-CONTEXT.md`](HUMAN-HANDOVER-CONTEXT.md) | New session, new AI |
| 6 | [`docs/AI-COGNITIVE-TWIN-PROTOCOL.md`](AI-COGNITIVE-TWIN-PROTOCOL.md) | Per project — fill once |

---

*Standard: DSOM For My AI Protocol v6.1 + Palace v1.0 | Harisfazillah Jamel | LinuxMalaysia*
*This is the **baseline transition protocol** for all projects built on this skeleton.*
*Last Updated: 2026-04-08 | Version: v6.1*


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-04*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
