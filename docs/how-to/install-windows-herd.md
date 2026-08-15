---
okf_version: 0.1
type: guide
title: "🛠️ Windows Installation via Laravel Herd"
description: "Run CmsForNerd on Windows 11 natively using Laravel Herd for zero-config PHP 8.4, Composer, and Nginx."
resource: "file:///docs/how-to/install-windows-herd.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [windows, laravel-herd, php84, installation, how-to]
---

# 🛠️ Windows Installation via Laravel Herd

Learn how to run **CmsForNerd v4.3.0** on Windows 11 without virtual machines or Docker Desktop using **Laravel Herd**.

---

## 📋 Execution Steps

1. Install [Laravel Herd for Windows](https://herd.laravel.com/windows).
2. Open Herd Settings > PHP, and ensure **PHP 8.4** is active.
3. Open PowerShell in your Herd parked folder (`C:\Users\<Username>\Herd`):
   ```powershell
   cd C:\Users\$env:USERNAME\Herd
   git clone https://github.com/CMSForNerd/CmsForNerd.git cmsfornerd-site
   cd cmsfornerd-site
   composer install
   ```
4. Access `http://cmsfornerd-site.test/index.php` in Microsoft Edge or Firefox.

---

*CmsForNerd Windows Herd Installation Guide | DSOM Protocol 2026 | Harisfazillah Jamel (LinuxMalaysia)*
