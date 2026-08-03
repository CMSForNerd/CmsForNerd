<?php

/**
 * CmsForNerd - Centralized Bootstrap (Laboratory Engine v3.5)
 * Compliance: PHP 8.4+, PSR-12, PHPStan Level 8
 * * SECURITY NOTE: This file manages error suppression and path abstraction
 * to prevent Information Disclosure (CWE-200).
 */

declare(strict_types=1);

//

// 1. [LAB] ERROR MANAGEMENT & PATH PROTECTION
// In Laboratory mode, we hide absolute system paths from the browser.
ini_set('display_errors', '0'); //
ini_set('log_errors', '1'); //
error_reporting(E_ALL); //

// 2. [LAB] Load the Composer Autoloader
// dirname(__DIR__) is used to locate the root safely.
require_once dirname(__DIR__) . '/vendor/autoload.php';

// 3. [LAB] Load Function Declarations
require_once __DIR__ . '/global-control.inc.php'; //
require_once __DIR__ . '/common.inc.php'; //

/**
 * [V3.5 AMP UPDATE] LOAD NAV HELPERS
 * We include this here so that pageheader_amp() is globally available
 * to the Theme Pager.
 */
require_once __DIR__ . '/nav-helper.inc.php';

/**
 * 4. [LAB] SECURITY & NONCE GENERATION
 * Generates a unique 128-bit cryptographically strong nonce for CSP.
 */
$nonce = bin2hex(random_bytes(16)); //

/**
 * Creates the CMS context for a page using request, theme, asset, and security settings.
 *
 * Pages without an explicit schema type are classified based on their title and body content.
 *
 * @param array<string, mixed> $content Page content and metadata.
 * @param string $pageName Page identifier used for the context and body content lookup.
 * @param string|null $themeName Theme name, or the registered default.
 * @param string|null $cssPath Theme stylesheet path, or the registered default.
 * @param array<int, string>|null $dataFile Data-file references, or the registered defaults.
 * @param string|null $nonce Content Security Policy nonce, or the registered nonce.
 * @return \CmsForNerd\CmsContext The configured CMS context.
 */
function createCmsContext(
    array $content,
    string $pageName,
    ?string $themeName = null,
    ?string $cssPath = null,
    ?array $dataFile = null,
    ?string $nonce = null
): \CmsForNerd\CmsContext {
    // [LAB] ROOT URL CALCULATION
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host     = $_SERVER['HTTP_HOST'] ?? 'localhost';

    $scriptPath = $_SERVER['SCRIPT_NAME'] ?? '';
    $scriptDir  = str_replace('\\', '/', dirname($scriptPath));

    if (str_contains($scriptDir, '/docs')) {
        $baseUrlVal = rtrim($protocol . $host . str_replace('/docs', '', $scriptDir), '/') . '/';
    } else {
        $baseUrlVal = rtrim($protocol . $host . $scriptDir, '/') . '/';
    }

    // [v3.6] Automated Semantic Detection logic
    $isLab = (str_contains($content['title'] ?? '', 'Lab') || str_contains($content['title'] ?? '', 'Module'));

    $hasCode = false;
    if (!isset($content['schemaType']) && !$isLab) {
        $bodyPath = dirname(__DIR__) . "/contents/{$pageName}-body.inc";
        if (file_exists($bodyPath)) {
            $bodyContent = (string) file_get_contents($bodyPath);
            $hasCode = (str_contains($bodyContent, '<?php') || str_contains($bodyContent, '<code>'));
        }
    }

    $schemaType = (string) ($content['schemaType'] ?? 'WebPage');
    if (!isset($content['schemaType'])) {
        $schemaType = $isLab ? 'Course' : ($hasCode ? 'TechArticle' : 'WebPage');
    }

    return new \CmsForNerd\CmsContext(
        content:    $content,
        themeName:  $themeName ?? (string) \CmsForNerd\Registry::get('themeName', 'CmsForNerd'),
        cssPath:    $cssPath   ?? (string) \CmsForNerd\Registry::get('cssPath', 'themes/CmsForNerd/css/'),
        dataFile:   $dataFile  ?? (array) \CmsForNerd\Registry::get('dataFile', []),
        scriptName: $pageName,
        baseUrl:    $baseUrlVal,
        schemaType: $schemaType,
        cspNonce:   $nonce     ?? (string) \CmsForNerd\Registry::get('nonce', '')
    );
}

// 6. [LAB] SET SECURITY HEADERS & INTRODUCE OWASP COMPLIANCE
\CmsForNerd\SecurityUtils::validateRequestMethod();
\CmsForNerd\SecurityUtils::sendSecurityHeaders();
\CmsForNerd\SecurityUtils::startSecureSession();

// 7. [LAB] Initialization Phase
\CmsForNerd\boot_security();
$config = \CmsForNerd\get_runtime_config();

/**
 * 8. [SECURITY] Centralized Bot & Turnstile Hardening
 */
if (file_exists(__DIR__ . '/turnstile.php')) {
    require_once __DIR__ . '/turnstile.php';
}

if (file_exists(__DIR__ . '/is_bot.php')) {
    require_once __DIR__ . '/is_bot.php';
    if (is_bot()) {
        serve_bot_text_mode($config);
    }
}

/**
 * 9. [LAB] CENTRALIZED THEME CONFIGURATION
 * We store these in the Registry for Zero-Global compliance.
 */
\CmsForNerd\Registry::set('themeName', (string) ($config['THEMENAME'] ?? 'CmsForNerd'));
\CmsForNerd\Registry::set(
    'cssPath',
    (string) ($config['CSSPATH'] ?? "themes/" . \CmsForNerd\Registry::get('themeName') . "/css/")
);
\CmsForNerd\Registry::set('nonce', $nonce);

// 10. [LAB] Routing Preparation
$scriptName = basename($_SERVER['SCRIPT_NAME']);
\CmsForNerd\Registry::set('dataFile', explode(".", $scriptName));

// [COMPATIBILITY] Alias for legacy controllers (non-recommended)
$themeName = (string) \CmsForNerd\Registry::get('themeName');
$cssPath   = (string) \CmsForNerd\Registry::get('cssPath');
$dataFile  = (array) \CmsForNerd\Registry::get('dataFile');
// $nonce is already set above

// 11. [LAB] CLASS VERIFICATION
if (!class_exists('\\CmsForNerd\\CmsContext')) {
    error_log("Critical: CmsContext class not found in " . __FILE__);
    die("Laboratory Engine Error: Core components missing.");
}
