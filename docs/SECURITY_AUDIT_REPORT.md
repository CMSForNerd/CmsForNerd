---
okf_version: 0.1
type: report
title: "Security Audit Report"
description: "Comprehensive security audit and compliance analysis for CmsForNerd."
resource: "file:///docs/SECURITY_AUDIT_REPORT.md"
timestamp: "2026-08-05T10:00:00Z"
---
# CmsForNerd Security Audit Report (v4.2.0)

## 1. Enumeration Table

| File | Purpose | Auth Required? | Expected Role | Input Vectors Checked | Risk Level | Notes |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| `index.php` | Main entry / Routing | No | Guest | `QUERY_STRING` | Low | Uses `SecurityUtils` for safe routing. |
| `about.php` | About page | No | Guest | None | Low | Static content wrapper. |
| `history.php` | History page | No | Guest | None | Low | Static content wrapper. |
| `sitemap.php` | XML Sitemap | No | Guest | None | Low | Hardened headers and restricted CSP. |
| `ai-dev.php` | AI Dev Docs | No | Guest | None | Low | Documentation. |
| `ai-sop.php` | AI SOP Docs | No | Guest | None | Low | Documentation. |
| `lab-manual.php` | Lab Manual | No | Guest | None | Low | Documentation. |
| `lab-module1.php` | Lab Module 1 | No | Guest | None | Low | Worksheet. |
| `lab-module2.php` | Lab Module 2 | No | Guest | None | Low | Worksheet. |
| `lab-module3.php` | Lab Module 3 | No | Guest | None | Low | Worksheet. |
| `lab-module4.php` | Lab Module 4 | No | Guest | None | Low | Worksheet. |
| `lab-module5.php` | Lab Module 5 | No | Guest | None | Low | Worksheet. |
| `final-exam.php` | Final Exam | No | Guest | None | Medium | The "Break-Fix" challenge page. |
| `exam-answers.php` | Exam Answers | No (Now Yes) | Guest (Instructor) | `$_GET['key']` | **High** | Exposed sensitive answers to all. |
| `graduation.php` | Graduation | No (Now Yes) | Student | `$_GET['student_id']` | Medium | Accessible without completing modules. |
| `installation.php` | Setup Guide | No | Guest | None | Low | Documentation. |
| `linux-setup.php` | Linux Setup | No | Guest | None | Low | Documentation. |
| `windows-setup.php` | Windows Setup | No | Guest | None | Low | Documentation. |
| `ujian-form.php` | Turnstile Test | No | Guest | `$_POST` | Low | Protected by Turnstile. |

## 2. Executive Summary
The CmsForNerd codebase is a well-structured educational platform utilizing modern PHP 8.4 features. It emphasizes a **Zero-Global** architecture (via `Registry` and `CmsContext`) and implements strong security headers (CSP, X-Frame-Options, etc.). It has transitioned to a **Glassmorphism** visual layer in v4.0.0. However, it relies on simple key-based authorization rather than a formal RBAC system.

## 3. Critical Issues
- **Authentication Bypass (Exam Answers):** The `exam-answers.php` file was publicly accessible, allowing anyone to view answers to the final exam.
    - *Status:* Remedied with a basic "Instructor Key" check.

## 4. High Issues
- **Lack of RBAC:** No session management or user roles are enforced across the system.
- **Exposure of Technical Files:** Direct access to `.inc` files in subdirectories was possible if server configuration (like `.htaccess`) failed.
    - *Status:* Remedied via `boot_security()` improvements.

## 5. Medium Issues
- **Incomplete Output Escaping:** The `baseUrl` in some templates was rendered without explicit escaping.
    - *Status:* Remedied via `htmlspecialchars()` in `common-headertag.inc`.
- **CSRF Risk:** While Turnstile protects POST requests, there is no per-session CSRF token mechanism for state-changing operations.

## 6. Low/Informational Issues
- **Hardcoded Instructor Key:** The current "Instructor Key" is hardcoded in the script, which is a risk if the source is leaked.
- **Directory Discovery:** Subdirectories use `index.php` to prevent listing, but some might still be discoverable.

## 7. Safe Zones
- **Core Routing:** `SecurityUtils::resolvePageName` provides robust protection against Path Traversal.
- **Headers:** Centralized security headers in `bootstrap.php` and `.htaccess` provide strong baseline protection.

## 8. Top 5 Prioritized Security Fixes
1. **Restrict Access to Answers:** Implement a gate for `exam-answers.php`. (DONE)
2. **Harden Fragment Protection:** Block direct URL access to `.inc` files. (DONE)
3. **Consistent Escaping:** Ensure all dynamic variables in templates are escaped. (DONE)
4. **Implement Session-based CSRF:** Move beyond just Turnstile for state protection.
5. **Secure Configuration:** Move secrets (keys, API tokens) to a secure, non-versioned config file.

