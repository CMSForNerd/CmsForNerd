# 🚀 Release v4.0.0-alpha.0: The "Mobile Mastery" Milestone

We are proud to announce **CMSForNerd v4.0.0-alpha**, a significant evolution in our educational mission. This release introduces a sophisticated **Dual-View Architecture**, allowing the laboratory to serve lightning-fast, interactive mobile experiences via **AMP (Accelerated Mobile Pages)** while maintaining a robust desktop environment.

---

## 🌟 What's New in v4.0.0-alpha?

### 📱 Dual-View Architecture (Hybrid Delivery)

* **AMP Mode Integration**: A dedicated mobile rendering engine in `pager.php` that transforms standard PHP content into validated AMP components.
* **Interactive Sidebar**: Implementation of `amp-sidebar` for mobile navigation, featuring "Active State Highlighting" to improve user orientation.
* **Automated Image Transformation**: The CMS now automatically buffers content and converts standard `<img>` tags into CLS-optimized `<amp-img>` tags with responsive layouts.

### 🛠️ Defensive Front-End Engineering

* **CSS Size Guard**: A new safety mechanism in `includes/nav-helper.inc.php` that monitors `amp.css` to ensure it never exceeds the strict 75KB AMP limit.
* **Layered Stacking Context**: Optimized Z-index management to ensure mobile interactivity (burger menus and sidebars) remains responsive regardless of content complexity.
* **Strict CSP Nonce Propagation**: Cryptographic nonces are now synchronized across the core AMP library and all individual component scripts to prevent "Silent Failures" in secured environments.

### 🤖 Agentic Synchronization

* **Agent Brain v2.0**: Updated `.agent/brain/` documentation and task tracking to allow AI coding partners (Gemini/Antigravity) to maintain a deep understanding of the project's dual-view state.
* **Cross-Platform Parity**: Refined `includes/bootstrap.php` to ensure the "Mobile Mastery" suite performs identically across Linux and Windows development environments.

---

## 🧪 Educational Impact

With v4.0.0-alpha, the curriculum expands into **Modern Mobile UX**:

1. **AMP Standards**: Learning to build interactive UI without the weight of custom JavaScript.
2. **Resource Budgeting**: Understanding the importance of the 75KB CSS limit and how to minify assets.
3. **Stateful UI**: Using PHP logic to generate "Active" menu states in a stateless mobile environment.

---

## 📥 Installation & Upgrade

### Upgrading from v3.4

1. **Templates**: Create `themes/CmsForNerd/amp-sidebar.tpl`.
2. **Styles**: Add `themes/CmsForNerd/css/amp.css` for mobile styling.
3. **Core Logic**: Update `includes/nav-helper.inc.php` and `themes/CmsForNerd/pager.php` to enable the Dual-View controller.

---

# 🚀 Release v3.4.0: The "Zero-Debt" Milestone

We are proud to announce the release of **CMSForNerd v3.4**, a major leap forward in our mission to provide the ultimate learning environment for modern PHP engineering. This update moves beyond simple features, introducing **Static Analysis** and **Zero-Trust Security** as the new laboratory standards.

---

## 🌟 What's New in v3.4?

### 🏗️ Engineering Excellence (Zero-Debt Architecture)

* **PHPStan Level 8 Integration**: Every core file is now audited for strict type-safety, null-pointer prevention, and logical consistency. The laboratory now enforces "Zero-Debt" coding.
* **Immutable Context Factory**: We have transitioned the `CmsContext` to a `readonly` pattern, initialized via a central factory in `includes/bootstrap.php`. This eliminates global state corruption and teaches students the power of **Immutability**.

### 🛡️ Hardened Security (Zero-Trust)

* **Native CSP Engine**: Full implementation of Content Security Policy (CSP) with dynamic nonces. Your laboratory site is now inherently resistant to Cross-Site Scripting (XSS).
* **Path & Input Sanitization**: Enhanced protection against Directory Traversal and LFI (Local File Inclusion) through strict filename normalization and path validation.

### 📦 Professional Tooling

* **Composer-First Workflow**: v3.4 requires Composer for PSR-4 autoloading and development tools. Use `composer analyze` to run a professional-grade security audit on your code.
* **v3.4 Laboratory Suite**: New `installation.php` and `index-body.inc` files designed to guide new developers through the "Nerd-Stack" (PHP 8.4, PHPStan, Nginx, and Google Antigravity).

---

## 🧪 Educational Impact

This release is not just a CMS update; it is a **curriculum update**. Students will now learn:

1. **Static Analysis Mastery**: How to pass PHPStan Level 8 (Industry Standard).
2. **Dependency Management**: Handling modern libraries and autoloading via Composer.
3. **Decoupled Architecture**: Building Global-free PHP applications using the Context Factory pattern.

---

## 📥 Installation & Upgrade

### New Laboratory Setup

```bash
git clone [https://github.com/CMSForNerd/CmsForNerd.git](https://github.com/CMSForNerd/CmsForNerd.git)
cd CmsForNerd
composer install
composer lab-check

```

### Upgrading from v3.3

1. **Core Logic**: Replace `includes/bootstrap.php` and `src/CmsContext.php` with the v3.4 versions.
2. **Configuration**: Update your `composer.json` and `phpstan.neon` to match the new laboratory standards.
3. **Audit**: Run `composer update` followed by `composer analyze` to verify your code passes the Level 8 audit.

---

## 💖 Credits

* **Lead Architect**: [Harisfazillah Jamel](https://github.com/linuxmalaysia)
* **Engineering Partner**: Google Gemini & Google Antigravity
* **Community**: Special thanks to the PHP 8.4 Early Adopters and the Malaysian Open Source community.

*"Modernization without loss of simplicity. Security without the bloat."*
