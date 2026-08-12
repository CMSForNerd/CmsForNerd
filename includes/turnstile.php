<?php

/**
 * ==========================================================================
 * FILE: includes/turnstile.php
 * ROLE: Cloudflare Turnstile Integration - v3.5
 * DESCRIPTION: Provides secure automated bot blocking via Server-to-Server
 *              verification, collaborating with is_bot.php for defense-in-depth.
 * ==========================================================================
 * Compliance: PHP 8.4, PSR-12, PSR-1 (Side-effect management)
 */

declare(strict_types=1);

namespace CmsForNerd;

/**
 * 1. [CONFIG] Secure Key Management
 */
if (!defined('TURNSTILE_SECRET_KEY')) {
    define('TURNSTILE_SECRET_KEY', 'YOUR_SECRET_KEY_HERE');
}

/**
 * Verifies a Turnstile response token with Cloudflare.
 *
 * @param string $token The client response token.
 * @param string $remoteIp The client's remote IP address.
 * @return bool `true` if Cloudflare verifies the token, `false` otherwise.
 */
function verifyTurnstile(string $token, string $remoteIp): bool
{
    if (empty($token)) {
        return false;
    }

    $url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    // [PHP 8.4] Using cURL for robust API communication.
    // We use curl_setopt_array for better readability and bulk handling.
    $ch = curl_init($url);
    if (!$ch) {
        return false;
    }

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => http_build_query([
            'secret'   => TURNSTILE_SECRET_KEY,
            'response' => $token,
            'remoteip' => $remoteIp
        ]),
        CURLOPT_TIMEOUT        => 5,
        CURLOPT_CONNECTTIMEOUT => 2,
    ]);

    /** @var string|false $response */
    $response = curl_exec($ch);
    $error    = curl_error($ch);
    curl_close($ch);

    if ($error || $response === false) {
        error_log("Turnstile cURL Error: $error");
        return false;
    }

    $outcome = json_decode($response, true);
    return (bool)($outcome['success'] ?? false);
}

/**
 * 3. [LOGIC] Automated Verification Gateway
 *
 * To satisfy PSR-1 "Side Effects", we wrap the execution logic.
 * This gate ensures verification is active for all sensitive POST requests.
 */
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
    $userToken = (string) ($_POST['cf-turnstile-response'] ?? '');
    $userIp    = (string) ($_SERVER['REMOTE_ADDR'] ?? '');

    if (!verifyTurnstile($userToken, $userIp)) {
        // [SECURITY] Failed verification MUST result in a 403 Forbidden response.
        http_response_code(403);
        header('Content-Type: text/plain');
        die("Security Check Failed: Automated traffic detected (Turnstile).");
    }
}
