---
okf_version: 0.1
type: governance_protocol
title: "📖 DSOM Operational Guide (Level 3 - Specialised Tasks)"
description: "OKF-compliant documentation for OPERATIONAL-GUIDE.md."
resource: "file:///docs/governance/OPERATIONAL-GUIDE.md"
timestamp: 2026-07-04T09:40:04Z
topics: [docs, governance, operational, dsom, level]
---
# 📖 DSOM Operational Guide (Level 3 - Specialised Tasks)

> **"Theory without Practice is Hallucination. Practice without Theory is Chaos."**
> **Standard: DSOM v6.1 + Palace v1.0**

## 1. Purpose of this Document

This guide bridges the gap between the **Abstract Laws** (`AI-MASTER-PROTOCOL.md`) and the **Concrete Actions** (Bash/PowerShell scripts). It defines the **Specialised Tasks (L3)** required to execute the DSOM protocol.

It answers the question: *"How do I actually perform the rituals defined in the Master Protocol?"*

---

## 2. The Reanimation Sequence (Start-of-Day)

The Reanimation Ritual is not just running a script; it is a **Cognitive Handshake** that transfers the project's soul from disk to the AI's active memory.

### [RECOMMENDED] Ansible Palace SOD (T2: Linux/WSL2)

> Run this single command on T2 instead of Steps 1–3 below. It automates git pull, audit, palace check, and reanimate.

```bash
bash tools/sod-palace.sh  # (Windows: .\tools\sod-palace.ps1)
```

Then upload the manifest and say: *"Initialise DSOM Protocol v6.1 + Palace v1.0. Walk the Palace Registry in Section [14]. State: 'Sovereign State Synchronised' when ready."*

---

### Step 1: Physical Reality Check (The Audit)

Before waking the AI, we must verify that the physical environment matches the expected state.

**Command:**

```bash
# Linux / WSL2
./tools/audit-pre-flight.sh

# Windows
.\tools\audit-pre-flight.ps1
```

**Success Criteria:**

1. **Brain Check:** `task.md` and `walkthrough.md` must exist.
2. **Git Drift:** Local repo must be synced with Remote.
3. **Palace Registry:** `.agents/brain/palace_registry.md` must exist. If missing, run `bash tools/palace-sync.sh --backfill`.
4. **Cognitive Twin Protocol:** `docs/AI-COGNITIVE-TWIN-PROTOCOL.md` must exist and be filled in for this project.
5. **Ansible Baseline:** `inventory/hosts.yml` and `ansible.cfg` must exist if this project uses infrastructure automation.

### Step 2: Generating the Manifest (The Injection)

We aggregate all context into a single "Truth File."

**Command:**

```bash
# Linux
./tools/reanimate.sh

# Windows
.\tools\reanimate.ps1
```

**What is Injected (v2.2 — Palace-aware)?**

| Section | Content |
|---|---|
| [1–5] | Identity, Laws, Brain (task/walkthrough/plan) |
| [6–10] | Git log, file tree, SUMMARY, CHANGELOG, env telemetry |
| [11–13] | Cognitive Twin, Ansible topology, GitOps strategy |
| **[14]** | **`palace_registry.md` — AI spatial map (Palace v1.0)** |

### Step 3: The Handshake (The Prompt)

Upload the generated text file to the AI and say:
> *"Initialise DSOM Protocol v6.1 + Palace v1.0. Read the manifest. Walk the Palace Registry in Section [14] — identify relevant Rooms for today's work. State: 'Sovereign State Synchronised' when ready."*

### Step 4: Walking the Palace (The Retrieval)

Before starting logic work, the AI must traverse the **Spatial Markdown Palace** (`.agents/brain/wings/`):

1. **Registry Scan:** Read `palace_registry.md` to identify the relevant Wing and Rooms.
2. **Room Entry:** Load the `closet.md` for specific technical contexts (e.g., Auth, Persistence).
3. **Discovery:** Use the Palace to identify connections between disparate data points that the linear walkthrough might obscure.

---

## 3. The Hibernation Sequence (End-of-Day)

We never "just close the window." We must perform a controlled shutdown to prevent context decay.

### [RECOMMENDED] Ansible Palace EOD (T2: Linux/WSL2)

> Run this single command on T2 instead of Steps 1–2 below. It validates brain, runs palace-sync, stages, commits, and pushes.

```bash
bash tools/eod-palace.sh  # (Windows: .\tools\eod-palace.ps1)
```

After it completes — review `palace_update_proposal_YYYY-MM-DD.md` with your AI and update relevant closets.

---

### Step 1: Context Consolidation

1. **Update `task.md`:** Check off completed items.
2. **Update `walkthrough.md`:** Create a new "Session Anchor."
3. **Log decisions:** Apply the Decision Log Protocol — record *why* key decisions were made.

