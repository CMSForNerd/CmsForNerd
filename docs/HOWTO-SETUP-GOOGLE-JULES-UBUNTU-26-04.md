---
okf_version: 0.1
type: documentation
title: "Google Jules Setup on Ubuntu 26.04 with Podman 5+"
description: "Comprehensive setup and configuration guide for Google Jules with Podman 5 on Ubuntu 26.04."
resource: "file:///docs/HOWTO-SETUP-GOOGLE-JULES-UBUNTU-26-04.md"
timestamp: "2026-07-27T12:00:00Z"
topics: [docs, google, jules, ubuntu, podman]
---
# 🤖 Google Jules Setup Guide on Ubuntu 26.04 with Podman 5+

*Planned Handover Date: 2026-07-27*

This document serves as the official handbook for setting up the Google Jules asynchronous coding agent on an unprivileged, hardened
**Ubuntu 26.04 (Resolute Raccoon)** environment utilising native **Podman version 5 and above**.

---

## 1. Operating System Compatibility & Rationale

### Does Google Jules support Ubuntu 26.04?
Yes, Google Jules fully supports running and executing automated development pipelines on Ubuntu 26.04. Because Jules operates via
asynchronous workspace VMs and secure containers, targeting Ubuntu 26.04 as the underlying operating system provides optimal compatibility.

### Why Ubuntu 26.04 is the Required Standard
- **Native Podman 5.x Packages**: Ubuntu 26.04 is the first stable LTS-ready OS release to package **Podman version 5.x** natively within
  its official stable APT repositories.
- **Avoids Unstable PPAs**: Older LTS releases (like Ubuntu 24.04 or 22.04) natively bundle older, deprecated versions (Podman 4.x or 3.x).
  Running Podman 5 on those releases requires third-party repositories, experimental PPAs, or complex compilation from source, which
  compromises digital sovereignty and environment stability.
- **Default Netavark & Aardvark-DNS**: Podman 5 fully transitions the rootless network backend to `netavark` and `aardvark-dns`, completely
  retiring CNI plugins. Ubuntu 26.04 provides this stabilised networking configuration out of the box.

---

## 2. Infrastructure Setup & Dependencies

To provision an Ubuntu 26.04 server for Google Jules with Podman 5+, run the following setup steps as a privileged administrator:

### Step 2.1: Update APT Repositories
Update the local package manager registry to fetch the official stable releases:
```bash
sudo apt update
```

### Step 2.2: Install Podman 5 and Supporting Tooling
Ubuntu 26.04's package manager natively resolves all dependencies of Podman 5. Install the core suite and unprivileged helper tools:
```bash
sudo apt install -y podman podman-compose git uidmap dbus-user-session catatonit
```

### Step 2.3: Verify Podman Version
Ensure the installed Podman version is indeed 5.0.0 or higher:
```bash
podman --version
# Expected Output: podman version 5.x.y
```

---

## 3. Configuring Rootless Mode for Google Jules

For security, Google Jules and the target applications should execute within an unprivileged user space.

### Step 3.1: Configure User GID/UID Maps
Rootless containers map internal container IDs to a sub-range of IDs on the host. Ensure `/etc/subuid` and `/etc/subgid` are configured:
```bash
# Verify the configuration for your user (e.g., dsom-admin)
grep dsom-admin /etc/subuid
grep dsom-admin /etc/subgid
```

If missing, configure them using:
```bash
sudo usermod --add-subuids 100000-165535 --add-subgids 100000-165535 dsom-admin
```

### Step 3.2: Allow Privileged Port Binding
By default, unprivileged users cannot bind to ports below 1024. Configure `sysctl` to allow unprivileged ports down to port 80:
```bash
sudo sysctl -w net.ipv4.ip_unprivileged_port_start=80
```
To persist this across system reboots, write to `/etc/sysctl.d/99-podman-ports.conf`:
```text
net.ipv4.ip_unprivileged_port_start=80
```

### Step 3.3: Enable Lingering for Asynchronous Persistence
To prevent systemd from terminating Jules processes and container networks when the user session closes:
```bash
sudo loginctl enable-linger dsom-admin
```

---

## 4. Migrating from Docker to Podman Natively

To enforce a pure, Docker-independent Podman 5 environment, all legacy configurations and commands have been migrated to native Podman
standards:

