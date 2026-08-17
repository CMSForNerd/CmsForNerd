---
okf_version: 0.1
type: tutorial
title: "🐧 Local Environment Tutorial: WSL2 + AlmaLinux 10 + Podman"
description: "Step-by-step tutorial to configure Windows Subsystem for Linux 2 with AlmaLinux 10 and Rootless Podman 5+ for CmsForNerd local container development."
resource: "file:///docs/tutorials/local-almalinux10-wsl2-podman-setup.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [wsl2, almalinux10, podman, containers, local-environment]
---

# 🐧 Local Environment Tutorial: WSL2 + AlmaLinux 10 + Podman

This tutorial provides step-by-step instructions for preparing an enterprise-grade Linux development environment on Windows 10/11 using **Windows Subsystem for Linux (WSL2)**, **AlmaLinux 10**, and **Rootless Podman 5+**.

---

## 🎯 Onboarding Procedure

### Step 1: WSL2 Boundary Resource Configuration
In your Windows user profile folder (`%USERPROFILE%\.wslconfig`), define hardware limits:
```ini
[wsl2]
memory=8GB
processors=4
swap=2GB
localhostForwarding=true
```
Restart WSL from PowerShell (Admin): `wsl --shutdown`

### Step 2: Linux Kernel Tuning
Inside WSL2, ensure `vm.max_map_count` is configured without appending duplicate entries:
```bash
sudo sysctl -w vm.max_map_count=262144
grep -q "^vm.max_map_count=262144" /etc/sysctl.conf || echo "vm.max_map_count=262144" | sudo tee -a /etc/sysctl.conf
sudo sysctl -p
```

### Step 3: AlmaLinux 10 Distribution & Toolchain Installation
```powershell
wsl --install -d AlmaLinux-10
wsl -d AlmaLinux-10
```
Inside AlmaLinux 10, enable Remi repositories and install dependencies:
```bash
sudo dnf install -y epel-release https://rpms.remirepo.net/enterprise/remi-release-10.rpm
sudo dnf module reset php -y && sudo dnf module enable php:remi-8.4 -y
sudo dnf install -y podman podman-compose git composer php-cli php-mbstring php-xml php-zip
```

### Step 4: Native Linux Workspace & Podman Execution
Clone into the native Linux filesystem for maximum I/O performance:
```bash
mkdir -p ~/dev-space && cd ~/dev-space
git clone https://github.com/CMSForNerd/CmsForNerd.git cmsfornerd-container
cd cmsfornerd-container
podman build -t cmsfornerd-local:v4.3 -f Containerfile .
podman run -d --replace --name cmsfornerd-app -p 8080:80 -v $(pwd)/contents:/var/www/html/contents:Z cmsfornerd-local:v4.3
```

Verify container status with `podman ps` and access `http://localhost:8080/user-manual.php`.

---

*CmsForNerd WSL2 AlmaLinux 10 Podman Setup Guide | DSOM Protocol 2026 | Harisfazillah Jamel (LinuxMalaysia)*
