<?php

/**
 * CmsForNerd - Common UI Helpers
 * These functions handle the standard HTML structure for the laboratory CMS.
 */

declare(strict_types=1);

// [SEO/HTML5] pageheader() builds the REQUIRED top part of every HTML document.
function pageheader(CmsForNerd\CmsContext $ctx): void
{
    // [SEO/AI] Determine Schema.org type based on page content
    $schemaType = $ctx->content['schemaType'] ?? 'WebPage';

    // [SEO/AI] Determine language (default: English)
    $pageLang = $ctx->content['lang'] ?? 'en';

    // [SEO] The DOCTYPE tells the browser we MUST use modern HTML5.
    print("<!DOCTYPE html>\n");

    // [RFC 2119] MUST: The HTML tag MUST include itemscope and itemtype (Microdata for AI)
    print('<html lang="' . htmlspecialchars($pageLang, ENT_QUOTES, 'UTF-8') . '" ');
    print('itemscope itemtype="https://schema.org/');
    print(htmlspecialchars($schemaType, ENT_QUOTES, 'UTF-8') . '">' . "\n");

    print("<head>\n");

    // [STRUCTURE] common-headertag.inc MUST be included for CSP and Meta tags.
    include "contents/common-headertag.inc";

    // [SEO] Page titles SHOULD be descriptive and dynamic.
    $title = htmlspecialchars($ctx->content['title'] ?? 'CMSForNerd', ENT_QUOTES, 'UTF-8');
    print("<title>{$title}</title>\n");

    /**
     * [V3.5 AMP DISCOVERY]
     * EDUCATIONAL NOTE: This "Handshake" tells search engines that a mobile-optimized
     * AMP version exists. The Pager handles the actual view switching when the
     * '?view=amp' parameter is detected in the URL.
     */
    $ampUrl = htmlspecialchars($ctx->scriptName . '.php?view=amp', ENT_QUOTES, 'UTF-8');
    print('    <link rel="amphtml" href="' . $ampUrl . '">' . "\n");

    // [PWA FOUNDATION] Include Manifest and Theme Color for installability
    print("    <link rel=\"manifest\" href=\"/manifest.json\">\n");
    print("    <meta name=\"theme-color\" content=\"#0d6efd\">\n");

    // [THEME] Stylesheets SHOULD be imported via the configured cssPath.
    // Cache-busting: ?v=4.0.0 is used to force-refresh the Phase 11 Glassmorphism architecture.
    print("    <style type=\"text/css\" media=\"all\">@import \"{$ctx->cssPath}?v=4.0.0\";</style>\n");

    // [SECURITY] Turnstile script MUST be loaded to protect forms.
    $nonce = htmlspecialchars($ctx->cspNonce, ENT_QUOTES, 'UTF-8');
    $script = '    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" ';
    $script .= 'async defer nonce="' . $nonce . '"></script>' . "\n";
    print($script);

    print("</head>\n");
}

/**
 * [LOGIC] pagecontent()
 * [LAB] Module 1: Automated Content Mapping (Pair Logic).
 * This function automatically finds the content partial based on the script name.
 */
function pagecontent(CmsForNerd\CmsContext $ctx): void
{
    // We prioritize scriptName to maintain the 'index' -> 'index-body.inc' convention.
    $bodyFile = "contents/" . $ctx->scriptName . "-body.inc";

    if (file_exists($bodyFile)) {
        include $bodyFile;
    } else {
        // [LAB] Fallback: If scriptName fails, check dataFile[0] as a secondary fallback.
        $fallbackFile = "contents/" . ($ctx->dataFile[0] ?? 'index') . "-body.inc";

        if (file_exists($fallbackFile)) {
            include $fallbackFile;
        } else {
            // [LAB] Educational error message for debugging.
            echo "<div class='error-box' style='color:red; border:2px dashed red; padding:15px; margin:10px;'>";
            echo "<strong>[LAB ERROR] Content File Missing:</strong><br>";
            echo "The system expected a content file at: <code>" . htmlspecialchars($bodyFile) . "</code>";
            echo "</div>";
        }
    }
}

// [STRUCTURE] pagetailer() MUST be used to close the HTML document.
function pagetailer(CmsForNerd\CmsContext $ctx): void
{
    print("</html>");
}
