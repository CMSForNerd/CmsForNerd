<?php

/**
 * CMSForNerd v4.0.0-alpha - Web Environment Sanity Check (Secure Version)
 * Verifies Web Server configuration without exposing system paths.
 * Supports both CLI and Browser viewing.
 */

declare(strict_types=1);

$requirements = [
    'PHP Version (>=8.4)' => PHP_VERSION_ID >= 80400,
    'cURL Extension' => extension_loaded('curl'),
    'OpenSSL Extension' => extension_loaded('openssl'),
    'MBString Extension' => extension_loaded('mbstring'),
    'JSON Extension' => extension_loaded('json'),
    'Data Directory Writable' => is_writable(__DIR__ . '/../data'),
];

$isCli = PHP_SAPI === 'cli';
$title = "🧪 CMSForNerd v4.0.0-alpha Sanity Check";

if ($isCli) {
    echo "{$title}\n" . str_repeat("=", 40) . "\n";
    foreach ($requirements as $label => $pass) {
        echo ($pass ? "✅" : "❌") . " {$label}: " . ($pass ? "PASS" : "FAIL") . "\n";
    }
    echo str_repeat("-", 40) . "\n";
    if (!in_array(false, $requirements, true)) {
        echo "🎯 RESULT: System is Laboratory-Ready!\n";
    } else {
        echo "⚠️  RESULT: Fix missing requirements before proceeding.\n";
    }
    exit(in_array(false, $requirements, true) ? 1 : 0);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <style>
        body { font-family: -apple-system, blinkmacsystemfont, "Segoe UI", roboto, sans-serif; 
               line-height: 1.6; max-width: 800px; margin: 2rem auto; padding: 0 1rem; 
               background: #f4f4f9; color: #333; }
        .card { background: white; padding: 2rem; border-radius: 8px; 
                box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .status { font-weight: bold; padding: 4px 12px; border-radius: 20px; 
                  font-size: 0.85rem; }
        .pass { background: #d4edda; color: #155724; }
        .fail { background: #f8d7da; color: #721c24; }
        h1 { color: #1a1a1a; border-bottom: 2px solid #eee; padding-bottom: 0.5rem; 
             display: flex; align-items: center; justify-content: space-between; }
        .version-badge { background: #333; color: #fff; padding: 5px 12px; 
                         border-radius: 20px; font-size: 0.85rem; font-weight: normal; }
        ul { list-style: none; padding: 0; }
        li { margin-bottom: 15px; border-bottom: 1px solid #f9f9f9; padding-bottom: 10px; 
             display: flex; justify-content: space-between; align-items: center; }
        .verdict { margin-top: 2rem; padding: 1rem; border-radius: 6px; text-align: center; 
                   font-weight: bold; }
        .verdict-pass { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .verdict-fail { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .footer { margin-top: 2rem; color: #777; font-size: 0.8rem; text-align: center; 
                  border-top: 1px solid #eee; padding-top: 1rem; }
    </style>
</head>
<body>
    <div class="card">
        <h1>
            <span><?php echo $title; ?></span>
            <span class="version-badge">PHP <?php echo PHP_VERSION; ?></span>
        </h1>
        
        <ul>
            <?php foreach ($requirements as $label => $pass) : ?>
                <li>
                    <?php echo htmlspecialchars($label); ?>
                    <span class="status <?php echo $pass ? 'pass' : 'fail'; ?>">
                        <?php echo $pass ? 'READY' : 'FIX REQUIRED'; ?>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php if (!in_array(false, $requirements, true)) : ?>
            <div class="verdict verdict-pass">
                ✅ Your Web Server is fully optimized for the v4.0.0-alpha Laboratory!
            </div>
        <?php else : ?>
            <div class="verdict verdict-fail">
                ❌ Please fix the identified issues before proceeding.
            </div>
        <?php endif; ?>
        
        <div class="footer">
            Logical Component: <code>tools/sanity-check.php</code> | Environment: Secure Laboratory Mode
        </div>
    </div>
</body>
</html>
