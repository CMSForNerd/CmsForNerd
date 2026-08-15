---
okf_version: 0.1
type: explanation
title: "🧠 Explanation: Zero-Global Architecture & Pair Logic Pattern"
description: "Understand how CmsForNerd routes requests through its Zero-Global PHP 8.4 pipeline using the Pair Logic controller-body design."
resource: "file:///docs/explanation/zero-global-architecture-pair-logic.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [zero-global, pair-logic, architecture, cms-context, routing]
---

# 🧠 Explanation: Zero-Global Architecture & Pair Logic Pattern

This document explains the internal execution lifecycle of CmsForNerd v4.3.0, detailing how incoming HTTP requests flow through the Zero-Global pipeline and Pair Logic pattern.

---

## 🏛️ Request Pipeline Execution Flow

When a user or search crawler requests a page (e.g. `user-manual.php`), execution follows a deterministic, linear pipeline:

```text
HTTP Request (GET /user-manual.php)
       │
       ▼
1. Controller Initialization (user-manual.php)
       │
       ▼
2. Bootstrap Execution (includes/bootstrap.php)
       ├── Load Composer Autoloader (vendor/autoload.php)
       ├── Initialize Global Control Config (includes/global-control.inc.php)
       └── Initialize Session & Nonces (SecurityUtils::generateNonce())
       │
       ▼
3. Input Sanitization (SecurityUtils::resolvePageName('user-manual'))
       │
       ▼
4. Context Factory Creation (createCmsContext(...))
       └── Instantiates immutable CmsContext object
       │
       ▼
5. Theme Front Controller Dispatcher (themes/CmsForNerd/pager.php)
       ├── Inspects ?view=amp URL query string
       ├── Standard View: includes header.tpl -> contents/user-manual-body.inc -> footer.tpl
       └── AMP View: includes amp-header.tpl -> contents/user-manual-body.inc -> amp-footer.tpl
       │
       ▼
HTTP Response (HTML Output with CSP Nonce & Security Headers)
```

---

## 👥 The Pair Logic Pattern

Pair Logic separates controller routing logic from presentation content:

* **The Controller (`user-manual.php`):**
  Defines SEO metadata, resolves page names safely, creates `CmsContext`, and dispatches to the theme engine.
* **The Body Fragment (`contents/user-manual-body.inc`):**
  Contains clean HTML body elements. It is included dynamically by the theme pager without wrapping `<html>`, `<head>`, or `<body>` outer tags.

---

## 🚫 Why Zero-Global Matters

Legacy PHP applications frequently relied on global state (`global $site_title;`). Global variables lead to hard-to-trace bugs, memory leaks in long-running processes, and severe testing friction.

CmsForNerd eliminates global state completely:
* Request-scoped state is stored inside the readonly, immutable `CmsContext` instance.
* Site-wide key-value state is managed statically via `Registry::set()` and `Registry::get()`.

---

*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-15*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
