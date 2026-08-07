---
okf_version: 0.1
type: governance_protocol
title: "🔄 DSOM Three-Pillar Strategy: GitOps · AIOps · Ansible (v1.0)"
description: "OKF-compliant documentation for GITOPS-AIOPS-ANSIBLE-STRATEGY.md."
resource: "file:///docs/governance/GITOPS-AIOPS-ANSIBLE-STRATEGY.md"
timestamp: 2026-07-04T09:40:04Z
topics: [docs, governance, gitops, aiops, ansible]
---
# 🔄 DSOM Three-Pillar Strategy: GitOps · AIOps · Ansible (v1.0)

> **Entry Point 9:** This document serves as the Security & Defense Entry Point. See [START-HERE.md](../../START-HERE.md) for the master onboarding roadmap.

## docs/GITOPS-AIOPS-ANSIBLE-STRATEGY.md

> **"Git is the source of truth. Ansible is the hand. AI is the mind."**

This document defines the **strategic doctrine** for the three operational pillars of any DSOM-based project. These pillars are non-negotiable and apply universally, regardless of the technology stack.

---

## 1. The Three-Pillar Model (+ Palace)

```
+--------------------------------------------------------------+
|                   DSOM OPERATING MODEL                       |
|                                                              |
|  +----------+   +----------+   +------------------+         |
|  |  AIOps   |-->|  GitOps  |-->|   The Executor   |         |
|  |  (Mind)  |   | (Record) |   |      (Hand)      |         |
|  +----------+   +----------+   +------------------+         |
|       |              |                  |                    |
|   AI proposes   Git records       Executor runs             |
|   & analyses    all state         on target nodes          |
|       ^              |                  |                    |
|       +--------------+--- AI verifies --+                    |
|                                                              |
|  +----------------------------------------------------------+ |
|  |  Palace (Memory)  --  Sovereign Markdown Palace v1.0    | |
|  |  .agents/brain/palace_registry.md + wings/               | |
|  |  SOD: AI reads Palace Registry (Section [14])           | |
|  |  EOD: palace-sync.sh writes spatial update proposal     | |
|  +----------------------------------------------------------+ |
+--------------------------------------------------------------+
```

**The Integration Loop:**

1. **AI Proposes** — AI (Cognitive Twin) analyses logs, generates playbooks/scripts, and recommends next action.
2. **Git Records** — Human commits the proposed script/config to the repository.
3. **Executor Runs** — Human triggers the execution (e.g. Ansible, `uv`, `node`) against target nodes.
4. **AI Verifies** — Human pastes output back to AI; AI analyses results and confirms success or recommends remediation.
5. **Palace Remembers** — EOD palace-sync captures spatial knowledge; SOD manifest includes Palace Registry so AI walks it on wake-up.

---

## 🤖 2. AIOps Pillar

### 2.1 Role Definition

The AI operates exclusively as the **Cognitive Digital Twin** — an advisory intelligence layer. It is **not** an autonomous executor.

| AI CAN | AI CANNOT |
|:---|:---|
| Generate Ansible playbooks | Directly SSH into any node |
| Analyse log output | Execute shell commands on remote hosts |
| Recommend architectural changes | Store or handle secrets/credentials |
| Propose Git commits and messages | Push to `main` without Human approval |
| Trigger Ansible *guidance* (tell Human what to run) | Auto-run any playbook |

### 2.2 AIOps Workflow

```
Log / Event → AI Analysis → Diagnosis + Playbook Proposal
                                      ↓
                          Human Review & Approval
                                      ↓
                          Git Commit → Ansible Execute
                                      ↓
                          Output → AI Verification Loop
```

### 2.3 AI Context Requirements

Before any advisory session, the AI **MUST** read:

- `docs/AI-MASTER-PROTOCOL.md` — Governance laws
- `docs/AI-COGNITIVE-TWIN-PROTOCOL.md` — Project-specific environment map
- `.agents/brain/task.md` — Current state
- `.agents/brain/walkthrough.md` — History and Mental Anchor
- `.agents/brain/palace_registry.md` — **Spatial map (Palace v1.0)** — walk this to locate relevant Rooms
- `inventory/hosts.yml` — Target node topology

> **SOD shortcut:** `bash tools/sod-palace.sh  # (Windows: .\tools\sod-palace.ps1)` injects all of the above automatically via the reanimation manifest (Section [14] = Palace Registry).

---

## 🔄 3. GitOps Pillar

### 3.1 Core Principle

**Git is the single source of truth.** All system state, configuration, and desired state is represented in the Git repository. The repository is the only authoritative source.

