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

// 1. Initialize Laboratory Context
$ctx = createCmsContext();

// 2. Define Page-Specific Header Metadata
$ctx->content['title'] = 'Ansible Laboratory Orchestration';
$ctx->content['description'] = 'Automated Nginx and PHP 8.4-FPM deployment guide using the CmsForNerd Ansible fabric.';

// 3. Execute the Standard Laboratory Layout
require_once 'template.php';
