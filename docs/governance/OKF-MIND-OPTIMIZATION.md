---
okf_version: 0.1
type: documentation
title: "OKF-Based AI Agent Mind Optimization"
description: "Architectural policy codifying Progressive Disclosure, Artifact Pyramid, and Semantic Routing."
resource: "file:///docs/governance/OKF-MIND-OPTIMIZATION.md"
timestamp: 2026-07-11T22:33:26Z
topics: [docs, governance, okf, mind, optimization]
---
# OKF-Based AI Agent Mind Optimization

This governance document codifies the principles of Cognitive Architecture designed to combat context decay and the "lost-in-the-middle" phenomenon in LLMs, specifically adopting the research from the OKF Agentic Optimization protocols.

## 1. The Epistemological Crisis
Traditional stateless Retrieval-Augmented Generation (RAG) fails at complex knowledge synthesis because nothing accumulates between sessions. The **LLM Wiki Paradigm** resolves this by treating the AI agent as an active, continuous curator of a persistent filesystem, transforming disjointed information into a compounding artifact.

## 2. Progressive Disclosure & Navigational Indexes
The most profound constraint in modern AI engineering is the hard limit on channel capacity. The objective of **Progressive Disclosure** is to deliver the absolute minimum viable semantic information into the context window at precisely the moment it is required.

- **index.md Strategy:** An index.md file must be placed at key directory levels within an OKF bundle. This serves as a localized table of contents. When an autonomous agent explores a knowledge domain, it loads the index.md file first to build a topographical map, traversing deeper only into required files.

## 3. The Artifact Pyramid
To prevent context crowding during complex multi-agent workflows, all OKF bundles must stratify knowledge into an ontological pyramid based on the degree of synthesis:

- **Layer 1 (L1) - Synthesis:** High-level executive summaries, strategic playbooks. (Target: Orchestrator agents).
- **Layer 2 (L2) - Analysis:** Deeply focused, domain-specific investigations (e.g., market mappings). (Target: Worker subagents).
- **Layer 3 (L3) - Raw Dossiers:** Unaltered source transcripts, statistical data, and methodology notes. (Target: Validator agents).

### Zero-Cost Context Prediction (The SOURCES Block)
To achieve zero-cost context prediction, every L1 and L2 concept file **MUST** append a highly structured SOURCES block to the bottom of its Markdown body. Each entry must pair a standard Markdown link with a mandatory, single-line description of the target file's contents (e.g., [market-position.md](file:///path) -> Competitor mapping supporting Section 2). This allows agents to evaluate relevance without executing a filesystem read.

## 4. YAML Metadata Semantic Routing
The YAML frontmatter serves as the exact metadata anchor required for high-dimensional vector semantic routing. Autonomous control planes use the description and `type` fields to calculate cosine similarity against a user's prompt, dynamically routing tasks to specific files, tools, or specialized agents.

Furthermore, this frontmatter is the absolute prerequisite for the **Knowledge-First Discovery Protocol**. By mandating that agents `grep_search` the `topics:` and verify the `timestamp:` metadata, the AI achieves localized contextual awareness without executing expensive and potentially destructive remote terminal commands.

## 5. The Codification of Procedural Memory
Procedural memory dictates *how* an agent should behave. Within the DSOM framework, this is codified in the AGENTS.md and SKILL.md specifications using the following strict constraints:

- **Command-First Architecture:** Agents do not benefit from human-readable prose like "be careful". Instructions must map to exact, executable terminal invocations.
- **Byte-Capped Executions:** To maintain strict context discipline, exploratory operations must be capped (e.g., executing COMMAND 2>&1 | head -c 4000) to prevent log floods.
- **Closure Definitions:** Explicitly define the exact parameters of task completion (e.g., zero linter warnings, successful test suites).
- **Escalation and Defensive Constraints:** Explicit "ask-first" permission boundaries and absolute prohibitions against destructive actions must be defined to prevent dangerous AI improvisations.


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-11*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
