<?php

/**
 * ==========================================================================
 * Deep State of Mind (DSOM) For My AI Protocol
 * Author      : Harisfazillah Jamel (LinuxMalaysia)
 * Timestamp   : 2026-08-01T14:30:00Z
 * License     : GNU General Public License v3.0
 * Standard    : UK English | DBP-standard Bahasa Melayu Malaysia (Piawai)
 * ==========================================================================
 *
 * ---
 * okf_version: 0.1
 * type: automation_tool
 * title: "Markdown Finder Benchmark"
 * description: "Compares recursive array_merge vs recursive closure vs recursive helper by reference vs RecursiveDirectoryIterator."
 * timestamp: 2026-08-01T14:30:00Z
 * topics: [performance, benchmark, recursive, array_merge, DirectoryIterator]
 * ---
 */

declare(strict_types=1);

namespace CmsForNerd;

require_once __DIR__ . '/../vendor/autoload.php';

$rootDir = realpath(__DIR__ . '/../');
if (!$rootDir) {
    echo "❌ Failed to resolve root directory.\n";
    exit(1);
}

/**
 * Recursively retrieves all markdown files (Original).
 *
 * @param string $dir The directory to scan.
 * @param string $baseDir The base directory to calculate relative paths.
 * @return array<int, array{rel_path: string, mtime: int}>
 */
function findMarkdownFilesOrig(string $dir, string $baseDir): array
{
    // Initialize standard array to accumulate items
    $origResultsAccumulator = [];
    if (!is_dir($dir)) {
        return $origResultsAccumulator;
    }
    // Read the contents of the target directory
    $scannedDirItems = scandir($dir);
    if ($scannedDirItems === false) {
        return $origResultsAccumulator;
    }
    foreach ($scannedDirItems as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        $absoluteFileAndDirPath = $dir . '/' . $item;
        if (is_dir($absoluteFileAndDirPath)) {
            // Recurse into directory and merge results
            $origResultsAccumulator = array_merge($origResultsAccumulator, findMarkdownFilesOrig($absoluteFileAndDirPath, $baseDir));
        } elseif (pathinfo($absoluteFileAndDirPath, PATHINFO_EXTENSION) === 'md') {
            $relativeCleanPath = ltrim(str_replace($baseDir, '', $absoluteFileAndDirPath), '/\\');
            $relativeCleanPath = str_replace('\\', '/', $relativeCleanPath);

            // Validate the clean path component via security manager
            if (!\CmsForNerd\SecurityUtils::isValidPath($relativeCleanPath)) {
                continue;
            }

            $origResultsAccumulator[] = [
                'rel_path' => $relativeCleanPath,
                'mtime' => (int)filemtime($absoluteFileAndDirPath),
            ];
        }
    }
    return $origResultsAccumulator;
}

/**
 * Helper to recursively retrieve markdown files by reference.
 *
 * @param string $dir
 * @param string $baseDir
 * @param array<int, array{rel_path: string, mtime: int}> $results
 */
function findMarkdownFilesHelper(string $dir, string $baseDir, array &$results): void
{
    if (!is_dir($dir)) {
        return;
    }
    // Perform standard scanning of the directory
    $directoryContents = scandir($dir);
    if ($directoryContents === false) {
        return;
    }
    foreach ($directoryContents as $fileItem) {
        if ($fileItem === '.' || $fileItem === '..') {
            continue;
        }
        $fullPathName = $dir . '/' . $fileItem;
        if (is_dir($fullPathName)) {
            // Direct recursive call using reference array
            findMarkdownFilesHelper($fullPathName, $baseDir, $results);
        } elseif (pathinfo($fullPathName, PATHINFO_EXTENSION) === 'md') {
            $finalRelPath = ltrim(str_replace($baseDir, '', $fullPathName), '/\\');
            $finalRelPath = str_replace('\\', '/', $finalRelPath);

            // Use the shared CmsForNerd\SecurityUtils path-validation operation
            if (!\CmsForNerd\SecurityUtils::isValidPath($finalRelPath)) {
                continue;
            }

            $results[] = [
                'rel_path' => $finalRelPath,
                'mtime' => (int)filemtime($fullPathName),
            ];
        }
    }
}

/**
 * Recursively retrieves all markdown files (Optimized via Reference Helper).
 *
 * @param string $dir The directory to scan.
 * @param string $baseDir The base directory to calculate relative paths.
 * @return array<int, array{rel_path: string, mtime: int}>
 */
function findMarkdownFilesRef(string $dir, string $baseDir): array
{
    $results = [];
    findMarkdownFilesHelper($dir, $baseDir, $results);
    return $results;
}

/**
 * Recursively retrieves all markdown files (Optimized via RecursiveDirectoryIterator).
 *
 * @param string $dir The directory to scan.
 * @param string $baseDir The base directory to calculate relative paths.
 * @return array<int, array{rel_path: string, mtime: int}>
 */
