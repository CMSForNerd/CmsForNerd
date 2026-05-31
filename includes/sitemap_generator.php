<?php

/**
 * ==========================================================================
 * FILE: includes/sitemap_generator.php
 * ROLE: Sitemap Generator Helper (Educational Edition)
 * ==========================================================================
 * TRAINING NOTE: This script uses "Pair Logic." It scans the 'contents'
 * folder for body fragments and verifies a matching .php file exists.
 */

declare(strict_types=1);

/**
 * @return array<int, array{file: string, url: string, lastmod: string, title: string}>
 */
function get_detailed_site_pages(): array
{
    $pages = [];

    // [PATH DISCOVERY] Get the absolute path to the project root
    // We use realpath to resolve any strange Windows/Herd path issues
    $rootDir = realpath(__DIR__ . '/../');
    $fragmentDir = $rootDir . DIRECTORY_SEPARATOR . 'contents' . DIRECTORY_SEPARATOR;

    // Safety check: If directory doesn't exist, stop here
    if (!$rootDir || !is_dir($fragmentDir)) {
        return [];
    }

    // [FILE DISCOVERY] Cache master PHP files and their modification times
    $masterFiles = [];
    $dirIterator = new DirectoryIterator($rootDir);
    foreach ($dirIterator as $fileInfo) {
        if ($fileInfo->isFile() && $fileInfo->getExtension() === 'php') {
            $masterFiles[$fileInfo->getBasename()] = $fileInfo->getMTime();
        }
    }

    // [FILE DISCOVERY] Collect body fragments with their modification times
    $exclude = ['index', 'sitemap', 'empty', '403', '404', 'header', 'footer', 'sitemap-page'];
    $fragmentIterator = new DirectoryIterator($fragmentDir);

    foreach ($fragmentIterator as $fileInfo) {
        if (!$fileInfo->isFile()) {
            continue;
        }

        $basename = $fileInfo->getBasename();
        if (!str_ends_with($basename, '-body.inc')) {
            continue;
        }

        // Extract the slug (e.g., 'lab-manual')
        $slug = str_replace('-body.inc', '', $basename);

        // [SECURITY] Skip system files
        if (in_array($slug, $exclude, true)) {
            continue;
        }

        // Check if the Master Controller exists in our cached list
        $masterBasename = $slug . '.php';

        if (isset($masterFiles[$masterBasename])) {
            // Get latest modification date (using cached mtimes)
            $mTime = max($masterFiles[$masterBasename], $fileInfo->getMTime());

            $pages[] = [
                'file'    => $masterBasename,
                'url'     => $masterBasename,
                'lastmod' => date('Y-m-d', $mTime),
                'title'   => ucfirst(str_replace(['-', '_'], ' ', $slug))
            ];
        }
    }

    return $pages;
}
