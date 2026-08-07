---
okf_version: 0.1
type: governance_protocol
title: "🏛️ Sovereign Markdown Palace Protocol (v1.0)"
description: "OKF-compliant documentation for DIGITAL-SOVEREIGNTY-OPERATIONAL-MODEL-PALACE.md."
resource: "file:///docs/governance/DIGITAL-SOVEREIGNTY-OPERATIONAL-MODEL-PALACE.md"
timestamp: 2026-07-04T09:40:04Z
topics: [docs, governance, digital, sovereignty, operational]
---
# 🏛️ Sovereign Markdown Palace Protocol (v1.0)

> **"Sovereignty through Spatial Awareness. Recall through Structured Reflection."**

## 1. 🏛️ Concept: The Memory Archive Evolved

The **Sovereign Markdown Palace** is a spatial governance layer for the **Deep State of Mind (DSOM)** framework. This architecture **originated from** and was inspired by the **MemPalace** project ([milla-jovovich/mempalace](https://github.com/milla-jovovich/mempalace)). It replaces flat, linear session logs with a multidimensional directory hierarchy.

Instead of an AI agent reading 300+ lines of a single `walkthrough.md` (which leads to **Context Decay** and **Attention Drift**), the Palace allows the agent to "walk" through specific domains of knowledge, loading only the high-fidelity summaries needed for the task at hand.

---

## 2. 🏗️ The Architectural Hierarchy

The Palace is structured into five distinct semantic layers:

### i) Wings (The Domains)

Top-level directories representing a major project, person, or entity.

* *Example:* `wing_dsom_core`, `wing_laboratory_cms`.

### ii) Halls (The Memory Corridors)

Standardized memory types shared across every wing to ensure consistency.

* `hall_facts`: Immutable laws, architectural decisions, and "Truths."
* `hall_events`: Chronological milestones and session anchors.
* `hall_preferences`: User persona, linguistic mandates, and stylistic choices.
* `hall_discoveries`: Breakthroughs and new insights.
* `hall_advice`: Past recommendations and solutions.

### iii) Rooms (The Topics)

Specific subjects within a wing.

* *Example:* `room_clean_architecture`, `room_persistence_fabric`.

### iv) Closets (The Summaries)

Each room contains a `closet.md` file. This is the **distilled truth**—a highly optimized Markdown summary that provides the AI with immediate context without reading verbatim logs.

### v) Drawers (The Verbatim Logs)

The original, unedited session logs (e.g., `walkthrough.md`). Closets always link back to specific line numbers in the drawers for deep-dive verification.

---

## 🔄 3. Dual-Mode Persistence (The SSoT)

The Palace operates in tandem with the **Chronological Drawer** (`walkthrough.md`) to provide "the best of both worlds":

| Dimension | Chronological Drawer (`walkthrough.md`) | Spatial Palace (`wings/`) |
| :--- | :--- | :--- |
| **Logic** | Temporal (Time-based) | Semantic (Meaning-based) |
| **Pillar** | **Persistence** (The Record) | **Recall** (The Retrieval) |
| **Storage** | Single flat file | Structured directory tree |
| **Usage** | Archival audit trail | Active session reanimation |

---

## 🚶 4. Operational Workflow: "Walking the Palace"

### i) Reanimation (SOD)

When the AI wakes up, it no longer reads the entire `walkthrough.md`. Instead:

1. Read `palace_registry.md` to identify the relevant **Wings** and **Rooms**.
2. Load the `closet.md` for those specific rooms.
3. Establish a high-resolution **Mental Anchor** in seconds.

### ii) Hibernation (EOD)

During the EOD ritual, the AI must perform a **Dual-Update**:

1. Record the verbatim session anchor in `walkthrough.md`.
2. Refine or update the relevant Palace **Closets** with the session's distilled outputs.
3. Ensure the **Registry** reflects any new rooms created.

---

## 📥 5. The Ingestion Loop (Adding Knowledge)

To ensure the Palace remains a living entity, new knowledge must follow the **Spatial Ingestion Loop**:

1. **Categorisation:** Determine the **Wing** (Domain) and **Hall** (Type).
2. **Closet Update:** Modify the relevant `closet.md`. Use high-density, substance-rich Markdown.
3. **Cross-Linking:** Link the new closet entry to the specific line in the **Chronological Drawer** (`walkthrough.md`) where the discovery happened.
4. **Registry Sync:** If a new Room was created, update `palace_registry.md`.

## 🔄 6. Git Reflection Mandate

**"No code change is complete without its shadow in the Palace."**

To keep the Brain in sync with the repository state:

1. **Post-Commit Reflection:** After every `git commit`, the AI should ask: *"Which Palace Room does this change affect?"*
2. **Semantic Mapping:** The AI must update the corresponding `closet.md` to reflect the new technical state (e.g., version upgrades, new dependencies, logic refactors).
3. **Ledger Alignment:** Ensure `HISTORY.md` and the Palace `hall_events` are reconciled during the EOD ritual.

---

## ⚖️ 7. The Sovereign Laws of the Palace

1. **Plain Markdown Mandate:** The Palace MUST remain in plain, human-legible Markdown. No proprietary encoding or lossy compression (AAAK) is permitted unless explicitly authorized.
2. **Universal Search:** Every closet must link to the original drawer (verbatim log) to prevent AI hallucination.
3. **Git Sovereignty:** Every Palace move (directory creation/closet update) must be versioned and committed.

---
---
*Created by Harisfazillah Jamel | Lead Architect of DSOM | Protocol v6.1 | Inspired by and originated from [milla-jovovich/mempalace](https://github.com/milla-jovovich/mempalace)*


---

## 🚀 8. Evolution to Sovereign Workspace v2 (OKF & Agent Skills)

In DSOM Protocol v6.2+, the Palace evolved from a static markdown tree (`.agent`) to the dynamic **Sovereign Workspace v2** (`.agents`). This upgrade introduced two critical capabilities:

1. **Open Knowledge Format (OKF) v0.1**: All `closet.md` files now require strict YAML frontmatter. This transforms the Palace from human-readable text into a machine-indexable database, allowing AI to instantly parse `type`, `title`, and `description` without reading the body text.
2. **Self-Healing Agent Skills**: Automation scripts (`.sh`, `.ps1`) are fragile. Workspace v2 introduced `.agents/skills/`. Each skill is an OKF-compliant `SKILL.md` file that embeds its own operational logic. If a script is deleted, the AI reads the `SKILL.md` to rebuild it from scratch, achieving true self-recovery.


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-04*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
