---
okf_version: 0.1
type: skill
title: "OKF Frontmatter Injector"
name: "okf-frontmatter-injector"
description: "Scans a target directory and automatically injects OKF v0.1 YAML frontmatter into any Markdown files missing it."
topics: [okf, frontmatter, yaml, compliance, markdown]
timestamp: 2026-07-04T10:00:00Z
---

# 💉 OKF Frontmatter Injector

## When to use this skill
Use this skill when the user asks to ensure documentation is OKF (Open Knowledge Format) compliant, or when importing new markdown files that lack standard YAML frontmatter headers.

## Instructions
1. All Markdown (.md) documents in the repository must adhere to Open Knowledge Format (OKF) v0.1, requiring five YAML frontmatter fields: okf_version, type, title, timestamp, and topics (a 3-5 item lowercase array). Compliance is enforced via `uv run .agents/skills/okf-frontmatter-injector/scripts/apply_okf.py .`, which is hardened to ignore vendor, .git, node_modules, scratch, and data directories, and strips UTF-8 BOM (\ufeff) headers to prevent duplication.
2. Procedural AI agent skills reside in `.agents/skills/` (using OKF `SKILL.md` files) and are constrained to a strict 4,000-token limit per section, verified by `.agents/skills/dsom-token-calculator/scripts/calculate-tokens.py`.
3. Execute the script using your terminal tools:
   ```bash
   uv run .agents/skills/okf-frontmatter-injector/scripts/apply_okf.py <TARGET_DIRECTORY>
   ```
4. The script will automatically skip files that already possess frontmatter. It categorizes files dynamically based on their folder structure (e.g. `agent_skill`, `governance_protocol`, etc.).
5. Inform the user of the total number of files modified based on the script's output.


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-04*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
