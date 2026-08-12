<?php

/**
 * ==========================================================================
 * FILE: includes/is_bot.php
 * ROLE: Hybrid Bot Intelligence & Protection (v3.5)
 * DESCRIPTION: Combines User-Agent regex with verified IP CIDR matching
 *              to classify search engine bots and defend against malicious scrapers.
 * ==========================================================================
 * Compliance: PHP 8.4+, PSR-12, PHPStan Level 8
 */

declare(strict_types=1);

/**
 * Checks if the visitor is a verified search engine crawler or bot.
 *
 * Utilizes in-memory caching and IP-to-provider range matching.
 *
 * @param string|null $userAgent Optional override for visitor user agent.
 * @param \CmsForNerd\CmsContext|null $ctx Optional rendering context carrying bot cache state.
 * @return bool True if verified as a crawler, false otherwise.
 */
function is_bot(?string $userAgent = null, ?\CmsForNerd\CmsContext $ctx = null): bool
{
    if ($ctx === null) {
        if (function_exists('createCmsContext')) {
            $ctx = createCmsContext([], 'is_bot');
        } else {
            $ctx = new \CmsForNerd\CmsContext(
                [],
                'CmsForNerd',
                'css/',
                [],
                'is_bot',
                'http://localhost/',
                'WebPage',
                ''
            );
        }
    }

    $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    $ua = $userAgent ?? $_SERVER['HTTP_USER_AGENT'] ?? '';

    if ($ctx->botCache->lastRes !== null && $ip === $ctx->botCache->lastIp && $ua === $ctx->botCache->lastUa) {
        return $ctx->botCache->lastRes;
    }

    // 1. [FAST PATH] Localhost is never a bot
    if ($ip === '127.0.0.1' || $ip === '::1') {
        $ctx->botCache->lastIp  = $ip;
        $ctx->botCache->lastUa  = $ua;
        $ctx->botCache->lastRes = false;
        return false;
    }

    if (empty($ua)) {
        $ctx->botCache->lastIp  = $ip;
        $ctx->botCache->lastUa  = $ua;
        $ctx->botCache->lastRes = false;
        return false;
    }

    // 2. [PATTERN MATCH] Primary UA check (includes gptbot, chatgpt, openai, cloudflare)
    $pattern = '/(googlebot|bingbot|yandex|baiduspider|applebot|whatsapp|' .
        'discordbot|slurp|search|gptbot|chatgpt|openai|cloudflare)/i';
    $regexMatch = (bool) preg_match($pattern, $ua);

    // 3. [TRUST BUT VERIFY] If UA looks like a bot, check the IP with provider-specific binding
    if ($regexMatch) {
        $provider = null;
        if (preg_match('/googlebot/i', $ua)) {
            $provider = 'Google';
        } elseif (preg_match('/bingbot/i', $ua)) {
            $provider = 'Bing';
        } elseif (preg_match('/cloudflare/i', $ua)) {
            $provider = 'Cloudflare';
        } elseif (preg_match('/gptbot/i', $ua)) {
            $provider = 'GPTBot';
        } elseif (preg_match('/searchbot/i', $ua)) {
            $provider = 'SearchBot';
        } elseif (preg_match('/chatgpt-user/i', $ua)) {
            $provider = 'ChatGPT-User';
        }

        if (is_trusted_bot_ip($ip, $provider)) {
            $ctx->botCache->lastIp  = $ip;
            $ctx->botCache->lastUa  = $ua;
            $ctx->botCache->lastRes = true;
            return true;
        }
    }

    $ctx->botCache->lastIp  = $ip;
    $ctx->botCache->lastUa  = $ua;
    $ctx->botCache->lastRes = false;
    return false;
}

/**
 * Verifies if a given IP address belongs to a trusted search engine or provider.
 *
 * Matches against a pre-compiled JSON database of official ranges.
 *
 * @param string $ip Client IP address to verify.
 * @param string|null $provider Specific provider slug (e.g. 'Google', 'Bing') or null for all.
 * @return bool True if IP is in the provider's official range, false otherwise.
 */
function is_trusted_bot_ip(string $ip, ?string $provider = null): bool
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
        if ($provider !== null && $bot['name'] !== $provider) {
            continue;
        }
        foreach ($bot['prefixes'] as $prefix) {
            if (ip_in_range($ip, $prefix)) {
                return true;
            }
        }
    }

    return false;
}

/**
 * Checks if an IP address is contained within a specific CIDR subnet range.
 *
 * Supports both IPv4 and IPv6 addresses.
 *
 * @param string $ip Client IP address.
 * @param string $range Target range, potentially with CIDR bitmask component (e.g., /24, /121).
 * @return bool True if IP falls within the range, false otherwise.
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
 * Updates the trusted IP database from official provider URLs.
 * Optimized with high-performance concurrent cURL (curl_multi) requests.
 *
 * @return array<string, mixed> Updated bot database with timestamps.
 */
