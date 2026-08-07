---
okf_version: 0.1
type: governance_protocol
title: "**Operational Sovereignty through Metacognitive Governance: Integrating the Deep State of Mind Protocol with CAPM and Git-Native PMO Frameworks**"
description: "OKF-compliant documentation for OPERATIONAL-SOVEREIGNTY.md."
resource: "file:///docs/governance/OPERATIONAL-SOVEREIGNTY.md"
timestamp: 2026-07-04T09:40:04Z
topics: [docs, governance, operational, sovereignty, through]
---
# **Operational Sovereignty through Metacognitive Governance: Integrating the Deep State of Mind Protocol with CAPM and Git-Native PMO Frameworks**

The Deep State of Mind (DSOM) protocol serves as a **metacognitive governance framework** designed to establish an active, persistent bridge between human architectural intent and machine execution. By transforming documentation from a passive record into a **sovereign ecosystem**, the protocol ensures high-fidelity continuity across diverse AI agents and distributed teams.

---

### **1. Theoretical Framework of Operational Sovereignty**

Architectural sovereignty is maintained by authorizing **local brain artifacts** within the `.agents/brain/` directory as the absolute **Single Source of Truth (SSoT)**. This approach prevents vendor lock-in, making project knowledge portable across various AI models such as Google Gemini, Anthropic Claude, or local LLMs.

Sovereignty is operationalised through the **5W1H Framework**:

* **Who:** Managed by the Lead Architect and the AI agent acting as a **Cognitive Digital Twin**.
* **What:** Integrates **Clean Architecture** with the CRISP Operational Strategy.
* **When:** Executed via daily **SOD/EOD Rituals** and a weekly **Sunday Human Audit**.
* **Where:** Hosted in the sovereign `.agents/brain/` artifacts within the local repository.
* **Why:** To ensure **Sovereign Portability** and eliminate vendor dependence.
* **How:** Enforced through **Atomic Git Hygiene**, mandatory Handshake protocols, and the **Ansible Palace SOD/EOD Ritual** (`sod-palace.yml` / `eod-palace.yml`).

```
mindmap
  root((Operational Sovereignty))
    Who
      - Lead Architect
      - AI Cognitive Twin
    What
      - Clean Architecture
      - CRISP Strategy
    When
      - Daily SOD/EOD Rituals
      - Weekly Human Audit
    Where
      - .agents/brain/ artifacts
    Why
      - Sovereign Portability
      - Eliminate Vendor Dependence
    How
      - Atomic Git Hygiene
      - Mandatory Handshake Protocols
```

---

### **2. The CRISP² Methodological Hierarchy**

To maintain structural integrity, the DSOM protocol utilizes a four-level hierarchical process model derived from **CRISP-DM**. Every action within the Project Management Office (PMO) is mapped to a specific level of abstraction to ensure stability and auditability.

| **Level** | **Category** | **Implementation** | **Primary SSoT Artifact** |
| :--- | :--- | :--- | :--- |
| **L1** | **Phases** | Rituals of Transition (SOD, Active Flow, EOD) | `RITUAL-OF-TRANSITION.md` |
| **L2** | **Generic Tasks** | The **CRISP Mandate** (Context, Review, Iteration, Single-purpose, Partnership) | `AI-MASTER-PROTOCOL.md` |
| **L3** | **Specialised Tasks** | **Clean Architecture Layers** (Entities, Use Cases, Adapters, Drivers) | `OPERATIONAL-GUIDE.md` |
| **L4** | **Process Instances** | History of **Mental Anchors** and Logic Breakthroughs | `walkthrough.md` |

```
classDiagram
    class L1_Phases {
        Rituals of Transition
        Artifact: RITUAL-OF-TRANSITION.md
    }
    class L2_GenericTasks {
        CRISP Mandate
        Artifact: AI-MASTER-PROTOCOL.md
    }
    class L3_SpecialisedTasks {
        Clean Architecture Layers
        Artifact: OPERATIONAL-GUIDE.md
    }
    class L4_ProcessInstances {
        Mental Anchors & Breakthroughs
        Artifact: walkthrough.md
    }

    L1_Phases <|-- L2_GenericTasks
    L2_GenericTasks <|-- L3_SpecialisedTasks
    L3_SpecialisedTasks <|-- L4_ProcessInstances
```

