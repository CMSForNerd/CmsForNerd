# [DOC] CmsForNerd Ansible Configuration Guide (v3.5.1)
# docs/ANSIBLE-CONFIG-GUIDE.md

> **"Orchestration for Simplicity. Automation for the Laboratory."**

---

## [TOOL] The Ansible Blueprint (`ansible.cfg`)

The `ansible.cfg` file is optimized for the **CmsForNerd Deployment Fabric**. Our Ansible orchestration engine is designed to **autonomously detect the target OS (Ubuntu, AlmaLinux, RHEL, or Debian)** and focuses exclusively on the deployment of a highly secure **Nginx** and **PHP-FPM 8.4+** stack:
* **Pipelining**: Enabled to reduce SSH handshake latency when deploying to remote Linux enclaves.
* **YAML Callback**: Standardized for high-fidelity human/AI audit of deployment steps, ensuring visibility into Composer outputs.
* **Privilege Escalation**: `become: true` configured at the task level strictly for OS tuning (Nginx, PHP-FPM 8.4 installation, and firewall configuration).
* **Orchestrator User**: Ansible connects via a secure staging user (e.g., `lab-admin`) utilizing SSH keys.

---

## [SHIELD] The Doctrine: "Rootful Orchestration, Isolated Execution"

In v3.5.1, we enforce a strict separation of concerns to ensure production-grade security for the educational CMS:

- **Rootful Orchestration**: Ansible runs tasks requiring package management (Nginx, PHP 8.4, Composer) and directory creation (`/var/www/cmsfornerd`) as root (`become: yes`).
- **Isolated Execution**: The CMS itself is strictly executed via the unprivileged web server identity.
    - *Production Identity*: Strictly enforced via automated OS detection: **`www-data`** (Ubuntu/Debian) or **`nginx`** (AlmaLinux/RHEL).
    - *Development Identity*: Supports Windows 11 Laravel Herd (`haris`) for local simulation.
- **Sovereign Persistence**:
    - **Primary Web Root**: `/var/www/cmsfornerd/` (Native Linux ext4, strictly owned by the web identity).
    - **Security Boundaries**: `chmod 755` for directories and `chmod 644` for files. The `.git` folder is explicitly blocked via Nginx routing rules.
- **Zero-Global Guarantee**: Ansible playbooks execute `composer lab-check` locally on the deployment node prior to rotating symlinks to guarantee the Zero-Global PHPStan Level 8 baseline.

---

## 🌳 Laboratory Project Structure (Deployment Fabric)

```text
.
├── ansible.cfg                # Playbook performance tuning
├── deploy.yml                 # Master Laboratory Orchestrator
├── inventory/
│   ├── hosts.staging.yml      # Lab Staging Nodes
│   └── hosts.prod.yml         # Production Enclaves
├── playbooks/
│   ├── roles/
│       ├── common/            # Foundation: OS Hardening & Firewall (UFW/firewalld)
│       ├── webserver/         # Proxy: Nginx Configuration & Certbot SSL
│       ├── php/               # Application: PHP 8.4-FPM & Composer 2.x Installation
│       └── cmsfornerd/        # Codebase: Git Clone, Permissions, & `composer install`
└── tools/                     # Operational Lifecycle Tools
    ├── deploy-lab.sh          # Manual trigger wrapper for Ansible
    └── audit-semantics.php    # Post-deployment sanity & semantic validation
```

---

## [VAULT] Sovereign Secrets & Identity

- **Registry**: `vault/secrets.yml` (Git-ignored) storing potential deploy keys or SSL cert paths.
- **Identity (Production Fabric)**:
    - `ansible_user`: Set to `lab-admin` for secure VM access.
    - `ansible_become`: Must be `true` for infrastructure and Nginx restarts.
- **Ownership Sovereignty**: 
    > **[BRAIN] Logic**: The CMS is database-free and requires write access *only* if flat-file editing is enabled. The baseline ensures `/var/www/cmsfornerd` is owned by `www-data:www-data` so PHP-FPM can effectively route requests, but modifications must be pulled via Git tracking, never edited directly on the production host.
- **No .env Bloat**: Because CmsForNerd relies on architectural simplicity and `bootstrap.php` constants, we avoid heavy environment file synchronization in Ansible.

---

## 🌐 Core Routing Ports

| Port | Service | Role | Data Flow |
| :--- | :--- | :--- | :--- |
| **80** | Nginx HTTP | Subsystem | Port 80 redirects universally to HTTPS |
| **443** | Nginx HTTPS | Presentation | Main Entrypoint / Standard & AMP Dual-View |
| **22** | SSH | Persistence | Ansible Orchestration / Orchestrator Sync |

---

## [SPEED] Phase 1: Reanimation & Deployment Sequence

1.  **Sync Hierarchy**: Tier 1 (Windows Herd) -> Run `composer lab-check` -> Tier 2 (Ansible Node).
2.  **OS Hardening**: Deploy `common` role (Rootful Tuning, Firewall Port 80/443 mapping).
3.  **Application Engine**: Deploy `php` role (PHP 8.4 CLI/FPM, MBString, XML extensions, Composer).
4.  **Web Proxy**: Deploy `webserver` role (Nginx configuring strict routing interceptors to drop `.inc` and `.git` requests).
5.  **Repository Sync**: Deploy `cmsfornerd` role (Git shallow clone `master` branch, enforce `www-data` ownership).
6.  **Sovereign Pulse**: Execute health check `curl -I https://localhost/` confirming the dual-view router (`pager.php`) is responding mathematically at 200 OK.

---
*Adopted for CmsForNerd Laboratory | v3.5.1 Automation Protocol | 2026-03-26*
