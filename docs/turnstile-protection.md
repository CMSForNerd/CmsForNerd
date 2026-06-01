# 🛡️ Turnstile Protection: Automated Traffic Gate (v4.0.0-alpha)

The CMSForNerd v4.0.0-alpha Laboratory integrates **Cloudflare Turnstile** to provide non-intrusive CAPTCHA protection against automated form submissions and brute-force attacks.

## 1. Executive Summary (The 5 W's)

* **What**: A "Server-to-Server" verification gateway for sensitive POST requests.
* **Why**: Traditional CAPTCHAs are frustrating for users and easily broken by AI. Turnstile uses invisible challenges to prove "humanness" without friction.
* **Where**: Primary logic is in [includes/turnstile.php](../includes/turnstile.php).
* **When**:
  * Triggered automatically for any incoming `POST` request that contains data.
  * Required for login forms, contact forms, and content edits.
* **How**: The client sends a challenge token (`cf-turnstile-response`). The CMS then makes a background `cURL` request to Cloudflare's verification API to approve or reject the request.

## 2. Technical Architecture

### Integration Point

In [includes/turnstile.php](../includes/turnstile.php), the module listens globally for POST traffic:

```php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
    $userToken = (string) ($_POST['cf-turnstile-response'] ?? '');
    if (!verifyTurnstile($userToken, $userIp)) {
        http_response_code(403);
        die("Security Check Failed: Automated traffic detected.");
    }
}
```

### Server-to-Server Flow

1. **Token Capture**: Extract `cf-turnstile-response` from the POST body.
2. **API Communication**: Uses `curl_init()` to reach `https://challenges.cloudflare.com/turnstile/v0/siteverify`.
3. **Decision**: If the API returns `success: true`, the request proceeds. Otherwise, the CMS immediately issues a `403 Forbidden` and terminates execution.

## 3. Configuration

To enable Turnstile, you must define your secret key in your configuration or directly in the file (not recommended for production).

```php
if (!defined('TURNSTILE_SECRET_KEY')) {
    define('TURNSTILE_SECRET_KEY', 'your-secret-key-here');
}
```

## 4. Performance & Security

1. **Low Latency**: The `cURL` request is configured with a 2-second connection timeout to ensure site availability if Cloudflare is unreachable.
2. **Defense-in-Depth**: Turnstile works alongside [is_bot.php](../includes/is_bot.php). While `is_bot` identifies legitimate crawlers for GET requests, Turnstile guards the writeable POST routes against malicious bots.

## 5. Troubleshooting

If you see "Security Check Failed":

1. Ensure the `TURNSTILE_SECRET_KEY` is correct.
2. Verify that your client-side form includes the `<div class="cf-turnstile"></div>` widget.
3. Check [includes/turnstile.php](../includes/turnstile.php) for cURL error logs in your server log.
