---
okf_version: 0.1
type: documentation
title: "Automation & Script Audit Ledger"
description: "A comprehensive index of all executable scripts (.ps1, .sh) and Ansible playbooks/variables (.yml) currently active in the DSOM framework for human auditing purposes."
resource: "file:///docs/governance/AUTOMATION-AUDIT-LIST.md"
timestamp: 2026-07-12T07:16:00Z
topics: [docs, governance, automation, audit, list]
---
# Automation & Script Audit Ledger

This document serves as a human-auditable index of all executable automation scripts and configuration files currently present in the DSOM workspace. It is segmented by file type to facilitate security, syntax, and operational auditing.

## 1. Shell Scripts (`.sh`)
*POSIX-compliant scripts used for Linux and Termux environments.*

- **`tools/audit-pre-flight.sh`**: Performs initial system compliance and environment checks prior to AI operations.
- **`tools/build_sovereign_book.sh`**: Compiles the Sovereign AI operational runbooks and static documentation.
- **`tools/check-usage.sh`**: Monitors system resources and tracks AI token usage limits.
- **`tools/checkpoint.sh`**: Forces a manual save of the current spatial memory (`.agents/brain`) state.
- **`tools/diagnostic.sh`**: Runs deep system diagnostics, health checks, and dependency validation on the agent environment.
- **`tools/dsom-onboard.sh`**: Initializes and bootstraps a new user or agent into the DSOM workspace.
- **`tools/eod-palace.sh`**: The End of Day (EOD) ritual script to package spatial memory and synchronize it to the remote repository.
- **`tools/generate-walkthrough.sh`**: Automatically compiles a summary artifact of the active session's work.
- **`tools/git-ritual.sh`**: Standardizes GitOps commit and push procedures for the AI agent.
- **`tools/hibernation.sh`**: Safely powers down the agent and locks the context state to prevent corruption.
- **`tools/init-brain.sh`**: Scaffolds the `.agents/brain` directory structure, including the palace registry and isolated logic wings.
- **`tools/palace-sync.sh`**: Synchronizes the local brain state with the upstream Git repository to ensure cross-device continuity.
- **`tools/privacy-guardian.sh`**: Scans the workspace for leaked credentials or PII before allowing Git commits.
- **`tools/reanimate-claude.sh`**: Specialized Start of Day (SOD) script for environments powered by Claude models.
- **`tools/reanimate.sh`**: The standard Start of Day (SOD) script to wake the agent, load context, and initialize the terminal.
- **`tools/setup-dsom-control-node.sh`**: Provisions a new Linux host machine to act as a centralized DSOM control node.
- **`tools/sod-palace.sh`**: Start of Day (SOD) ritual to initialize the memory palace ledger.
- **`tools/template-reset.sh`**: Wipes the current operational state and restores the `.agents/brain` to its pristine baseline template.

## 2. PowerShell Scripts (`.ps1`)
*Cross-platform orchestration scripts designed for Windows and PowerShell Core.*

- **`tools/audit-pre-flight.ps1`**: Performs initial system compliance and environment checks prior to AI operations.
- **`tools/checkpoint.ps1`**: Forces a manual save of the current spatial memory state.
- **`tools/CheckUsage.ps1`**: Monitors system resources and tracks AI token usage limits.
- **`tools/diagnostic.ps1`**: Runs deep system diagnostics and dependency validation.
- **`tools/dsom-onboard.ps1`**: Initializes a new user or agent into the DSOM workspace.
- **`tools/eod-palace.ps1`**: The End of Day (EOD) ritual script to package spatial memory.
- **`tools/generate-walkthrough.ps1`**: Automatically compiles a summary artifact of the active session's work.
- **`tools/git-ritual.ps1`**: Standardizes GitOps commit and push procedures.
- **`tools/hibernation.ps1`**: Safely powers down the agent and locks the context state.
- **`tools/init-brain.ps1`**: Scaffolds the `.agents/brain` directory structure.
- **`tools/palace-sync.ps1`**: Synchronizes the local brain state with the upstream Git repository.
- **`tools/privacy-guardian.ps1`**: Scans the workspace for leaked credentials or PII before Git commits.
- **`tools/reanimate-claude.ps1`**: Specialized Start of Day (SOD) script for Claude models.
- **`tools/reanimate.ps1`**: The standard Start of Day (SOD) script to wake the agent and load context.
- **`tools/setup-wsl-almalinux10.ps1`**: Provisions a Windows Subsystem for Linux (WSL) environment running AlmaLinux 10.
- **`tools/sod-palace.ps1`**: Start of Day (SOD) ritual to initialize the memory palace ledger.
- **`tools/template-reset.ps1`**: Wipes the current state and restores the `.agents/brain` baseline template.

## 3. YAML Configurations & Playbooks (`.yml`)
*Ansible playbooks, role tasks, and inventory configurations.*

### Root & Inventory
- **`mkdocs.yml`**: Core configuration for the MkDocs static site generator used to publish DSOM documentation.
- **`inventory/hosts.yml`**: Ansible inventory file defining target nodes and server groups.
- **`inventory/group_vars/all.yml`**: Global variables and shared secrets for the Ansible infrastructure fabric.

### Ansible Playbooks
- **`playbooks/common.yml`**: Baseline configurations and hardening applied to all infrastructure nodes.
- **`playbooks/preflight.yml`**: Pre-execution checks and environment validation for the Ansible fabric.
- **`playbooks/dsom/audit-preflight.yml`**: Ansible orchestration translating the standalone audit pre-flight checks to multi-node environments.
- **`playbooks/dsom/checkpoint-palace.yml`**: Ansible orchestration for distributed memory checkpointing.
- **`playbooks/dsom/eod-palace.yml`**: Ansible orchestration for End of Day rituals across the node network.
- **`playbooks/dsom/init-brain.yml`**: Ansible orchestration for scalable brain scaffolding.
- **`playbooks/dsom/onboard-dsom.yml`**: Ansible playbook for onboarding remote nodes into the DSOM framework.
- **`playbooks/dsom/privacy-scan.yml`**: Ansible orchestration enforcing privacy guarding across distributed nodes.
- **`playbooks/dsom/site.yml`**: Master site playbook sequentially combining all DSOM orchestration roles.
- **`playbooks/dsom/sod-palace.yml`**: Ansible orchestration for Start of Day initialization across nodes.

### Ansible Roles (Common)
- **`roles/common/defaults/main.yml`**: Default variable definitions for the common baseline role.
- **`roles/common/handlers/main.yml`**: Reusable service handlers (e.g., restarting SSH/Nginx) for the common role.
- **`roles/common/meta/main.yml`**: Role dependency definitions and Galaxy metadata.
- **`roles/common/tasks/directories.yml`**: Idempotent creation of standard organizational directory structures.
- **`roles/common/tasks/main.yml`**: Primary execution file orchestrating the sub-tasks for the common role.
- **`roles/common/tasks/packages.yml`**: Package management definitions for baseline dependencies (e.g., `git`, `curl`, `podman`).
- **`roles/common/tasks/sysctl.yml`**: Kernel parameter tuning and security hardening via `sysctl`.
- **`roles/common/tasks/timezone.yml`**: Idempotent configuration of node timezones and NTP synchronization.

---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-12*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
