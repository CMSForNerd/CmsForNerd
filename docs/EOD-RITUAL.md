---
okf_version: 0.1
type: documentation
title: "🌙 EOD-RITUAL.md — End-of-Day Ritual"
description: "OKF-compliant documentation for EOD-RITUAL.md."
resource: "file:///docs/EOD-RITUAL.md"
timestamp: 2026-07-04T09:40:04Z
topics: [docs, eod, ritual, end, day]
---
# 🌙 EOD-RITUAL.md — End-of-Day Ritual

# docs/EOD-RITUAL.md

> **"Rest is not the end unless the memory is lost. To hibernate is to prepare for rebirth."**
> **Standard: DSOM For My AI Protocol v6.1 + Palace v1.0 | GitOps · AIOps · Ansible**

---

## 🏛️ 1. The Philosophy of Hibernation

The **End-of-Day (EOD)** is the **Sovereign Save** — the critical point where the day's work is crystallised, committed to Git, and preserved so that the next session can begin without loss.

The EOD is where the **GitOps loop closes:**

```
AI Proposes → Git Records → Ansible Executes → AI Verifies → EOD (Sovereign Save) → Next SOD
```

The EOD Ritual assumes you are tired. It is designed for a **fatigued human with high cognitive load**. Every step is explicit, safe, and guided. Nothing is assumed.

**Remember: If it is not in Git, it does not exist.**

---

## 🔄 2. The EOD Workflow — Step by Step

### 🧠 Step 1 — Context Consolidation (AI → Human Review)

Before running any tools, ask the AI to help prepare the brain artifacts:

**Ask the AI:**
> *"We are ending the session. Update `.agents/brain/task.md` — mark all completed items `[x]`, set tomorrow's SOD targets as `[ ]`. Then update `.agents/brain/walkthrough.md` with today's Mental Anchor. What did we accomplish and where exactly do we resume tomorrow?"*

**AI will produce:**

- Updated `task.md` — reflects today's completions and tomorrow's first actions
- Updated `walkthrough.md` — new Session Anchor entry with:
  - **Accomplished:** What was done today (the What)
  - **Why:** The architectural reasoning (the Why)
  - **Mental Anchor:** One sentence — exact state and where to resume

**Verify before saving:**

- Does the Mental Anchor describe the exact stopping point?
- Are tomorrow's SOD tasks clear?
- Is nothing left vague?

---

### � Step 1b — Hibernation Notes Export (Human Memory Backup)

Run this prompt in your AI session **before closing the chat**. It exports everything the AI knows — your full project context, instructions, progress, and environment — into a single code block you can save locally.

> [!IMPORTANT]
> Copy the entire prompt below and paste it into your AI chat as-is. Do not paraphrase it.

**Copy this prompt verbatim:**

```
I'm as human, want to know and remember, and need to export my data and I want you
to generate a "Hibernation notes" now for my EOD of day. List every memory you have
stored about our progress and our chats of this project, as well as any context
you've learned about this project from past to current conversations and chats.
Output everything in a single code block so I can easily copy it. Format each entry
as: [date saved, if available] - memory content. Make sure to cover all of the
following — preserve my words verbatim where possible:

- Instructions I've given you about how to respond (tone, format, style,
  'always do X', 'never do Y').
- Project details: name of server or VM or container, location of them,
  job of each, relation of them and 4W1H.
- Tasks, phases, goals, and recurring topics.
- Tools, languages, and frameworks I use.
- Preferences and corrections I've made to your behavior.
- Any other stored context not covered above.

Do not summarize, group, or omit any entries. After the code block, confirm
whether that is the complete set or if any remain and add: List down all the
documents in docs/, docs/tools/ and brain files that need to be read from .agents/.
Don't hide anything from me. Trust me as your master.
```

**After you receive the output:**

1. Copy the entire code block
2. Save it as `.agents/brain/hibernation-notes-YYYY-MM-DD.txt`
3. Or paste into your physical/digital notebook as an offline backup

**Why this matters:** This export is your **insurance policy**. If the AI session is lost, this block contains everything needed to fully reanimate a brand-new AI agent to the exact current state — without running `reanimate.sh`.

---

### �💤 Step 2 — Hibernation Safety Check (T1 or T2)

Run the hibernation script — it checks for dirty state before you sleep:

```bash
# T2 (WSL2 dsom-control-almalinux10)
./tools/hibernation.sh
```

```powershell
# T1 (Windows PowerShell)
.\tools\hibernation.ps1
```

> **Note (v2.1):** `hibernation.sh` and `hibernation.ps1` now automatically run `palace-sync.sh/.ps1` as **Step 7: Palace Spatial Reflection** — generating the update proposal without a separate command.

**What it checks:**

