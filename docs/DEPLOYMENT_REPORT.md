---
okf_version: 0.1
type: documentation
title: "CmsForNerd Infrastructure Deployment Report"
description: "Deployment report detailing the CmsForNerd application stack deployment under rootless Podman on Ubuntu."
resource: "file:///docs/DEPLOYMENT_REPORT.md"
timestamp: 2026-07-29T00:00:00Z
topics: [docs, deployment, report, cmsfornerd, infrastructure]
---
# CmsForNerd Infrastructure Deployment Report

## 1. Deployment Overview & Topology
This report details the deployment of the CmsForNerd application stack under an unprivileged user space (`dsom-admin:2001:2001`) utilizing **Podman v5.2.2** in native rootless mode on Ubuntu.

The infrastructure utilizes three containerized services running in a cohesive unprivileged environment:
*   **cms-bunkerweb (docker.io/bunkerity/bunkerweb:1.5.8):** Bound to unprivileged host ports `8080` (HTTP) and `8443` (HTTPS/UDP), acting as the primary security gateway, terminating SSL/TLS, and handling dynamic application routing.
*   **cms-nginx (docker.io/nginxinc/nginx-unprivileged:1.27):** Serves as the upstream static file server and FastCGI proxy, configured cleanly to run natively as an unprivileged user.
*   **cms-php (localhost/cmsfornerd-php:8.5):** Custom PHP-FPM container configured to run with the `--allow-to-run-as-root` (`-R`) flag, ensuring zero privilege conflicts inside the user namespace.

---

## 2. Key Resolution Strategies & Architecture Fixes

### A. Rootless PHP-FPM Privilege Drop Bypass
*   **Problem:** Standard PHP-FPM images attempt to execute a setgid/setuid privilege drop to the configured pool user (often matching UID 2001). Inside rootless Podman user namespaces, UID 2001 maps to a high unmapped host subuid, raising a fatal `failed to setgid(2001): Operation not permitted` crash.
*   **Solution:** Configured `www.conf.j2` pool rules to run as `root:root` within the container context, and updated the start command to `php-fpm -R`. Since the entire container executes inside the rootless namespace of `dsom-admin` (UID 2001 on the host), container-side root translates securely to the host user without any host-wide privilege escalation vulnerabilities.

### B. Unprivileged Volume Ownership Alignment (BunkerWeb)
*   **Problem:** BunkerWeb runs internally as unprivileged user `nginx` (UID 101) inside its container. The host-mounted volumes (`/opt/cmsfornerd/bunkerweb/configs` and `/opt/cmsfornerd/bunkerweb/data`) were initialized with `dsom-admin:dsom-admin` ownership and restricted `700` permissions, throwing a `PermissionError: [Errno 13] Permission denied` during dynamic configuration generation.
*   **Solution:** Adjusted owner permissions cleanly on the host using native namespace translation:
    ```bash
    sudo -u dsom-admin podman unshare chown -R 101:101 /opt/cmsfornerd/bunkerweb/configs /opt/cmsfornerd/bunkerweb/data
    ```
    This securely maps UID 101 inside the container to the correct relative subuid offsets on the host filesystem.
*   **Important Note:** Any backup, cleanup, or host-side automation accessing the remapped `/opt/cmsfornerd/bunkerweb/configs` and `/opt/cmsfornerd/bunkerweb/data` directories must run through the same `podman unshare` namespace (e.g., `sudo -u dsom-admin podman unshare <command>`) or use an explicitly configured compensating ACL/group policy to avoid permission conflicts with the remapped UIDs.

---

## 3. Findings & Improvement Recommendations

### A. Performance Optimization Findings
1.  **OPcache Configuration:**
    *   *Finding:* The PHP-FPM container currently runs with default development settings. Production performance can be dramatically accelerated by enabling OPcache for bytecode caching.
    *   *Recommendation:* Inject a custom `opcache.ini` configuration containing:
        ```ini
        opcache.enable=1
        opcache.memory_consumption=128
        opcache.interned_strings_buffer=8
        opcache.max_accelerated_files=4000
        opcache.revalidate_freq=60
        ```
2.  **FastCGI Buffering:**
    *   *Finding:* Large dynamic page payloads can lead to upstream disk-buffering bottlenecks inside Nginx.
    *   *Recommendation:* Enhance the upstream Nginx template configuration to configure optimal buffer sizes:
        ```nginx
        fastcgi_buffers 16 16k;
        fastcgi_buffer_size 32k;
        ```

### B. Security Hardening Findings
1.  **Restrictive Subuid Ranges:**
    *   *Finding:* Subuid limits on the host (`/etc/subuid` and `/etc/subgid`) must be audited to ensure that they are scoped narrowly and do not overlap with other unprivileged accounts.
    *   *Recommendation:* Configure `/etc/subuid` to allocate exactly 65536 subuids per unprivileged user, establishing a strict security boundary.
2.  **BunkerWeb Hardening Configurations:**
    *   *Finding:* BunkerWeb 1.5.8 runs with ModSecurity enabled by default and uses self-signed SSL/TLS (AUTO_LETS_ENCRYPT=no is implicit). Additional hardening features like antibot protection and rate-limiting are available but not yet configured.
    *   *Recommendation:* Inject additional security variables in `compose.yml` to enable bad bot blocking and rate-limiting:
        ```yaml
        environment:
          - USE_ANTIBOT=captcha
          - USE_LIMIT_REQ=yes
          - LIMIT_REQ_RATE=10r/s
        ```


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-29*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
