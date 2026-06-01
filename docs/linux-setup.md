# 🐧 Linux Environment Setup (v3.5)

Standardizing the PHP 8.4 installation across Linux distributions for the CMSForNerd Laboratory.

---

## ⚠️ Requirement Level (RFC 2119)

Your server **MUST** run **PHP 8.4** or higher to support Property Hooks and the v3.5 engine logic.

---

## 📦 Option A: Debian & Ubuntu

Using the **Ondřej Surý** repository—the industry standard for modern PHP on Apt systems.

```bash
# Install dependencies
sudo apt update && sudo apt install -y curl ca-certificates

# Add GPG Key
curl -sSLo /usr/share/keyrings/deb.sury.org-php.gpg https://packages.sury.org/php/apt.gpg

# Add Repo
echo "deb [signed-by=/usr/share/keyrings/deb.sury.org-php.gpg] https://packages.sury.org/php/ $(lsb_release -sc) main" | sudo tee /etc/apt/sources.list.d/php.list

# Install PHP 8.4 Stack
sudo apt update && sudo apt install -y php8.4 php8.4-cli php8.4-mbstring php8.4-xml php8.4-curl php8.4-zip php8.4-xdebug
```

---

## 📦 Option B: AlmaLinux (9+)

Using the **Remi Repository** to enable DNF module streams for RHEL-based systems.

```bash
# Install Remi Repo
sudo dnf install -y https://rpms.remirepo.net/enterprise/remi-release-9.rpm

# Reset and Enable PHP 8.4 Stream
sudo dnf module reset php
sudo dnf module enable php:remi-8.4 -y

# Install PHP 8.4 Stack
sudo dnf install -y php php-cli php-mbstring php-xml php-curl php-zip php-xdebug

# Verify
php -v
```

---

## 📂 Directory Permissions

Linux is strict about ownership. The web user **MUST** have read access to the CMS core.

```bash
# Set ownership (Ubuntu/Debian)
sudo chown -R www-data:www-data /var/www/cmsfornerd

# Standard Lab Permissions
sudo find /var/www/cmsfornerd -type d -exec chmod 755 {} \;
sudo find /var/www/cmsfornerd -type f -exec chmod 644 {} \;
```

---

## 🎓 Linux Compliance Summary

* **MUST:** Use `https://` for all repository and GPG key downloads.
* **REQUIRED:** Install `php-mbstring` and `php-xml`.
* **SHOULD:** Enable `Xdebug` for Module 5 code coverage testing.

[Next: Student Welcome Kit](welcome-kit.md)
