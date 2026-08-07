---
okf_version: 0.1
type: documentation
title: "DSOM Efficiency Protocols"
description: "A comprehensive breakdown of how DSOM achieves perfect memory retention with maximum token efficiency via RAG-style mechanisms, Progressive Disclosure, and strict persona constraints."
timestamp: 2026-08-07T05:39:28Z
topics: [docs, governance, dsom, efficiency, protocols]
---
# 🧠 DSOM: The Engine of Persistent Memory & Efficiency

> **Published Article:** [DSOM: Engine of Persistent Memory](https://www.linuxmalaysia.com/2026/07/dsom-engine-of-persistent-memory.html)

The Deep State of Mind (DSOM) framework is fundamentally designed to solve the two biggest constraints of working with AI: **Context Window Amnesia** (forgetting past decisions) and **Token Bloat** (wasting API costs and processing time on irrelevant data).

Here is a comprehensive list of all the protocols, rules, and architectural patterns we have implemented to ensure the AI acts as a Cognitive Twin—retaining a perfect "RAG-style" memory while remaining incredibly token-efficient.

---

## 1. The RAG-Style Memory Architecture
Instead of relying on the ephemeral chat history (which gets truncated or becomes too expensive), we externalized the AI's memory into the file system.

* **Zero-Global Spatial Memory (Rule 1):** The AI's memory lives strictly in `.agents/brain`. To remember something, the AI must explicitly synchronize its context using the `palace_registry.md`.
* **The Sovereign Markdown Palace:** Knowledge is physically mapped into "Wings," "Halls," and "Rooms." When the AI needs context about a specific topic (e.g., Ansible), it doesn't read the whole project. It reads the registry, walks to that specific room, and retrieves just that specific `closet.md` file.
* **Knowledge Compounding (Rule 15 - The LLM WIKI Mandate):** If we solve a complex problem or analyze a new architecture in our chat, I am explicitly mandated to extract that knowledge from the chat and save it as a permanent `.md` file in the Palace. This ensures we *never* have to solve the same problem twice.

## 2. Token-Optimized Knowledge Retrieval (Progressive Disclosure)
We implemented mechanisms to ensure that the AI only loads the exact bytes of data it needs, at the exact moment it needs them.

* **Semantic Skill Routing (Rule 12):** AI Agent skills (`SKILL.md`) are discovered *exclusively* via their OKF YAML Frontmatter (`name` and `description`). The AI initially loads only this lightweight metadata. It fetches the full 300-line `SKILL.md` payload *only* at the exact moment of execution. This prevents massive token bloat.
* **The Artifact Pyramid (Rule 9):** Knowledge is stratified conceptually into L1 (Synthesis), L2 (Analysis), and L3 (Raw). L1/L2 documents contain a `SOURCES` block pairing Markdown links with single-line semantic descriptions. The AI can predict if a file is relevant just by reading a single line, saving thousands of tokens of unnecessary reading.
* **Local Knowledge-First Discovery (Rule 20 & 21):** Before issuing exploratory terminal commands (which risk context-flooding via huge output logs), the AI must leverage `grep_search` on local OKF frontmatter (`topics:`). This mechanism prevents wasting thousands of tokens on blind terminal probes when the answer is already documented in the Palace.

## 3. Operational Token Efficiency Limits
We codified strict constraints on how the AI interacts with the terminal and generates code to prevent accidental context flooding.

* **Byte-Capped Terminal Executions (Rule 10):** All exploratory terminal operations must be capped (e.g., `COMMAND 2>&1 | head -c 4000`). If I run a command that spits out 50,000 lines of logs, it would instantly wipe out our chat memory. This rule prevents that.
* **Differential Execution (Persona Constraint):** When modifying configurations or scripts, I am instructed to provide only relevant `git-style diffs` or specific line changes instead of vomiting the entire 500-line file back into the chat window.
* **Omission of Conversational Fluff:** The Cognitive Twin Persona explicitly instructs me to omit filler ("Sure, here is your script...") and jump straight to the technical blocks.

## 4. Skills Designed for Knowledge Ingestion
We built specific skills to automate the process of absorbing new knowledge into this RAG-style system.

* **`dsom-knowledge-ingester`:** A skill dedicated entirely to taking raw external documents (PDFs, foreign markdown, web articles) and systematically synthesizing them into the Sovereign Markdown Palace using DSOM GEO standards.
* **`palace-auditor`:** A "Linting" skill that acts as the tireless maintainer of the Palace. It scans for "Orphan Documents" (files that were saved but not indexed) and contradictions in our rules, ensuring our memory never becomes corrupted or disjointed.

## 5. Continuity Rituals (Across Sessions)
To prevent the AI from starting "blank" every day, we built rituals to compress and transfer state.

* **Start of Day (SOD) & End of Day (EOD) Manifests:** We use the `sod-palace.ps1` and `HOWTO-REANIMATE.md` protocols to compress the entire state of our project into a single, highly-dense handover manifest. Instead of feeding the AI thousands of tokens of past conversation, we feed it a 500-word manifest that instantly aligns it with the exact state of the project.
* **The Triple-Ledger Synchronization (Rule 8):** Every major action is synchronously updated across `README.md` (links), `CHANGELOG.md` (versions), and `HISTORY.md` (universal ledger). The AI can instantly grasp the historical trajectory of the project by reading one file.

---

### 🎯 The Ultimate Result
By combining **Progressive Disclosure**, **External File-System Memory (The Palace)**, and **Strict Persona Constraints**, DSOM allows us to build a project of infinite complexity. We only pay the token cost for the *exact slice* of knowledge we are actively working on, while retaining perfect, permanent recall of everything we have ever discussed.

---

### 🔗 Open Source Repositories & Documentation
Explore the full Deep State of Mind (DSOM) framework and Sovereign Palace architecture at:
- **GitHub:** [linuxmalaysia/deep-state-of-mind-for-my-ai](https://github.com/linuxmalaysia/deep-state-of-mind-for-my-ai)
- **GitLab:** [linuxmalaysia/deep-state-of-mind-for-my-ai](https://gitlab.com/linuxmalaysia/deep-state-of-mind-for-my-ai)
- **GitBook:** [DSOM Protocol Documentation](https://malaysia-open-source-community.gitbook.io/deep-state-of-mind-dsom-protocol-for-my-ai)

---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-16*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
