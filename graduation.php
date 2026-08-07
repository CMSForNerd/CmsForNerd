<?php

declare(strict_types=1);

/**
 * CmsForNerd v4.3.0 - Page Controller (graduation.php)
 * * ROLE: Lab Graduation Page: Certificate of Completion.
 * This file is synchronized with the master template.php logic to ensure
 * total architectural consistency across the entire CMS.
 *
 * @package     linuxmalaysia/cmsfornerd
 * @author      Harisfazillah Jamel <linuxmalaysia@songketmail.org>
 * @copyright   2005 - 2026 Harisfazillah Jamel
 * @license     GPL-3.0-or-later
 */

// 1. [PERFORMANCE] Enable GZIP and Output Buffering
if (!ob_start("ob_gzhandler")) {
    ob_start();
}

/**
 * 2. [LAB] BOOTSTRAP PHASE
 */
require_once __DIR__ . '/includes/bootstrap.php';

/**
 * [SECURITY] Graduation Access Control
 * Only users who have completed the modules or provide a valid certificate ID
 * should see this page. For this lab, we use a simple 'student_id' check.
 */
if (empty($_GET['student_id'])) {
    http_response_code(403);
    die("Access Denied: Student ID required for graduation certificate.");
}

/**
 * 3. [SEO/AI] Page Metadata
 */
$content = [
    'title'       => "Graduation: PHP 8.4+ Mastery - CmsForNerd v4.3.0",
    'author'      => "CMSForNerd Team & Google Gemini",
    'description' => "Official Certificate of Completion for the CmsForNerd Modernization Curriculum.",
    'keywords'    => "Graduation, Certificate, PHP 8.4, Modern PHP, Software Engineering",
];

/**
 * 4. [LAB] ROUTING & SANITIZATION
 */
$pageName = \CmsForNerd\SecurityUtils::resolvePageName(pathinfo(basename(__FILE__), PATHINFO_FILENAME), 'graduation');

$content['data'] = $pageName;

/**
 * 5. [MODERN PHP] CmsContext Initialization (Factory Method)
 */
$ctx = createCmsContext(
    content: $content,
    pageName: $pageName,
    themeName: $themeName,
    cssPath: $cssPath,
    dataFile: $dataFile,
    nonce: $nonce
);

/**
 * [LAB] BOT DETECTION
 */

/**
 * 6. [RENDER] Theme Dispatcher
 */
$pagerPath = __DIR__ . "/themes/{$ctx->themeName}/pager.php";
if (file_exists($pagerPath)) {
    require_once $pagerPath;
    pager($ctx);
} else {
    header('HTTP/1.1 500 Internal Server Error');
    echo "Fatal Error: Theme engine missing in /themes/{$ctx->themeName}/";
}

ob_end_flush();
