---
okf_version: 0.1
type: documentation
title: "📖 CmsForNerd Local User Manual and Quickstart"
description: "Master user manual and quickstart tutorial for installing, running, and configuring CmsForNerd v4 locally using Diátaxis framework."
resource: "file:///docs/user-manual/README.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [user-manual, installation, diataxis, local-development, quickstart]
---

# 📖 CmsForNerd Local User Manual and Quickstart

Welcome to the **CmsForNerd v4.3.0** Local User Manual. CmsForNerd is a lightweight, database-free PHP 8.4 CMS framework built for developers who want full code control, zero SQL database overhead, and production-grade security out of the box.

This documentation is structured according to the **Diátaxis Framework**:

* **[🎓 1. Quickstart & Local Setup Tutorials](#-1-quickstart--local-setup-tutorials):** Step-by-step onboarding lessons for local machine and WSL2 Podman environments.
* **[🛠️ 2. How-To Guides](../how-to/cmsfornerd-how-to-guides.md):** Practical procedural instructions for Herd, native Linux, containers, Render, page creation, security, SEO, and testing.
* **[📋 3. Technical Reference](../reference/cmsfornerd-technical-reference.md):** Factual specifications for PHP 8.4 requirements, CmsContext API, Registry, SecurityUtils, PerformanceUtils, and Composer scripts.
* **[🧠 4. Architectural Explanations](../explanation/cmsfornerd-architecture-explanation.md):** Deep-dive explanations covering Zero-Global design, Pair Logic, Dual-View AMP, OWASP security, and 3-tier caching.

---

## 🎓 1. Quickstart & Local Setup Tutorials

### 🚀 Five-Minute Quickstart Onboarding

Launch a live CmsForNerd instance on your workstation in four simple commands. Note that PHP 8.3 is unsupported; PHP 8.4 or newer is strictly required.

1. **Fetch Repository:**
   ```bash
   git clone https://github.com/CMSForNerd/CmsForNerd.git ~/cmsfornerd-app
   cd ~/cmsfornerd-app
   ```

2. **Install autoloader and dev tools:**
   ```bash
   composer install --no-interaction
   ```

3. **Validate Environment Quality Gate:**
   ```bash
   composer lab-check
   ```

4. **Start local web server:**
   ```bash
   php -S 127.0.0.1:8000
   ```

Visit `http://127.0.0.1:8000/index.php` or `http://127.0.0.1:8000/user-manual.php` in your browser.

---

*CmsForNerd Master User Manual & Quickstart | DSOM Protocol 2026 | Harisfazillah Jamel (LinuxMalaysia)*
