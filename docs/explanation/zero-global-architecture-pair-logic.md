---
okf_version: 0.1
type: explanation
title: "🧠 Zero-Global PHP 8.4 Routing and Pair Logic Execution"
description: "Explanation of request lifecycle routing, immutable CmsContext objects, and controller-fragment Pair Logic."
resource: "file:///docs/explanation/zero-global-architecture-pair-logic.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [zero-global, pair-logic, architecture, cms-context, routing]
---

# 🧠 Zero-Global PHP 8.4 Routing and Pair Logic Execution

Execution lifecycle breakdown for CmsForNerd.

---

## 🏛️ Pipeline Sequence

1. **Request Intake:** Browser requests `[page].php`.
2. **Bootstrap:** Autoloads classes, initializes session, generates CSP nonce.
3. **Sanitization:** `SecurityUtils::resolvePageName()` validates slug.
4. **Context Creation:** Instantiates readonly `CmsContext`.
5. **Theme Dispatch:** `themes/CmsForNerd/pager.php` embeds `contents/[page]-body.inc`.

---

*CmsForNerd Zero-Global Architecture Explanation | DSOM Protocol 2026 | Harisfazillah Jamel (LinuxMalaysia)*
