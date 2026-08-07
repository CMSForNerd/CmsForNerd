---
okf_version: 0.1
type: documentation
title: "🔄 HOWTO: Migrate Existing DSOM to Sovereign Markdown Palace"
description: "OKF-compliant documentation for HOWTO-MIGRATE-TO-PALACE.md."
resource: "file:///docs/HOWTO-MIGRATE-TO-PALACE.md"
timestamp: 2026-07-04T09:40:04Z
topics: [docs, migrate, palace, existing, dsom]
---
# 🔄 HOWTO: Migrate Existing DSOM to Sovereign Markdown Palace

> **Who this document is for:** Anyone already using DSOM (any version) who wants to upgrade to the Palace-enabled system (v10.0.0-palace and later).

**Migration Target:** Palace v1.0 | **DSOM Protocol:** v6.1

---

## ⚠️ Before You Start

> [!IMPORTANT]
> This migration adds new files and upgrades existing scripts. It does **not** delete or break anything already working. Your existing `walkthrough.md`, `task.md`, and tool scripts remain intact.

**Time required:** 15–30 minutes for a typical DSOM project.

**Estimated git commits:** 3–5 commits.

---

## 🗺️ What Changes

| Component | Before (Old DSOM) | After (Palace v1.0) |
|---|---|---|
| Brain structure | `task.md`, `walkthrough.md`, `implementation_plan.md` | + `palace_registry.md` + `wings/` hierarchy |
| Memory retrieval | Linear scan of `walkthrough.md` | Walk Palace → Room → Closet → Drawer if needed |
| EOD ritual | Commit + push | + Palace Sync (via `eod-palace.yml` or `hibernation.sh` v2.1) |
| SOD manifest | 13 sections | + Section [14]: Palace Registry (via `sod-palace.yml` or `reanimate.sh` v2.2) |
| `hibernation.sh` | v2.0 | v2.1 (Palace Sync step) |
| `reanimate.sh` | v2.1 | v2.2 (Section [14]) |
| `hibernation.ps1` | v2.0 | v2.1 (Palace Sync step) |
| `reanimate.ps1` | v2.0 | v2.1 (Section [14]) |

---

## 📋 Migration Checklist

### Phase 1: Pull Latest Tools

```bash
# Linux / WSL2
git pull origin main

# Windows
git pull origin main
```

Confirm you have at minimum tag `v10.0.0-palace`:

```bash
git tag | grep palace
# Expected: v10.0.0-palace
```

---

### Phase 2: Initialise the Palace Structure

Run the Palace initialisation script to create the Wing/Hall/Room skeleton:

```bash
# Linux / WSL2
bash tools/palace-sync.sh --init-only

# Windows (PowerShell)
.\tools\palace-sync.ps1 -InitOnly
```

This creates the directory structure under `.agents/brain/wings/` without touching any existing files.

**If the script is not available yet in your clone**, create manually:

```bash
mkdir -p .agents/brain/wings/wing_dsom_core/{hall_facts,hall_events,hall_preferences,hall_discoveries}
mkdir -p .agents/brain/wings/wing_dsom_core/hall_facts/{room_clean_architecture,room_crisp_strategy,room_dsom_protocol,room_tooling}
mkdir -p .agents/brain/wings/wing_dsom_core/hall_events/{room_sovereign_fabric_v9_8,room_brain_artifacts,room_ledger}
mkdir -p .agents/brain/wings/wing_dsom_core/hall_discoveries/room_uncategorised
```

---

### Phase 3: Run the Backfill

This is the key step. It reads your **entire git history** and generates a `palace_update_proposal_YYYY-MM-DD.md` that maps every commit to its target Palace Room.

```bash
# Linux / WSL2
bash tools/palace-sync.sh --backfill

# Windows (PowerShell)
.\tools\palace-sync.ps1 -Backfill
```

The proposal file is saved to `.agents/brain/palace_update_proposal_YYYY-MM-DD.md`.

---

### Phase 4: Create the Palace Registry

Create `.agents/brain/palace_registry.md`. You can copy the template from the `docs/agent-configs/` dir if available, or adapt the existing one:

```bash
# If you have the reference repo available:
cp /path/to/dsom-reference/.agents/brain/palace_registry.md .agents/brain/palace_registry.md
# Then edit to match YOUR project's Wing/Hall structure
```

The registry should list:

- All Wings
- All Halls within each Wing
- All Rooms with their paths and purpose

See [`.agents/brain/palace_registry.md`](../.agents/brain/palace_registry.md) in this reference repo for the full format.

---

### Phase 5: Populate Closets from the Proposal

Open your generated `palace_update_proposal_YYYY-MM-DD.md` and work through it section by section.

For each Room listed in the proposal:

