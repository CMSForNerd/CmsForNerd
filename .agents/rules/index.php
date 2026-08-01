<?php

/**
 * ==========================================================================
 * FILE: themes/CmsForNerdNew/index.php
 * ROLE: Directory Privacy & Security Gateway
 * VERSION: 3.4 (Strict Mode)
 * ==========================================================================
 * * [SECURITY]
 * This file prevents "Directory Listing" and direct browser access to
 * theme assets. In a "Zero-Debt" workflow, we do not want users or
 * bots to map out the folder structure of the CMS.
 *
 * [PERFORMANCE]
 * We use a 403 response instead of a 302 redirect. This terminates
 * the request faster and signals to search engines that they should
 * not index this specific path.
 *
 * [REUSABILITY]
 * This code is path-agnostic. You can copy it to any subdirectory
 * within CMSForNerd to protect sensitive logic or template files.
 */

declare(strict_types=1);

// 1. Send the 403 Forbidden header to the browser/client.
header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');

// 2. Clear all output buffers to ensure no partial content is leaked.
if (ob_get_level() > 0) {
    ob_end_clean();
}

// 3. Display a minimalist, low-bandwidth error message.
// Using inline CSS to keep it self-contained for training purposes.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>403 Forbidden</title>
    <style>
        body { 
            background: #f1f5f9; 
            color: #0f172a; 
            font-family: system-ui, sans-serif; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0; 
        }
        .msg { 
            border: 1px solid #cbd5e1; 
            padding: 20px; 
            background: #fff; 
            border-radius: 4px; 
            text-align: center;
        }
        code { color: #64748b; font-size: 0.8rem; }
    </style>
</head>
<body>
    <div class="msg">
        <strong>Direct access is forbidden.</strong><br>
        <code>CTX::DIR_SECURITY_GATEWAY</code>
    </div>
</body>
</html>
<?php
// 4. Halt execution immediately.
exit;
