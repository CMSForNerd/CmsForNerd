---
okf_version: 0.1
type: skill
title: "Telemetry and Bidirectional Feedback Operations"
name: "telemetry-and-feedback-ops"
description: "Guidelines and procedures for managing the local telemetry loops, dispatching feedback reports, and orchestrating multi-distro test matrices."
topics: [telemetry, feedback, testing, ansible, wsl2]
timestamp: 2026-08-01T09:00:00Z
---

# 📊 Telemetry and Bidirectional Feedback Operations

## Purpose
This skill governs the execution of local telemetry tracking, automatic generation of markdown reports, feedback pipeline dispatching, and multi-distribution matrix testing.

## When to use this skill
Execute this skill when running test pipelines, reviewing execution telemetry logs, or setting up multi-distro Podman test scenarios.

## Guidelines & Best Practices

### 1. Telemetry Pipeline Registration
The Telemetry and Bidirectional Feedback Pipeline must remain documented in `docs/governance/SOP-TELEMETRY-FEEDBACK-PIPELINE.md` and fully indexed in all four omni-documentation layers:
- `START-HERE.md` (Entry Point 15)
- `docs/SUMMARY.md`
- `mkdocs.yml`
- `llms.txt`

### 2. Execution-Mode Separated Telemetry Loops
The local telemetry loop separates execution environments using the `execution_mode` configuration flag:
- **`dev` mode:** The `feedback_collector` role compiles active logs, kernel states, and exit codes into `/tmp/jules_telemetry.json`. To protect data before external transmission:
  - Write compiled telemetry strictly to a securely created temporary file with permissions set to `0600` and guaranteed cleanup on exit.
  - Allowlist or redact sensitive log and kernel-state fields.
  - Require explicit operator authorization before sending reports through the `jules feed` or `gh pr comment` channels.
- **Feedback Dispatch:** The script `scripts/jules_gh_feedback.sh` parses this compiled JSON and dispatches a formatted Markdown report back to the active Google Jules session (via `jules feed`) and the active GitHub Pull Request (via `gh pr comment`).
- **`user` mode:** Telemetry loop execution is minimized or disabled to respect user boundaries and preserve privacy.

### 3. Ansible-Driven WSL2/Podman Multi-Distro Matrix Testing
To support automated local testing outside of Google Jules runtime, run the multi-distribution matrix playbook:
- Execute `playbooks/matrix_test.yml` on a WSL2 host running Podman 5+.
- This systematically tests application deployment and compliance across Ubuntu 24.04, Ubuntu 26.04, AlmaLinux 9, and Debian 12.


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-01*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
