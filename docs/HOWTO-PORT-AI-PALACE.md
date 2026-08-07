---
okf_version: 0.1
type: documentation
title: "🚀 HOWTO: Port the AI Spatial Memory Palace to Other Projects"
description: "OKF-compliant documentation for HOWTO-PORT-AI-PALACE.md."
resource: "file:///docs/HOWTO-PORT-AI-PALACE.md"
timestamp: 2026-07-04T09:40:04Z
topics: [docs, port, palace, spatial, memory]
---
# 🚀 HOWTO: Port the AI Spatial Memory Palace to Other Projects

**Target Audience:** Development Teams, AI Engineers, and Lead Architects looking to solve "AI Context Decay" (Amnesia) in their own repositories.

---

## 🧠 1. The Core Philosophy: Solving "AI Amnesia"

When you use an AI Agent (like Gemini, Claude, or Cursor) to build software over weeks or months, the conversation history grows too large. Eventually, the AI exceeds its **Context Window** and begins to forget earlier architectural decisions, rules, and configurations. This is called **Context Decay**.

To permanently cure this, the DSOM Protocol utilizes the **Spatial Memory Palace**.

Instead of relying on the AI's ephemeral chat history, we externalise the AI's memory into a strictly structured physical folder system (`.agents/brain/`). The AI writes down what it learns into highly distilled `closet.md` files. When you start a new session, the AI reads these tiny files to instantly reload months of context without hallucinating or exceeding token limits.

> [!NOTE]
> **Public vs. Air-Gapped Repositories:** The core DSOM framework (like this repository) is designed for public adoption, learning, and open-source contribution using standard public Git providers (e.g., GitHub, GitLab). However, if you are porting this Palace to a project that handles classified intelligence, proprietary code, or sensitive data, you should enforce **Absolute Air-Gapped GitOps** by utilizing self-hosted, localized Git backends (like Gitea) and internal CI/CD schedulers.

---

## 📦 2. The "Brain Transplant" Checklist

To adopt this system into a new or existing repository, you must copy a specific set of tools, rules, and skills from the DSOM Core repository into your project root.

### A. The Structural Scaffolding (Scripts)
These bash/PowerShell scripts automate the creation and maintenance of the AI Brain. Copy them into your project's `tools/` directory:
- `tools/init-brain.sh` (or `.ps1`) - Creates the `.agents/brain/` skeleton and default artifacts.
- `tools/sod-palace.sh` (or `.ps1`) - The **Start-of-Day (SOD)** Reanimation script.
- `tools/eod-palace.sh` (or `.ps1`) - The **End-of-Day (EOD)** Hibernation script.

### B. The Cognitive Rules (Documentation)
These documents explain the operational rules to both the Human and the AI. Copy them into your `docs/` directory:
- `docs/HOWTO-PALACE-ONBOARDING.md` - Instructs a new AI on how to "walk" the Palace.
- `docs/AI-MASTER-PROTOCOL.md` - The governance laws restricting how the AI operates (e.g., GitOps-first, no blind executions).
- `docs/AI-COGNITIVE-LOGGING-PROTOCOL.md` - Instructs the AI on how to link its raw conversation transcripts into the Palace for forensic auditing.

### C. The AI Skills (Optional, but highly recommended)
To give your AI native abilities to parse logs or sync the Palace, copy these folders into your `.agents/skills/` directory:
- `.agents/skills/forensic-log-audit/`
- `.agents/skills/eod-palace-sync/`
- `.agents/skills/sod-palace-sync/`

---

## 🏗️ 3. How to Rebuild and Initialize

Once you have copied the necessary files into your repository, follow these steps to bootstrap the AI's memory.

### Step 1: Initialize the Brain Directory
Run the initialization script from your project root:
```bash
bash tools/init-brain.sh
```
*(This will safely create the `.agents/brain/` folder, along with the foundational `task.md`, `walkthrough.md`, and `implementation_plan.md` artifacts without overwriting existing work.)*

### Step 2: Define the Palace Registry (The Map)
Create a file at `.agents/brain/palace_registry.md`. This is the map the AI will read when it boots up. You must define "Wings" (your project's domains) and "Halls" (the categories of memory).

**Example Template:**
```markdown
# 🗺️ Palace Registry

## 🏛️ Wings
*   **wing_frontend:** All UI/UX memory.
*   **wing_backend:** All API and database memory.

## 🚪 Active Closets
*   `wings/wing_backend/hall_facts/room_architecture/closet.md` - Describes our DB schema.
*   `wings/wing_frontend/hall_events/room_sprints/closet.md` - Describes what we built in Sprint 1.
```

### Step 3: Populate the Closets
Create the folders and `closet.md` files you defined in your registry. Write a brief, 5-10 line summary of the current state of your project inside them so the AI has a starting point.

### Step 4: The First Reanimation (Start of Day)
Open a new chat with your AI Agent and tell it to boot up:
> *"Initialise the Palace Protocol. Read `.agents/brain/palace_registry.md` to map the workspace. Then, read the closets relevant to our backend architecture so we can begin work."*

### Step 5: The EOD Hibernation
At the end of your coding session, before you close the window, instruct the AI:
> *"We are done for the day. Please summarise our work and update the relevant `closet.md` files in the Palace. Record your Conversation ID in the closet header as per the Cognitive Logging Protocol."*

Commit the updated `.agents` folder to Git. Your AI's memory is now permanently saved, version-controlled, and completely immune to Context Decay!


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-04*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
