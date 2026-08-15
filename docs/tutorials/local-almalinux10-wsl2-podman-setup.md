---
okf_version: 0.1
type: tutorial
title: "🐧 Local Setup Guide: WSL2 + AlmaLinux 10 + Podman for CmsForNerd"
description: "Step-by-step tutorial to configure Windows Subsystem for Linux 2 with AlmaLinux 10 and Rootless Podman 5+ for CmsForNerd local development."
resource: "file:///docs/tutorials/local-almalinux10-wsl2-podman-setup.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [wsl2, almalinux10, podman, containers, local-environment]
---

# 🐧 Local Setup Guide: WSL2 + AlmaLinux 10 + Podman for CmsForNerd

This tutorial provides comprehensive instructions for setting up an enterprise-grade Linux development environment on Windows 10/11 using **Windows Subsystem for Linux (WSL2)**, **AlmaLinux 10**, and **Rootless Podman 5+**.

By isolating your CmsForNerd development environment inside an AlmaLinux 10 WSL2 instance with Rootless Podman, you achieve full parity with enterprise RHEL-family production deployments while maintaining high filesystem performance.

---

## 🎯 Learning Objectives

In this tutorial, you will:
1. Configure WSL2 hardware resource boundaries using `.wslconfig`.
2. Apply critical Linux kernel sysctl tuning inside WSL2.
3. Install and initialize AlmaLinux 10 as your WSL2 distribution.
4. Install PHP 8.4, Composer, Podman 5+, podman-compose, and Ansible.
5. Deploy CmsForNerd containerized locally using Rootless Podman and verify connectivity.

---

## 📋 Step 1: Hardware & WSL2 Configuration (`.wslconfig`)

To ensure smooth performance when running containerized workloads inside WSL2, configure memory and processor boundaries.

1. In Windows, open File Explorer and navigate to your user home directory (`C:\Users\<YourUsername>\`).
2. Create or edit a file named `.wslconfig`:

```ini
[wsl2]
memory=8GB
processors=4
swap=2GB
localhostForwarding=true
```

3. Restart WSL from Windows PowerShell (Run as Administrator):

```powershell
wsl --shutdown
```

---

## 🛠️ Step 2: Kernel Tuning (sysctl)

Certain high-performance networking and container workloads require proper memory map limits. Configure kernel limits inside WSL2:

1. Check the current virtual memory allocation:
   ```bash
   sysctl vm.max_map_count
   ```
2. Set the parameter temporarily:
   ```bash
   sudo sysctl -w vm.max_map_count=262144
   ```
3. Make the setting permanent across reboots by appending it to `/etc/sysctl.conf`:
   ```bash
   echo "vm.max_map_count=262144" | sudo tee -a /etc/sysctl.conf
   ```

---

## 🚀 Step 3: Install AlmaLinux 10 on WSL2

1. Open Windows PowerShell and list available WSL distributions:
   ```powershell
   wsl --list --online
   ```
2. Install AlmaLinux 10 (or import the official AlmaLinux 10 WSL app/rootfs):
   ```powershell
   wsl --install -d AlmaLinux-10
   ```
3. Launch your AlmaLinux 10 distribution:
   ```powershell
   wsl -d AlmaLinux-10
   ```

---

## 📦 Step 4: Install Packages (PHP 8.4, Podman 5+, Composer, Ansible)

Inside your AlmaLinux 10 terminal, update system packages and install the development toolchain:

1. Update package repositories:
   ```bash
   sudo dnf update -y
   ```
2. Enable EPEL and Remi repositories for PHP 8.4 packages:
   ```bash
   sudo dnf install -y epel-release https://rpms.remirepo.net/enterprise/remi-release-10.rpm
   sudo dnf module reset php -y
   sudo dnf module enable php:remi-8.4 -y
   ```
3. Install PHP 8.4, Podman, podman-compose, Git, Composer, and Ansible:
   ```bash
   sudo dnf install -y \
       php-cli \
       php-mbstring \
       php-xml \
       php-zip \
       php-json \
       php-opcache \
       podman \
       podman-compose \
       git \
       composer \
       ansible
   ```
4. Verify Podman installation and rootless operational mode:
   ```bash
   podman info
   ```

---

## 🐙 Step 5: Clone CmsForNerd to WSL Linux Filesystem

> ⚠️ **Crucial Performance Rule:** Always clone your repository into the native Linux filesystem (`/home/username/code/`) rather than the Windows mount (`/mnt/c/`). Native Linux filesystem I/O is up to 10x faster.

```bash
# Clone directly inside Linux home directory for 10x faster I/O performance
mkdir -p ~/workspace && cd ~/workspace
git clone https://github.com/CMSForNerd/CmsForNerd.git cmsfornerd-lab
cd cmsfornerd-lab
```

---

## 🐳 Step 6: Deploy CmsForNerd with Rootless Podman

CmsForNerd ships with both a `Dockerfile` and a `Containerfile` optimized for rootless container execution.

### Option A: Using `podman build` & `podman run`

1. Build the local container image:
   ```bash
   podman build -t cmsfornerd:almalinux10 -f Containerfile .
   ```
2. Run the container rootlessly, mounting flat-file content and data directories with SELinux relabeling (`:Z`):
   ```bash
   podman run -d \
       --name cmsfornerd-app \
       -p 8080:80 \
       -v $(pwd)/contents:/var/www/html/contents:Z \
       -v $(pwd)/data:/var/www/html/data:Z \
       cmsfornerd:almalinux10
   ```

### Option B: Using `podman-compose`

If you prefer using `podman-compose`:

```bash
podman-compose up -d
```

---

## ✅ Step 7: Verification

1. Verify running containers:
   ```bash
   podman ps
   ```
   *Expected Output:*
   ```text
   CONTAINER ID  IMAGE                     COMMAND                 CREATED        STATUS        PORTS                 NAMES
   a1b2c3d4e5f6  localhost/cmsfornerd:local  "httpd-foreground" ...  10 seconds ago Up 10 seconds 0.00.0.0:8080->80/tcp cmsfornerd-app
   ```

2. Test local endpoint using `curl`:
   ```bash
   curl -I http://localhost:8080/
   ```
   *Expected Response:*
   ```http
   HTTP/1.1 200 OK
   X-Content-Type-Options: nosniff
   X-Frame-Options: SAMEORIGIN
   X-XSS-Protection: 1; mode=block
   ```

3. Access from Windows Web Browser:
   Open your browser on Windows and visit:
   * **Home:** `http://localhost:8080/`
   * **User Manual:** `http://localhost:8080/user-manual.php`

---

## 🧹 Teardown

To stop and remove the local Podman container:

```bash
podman stop cmsfornerd-app
podman rm cmsfornerd-app
```

---

*Deep State of Mind (DSOM) For My AI Protocol | CmsForNerd WSL2 AlmaLinux 10 Container Guide | Harisfazillah Jamel (LinuxMalaysia)*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