---

### **3. Integrating CAPM Knowledge Areas**

DSOM embeds professional project management standards directly into the repository structure.

* **Integration and Scope Governance:** The `AI-MASTER-PROTOCOL.md` acts as the project constitution, while `implementation_plan.md` serves as a non-negotiable roadmap to prevent **"gold plating"** and scope creep.
* **Schedule and Cost Control:** Progress is quantitatively tracked using **Earned Value (EV)** derived from atomic tasks.
  * **Cost Performance Index (CPI):** $CPI = \frac{EV}{AC} \geq 1.0$.
  * **Schedule Performance Index (SPI):** $SPI = \frac{EV}{PV} \geq 1.0$.
* **Quality Management:** Enforced through pre-flight audits and pedagogical standards based on the **Linux Documentation Project (LDP)**.

```
flowchart TD
    A[Integration & Scope Governance] -->|AI-MASTER-PROTOCOL.md| B[Project Constitution]
    A -->|implementation_plan.md| C[Roadmap]

    D[Schedule & Cost Control] --> E[CPI >= 1.0]
    D --> F[SPI >= 1.0]

    G[Quality Management] --> H[Pre-flight Audits]
    G --> I[LDP Pedagogical Standards]

```

---

### **4. The Anatomy of the DSOM Brain (SKMS)**

Within an **ITIL 4 framework**, the `.agents/brain/` directory acts as the **Service Knowledge Management System (SKMS)**. It preserves structured intelligence across five core temporal states:

1. **Eternal (`AI-MASTER-PROTOCOL.md`):** Defines governance, identity, and architectural laws.
2. **Future (`implementation_plan.md`):** Strategic roadmap categorized by individual.
3. **Present (`task.md`):** Shared working memory and granular checklists to solve short-term context loss.
4. **Past (`walkthrough.md`):** Narrative logic breakthroughs and **Mental Anchors** (the exact logical stopping point).
5. **Spatial (`palace_registry.md` + `wings/`):** The **Sovereign Markdown Palace** — knowledge indexed by location (Wing > Room > Closet). Loaded via Section [14] of the SOD manifest.

```
pie showData
    title DSOM Brain Temporal States
    "Eternal (AI-MASTER-PROTOCOL.md)" : 20
    "Future (implementation_plan.md)" : 20
    "Present (task.md)" : 20
    "Past (walkthrough.md)" : 20
    "Spatial (palace_registry.md)" : 20
```

---

### **5. Operational Rituals of the Lifecycle**

Context persistence is managed through three critical rituals:

* **Start-of-Day (SOD) / Reanimation:** A structured bootloader where the human performs an environment audit and the AI performs a **Cognitive Handshake** to synchronise with the project's last recorded intent.
* **Active Flow:** Guided by the **CRISP pillars**, the AI agent acts as a **Senior Architect Twin**, challenging any requests that violate the constitution.
* **End-of-Day (EOD) / Hibernation:** The human and AI define a Mental Anchor and perform a **Sovereign Save** to commit the session’s logical flow to the repository.

```
stateDiagram-v2
    [*] --> SOD
    SOD: Start-of-Day (Reanimation)
    SOD --> ActiveFlow
    ActiveFlow: Guided by CRISP pillars
    ActiveFlow --> EOD
    EOD: End-of-Day (Hibernation)
    EOD --> [*]
```

---

### **6. Technical Enforcement and Automation**

Automation tools anchor AI logic in physical reality:

* **`init-brain.sh`:** Initialises the directory structure and baseline artifacts.
* **`audit-pre-flight.sh`:** Verifies the physical environment and Git state before coding begins.
* **`reanimate.sh`:** Aggregates brain artifacts into a single manifest for AI context injection (Section [14] = Palace Registry).
* **`privacy-guardian.sh`:** Employs a **"Fail-Closed"** principle to scan for sensitive data leaks.
* **`palace-sync.sh`:** Analyses Git history against brain artifacts to generate `palace_update_proposal_YYYY-MM-DD.md` for EOD closet updates.
* **`ansible-playbook sod-palace.yml`:** Automates the full SOD loop (git pull → audit → palace check → reanimate) on T2.
* **`ansible-playbook eod-palace.yml`:** Automates the full EOD loop (validate → palace-sync → commit → push) on T2.

