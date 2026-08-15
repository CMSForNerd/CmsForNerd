---
okf_version: 0.1
type: reference
title: "📋 Reference: System Requirements for CmsForNerd v4.3.0"
description: "Review required PHP versions, extensions, supported web servers, operating systems, and optional packages needed for CmsForNerd."
resource: "file:///docs/reference/system-requirements.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [requirements, php84, web-servers, extensions, reference]
---

# 📋 Reference: System Requirements for CmsForNerd v4.3.0

This specification details the environment requirements for running **CmsForNerd v4.3.0** in production or development.

---

## 🐘 PHP Runtime Requirements

* **PHP Version:** PHP 8.4.0 or higher.
* **Property Hooks & Readonly Classes:** PHP 8.4 features must be supported natively by the PHP binary.

### Required PHP Extensions:
* `mbstring`: Multibyte string handling for UTF-8 title and content parsing.
* `json`: JSON serialization and deserialization for Schema.org and trusted-bots caching.
* `openssl`: Cryptographic nonce generation (`random_bytes()`) and session hashing.
* `zip` / `zlib`: GZIP compression and static archive handling.
* `curl`: Asynchronous bot CIDR fetching via `curl_multi`.

### Optional Extensions:
* `apcu`: In-memory caching layer for `PerformanceUtils` max mtime performance acceleration.
* `gd` or `imagick`: Optional image processing tools.

---

## 🌐 Supported Web Servers

* **Nginx:** 1.20+ with FastCGI / PHP-FPM 8.4.
* **Apache:** 2.4+ with `mod_rewrite`, `mod_headers`, and `mod_deflate` enabled.
* **LiteSpeed / OpenLiteSpeed:** Native PHP 8.4 support.
* **Microsoft IIS:** 10.0+ with FastCGI module.
* **Built-in PHP CLI Server:** `php -S localhost:8000` (for local development).

---

## 💻 Operating System Support

* **Linux:** Ubuntu 22.04 / 24.04 / 26.04 LTS, Debian 12, AlmaLinux 9 / 10, RHEL 9 / 10, Fedora, Rocky Linux.
* **Windows:** Windows 10 / 11 with Laravel Herd, WSL2, or XAMPP.
* **macOS:** macOS 13+ (Ventura / Sonoma / Sequoia) via Homebrew PHP 8.4.
* **Containers:** Podman 5+ (Rootless), Docker 24+, OpenShift.

---

*Deep State of Mind (DSOM) For My AI Protocol | CmsForNerd System Environment Requirements | Harisfazillah Jamel (LinuxMalaysia)*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
