<?php

/**
 * CmsForNerd - Default Theme Configuration
 * This file is included within the scope of CmsForNerd\get_runtime_config().
 * It inherits the $themeName variable from that function.
 *
 * @package linuxmalaysia/cmsfornerd
 * @version 4.3.0
 */

declare(strict_types=1);

namespace CmsForNerd;

// [SECURITY] Prevent direct access if not called through the bootstrap function
if (!isset($themeName)) {
    header('HTTP/1.1 403 Forbidden');
    exit('Direct access forbidden.');
}

// [THEME] Assets Configuration
// This defines the specific stylesheet used by the laboratory theme.
$CSSPATH = "/themes/$themeName/style.css";

// [METADATA] Theme Information
// Useful for future updates or identifying the environment version.
$THEME_VERSION = "4.3.0";
$THEME_AUTHOR  = "Harisfazillah Jamel";

// [EXEMPTION] This global guard block must reside directly in theme.php (rather than get_runtime_config)
// because it is strictly verified by the automated ThemeMetadataGuardTest suite, which validates
// include-time empty() semantics and error logging for theme metadata variants.
if (empty($THEME_VERSION) || empty($THEME_AUTHOR)) {
    error_log("Theme metadata is incomplete.");
}

/**
 * [EXTENSIBILITY] Additional Theme Logic
 * You can define theme-specific constants or configurations here
 * without polluting the global namespace.
 */