## 9. Code Patch Recommendations (Examples)

### A. Escaping Base URL
**Before:**
```html
<meta itemprop="image" content="<?= $ctx->baseUrl ?>images/cmsfornerd-logo.png">
```
**After:**
```html
<meta itemprop="image" content="<?= htmlspecialchars($ctx->baseUrl, ENT_QUOTES, 'UTF-8') ?>images/cmsfornerd-logo.png">
```

### B. Direct Access Protection
**Before:**
```php
if ($currentFile === 'global-control.inc.php') {
    die("Direct access forbidden.");
}
```
**After:**
```php
if ($currentFile === 'global-control.inc.php' || str_ends_with($currentFile, '.inc')) {
    http_response_code(403);
    die("Access Denied.");
}
```

## 10. OS-Level Hardening & ASIMP Integration

### A. ASIMP Overview
ASIMP (**Ansible System Integrity Management Platform**) is a host-based, automated security hardening, compliance, and auditing framework powered by Ansible. It enforces a strict "Measure, Harden, Re-Measure" security loop.

CmsForNerd integrates ASIMP principles through the `setup_os` role in `playbooks/roles/setup_os`, executing multi-engine vulnerability and compliance scoring using **Lynis** and **OpenSCAP** against CIS Security Linux Level 2 profile and OVAL vulnerability definition standards.

### B. Google Jules Sandbox Mock Settings
When operating inside the **Google Jules Sandbox**, the agent does not possess privileged root permissions or write privileges to host system configuration spaces (such as `/etc/sysctl.conf`). To bypass environmental constraints while ensuring full structural auditing, a mock execution engine is provided via `tools/mock-asimp.sh`.

#### Mock Execution Architecture
1. **Environmental Auto-Detection:** The script checks the execution UID and path context. If it detects containment under user `jules`, it automatically routes workflows into Google Jules Mock mode.
2. **Local Scorecard Generation:** To prevent permission-denied errors on production paths, the mock system writes reports and baseline database state directly into custom sandbox directories:
   - JSON Score Datastore: `data/asimp_mock/var/log/asimp-baseline-scores.json`
   - Security Audit Report: `data/asimp_mock/opt/report/openscap/SECURITY_AUDIT_REPORT.md`
3. **Score Simulation Matrix:**
   - **Lynis Hardening Index:** Baselines at `62 / 100`, rising to `88 / 100` post-hardening (Target: 85+).
   - **OpenSCAP Compliance %:** Baselines at `58.4%`, rising to `91.2%` post-hardening (Target: 90%+).

#### Sandbox Mock Results

```json
{
  "timestamp": "2026-08-05T10:28:26Z",
  "environment": "Google Jules Sandbox Mock",
  "scores": {
    "before": {
      "lynis_hi": 62,
      "openscap_pct": 58.4
    },
    "after": {
      "lynis_hi": 88,
      "openscap_pct": 91.2
    }
  }
}
```

### C. Production Execution Pathway (Real OS)
When executed on a real target OS (such as Debian, Ubuntu, RedHat, Rocky, or AlmaLinux), ASIMP/setup_os runs the authentic Ansible playbooks to harden the host. The operator must initially possess root or sudo capability on the control node to run the playbooks, ensuring that `bootstrap_node.yml` can obtain root through `--become` privilege escalation during first-run setup.

#### Execution Workflow
To execute the live hardening loop in production:
```bash
# 1. Ensure you have initial root or sudo privilege escalation capability on the control node.
# If the dsom-admin identity has not been created, perform first-run bootstrapping to provision dsom-admin and configure NOPASSWD sudo:
sudo ansible-playbook -i inventory/hosts.local.yml playbooks/bootstrap_node.yml --tags bootstrap

# 2. Access host control node as dsom-admin (after identity has been bootstrapped)
sudo su - dsom-admin

# 3. Run the mock-asimp.sh script (which automatically triggers authentic playbooks when run with write access on a real OS)
bash tools/mock-asimp.sh
```

#### Hardened OS Measures Applied
- **Transparent Huge Pages (THP):** Disabled permanently to prevent kernel memory allocation overhead.
- **SSH Protocol Hardening:** Disables TCP forwarding, limits authentication attempts to 3, and limits sessions to 2.
- **Compiler Restrictions:** GCC and other build tools are locked to root-only execution (`0700` permissions).
- **Network Sysctl Tuning:** Applies DDoS SYN cookies, disables IP forwarding, and enables TCP BBR congestion control.
- **Vulnerability Checks:** Schedules daily system scanning via Lynis and OpenSCAP OVAL USN security advisory feeds.
- **Firewall Setup:** Configures automated, restrictive UFW (Debian) or Firewalld (RHEL) security rules.
