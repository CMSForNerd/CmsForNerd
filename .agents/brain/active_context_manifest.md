---
okf_version: 0.1
type: active_context_manifest
title: "Active Context Manifest — Template"
description: "OKF-compliant manifest declaring the exact file paths the agent must load for the current task session. Replace symlinks with this pattern for cross-platform portability."
timestamp: 2026-07-19T11:12:00+08:00
topics: [brain, active, context, manifest, always]
---
# Active Context Manifest

> **Usage:** Update this file at the start of each session (SOD ritual).
> The agent reads this manifest first, then loads only the listed files into context.
> This replaces filesystem symlinks, which are not portable across Windows/WSL2/Termux.

## Core Brain (Always Load)
- `.agents/AGENTS.md`
- `.agents/brain/task.md`
- `.agents/brain/walkthrough.md`
- `.agents/brain/palace_registry.md`

## Active Task Files (Update per session)
- `.agents/brain/wings/wing_dsom_core/hall_facts/room_tooling/closet.md`

## Governance (Load on demand — verify token count first)
- `docs/governance/AI-MASTER-PROTOCOL.md`
- `docs/governance/PYTHON-UV-ENVIRONMENT-GUIDE.md`
- `docs/governance/BYTE-CAPPED-EXECUTION-FRAMEWORK.md`

## Excluded (Archival — never load directly)
# The following files exceed 30,000 tokens each. Access via view_file with line ranges only.
# - .agents/brain/palace_update_proposal_2026-07-17_0713.md  (55,726 tokens)
# - .agents/brain/palace_update_proposal_2026-04-08_2154.md  (34,534 tokens)
# - .agents/brain/palace_update_proposal_2026-04-08_1214.md  (30,206 tokens)