```
flowchart LR
    Init[init-brain.sh] --> Audit[audit-pre-flight.sh]
    Audit --> Reanimate[reanimate.sh]
    Reanimate --> Privacy[privacy-guardian.sh]
    Privacy -->|Fail-Closed| Secure[Secure Repository State]
    Reanimate --> Palace[palace-sync.sh]
    Palace --> Proposal[palace_update_proposal]
    SOD[sod-palace.yml] --> Audit
    EOD[eod-palace.yml] --> Palace
```

---

### **7. Linguistic Sovereignty**

To ensure professional precision and technical rigour, the protocol strictly enforces naming and documentation standards in **UK English** and **DBP-standard Malay (Piawai)**. Regional colloquialisms and Indonesian loanwords are strictly prohibited to maintain architectural clarity.

```
flowchart TD
    A[Documentation Standards] --> B[UK English]
    A --> C[DBP-standard Malay (Piawai)]
    A --> D[No Colloquialisms]
    A --> E[No Indonesian Loanwords]
    B --> F[Architectural Clarity]
    C --> F
    D --> F
    E --> F
```

---

To establish the **Sovereign State of Truth (SSoT)** and enable effective knowledge retrieval for your AI, you should integrate the authoritative references defined within the **DSOM For My AI** protocol. According to the sources, these links serve as the primary external memory and governance guide for the AI.

### **Authoritative References (The SSoT)**

The **AI-MASTER-PROTOCOL.md** mandates that if any task contradicts the core laws or requires deep architectural context, the AI must refer to these specific sources:

