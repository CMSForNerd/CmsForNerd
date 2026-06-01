# 🛠️ Installation Guide: CMSForNerd v3.5

**CMSForNerd** is a lightweight, flat-file Content Management System geared towards developers and enthusiasts who want full control over their code. Unlike complex database-driven platforms, CMSForNerd stores all content in simple text files, making it incredibly fast, secure, and easy to backup—just copy the files!

The v3.5 modernization ensures full **cross-platform compatibility** (Windows, Linux, Unix, FreeBSD) achieved through **AI-Assisted Coding** using **Google Antigravity**.

---

## 📋 Requirements

To run the laboratory environment, ensure your system meets these specifications:

* **Web Server:** Nginx (Recommended), Apache, IIS, or LiteSpeed.
* **PHP Version:** **PHP 8.4+ REQUIRED** (supports Property Hooks and modern strict typing).
* **PHP Modules:** `mbstring` (string handling), `openssl` (security), and `zip` (archives).
* **Operating System:** Windows 11, Linux (Ubuntu/Debian/AlmaLinux), Unix, or FreeBSD.
* **Dependency Manager:** [Composer](https://getcomposer.org/) is REQUIRED for autoloader generation and laboratory check scripts.
* **Database:** **None!** (Radically simple flat-file architecture).

---

## 🚀 Installation Steps

Follow these steps to get your CMS up and running:

### 1. Download the Source

Clone the repository or download the latest release from GitHub:

```bash
git clone https://github.com/CMSForNerd/CmsForNerd.git .
```

### 2. Dependency Setup

Open your terminal in the root folder and run Composer. This step is crucial for the v3.5 PSR-4 Autoloader and technical tools:

```bash
composer install
```

### 3. Permissions & Deployment

Move the files to your web server's public directory. Ensure permissions follow the 755/644 standard:

* **Linux (Debian/Ubuntu):**

```bash
# Set ownership to the web server user (example path)
sudo chown -R www-data:www-data /var/www/html/

# Secure permissions: Directories (755) and Files (644)
sudo find /var/www/html/ -type d -exec chmod 755 {} +
sudo find /var/www/html/ -type f -exec chmod 644 {} +
```

> [!IMPORTANT]
> Never use `chmod -R 777` or `755` on files. The commands above ensure that only directories are searchable/traversable and files are read-only for security, while remaining writable by the web server owner if needed.

* **Windows:** Ensure the user running your web server (e.g., Laravel Herd) has read/write access to the `contents/` folder.

---

## 📂 The "Pair Logic" System

CMSForNerd uses a unique **Pair Logic** architecture. To create a new page (e.g., `about.php`), you need two components:

1. **The Entry Point (Controller):** A `.php` file in the root directory. You MUST copy `template.php` and rename it. This file initializes the `CmsContext` using the `createCmsContext()` factory method.
2. **The Content Body (View):** A file inside the `contents/` folder named `about-body.inc`. Put your raw HTML/PHP content here (only the portion intended for the `<body>` tag).

---

## 🛡️ Security Features (v3.5)

The modernized framework ensures a "Bunker" security posture:

* **Strict Typing:** `declare(strict_types=1);` is enforced on all core files.
* **Zero Directory Traversal:** Strict path validation via `SecurityUtils`.
* **Bot Protection:** Built-in [Cloudflare Turnstile](https://www.cloudflare.com/products/turnstile/) support and bot detection.
* **CSP Engine:** Automatic Nonce generation for every request to block XSS.
* **PHPStan Level 8:** All code is audited for type-heavy safety.

---

## 🧰 Recommended Nerd Stack

| Category | Recommended Option | Why? |
| --- | --- | --- |
| **Editor** | **Google Antigravity** / VS Code | AI-optimized coding and terminal speed. |
| **Local Server** | **Laravel Herd** | The fastest PHP 8.4 engine for Windows. |
| **Browser** | **Firefox Developer Edition** | Superior CSP auditing and CSS Grid debugging. |
| **Compliance** | **Composer** | Vital for checks: `composer compliance` or `composer lab-check`. |

---

## 🎓 Advanced: Theme Development (PHP 8.4)

When building themes, always use the **Context Object** pattern. Avoid using global variables directly:

```php
// Standard v3.5 Theme Inclusion (Context Pattern)
include "themes/{$ctx->themeName}/header.tpl";
```

## 📜 Maintenance Note

Whenever you move the project or pull updates, **MUST** run `composer install` to ensure the autoloader and the Nerd-Stack are ready for the laboratory modules.