### 3.2 GitOps Laws

1. **No Ad-hoc Changes**: No manual SSH edits to production/staging nodes. If a change is not in Git, it does not exist.
2. **Deploy-by-Merge**: The merge to `main` is the trigger for a deployment (via Ansible). No other trigger is valid.
3. **Branch Protection**: The `main` branch is protected. All changes require a Pull Request (PR) or explicit Sovereign authorisation.
4. **Declarative State**: All configuration files are declarative (Ansible playbooks, `hosts.yml`, `group_vars/`). The desired state is always readable.
5. **Audit Trail**: Every change has a Git commit with a semantic message. The Git log is the deployment history.

### 3.3 Commit Convention

All commits must follow the **Conventional Commits** standard:

```
type(scope): descriptive message [Phase-XX/vX.X]

Types: feat | fix | docs | chore | refactor | test | ci
Scope: the component or role affected (e.g., kafka, logstash, haproxy)

Examples:
  feat(ansible): add kafka broker idempotency role [Phase-12/v2.3]
  fix(inventory): correct 'dsom_suffix' variable for node-02 [Phase-14/v2.4]
  docs(brain): update walkthrough mental anchor for Phase 15
```

### 3.4 Sync Ritual (The Push-Pull Cycle)

```bash
# Tier 1 (Command Centre): Author & Push
git add .
git commit -m "feat(scope): descriptive message [Phase-XX/vX.X]"
git push origin main

# Tier 2 (Dev Bridge): Palace SOD — full automated reanimation
bash tools/sod-palace.sh  # (Windows: .\tools\sod-palace.ps1)
# Then upload manifest + handshake with AI

# Tier 2 (Dev Bridge): Palace EOD — full automated save
bash tools/eod-palace.sh  # (Windows: .\tools\eod-palace.ps1)
# Then review palace_update_proposal + update closets
```

> **Manual alternative (T1 Windows):**
>
> ```powershell
> .\tools\reanimate.ps1   # SOD
> .\tools\hibernation.ps1 # EOD (includes palace-sync v2.1)
> ```

---

## ⚙️ 4. The Executor Pillar (Ansible / uv / Node)

### 4.1 Execution Modularity
While DSOM was originally forged for heavy infrastructure automation using **Ansible**, the actual mechanics of the Third Pillar (The Hand) apply to *any* project type. The Executor is fully modular:
- **Infrastructure:** `ansible-playbook` (OS-level operations)
- **Data Science / Python:** `uv run` (Isolated Python execution)
- **Web Development:** `npm run` / `node` (JS execution)
- **Documentation:** `pandoc` / `latex` (Compilation)

Regardless of the tool, the Executor acts as the **exclusive mechanism** for altering state. No manual edits or ad-hoc tinkering are permitted outside the execution scripts.

### 4.2 The Windows WSL2 Bridge (Control Node)
For projects operating on Windows 11 without a dedicated Linux jumphost, **WSL2 (Ubuntu or AlmaLinux)** must be configured as the local Control Node.
- This "Execution Bridge" ensures that humans and AI agents can seamlessly leverage standard Linux tooling (Ansible, Make, Bash) natively within the Windows environment.
- It removes architectural friction, allowing DSOM to be applied universally across OS environments without requiring external VMs.

### 4.3 Ansible Laws (If Applicable)

If your project is utilizing Ansible as the Executor, the following laws apply:

1. **Idempotency Law**: Every playbook MUST be safe to re-run multiple times with the same result. No playbook may cause data corruption on repeated execution.
2. **No Hardcoded Secrets**: All credentials are managed via `ansible-vault`. Never commit plaintext passwords or API keys.
3. **Role-Based Structure**: All automation is organised into `roles/` following the Ansible Galaxy standard.
4. **SSH Key Authentication**: All Ansible connections use SSH key-based auth. Password authentication is prohibited.
5. **Pre-flight Prerequisite**: `tools/audit-pre-flight.sh` MUST be run and pass before any playbook is executed.

### 4.3 Standard Directory Structure

Every DSOM project using Ansible MUST follow this structure:

```
[project-root]/
├── ansible.cfg                    # Ansible configuration
├── inventory/
│   ├── hosts.yml                  # Node inventory (Git-tracked, no secrets)
│   ├── group_vars/
│   │   ├── all.yml                # Variables for all hosts
│   │   └── [group_name].yml       # Group-specific variables
│   └── host_vars/
│       └── [hostname].yml         # Host-specific variables
├── playbooks/
│   ├── site.yml                   # Master playbook (entry point)
│   ├── deploy-[component].yml     # Component-specific playbooks
│   └── preflight.yml              # Pre-flight verification playbook
├── roles/
│   └── [role_name]/
│       ├── tasks/main.yml
│       ├── handlers/main.yml
│       ├── templates/
│       ├── files/
│       ├── vars/main.yml
│       └── defaults/main.yml
└── vault/
    └── .gitignore                 # Vault files NEVER committed to Git
```

