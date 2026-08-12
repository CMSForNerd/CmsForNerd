<?php

/**
 * ==========================================================================
 * FILE: src/CmsContext.php
 * ROLE: Core CMS Immutable Context (v4.3.0)
 * DESCRIPTION: Carries global configuration, metadata, and state through
 *              the rendering pipeline without global state variables.
 * ==========================================================================
 * Compliance: PHP 8.4+, PSR-12, PHPStan Level 8
 */

declare(strict_types=1);

namespace CmsForNerd;

/**
 * Class CmsContext
 *
 * Represents a readonly container of execution state used by controllers and templates.
 * While the CmsContext container itself is readonly (immutable), its public stdClass property
 * $botCache remains mutable to allow dynamic bot detection caching across requests.
 * The script includes/is_bot.php updates botCache fields lastIp, lastUa, and lastRes
 * after the context has been constructed.
 *
 * @package CmsForNerd
 */
readonly class CmsContext
{
    /** @var \stdClass Holds execution-mode runtime cache for bot checks. */
    public \stdClass $botCache;

    /**
     * CmsContext Constructor.
     *
     * @param array<string, mixed> $content Site metadata (title, author, content snippets, etc.).
     * @param string $themeName The directory name of the active theme under /themes/.
     * @param string $cssPath The public path to the theme's stylesheets directory.
     * @param array<int, string> $dataFile Array of data files associated with the request context.
     * @param string $scriptName The normalized name of the current execution route/page.
     * @param string $baseUrl The fully resolved base URL of the site.
     * @param string $schemaType The Schema.org vocabulary type for semantic markup (e.g. 'WebPage').
     * @param string $cspNonce A cryptographically secure nonce value for inline script protection.
     * @param \stdClass|null $botCache Optional pre-configured bot caching helper container.
     */
    public function __construct(
        public array $content,
        public string $themeName,
        public string $cssPath,
        public array $dataFile,
        public string $scriptName,
        public string $baseUrl,
        public string $schemaType = 'WebPage',
        public string $cspNonce = '',
        ?\stdClass $botCache = null,
    ) {
        $this->botCache = $botCache ?? new \stdClass();
        if (!isset($this->botCache->lastIp)) {
            $this->botCache->lastIp = '';
        }
        if (!isset($this->botCache->lastUa)) {
            $this->botCache->lastUa = '';
        }
        if (!isset($this->botCache->lastRes)) {
            $this->botCache->lastRes = null;
        }
    }
}
