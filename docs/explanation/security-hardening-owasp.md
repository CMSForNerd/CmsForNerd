---
okf_version: 0.1
type: explanation
title: "🧠 OWASP Top 10 Security Hardening Rationale"
description: "Conceptual overview of CmsForNerd's defense-in-depth security mechanisms."
resource: "file:///docs/explanation/security-hardening-owasp.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [security, owasp, csp-nonce, csrf, hardening]
---

# 🧠 OWASP Top 10 Security Hardening Rationale

Defense-in-depth security model implemented across CmsForNerd.

---

## 🛡️ Core Defenses

* **Path Traversal Protection:** Input resolved via `SecurityUtils::resolvePageName()`.
* **CSP Script Nonces:** Per-request 128-bit nonces block unauthorized inline JavaScript execution.
* **CSRF Token Guards:** Tokens validated using constant-time `hash_equals()` string comparison.
* **Strict Cookies:** Session cookies set with `SameSite=Strict` and `HttpOnly`.

---

*CmsForNerd OWASP Security Hardening Explanation | DSOM Protocol 2026 | Harisfazillah Jamel (LinuxMalaysia)*
