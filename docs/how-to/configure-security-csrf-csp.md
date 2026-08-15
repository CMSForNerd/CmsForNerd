---
okf_version: 0.1
type: guide
title: "🛠️ Configuring Security Nonces, CSRF Tokens, and Turnstile"
description: "Implement per-request CSP nonces, CSRF form tokens, and Cloudflare Turnstile bot protection in CmsForNerd."
resource: "file:///docs/how-to/configure-security-csrf-csp.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [security, csp-nonce, csrf, bot-protection, turnstile]
---

# 🛠️ Configuring Security Nonces, CSRF Tokens, and Turnstile

CmsForNerd provides built-in defenses against XSS, CSRF, and bot abuse.

---

## 🔒 Security Implementations

* **Content Security Policy Nonces:** Attach `$ctx->nonce` to inline scripts:
  ```html
  <script nonce="<?= $ctx->nonce ?>">console.log("CSP Nonce Verified");</script>
  ```
* **CSRF Token Form Guard:**
  ```html
  <input type="hidden" name="csrf_token" value="<?= \CmsForNerd\SecurityUtils::getCsrfToken() ?>">
  ```
  Validate with `\CmsForNerd\SecurityUtils::validateCsrfToken($_POST['csrf_token'] ?? '')`.

---

*CmsForNerd Security Hardening Guide | DSOM Protocol 2026 | Harisfazillah Jamel (LinuxMalaysia)*
