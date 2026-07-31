<?php

declare(strict_types=1);

/**
 * Deep State of Mind (DSOM) Sovereign Architecture - Legacy Global Audit Script
 *
 * Checks target directories for legacy global keyword or $GLOBALS array access.
 */

echo '--- Checking for Legacy Global Keyword (includes & src) ---' . PHP_EOL;

$dirs = ['includes', 'src'];
$found = false;

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        continue;
    }

    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($files as $file) {
        if ($file->isDir() || $file->getExtension() !== 'php') {
            continue;
        }

        $content = file_get_contents($file->getPathname());
        if (preg_match('/\b(global\s+\$|\$GLOBALS\[)/', $content)) {
            echo '[FAIL] Global state detected in: ' . $file->getPathname() . PHP_EOL;
            $found = true;
        }
    }
}

if (!$found) {
    echo '[OK] Architecture is Global-free.' . PHP_EOL;
}
