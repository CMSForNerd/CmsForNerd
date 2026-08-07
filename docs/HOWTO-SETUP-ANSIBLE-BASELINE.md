---
okf_version: 0.1
type: documentation
title: "🛠️ HOWTO: Set Up the Ansible Baseline for a DSOM Project"
description: "OKF-compliant documentation for HOWTO-SETUP-ANSIBLE-BASELINE.md."
resource: "file:///docs/HOWTO-SETUP-ANSIBLE-BASELINE.md"
timestamp: 2026-07-04T09:40:04Z
topics: [docs, ansible, baseline, set, dsom]
---
# 🛠️ HOWTO: Set Up the Ansible Baseline for a DSOM Project

**Title:** HOWTO: Set Up the Ansible Baseline for a DSOM Project
**Author:** Harisfazillah Jamel (LinuxMalaysia)
**Version:** 1.0
**Date:** 2026-03-09
**License:** GPLv3
**Standard:** Linux Documentation Project (LDP) | UK English

---

## 1. Introduction

### 1.1 Scope

This guide describes how to establish the **Ansible baseline** for any new project built on the DSOM skeleton. Upon completion, you will have:

- A standard Ansible directory structure
- A configured `ansible.cfg`
- A template `inventory/hosts.yml`
- A pre-flight verification playbook
- Integration with the DSOM Palace rituals (`sod-palace.yml`, `eod-palace.yml`)
- Ansible Vault integration for secrets management
- Integration with the DSOM `tools/audit-pre-flight.sh` script

### 1.2 Target Audience

- Lead Architects and DevOps Engineers adopting the DSOM protocol
- AI Agents (Cognitive Digital Twins) bootstrapping context for a new project

### 1.3 Related Documents

- [`docs/GITOPS-AIOPS-ANSIBLE-STRATEGY.md`](GITOPS-AIOPS-ANSIBLE-STRATEGY.md) — Strategic doctrine
- [`docs/AI-COGNITIVE-TWIN-PROTOCOL.md`](AI-COGNITIVE-TWIN-PROTOCOL.md) — Project identity template
- [`docs/AI-MASTER-PROTOCOL.md`](AI-MASTER-PROTOCOL.md) — Governance laws

---

## 2. Prerequisites

Before beginning, verify the following are available on your **Tier 2 Dev Bridge** (or Tier 1 Command Centre for Windows):

| Requirement | Minimum Version | Verification Command |
|:---|:---|:---|
| Python | 3.9+ | `python3 --version` |
| pip | 23+ | `pip3 --version` |
| Ansible | 2.15+ | `ansible --version` |
| SSH Client | Any | `ssh -V` |
| Git | 2.30+ | `git --version` |

### Install Ansible (if not present)

```bash
# Ubuntu / Debian
sudo apt update && sudo apt install -y python3-pip
pip3 install ansible

# RHEL / AlmaLinux / Rocky Linux
sudo dnf install -y python3-pip
pip3 install ansible

# Verify
ansible --version
```

---

## 3. Procedure

### Step 1: Create the Directory Structure

**Action:** From your project root, create the standard Ansible skeleton.

**Command:**

```bash
# From project root (Tier 2 Dev Bridge or WSL2)
mkdir -p inventory/group_vars inventory/host_vars
mkdir -p playbooks/dsom
mkdir -p roles
mkdir -p vault
mkdir -p .logs

# Protect the vault directory — NEVER commit secrets
cat >> .gitignore << 'EOF'

# Ansible Vault & Sensitive Files
vault/*.yml
!vault/.gitignore
*.retry
.logs/*.log
EOF

# Create vault .gitignore placeholder
echo "# Vault secrets are NEVER committed to Git" > vault/.gitignore
```

**Outcome:** The standard DSOM Ansible directory skeleton exists at your project root.

---

### Step 2: Create `ansible.cfg`

**Action:** Create the Ansible configuration file at the project root.

**Command:**

```bash
cat > ansible.cfg << 'EOF'
[defaults]
inventory           = inventory/hosts.yml
roles_path          = roles
retry_files_enabled = False
host_key_checking   = False
stdout_callback     = yaml
callbacks_enabled   = profile_tasks
log_path            = .logs/ansible.log

[privilege_escalation]
become              = False
become_method       = sudo
become_user         = root

[ssh_connection]
ssh_args            = -o ControlMaster=auto -o ControlPersist=60s
pipelining          = True
EOF
```

**Outcome:** `ansible.cfg` is created with DSOM-standard settings.

> **[BRAIN] Why `host_key_checking = False`?** In automated pipelines with dynamic inventories, SSH host key prompts block execution. Ensure your network is secured at the perimeter level instead.

---

