<?php
/**
 * ==========================================================================
 * FILE: ansible-lab.php
 * ROLE: Controller for Ansible Laboratory Orchestration Guide
 * VERSION: v3.5.1 (Modern Laboratory Engine)
 * ==========================================================================
 */

declare(strict_types=1);

require_once 'includes/bootstrap.php';

use CmsForNerd\CmsContext;

if (!ob_start("ob_gzhandler")) {
    ob_start();
}

require_once __DIR__ . '/includes/bootstrap.php';

$content = [
    'title'       => "Ansible Laboratory Orchestration | CmsForNerd",
    'author'      => "CmsForNerd Team",
    'description' => "Automated Nginx and PHP 8.4-FPM deployment guide using the CmsForNerd Ansible fabric.",
    'keywords'    => "Ansible, Nginx, PHP 8.4, Automation, Lab",
    'schemaType'  => "TechArticle"
];

$rawPage = match (true) {
    !empty($_SERVER['QUERY_STRING']) => (string) $_SERVER['QUERY_STRING'],
    default                          => pathinfo(basename(__FILE__), PATHINFO_FILENAME)
};

$isValid = \CmsForNerd\SecurityUtils::isValidPageName($rawPage);
$page = $isValid ? $rawPage : 'ansible-lab';
$pageName = pathinfo($page, PATHINFO_FILENAME);

$content['data'] = $pageName;

$ctx = createCmsContext($content, $pageName);

if (file_exists(__DIR__ . '/includes/turnstile.php')) {
    require_once __DIR__ . '/includes/turnstile.php';
}

$pagerPath = __DIR__ . "/themes/{$ctx->themeName}/pager.php";
if (file_exists($pagerPath)) {
    require_once $pagerPath;
    pager($ctx);
} else {
    header('HTTP/1.1 500 Internal Server Error');
    echo "Fatal Error: Theme engine (pager.php) missing in /themes/{$ctx->themeName}/";
}

ob_end_flush();
