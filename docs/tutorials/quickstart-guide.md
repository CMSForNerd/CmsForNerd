---
okf_version: 0.1
type: tutorial
title: "🚀 Quickstart Guide: Install and Run CmsForNerd Locally"
description: "Clone the repo, install Composer dependencies, verify with lab-check, and have a working CmsForNerd site running locally in 5 minutes."
resource: "file:///docs/tutorials/quickstart-guide.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [quickstart, local-setup, composer, php84, tutorial]
---

# 🚀 Quickstart Guide: Install and Run CmsForNerd Locally

Welcome to the **CmsForNerd v4.3.0** Quickstart Tutorial. This guide will walk you through setting up a fully functional, database-free PHP 8.4 CMS environment on your local development machine in under five minutes.

---

## 🎯 Learning Objectives

By the end of this tutorial, you will have:
1. Cloned the CmsForNerd repository to your local machine.
2. Installed required development tools and autoloader via Composer.
3. Verified environment compliance using `composer lab-check`.
4. Launched a local web server to browse the CMS live.

---

## 📋 Step 1: Clone the Repository

Open your terminal (macOS/Linux) or PowerShell/WSL terminal (Windows) and clone the repository:

```bash
git clone https://github.com/CMSForNerd/CmsForNerd.git
cd CmsForNerd
```

---

## 📦 Step 2: Install Dependencies

Run Composer to generate the PSR-4 autoloader and download required testing and static analysis tools:

```bash
composer install
```

> **Note for PHP 8.3 users:** If your local machine is running PHP 8.3 while preparing to upgrade to PHP 8.4, pass the `--ignore-platform-reqs` flag:
> ```bash
> composer install --ignore-platform-reqs
> ```

---

## ✅ Step 3: Verify Environment with `composer lab-check`

Before writing code, run the built-in laboratory compliance check script to verify your PHP version, extension availability, and code cleanliness:

```bash
composer lab-check
```

Expected Output:
```text
[OK] PHPStan Level 8 Analysis: 0 Errors.
[OK] PSR-12 Style Check: Passed.
[OK] OKF Frontmatter & Security Audit: Passed.
```

A passing run confirms that your environment is 100% ready for local development.

---

## 🌐 Step 4: Start the Local PHP Web Server

Launch PHP's built-in web server from the repository root directory:

```bash
php -S localhost:8000
```

Open your web browser and navigate to:
* **Home Page:** `http://localhost:8000/index.php`
* **Local User Manual:** `http://localhost:8000/user-manual.php`
* **Installation Guide:** `http://localhost:8000/installation.php`

Congratulations! Your CmsForNerd local instance is up and running.

---

## 🎓 Next Steps

Now that your local instance is active:
* Learn how to set up an enterprise-grade Linux environment on Windows using **[WSL2 + AlmaLinux 10 + Podman](local-almalinux10-wsl2-podman-setup.md)**.
* Learn how to build new pages using **[Pair Logic](../how-to/create-manage-pages.md)**.
* Explore the **[CmsContext Class Reference](../reference/cms-context-api.md)**.

---

*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-15*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
