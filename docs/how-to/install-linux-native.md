---
okf_version: 0.1
type: guide
title: "🛠️ How-To: Native Linux Installation (Ubuntu, Debian, AlmaLinux)"
description: "Install CmsForNerd on Ubuntu, Debian, or AlmaLinux using PHP 8.4 from Ondřej Surý or Remi, with Nginx or Apache and correct file permissions."
resource: "file:///docs/how-to/install-linux-native.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [linux, nginx, apache, php84, ubuntu, almalinux]
---

# 🛠️ How-To: Native Linux Installation (Ubuntu, Debian, AlmaLinux)

This guide provides instructions for installing **CmsForNerd v4.3.0** on Linux servers (Ubuntu 24.04/26.04, Debian 12, or AlmaLinux 9/10) using PHP 8.4 and Nginx or Apache.

---

## 📋 Step 1: Install PHP 8.4 Runtime

### On Ubuntu / Debian:

```bash
sudo apt update
sudo apt install -y software-properties-common ca-certificates lsb-release apt-transport-https
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.4-fpm php8.4-cli php8.4-mbstring php8.4-xml php8.4-zip php8.4-opcache composer git nginx
```

### On AlmaLinux 9 / 10:

```bash
# Enable Remi repository for PHP 8.4 on AlmaLinux/RHEL
sudo dnf install -y epel-release https://rpms.remirepo.net/enterprise/remi-release-10.rpm && \
sudo dnf module reset php -y && \
sudo dnf module enable php:remi-8.4 -y && \
sudo dnf install -y php-fpm php-cli php-mbstring php-xml php-zip php-opcache composer git nginx
```

---

## 📂 Step 2: Deploy Code & Permissions

1. Clone CmsForNerd to your web root (e.g., `/var/www/cmsfornerd`):
   ```bash
   sudo git clone https://github.com/CMSForNerd/CmsForNerd.git /var/www/cmsfornerd
   cd /var/www/cmsfornerd
   ```
2. Install Composer dependencies:
   ```bash
   sudo composer install --no-dev --optimize-autoloader
   ```
3. Set ownership and file permissions:
   ```bash
   sudo chown -R www-data:www-data /var/www/cmsfornerd  # On Ubuntu/Debian
   # OR
   sudo chown -R nginx:nginx /var/www/cmsfornerd        # On AlmaLinux

   sudo chmod -R 755 /var/www/cmsfornerd
   sudo chmod -R 775 /var/www/cmsfornerd/data /var/www/cmsfornerd/contents
   ```

---

## ⚙️ Step 3: Configure Web Server (Nginx Example)

Create `/etc/nginx/sites-available/cmsfornerd.conf`:

```nginx
server {
    listen 80;
    server_name cmsfornerd.example.com;
    root /var/www/cmsfornerd;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # Deny access to sensitive files
    location ~ \.(inc|json|lock|yml|yaml|sh|ps1)$ {
        deny all;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/run/php/php8.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

Enable site and reload Nginx:

```bash
sudo ln -s /etc/nginx/sites-available/cmsfornerd.conf /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

---

*Deep State of Mind (DSOM) For My AI Protocol | CmsForNerd Native Linux Server Deployment | Harisfazillah Jamel (LinuxMalaysia)*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
