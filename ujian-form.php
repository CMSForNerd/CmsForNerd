<?php

declare(strict_types=1);

/**
 * [LAB] Turnstile Bot Trap Verification with CSRF Hardening
 */

require_once __DIR__ . '/includes/bootstrap.php';

// Generate CSRF token for the form session
$csrfToken = \CmsForNerd\SecurityUtils::generateCsrfToken();

$csrfValid = true;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedToken = $_POST['csrf_token'] ?? '';
    if (!\CmsForNerd\SecurityUtils::validateCsrfToken($submittedToken)) {
        $csrfValid = false;
    }
}

?>
<!DOCTYPE html>
<html lang="en" itemscope itemtype="https://schema.org/WebPage">
<head>
    <meta charset="UTF-8">
    <title>Turnstile Test Laboratory</title>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <style>
        body { font-family: sans-serif; padding: 2rem; background: #f4f4f9; }
        .box { background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .status { margin-top: 1rem; padding: 1rem; border-left: 4px solid #007bff; background: #e7f3ff; }
        .visually-hidden { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); border: 0; }
    </style>
</head>
<body>
    <div class="box">
        <h2>🧪 Turnstile Bot-Trap Test & CSRF Validation</h2>
        <p>Submit this form to test if <code>includes/turnstile.php</code> and CSRF defenses validate your request.</p>
        
        <form method="POST">
            <!-- OWASP CSRF mitigation -->
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

            <label for="test_data" class="visually-hidden">Test Data Input</label>
            <input type="text" id="test_data" name="test_data" autocomplete="off" placeholder="Enter test data…" required>
            <br><br>
            <div class="cf-turnstile" data-sitekey="1x00000000000000000000AA"></div>
            <br>
            <button type="submit" aria-label="Submit POST security test form">Test POST Security</button>
        </form>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST') : ?>
            <?php if ($csrfValid) : ?>
                <div class="status">
                    <strong>[PASS]</strong> If you see this, the Turnstile and CSRF validation checks were successful!
                </div>
            <?php else : ?>
                <div class="status" style="border-left-color: #dc3545; background: #f8d7da; color: #721c24;">
                    <strong>[FAIL]</strong> CSRF Validation failed. The request was securely blocked.
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
