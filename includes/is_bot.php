<?php

/**
 * ==========================================================================
 * FILE: /includes/is_bot.php
 * ROLE: Hybrid Bot Intelligence & Protection (v3.5)
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
 */
function ip_in_range(string $ip, string $range): bool
{
    if (str_contains($range, '/')) {
        [$subnet, $bits] = explode('/', $range);
        $bits = (int)$bits;
    } else {
        $subnet = $range;
        $bits = str_contains($ip, ':') ? 128 : 32;
    }

    if (str_contains($ip, ':') !== str_contains($subnet, ':')) {
        return false; // Type mismatch
    }

    if (!str_contains($ip, ':')) {
        // IPv4
        $ipLong = ip2long($ip);
        $subnetLong = ip2long($subnet);
        $mask = -1 << (32 - $bits);
        return ($ipLong & $mask) === ($subnetLong & $mask);
    } else {
        // IPv6
        $ipBin = inet_pton($ip);
        $subnetBin = inet_pton($subnet);
        if ($ipBin === false || $subnetBin === false) {
            return false;
        }

        $mask = "";
        for ($i = 0; $i < 16; $i++) {
            if ($bits >= 8) {
                $mask .= chr(255);
                $bits -= 8;
            } elseif ($bits > 0) {
                $mask .= chr(256 - (1 << (8 - $bits)));
                $bits = 0;
            } else {
                $mask .= chr(0);
            }
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
 */
function block_datacenter_traffic(string $token): void
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

    // 1. [PERFORMANCE] Localhost check
    if ($ip === '127.0.0.1' || $ip === '::1') {
        return;
    }

    // 2. [INTELLIGENCE] Trust bots before blocking datacenters
    if (is_bot()) {
        return;
    }

    $json = @file_get_contents("https://ipinfo.io/{$ip}/json?token={$token}");
    if ($json === false) {
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
function serve_bot_text_mode(array $config): void
{
    header('Content-Type: text/plain; charset=utf-8');
    echo "CmsForNerd v3.5 - Laboratory Text Mode\n";
    echo "Sitemap: " . ($config['sitemap_url'] ?? '/sitemap.php');
    exit;
}
