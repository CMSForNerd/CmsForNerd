---
okf_version: 0.1
type: workflow
title: Subagent Orchestration (Worktree Isolation)
description: Procedural governance for delegating tasks to autonomous subagents using Git Worktree Isolation.
tags: [workflows, subagents, gitops, isolation]
owner: Lead Architect
timestamp: 2026-08-07T05:39:28Z
topics: [workflows, subagent, orchestration, workflow, trigger]
---
# 🤖 Workflow: Subagent Orchestration

This automated guide defines the strict procedural governance for a Lead Architect Agent (or human) when delegating tasks to autonomous subagents.

To prevent **Silent Subagent Merge Conflicts** and memory corruption in the Palace (`.agents/brain/`), we mandate **Git Worktree Isolation**. Subagents must never push directly to the `main` branch.

## 1. Trigger Conditions
Use this workflow whenever a massive refactor, parallel research task, or multi-component deployment requires spinning up an autonomous subagent.

## 2. Execution Steps

### Step 2.1: Isolate the Worktree
Before invoking the subagent, you MUST isolate their execution environment. The preferred method is creating a new git branch:

```bash
# Example branching strategy for a subagent
git checkout -b subagent/feature-X
```

### Step 2.2: Invoke the Subagent
Pass the explicit contextual task to the subagent. Inform them that they are operating on an isolated branch and must commit their work locally.

### Step 2.3: Subagent Execution
The subagent will:
1. Initialize the DSOM Handshake (reading the Palace).
2. Execute the delegated task.
3. Update their localized `.agents/brain/` artifacts.
4. Execute `git add .` and `git commit -m "feat(subagent): completed task X"`.
5. Send a message back to the Lead Architect indicating task completion.

### Step 2.4: Review and Consensus Merge
The Lead Architect Agent regains control. You MUST:
1. Review the subagent's changes (e.g., via `git diff main..subagent/feature-X`).
2. Verify that no critical `palace_registry.md` corruption occurred.
3. If approved, merge the branch back into the primary memory trunk:

```bash
git checkout main
git merge subagent/feature-X
git push origin main
```

## 3. Handling Conflicts
If the Lead Architect and the subagent both modified the same `closet.md` file while operating asynchronously, the Git merge will throw a conflict. The Lead Architect MUST manually resolve this conflict before committing, ensuring the unified cognitive memory remains logically sound.


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-06-19*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