### Step 2: The Safe Shutdown

Run the hibernation tool to verify safety.

**Command:**

```bash
# Linux
./tools/hibernation.sh

# Windows
.\tools\hibernation.ps1
```

**The Logic (v2.1 — Palace-aware):**

- Checks `task.md` for `[x]` completed tasks
- Checks `walkthrough.md` for today's date anchor
- **Step 7: Palace Spatial Reflection** — auto-runs `palace-sync.sh` to generate `palace_update_proposal_YYYY-MM-DD.md`
- Commits and pushes if all checks pass

---

## 4. 🛠️ Architectural Layers (Clean Architecture)

When writing code, you must place files in the correct "Ring" of the Clean Architecture model.

| Layer | Directory | Allowed Dependencies |
| :--- | :--- | :--- |
| **Entities** | `src/Domain/` | None (Pure Logic). |
| **Use Cases** | `src/Application/` | Entities only. |
| **Adapters** | `src/Infrastructure/` | Use Cases & Entities. |
| **Drivers** | `tools/`, `public/` | Everything (The Entry Points). |

**Rule:** Dependencies point INWARD. `tools/` can import `src/`, but `src/Domain/` cannot import `tools/`.

---

---

## 5. Adoption & Upgrade Scenarios

For detailed step-by-step guides on how to apply DSOM to your specific situation, refer to the specialized manuals:

### Scenario 1: Brownfield Adoption

- **Situation:** You have an existing project (Standard Code) and want to add DSOM.
- **Guide:** [HOWTO: Adopt DSOM in Existing Projects](HOWTO-ADOPT-DSOM.md)

### Scenario 2: Legacy Upgrade

- **Situation:** You have an older DSOM version (v3/v4) and want to upgrade to v5.x (ITIL/Privacy).
- **Guide:** [HOWTO: Upgrade and Audit DSOM](HOWTO-UPGRADE-DSOM.md)

### Scenario 3: Palace Migration

- **Situation:** You have DSOM v6.x but no Palace. You want to add the spatial memory layer.
- **Guide:** [HOWTO: Migrate to Palace](HOWTO-MIGRATE-TO-PALACE.md)

### Scenario 4: New Adopter — Palace Edition

- **Situation:** First time setting up DSOM from scratch with Palace v1.0.
- **Guide:** [HOWTO: Palace Onboarding](HOWTO-PALACE-ONBOARDING.md)

---

## 6. ⚙️ Execution Guardrails (The Three-Pillar Laws)

These guardrails apply to **all** DSOM projects using the GitOps + AIOps + Ansible model. They are non-negotiable.

### Guardrail 1: No Silent Execution

The AI **never** directly runs commands on remote infrastructure. The workflow is always:
> **AI Proposes** → **Human Reviews** → **Human Approves** → **Ansible Executes**

### Guardrail 2: Ansible Pre-flight Mandate

Before executing **any** playbook, the following must be verified:

```bash
# 1. Run the DSOM pre-flight audit
./tools/audit-pre-flight.sh

# 2. Verify Ansible connectivity to all target nodes
ansible all -m ping -i inventory/hosts.yml

# 3. Dry-run the playbook first
ansible-playbook playbooks/site.yml --check --diff
```

If any step fails, execution is **stopped**. The AI diagnoses the failure and proposes a fix.

### Guardrail 3: Log Review Protocol

After every Ansible execution, the human must provide output to the AI for verification. Two accepted formats:

- **Direct Terminal Sync:** Full output copied and pasted into the chat.
- **Persistent Log File:** Output redirected to a log file for deep analysis:

  ```bash
  ansible-playbook playbooks/site.yml 2>&1 | tee .logs/deploy-$(date +%Y%m%d-%H%M%S).log
  ```

The AI will not proceed to the next phase without reviewing the execution output.

### Guardrail 4: Self-Healing Rule

- **NEVER** delete data directories (e.g., `[PROD_PATH]/data`). Deletion requires explicit Sovereign authorisation.
- Recovery is achieved through **idempotent Ansible automation** — re-running the playbook restores the desired state.
- If data corruption is suspected, stop all actions, preserve logs, and escalate to the Sovereign Architect.

### Guardrail 5: GitOps Loop

- All playbook or configuration changes are **committed to Git before execution**.
- No ad-hoc file edits on target nodes via SSH.
- The Git commit IS the change record. No commit = no change.

For full doctrine, see [`docs/GITOPS-AIOPS-ANSIBLE-STRATEGY.md`](GITOPS-AIOPS-ANSIBLE-STRATEGY.md).

---

*Last Updated: 2026-04-08 (v6.1 + Palace v1.0: Ansible SOD/EOD playbooks, Section [14] Palace Registry, Palace Migration scenarios)*


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-04*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
