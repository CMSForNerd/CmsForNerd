---
okf_version: 0.1
type: documentation
title: "🏛️ HOWTO: Palace Onboarding — First-Time Guide for Users and AI Agents"
description: "OKF-compliant documentation for HOWTO-PALACE-ONBOARDING.md."
resource: "file:///docs/HOWTO-PALACE-ONBOARDING.md"
timestamp: 2026-07-04T09:40:04Z
topics: [docs, palace, onboarding, first, time]
---
# 🏛️ HOWTO: Palace Onboarding — First-Time Guide for Users and AI Agents

> **Entry Point 4:** This document serves as the Daily Operations Entry Point. See [START-HERE.md](../START-HERE.md) for the master onboarding roadmap.

> **Who this document is for:** Any new human user adopting DSOM, or any AI agent reading this repo for the first time.

**Workspace Version:** v2.0 (OKF Palace) | **Protocol:** DSOM v6.1

---

## 🤖 If You Are an AI Agent Reading This

**Welcome.** This repository uses the **Sovereign Markdown Palace** — a spatial memory system layered on top of standard Git. Before doing anything else, follow this exact reading sequence:

### Step 1 — Read the Palace Registry (Your Spatial Map)

```
.agents/brain/palace_registry.md
```

This is your entry point. It lists all Wings, Halls, and Rooms with a Quick-Reference table mapping tasks to the correct Room.

### Step 2 — Walk to Relevant Rooms

Based on the current task, load the `closet.md` for each relevant Room. Do NOT read the full `walkthrough.md` first — the closets give you 90% of what you need in 10% of the tokens.

**Quick example:**

- Working on a tool script? → `wings/wing_dsom_core/hall_facts/room_tooling/closet.md`
- Working on infrastructure/Ansible? → `wings/wing_dsom_core/hall_events/room_sovereign_fabric_v9_8/closet.md`
- Checking version history? → `wings/wing_dsom_core/hall_events/room_ledger/closet.md`

### Step 3 — Drill Down Only If Needed

If the closet summary is insufficient for precision work, follow its `## 🔗 Retrieval Reference` link to the specific line range in `walkthrough.md`.

### Step 4 — Confirm with the Human

State: **"Palace orientation complete. Walking [Room Name]. Ready to proceed."**

---

## 👤 If You Are a New Human User

### What Is the Palace?

The Palace is the **memory layer** of DSOM. It gives your AI fast, accurate, spatially-organised recall across months and years of project history — without needing to re-read entire session logs.

Think of it as a **library index**: instead of reading every book to find a fact, you walk to the right shelf (Wing → Hall → Room → Closet) and find a distilled summary.

### How Does It Work With the Rest of DSOM?

```
YOUR PROJECT REPOSITORY
│
├── README.md                     ← START HERE (overview + daily rituals)
├── docs/
│   ├── AI-COGNITIVE-TWIN-PROTOCOL.md   ← Fill this in for your project
│   ├── AI-MASTER-PROTOCOL.md           ← Governance laws (read once)
│   ├── DIGITAL-SOVEREIGNTY-OPERATIONAL-MODEL-PALACE.md  ← Full Palace spec
│   └── HOWTO-PALACE-ONBOARDING.md     ← You are here
│
└── .agents/
    ├── AGENTS.md                 ← The core rulebook for AI operations
    ├── skills/                   ← Self-healing OKF skills (eod-sync, gitops)
    └── brain/
        ├── palace_registry.md    ← AI's spatial index (read at every SOD)
        ├── task.md               ← What the AI is working on NOW
        ├── walkthrough.md        ← Full session history (The Drawer)
        └── wings/                ← The Palace Rooms
            └── wing_dsom_core/
                ├── hall_facts/   ← Laws, architecture, tools
                └── hall_events/  ← Milestones, versions, brain history
```

### The Document Reading Chain (First Time)

Follow this order for full context:

