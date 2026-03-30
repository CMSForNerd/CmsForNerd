<?php

/**
 * [CORE] CmsContext - The State Machine for v3.5
 * This object carries all global state through the theme engine.
 * Requirement: MUST be immutable (readonly).
 * * Compliance: PHP 8.4+, PSR-12, PHPStan Level 8
 */

declare(strict_types=1);

namespace CmsForNerd;

readonly class CmsContext
{
    /**
     * @param array<string, mixed> $content Site metadata (title, author, etc.)
     * @param string $themeName The active folder in /themes/
     * @param string $cssPath   The path to the main stylesheet
     * @param array<int, string> $dataFile The exploded path info of the current request
     * @param string $scriptName The normalized name of the current page
     * @param string $baseUrl    The absolute base URL
     * @param string $schemaType The Schema.org type (v3.6)
     * @param string $cspNonce  A cryptographically secure random string for security
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
    ) {
    }
}
