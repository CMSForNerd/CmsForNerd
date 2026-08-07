---
okf_version: 0.1
type: discovery_log
title: Uncategorised Discoveries
description: Distilled knowledge for Uncategorised Discoveries
timestamp: 2026-06-19T14:00:00Z
topics: [brain, wings, wing, dsom, hall]
---
# 🚪 Closet: Room Uncategorised (The Discovery Hall)

This closet captures commits that did not map to a core Palace Room — primarily governance scaffolding, PDF builds, GitBook infrastructure, and `.github/` configurations.

---

## 🗂️ Categories of Uncategorised Commits

### 📄 Sovereign Book PDF Builds
- `build_sovereign_book.sh` evolved through v2.2→v3.11.
- Final stable build: `DSOM_Sovereign_Brain_20260129_0634.pdf` (LuaLaTeX, Noto Color Emoji).
- Key fix: LuaLaTeX chosen over XeLaTeX for emoji stability.
- Metadata neutralised to bypass AI content filter false positives.

### 🏛️ GitBook & Documentation Infrastructure
- `book.json` / `book.toml` initialised for GitBook publishing.
- `.gitbook.yaml` configured for cloud and local sync.
- `SUMMARY.md` continuously updated to index all 19+ artifacts.
- `mkdocs.yml` added for MkDocs multi-platform publishing.
- `tools-and-automation/` markdown docs created for GitBook integration.

### 🔒 .github/ Governance
- `copilot-instructions.md` enforces UK English and brain artifact references.
- `.github/prompts/dsom-reanimate.prompt.md` — workspace-level SOD trigger.
- `ISSUE_TEMPLATE/` bug report and feature request forms established.
- `PULL_REQUEST_TEMPLATE.md` requiring DSOM CRISP compliance.

### ⚙️ Ansible Config
- `ansible.cfg` — `result_format=yaml` + `stdout_callback=default` (fixed deprecated `yaml` callback for ansible-core 2.13+).
- `2026-04-05`: Deprecated YAML callback replaced. Phase: Ansible Environment Bootstrap/v6.2.0.

### ⚠️ Uncategorisation Law
> Items here should be reviewed periodically. If a pattern emerges (e.g., 3+ commits in one category), promote it to a dedicated Room.

---
## 🔗 Retrieval Reference
- **Backfill Proposal:** `.agents/brain/palace_update_proposal_2026-04-08_1214.md` (room_uncategorised section)

---
*Last Refined: 2026-04-08 | Backfill: Full History | Hall: hall_discoveries | Wing: wing_dsom_core*
