---
okf_version: 0.1
type: explanation
title: "🧠 Dual-View Rendering Engine and Google AMP Integration"
description: "Explanation of single-source dual-view rendering delivering standard HTML5 and Google AMP mobile pages."
resource: "file:///docs/explanation/dual-view-amp-engine.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [dual-view, amp, mobile-optimization, pager, rendering]
---

# 🧠 Dual-View Rendering Engine and Google AMP Integration

CmsForNerd automatically renders both standard HTML5 and Google AMP views from a single content fragment.

---

## ⚡ Dual-View Routing

* **Standard Request:** `user-manual.php` loads `header.tpl` + `contents/user-manual-body.inc` + `footer.tpl`.
* **AMP Request (`?view=amp`):** `user-manual.php?view=amp` loads `amp-header.tpl` + `contents/user-manual-body.inc` + `amp-footer.tpl`.
* **Mutual Discovery:** Bidirectional `<link rel="amphtml">` and `<link rel="canonical">` tags guide search engine crawlers.

---

*CmsForNerd Dual-View Rendering Explanation | DSOM Protocol 2026 | Harisfazillah Jamel (LinuxMalaysia)*
