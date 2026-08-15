---
okf_version: 0.1
type: guide
title: "🛠️ How-To: Configure Security, CSP Nonces, CSRF, and Bot Defenses"
description: "How to configure per-request CSP nonces, CSRF form tokens, and Cloudflare Turnstile bot protection in CmsForNerd."
resource: "file:///docs/how-to/configure-security-csrf-csp.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [security, csp-nonce, csrf, bot-protection, turnstile]
---

# 🛠️ How-To: Configure Security, CSP Nonces, CSRF, and Bot Defenses

CmsForNerd incorporates OWASP-aligned security features out of the box.

---

## 🔒 1. Per-Request Content Security Policy (CSP) Nonces

CmsForNerd generates a cryptographically secure 128-bit random nonce for every HTTP request:

```php
$nonce = \CmsForNerd\SecurityUtils::generateNonce();
```

The nonce is injected into the HTTP header by `SecurityUtils::sendSecurityHeaders($nonce)`.

### Using Nonces in Inline Scripts:

When adding custom JavaScript inside templates or pages, always attach the `$ctx->nonce` property:

```html
<script nonce="<?= $ctx->nonce ?>">
  console.log("Secure script execution with CSP nonce");
</script>
```

---

## 🛡️ 2. CSRF Token Protection for HTML Forms

To protect forms against Cross-Site Request Forgery (CSRF):

### Step 1: Embed CSRF Token in Form:
```html
<form method="POST" action="process-form.php">
  <input type="hidden" name="csrf_token" value="<?= \CmsForNerd\SecurityUtils::getCsrfToken() ?>">
  <input type="text" name="username" required>
  <button type="submit">Submit</button>
</form>
```

### Step 2: Validate Token on Processing:
```php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!\CmsForNerd\SecurityUtils::validateCsrfToken($token)) {
        header('HTTP/1.1 403 Forbidden');
        die('CSRF token validation failed.');
    }
}
```

---

## 🤖 3. Cloudflare Turnstile & Bot Protection

Bot detection uses CIDR range matching in `includes/is_bot.php` backed by `data/trusted-bots.json`. Form bot verification can be enabled via Cloudflare Turnstile integration in `includes/turnstile.php`.

---

*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-15*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
