---
okf_version: 0.1
type: governance_protocol
title: "Generative Engine Optimization (GEO)"
description: "OKF-compliant documentation for GENERATIVE-ENGINE-OPTIMIZATION.md."
resource: "file:///docs/governance/GENERATIVE-ENGINE-OPTIMIZATION.md"
timestamp: 2026-08-07T05:39:28Z
topics: [docs, governance, generative, engine, optimization]
---
﻿---
okf_version: 0.1
type: documentation
title: "Generative Engine Optimization (GEO)"
description: "Architectural policy for ensuring documentation is machine-readable and highly cited by AI Answer Engines."
resource: "file:///docs/governance/GENERATIVE-ENGINE-OPTIMIZATION.md"
timestamp: 2026-07-11T23:02:18Z
---
# Generative Engine Optimization (GEO)

This governance document establishes the standard for architecting all documentation within the DSOM framework to be natively "AI-ready." The digital landscape has shifted from traditional deterministic search (SEO) to probabilistic information synthesis (GEO/AEO).

## 1. The GEO Paradigm
Answer engines (e.g., ChatGPT, Perplexity, Claude, Google AI Overviews) synthesize contextualized answers directly. To ensure our documentation is extracted, cited, and summarized accurately, we must optimize for **machine readability, source attribution, and direct answerability**.

## 2. Empirically Validated GEO Strategies
All human contributors and AI agents must actively employ the following content strategies when drafting documentation:
- **Quotation Addition (+41% lift):** Provide discrete, attributable human expertise.
- **Statistics Addition (+31-38% lift):** Replace vague qualitative prose with specific, verifiable data points.
- **Cite Sources (+35% lift):** Inject inline references to credible third parties.
- **Fluency Optimization (+26% lift):** Improve stylistic clarity to make text mechanically easier for the LLM to parse.
- **Authoritative Tone (+21% lift):** Remove hedging and uncertain phrasing, which models interpret as low-confidence data.

*Note: Keyword stuffing actively harms visibility by triggering AI trust-layer penalties (-8% to -12% drop).*

## 3. Content Creation & Hierarchy for AI Ingestion
- **Atomic Intent:** Documentation must consist of atomic pages with a singular, clear intent.
- **Context Window Chunking:** Keep sections concise (max 200–400 words) to prevent the model from truncating vital information.
- **Semantic Hierarchy:** Formulate H2 subheadings directly as common user questions (e.g., "How to rotate an API key").
- **Colocation of Concepts:** Place examples (code snippets, inputs/outputs) in immediate proximity to the theoretical explanation to reduce cognitive load.

## 4. Machine Readability & The llms.txt Standard
LLMs consume raw text. Parsing HTML and complex DOM structures wastes computational resources. DSOM strictly enforces **Markdown** as the native language of knowledge representation.
To guide AI crawlers, the repository must maintain an llms.txt specification at the root directory. This plain-text file acts as an XML sitemap for machine intelligence, providing a curated, high-signal map of our content.


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-11*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