1. Create `closet.md` in the room directory.
2. Copy the template below.
3. Fill in the content from the proposal for that Room.
4. Add links to the relevant `walkthrough.md` sections or source files.

**Closet template:**

```markdown
# 🚪 Closet: Room [NAME] ([Short Description])

Brief description of what this Room stores.

---

## [Section Heading]

Your distilled knowledge here.

## 📅 Evolution Timeline

| Date | What Changed |
|---|---|
| YYYY-MM-DD | Event |

---
## 🔗 Retrieval Reference
- [walkthrough.md](../../../walkthrough.md)

---
*Last Refined: YYYY-MM-DD | Hall: hall_X | Wing: wing_Y*
```

---

### Phase 6: Commit the Palace

```bash
git add .agents/brain/
git commit -m "feat(palace): initialise Sovereign Markdown Palace v1.0 — [N] Rooms backfilled"
git push origin main
```

---

### Phase 7: Tag the Milestone

```bash
git tag -a vX.X.X-palace -m "feat: Sovereign Markdown Palace v1.0 — spatial memory initialised"
git push origin vX.X.X-palace
```

---

### Phase 8: Sync the Other Machine

If you work across multiple machines (Windows + Linux):

```bash
# On the second machine
git pull --rebase origin main
```

---

### Phase 9: Verify

Run a full SOD sequence and confirm Section [14] appears in your manifest:

```bash
# Recommended on T2 (WSL2 / Linux)
bash tools/sod-palace.sh  # (Windows: .\tools\sod-palace.ps1)

# Or manual (Linux/Windows)
bash tools/reanimate.sh | grep -A 5 "\[14\]"
```

Expected output:

```
### [14] PALACE REGISTRY (Spatial Knowledge Map)
# 🏛️ Palace Registry: Sovereign Retrieval Map
...
```

Run a test EOD to confirm Palace Sync runs automatically:

```bash
# Recommended on T2 (WSL2 / Linux)
bash tools/eod-palace.sh  # (Windows: .\tools\eod-palace.ps1)

# Or manual (Linux/Windows)
bash tools/hibernation.sh
# Should show: [Palace Sync] Running Spatial Reflection...
```

---

## 🧩 Verify Your Tool Versions

After pulling from the reference repo, confirm you have the right script versions:

```bash
grep "^VERSION\|^v2\." tools/hibernation.sh tools/reanimate.sh tools/palace-sync.sh
```

Expected:

| Script | Version |
|---|---|
| `hibernation.sh` | v2.1 |
| `reanimate.sh` | v2.2 |
| `palace-sync.sh` | v1.0 |

```powershell
# Windows
Select-String -Path tools\hibernation.ps1,tools\reanimate.ps1,tools\palace-sync.ps1 -Pattern '^\$VERSION'
```

Expected:

| Script | Version |
|---|---|
| `hibernation.ps1` | v2.1 |
| `reanimate.ps1` | v2.1 |
| `palace-sync.ps1` | v1.0 |

---

## ❓ FAQ

**Q: Do I need to re-read all my old walkthrough.md entries?**
No. The backfill reads git history, not the walkthrough. Your walkthrough is not touched.

**Q: My closets are empty / sparse — is that OK?**
Yes. Better to have a sparse accurate closet than a full inaccurate one. Add more as sessions accumulate. The EOD `palace-sync` keeps them growing automatically via the proposal mechanism.

**Q: Can I skip the backfill?**
Yes, for the short term. The Palace will still work — it just won't have historical context in the Rooms. Run `--backfill` when you have time.

**Q: I'm using a custom brain structure — what do I do?**
Adapt the Wing/Hall/Room hierarchy to your project. Create custom Rooms, register them in the registry. The structure is fully flexible — only the `closet.md` filename is mandated.

**Q: My project has no Ansible. Do I need `room_sovereign_fabric`?**
No. Only create Rooms relevant to your project. For a pure software dev project, you might only need `room_tooling`, `room_architecture`, and `room_ledger`.

---

## 🔗 Related Documents

| Document | Purpose |
|---|---|
| [`docs/HOWTO-PALACE-ONBOARDING.md`](HOWTO-PALACE-ONBOARDING.md) | First-time guide — what the Palace is |
| [`docs/PALACE-BUILD-STORY.md`](PALACE-BUILD-STORY.md) | How and why the Palace was built |
| [`docs/DIGITAL-SOVEREIGNTY-OPERATIONAL-MODEL-PALACE.md`](DIGITAL-SOVEREIGNTY-OPERATIONAL-MODEL-PALACE.md) | Full Palace Protocol specification |
| [`.agents/brain/palace_registry.md`](../.agents/brain/palace_registry.md) | Live Room index |

---
*Document created: 2026-04-08 | Palace v1.0 | DSOM Protocol v6.1*
*Author: Harisfazillah Jamel | Partner: Google Antigravity*


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-04*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
