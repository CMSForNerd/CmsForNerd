---
okf_version: 0.1
type: guide
title: "🛠️ Theme Customization and Navigation Control"
description: "Modify themes in themes/CmsForNerd/, update Glassmorphism styles, edit header/footer templates, and configure dynamic navbar menus."
resource: "file:///docs/how-to/customize-themes-navigation.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [theming, navigation, layout, css, amp]
---

# 🛠️ Theme Customization and Navigation Control

All visual themes reside under `themes/`. The default Glassmorphism layout is defined in `themes/CmsForNerd/`.

---

## 🎨 Modifying Layouts & Navigation

* **Global CSS Stylesheet:** Edit `themes/CmsForNerd/style.css` (or `themes/CmsForNerd/css/amp.css` for Google AMP). Avoid inserting raw `<style>` tags in body includes.
* **Navigation Labels & Exclusions:** Edit `includes/global-control.inc.php`:
  ```php
  $pageTitles = ['index' => 'Home', 'user-manual' => 'User Manual'];
  $excludedNavPages = ['offline', 'template'];
  ```

---

*CmsForNerd Theme & Navigation Customization Guide | DSOM Protocol 2026 | Harisfazillah Jamel (LinuxMalaysia)*