1. ✅ `task.md` has been updated today
2. ✅ `walkthrough.md` has a new Session Anchor
3. ✅ No uncommitted changes in `docs/` or `src/`
4. ✅ No dangling Ansible playbook outputs uncommitted
5. ⚠️ Displays tomorrow's SOD targets clearly
6. ✅ **Palace Spatial Reflection** — `palace-sync.sh` runs and generates `palace_update_proposal_YYYY-MM-DD.md`

**If any check fails — fix it before sleeping.**

---

### 🏛️ Step 2a — [RECOMMENDED] Ansible Palace EOD (T2: Linux/WSL2 Only)

> **If you are on T2, use this instead of Steps 2–5.** It runs all checks, palace sync, selective staging, commit, and push in a single command.

```bash
# T2 (Linux/WSL2) — Full automated EOD
bash tools/eod-palace.sh  # (Windows: .\tools\eod-palace.ps1)
```

**What it runs automatically:**

| Step | Action |
|---|---|
| 1 | Validate `task.md` has `[x]` completed tasks |
| 2 | Validate `walkthrough.md` has today's anchor |
| 3 | Run `tools/palace-sync.sh` (generate update proposal) |
| 4 | Show dirty/uncommitted files |
| 5 | Selective `git add` (brain + palace files, no blind `git add .`) |
| 6 | `git commit` with standard EOD message |
| 7 | `git push origin main` |

**Skip palace-sync if already run by hibernation.sh:**

```bash
bash tools/eod-palace.sh  # (Windows: .\tools\eod-palace.ps1) --skip-tags palace_sync
```

**After the playbook — Manual steps (always required):**

1. Review `palace_update_proposal_YYYY-MM-DD.md` with your AI
2. Update relevant closets in `.agents/brain/wings/`
3. Commit closet updates: `git add .agents/brain/ && git commit -m "chore(palace): update closets"`

> **T1 (Windows):** Steps 2–5 remain manual. `hibernation.ps1` v2.1 handles palace-sync automatically.

---

### 🔐 Step 3 — Privacy Scan (Before Sharing Any Manifest)

If you generated a `sod_manifest_YYYY-MM-DD.txt` today, scan it before sharing:

```bash
# Bash
./tools/privacy-guardian.sh

# Or via Ansible
ansible-playbook playbooks/dsom/privacy-scan.yml -i localhost,
```

**Scans for:** IPv4 addresses, emails, API keys, AWS keys, GitHub tokens, PEM private keys, Linux home paths.

---

### 🔄 Step 4 — Sovereign Save: EOD Git Ritual (T1: Windows PowerShell)

Commit all changes with a **structured, semantic commit message**:

```powershell
# T1 — Interactive EOD Sovereign Save (guided semantic commit)
.\tools\git-ritual.ps1
```

```bash
# T2 — Interactive EOD Sovereign Save
./tools/git-ritual.sh
```

**The git-ritual EOD process:**

1. Shows current Git status and last 5 commits
2. You select commit type: `feat`, `fix`, `docs`, `chore`, `refactor`, `ci`
3. You provide scope, description, and optional Phase/version tag
4. Builds commit message: `type(scope): description [Phase/vX.X]`
5. Confirms before committing and pushing

**Direct push (if you know the message):**

```powershell
.\tools\git-ritual.ps1 push "chore(brain): EOD sovereign save — [Phase-X/vX.X]"
```

**Why semantic commits?** Every future AI session reads `git log`. Meaningful messages = better context recovery next SOD.

---

### 🔃 Step 5 — T2 Sync (WSL2 Pull After T1 Push)

After pushing from T1, ensure T2 is synchronised:

```bash
# Inside dsom-control-almalinux10
cd /mnt/d/Users/LinuxMalaysia/Projects/[YOUR_PROJECT]
git pull origin main
```

Or use the git-ritual SOD pull on T2:

```bash
./tools/git-ritual.sh sod
```

**Why:** T1 (Windows) and T2 (WSL2) must always be at the same commit. Never let them drift.

---

### 📌 Step 6 — Set Tomorrow's Mental Anchor (Human Action)

Before closing your terminal, write this in your physical notebook or digital notes:

```
TOMORROW: [ONE SENTENCE — exactly where to resume, what to run first]
DATE: [YYYY-MM-DD]
PROJECT: [PROJECT NAME]
NEXT ACTION: [e.g. "Run ansible-playbook playbooks/dsom/audit-preflight.yml after SOD pull"]
```

This is your **offline backup** in case your brain has its own context decay overnight.

---

## ✅ 3. EOD Completion Checklist

