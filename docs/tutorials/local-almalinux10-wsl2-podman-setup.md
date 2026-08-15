---
okf_version: 0.1
type: tutorial
title: "🐧 WSL2 AlmaLinux 10 Rootless Podman Environment Setup"
description: "Tutorial for setting up an enterprise Linux container environment on Windows 11 using WSL2, AlmaLinux 10, sysctl tuning, and Podman 5."
resource: "file:///docs/tutorials/local-almalinux10-wsl2-podman-setup.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [wsl2, almalinux10, podman, containers, local-environment]
---

# 🐧 WSL2 AlmaLinux 10 Rootless Podman Environment Setup

This tutorial demonstrates how to build a high-performance Linux container sandbox on Windows 10/11 using **WSL2**, **AlmaLinux 10**, and **Rootless Podman 5+**.

---

## ⚙️ Configuration & Deployment Sequence

### 1. Boundary Limits (`%USERPROFILE%\.wslconfig`)
```ini
[wsl2]
memory=8GB
processors=4
swap=2GB
localhostForwarding=true
```

### 2. Kernel Memory Map Tuning
```bash
sudo sysctl -w vm.max_map_count=262144
```

### 3. AlmaLinux 10 Initialization & Toolchain
```bash
wsl --install -d AlmaLinux-10
wsl -d AlmaLinux-10
sudo dnf update -y && sudo dnf install -y podman podman-compose git composer php-cli php-mbstring php-xml php-zip
```

### 4. Container Execution with SELinux Relabeling (`:Z`)
```bash
mkdir -p ~/workspace && cd ~/workspace
git clone https://github.com/CMSForNerd/CmsForNerd.git cmsfornerd-app
cd cmsfornerd-app
podman build -t cmsfornerd-local:v4.3 -f Containerfile .
podman run -d --name cmsfornerd-container -p 8080:80 -v $(pwd)/contents:/var/www/html/contents:Z cmsfornerd-local:v4.3
```

Confirm container status via `podman ps` and access `http://localhost:8080/user-manual.php`.

---

*CmsForNerd WSL2 AlmaLinux 10 Podman Setup Guide | DSOM Protocol 2026 | Harisfazillah Jamel (LinuxMalaysia)*
