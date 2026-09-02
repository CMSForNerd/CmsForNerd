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
This skill establishes security, syntax, and execution specifications for infrastructure management using rootless Podman containers, Render blueprint deployments, and Ansible automation.

## When to use this skill
Trigger this skill when writing or modifying Ansible Playbooks, Roles, tasks, or Podman configuration files, and when troubleshooting container deployments, Render blueprints, or host networking.

## Guidelines & Best Practices

### 1. Rootless Podman Container Hardening & Workstation Setup
To implement robust defense-in-depth measures and ensure SELinux compatibility:
- **Fully Qualified Images:** Always use fully qualified container image names with specific version tags (e.g., `docker.io/library/nginx:1.27`). Never use untagged or short names.
- **Privilege Limitation:** Explicitly define `security_opt: [no-new-privileges]`.
- **Capability Dropping:** Include `cap_drop: [all]` to strip all container privileges.
- **Volume Mount Suffixes:** Always use the `:Z` flag suffix for SELinux volume relabeling, and append `,ro` for read-only configuration directories.
- **Shared Pod Isolation:** Deploy Nginx and PHP-FPM within a shared Podman pod (e.g., `cmsfornerd-pod`) to allow secure communication over localhost without requiring host networking.
- **Privileged Port Binding & Dedicated-Host Invariant:** In production, privileged port access (80/443) is terminated by a dedicated front-end proxy or bound via narrow system capabilities. BunkerWeb is configured in production (`playbooks/roles/podman_prod/templates/compose.yml.j2`) to terminate SSL/TLS directly using native self-signed certificate generation (`GENERATE_SELF_SIGNED_SSL=yes` and `LISTEN_HTTPS=yes` on port 8443) with host port 443 mapped to 8443.
- **Ubuntu 26.04 & Podman 5+ Invariant:** Ubuntu 26.04 (Resolute Raccoon) is the primary targeted deployment OS for native Podman 5.x support. Google Jules VM session environments run as Google Cloud Workstations configured to permanently deploy Ubuntu 26.04+ and Podman 5+. Playbooks enforce Podman 5+ assertions via `podman --version` parsed major version checks and require packages like `uidmap`, `dbus-user-session`, `catatonit`, and `loginctl enable-linger`.

### 2. Rootless Ansible Execution Context & Sudo Lock
All Ansible tasks managing rootless Podman containers must run in the target user context.
- Explicitly enforce execution using `become: true` combined with `become_user: cmsfornerd`.
- **Ownership/Location Mapping:** The architecture maps Nginx/PHP-FPM configurations, logs, and application code under `/home/cmsfornerd/`, with container and file ownership managed strictly by user `cmsfornerd` (UID/GID 1501).
- **Sudo Lock Validation:** Validate Ansible privilege escalation structurally by enforcing the documented allowlist for play, task, and role scopes rather than matching two-space become lines. Report Sudo Lock as enforced only when all become usages are permitted and play-level privilege escalation (`become: true`) is disabled in `deploy_prod_compose.yml`.

### 3. Container Co-Existence, Render Blueprint & Local Dev Server
- **Dockerfile & Containerfile Co-Existence:** The repository completely favors Podman version 5+ (renaming Dockerfile to Containerfile and `docker-compose.yml` to `compose.yml`). However, a duplicate root-level `Dockerfile` co-exists with `Containerfile` to support cloud-based Docker and Render deployments.
- **Render Blueprint (`render.yaml`):** Render Blueprint deployments use `render.yaml` configured for the `singapore` region, explicitly pointing to root `Dockerfile` targeting PHP 8.4 and Apache (with `mod_rewrite`, `mod_headers`, and `.htaccess` overrides enabled). `TZ` is set to `Asia/Kuala_Lumpur` and secrets use `sync: false`.
- **Docker Validation Tests:** Docker validation tests require Dockerfile and Containerfile to expose port 80, restrict chown permissions specifically to `/var/www/html/data`, avoid duplicate `.dockerignore` entries, and maintain identical exclusion configurations between `sonar-project.properties` and GHA workflows.
- **Apache .htaccess Rules:** In Apache configurations, using `<DirectoryMatch>` or `<Directory>` tags inside `.htaccess` files is forbidden and causes 500 Internal Server Errors. Access restrictions within `.htaccess` should instead be implemented using `mod_rewrite` rules wrapped in `<IfModule mod_rewrite.c>`.
- **Local Dev Server & Node Bootstrapping:** Run local development server using `php -S 127.0.0.1:3000` by default, using `php -S 0.0.0.0:3000` only when explicitly opting in for remote Jules or VM access. The node bootstrapping playbook `playbooks/bootstrap_node.yml` and `playbooks/roles/setup_os/` configure host baseline targeting `localhost` and private IP addresses (`10.0.0.10`). Setup documentation lives in `docs/HOWTO-SETUP-GOOGLE-JULES-UBUNTU-26-04.md`.

### 4. Safe Bash Validation & Deployment Scripts
- **Safe `grep -c` under `set -e`:** In bash validation scripts run under `set -e`, executing `grep -c` directly inside command substitution triggers failure branches on zero matches (exit code 1). Always guard with `|| true` or use `grep | wc -l`.
- **Pre-flight Environmental Gateway (`tools/audit-pre-flight.sh`):** Serves as a hybrid gateway verifying PHP requirement (`PHP_VERSION_ID >= 80300`) using Bash `[[ ]]` expressions and DSOM synchronization, bypassable via `BYPASS_PHP_CHECK=1`. The project requires PHP 8.4 or newer for running tests and tools; `composer install --ignore-platform-reqs` is restricted to installation diagnostics only.
- **Deployment Wrapper (`tools/deploy-prod.sh`):** Validates unprivileged identity constraints (`dsom-admin:2001:2001`) via `tools/validate-inventory.py`, supports Ansible `@file` inputs, and checks `podman_prod` state-based conditions derived from container running states rather than compose stdout.

### 5. Ansible Role, FQCN & Quality Checks
- All Ansible playbooks and roles must utilize FQCN for all modules (e.g., `ansible.builtin.file` instead of `file`, or `containers.podman.podman_container` instead of `podman_container`).
- DevOps verification and quality checks are run using `composer lab-check` (which runs PHPStan Level 8 static analysis, code standard audits, and Pest PHP tests via `composer test`).
- Enforce quality checks using syntax analysis and linting:
  ```bash
  ansible-lint deploy.yml
  ansible-playbook --syntax-check deploy.yml
  ```


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-05*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
