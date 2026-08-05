---
okf_version: 0.1
type: skill
title: "Bot Detection and Network Operations Standards"
name: "bot-detection-and-network-ops"
description: "Guidelines and protocols for dynamic bot detection, secure network operations, SSRF mitigation, and caching."
topics: [bot-detection, network, ssrf, curl, caching]
timestamp: 2026-08-01T09:00:00Z
---

# 🤖 Bot Detection and Network Operations Standards

## Purpose
This skill provides procedural guidelines for managing bot identification, executing secure external cURL requests, preventing SSRF vulnerabilities, and caching external payloads efficiently.

## When to use this skill
Use this skill when developing network-dependent code in PHP, modifying bot-detection filters (such as `includes/is_bot.php` or `includes/bootstrap.php`), or when integrating third-party APIs.

## Guidelines & Best Practices

### 1. Dynamic IP CIDR Bot Detection
The bot detection system (`includes/is_bot.php`) supports dynamic IP CIDR checks against trusted crawlers (Google, Bing, Cloudflare, and OpenAI/ChatGPT bots like GPTBot, SearchBot, ChatGPT-User).
- These IP ranges must be fetched concurrently via `curl_multi` to avoid network delays.
- Results should be merged and stored locally in `data/trusted-bots.json`.
- In `includes/bootstrap.php`, bot detection logic must always execute *after* the `$config` variable is fully initialized, ensuring sitemap URLs are available for text-mode responses.

### 2. cURL Redirect Handling
When executing external API requests (such as fetching dynamic IP ranges in `update_trusted_bot_ips()`), always enable redirect following securely:
```php
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_MAXREDIRS, 5); // Set to a safe, low limit
```
This cleanly handles dynamic endpoints (like Google's redirecting IP JSON feed) without open-ended redirect loops.

### 3. Server-Side Request Forgery (SSRF) Prevention
When performing network calls or executing lookups based on visitor IP addresses (e.g., in `block_datacenter_traffic`), always:
- Validate the IP addresses using `filter_var()` with private/reserved range exclusions:
  ```php
  $isValid = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
  ```
- Always use `urlencode()` for any query parameters passed to external endpoints.
- Enforce explicit connection and execution timeouts.

### 4. Blocking API Calls & Secure Protocols
For blocking API calls to external services:
- Enforce strictly secure protocols: set `CURLOPT_PROTOCOLS` to `CURLPROTO_HTTPS` (or equivalent secure transport).
- Set explicit timeouts (e.g., connection timeout `CURLOPT_CONNECTTIMEOUT` of 2-3 seconds, total timeout `CURLOPT_TIMEOUT` of 5 seconds).
- Implement file-based caching (typically inside `data/cache/` with a 24-hour TTL) using SHA-256 for cache keys to prevent repeated heavy requests and fulfill security quality standards.

### 5. Asynchronous Multi-Requests
Always prefer `curl_multi` over sequential `curl_init` or blocking `file_get_contents()` calls when resolving multiple external resources, in order to minimize network I/O blockages.


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-05*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
