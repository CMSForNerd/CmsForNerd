# 🤖 Bot Intelligence: Hybrid Protection (v4.0.0)

To maintain a "Zero-Error" laboratory, we use a hybrid bot detection system. This ensures that legitimate crawlers (Google, Bing, Apple) are recognized even if their User-Agents are stripped, while keeping performance lean.

## 1. Executive Summary (The 5 W's)

*   **What**: A defense-in-depth module combining User-Agent regex with IP-based CIDR verification.
*   **Why**: To distinguish between legitimate search engines (SEO) and malicious scrapers/spam bots. Malicious bots often spoof User-Agents, but they cannot spoof their IP source in the same way.
*   **Where**: Located in [includes/is_bot.php](../includes/is_bot.php).
*   **When**: 
    *   Triggered whenever metadata or performance layers need to decide if they are serving a human or a crawler.
    *   Used in `block_datacenter_traffic()` to allow legitimate bots to pass while blocking institutional scrapers.
*   **How**: It uses a "Fast-Path" for localhost, followed by a RegEx UA check, and finally a "Trust-but-Verify" IP lookup against a local JSON database.

## 2. Implementation: How it Works

The logic flow in [is_bot.php](../includes/is_bot.php) is designed for maximum speed:

1.  **Fast Path**: If the IP is `127.0.0.1`, it immediately returns `false` (Not a bot).
2.  **Context-Aware Cache**: It stores the result of the last detection. If the IP and User-Agent haven't changed since the last call, it returns the cached result (0ms overhead).
3.  **Pattern Match**: It checks the `HTTP_USER_AGENT` against known strings like `googlebot`, `bingbot`, `gptbot`, `chatgpt`, `openai`, or `cloudflare`.
4.  **CIDR Verification**: If the UA matches, it verifies the IP against [data/trusted-bots.json](../data/trusted-bots.json).

## 3. Data Management

The project uses a local database for verified ranges. This ensures we don't make external API calls on every request.

### Automated Update
Run the following command to sync with official Google/Bing/Cloudflare/OpenAI endpoints:
```bash
composer update-bots
```
This executes `update_trusted_bot_ips()` which concurrently fetches live CIDR blocks from:
*   **Google**: [googlebot.json](https://developers.google.com/search/apis/ipranges/googlebot.json) (with redirect following)
*   **Bing**: [bingbot.json](https://www.bing.com/toolbox/bingbot.json)
*   **Cloudflare**: [api.cloudflare.com](https://api.cloudflare.com/client/v4/ips)
*   **OpenAI (GPTBot, SearchBot, ChatGPT-User)**: [gptbot.json](https://openai.com/gptbot.json), [searchbot.json](https://openai.com/searchbot.json), [chatgpt-user.json](https://openai.com/chatgpt-user.json)

### Manual Overrides
Developers can manually add IP ranges to `data/trusted-bots.json`.
```json
{
  "name": "Custom-Bot",
  "prefixes": ["1.2.3.4/32"]
}
```

## 4. Performance Safeguards

1.  **Lazy Loading**: The IP list is ONLY loaded from disk if a potential bot is detected via User-Agent.
2.  **Static Result Cache**: Optimized for multiple calls within a single execution cycle.
3.  **Localhost Exclusion**: Zero latency during local development.

## 5. Maintenance

Regularly run `composer update-bots` to keep the CIDR ranges current. This is critical as search engine IP ranges can change periodically.

---

> **Note to AI Agents:** This module (`is_bot.php`) is a critical security component. Any changes to its logic or data source MUST be vetted against the **[Master Protocol](AI-MASTER-PROTOCOL.md)** to ensure no vulnerability is introduced during the State Sync.