function update_trusted_bot_ips(): array
{
    // [PERFORMANCE] Highly-optimized asynchronous curl_multi parallel network operations
    /** @var array<string, string> $sources */
    $sources = [
        'Google'       => 'https://developers.google.com/search/apis/ipranges/googlebot.json',
        'Bing'         => 'https://www.bing.com/toolbox/bingbot.json',
        'Cloudflare'   => 'https://api.cloudflare.com/client/v4/ips',
        'GPTBot'       => 'https://openai.com/gptbot.json',
        'SearchBot'    => 'https://openai.com/searchbot.json',
        'ChatGPT-User' => 'https://openai.com/chatgpt-user.json',
    ];

    $dataPath = dirname(__DIR__) . '/data/trusted-bots.json';

    // Load existing trusted-bot dataset to fallback to if a request fails/times out
    $existingBots = [];
    if (file_exists($dataPath)) {
        $existingData = json_decode((string)file_get_contents($dataPath), true);
        if (is_array($existingData) && isset($existingData['bots'])) {
            foreach ($existingData['bots'] as $bot) {
                if (isset($bot['name']) && isset($bot['prefixes'])) {
                    $existingBots[$bot['name']] = $bot['prefixes'];
                }
            }
        }
    }

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
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_multi_remove_handle($mh, $ch);
        curl_close($ch);

        $prefixes = [];
        $success = false;

        if ($response && $code === 200) {
            $data = json_decode($response, true);
            if (is_array($data)) {
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

                $prefixes = array_values(array_unique(array_filter($prefixes)));
                if (!empty($prefixes)) {
                    $success = true;
                }
            }
        }

        if ($success) {
            // Replace with validated new prefixes
            $results['bots'][] = [
                'name'     => $name,
                'prefixes' => $prefixes
            ];
        } elseif (isset($existingBots[$name])) {
            // Retain last known prefixes on request timeout, error, malformed JSON, or empty prefixes
            $results['bots'][] = [
                'name'     => $name,
                'prefixes' => $existingBots[$name]
            ];
        }
    }

    curl_multi_close($mh);

    file_put_contents($dataPath, json_encode($results, JSON_PRETTY_PRINT));

    return $results;
}

/**
 * Fetches geolocation/ASN metadata for a visitor IP address.
 *
 * Automatically caches queries for 24h using APCu and local disk storage.
 *
 * @param string $ip Target IP address.
 * @param string $token ipinfo.io developer token.
 * @return object|null Geolocation/ASN details object, or null on error.
 */
function fetch_ip_details(string $ip, string $token): ?object
{
    $cacheKey = hash('sha256', $ip);
    $apcuKey = 'ip_info_' . $cacheKey;
    $ttl = 86400; // 24-hour TTL
    $json = null;

    // 1. First Tier: APCu Memory Cache
    if (function_exists('apcu_fetch')) {
        $success = false;
        $cached = apcu_fetch($apcuKey, $success);
        if ($success && is_string($cached) && !empty($cached)) {
            $json = $cached;
        }
    }

    $cacheDir = dirname(__DIR__) . '/data/cache';
    $cacheFile = $cacheDir . '/ip_' . $cacheKey . '.json';

    // 2. Second Tier: File-based Cache fallback
    if (!is_string($json)) {
        if (file_exists($cacheFile) && (time() - (int)filemtime($cacheFile)) < $ttl) {
            $json = @file_get_contents($cacheFile);
            if (is_string($json) && !empty($json) && function_exists('apcu_store')) {
                @apcu_store($apcuKey, $json, $ttl);
            }
        }
    }

    // 3. Cache Miss: API Query via cURL
    if (!is_string($json) || empty($json)) {
        $ch = curl_init();
        if ($ch !== false) {
            $url = "https://ipinfo.io/" . urlencode($ip) . "/json?token=" . urlencode($token);
            /** @var non-falsy-string $url */
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 2);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 5);

            // Avoid CURLOPT_PROTOCOLS deprecation warning in PHP 8.1+
            if (defined('CURLOPT_PROTOCOLS_STR')) {
                curl_setopt($ch, CURLOPT_PROTOCOLS_STR, 'https');
            } else {
                curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS);
            }

            /** @var non-empty-string $uaString */
            $uaString = 'CMSForNerd-Bot-Intelligence/4.0';
            curl_setopt($ch, CURLOPT_USERAGENT, $uaString);

            $result = curl_exec($ch);
            curl_close($ch);

            if (is_string($result) && !empty($result)) {
                $json = $result;
                // Verify valid JSON structure before caching
                $decoded = json_decode($json);
                if (json_last_error() === JSON_ERROR_NONE && is_object($decoded)) {
                    if (!is_dir($cacheDir)) {
                        @mkdir($cacheDir, 0777, true);
                    }
                    @file_put_contents($cacheFile, $json, LOCK_EX);
                    if (function_exists('apcu_store')) {
                        @apcu_store($apcuKey, $json, $ttl);
                    }
                }
            }
        }
    }

    if ($json === null || $json === false || empty($json)) {
        return null;
    }

    $decoded = json_decode($json);
    return is_object($decoded) ? $decoded : null;
}

/**
 * Restricts access for hosting datacenters and proxies (non-residential).
 *
 * @param string $token ipinfo.io API token.
 * @return void
 */
function block_datacenter_traffic(string $token): void
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

    // 1. [PERFORMANCE] Localhost & Validation check with SSRF Protection
    if ($ip === '127.0.0.1' || $ip === '::1') {
        return;
    }
    $isValidIp = (bool)filter_var(
        $ip,
        FILTER_VALIDATE_IP,
        FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
    );
    if (!$isValidIp) {
        return;
    }

    // 2. [INTELLIGENCE] Trust bots before blocking datacenters
    if (is_bot()) {
        return;
    }

    $details = fetch_ip_details($ip, $token);
    if ($details !== null && isset($details->asn->type) && $details->asn->type === 'hosting') {
        http_response_code(403);
        die("Data center traffic blocked. Institutional/Bot detected.");
    }
}

/**
 * Serves lightweight plain text version for verified bots.
 *
 * @param array<string, mixed> $config Runtime environment configurations.
 * @return never
 */
function serve_bot_text_mode(array $config): void
{
    header('Content-Type: text/plain; charset=utf-8');
    echo "CmsForNerd v3.5 - Laboratory Text Mode\n";
    echo "Sitemap: " . ($config['sitemap_url'] ?? '/sitemap.php');
    exit;
}
