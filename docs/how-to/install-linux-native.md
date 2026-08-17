---
okf_version: 0.1
type: guide
title: "🛠️ Native Linux Web Server Setup (Ubuntu & AlmaLinux)"
description: "Deploy CmsForNerd on Ubuntu 24.04 or AlmaLinux 10 servers with PHP 8.4 FPM, Nginx, and proper directory permissions."
resource: "file:///docs/how-to/install-linux-native.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [linux, nginx, apache, php84, ubuntu, almalinux]
---

# 🛠️ Native Linux Web Server Setup (Ubuntu & AlmaLinux)

Procedural instructions for deploying CmsForNerd directly on bare-metal or cloud Linux instances.

---

## 🛠️ Step-by-Step Procedure

### 1. Package Installation
```bash
# On Ubuntu 24.04 / 26.04:
sudo add-apt-repository ppa:ondrej/php -y && sudo apt update
sudo apt install -y php8.4-fpm php8.4-cli php8.4-mbstring php8.4-xml php8.4-zip nginx git composer
```

### 2. Permissions Setup
```bash
sudo git clone https://github.com/CMSForNerd/CmsForNerd.git /var/www/cmsfornerd-web
cd /var/www/cmsfornerd-web
sudo composer install --no-dev --optimize-autoloader
sudo chown -R www-data:www-data /var/www/cmsfornerd-web
sudo chmod -R 755 /var/www/cmsfornerd-web
sudo chmod -R 775 /var/www/cmsfornerd-web/data /var/www/cmsfornerd-web/contents
```

---

*CmsForNerd Native Linux Installation Guide | DSOM Protocol 2026 | Harisfazillah Jamel (LinuxMalaysia)*
