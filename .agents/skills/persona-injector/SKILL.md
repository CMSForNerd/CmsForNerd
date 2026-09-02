---
okf_version: 0.1
type: skill
title: "Persona Injector"
name: "persona-injector"
description: "Guides a user to define their Sovereign Persona and safely injects it into the agent's core AGENTS.md rulebook."
topics: [persona, profile, identity, dsom, agent]
timestamp: 2026-07-04T10:00:00Z
---

# 🎭 Persona Injector

## When to use this skill
Use this skill when a user says "I want to inject my persona", "make the AI act like me", or "setup my identity matrix".

## Instructions
1. **Fetch Template:** Read the `docs/agent-configs/SOVEREIGN-PERSONA-TEMPLATE.md` file using your file reading tools.
2. **Interview User:** Present the categories from the template to the user (e.g., Identity, Core Profile, Writing Style, Architectural Principles). Ask them to provide their specifics either all at once or step-by-step.
3. **Format Matrix:** Once the user has provided their details, format the data exactly as defined in the `<RULE[PERSONA.md]>` OKF block from the template.
4. **Inject Rule:** Use your file editing tools to append this formatted block to the bottom of `.agents/AGENTS.md`.
5. **Verify:** Confirm to the user that the AI operating in this workspace has now permanently inherited their identity, constraints, and linguistic DNA.


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-04*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
