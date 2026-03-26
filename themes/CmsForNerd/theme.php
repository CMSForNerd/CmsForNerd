<?php

/**
 * CmsForNerd - Default Theme Configuration
 * This file is included within the scope of CmsForNerd\get_runtime_config().
 * It inherits the $themeName variable from that function.
 *
 * @package linuxmalaysia/cmsfornerd
 * @version 3.5.0
 */

declare(strict_types=1);

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
$THEME_VERSION = "3.5.0";
$THEME_AUTHOR  = "Harisfazillah Jamel";
$THEME_NAME    = "CmsForNerd Laboratory";

/**
 * [EXTENSIBILITY] Additional Theme Logic
 * You can define theme-specific constants or configurations here
 * without polluting the global namespace.
 */
