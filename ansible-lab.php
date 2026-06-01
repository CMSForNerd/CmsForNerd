<?php
/**
 * ==========================================================================
 * FILE: ansible-lab.php
 * ROLE: Controller for Ansible Laboratory Orchestration Guide
 * VERSION: v4.0.0-alpha.1 (Modern Laboratory Engine)
 * ==========================================================================
 */

declare(strict_types=1);

if (!ob_start("ob_gzhandler")) {
    ob_start();
}

require_once __DIR__ . '/includes/bootstrap.php';

use CmsForNerd\CmsContext;

$content = [
    'title'       => "Ansible Laboratory Orchestration | CmsForNerd",
    'author'      => "CmsForNerd Team",
    'description' => "Automated Nginx and PHP 8.4-FPM deployment guide using the CmsForNerd Ansible fabric.",
    'keywords'    => "Ansible, Nginx, PHP 8.4, Automation, Lab",
    'schemaType'  => "TechArticle"
];

$pageName = \CmsForNerd\SecurityUtils::resolvePageName(pathinfo(basename(__FILE__), PATHINFO_FILENAME), 'ansible-lab');

$content['data'] = $pageName;

$ctx = createCmsContext($content, $pageName);

$pagerPath = __DIR__ . "/themes/{$ctx->themeName}/pager.php";
if (file_exists($pagerPath)) {
    require_once $pagerPath;
    pager($ctx);
} else {
    header('HTTP/1.1 500 Internal Server Error');
    echo "Fatal Error: Theme engine (pager.php) missing in /themes/{$ctx->themeName}/";
}

ob_end_flush();
