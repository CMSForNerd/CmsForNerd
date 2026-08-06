<?php

declare(strict_types=1);

/**
 * CmsForNerd v4.3.0 - Page Controller (exam-answers.php)
 * * ROLE: Official Answer Key: Final Exam.
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
 * [SECURITY] Instructor Key Protection
 * This page contains sensitive exam answers. Access is restricted to users
 * who provide the correct 'instructor_key' via GET parameter.
 */
$instructorKey = (string) ($config['INSTRUCTOR_KEY'] ?? 'NERD-LAB-2025');
if ((\CmsForNerd\Registry::get('instructor_mode') !== true) &&
    (($_GET['key'] ?? '') !== $instructorKey)) {
    http_response_code(403);
    die("Access Denied: Instructor Key Required.");
}

/**
 * 3. [SEO/AI] Page Metadata
 */
$content = [
    'title'          => "Official Answer Key: Final Exam | CMSForNerd v4.3.0",
    'author'         => "CMSForNerd Team & Google Gemini",
    'description'    => "Instructor grading rubric and official logic solutions for the CMSForNerd v4.3.0 Final Exam.",
    'keywords'       => "Answer Key, Grading Rubric, PHP 8.4 Hooks, Security Audit, PSR-12",
    'robots'         => "noindex, nofollow", // Keep exam answers out of public search engines
    'schemaType'     => "EducationalOccupationalCredential"
];

/**
 * 4. [LAB] ROUTING & SANITIZATION
 */
$pageName = \CmsForNerd\SecurityUtils::resolvePageName(pathinfo(basename(__FILE__), PATHINFO_FILENAME));

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
