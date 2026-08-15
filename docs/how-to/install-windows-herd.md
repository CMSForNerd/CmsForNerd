---
okf_version: 0.1
type: guide
title: "🛠️ How-To: Install CmsForNerd on Windows using Laravel Herd"
description: "Set up CmsForNerd v4 on Windows 10/11 using Laravel Herd for one-click PHP 8.4, Composer, and local flat-file development."
resource: "file:///docs/how-to/install-windows-herd.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [windows, laravel-herd, php84, installation, how-to]
---

# 🛠️ How-To: Install CmsForNerd on Windows using Laravel Herd

This guide explains how to install and run **CmsForNerd v4.3.0** on Windows 10 or Windows 11 using **Laravel Herd**.

Laravel Herd provides a native, zero-dependency Windows development environment that bundles PHP 8.4, Composer, and Nginx without requiring WSL, Docker, or XAMPP.

---

## 📋 Prerequisites

* Windows 10 (Build 19041+) or Windows 11.
* [Laravel Herd for Windows](https://herd.laravel.com/windows) downloaded and installed.
* [Git for Windows](https://gitforwindows.org/) installed.

---

## 🚀 Step 1: Configure PHP 8.4 in Laravel Herd

1. Launch **Laravel Herd** from the Windows Start menu.
2. Open **Settings > PHP**.
3. Ensure **PHP 8.4** is downloaded and selected as the default or project PHP version.

---

## 📦 Step 2: Clone the Repository to Herd Parked Directory

By default, Laravel Herd serves sites from `C:\Users\<YourUsername>\Herd`.

1. Open PowerShell or Git Bash and navigate to your Herd directory:
   ```powershell
   cd C:\Users\$env:USERNAME\Herd
   ```
2. Clone CmsForNerd:
   ```powershell
   git clone https://github.com/CMSForNerd/CmsForNerd.git cmsfornerd
   cd cmsfornerd
   ```

---

## ⚙️ Step 3: Install Dependencies via Composer

Run Composer using Herd's bundled binary:

```powershell
composer install
```

---

## ✅ Step 4: Verify and Access

1. Open Herd settings and ensure `cmsfornerd` is listed under active parked sites.
2. Herd automatically generates a local `.test` domain.
3. Open your browser and navigate to:
   * `http://cmsfornerd.test/index.php`
   * `http://cmsfornerd.test/user-manual.php`

---

*Deep State of Mind (DSOM) For My AI Protocol | CmsForNerd Windows Herd Installation | Harisfazillah Jamel (LinuxMalaysia)*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
