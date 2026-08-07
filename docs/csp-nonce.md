---
okf_version: 0.1
type: documentation
title: "🛡️ Content Security Policy (CSP) Nonces"
description: "OKF-compliant documentation for csp-nonce.md."
resource: "file:///docs/csp-nonce.md"
timestamp: 2026-08-07T05:39:28Z
topics: [docs, csp, nonce, content, security]
---
# 🛡️ Content Security Policy (CSP) Nonces

## The Problem: Inline Script Vulnerabilities
Traditional websites often allow `'unsafe-inline'` in their CSP to let internal scripts run. However, this allows attackers to inject their own scripts via URL parameters or form inputs.

## The Solution: Cryptographic Nonces
In **CMSForNerd v4.0.0**, we implement a "Number Used Once" (Nonce) strategy:

1. **Generation:** `includes/bootstrap.php` creates a random 128-bit string on every page refresh.
2. **Declaration:** The string is sent in the HTTP response header or Meta tag.
3. **Execution:** The browser compares the `nonce` attribute on `<script>` tags to the one in the header.

### Mandatory Standards
* **RFC 2119 MUST:** A new nonce MUST be generated for every request.
* **RFC 2119 MUST NOT:** The nonce MUST NOT be predictable or reusable.
