<?php

/**
 * ==========================================================================
 * FILE: /includes/is_bot.php
 * ROLE: Hybrid Bot Intelligence & Protection (v4.0.0-alpha)
 * DESCRIPTION: Combines User-Agent regex with verified IP CIDR matching.
 * ==========================================================================
 */

declare(strict_types=1);

/**
 * [SEO/PERFORMANCE] checks if the visitor is a verified search engine crawler.
 */
function is_bot(?string $userAgent = null): bool
{
    static $lastIp  = '';
    static $lastUa  = '';
    static $lastRes = null;

    $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    $ua = $userAgent ?? $_SERVER['HTTP_USER_AGENT'] ?? '';

    if ($lastRes !== null && $ip === $lastIp && $ua === $lastUa) {
        return $lastRes;
    }

    // 1. [FAST PATH] Localhost is never a bot
    if ($ip === '127.0.0.1' || $ip === '::1') {
        return $isBotResult = false;
    }

    $userAgent = $userAgent ?? $_SERVER['HTTP_USER_AGENT'] ?? '';
    if (empty($userAgent)) {
        return $isBotResult = false;
    }

    // 2. [PATTERN MATCH] Primary UA check
    $pattern = '/(googlebot|bingbot|yandex|baiduspider|applebot|whatsapp|discordbot|slurp|search)/i';
    $regexMatch = (bool) preg_match($pattern, $userAgent);

    // 3. [TRUST BUT VERIFY] If UA looks like a bot, check the IP
    if ($regexMatch) {
        if (is_trusted_bot_ip($ip)) {
            $lastIp  = $ip;
            $lastUa  = $ua;
            return $lastRes = true;
        }
    }

    $lastIp  = $ip;
    $lastUa  = $ua;
    return $lastRes = false;
}

/**
 * [INTELLIGENCE] Verifies if an IP belongs to a trusted bot network.
 */
function is_trusted_bot_ip(string $ip): bool
{
    $dataPath = dirname(__DIR__) . '/data/trusted-bots.json';
    if (!file_exists($dataPath)) {
        error_log("BOT-INTEL: Missing database at $dataPath");
        return false;
    }

    $data = json_decode((string)file_get_contents($dataPath), true);
    if (!isset($data['bots'])) {
        return false;
    }

    foreach ($data['bots'] as $bot) {
        foreach ($bot['prefixes'] as $prefix) {
            if (ip_in_range($ip, $prefix)) {
                return true;
            }
        }
    }

    return false;
}

/**
 * [LOGIC] CIDR Matcher (IPv4/IPv6 Support)
 * * Compliance: SonarCloud Security Hardened (No Uncontrolled Resource Consumption)
 */
function ip_in_range(string $ip, string $range): bool
{
    // [SECURITY] Validate inputs to prevent processing malformed/tainted data
    if (!filter_var($ip, FILTER_VALIDATE_IP)) {
        return false;
    }

    $isIpv6 = str_contains($ip, ':');

    if (str_contains($range, '/')) {
        [$subnet, $bitsRaw] = explode('/', $range);
        $bits = (int)$bitsRaw;
    } else {
        $subnet = $range;
        $bits = $isIpv6 ? 128 : 32;
    }

    if (!filter_var($subnet, FILTER_VALIDATE_IP)) {
        return false;
    }

    // [SECURITY] Clamp bits to prevent uncontrolled resource consumption (DoS)
    $bits = max(0, min($bits, $isIpv6 ? 128 : 32));

    if ($isIpv6 !== str_contains($subnet, ':')) {
        return false; // Type mismatch
    }

    if (!$isIpv6) {
        // IPv4
        $bits = max(0, min(32, $bits));
        $ipLong = ip2long($ip);
        $subnetLong = ip2long($subnet);
        if ($ipLong === false || $subnetLong === false) {
            return false;
        }

        // [SECURITY] Safe shift calculation to avoid undefined behavior or overflows
        $shift = 32 - $bits;
        $mask = ($bits === 0) ? 0 : ((int)~0 << (int)$shift);
        return ($ipLong & $mask) === ($subnetLong & $mask);
    } else {
        // IPv6 - Architectural Hardening for Performance & Security
        $bits = max(0, min(128, $bits));
        $ipBin = inet_pton($ip);
        $subnetBin = inet_pton($subnet);
        if ($ipBin === false || $subnetBin === false) {
            return false;
        }

        // Pre-allocate fixed 16-byte zero mask to satisfy DoS protection rules
        $mask = "\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00";
        $fullBytes = (int)($bits / 8);
        $clampedFullBytes = max(0, min(16, $fullBytes));

        for ($i = 0; $i < 16; $i++) {
            if ($i < $clampedFullBytes) {
                $mask[$i] = "\xFF";
            }
        }

        $remainingBits = $bits % 8;
        if ($remainingBits > 0 && $clampedFullBytes < 16) {
            $mask[$clampedFullBytes] = chr(256 - (1 << (8 - $remainingBits)));
        }

        return ($ipBin & $mask) === ($subnetBin & $mask);
    }
}

