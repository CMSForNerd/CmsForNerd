---
okf_version: 0.1
type: documentation
title: "Render Deployment Guide"
description: "Comprehensive guide for deploying CmsForNerd to Render using Blueprint specification and custom Containerfiles."
resource: "file:///docs/RENDER-DEPLOYMENT-GUIDE.md"
topics: [render, deploy, blueprint, container, setup]
timestamp: 2026-07-30T12:00:00Z
---
# Render Blueprint Deployment Guide

This guide describes how to deploy **CmsForNerd** onto Render using Infrastructure as Code (IaC) via the `render.yaml` Blueprint specification and a custom, high-performance `Containerfile` containerizer.

---

## 🏛️ Architecture Overview

Because Render does not offer a native, out-of-the-box PHP runtime, our architecture containerizes the application using a secure, multi-stage **PHP 8.4 Apache** image with the **Composer** build process fully automated inside the container. This approach ensures a lean, secure, and production-ready image.

---

## 🗺️ Region & Timezone Alignment

The infrastructure is explicitly bound to the following regional and temporal configurations:
- **Region**: `singapore` (The closest ultra-low-latency region in Southeast Asia).
- **Timezone**: `Asia/Kuala_Lumpur` (Configured via the `TZ` environment variable for system timezone context).

**Important**: The `TZ` environment variable sets the timezone configuration but does NOT synchronize the system clock. For PHP applications, you must also explicitly configure the timezone via:
- The `date.timezone` directive in `php.ini`, OR
- Calling `date_default_timezone_set('Asia/Kuala_Lumpur')` in your application bootstrap code.

Without explicit PHP timezone configuration, PHP will use its default timezone regardless of the `TZ` environment variable.

---

## 🔒 Secret Management & Password Safety

To satisfy strict digital sovereignty and security compliance constraints, sensitive passwords (such as `APP_SECRET` and `ADMIN_PASSWORD`) are **NEVER** stored or hardcoded in `render.yaml` or Git/GitHub.

Instead, they are configured with `sync: false` in `render.yaml`. This forces the Render Dashboard to prompt the administrator to input these values securely during the initial setup.

---

## 🚀 Step-by-Step Deployment

1. **Connect Repository**: Log in to your Render Dashboard and navigate to **Blueprints**.
2. **Link Project**: Click **Connect** on the CmsForNerd repository.
3. **Secret Handshake**: The Render interface will automatically detect `render.yaml` and prompt you to input the values for:
   - `APP_SECRET`
   - `ADMIN_PASSWORD`
4. **Deploy**: Render will build the custom `Containerfile`, configure Apache with mod_rewrite enabled, respect `.htaccess` security controls, and launch the web service.

---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-30*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
