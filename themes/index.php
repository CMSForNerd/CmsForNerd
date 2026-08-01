<?php

/**
 * ==========================================================================
 * FILE: themes/CmsForNerdNew/index.php
 * ROLE: Directory Privacy & Security Gateway (PHP Layer)
 * VERSION: 3.4 (Strict Mode)
 * ==========================================================================
 * * [SECURITY]
 * This file prevents "Directory Listing" by terminating the request at the
 * server level before any folder contents are indexed or displayed.
 *
 * [PERFORMANCE]
 * By using a 403 Forbidden header, we tell crawlers and browsers to stop
 * processing immediately, saving server bandwidth and CPU cycles.
 *
 * [REUSABILITY]
 * This code is path-agnostic. Copy it to any subdirectory to protect
 * logic, templates, or assets.
 */

declare(strict_types=1);

// 1. [HEADER] Send 403 Forbidden.
// This is more professional than a 302 redirect because it accurately
// describes the security state of the request.
header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');

// 2. [CLEANUP] Clear output buffers to prevent any accidental data leakage.
if (ob_get_level() > 0) {
    ob_end_clean();
}

// 3. [UI] Minimalist "Lab" aesthetic to match CMSForNerd v3.4.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>403 - Forbidden</title>
    <style>
        :root {
            --lab-bg: #f1f5f9;
            --lab-ink: #0f172a;
            --lab-border: #cbd5e1;
            --font-mono: 'SF Mono', 'Menlo', 'Monaco', monospace;
        }
        body { 
            background: var(--lab-bg); 
            color: var(--lab-ink); 
            font-family: system-ui, -apple-system, sans-serif; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0; 
        }
        .msg { 
            border: 1px solid var(--lab-border); 
            padding: 2rem; 
            background: #fff; 
            border-radius: 8px; 
            text-align: center;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            max-width: 400px;
        }
        .icon { font-size: 2rem; margin-bottom: 1rem; }
        h1 { margin: 0; font-family: var(--font-mono); font-size: 1.5rem; }
        p { color: #64748b; font-size: 0.9rem; margin: 1rem 0; }
        code { 
            font-family: var(--font-mono); 
            background: var(--lab-bg); 
            padding: 2px 5px; 
            border-radius: 4px; 
            font-size: 0.75rem; 
        }
        .back-link { 
            display: inline-block;
            margin-top: 1rem;
            text-decoration: none;
            color: #0ea5e9;
            font-weight: bold;
            font-size: 0.85rem;
        }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="msg">
        <div class="icon">🔒</div>
        <h1>403_FORBIDDEN</h1>
        <p>Directory listing has been disabled for security purposes.</p>
        <code>CTX::SECURITY_GATEWAY_PHP</code>
        <br>
        <a href="/" class="back-link">← Return to Home</a>
    </div>
</body>
</html>
<?php
// 4. [HALT] Terminate script execution.
exit;
