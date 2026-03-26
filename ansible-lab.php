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

// 1. Define Page-Specific Header Metadata
$content = [
    'title'       => 'Ansible Laboratory Orchestration | CmsForNerd',
    'description' => 'Automated Nginx and PHP 8.4-FPM deployment guide using the CmsForNerd Ansible fabric.',
    'author'      => 'CmsForNerd Team',
    'keywords'    => 'Ansible, Nginx, PHP 8.4, Automation, Lab',
    'schemaType'  => 'TechArticle'
];

$pageName = 'ansible-lab';

// 2. Initialize Laboratory Context (Global-Free Pattern)
$ctx = createCmsContext($content, $pageName);

// 3. Execute the Standard Laboratory Layout
require_once 'template.php';
