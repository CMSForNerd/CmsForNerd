# 🧪 CmsForNerd Ansible Laboratory Manual (v3.5.1)
# docs/ANSIBLE-LAB-MANUAL.md

> **"From Zero to Production in One Command."**

This guide provides the exact operational commands to orchestrate the **CmsForNerd** environment on Linux nodes (Ubuntu, Debian, AlmaLinux, RHEL).

---

## 🛠️ 1. Prerequisites (Tier 1 Command Center)

Before deploying, ensure your Windows/WSL2 or Linux control node has:
- **Ansible 2.15+** installed.
- **SSH Access**: Your public key MUST be present on the target node's `~/.ssh/authorized_keys`.
- **PHP 8.4**: Local installation for the "Zero-Debt" pre-flight analysis.

---

## 🛰️ 2. Step-by-Step Deployment

### A. Initialize the Laboratory
Update the inventory file with your target node's IP address and credentials:
```bash
# Edit the staging inventory
nano inventory/hosts.staging.yml
```

### B. The "Zero-Debt" Execution (Recommended)
Use our optimized wrapper script. This script automatically runs **PHPStan Level 8** and **PSR-12** checks *before* allowing the Ansible playbook to start.
```bash
# Execute the secure deployment chain
bash tools/deploy-lab.sh
```

### C. Manual Playbook Execution
If you wish to bypass the pre-flight checks or run specific components:
```bash
# Run the complete orchestrator
ansible-playbook deploy.yml -i inventory/hosts.staging.yml
```

---

## 🛡️ 3. Targeted Orchestration (Using Tags)

You can run specific parts of the deployment fabric to save time:

| Task | Command |
| :--- | :--- |
| **OS 80/443 Hardening** | `ansible-playbook deploy.yml --tags foundation` |
| **Secure Nginx Setup** | `ansible-playbook deploy.yml --tags nginx` |
| **PHP 8.4-FPM Install** | `ansible-playbook deploy.yml --tags php` |
| **Codebase Sync** | `ansible-playbook deploy.yml --tags codebase` |

---

## ✅ 4. Post-Deployment Verification

Once the playbook completes, verify the "State of Mind" of your node:

1. **Routing Check**:
   ```bash
   curl -I https://your-server-domain.local/
   # Expected: HTTP/1.1 200 OK
   ```
2. **PHP Engine Check**:
   ```bash
   ssh lab-admin@your-server-ip "php -v"
   # Expected: PHP 8.4.x (cli)
   ```
3. **Security Check**:
   ```bash
   ssh lab-admin@your-server-ip "ls -la /var/www/cmsfornerd/.git"
   # Note: Nginx configuration blocks public access to this directory.
   ```

---

## 🚦 5. Troubleshooting the Lab

- **Connection Refused?**: Verify SSH is running and your `ansible_user` has `sudo` privileges.
- **PHPStan Fails?**: Fix your code's type-hinting or strict typing before attempting to redeploy. "Zero-Debt" is mandatory.
- **Nginx Not Starting?**: Check if another service is already using port 80.

---
*Maintained by the CmsForNerd Engineering Team | v3.5.1 | 2026-03-26*
