---
okf_version: 0.1
type: skill
title: "Ansible and Podman Infrastructure Operations"
name: "ansible-and-podman-ops"
description: "Guidelines and security enforcements for orchestrating rootless Podman containers and writing high-fidelity Ansible Playbooks."
topics: [ansible, podman, rootless, security-hardening, fqcn]
timestamp: 2026-08-01T09:00:00Z
---

# 🐳 Ansible and Podman Infrastructure Operations

## Purpose
This skill establishes security, syntax, and execution specifications for infrastructure management using rootless Podman containers and Ansible automation.

## When to use this skill
Trigger this skill when writing or modifying Ansible Playbooks, Roles, tasks, or Podman configuration files, and when troubleshooting container deployments or configuring host networks.

## Guidelines & Best Practices

### 1. Rootless Podman Container Hardening
To implement robust defense-in-depth measures and ensure SELinux compatibility (not claiming absolute security):
- **Fully Qualified Images:** Always use fully qualified container image names with specific version tags (e.g., `docker.io/library/nginx:1.27`). Never use untagged or short names.
- **Privilege Limitation:** Explicitly define `security_opt: [no-new-privileges]`.
- **Capability Dropping:** Include `cap_drop: [all]` to strip all container privileges.
- **Volume Mount Suffixes:** Always use the `:Z` flag suffix for SELinux volume relabeling, and append `,ro` for read-only configuration directories.
- **Shared Pod Isolation:** Deploy Nginx and PHP-FPM within a shared Podman pod (e.g., `cmsfornerd-pod`) to allow secure communication over localhost without requiring host networking.
- **Privileged Port Binding:** Allow rootless container binding to port 80/443 by configuring host-level `sysctl` with `net.ipv4.ip_unprivileged_port_start` set to `80`.

### 2. Rootless Ansible Execution Context
All Ansible tasks managing rootless Podman containers must run in the target user context.
- Explicitly enforce execution using `become: true` combined with `become_user: cmsfornerd`.
- **Ownership/Location Mapping:** The architecture maps Nginx/PHP-FPM configurations, logs, and application code under `/home/cmsfornerd/`, with container and file ownership managed strictly by user `cmsfornerd` (UID/GID 1501).

### 3. Ansible Role & Variable Naming
To satisfy Ansible quality and linting standards:
- Always prefix role-defined variables with the name of the role itself (e.g., `podman_cms_user`).
- Avoid generic variables that may lead to namespace collision.

### 4. Restricted Permissions Quality Gate
Enforce restricted file system permissions in Ansible-rendered templates and setup directories:
- Set file permissions to `0600`.
- Set directory permissions to `0700`.

### 5. FQCN (Fully Qualified Collection Names)
All Ansible playbooks and roles must utilize FQCN for all modules (e.g., `ansible.builtin.file` instead of `file`, or `containers.podman.podman_container` instead of `podman_container`) to pass linting and SonarCloud analysis gates.

### 6. Deployment Quality & Syntax Auditing
The automated deployment playbook `deploy.yml` orchestrates custom-built PHP-FPM 8.4 and Nginx containers supporting Ubuntu, Debian, and RHEL-based distributions (AlmaLinux, Rocky, Oracle Linux).
- Enforce quality checks using syntax analysis and linting targeting the production profile:
  ```bash
  ansible-lint deploy.yml
  ansible-playbook --syntax-check deploy.yml
  ```


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-05*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
