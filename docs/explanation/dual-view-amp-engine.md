---
okf_version: 0.1
type: explanation
title: "🧠 Explanation: Dual-View Rendering Engine & Google AMP Integration"
description: "Discover how CmsForNerd automatically serves standard HTML5 and validated Google AMP views from a single controller and content body."
resource: "file:///docs/explanation/dual-view-amp-engine.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [dual-view, amp, mobile-optimization, pager, rendering]
---

# 🧠 Explanation: Dual-View Rendering Engine & Google AMP Integration

CmsForNerd features a **Dual-View Rendering Engine** that serves both standard desktop/mobile HTML5 views and Google Accelerated Mobile Pages (AMP) from a single controller and body fragment file.

---

## ⚡ How Dual-View Works

When a request arrives at `themes/CmsForNerd/pager.php`, the front controller inspects the request query string:

```php
// Check for AMP view request
$isAmp = isset($_GET['view']) && $_GET['view'] === 'amp';
```

### Standard View (`http://localhost:8000/user-manual.php`):
* Renders `header.tpl` (includes standard CSS `themes/CmsForNerd/style.css`, full JavaScript, Glassmorphism UI elements, and canonical link pointing to self).
* Includes `contents/user-manual-body.inc`.
* Renders `footer.tpl`.

### AMP Mobile View (`http://localhost:8000/user-manual.php?view=amp`):
* Renders `amp-header.tpl` (includes boilerplate `<html ⚡>`, inline validated CSS from `themes/CmsForNerd/css/amp.css`, AMP JS scripts, and `<link rel="canonical">` pointing back to standard view).
* Includes `contents/user-manual-body.inc`.
* Renders `amp-footer.tpl`.

---

## 🔗 Mutual Canonical Link Discovery

To ensure search engines recognize both views without duplicate content penalties:

* **Standard Page `<head>`:**
  Includes `<link rel="amphtml" href="user-manual.php?view=amp">`
* **AMP Page `<head>`:**
  Includes `<link rel="canonical" href="user-manual.php">`

This bidirectional linking allows Google Search crawlers to index the AMP version for mobile search carousels while serving the full-featured Glassmorphism layout to desktop browsers.

---

*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-15*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
