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
 * title: "Bot Intelligence Update Benchmark"
 * description: "Compares synchronous file_get_contents vs asynchronous curl_multi."
 * timestamp: 2026-08-01T14:30:00Z
 * topics: [performance, benchmark, bot, curl, concurrent]
 * ---
 */

declare(strict_types=1);

namespace CmsForNerd;

// We need to fetch from the actual endpoints defined in includes/is_bot.php
$sources = [
    'Google'       => 'https://developers.google.com/search/apis/ipranges/googlebot.json',
    'Bing'         => 'https://www.bing.com/toolbox/bingbot.json',
    'Cloudflare'   => 'https://api.cloudflare.com/client/v4/ips',
    'GPTBot'       => 'https://openai.com/gptbot.json',
    'SearchBot'    => 'https://openai.com/searchbot.json',
    'ChatGPT-User' => 'https://openai.com/chatgpt-user.json',
];

echo "=== BOT INTELLIGENCE BENCHMARK SUITE ===\n\n";

// --- 1. Synchronous Baseline ---
echo "1. Running Synchronous Baseline (Serial cURL)...\n";
$syncStart = microtime(true);
$syncResults = [];
foreach ($sources as $name => $url) {
    echo "   [+] Fetching $name synchronously...\n";
    $ch = curl_init();
    if ($ch === false) {
        $syncResults[$name] = 0;
        continue;
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    if (defined('CURLOPT_PROTOCOLS_STR')) {
        curl_setopt($ch, CURLOPT_PROTOCOLS_STR, 'https');
        curl_setopt($ch, CURLOPT_REDIR_PROTOCOLS_STR, 'https');
    } else {
        curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS);
        curl_setopt($ch, CURLOPT_REDIR_PROTOCOLS, CURLPROTO_HTTPS);
    }
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
    curl_setopt($ch, CURLOPT_USERAGENT, 'CMSForNerd-Bot-Intelligence/4.0');

    $response = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($response !== false && $code === 200) {
        json_decode((string)$response, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $syncResults[$name] = strlen((string)$response);
        } else {
            $syncResults[$name] = 0;
        }
    } else {
        $syncResults[$name] = 0;
    }
}
$syncEnd = microtime(true);
$syncTime = $syncEnd - $syncStart;
echo "   [OK] Synchronous fetch completed in: " . number_format($syncTime, 4) . " seconds\n\n";

// --- 2. Asynchronous curl_multi ---
echo "2. Running Asynchronous Optimization (curl_multi)...\n";
$asyncStart = microtime(true);

$mh = curl_multi_init();
if ($mh === false) {
    die("Failed to initialize curl_multi\n");
}

$handles = [];
foreach ($sources as $name => $url) {
    $ch = curl_init();
    if ($ch === false) {
        continue;
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    if (defined('CURLOPT_PROTOCOLS_STR')) {
        curl_setopt($ch, CURLOPT_PROTOCOLS_STR, 'https');
        curl_setopt($ch, CURLOPT_REDIR_PROTOCOLS_STR, 'https');
    } else {
        curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS);
        curl_setopt($ch, CURLOPT_REDIR_PROTOCOLS, CURLPROTO_HTTPS);
    }
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
    curl_setopt($ch, CURLOPT_USERAGENT, 'CMSForNerd-Bot-Intelligence/4.0');

    curl_multi_add_handle($mh, $ch);
    $handles[$name] = $ch;
}

$active = null;
do {
    $status = curl_multi_exec($mh, $active);
    if ($active) {
        curl_multi_select($mh);
    }
} while ($active && $status === CURLM_OK);

$asyncResults = [];
foreach ($handles as $name => $ch) {
    $response = curl_multi_getcontent($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($response !== false && $code === 200) {
        json_decode((string)$response, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $asyncResults[$name] = strlen((string)$response);
        } else {
            $asyncResults[$name] = 0;
        }
    } else {
        $asyncResults[$name] = 0;
    }

    curl_multi_remove_handle($mh, $ch);
    curl_close($ch);
}
curl_multi_close($mh);

$asyncEnd = microtime(true);
$asyncTime = $asyncEnd - $asyncStart;
echo "   [OK] Asynchronous fetch completed in: " . number_format($asyncTime, 4) . " seconds\n\n";

// --- 3. Comparison Metrics ---
echo "=== PERFORMANCE RESULTS ===\n";
echo "Synchronous Time  : " . number_format($syncTime, 4) . " s\n";
echo "Asynchronous Time : " . number_format($asyncTime, 4) . " s\n";

$speedup = $asyncTime > 0 ? ($syncTime / $asyncTime) : 0;
$reduction = $syncTime > 0 ? (($syncTime - $asyncTime) / $syncTime * 100) : 0;

echo "Speedup Factor     : " . number_format($speedup, 2) . "x faster\n";
echo "Latency Reduction  : " . number_format($reduction, 2) . "%\n";
echo "============================\n";
