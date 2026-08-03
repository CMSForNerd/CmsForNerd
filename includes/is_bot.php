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
        $lastIp  = $ip;
        $lastUa  = $ua;
        return $lastRes = false;
    }

    if (empty($ua)) {
        $lastIp  = $ip;
        $lastUa  = $ua;
        return $lastRes = false;
    }

    // 2. [PATTERN MATCH] Primary UA check (includes gptbot, chatgpt, openai, cloudflare)
    $pattern = '/(googlebot|bingbot|yandex|baiduspider|applebot|whatsapp|' .
        'discordbot|slurp|search|gptbot|chatgpt|openai|cloudflare)/i';
    $regexMatch = (bool) preg_match($pattern, $ua);

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
        $bits = max(0, min(32, $bits));
        $ipLong = ip2long($ip);
        $subnetLong = ip2long($subnet);
        $mask = -1 << (32 - $bits);
        return ($ipLong & $mask) === ($subnetLong & $mask);
    } else {
        // IPv6 - Architectural Hardening for Performance & Security
        $bits = max(0, min(128, $bits));
        $ipBin = inet_pton($ip);
        $subnetBin = inet_pton($subnet);
        if ($ipBin === false || $subnetBin === false) {
            return false;
        }

        // Pre-allocate fixed 16-byte mask using fast native string repetition to prevent DoS vector
        $fullBytes = $bits >> 3;
        $remainingBits = $bits & 7;

        $mask = str_repeat("\xFF", $fullBytes);
        if ($remainingBits > 0) {
            $mask .= chr((0xFF00 >> $remainingBits) & 0xFF);
        }
        $mask = str_pad($mask, 16, "\x00");

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
        'Google'       => 'https://developers.google.com/search/apis/ipranges/googlebot.json',
        'Bing'         => 'https://www.bing.com/toolbox/bingbot.json',
        'Cloudflare'   => 'https://api.cloudflare.com/client/v4/ips',
        'GPTBot'       => 'https://openai.com/gptbot.json',
        'SearchBot'    => 'https://openai.com/searchbot.json',
        'ChatGPT-User' => 'https://openai.com/chatgpt-user.json',
    ];

    $results = [
        'updated' => date('c'),
        'bots'    => []
    ];

    $mh = curl_multi_init();
    $handles = [];

    foreach ($sources as $name => $url) {
        $ch = curl_init();
        if ($ch === false) {
            continue;
        }

        /** @var non-empty-string $url */
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS); // Enforce HTTPS for safety
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);

        /** @var non-empty-string $uaString */
        $uaString = 'CMSForNerd-Bot-Intelligence/4.0';
        curl_setopt($ch, CURLOPT_USERAGENT, $uaString);

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

    foreach ($handles as $name => $ch) {
        $response = curl_multi_getcontent($ch);
        curl_multi_remove_handle($mh, $ch);
        curl_close($ch);

        if ($response) {
            $data = json_decode($response, true);
            if (!is_array($data)) {
                continue;
            }
            $prefixes = [];

            if (($name === 'Google' || $name === 'Bing') && isset($data['prefixes'])) {
                foreach ($data['prefixes'] as $p) {
                    if (isset($p['ipv4Prefix'])) {
                        $prefixes[] = $p['ipv4Prefix'];
                    }
                    if (isset($p['ipv6Prefix'])) {
                        $prefixes[] = $p['ipv6Prefix'];
                    }
                }
            } elseif ($name === 'Cloudflare' && isset($data['result'])) {
                if (isset($data['result']['ipv4_cidrs'])) {
                    foreach ($data['result']['ipv4_cidrs'] as $cidr) {
                        $prefixes[] = $cidr;
                    }
                }
                if (isset($data['result']['ipv6_cidrs'])) {
                    foreach ($data['result']['ipv6_cidrs'] as $cidr) {
                        $prefixes[] = $cidr;
                    }
                }
            } elseif (in_array($name, ['GPTBot', 'SearchBot', 'ChatGPT-User'], true) && isset($data['prefixes'])) {
                foreach ($data['prefixes'] as $p) {
                    if (isset($p['ipv4Prefix'])) {
                        $prefixes[] = $p['ipv4Prefix'];
                    }
                    if (isset($p['ipv6Prefix'])) {
                        $prefixes[] = $p['ipv6Prefix'];
                    }
                }
            }

            if (!empty($prefixes)) {
                $results['bots'][] = [
                    'name'     => $name,
                    'prefixes' => array_values(array_unique(array_filter($prefixes)))
                ];
            }
        }
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

    // 1. [PERFORMANCE] Localhost & Validation check
    if ($ip === '127.0.0.1' || $ip === '::1' || !filter_var($ip, FILTER_VALIDATE_IP)) {
        return;
    }

    // 2. [INTELLIGENCE] Trust bots before blocking datacenters
    if (is_bot()) {
        return;
    }

    // Secure IP lookup with validated input
    $ctx = stream_context_create(['http' => ['timeout' => 2]]);
    $json = @file_get_contents("https://ipinfo.io/" . urlencode($ip) . "/json?token=" . urlencode($token), false, $ctx);
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
function serve_bot_text_mode(array $config): void
{
    header('Content-Type: text/plain; charset=utf-8');
    echo "CmsForNerd v3.5 - Laboratory Text Mode\n";
    echo "Sitemap: " . ($config['sitemap_url'] ?? '/sitemap.php');
    exit;
}
