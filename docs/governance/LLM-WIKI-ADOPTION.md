---
okf_version: 0.1
type: documentation
title: "DSOM vs. LLM WIKI: Comparative Analysis & Adoption Strategy"
description: "Analysis of Andrej Karpathy's LLM WIKI concept and how it maps to DSOM architecture."
timestamp: 2026-08-07T05:39:28Z
topics: [docs, governance, llm, wiki, adoption]
---
# 🧠 DSOM vs. LLM WIKI: Comparative Analysis & Adoption Strategy

> **Entry Point 8:** This document serves as the Sovereign Knowledge Entry Point. See [START-HERE.md](../../START-HERE.md) for the master onboarding roadmap.

> *In honor of Andrej Karpathy's "LLM WIKI" vision, which inspired the formalization of the Ingest, Query, and Lint protocols within the DSOM framework.*

Andrej Karpathy's "LLM WIKI" Gist describes a paradigm shift from standard Retrieval-Augmented Generation (RAG) to a persistent, LLM-maintained knowledge base. Instead of parsing raw documents on every query, the LLM continuously reads, synthesizes, and cross-references information into a structured Markdown wiki. The human is the curator and the LLM is the tireless maintainer.

Remarkably, **our Deep State of Mind (DSOM) framework already implements the vast majority of this vision.** Let's break down the parallels, list our current implementations, and define how we can adopt Karpathy's remaining concepts to elevate our intelligence architecture.

---

## 1. What We Already Do (The DSOM Baseline)

Karpathy defines three architectural layers and several core operations. Here is how our existing DSOM protocols map perfectly to his concepts:

| LLM WIKI Concept | DSOM Implementation | Status |
| :--- | :--- | :--- |
| **The Schema** (Instructions on how the LLM should maintain the wiki) | `AGENTS.md` (The Sovereign Constitution) and `SKILL.md` files that dictate exact behavioral guardrails and operational workflows. | **Implemented** (Highly Mature) |
| **The Wiki** (LLM-maintained Markdown files) | The **Sovereign Markdown Palace** (`docs/governance/`, `hall_facts`, etc.). | **Implemented** |
| **`index.md`** (Content catalog for navigation) | `palace_registry.md` (Our spatial memory map). | **Implemented** |
| **`log.md`** (Chronological tracking) | `HISTORY.md` (Universal Ledger) and `CHANGELOG.md`. | **Implemented** |
| **Chronological State Logging** | `checkpoint_summary.txt`, `task.md`, and `walkthrough.md`. | **Implemented** |

*In many ways, DSOM is a highly advanced, production-grade implementation of the LLM WIKI concept, specifically tuned for software engineering and DevOps infrastructure.*

---

## 2. How LLM WIKI Can Make Us Better (The Gaps)

While we have the structure, the LLM WIKI highlights three operational verbs that we can adopt to make the DSOM Palace self-sustaining and compounding: **Ingest**, **Query**, and **Lint**.

### A. The "Ingest" Protocol (Automated Synthesis)
**The Concept:** When a new raw source (e.g., an article, a NOSS PDF, a meeting transcript) is introduced, the AI shouldn't just read it. It should extract the data, summarize it, create new concept pages, and link it into the existing Wiki.
**Our Adoption:** We need a strict separation between `raw_sources/` and the synthesized Palace. We should create a **`dsom-knowledge-ingester` skill**. When you drop a PDF into `raw_sources/`, I execute this skill to:
1. Extract the text.
2. Synthesize a Markdown page in the appropriate Palace Hall.
3. Update `palace_registry.md`.
4. Update `SUMMARY.md` and `mkdocs.yml` (using our new Rule 14).

### B. The "Lint" Ritual (Automated Health Checks)
**The Concept:** The AI periodically health-checks the Wiki for contradictions, orphan pages, and stale claims.
**Our Adoption:** As the Sovereign Markdown Palace grows, context can rot. We should introduce a **`palace-auditor` skill** (a monthly or ad-hoc ritual) where I crawl `docs/` and `.agents/` to:
1. Find broken Markdown links.
2. Flag rules in `AGENTS.md` that contradict each other.
3. Identify "orphan" documents that are not listed in `palace_registry.md` or `SUMMARY.md`.

### C. The "Query" Loop (Compounding Answers)
**The Concept:** When the LLM generates a really good answer, comparison, or analysis, it shouldn't disappear into the chat history. It should be filed back into the Wiki.
**Our Adoption:** We currently do this manually when I create an "Artifact" and we save it. We should establish a core behavioral rule: **"Any time a complex architectural analysis or troubleshooting guide is generated in chat, the AI must proactively propose saving it as a persistent `.md` document in the Palace."**

---

## 3. Proposed Next Steps for Adoption

If you agree with this analysis, we can immediately begin adopting the missing LLM WIKI concepts into DSOM by:

1. **Creating the `palace-auditor` skill:** To allow me to "Lint" our knowledge base.
2. **Creating the `dsom-knowledge-ingester` skill:** To handle the "Ingest" phase for external documents (like your NOSS integration).
3. **Adding a "Knowledge Compounding" rule to `AGENTS.md`:** Instructing me to permanently save valuable chat analyses into the Palace.

---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-16*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
