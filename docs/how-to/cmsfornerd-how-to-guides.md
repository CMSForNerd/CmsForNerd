---
okf_version: 0.1
type: guide
title: "🛠️ CmsForNerd Comprehensive How-To Guides"
description: "Goal-oriented procedural instructions covering Windows Herd, native Linux (Ubuntu, Debian, AlmaLinux 9/10), containers, Render cloud, Pair Logic page creation, security, SEO, and Pest testing."
resource: "file:///docs/how-to/cmsfornerd-how-to-guides.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [how-to, installation, containers, pair-logic, security]
---

# 🛠️ CmsForNerd Comprehensive How-To Guides

This guide compiles step-by-step procedural instructions for common developer tasks in CmsForNerd v4.3.0.

---

## 🪟 1. Windows Installation via Laravel Herd

1. Download [Laravel Herd for Windows](https://herd.laravel.com/windows).
2. Set PHP version to **PHP 8.4** in Herd Settings.
3. Open PowerShell in Herd's parked folder (`C:\Users\<User>\Herd`):
   ```powershell
   cd C:\Users\$env:USERNAME\Herd
   git clone https://github.com/CMSForNerd/CmsForNerd.git cmsfornerd-herd
   cd cmsfornerd-herd
   composer install
   ```
4. Access `http://cmsfornerd-herd.test/user-manual.php`.

---

## 🐧 2. Native Linux Web Server Deployment (Ubuntu, Debian, AlmaLinux 9/10)

### Distribution Package Setup & Socket Configurations:

* **Ubuntu 24.04 / 26.04:**
  ```bash
  sudo add-apt-repository ppa:ondrej/php -y && sudo apt update
  sudo apt install -y php8.4-fpm php8.4-cli nginx git composer
  # Nginx FastCGI pass: unix:/run/php/php8.4-fpm.sock
  ```

* **Debian 12:**
  ```bash
  # Use official Debian packages (do not apply Ubuntu PPA to Debian)
  sudo apt update && sudo apt install -y php-fpm php-cli nginx git composer
  # Nginx FastCGI pass: unix:/run/php/php8.4-fpm.sock
  ```

* **AlmaLinux 9:**
  ```bash
  sudo dnf install -y epel-release https://rpms.remirepo.net/enterprise/remi-release-9.rpm
  sudo dnf module enable php:remi-8.4 -y && sudo dnf install -y php-fpm nginx git composer
  # Nginx FastCGI pass: unix:/run/php-fpm/www.sock
  ```

* **AlmaLinux 10:**
  ```bash
  sudo dnf install -y epel-release https://rpms.remirepo.net/enterprise/remi-release-10.rpm
  sudo dnf module enable php:remi-8.4 -y && sudo dnf install -y php-fpm nginx git composer
  # Nginx FastCGI pass: unix:/run/php-fpm/www.sock
  ```

### Directory Permissions Setup:
```bash
sudo git clone https://github.com/CMSForNerd/CmsForNerd.git /var/www/cmsfornerd-srv
cd /var/www/cmsfornerd-srv
sudo composer install --no-dev --optimize-autoloader
sudo chown -R www-data:www-data /var/www/cmsfornerd-srv # or nginx:nginx on EL
sudo chmod -R 755 /var/www/cmsfornerd-srv
sudo chmod -R 775 /var/www/cmsfornerd-srv/data /var/www/cmsfornerd-srv/contents
```

---

## 🐳 3. Container Orchestration (Podman & Docker)

* **Rootless Podman:** `podman build -t cmsfornerd:v4 -f Containerfile . && podman run -d -p 8080:80 -v $(pwd)/contents:/var/www/html/contents:Z cmsfornerd:v4`
* **Docker Compose:** `docker compose up -d --build`

---

## ☁️ 4. Cloud Deployment (Render & GitHub Pages)

* **Render.com Blueprint:** Deploy using `render.yaml`. Render detects the blueprint and builds via `Dockerfile`.
* **GitHub Pages Static Baking:** Compile dynamic PHP routes into flat HTML:
  ```bash
  php tools/bake-static-pages.php
  ```
  Generates static HTML files, sitemaps, and `.nojekyll` inside `build_static/`.

---

## 📄 5. Creating Pages via Pair Logic

To create a new page (e.g. `services.php`):
1. **Controller (`services.php`):**
   ```php
   <?php
   declare(strict_types=1);

   namespace CmsForNerd;

   if (!ob_start("ob_gzhandler")) { ob_start(); }
   require_once __DIR__ . '/includes/bootstrap.php';

   $content = [
       'title'       => 'Services | CmsForNerd',
       'author'      => 'Dev Team',
       'description' => 'Flat-file PHP 8.4 services.',
       'keywords'    => 'services, php84, cms',
       'schemaType'  => 'Service'
   ];

   $page = SecurityUtils::resolvePageName(pathinfo(basename(__FILE__), PATHINFO_FILENAME));
   $content['data'] = $page;

   $ctx = \createCmsContext(content: $content);
   require_once __DIR__ . "/themes/{$ctx->themeName}/pager.php";
   pager($ctx);
   ob_end_flush();
   ```
2. **Body Fragment (`contents/services-body.inc`):**
   ```html
   <h1>Our Services</h1>
   <p>High-performance zero-database PHP web applications.</p>
   ```

---

## 🌿 6. Content Version Control Workflow

When editing content files in `contents/`:
1. Work on a dedicated branch (`docs/*`, `feat/*`, or `fix/*`).
2. Obtain explicit approval before committing or pushing.
3. Push your branch and open a Pull Request for review rather than pushing directly to `main`.

---

## 🎨 7. Theme Styling & Navigation Customization

* **Stylesheets:** Edit `themes/CmsForNerd/style.css` (Standard View) or `themes/CmsForNerd/css/amp.css` (Google AMP).
* **Navigation Map:** Configure `$pageTitles` and `$excludedNavPages` in `includes/global-control.inc.php`.

---

## 🛡️ 8. Security Nonces, CSRF Tokens, and Required Turnstile

* **Content Security Policy Nonces:** Injected per-request via `$ctx->cspNonce`. Attach to inline scripts: `<script nonce="<?= $ctx->cspNonce ?>"></script>`.
* **Required Form Turnstile & CSRF Guards:**
  ```html
  <form method="POST" action="process.php">
    <input type="hidden" name="csrf_token" value="<?= \CmsForNerd\SecurityUtils::getCsrfToken() ?>">
    <div class="cf-turnstile" data-sitekey="YOUR_TURNSTILE_SITEKEY"></div>
    <button type="submit">Submit</button>
  </form>
  ```
  On processing:
  ```php
  // 1. Verify Turnstile token first
  require_once __DIR__ . '/includes/turnstile.php';
  if (!verifyTurnstileToken($_POST['cf-turnstile-response'] ?? '')) {
      header('HTTP/1.1 403 Forbidden'); die('Bot verification failed.');
  }
  // 2. Validate CSRF token
  if (!\CmsForNerd\SecurityUtils::validateCsrfToken($_POST['csrf_token'] ?? '')) {
      header('HTTP/1.1 403 Forbidden'); die('CSRF token mismatch.');
  }
  ```

---

## 🗺️ 9. SEO, Robots.txt, and Pest Testing

* **Sitemap Generation:** `php tools/generate-seo-files.php` (updates `sitemap.xml`, `sitemap.txt`, `rss.xml`, `ror.xml`, `schema-org.json`).
* **Robots.txt Directive Declarations:**
  ```text
  User-agent: *
  Allow: /

  Sitemap: https://www.linuxmalaysia.com/sitemap.xml
  Sitemap: https://www.linuxmalaysia.com/sitemap.txt
  Sitemap: https://www.linuxmalaysia.com/sitemap.php
  Sitemap: https://linuxmalaysia.github.io/CmsForNerd/sitemap.xml
  Sitemap: https://linuxmalaysia.github.io/CmsForNerd/sitemap.txt
  Sitemap: https://linuxmalaysia.github.io/CmsForNerd/sitemap.php
  ```
* **Pest Unit Tests:** `./vendor/bin/pest`
* **PHPStan Level 8 Analysis:** `vendor/bin/phpstan analyze`
* **Full Laboratory Audit:** `composer lab-check`

---

*CmsForNerd Procedural How-To Guides | DSOM Protocol 2026 | Harisfazillah Jamel (LinuxMalaysia)*