* **Primary Repository:** [https://github.com/linuxmalaysia/deep-state-of-mind-for-my-ai](https://github.com/linuxmalaysia/deep-state-of-mind-for-my-ai)
* **Official Documentation (GitBook):** [https://malaysia-open-source-community.gitbook.io/deep-state-of-mind-dsom-protocol-for-my-ai](https://malaysia-open-source-community.gitbook.io/deep-state-of-mind-dsom-protocol-for-my-ai)
* **Philosophical Foundations:** **The Book of Busas** (Buku Busas - Bukan Sekadar Internet Sahaja), which provides the underlying logic for Open Source sovereignty in Malaysia.

### **Operational Standards and Manuals**

For technical execution and documentation structure, the protocol links to the following standards:

* **LDP Standards:** All procedures and "HOWTO" guides must follow the **Linux Documentation Project** structure (Prerequisites, Procedures, Troubleshooting) to ensure community portability.
* **Semantic Integrity:** All notable changes are documented following the **Keep a Changelog** and **Semantic Versioning 2.0.0** standards.

### **Configuration for Your AI**

To ensure your AI agent (the **Cognitive Digital Twin**) can retrieve this information at the start of every session, you should include these links in the **Knowledge Retrieval (L3)** block of your AI's custom instructions or personalization settings.

If you are using specific IDE agents, the sources suggest pointing them to the local versions of these documents within your repository using the following configuration files:

* **Cursor/Windsurf:** Use `.cursorrules` or `.windsurfrules` to point the agent to `@docs/AI-MASTER-PROTOCOL.md` and `@docs/OPERATIONAL-GUIDE.md`.
* **GitHub Copilot:** Utilize `.github/copilot-instructions.md` to enforce these architectural laws and references.

```
flowchart TD
    %% Root
    DSOM[Operational Sovereignty through Metacognitive Governance]

    %% 1. Theoretical Framework
    DSOM --> Sovereignty[1. Theoretical Framework of Operational Sovereignty]
    Sovereignty --> Who[Who: Lead Architect + AI Twin]
    Sovereignty --> What[What: Clean Architecture + CRISP Strategy]
    Sovereignty --> When[When: SOD/EOD + Sunday Audit]
    Sovereignty --> Where[Where: .agents/brain/ artifacts]
    Sovereignty --> Why[Why: Sovereign Portability]
    Sovereignty --> How[How: Git Hygiene + Handshake]

    %% 2. CRISP² Hierarchy
    DSOM --> CRISP[2. CRISP² Methodological Hierarchy]
    CRISP --> L1[L1: Phases → Rituals of Transition]
    CRISP --> L2[L2: Generic Tasks → CRISP Mandate]
    CRISP --> L3[L3: Specialised Tasks → Clean Architecture Layers]
    CRISP --> L4[L4: Process Instances → Mental Anchors]

    %% 3. CAPM Integration
    DSOM --> CAPM[3. Integrating CAPM Knowledge Areas]
    CAPM --> Integration[Integration & Scope Governance]
    CAPM --> Schedule[Schedule & Cost Control (CPI/SPI)]
    CAPM --> Quality[Quality Management → Pre-flight Audits + LDP Standards]

    %% 4. DSOM Brain (SKMS)
    DSOM --> Brain[4. Anatomy of the DSOM Brain (SKMS)]
    Brain --> Eternal[Eternal: AI-MASTER-PROTOCOL.md]
    Brain --> Future[Future: implementation_plan.md]
    Brain --> Present[Present: task.md]
    Brain --> Past[Past: walkthrough.md]

    %% 5. Operational Rituals
    DSOM --> Rituals[5. Operational Rituals of Lifecycle]
    Rituals --> SODR[SOD: Reanimation + Audit]
    Rituals --> Active[Active Flow: CRISP pillars + Twin enforcement]
    Rituals --> EODR[EOD: Hibernation + Sovereign Save]

    %% 6. Technical Enforcement
    DSOM --> Automation[6. Technical Enforcement & Automation]
    Automation --> Init[init-brain.sh]
    Automation --> AuditScript[audit-pre-flight.sh]
    Automation --> ReanimateScript[reanimate.sh]
    Automation --> Privacy[privacy-guardian.sh]

    %% 7. Linguistic Sovereignty
    DSOM --> Language[7. Linguistic Sovereignty]
    Language --> UK[UK English enforced]
    Language --> Malay[DBP-standard Malay (Piawai)]
    Language --> NoColloq[No Colloquialisms]
    Language --> NoLoan[No Indonesian Loanwords]
```

---

## **v6.1 Addendum: Operationalising Sovereignty**

*Added: 2026-03-10 | Protocol Version: v6.1*

Sections 1–7 above establish the metacognitive and governance foundations of DSOM. This addendum records the **strategic evolution** from documentation governance to a **full operational triad** — without repeating the detailed procedures that are authoritatively documented elsewhere.

---

### **8. The Three Non-Negotiable Pillars (v6.0+)**

DSOM sovereignty is now enforced by three pillars + Palace operating as a closed loop:

```
AI Proposes → Git Records → Ansible Executes → AI Verifies → Palace Remembers → EOD Save → (next SOD)
```

| Pillar | Sovereign Law |
|:---|:---|
| **AIOps** | AI is Advisory only. Proposes and verifies. Never executes directly. |
| **GitOps** | Git is the Single Source of Truth. If not committed, it does not exist. |
| **Ansible** | Exclusive OS-level executor. Zero ad-hoc SSH to any node. |
| **Palace** | Spatial memory layer. AI walks Registry at SOD; palace-sync updates closets at EOD. |

> **Full strategic doctrine:** [`docs/GITOPS-AIOPS-ANSIBLE-STRATEGY.md`](GITOPS-AIOPS-ANSIBLE-STRATEGY.md)

---

### **9. The 4-Tier Command Highway**

Code and configuration flow through a defined hierarchy — no tier may be bypassed. The sovereign identity `dsom-admin:2001:2001` is consistent across all tiers derived from this skeleton.

```
T1 (Windows/AI) → git push → T2 (WSL2 dsom-control-almalinux10) → ansible-playbook → T3/T4
```

> **Setup guide:** [`docs/HOWTO-SETUP-WSL-ALMALINUX10.md`](HOWTO-SETUP-WSL-ALMALINUX10.md)
> **Ansible baseline:** [`docs/HOWTO-SETUP-ANSIBLE-BASELINE.md`](HOWTO-SETUP-ANSIBLE-BASELINE.md)
> **Environment map:** [`docs/AI-COGNITIVE-TWIN-PROTOCOL.md`](AI-COGNITIVE-TWIN-PROTOCOL.md) — fill once per project

---

### **10. Ansible: Executable Sovereignty**

From v6.0, Ansible is the exclusive mechanism for production state changes. The `playbooks/dsom/` directory provides programmatic equivalents of all `tools/` scripts — runnable on `localhost` (T2) or any inventory group.

The **Sovereign Identity** (`dsom-admin`, UID 2001) is the standard execution context for all Ansible operations. Idempotency, `ansible-vault`-only secrets, and inventory-driven targeting are non-negotiable.

> **Playbook reference:** [`playbooks/dsom/site.yml`](../playbooks/dsom/site.yml) — master entry point
> **Contributor guide:** [`CONTRIBUTING.md`](../CONTRIBUTING.md) — Ansible standards and hybrid workflow

---

### **11. The Cognitive Continuity Loop**

The v6.1 + Palace v1.0 formalisation of day-to-day AI context continuity: the **Hibernation Notes** mechanism bridges the ephemeral AI session with the permanent Git repository. The **Sovereign Markdown Palace** adds spatial memory that persists across sessions as structured Markdown.

At EOD, the AI exports its full context (instructions, environment, history, Mental Anchor) as a single code block. `palace-sync.sh` (or `eod-palace.yml`) generates `palace_update_proposal_YYYY-MM-DD.md`. At the next SOD, the manifest (including Section [14] Palace Registry) is fed back to any AI to restore full context — making DSOM **AI-model-agnostic** and **spatially aware**.

> **EOD procedure:** [`docs/EOD-RITUAL.md`](EOD-RITUAL.md) — Step 2a (Ansible Palace EOD)
> **SOD procedure:** [`docs/SOD-RITUAL.md`](SOD-RITUAL.md) — Step 1a (Ansible Palace SOD)
> **AI prompts:** [`docs/REANIMATION-PROMPT-TEMPLATE.md`](REANIMATION-PROMPT-TEMPLATE.md) — 5 copy-paste prompts
> **Palace guide:** [`docs/HOWTO-PALACE-ONBOARDING.md`](HOWTO-PALACE-ONBOARDING.md) — closet structure and update guide

---

### **12. Authoritative Document Index (v6.1)**

The following documents constitute the **Sovereign Document Stack** for any v6.1 DSOM project:

| Role | Document |
|:---|:---|
| Constitution | [`AI-MASTER-PROTOCOL.md`](AI-MASTER-PROTOCOL.md) |
| Project Identity | [`AI-COGNITIVE-TWIN-PROTOCOL.md`](AI-COGNITIVE-TWIN-PROTOCOL.md) |
| Session Handover | [`HUMAN-HANDOVER-CONTEXT.md`](HUMAN-HANDOVER-CONTEXT.md) |
| Start of Day | [`SOD-RITUAL.md`](SOD-RITUAL.md) |
| End of Day | [`EOD-RITUAL.md`](EOD-RITUAL.md) |
| AI Prompts | [`REANIMATION-PROMPT-TEMPLATE.md`](REANIMATION-PROMPT-TEMPLATE.md) |
| Transition | [`RITUAL-OF-TRANSITION.md`](RITUAL-OF-TRANSITION.md) |
| Strategy | [`GITOPS-AIOPS-ANSIBLE-STRATEGY.md`](GITOPS-AIOPS-ANSIBLE-STRATEGY.md) |
| Operations | [`OPERATIONAL-GUIDE.md`](OPERATIONAL-GUIDE.md) |
| **Palace Memory** | **[`HOWTO-PALACE-ONBOARDING.md`](HOWTO-PALACE-ONBOARDING.md)** |
| **Palace Build Story** | **[`PALACE-BUILD-STORY.md`](PALACE-BUILD-STORY.md)** |
| Ansible Setup | [`HOWTO-SETUP-ANSIBLE-BASELINE.md`](HOWTO-SETUP-ANSIBLE-BASELINE.md) |
| WSL2 Setup | [`HOWTO-SETUP-WSL-ALMALINUX10.md`](HOWTO-SETUP-WSL-ALMALINUX10.md) |
| Contributors | [`CONTRIBUTING.md`](../CONTRIBUTING.md) |

---

*Standard: DSOM For My AI Protocol v6.1 + Palace v1.0 | Harisfazillah Jamel | LinuxMalaysia*
*Primary Repository: <https://github.com/linuxmalaysia/deep-state-of-mind-for-my-ai>*
*Official Documentation: <https://malaysia-open-source-community.gitbook.io/deep-state-of-mind-dsom-protocol-for-my-ai>*
*Original Manifesto: v5.x (retained intact) | v6.1 Addendum: 2026-03-10 | Palace v1.0 Addendum: 2026-04-08*


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-04*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