1. **`README.md`** — What DSOM is, the 6-step boot checklist, daily rituals.
2. **`docs/AI-COGNITIVE-TWIN-PROTOCOL.md`** — Fill in every `[PLACEHOLDER]` for your project.
3. **`docs/DIGITAL-SOVEREIGNTY-OPERATIONAL-MODEL-PALACE.md`** — Understand the Palace laws and the Ingestion Loop.
4. **`.agents/brain/palace_registry.md`** — See what Rooms exist and what they contain.
5. **Pick relevant closets** — Read the 2-3 closets most relevant to your current work.
6. **`docs/SOD-RITUAL.md`** + **`docs/EOD-RITUAL.md`** — Understand the daily operating rhythm, including the Ansible SOD/EOD playbooks.

---

## 🏗️ Palace Structure Reference

### Wings (Domains)

Each Wing represents a major project domain.

| Wing | Description |
|---|---|
| `wing_dsom_core` | The DSOM framework itself — protocol, tools, infrastructure |

### Halls (Memory Types)

Every Wing has the same hall types for consistency.

| Hall | What It Contains |
|---|---|
| `hall_facts` | Immutable laws, architecture decisions, tool inventory |
| `hall_events` | Chronological milestones, phase completions, ledger |
| `hall_preferences` | Architect persona, style rules, linguistic mandates |
| `hall_discoveries` | Breakthroughs, uncategorised findings |

### Rooms (Topics)

Specific subjects within a hall. Each room has one `closet.md`.

| Room | Hall | What It Knows |
|---|---|---|
| `room_clean_architecture` | hall_facts | Inward Dependency Rule, Clean Architecture layers |
| `room_crisp_strategy` | hall_facts | CRISP mandate, interaction filters |
| `room_dsom_protocol` | hall_facts | All protocol docs, SOD/EOD, rituals, README evolution |
| `room_tooling` | hall_facts | Every tool, its version, purpose, and evolution |
| `room_sovereign_fabric_v9_8` | hall_events | Elasticsearch fabric, Ansible infra, WSL2 |
| `room_brain_artifacts` | hall_events | Brain file evolution, Palace v1.0 launch |
| `room_ledger` | hall_events | CHANGELOG/HISTORY versions from v1.0 to Palace v1.0 |
| `room_uncategorised` | hall_discoveries | PDFs, GitBook, .github/, ansible.cfg fixes |

---

## 🔄 The Palace in the Daily Loop

```
SOD (Start of Day)
  └── bash tools/sod-palace.sh  # (Windows: .\tools\sod-palace.ps1) (or bash tools/reanimate.sh)
        └── Audits environment, validates Git, executes Palace check
              └── Section [14]: Palace Registry loaded into manifest
                    └── AI reads registry → walks relevant rooms → ready in seconds

Active Work
  └── git commit (any time)
        └── AI notes which files changed
              └── Identifies target Room per Git Reflection Mandate

EOD (End of Day)
  └── bash tools/eod-palace.sh  # (Windows: .\tools\eod-palace.ps1) (or bash tools/hibernation.sh)
        └── Validates artifacts, runs palace-sync.sh automatically
              └── Generates palace_update_proposal_YYYY-MM-DD.md
                    └── AI reviews → updates closets → registry stays fresh
```

---

## 🆕 Adding a New Wing (New Project Domain)

When you start a new project on this DSOM instance, create a new Wing:

```bash
mkdir -p .agents/brain/wings/wing_YOUR_PROJECT/{hall_facts,hall_events,hall_discoveries}
# Create at minimum one Room
mkdir -p .agents/brain/wings/wing_YOUR_PROJECT/hall_facts/room_overview
touch .agents/brain/wings/wing_YOUR_PROJECT/hall_facts/room_overview/closet.md
```

Then register it in `palace_registry.md`.

---

## ⚖️ The Three Sovereign Laws of the Palace

1. **Plain Markdown Mandate** — All closets must be human-legible Markdown. No proprietary encoding.
2. **Universal Search** — Every closet must link back to the original Drawer entry. No AI hallucination.
3. **Git Sovereignty** — Every closet update must be committed to Git alongside the code change that triggered it.

---
*Document created: 2026-04-08 | Palace v1.0 | DSOM Protocol v6.1*
*Author: Harisfazillah Jamel | Partner: Google Antigravity*


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-04*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