function findMarkdownFilesIterator(string $dir, string $baseDir): array
{
    $iteratorResults = [];
    if (!is_dir($dir)) {
        return $iteratorResults;
    }
    try {
        // Construct recursive standard iterator skipping dots
        $fileSystemIterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($fileSystemIterator as $currentFileInfo) {
            /** @var \SplFileInfo $currentFileInfo */
            if ($currentFileInfo->isFile() && $currentFileInfo->getExtension() === 'md') {
                $fullPath = $currentFileInfo->getPathname();
                $cleanRelativePath = ltrim(str_replace($baseDir, '', $fullPath), '/\\');
                $cleanRelativePath = str_replace('\\', '/', $cleanRelativePath);

                // Use the shared CmsForNerd\SecurityUtils path-validation operation
                if (!\CmsForNerd\SecurityUtils::isValidPath($cleanRelativePath)) {
                    continue;
                }

                $iteratorResults[] = [
                    'rel_path' => $cleanRelativePath,
                    'mtime' => $currentFileInfo->getMTime(),
                ];
            }
        }
    } catch (\UnexpectedValueException) {
        return $iteratorResults;
    }

    // Alphabetical sort of iterator paths to align output
    usort($iteratorResults, fn($first, $second) => strcmp($first['rel_path'], $second['rel_path']));

    return $iteratorResults;
}

echo "=== MARKDOWN FILE SEARCH BENCHMARK ===\n\n";

$targetDir = $rootDir . '/docs';
echo "Target directory: $targetDir\n\n";

// Ensure correctness first
$resOrig = findMarkdownFilesOrig($targetDir, $rootDir);
$resRef = findMarkdownFilesRef($targetDir, $rootDir);
$resIter = findMarkdownFilesIterator($targetDir, $rootDir);

// Sort original too so we compare canonical sorted contents
usort($resOrig, fn($a, $b) => strcmp($a['rel_path'], $b['rel_path']));
usort($resRef, fn($a, $b) => strcmp($a['rel_path'], $b['rel_path']));

if (count($resOrig) !== count($resRef) || count($resOrig) !== count($resIter)) {
    echo "❌ Error: Result count mismatch! Orig: " . count($resOrig) . ", Ref: " . count($resRef) . ", Iter: " . count($resIter) . "\n";
    exit(1);
}

// Compare structure
for ($i = 0; $i < count($resOrig); $i++) {
    if (
        $resOrig[$i]['rel_path'] !== $resRef[$i]['rel_path'] ||
        $resOrig[$i]['mtime'] !== $resRef[$i]['mtime'] ||
        $resOrig[$i]['rel_path'] !== $resIter[$i]['rel_path'] ||
        $resOrig[$i]['mtime'] !== $resIter[$i]['mtime']
    ) {
        echo "❌ Error: Result element mismatch at index $i!\n";
        echo "Orig: " . json_encode($resOrig[$i]) . "\n";
        echo "Ref: " . json_encode($resRef[$i]) . "\n";
        echo "Iter: " . json_encode($resIter[$i]) . "\n";
        exit(1);
    }
}
echo "✅ Results validation passed! Both algorithms produced identical outputs (Count: " . count($resOrig) . ").\n\n";

$iterations = 500;
echo "Running each algorithm $iterations times...\n\n";

// Reset statcache for a clean baseline/run
clearstatcache();

// 1. Run Original
$startOrig = microtime(true);
for ($i = 0; $i < $iterations; $i++) {
    findMarkdownFilesOrig($targetDir, $rootDir);
}
$endOrig = microtime(true);
$timeOrig = $endOrig - $startOrig;

// Reset statcache again
clearstatcache();

// 2. Run Ref
$startRef = microtime(true);
for ($i = 0; $i < $iterations; $i++) {
    findMarkdownFilesRef($targetDir, $rootDir);
}
$endRef = microtime(true);
$timeRef = $endRef - $startRef;

// Reset statcache again
clearstatcache();

// 3. Run Iterator
$startIter = microtime(true);
for ($i = 0; $i < $iterations; $i++) {
    findMarkdownFilesIterator($targetDir, $rootDir);
}
$endIter = microtime(true);
$timeIter = $endIter - $startIter;

echo "=== RESULTS ===\n";
echo "Original Algorithm Total Time        : " . number_format($timeOrig, 4) . " s\n";
echo "Ref-Helper Algorithm Total Time      : " . number_format($timeRef, 4) . " s\n";
echo "Iterator Algorithm Total Time        : " . number_format($timeIter, 4) . " s\n\n";

$speedupRef = $timeRef > 0 ? ($timeOrig / $timeRef) : 0;
$reductionRef = $timeOrig > 0 ? (($timeOrig - $timeRef) / $timeOrig * 100) : 0;

$speedupIter = $timeIter > 0 ? ($timeOrig / $timeIter) : 0;
$reductionIter = $timeOrig > 0 ? (($timeOrig - $timeIter) / $timeOrig * 100) : 0;

echo "Ref-Helper Speedup Factor            : " . number_format($speedupRef, 2) . "x faster\n";
echo "Ref-Helper Latency Reduction         : " . number_format($reductionRef, 2) . "%\n\n";

echo "Iterator Speedup Factor              : " . number_format($speedupIter, 2) . "x faster\n";
echo "Iterator Latency Reduction           : " . number_format($reductionIter, 2) . "%\n";
echo "================\n";