### Step 3: Create `inventory/hosts.yml`

**Action:** Create the inventory template. Replace all `[PLACEHOLDER]` values with your actual node details.

**Command:**

```bash
cat > inventory/hosts.yml << 'EOF'
---
# DSOM Ansible Inventory Template
# Project: [YOUR_PROJECT_NAME]
# Last Updated: [DATE]
# IMPORTANT: This file must NOT contain secrets. Use ansible-vault for credentials.

all:
  vars:
    ansible_user: "[YOUR_DEPLOY_USER]"       # e.g., example-user, dsom-admin
    ansible_ssh_private_key_file: "~/.ssh/[YOUR_KEY_NAME]"
    ansible_python_interpreter: "/usr/bin/python3"

  children:
    # ── Tier 2: Development / Dev Bridge ──────────────────────────────
    dev:
      hosts:
        dev-node-01:
          ansible_host: "[DEV_IP_OR_HOSTNAME]"   # e.g., 10.10.10.11
          ansible_user: "[DEV_USER]"              # e.g., example-user

    # ── Tier 3: Staging / UAT ─────────────────────────────────────────
    staging:
      hosts:
        staging-node-01:
          ansible_host: "[STAGING_IP_OR_HOSTNAME]"

    # ── Tier 4: Production ────────────────────────────────────────────
    production:
      hosts:
        prod-node-01:
          ansible_host: "[PROD_IP_OR_HOSTNAME]"
          ansible_user: "[PROD_USER]"             # e.g., dsom-admin
EOF
```

**Outcome:** `inventory/hosts.yml` is created as the node topology source of truth.

---

### Step 4: Create Group Variables

**Action:** Create baseline variable files for all hosts and environment groups.

**Command:**

```bash
cat > inventory/group_vars/all.yml << 'EOF'
---
# Variables applied to ALL hosts
project_name:   "[YOUR_PROJECT_NAME]"
project_path:   "/opt/[YOUR_PROJECT_NAME]"
deploy_user:    "[YOUR_DEPLOY_USER]"
deploy_uid:     "[YOUR_UID]"          # e.g., 1000 (dev) or 2001 (prod)
EOF

cat > inventory/group_vars/production.yml << 'EOF'
---
# Production-specific overrides
deploy_uid:   "[PROD_UID]"            # e.g., 2001
project_path: "/opt/[YOUR_PROJECT_NAME]-prod"
# Vault-injected variables — DO NOT put secrets here
# credentials: "{{ vault_production_credentials }}"
EOF
```

**Outcome:** Declarative group variables established for the GitOps single-source-of-truth principle.

---

### Step 5: Create the Pre-flight Verification Playbook

**Action:** Create a playbook to verify the Ansible baseline is functional before any deployment.

**Command:**

```bash
cat > playbooks/preflight.yml << 'EOF'
---
# DSOM Pre-flight Verification Playbook
# Purpose: Verify connectivity and baseline state before any deployment.
# Usage:   ansible-playbook playbooks/preflight.yml
# Rule:    This playbook MUST pass before any other playbook is run.

- name: "DSOM Pre-flight Verification"
  hosts: all
  gather_facts: true
  become: false

  tasks:
    - name: "Verify node connectivity and Python availability"
      ansible.builtin.ping:
      register: ping_result

    - name: "Display node OS information"
      ansible.builtin.debug:
        msg: >
          Node: {{ inventory_hostname }} |
          OS: {{ ansible_distribution }} {{ ansible_distribution_version }} |
          Kernel: {{ ansible_kernel }} |
          Python: {{ ansible_python_version }}

    - name: "Verify deploy user identity"
      ansible.builtin.command: "id"
      register: id_output
      changed_when: false

    - name: "Show deploy user identity"
      ansible.builtin.debug:
        msg: "Identity on {{ inventory_hostname }}: {{ id_output.stdout }}"

    - name: "Verify project path exists (if already deployed)"
      ansible.builtin.stat:
        path: "{{ project_path }}"
      register: project_dir

    - name: "Report project path status"
      ansible.builtin.debug:
        msg: >
          Project path '{{ project_path }}' on {{ inventory_hostname }}:
          {{ 'EXISTS' if project_dir.stat.exists else 'NOT YET CREATED (expected for fresh deploy)' }}
EOF
```

**Outcome:** The pre-flight playbook exists and is ready to validate the environment.

---

### Step 6: Verify Connectivity

**Action:** Test that Ansible can reach all nodes.

**Command:**

```bash
# Basic connectivity ping
ansible all -m ping -i inventory/hosts.yml

# Run the pre-flight playbook in check mode first
ansible-playbook playbooks/preflight.yml --check

# Run the pre-flight playbook live
ansible-playbook playbooks/preflight.yml
```

