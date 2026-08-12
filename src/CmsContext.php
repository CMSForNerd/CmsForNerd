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
 * Represents an immutable snapshot of execution state used by controllers and templates.
 *
 * @package CmsForNerd
 */
readonly class CmsContext
{
    /** @var \stdClass Holds execution-mode runtime cache for bot checks. */
    public \stdClass $botCache;

    /**
     * Creates an immutable snapshot of CMS execution state for rendering.
     *
     * The bot cache container is created when omitted, and its `lastIp`, `lastUa`,
     * and `lastRes` properties are initialized when absent.
     *
     * @param array<string, mixed> $content Site metadata and content snippets.
     * @param string $themeName Directory name of the active theme.
     * @param string $cssPath Public path to the theme's stylesheets.
     * @param array<int, string> $dataFile Data files associated with the request.
     * @param string $scriptName Normalized name of the current route or page.
     * @param string $baseUrl Fully resolved base URL of the site.
     * @param string $schemaType Schema.org type used for semantic markup.
     * @param string $cspNonce Nonce used to protect inline scripts.
     * @param \stdClass|null $botCache Optional bot-cache container.
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
