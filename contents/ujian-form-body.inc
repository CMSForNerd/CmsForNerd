<?php

declare(strict_types=1);

/**
 * [LAB] Turnstile Bot Trap Verification UI Body Fragment
 */

$csrfToken = \CmsForNerd\SecurityUtils::generateCsrfToken();

$csrfValid = true;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rawToken = $_POST['csrf_token'] ?? '';
    if (!is_string($rawToken) || !\CmsForNerd\SecurityUtils::validateCsrfToken($rawToken)) {
        $csrfValid = false;
    }
}

?>
<article class="box" style="background: var(--lab-box-bg, #fff); padding: 2rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
    <h2>🧪 Turnstile Bot-Trap Test &amp; CSRF Validation</h2>
    <p>Submit this form to test if <code>includes/turnstile.php</code> and CSRF defenses validate your request.</p>

    <form method="POST">
        <!-- OWASP CSRF mitigation -->
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

        <label for="test_data" class="visually-hidden" style="position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); border: 0;">Test Data Input</label>
        <input type="text" id="test_data" name="test_data" autocomplete="off" placeholder="Enter test data…" required>
        <br><br>
        <div class="cf-turnstile" data-sitekey="1x00000000000000000000AA"></div>
        <br>
        <button type="submit">Test POST Security</button>
    </form>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST') : ?>
        <?php if ($csrfValid) : ?>
            <div class="status" style="margin-top: 1rem; padding: 1rem; border-left: 4px solid #007bff; background: #e7f3ff; color: #004085;">
                <strong>[PASS]</strong> If you see this, the Turnstile and CSRF validation checks were successful!
            </div>
        <?php else : ?>
            <div class="status" style="margin-top: 1rem; padding: 1rem; border-left: 4px solid #dc3545; background: #f8d7da; color: #721c24;">
                <strong>[FAIL]</strong> CSRF Validation failed. The token was invalid or missing.
            </div>
        <?php endif; ?>
    <?php endif; ?>
</article>
