<?php

declare(strict_types=1);

/**
 * CMSForNerd v4.3.0 - Silent Sentry
 * Rule #8: Unauthorized directory browsing is forbidden.
 */

header('HTTP/1.1 403 Forbidden');
exit("Access Denied: Laboratory Gateway Active.");
