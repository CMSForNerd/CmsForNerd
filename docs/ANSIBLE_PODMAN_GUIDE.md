---
okf_version: 0.1
type: documentation
title: "CmsForNerd Ansible-Podman Deployment Guide"
description: "OKF-compliant documentation for ANSIBLE_PODMAN_GUIDE.md."
resource: "file:///docs/ANSIBLE_PODMAN_GUIDE.md"
timestamp: 2026-08-07T05:39:28Z
topics: [docs, ansible, podman, cmsfornerd, deployment]
---
# CmsForNerd Ansible-Podman Deployment Guide

This guide describes how to deploy CmsForNerd using Ansible and Podman in a rootless environment.

## Overview

The Ansible automation performs the following steps:
1.  Installs Podman and necessary system utilities.
2.  Configures `sysctl` to allow rootless containers to bind to port 80.
3.  Creates a rootless user `cmsfornerd` with UID/GID `1501`.
4.  Enables linger for the `cmsfornerd` user to allow containers to run after logout.
5.  Sets up a directory structure in `/home/cmsfornerd` for configuration, logs, and web content.
6.  Clones the CmsForNerd repository and installs dependencies using Composer (in a container as the `cmsfornerd` user).
7.  Builds a custom PHP 8.4-FPM image with required extensions (`curl`, `openssl`).
8.  Deploys and runs Nginx and PHP-FPM as rootless containers using Podman's host networking (as the `cmsfornerd` user).
9.  Configures firewalls (UFW for Debian/Ubuntu, Firewalld for RHEL-based) to allow HTTP/HTTPS traffic.

## Prerequisites

- Ansible installed on your local machine.
- SSH access to the target server(s) with sudo privileges.
- Target OS: Ubuntu, Debian, AlmaLinux, Rocky Linux, or Oracle Linux.

## Deployment Steps

1.  **Configure Inventory**: Update `inventory/hosts.staging.yml` with your server's IP address and SSH user.
    ```yaml
    all:
      hosts:
        your-server-ip:
          ansible_user: your-ssh-user
          ansible_host: your-server-ip
    ```

2.  **Run Playbook**: Execute the deployment playbook.
    ```bash
    ansible-playbook -i inventory/hosts.staging.yml deploy.yml
    ```

## Post-Deployment Actions (For Humans)

1.  **DNS/Hosts File**: Since the setup uses a dummy domain `cmsfornerd.local`, you need to add it to your local `/etc/hosts` (or `C:\Windows\System32\drivers\etc\hosts` on Windows) to access the site:
    ```text
    <your-server-ip> cmsfornerd.local
    ```

2.  **Verify Containers**: Log in to the server and check the running containers as the `cmsfornerd` user.
    ```bash
    sudo -u cmsfornerd podman ps
    ```

3.  **Logs**: Monitor logs in the mapped host directories:
    - Nginx: `/home/cmsfornerd/logs/nginx/`
    - PHP-FPM: `/home/cmsfornerd/logs/php/`

## Security Measures Applied

- **Rootless Podman**: Containers managed by an unprivileged user (`cmsfornerd`), significantly reducing the attack surface.
- **Privileged Port Handling**: Host-level `net.ipv4.ip_unprivileged_port_start=80` allows rootless Nginx to bind to port 80.
- **User Mapping**: Containers are configured to run as UID 1501 inside and outside, matching the host user.
- **Volume Isolation**: SELinux/AppArmor labels are applied to volumes using the `:Z` suffix.
- **Blocked Access**: Nginx configuration explicitly denies access to `.git` and `.inc` files.
- **Firewall**: Automated configuration for both UFW and Firewalld.

## Configuration Mapping

- **Nginx Config**: `/home/cmsfornerd/config/nginx/cmsfornerd.conf`
- **PHP Config**: `/home/cmsfornerd/config/php/www.conf`
- **Web Content**: `/home/cmsfornerd/www/`
