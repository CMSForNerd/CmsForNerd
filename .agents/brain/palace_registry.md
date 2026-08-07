---
okf_version: 0.1
type: architecture_concept
title: "🏛️ Palace Registry: Sovereign Retrieval Map"
description: "OKF-compliant documentation for palace_registry.md."
resource: "file:///.agents/brain/palace_registry.md"
timestamp: 2026-07-04T09:40:04Z
topics: [brain, palace, registry, sovereign, retrieval]
---
# 🏛️ Palace Registry: Sovereign Retrieval Map

This registry serves as the index (Open Knowledge Format (OKF) v0.1 compliant) for the AI to "walk" the **Sovereign Markdown Palace**. It defines the wings, halls, and rooms that provide specialized context retrieval beyond the chronological walkthrough. For full documentation on the architecture and rituals, see the [Sovereign Markdown Palace Protocol](file:///d:/Users/LinuxMalaysia/Projects/deep-state-of-mind-for-my-ai/docs/DIGITAL-SOVEREIGNTY-OPERATIONAL-MODEL-PALACE.md).

---

## 🏗️ Wings & Halls

### 1. `wing_dsom_core`
**The architectural and philosophical engine of the framework.**

| Hall | Content Type | Status |
| :--- | :--- | :--- |
| `hall_facts` | Core Laws, Clean Architecture, CRISP Mandate, Tooling | ACTIVE |
| `hall_events` | Major Phase Milestones, EOD Manifests, Ledger, Brain | ACTIVE |
| `hall_preferences` | Architect Persona, Linguistic Rules | STANDBY |
| `hall_discoveries` | Breakthroughs, uncategorised commits | ACTIVE |

---

## 🚪 Active Rooms

### Wing: `wing_dsom_core` → `hall_facts`

- **Room: `room_clean_architecture`**
  *Path:* `hall_facts/room_clean_architecture/closet.md`
  *Context:* The concentric skeleton of the project (Inward Dependency Rule).

- **Room: `room_crisp_strategy`**
  *Path:* `hall_facts/room_crisp_strategy/closet.md`
  *Context:* The operational filter for all AI interaction (Context/Review/Iteration/Single/Partnership).

- **Room: `room_dsom_protocol`**
  *Path:* `hall_facts/room_dsom_protocol/closet.md`
  *Context:* Core protocol docs — SOD/EOD rituals, RITUAL-OF-TRANSITION, AI-MASTER-PROTOCOL laws, README/SUMMARY evolution.

- **Room: `room_tooling`** *(NEW — Backfill 2026-04-08)*
  *Path:* `hall_facts/room_tooling/closet.md`
  *Context:* Full toolchain evolution from `reanimate.sh` v1.4 (2026-01-08) to `palace-sync` v1.0 (2026-04-08).

- **Room: `room_jules_knowledge`** *(NEW — Added 2026-07-31)*
  *Path:* `hall_facts/room_jules_knowledge/closet.md`
  *Context:* Comprehensive repository rules, operational memories, and DevOps guidelines for the Jules Cognitive Twin.

---

### Wing: `wing_dsom_core` → `hall_events`

- **Room: `room_sovereign_fabric_v9_8`**
  *Path:* `hall_events/room_sovereign_fabric_v9_8/closet.md`
  *Context:* 19-node Elasticsearch/Kibana fabric + Ansible infrastructure (v6.2.x roles/playbooks).

- **Room: `room_brain_artifacts`** *(NEW — Backfill 2026-04-08)*
  *Path:* `hall_events/room_brain_artifacts/closet.md`
  *Context:* Evolution of `.agents/brain/` — task.md, walkthrough.md, Palace structure, hibernation notes.

- **Room: `room_ledger`** *(NEW — Backfill 2026-04-08)*
  *Path:* `hall_events/room_ledger/closet.md`
  *Context:* CHANGELOG.md + HISTORY.md version milestones from v1.0.0 (2025-09-16) to Palace v1.0 (2026-04-08).

---

### Wing: `wing_dsom_core` → `hall_discoveries`

- **Room: `room_uncategorised`** *(NEW — Backfill 2026-04-08)*
  *Path:* `hall_discoveries/room_uncategorised/closet.md`
  *Context:* PDF sovereign books, GitBook/MkDocs infrastructure, .github governance, ansible.cfg fixes.

---

## 📜 Retrieval Protocol: "Walking the Palace"

1. **Initialise:** AI reads `palace_registry.md` at session start.
2. **Scout:** Identify relevant Wings and Rooms for the current task.
3. **Query:** Load the `closet.md` (summary) for quick context.
4. **Drill Down:** If precision is needed, follow links to the **Chronological Drawer** (`walkthrough.md`).

### Quick-Reference: Task → Room Mapping

| If working on... | Walk to... |
| :--- | :--- |
| Governance & Protocol laws | `hall_facts/room_dsom_protocol` |
| Shell scripts & tools | `hall_facts/room_tooling` |
| Architecture decisions | `hall_facts/room_clean_architecture` |
| AI interaction style | `hall_facts/room_crisp_strategy` |
| Elasticsearch/Ansible infra | `hall_events/room_sovereign_fabric_v9_8` |
| Brain files (task/walkthrough) | `hall_events/room_brain_artifacts` |
| Version history | `hall_events/room_ledger` |
| PDF/GitBook/misc | `hall_discoveries/room_uncategorised` |
| Repository rules & memories | `hall_facts/room_jules_knowledge` |

---
*Last Full Audit: 2026-04-08 | Last Registration: 2026-07-31 (room_jules_knowledge) | Backfill Complete | Protocol Version: Palace v1.0 | Rooms: 9*
*Last Human Audit: 2026-04-08 | Latest Room Registration: 2026-07-31 (room_jules_knowledge) | Backfill Complete | Protocol Version: Palace v1.0 | Rooms: 9*


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-31*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
