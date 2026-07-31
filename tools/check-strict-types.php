<?php

declare(strict_types=1);

/**
 * Deep State of Mind (DSOM) Sovereign Architecture - Strict Types Audit Script
 *
 * Checks target directories for mandatory declare(strict_types=1) header declaration.
 */

echo '--- Checking for strict_types=1 (includes & src) ---' . PHP_EOL;

$dirs = ['includes', 'src'];

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
        if (!preg_match('/declare\s*\(\s*strict_types\s*=\s*1\s*\)\s*;/', $content)) {
            echo '[WARN] Missing declare(strict_types=1) in: ' . $file->getPathname() . PHP_EOL;
        }
    }
}
