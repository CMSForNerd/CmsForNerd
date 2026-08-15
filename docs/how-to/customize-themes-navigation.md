---
okf_version: 0.1
type: guide
title: "🛠️ How-To: Customize Themes, Styling, and Site Navigation"
description: "Learn how to customize the CmsForNerd theme, update CSS, configure header and footer fragments, and manage dynamic navigation menus."
resource: "file:///docs/how-to/customize-themes-navigation.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [theming, navigation, layout, css, amp]
---

# 🛠️ How-To: Customize Themes, Styling, and Site Navigation

This guide explains how to customize CmsForNerd themes, edit CSS stylesheets, modify global layout fragments, and manage dynamic navigation menus.

---

## 🎨 Theme Architecture

All active themes reside in `themes/`. The default theme is `themes/CmsForNerd/`.

```text
themes/CmsForNerd/
├── theme.php            # Theme metadata and initialization
├── pager.php            # Main Front Controller dispatcher (Standard vs AMP)
├── style.css            # Consolidated global styles (Glassmorphism UI)
├── css/
│   └── amp.css          # Validated Google AMP CSS styles
├── header.tpl           # Desktop/Standard HTML <head> and navbar
├── footer.tpl           # Desktop/Standard <footer>
├── amp-header.tpl       # Google AMP <head> and AMP navigation
└── amp-footer.tpl       # Google AMP <footer>
```

---

## 🎨 Modifying Styles & Glassmorphism UI

To maintain AMP compliance and satisfy SonarCloud code duplication guidelines, all visual styles are consolidated into global stylesheets:
* Standard View CSS: `themes/CmsForNerd/style.css`
* AMP Mobile View CSS: `themes/CmsForNerd/css/amp.css`

> ⚠️ Do not insert raw `<style>` tags directly into body content files (`contents/*-body.inc`). Always add class definitions to `style.css` and `amp.css`.

---

## 🧭 Managing Dynamic Site Navigation

Navigation items are automatically discovered from root PHP controllers by `SecurityUtils::getDiscoveredPages()`.

To exclude a page from appearing in navigation menus or customize its display title, edit `includes/global-control.inc.php`:

```php
// Page titles and navigation order map
$pageTitles = [
    'index'        => 'Home',
    'about'        => 'About',
    'installation' => 'Installation',
    'user-manual'  => 'User Manual',
    'contact'      => 'Contact Us'
];

// Exclude sensitive or utility pages from navigation
$excludedNavPages = [
    'offline',
    'template',
    'ujian-form',
    'exam-answers'
];
```

---

*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-15*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
