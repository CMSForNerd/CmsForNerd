<?php

declare(strict_types=1);

/**
 * CmsForNerd v4.3.0 - Page Controller (ujian-form.php)
 * * ROLE: Turnstile Bot Trap Verification with CSRF Hardening.
 * [HTML MICRODATA] Output schema: itemscope itemtype="https://schema.org/WebPage"
 *
 * @package     linuxmalaysia/cmsfornerd
 * @author      Harisfazillah Jamel <linuxmalaysia@songketmail.org>
 * @copyright   2005 - 2026 Harisfazillah Jamel
 * @license     GPL-3.0-or-later
 */

if (!ob_start("ob_gzhandler")) {
    ob_start();
}

require_once __DIR__ . '/includes/bootstrap.php';

$content = [
    'title'       => "Turnstile Test Laboratory | CmsForNerd",
    'author'      => "CMSForNerd Team",
    'description' => "Turnstile Bot Trap Verification and OWASP CSRF mitigation laboratory test page.",
    'keywords'    => "Turnstile, CSRF, Bot Trap, PHP 8.4, Security",
    'schemaType'  => "WebPage"
];

$pageName = \CmsForNerd\SecurityUtils::resolvePageName(pathinfo(basename(__FILE__), PATHINFO_FILENAME));
$content['data'] = $pageName;

$ctx = createCmsContext(
    content: $content,
    pageName: $pageName,
    themeName: $themeName,
    cssPath: $cssPath,
    dataFile: $dataFile,
    nonce: $nonce
);

$pagerPath = __DIR__ . "/themes/{$ctx->themeName}/pager.php";
if (file_exists($pagerPath)) {
    require_once $pagerPath;
    pager($ctx);
} else {
    header('HTTP/1.1 500 Internal Server Error');
    echo "Fatal Error: Theme engine (pager.php) missing in /themes/{$ctx->themeName}/";
}

ob_end_flush();