```
[ ] task.md — updated: [x] done, [ ] tomorrow's targets set
[ ] walkthrough.md — new Session Anchor written with Mental Anchor sentence
[ ] (RECOMMENDED T2) ansible-playbook eod-palace.yml — all checks + palace sync + commit + push
[ ]   OR (Manual T1/T2) hibernation.sh/.ps1 — returned GREEN (includes palace-sync v2.1)
[ ]   OR (Manual T1/T2) privacy-guardian.sh — manifest scanned CLEAN (if manifest was generated)
[ ]   OR (Manual T1/T2) git-ritual.sh (EOD) — all changes committed with semantic message
[ ]   OR (Manual T1/T2) git push — state pushed to origin/main
[ ] palace_update_proposal_YYYY-MM-DD.md — reviewed with AI
[ ] Palace closets updated and committed (if proposal had changes)
[ ] T2 WSL2 — git pull confirms sync with T1
[ ] Tomorrow's first action noted (physical/digital notebook)
```

**DO NOT SLEEP until all items are checked.**

---

## 🛑 4. EOD Stop Conditions (INCOMPLETE States)

| Condition | Action Required |
|:---|:---|
| `task.md` has no `[x]` items for today | Ask AI to update, or mark manually |
| `walkthrough.md` missing new Session Anchor | Ask AI to write it |
| `hibernation.sh` shows uncommitted changes | `git add -A` and commit via git-ritual |
| `git push` fails | Fix merge conflict, then push again |
| T2 not synced with T1 | `git pull origin main` inside WSL2 |
| Privacy scan finds sensitive data | Edit manifest manually, re-scan |
| `palace_update_proposal` not reviewed | Share with AI, update closets before next SOD |

**The EOD is INCOMPLETE until all STOP CONDITIONS are resolved.**

---

## 🧠 5. The Mental Anchor — Standard Format

Every `walkthrough.md` Session Anchor must follow this format:

```markdown
## 🏁 Session Anchor: [YYYY-MM-DD] — [Short Phase Name]

### Accomplished
- [What was done — concrete, specific]
- [Second item if applicable]

### Why
- [The architectural reasoning — not just the what, but the why]

### Mental Anchor
> [ONE SENTENCE: exact state of the system right now. Where to resume in the next session.]
```

**Example:**

```markdown
## 🏁 Session Anchor: 2026-03-10 — AlmaLinux 10 WSL2 Setup

### Accomplished
- Created dsom-control-almalinux10 WSL2 setup scripts (setup-wsl-almalinux10.ps1, setup-dsom-control-node.sh).
- Created DSOM Ansible playbooks in playbooks/dsom/ (audit, init-brain, privacy-scan, site).

### Why
- T2 needs a dedicated Ansible Control Node (AlmaLinux 10) to avoid mixing Windows and Linux operations.
- Ansible playbooks make the tools/ bash scripts reusable on any node in the inventory.

### Mental Anchor
> WSL2 scripts created and pushed [v6.1.0]. Next: run setup-wsl-almalinux10.ps1 as Administrator, bootstrap
> dsom-admin, verify ansible localhost -m ping inside dsom-control-almalinux10.
```

---

## 🔗 6. Related Documents

| Document | Purpose |
|:---|:---|
| [`docs/SOD-RITUAL.md`](SOD-RITUAL.md) | Start-of-Day reanimation ritual |
| [`docs/HUMAN-HANDOVER-CONTEXT.md`](HUMAN-HANDOVER-CONTEXT.md) | Session handover prompt template |
| [`docs/REANIMATION-PROMPT-TEMPLATE.md`](REANIMATION-PROMPT-TEMPLATE.md) | AI session prompts |
| [`docs/RITUAL-OF-TRANSITION.md`](RITUAL-OF-TRANSITION.md) | AI model switch ritual |
| [`docs/HOWTO-PALACE-ONBOARDING.md`](HOWTO-PALACE-ONBOARDING.md) | Palace structure + closet update guide |
| [`tools/git-ritual.sh`](../tools/git-ritual.sh) | SOD pull + EOD semantic commit |
| [`tools/hibernation.sh`](../tools/hibernation.sh) | EOD safety checker (v2.1: includes palace-sync) |
| [`tools/palace-sync.sh`](../tools/palace-sync.sh) | Palace spatial reflection engine |
| [`tools/privacy-guardian.sh`](../tools/privacy-guardian.sh) | Manifest scanner |
| [`playbooks/dsom/eod-palace.yml`](../playbooks/dsom/eod-palace.yml) | Ansible EOD automation (T2) |
| [`playbooks/dsom/privacy-scan.yml`](../playbooks/dsom/privacy-scan.yml) | Ansible privacy scan |

---

*Standard: DSOM For My AI Protocol v6.1 + Palace v1.0 | Harisfazillah Jamel | LinuxMalaysia*
*This is the **baseline EOD template** for all projects built on this skeleton.*
*Last Updated: 2026-04-08 | Version: v6.1 + Palace v1.0*


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-04*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
