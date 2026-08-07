---
okf_version: 0.1
type: documentation
title: "Open Knowledge Format (OKF) Adoption Guide"
description: "OKF-compliant documentation for OKF-ADOPTION-GUIDE.md."
resource: "file:///docs/OKF-ADOPTION-GUIDE.md"
timestamp: 2026-07-04T09:40:04Z
topics: [docs, okf, adoption, open, knowledge]
---
# Open Knowledge Format (OKF) Adoption Guide

## What is the Open Knowledge Format (OKF)?
Introduced by Google Cloud on June 12, 2026, the **Open Knowledge Format (OKF)** is a vendor-neutral, open specification designed to solve the "context problem" in AI-driven development.

Historically, AI agents struggle because organizational knowledge (runbooks, architecture decisions, API schemas) is scattered across disparate wikis, databases, and code comments. OKF creates a standardized "common language" for knowledge by structuring it so that it is equally readable by human engineers and autonomous AI agents.

### Core Structure of OKF v0.1
OKF does not require proprietary vector databases or complex SDKs. It relies on standard, Git-native technologies:
1.  **Knowledge Bundles:** A self-contained, hierarchical collection of knowledge documents. Our `.agents/brain` and `.agents/skills` directories act as knowledge bundles.
2.  **Concepts (Markdown Files):** A single unit of knowledge within a bundle, represented as one markdown document (e.g., our `closet.md` and `SKILL.md` files).
3.  **Concept IDs:** The logical path of the concept's file within the bundle (without the `.md` suffix).
4.  **YAML Frontmatter:** Every Markdown concept must begin with structured YAML metadata (e.g., `type`, `title`, `description`). This allows the files to be programmatically queried and cataloged by AI agents before they read the entire file.
5.  **Directory-Based Organization:** Files are grouped logically in directories and committed directly alongside the codebase they describe.

---

## How OKF Aligns with the Sovereign AI Workspace (DSOM)

The incredible realization is that our existing **Deep State of Mind (DSOM)** framework and the **Spatial Memory Palace** (`.agents/brain/` and `.agents/skills/`) are already a near-perfect implementation of the Open Knowledge Format.

By utilizing GitOps to manage our AI's memory, we are already achieving the primary goal of OKF: keeping documentation right next to the code, versioned via Git, and accessible to the AI.

However, to formally adopt the OKF v0.1 specification and achieve maximum compliance, we must introduce strict **YAML Frontmatter** across our Memory Palace.

### 1. Adopting OKF in Agent Skills (`.agents/skills/`)
Our `SKILL.md` files already utilize a form of frontmatter to describe the tool to the AI. To be fully OKF-compliant, we will standardize the YAML schema for all skills:

```yaml
---
okf_version: 0.1
type: agent_skill
title: audit-cluster-health
description: Executes the Sovereign Pulse Audit script to extract deep telemetry.
resource: https://github.com/linuxmalaysia/deep-state-of-mind-for-my-ai
tags: [infrastructure, telemetry, health-check]
---
# Skill Instructions Below...
```

### 2. Adopting OKF in the Memory Palace (`.agents/brain/wings/`)
Currently, our `closet.md` files are plain markdown. Moving forward, every distilled knowledge file in the Spatial Memory Palace must include OKF frontmatter to allow the AI to rapidly index the domain before reading it.

```yaml
---
okf_version: 0.1
type: architecture_concept
title: Kafka Ingestion Backbone
description: The rootless Podman deployment architecture for Nodes 20 and 21.
resource: file:///docs/architecture/kafka-nodes.md
timestamp: 2026-06-19T14:00:00Z
---
# Architectural Distillation Below...
```

### 3. Reserved Filenames
The official OKF specification explicitly reserves two filenames at any level of the bundle hierarchy:
* `index.md`: Used for directory listings and progressive disclosure.
* `log.md`: Used for the chronological history of updates.

Our Memory Palace architecture currently utilizes `closet.md` and `SKILL.md` for concept files, so we naturally avoid conflict with these reserved names, but they must remain reserved moving forward.

### 4. Updating the Palace Registry (`palace_registry.md`)
With OKF frontmatter added to all closets and skills, the `palace_registry.md` can eventually be automatically generated. An AI agent or a simple bash script can crawl the `.agents/` directory, parse the YAML frontmatter of every file, and dynamically compile the master map of the Sovereign Workspace.

## Conclusion
The introduction of the Open Knowledge Format by Google Cloud validates the core thesis of the Sovereign AI Workspace. By enforcing YAML frontmatter across all our Markdown-based memory assets, we transition from an ad-hoc AI memory structure into an enterprise-grade, OKF-compliant knowledge graph.


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-04*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
