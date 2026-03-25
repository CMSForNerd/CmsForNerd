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
 * 5. [LAB] GLOBAL CONTEXT FACTORY (Refactored for Theme Independence)
 * EDUCATIONAL NOTE: We use nullable types (?) and default values to allow
 * simple calls from controllers while maintaining theme-wide settings here.
 *
 * @param array<string, mixed> $content
 * @param string $pageName
 * @return \CmsForNerd\CmsContext
 */
function createCmsContext(
    array $content,
    string $pageName,
    ?string $themeName = null,
    ?string $cssPath = null,
    ?array $dataFile = null,
    ?string $nonce = null
): \CmsForNerd\CmsContext {
    // [THEME INDEPENDENCE] Access the centralized variables defined below.
    global $globalThemeName, $globalCssPath, $globalNonce, $globalDataFile;

    return new \CmsForNerd\CmsContext(
        content:    $content,
        themeName:  $themeName ?? $globalThemeName,
        cssPath:    $cssPath   ?? $globalCssPath,
        dataFile:   $dataFile  ?? $globalDataFile,
        scriptName: $pageName,
        cspNonce:   $nonce     ?? $globalNonce
    );
}

// 6. [LAB] SET SECURITY HEADERS
header("X-Content-Type-Options: nosniff"); //
header("X-Frame-Options: DENY"); //

// 7. [LAB] Initialization Phase
\CmsForNerd\boot_security(); //
$config = \CmsForNerd\get_runtime_config(); //

/**
 * 8. [LAB] CENTRALIZED THEME CONFIGURATION
 * Change your theme here once, and it propagates everywhere.
 */
$globalThemeName = (string) ($config['THEMENAME']   ?? 'CmsForNerd'); //
$globalCssPath   = (string) ($config['CSSPATH']     ?? "themes/{$globalThemeName}/css/"); //
$globalNonce     = $nonce;

// 9. [LAB] Routing Preparation
$scriptName      = basename($_SERVER['SCRIPT_NAME']); //
$globalDataFile  = explode(".", $scriptName); //

// 10. [LAB] CLASS VERIFICATION
if (!class_exists('\\CmsForNerd\\CmsContext')) { //
    error_log("Critical: CmsContext class not found in " . __FILE__); //
    die("Laboratory Engine Error: Core components missing."); //
}
