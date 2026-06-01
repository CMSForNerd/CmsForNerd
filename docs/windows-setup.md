# 🪟 Windows Setup Guide: Laboratory Readiness (v4.0.0-alpha)

Professional toolchain configuration for the **CMSForNerd v4.0.0-alpha** Laboratory, prepared for PHP 8.4 and PHP 9.

---

## 🛠️ Phase 1: Installing the Professional Toolchain

### 1. Laravel Herd (The PHP 8.4+ Engine)

Herd is the preferred environment because it manages multiple PHP versions without complex configuration.

* **Download:** [herd.laravel.com](https://herd.laravel.com)
* **Version Selection:** During setup, ensure you select **PHP 8.4**.
* **PHP 9 Readiness:** Herd allows you to update the PHP engine with one click as soon as the PHP 9 Alpha/Beta versions are released.

### 2. Git for Windows (The Code Mover)

* **Download:** [git-scm.com](https://git-scm.com)
* **Crucial Step:** During installation, select **"Enable symbolic links"**. This is important for modern PHP project structures.

### 3. Google Antigravity (The Advanced Terminal)

**Why:** Standard Windows CMD often struggles with complex PHP 8.4 CLI output. Antigravity provides the high-speed rendering needed for automated audit tools.

---

## 📂 Phase 2: Cloning the Repository

1. Open **Antigravity**.
2. Navigate to your Herd sites folder (usually in your user profile):

   ```powershell
   cd ~\Herd
   ```

3. Clone the project:

   ```powershell
   git clone https://github.com/CMSForNerd/CmsForNerd.git
   ```

4. Enter the directory:

   ```powershell
   cd CmsForNerd
   ```

---

## ⚙️ Phase 3: Initializing for PHP 8.4 & 9

To make the CMS run on the latest engines, we must install the modern dependencies.

1. **Run Composer Install:**

   ```powershell
   composer install
   ```

   *(This downloads PHPUnit 11+ and PHP_CodeSniffer, required for modern testing).*

2. **Verify PHP Version:**

   ```powershell
   php -v
   ```

   Ensure it says **PHP 8.4.x**. If it says 8.2 or 8.3, go to the Herd Settings and change the version to 8.4.

---

## 🧪 Phase 4: Running the "Nerd Audit"

To confirm your installation is perfect and follows the RFC 2119 "MUST" requirements:

```powershell
composer compliance
```

---

## 💡 Important: Why PHP 8.4/9 Matters

* **Property Hooks:** Write code that is 30% shorter by using PHP 8.4 hooks instead of old-fashioned getters and setters.
* **Type Safety:** PHP 9 will continue to push for stricter types.
* **Performance:** PHP 8.4 and 9 are significantly faster than older versions.

---

### Next Step for Students

Once your terminal shows "Audit Passed", you are ready to open the project in your editor and start the [Lab Manual](lab-manual.md)!
