---
okf_version: 0.1
type: governance_protocol
title: "🛡️ CRISP² Operational Strategy: The Five Pillars of Persistence"
description: "OKF-compliant documentation for CRISP2-OPERATIONAL-STRATEGY.md."
resource: "file:///docs/governance/CRISP2-OPERATIONAL-STRATEGY.md"
timestamp: 2026-07-04T09:40:04Z
topics: [docs, governance, crisp, operational, strategy]
---
# 🛡️ CRISP² Operational Strategy: The Five Pillars of Persistence

**Author:** Harisfazillah Jamel
**Version:** 1.1.0
**License:** GPLv3
**Status:** Core Framework Documentation

---

## 1. Introduction

The **CRISP² Matrix** (Context-Record-Iteration-Single-Purpose-Pedagogy) is the operational engine of the DSOM protocol. It provides a structured methodology to prevent **Context Decay**—the phenomenon where an AI gradually loses track of complex architectural logic over a long conversation.

---

## 2. The Five Pillars (Generic Tasks)

### 🧩 I. Context Awareness (The Anchor)

Before any code is written, the AI must "synchronise" with the physical state of the repository.

* **The Action:** Reading `.agents/brain/` artifacts (`palace_registry.md`, `task.md`, `walkthrough.md`).
* **The Goal:** Ensure the AI's internal "Mental Anchor" matches the Human Architect's last saved state.
* **Human Check:** Always ask: *"What is our current Mental Anchor?"*

### 📝 II. Review & Record (The Audit Trail)

Logic must be committed to text before it is committed to code.

* **The Action:** Updating `walkthrough.md` and Palace `closet.md` files with the reasoning behind a change.
* **The Goal:** Create a permanent spatial record of *why* a decision was made, which serves as the "memory" for the next AI session.
* **DBP-Standard:** *Rekod dahulu, kod kemudian.*

### 🔄 III. Iteration (Atomic Git Hygiene)

Large, monolithic changes are the primary cause of architectural drift and bugs.

* **The Action:** Modifying only one file at a time; one commit per sub-task.
* **The Goal:** Maintain **High-Availability (HA)** of the codebase. If an error occurs, we can roll back a single "atomic" step.
* **Commit Format:** `type(scope): descriptive message` (e.g., `feat(entities): add validation logic`).

### 🎯 IV. Single-purpose Prompts (The Focus)

AI models perform best when given a narrow, high-depth task rather than a broad, shallow one.

* **The Action:** Focusing the prompt on one specific **Clean Architecture** layer (e.g., just the Entities).
* **The Goal:** Prevent the AI from hallucinating dependencies between unrelated layers.

### 🧠 V. Pedagogical Logic (The 'Why')

Code without explanation is "technical debt" in a multi-agent environment.

* **The Action:** Explaining the security, performance, or structural logic *before* providing the code block.
* **The Goal:** Ensure the Human Architect and the AI are in total agreement on the underlying theory.
* **Malay:** *Terangkan logik (Kenapa) sebelum kod (Apa).*

---

## 3. Hierarchy Mapping (CRISP-DM Integration)

| Level | Description | CRISP² Application |
| :--- | :--- | :--- |
| **L1** | **Phase** | The current stage of the project (e.g., Phase 2: Cognitive Core). |
| **L2** | **Generic Tasks** | The 5 CRISP Pillars described in this document. |
| **L3** | **Specialised Tasks** | Applying CRISP to specific tools (e.g., `reanimate-claude.sh`). |
| **L4** | **Process Instance** | The actual content of `walkthrough.md` for today's session. |

---

## 4. Troubleshooting Context Decay

If the AI begins to repeat errors or ignore the CRISP Strategy:

1. **STOP** the current dialogue.
2. **SYNC** local files to GitHub.
3. **REANIMATE** using the `REANIMATION-PROMPT-TEMPLATE.md`.

---
*Generated for the DSOM Sovereign Environment. UK English & DBP-standard Malay Enforced.*


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-04*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