### 4.1. Migration of Dockerfile to Containerfile
Podman natively champions `Containerfile` as the standard definition for building container images.
- All Dockerfiles inside `playbooks/roles/` have been renamed and migrated to `Containerfile` or `Containerfile.j2`.
- Building images natively:
  ```bash
  podman build -t localhost/cmsfornerd-php:8.5 -f Containerfile .
  ```

### 4.2. Migration of Docker-Compose to Podman-Compose
To avoid Docker-specific namespaces, all compose configurations use the standard `compose.yml` (replacing legacy `docker-compose.yml`):
- Start the multi-container stack natively:
  ```bash
  podman-compose -f compose.yml -p cmsfornerd up -d
  ```

### 4.3. High-Performance Podman 5 Systemd Integration
Under Podman 5+, systemd handles the container lifecycles seamlessly without running a central root daemon.
A user-level systemd unit file is generated for automated stack recovery on system boot:
```text
/home/dsom-admin/.config/systemd/user/cmsfornerd-pod.service
```

Use standard systemctl flags with the `--user` scope to control the stack:
```bash
# Check status
systemctl --user status cmsfornerd-pod

# Start / Stop stack
systemctl --user start cmsfornerd-pod
systemctl --user stop cmsfornerd-pod
```

---

## 5. Integrating Google Jules Agent

Google Jules works as an asynchronous developer agent by interacting directly with the Git repository.

### Step 5.1: Install Google Jules CLI Tools
Install the Google Jules CLI tool via npm and authenticate using the CLI onboarding flow:
```bash
npm install -g @google/jules
jules login
```
Or configure your authentication via API key if preferred:
```bash
jules auth set-key <your-api-key>
```

### Step 5.2: Configure the Digital Sovereignty Gateway
Google Jules reads this repository's `AGENTS.md` and `.agents/AGENTS.md` rulesets. To verify any adjustments to playbooks or files, ensure the environment has PHP 8.4+, Composer, and dependencies installed:

```bash
# Verify or install system PHP 8.4 and Composer on Ubuntu 26.04:
sudo apt install -y php-cli php-xml php-mbstring php-curl php-zip unzip

# Programmatically verify and install Composer securely
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
actual_sig=$(php -r "echo hash_file('sha384', 'composer-setup.php');")
expected_sig=$(curl -sS https://composer.github.io/installer.sig)

if [ "$actual_sig" != "$expected_sig" ]; then
    echo "ERROR: Invalid installer signature!"
    rm composer-setup.php
    exit 1
fi

sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
rm composer-setup.php

# Install development dependencies:
composer install

# Verify changes using the validation pipeline:
composer lab-check
```

If validating within an already prepared development environment, you can directly run:
```bash
composer lab-check
```

---

## 6. Permanent Provisioning via Google Cloud Workstations

To permanently configure the Google Cloud / Jules asynchronous coding sessions to deploy **Ubuntu 26.04+** and **Podman 5+**, you must define these specifications inside your Google Cloud Workstation configuration template.

### 6.1. Using Terraform
Apply the following Terraform resource definition to provision workstation environments that are natively built with the required operating system and container runtime:

```hcl
resource "google_workstations_workstation_config" "jules_default" {
  workstation_config_id  = "jules-ubuntu-26-04"
  workstation_cluster_id = "jules-cluster"
  location               = "us-central1"

  # Specify VM configurations matching standard predefined or custom workstation images
  container {
    image = "us-central1-docker.pkg.dev/cloud-workstations-images/predefined/code-oss:latest" # Valid predefined image
    # Or use a custom Ubuntu 26.04 base image: "docker.io/library/ubuntu:26.04"
  }
}
```

### 6.2. Using Google Cloud CLI
Alternatively, run the following `gcloud` command workflow to ensure idempotence by describing the configuration first and then using configs update on reruns:

```bash
# Check if the workstation configuration already exists
if gcloud workstations configs describe jules-ubuntu-26-04 --cluster=jules-cluster --location=us-central1 &>/dev/null; then
  # Update existing workstation configuration on rerun
  gcloud workstations configs update jules-ubuntu-26-04 \
    --cluster=jules-cluster \
    --location=us-central1 \
    --container-image="us-central1-docker.pkg.dev/cloud-workstations-images/predefined/code-oss:latest"
else
  # Create new workstation configuration first
  gcloud workstations configs create jules-ubuntu-26-04 \
    --cluster=jules-cluster \
    --location=us-central1 \
    --container-image="us-central1-docker.pkg.dev/cloud-workstations-images/predefined/code-oss:latest"
fi
```

---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-27 (Planned Handover Date)*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