**Success Criteria:**

- All nodes return `pong` for the ping.
- Pre-flight playbook completes with `failed=0` for all hosts.
- OS details and user identity are printed correctly.

---

### Step 7: Set Up Ansible Vault (Secrets Management)

**Action:** Encrypt a secrets file for any credentials required by playbooks.

**Command:**

```bash
# Create and encrypt a secrets file
cat > /tmp/secrets_draft.yml << 'EOF'
---
vault_deploy_password:    "CHANGE_ME"
vault_db_root_password:   "CHANGE_ME"
vault_api_key:            "CHANGE_ME"
EOF

# Encrypt it with ansible-vault
ansible-vault encrypt /tmp/secrets_draft.yml --output vault/production_secrets.yml
rm /tmp/secrets_draft.yml

# Verify the encrypted file (enter vault password when prompted)
ansible-vault view vault/production_secrets.yml
```

**Outcome:** Secrets are encrypted and stored in `vault/`. The plaintext file is destroyed immediately. The `vault/` directory is excluded from Git via `.gitignore`.

> **[BRAIN] Why `ansible-vault`?** Vault encryption ensures secrets can never be accidentally committed to Git. The vault password itself is stored out-of-band (e.g., a password manager, CI/CD secret store, or a separate `~/.vault_pass` file excluded from Git).

---

### Step 8: Integrate with `tools/audit-pre-flight.sh`

**Action:** Add Ansible checks to the DSOM audit pre-flight script so the AI Handshake automatically verifies Ansible readiness.

**Command:**

```bash
# Append Ansible checks to the existing audit script
cat >> tools/audit-pre-flight.sh << 'EOF'

# ── Ansible Pre-flight Checks ─────────────────────────────────────────────────
echo "--- [ANSIBLE] Verifying Ansible Baseline ---"

if ! command -v ansible &>/dev/null; then
  echo "[FAIL] Ansible is not installed. Run: pip3 install ansible"
  exit 1
fi
echo "[OK] Ansible: $(ansible --version | head -1)"

if [ ! -f "inventory/hosts.yml" ]; then
  echo "[FAIL] inventory/hosts.yml is missing. Create it from the DSOM template."
  exit 1
fi
echo "[OK] inventory/hosts.yml exists."

if [ ! -f "ansible.cfg" ]; then
  echo "[WARN] ansible.cfg is missing. Using Ansible defaults."
else
  echo "[OK] ansible.cfg exists."
fi

if [ ! -f "playbooks/preflight.yml" ]; then
  echo "[WARN] playbooks/preflight.yml is missing. Add it for full pre-flight support."
else
  echo "[OK] playbooks/preflight.yml exists."
fi

echo "[OK] Ansible baseline verified."
EOF
```

**Outcome:** The DSOM audit script now validates the Ansible baseline as part of every session Reanimation.

---

## 4. Troubleshooting

### Problem: `UNREACHABLE! Failed to connect`

- **Cause**: SSH key not distributed to target node, or wrong `ansible_host` IP.
- **Fix**: Run `ssh-copy-id [USER]@[HOST]` from your Tier 2 Dev Bridge, then retry the ping.

### Problem: `MODULE FAILURE: No Python interpreter found`

- **Cause**: Python 3 not installed on target node.
- **Fix**: Install Python: `sudo apt install -y python3` (Debian/Ubuntu) or `sudo dnf install -y python3` (RHEL/Rocky).

### Problem: `ERROR! Decryption failed`

- **Cause**: Wrong vault password, or vault file is corrupted.
- **Fix**: Verify the vault password. Re-create the vault file if necessary.

### Problem: Playbook is not idempotent (changes on re-run)

- **Cause**: Task uses `command:` or `shell:` module without `changed_when: false` or `creates:` condition.
- **Fix**: Replace with idempotent modules (`ansible.builtin.copy`, `ansible.builtin.template`, `ansible.builtin.service`). Use `changed_when: false` for read-only commands.

---

## 5. References

- [DSOM Primary Repository](https://github.com/linuxmalaysia/deep-state-of-mind-for-my-ai)
- [Ansible Documentation](https://docs.ansible.com/)
- [Ansible Vault Guide](https://docs.ansible.com/ansible/latest/user_guide/vault.html)
- [Conventional Commits Standard](https://www.conventionalcommits.org/)
- [`docs/GITOPS-AIOPS-ANSIBLE-STRATEGY.md`](GITOPS-AIOPS-ANSIBLE-STRATEGY.md)

---

*Standard: Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel*
**Current Status:** `ACTIVE` | **Last Human Audit:** 2026-04-08 | **Version:** 1.0 + Palace v1.0