/**
 * [AUTOMATION] Updates the trusted IP list from official sources.
 *
 * @return array<string, mixed>
 */
function update_trusted_bot_ips(): array
{
    /** @var array<string, string> $sources */
    $sources = [
        'Google' => 'https://developers.google.com/search/apis/ipranges/googlebot.json',
        'Bing'   => 'https://www.bing.com/toolbox/bingbot.json'
    ];

    $results = [
        'updated' => date('c'),
        'bots'    => []
    ];

    $mh = curl_multi_init();
    $handles = [];

    foreach ($sources as $name => $url) {
        $ch = curl_init();
        /** @var non-empty-string $url */
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        /** @var non-empty-string $ua */
        $ua = 'CmsForNerd Bot-Updater/3.5';
        curl_setopt($ch, CURLOPT_USERAGENT, $ua);
        curl_multi_add_handle($mh, $ch);
        $handles[$name] = $ch;
    }

    $running = null;
    do {
        curl_multi_exec($mh, $running);
        curl_multi_select($mh);
    } while ($running > 0);

    foreach ($handles as $name => $ch) {
        $json = curl_multi_getcontent($ch);
        if ($json) {
            $data = json_decode($json, true);
            $prefixes = [];
            if (isset($data['prefixes']) && is_array($data['prefixes'])) {
                foreach ($data['prefixes'] as $p) {
                    $prefixes[] = $p['ipv4Prefix'] ?? $p['ipv6Prefix'] ?? null;
                }
            }
            $results['bots'][] = [
                'name' => $name,
                'prefixes' => array_filter($prefixes)
            ];
        }
        curl_multi_remove_handle($mh, $ch);
        curl_close($ch);
    }
    curl_multi_close($mh);

    $dataPath = dirname(__DIR__) . '/data/trusted-bots.json';
    file_put_contents($dataPath, json_encode($results, JSON_PRETTY_PRINT));

    return $results;
}

/**
 * [SECURITY] Blocks traffic from data centers.
 * * Compliance: SonarCloud Security Hardened (SSRF & Safe Crypto)
 */
function block_datacenter_traffic(string $apiToken): void
{
    $ip = (string) ($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1');

    // 1. [PERFORMANCE] Localhost & Validation check
    // [SECURITY] Use FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE to prevent SSRF
    $isPublicIp = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
    if (!$isPublicIp || $ip === '127.0.0.1' || $ip === '::1') {
        return;
    }

    // 2. [INTELLIGENCE] Trust bots before blocking datacenters
    if (is_bot()) {
        return;
    }

    // 3. [PERFORMANCE] Local Cache Check (24h TTL)
    $cacheDir  = dirname(__DIR__) . '/data/cache';
    // [SECURITY] Use sha256 instead of md5 to satisfy modern security standards
    $cacheFile = $cacheDir . '/ip_' . hash('sha256', $ip) . '.json';

    if (!is_dir($cacheDir)) {
        @mkdir($cacheDir, 0700, true);
    }

    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 86400) {
        $json = (string) @file_get_contents($cacheFile);
    } else {
        // 4. [PERFORMANCE] Non-blocking cURL with timeout
        // [SECURITY] Encode IP and token to prevent SSRF or parameter injection
        $safeIp    = urlencode($ip);
        $safeToken = urlencode($apiToken);
        $url       = "https://ipinfo.io/{$safeIp}/json?token={$safeToken}";

        $ch = curl_init($url);
        if (!$ch) {
            return;
        }

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 3,
            CURLOPT_CONNECTTIMEOUT => 1,
            CURLOPT_USERAGENT      => 'CmsForNerd/4.0 Performance-Bot',
            CURLOPT_PROTOCOLS      => CURLPROTO_HTTPS, // [SECURITY] Force HTTPS
            CURLOPT_REDIR_PROTOCOLS => CURLPROTO_HTTPS
        ]);

        /** @var string|false $json */
        $json = curl_exec($ch);
        curl_close($ch);

        if ($json !== false) {
            @file_put_contents($cacheFile, $json);
        }
    }

    if ($json === false || empty($json)) {
        return;
    }

    $details = json_decode($json);
    if (isset($details->asn->type) && $details->asn->type === 'hosting') {
        http_response_code(403);
        die("Data center traffic blocked. Institutional/Bot detected.");
    }
}

/**
 * [SEO/AI] Serve a lightweight text version for bots.
 *
 * @param array<string, mixed> $config The runtime configuration.
 * @return never
 */
function serve_bot_text_mode(array $config): never
{
    header('Content-Type: text/plain; charset=utf-8');
    echo "CmsForNerd v4.0.0-alpha - Laboratory Text Mode\n";
    echo "Sitemap: " . htmlspecialchars($config["sitemap_url"] ?? "/sitemap.php", ENT_QUOTES, "UTF-8");
    exit;
}
