---
okf_version: 0.1
type: documentation
title: "NOSS Integration Guide: Adopting National Standards into DSOM"
description: "Governance protocol explaining how National Occupational Skills Standards (NOSS) are translated and adopted as modular AI skills under the master DSOM framework."
resource: "file:///docs/governance/NOSS-INTEGRATION-GUIDE.md"
timestamp: 2026-07-12T08:45:00Z
topics: [docs, governance, noss, integration, integrating]
---
# Integrating National Standards (NOSS) into the DSOM Baseline

This document serves as the operational guide for translating the National Occupational Skills Standard (NOSS) into modular, executable AI skills. It sits alongside the primary `AI-SKILL-ARCHITECTURE.md` to demonstrate how external compliance frameworks are systematically ingested by our AI agents.

## 1. The Hierarchy of Governance

It is critical to establish the architectural hierarchy: **Deep State of Mind (DSOM) is the Sovereign Engine and Master Baseline.**

DSOM natively governs the workspace through a multi-protocol ecosystem. The AI agents within this ecosystem are bound by several strict standards simultaneously:
*   **Open Knowledge Format (OKF v0.1):** For metadata routing and documentation structures.
*   **Generative Engine Optimization (GEO):** For deterministic, machine-readable output generation.
*   **Defensive GitOps / Defense-in-Depth:** For operational security and infrastructure isolation.
*   **POSIX / Termux Compatibility:** For cross-platform script execution.
*   **Sovereign Linguistics:** Strict adherence to UK English and standard Bahasa Melayu Malaysia (Piawai).

**NOSS is treated as a Compliance Payload.**
NOSS does not govern the system; rather, the DSOM engine *executes* NOSS standards as modular skills to achieve national compliance. Whether it is a Level 2, Level 3, or Diploma-level standard, it is processed simply as another set of standard operating procedures (SOPs) within the `.agents/skills/` directory.

---

## 2. Token Optimization: Digesting Original NOSS PDFs

> [!WARNING]
> **Token Constraints and PDF Ingestion**
> Human operators must exercise caution when feeding raw, original NOSS PDF documents to the AI. These documents are often hundreds of pages long and will rapidly consume the agent's context window (token limit), leading to memory fragmentation or complete operational failure.

**Best Practice for Human Operators:**
Do not dump entire NOSS PDFs into the AI workspace. Instead:
1. Extract only the specific **Competency Unit (CU)** and its relevant **Work Activities (WA)** that you intend to build a skill for.
2. Convert that targeted extraction into a lightweight Markdown file.
3. Provide the AI with this condensed, text-based artifact to begin the translation process.

---

## 3. The NOSS-to-Skill Translation Mechanics

Once the human has provided a targeted NOSS snippet, the AI translates the standard from passive, theoretical classroom learning into active, executable, industrial-grade AI Skills.

### A. Semantic Routing (YAML Tagging)
Every NOSS skill must declare its specific compliance targets within the OKF frontmatter of its `SKILL.md` file. This allows the DSOM Semantic Router to trigger the exact NOSS standard when requested.

```yaml
---
name: "linux-c01-w01"
description: "Executes OS-level security hardening according to national standards."
compliance_framework: "NOSS"
noss_level: 3
noss_competency_unit: "IT-020-3:2026-CU01-WA01"
---
```

### B. Competency Units (CU) & Work Activities (WA) Mapping
The logical structure of NOSS maps directly to our Skill Architecture:
*   **Work Activities** become the literal execution steps (the bash scripts or Ansible playbooks the AI must run).
*   **Performance Criteria** become the strict **Closure Definitions** (DSOM Rule 10). The AI must programmatically verify the criteria (e.g., verifying a port is closed, or checking a configuration flag) via verification scripts before marking the NOSS task as complete.
*   **Underpinning Knowledge** is stripped out of the execution skill to save token overhead (Progressive Disclosure) and stored separately in `docs/references/noss/`.

---

## 4. Authoring Guidelines for NOSS Skills

To ensure NOSS skills align with the DSOM vision of a Senior ICT Consultant operating highly available environments, authors must enforce the following rules:

### A. Eradicating Passive Tasks
NOSS standards written for humans often include passive instructions like "Understand network topologies." AI skills cannot be passive. Every NOSS skill must produce a tangible artifact, an executed log, a system modification, or a telemetry dashboard. Actionable execution is mandatory.

### B. Poka-Yoke & Automation
Abstract complex operations into foolproof scripts. Rather than instructing the AI to manually type out fifty lines of configuration to fulfill a NOSS criteria, wrap that configuration logic into an idempotent `playbook.yml` or `.sh` script stored inside the skill's `scripts/` folder. The AI simply executes the wrapper.

### C. The DSOM Gateway
Before a NOSS skill is merged into `.agents/skills/`, it must pass the DSOM industrial validation:
1. Does it operate via the `uv` Python isolated environment?
2. Does the skill respect the spatial memory boundaries (`.agents/brain`)?
3. Does it utilize the Universal Sovereign Signature injector?

By treating NOSS as a structured payload, DSOM seamlessly ingests national standards while maintaining absolute sovereign control over the execution architecture.

---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-12*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
