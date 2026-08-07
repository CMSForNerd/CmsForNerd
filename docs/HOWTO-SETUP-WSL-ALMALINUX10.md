---
okf_version: 0.1
type: documentation
title: "🐧 HOWTO: Setup AlmaLinux 10 WSL2 as DSOM Ansible Control Node"
description: "OKF-compliant documentation for HOWTO-SETUP-WSL-ALMALINUX10.md."
resource: "file:///docs/HOWTO-SETUP-WSL-ALMALINUX10.md"
timestamp: 2026-07-04T09:40:04Z
topics: [docs, wsl, almalinux, dsom, ansible]
---
# 🐧 HOWTO: Setup AlmaLinux 10 WSL2 as DSOM Ansible Control Node

> **Tier 2 (Dev Bridge): AlmaLinux 10 → `dsom-control-almalinux10`**
> **Purpose: Ansible Control Node for DSOM projects on Windows 11**

---

## Prerequisites

| Requirement | Details |
|:---|:---|
| Windows 11 | Build 22000+ |
| WSL2 feature | Enabled (script handles this) |
| Disk space | ~2GB for AlmaLinux 10 rootfs |
| Internet | Required for download and DNF updates |

---

## 🏗️ Architecture Position

```
[ Tier 1: Windows 11 + PowerShell ]  ← You work here
        ↓  wsl -d dsom-control-almalinux10
[ Tier 2: AlmaLinux 10 WSL2        ]  ← This setup guide
  Identity: dsom-admin (UID 2001)
  Tools:    Ansible, Git, rsync
        ↓  SSH via ansible-playbook
[ Tier 3/4: Target Nodes            ]  ← Remote Linux servers
```

---

## Phase 1: Enable WSL2 (One-Time Setup)

Open **PowerShell as Administrator** and run:

```powershell
# Enable WSL and Virtual Machine Platform
dism.exe /online /enable-feature /featurename:Microsoft-Windows-Subsystem-Linux /all /norestart
dism.exe /online /enable-feature /featurename:VirtualMachinePlatform /all /norestart

# Restart Windows, then continue:
wsl --set-default-version 2
wsl --update
```

> [!NOTE]
> A restart is required after enabling features. After restart, continue with Phase 2.

---

## Phase 2: Run the Automated Setup Script

From your Windows Terminal (normal PowerShell, **no need for Admin** after Phase 1):

```powershell
cd D:\Users\LinuxMalaysia\Projects\deep-state-of-mind-for-my-ai

# Run the automated setup
.\tools\setup-wsl-almalinux10.ps1
```

**What the script does:**

1. Downloads AlmaLinux 10 rootfs from the official [AlmaLinux WSL GitHub releases](https://github.com/AlmaLinux/wsl-images/releases)
2. Imports it as `dsom-control-almalinux10` to `C:\WSL\dsom-control-almalinux10\`
3. Runs the bootstrap script inside AlmaLinux to configure the Control Node

---

## Phase 3: Bootstrap Inside AlmaLinux 10

The setup script runs this automatically, but you can also run it manually:

```powershell
wsl -d dsom-control-almalinux10 -u root -- bash /mnt/d/Users/LinuxMalaysia/Projects/deep-state-of-mind-for-my-ai/tools/setup-dsom-control-node.sh
```

**What the bootstrap script does:**

- Updates all AlmaLinux 10 packages (`dnf update`)
- Installs: Ansible, Git, rsync, Python3, openssh-clients
- Creates `dsom-admin` user with **UID 2001** (DSOM identity standard)
- Adds `dsom-admin` to `wheel` with passwordless sudo
- Generates SSH key (`~/.ssh/id_ed25519`) for connecting to target nodes
- Configures `/etc/wsl.conf`:
  - `default=dsom-admin` (auto-login as correct user)
  - `systemd=true` (for managing systemd services)
  - `appendWindowsPath=false` (clean Linux PATH)

---

## Phase 4: Verify and Restart

After bootstrap, restart the WSL instance to apply `wsl.conf`:

```powershell
# Restart the WSL instance
wsl --terminate dsom-control-almalinux10

# Launch (will auto-login as dsom-admin)
wsl -d dsom-control-almalinux10
```

Inside AlmaLinux 10, verify:

```bash
# Should show dsom-admin (UID 2001)
id

# Should show ansible version
ansible --version

# Should return pong
ansible localhost -m ping
```

**Expected output:**

```
uid=2001(dsom-admin) gid=2001(dsom-admin) groups=2001(dsom-admin),10(wheel)

ansible [core 2.15+]
  ...

localhost | SUCCESS => { "ping": "pong" }
```

---

## Phase 5: Connect to Remote Target Nodes

```bash
# Inside dsom-control-almalinux10 as dsom-admin

# Copy your SSH public key to each target node
ssh-copy-id -i ~/.ssh/id_ed25519.pub [USER]@[TARGET_IP]

# Test connectivity
ansible all -m ping -i /mnt/d/Users/LinuxMalaysia/Projects/deep-state-of-mind-for-my-ai/inventory/hosts.yml
```

---

## Phase 6: Run DSOM Ansible Playbooks

```bash
# From inside dsom-control-almalinux10, cd to your project mount:
cd /mnt/d/Users/LinuxMalaysia/Projects/deep-state-of-mind-for-my-ai

# Run DSOM pre-flight audit on localhost
ansible-playbook playbooks/dsom/audit-preflight.yml -i localhost,

# Run the DSOM Palace SOD Ritual (Start of Day)
bash tools/sod-palace.sh  # (Windows: .\tools\sod-palace.ps1)
```

---

## The `/etc/wsl.conf` Reference

```ini
[user]
default=dsom-admin        # Auto-login as dsom-admin (UID 2001)

[boot]
systemd=true              # Enable systemd (required for service management)

[interop]
appendWindowsPath=false   # Keep Linux PATH clean

[automount]
enabled=true
root=/mnt/
options="metadata,umask=77,fmask=11"
```

---

## Troubleshooting

| Problem | Solution |
|:---|:---|
| `wsl --import` fails | Ensure you have ~2GB free disk space in `C:\WSL\` |
| AlmaLinux 10 download URL fails | Download manually from [github.com/AlmaLinux/wsl-images/releases](https://github.com/AlmaLinux/wsl-images/releases) and set `$TarballPath` manually |
| `ansible` not found after bootstrap | Run: `pip3 install ansible` inside the WSL as root |
| wsl.conf not applied | Run: `wsl --terminate dsom-control-almalinux10` and relaunch |
| SSH key not accepted by target | Verify `~/.ssh/authorized_keys` on target has your public key |

---

## Related Documents

- [`docs/HOWTO-SETUP-ANSIBLE-BASELINE.md`](HOWTO-SETUP-ANSIBLE-BASELINE.md) — Ansible project directory setup
- [`docs/GITOPS-AIOPS-ANSIBLE-STRATEGY.md`](GITOPS-AIOPS-ANSIBLE-STRATEGY.md) — Three-pillar strategy
- [`docs/ANSIBLE-CONTROL-NODE-PROTOCOL.md`](ANSIBLE-CONTROL-NODE-PROTOCOL.md) — Control node operating standards
- [`playbooks/dsom/`](../playbooks/dsom/) — DSOM operational playbooks

---

*Standard: DSOM Protocol v6.1 + Palace v1.0 | Harisfazillah Jamel*
**WSL Instance:** `dsom-control-almalinux10` | **Identity:** `dsom-admin` (UID 2001)
**Last Updated:** 2026-04-08 | **Version:** v1.1


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-04*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