### 4.4 Ansible Prerequisites Checklist

Before running any playbook, the AI MUST verify all of the following:

- [ ] `inventory/hosts.yml` exists and is populated
- [ ] SSH keys are distributed to all target nodes
- [ ] `ansible-vault` is configured if secrets are required
- [ ] `tools/audit-pre-flight.sh` has been run and passed
- [ ] The correct Ansible version is installed (`ansible --version`)
- [ ] Connectivity verified (`ansible all -m ping -i inventory/hosts.yml`)

### 4.5 Core Ansible Commands Reference

```bash
# Verify connectivity to all nodes
ansible all -m ping -i inventory/hosts.yml

# Dry-run (check mode) — always run before live execution
ansible-playbook playbooks/site.yml -i inventory/hosts.yml --check --diff

# Full execution with logging
ansible-playbook playbooks/site.yml -i inventory/hosts.yml \
  2>&1 | tee .logs/deploy-$(date +%Y%m%d-%H%M%S).log

# Run a specific role/tag only
ansible-playbook playbooks/site.yml -i inventory/hosts.yml --tags "[tag_name]"

# Encrypt a secrets file with vault
ansible-vault encrypt vault/production_secrets.yml

# View encrypted vault file
ansible-vault view vault/production_secrets.yml
```

---

## 5. Integration with DSOM Brain + Palace

The three pillars are anchored to the DSOM SKMS (Knowledge Management System):

| Brain Artifact | Pillar Role |
|:---|:---|
| `task.md` | AIOps: Current session targets and Ansible pre-flight checklist |
| `implementation_plan.md` | GitOps: The desired state roadmap; what to commit next |
| `walkthrough.md` | AIOps: History of Ansible runs, log analysis results, Mental Anchors |
| `inventory/hosts.yml` | Ansible: Node topology — always verified before execution |
| `playbooks/` | GitOps + Ansible: All automation is version-controlled |
| **`palace_registry.md`** | **Palace: AI spatial map — loaded at SOD via Section [14] of manifest** |
| **`wings/`** | **Palace: Room closets — spatial knowledge updated each EOD** |
| **`playbooks/dsom/sod-palace.yml`** | **Ansible + AIOps: Automates SOD reanimation loop** |
| **`playbooks/dsom/eod-palace.yml`** | **Ansible + GitOps: Automates EOD save + Palace sync** |

---

## 📋 6. Adoption Checklist for New Projects

When starting a new project with DSOM, use this checklist to establish the three pillar baseline:

### GitOps Setup

- [ ] Create GitHub repository with branch protection on `main`
- [ ] Configure `.gitignore` to exclude `vault/`, `*.log`, sensitive files
- [ ] Define commit convention in `CONTRIBUTING.md`

### AIOps Setup

- [ ] Create `docs/AI-COGNITIVE-TWIN-PROTOCOL.md` from template (fill all `[PLACEHOLDER]` values)
- [ ] Initialise `.agents/brain/` with `task.md`, `walkthrough.md`, `implementation_plan.md`
- [ ] Configure AI agent with `docs/AI-MASTER-PROTOCOL.md` as system context
- [ ] **Run `bash tools/palace-sync.sh --backfill`** to initialise Palace Registry
- [ ] Review `palace_update_proposal_YYYY-MM-DD.md` with AI and populate closets

### Ansible Setup

- [ ] Follow `docs/HOWTO-SETUP-ANSIBLE-BASELINE.md` for full setup
- [ ] Create `ansible.cfg`, `inventory/hosts.yml`, `playbooks/preflight.yml`
- [ ] Verify connectivity: `ansible all -m ping -i inventory/hosts.yml`
- [ ] Run pre-flight: `tools/audit-pre-flight.sh`
- [ ] **Test SOD playlist:** `bash tools/sod-palace.sh  # (Windows: .\tools\sod-palace.ps1)`
- [ ] **Test EOD playlist:** `bash tools/eod-palace.sh  # (Windows: .\tools\eod-palace.ps1)`

---

*Created by the DSOM Engineering Team | v2.0 | 2026-04-08*
*Standard: UK English | Licensed under GPLv3*


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-04*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
