---
okf_version: 0.1
type: documentation
title: "AI Skill Architecture: Progressive Disclosure & Semantic Routing"
description: "Governance policy defining how AI agents discover, trigger, and execute modular skills as operational manuals, utilizing token optimization."
resource: "file:///docs/governance/AI-SKILL-ARCHITECTURE.md"
timestamp: 2026-07-11T23:28:03Z
topics: [docs, governance, skill, architecture, use]
---
# AI Skill Architecture

> **Entry Point 7:** This document is part of the Procedural Skill Entry Point. See [START-HERE.md](../../START-HERE.md) for the master onboarding roadmap.

This document governs the operational paradigm for AI Agent Skills within the Deep State of Mind (DSOM) framework. Skills are not merely arbitrary scripts; they are formalized components of the AI's cognitive architecture, designed to optimize context windows and guarantee execution precision.

## How Do I Use These Skills?

They act as **executable operational manuals (SOPs)**. If the AI is asked to configure a server or audit a document, it does not guess what to do. Instead, the AI looks up the specific SKILL.md file for that task and strictly follows the execution steps and quality gates defined inside it. This explicit procedural memory guarantees that all executions meet our enterprise standards.

## How Are Skills Triggered?

Skills are automatically discovered through **OKF YAML Frontmatter**. When a user provides a prompt, the system scans the lightweight
ame and description fields of all available skills. If the framework detects a semantic match between the user's request and a skill's description, that specific skill is formally "triggered" and routed into the AI's active memory for execution.

## Does It Help Save Token Usage?

Yes, absolutely! This architecture utilizes a core OKF principle called **Progressive Disclosure**.
If the AI were to load the full text of all available skills into its context window at once, it would cost hundreds of thousands of tokens, run very slowly, and cause severe context bloat (the lost-in-the-middle phenomenon).
Instead, the system only loads the lightweight index metadata initially. The AI only fetches the heavy, full markdown file of a specific skill at the exact moment it is triggered to execute it. This technique **compresses token overhead by up to 91%**, keeping the Sovereign Engine fast, cheap, and hyper-focused!

## NOSS and Compliance Integration

While this document governs the overarching architecture of DSOM skills, instructions on how to translate specific external compliance standards (such as the National Occupational Skills Standard) into these executable skills can be found in the [NOSS Integration Guide](file:///docs/governance/NOSS-INTEGRATION-GUIDE.md).

## DSOM Project Skills Registry

The following 18 skills are currently registered in this project's local workspace (.agents/skills/):

1. **cross-platform-translator**
2. **dsom-bootstrap**
3. **dsom-policy-adopter**
4. **dsom-project-cloner**
5. **eod-palace-sync**
6. **forensic-log-audit**
7. **initialize-gitops**
8. **latex-proposal-compiler**
9. **node-proposal-formatter**
10. **node-slide-generator**
11. **odp-slide-generator**
12. **okf-frontmatter-injector**
13. **palace-auditor**
14. **pdf-text-extractor**
15. **persona-injector**
16. **proposal-docx-formatter**
17. **sod-palace-sync**
18. **ssh-passwordless-setup**


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-11*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
