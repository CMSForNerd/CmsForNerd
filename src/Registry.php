<?php

/**
 * [CORE] Registry - The Zero-Global State Storage
 * This class provides a centralized location for dynamic laboratory settings
 * without relying on the legacy 'global' keyword.
 * Compliance: PHP 8.4+, PSR-12, PHPStan Level 8
 */

declare(strict_types=1);

namespace CmsForNerd;

class Registry
{
    /** @var array<string, mixed> Storage for dynamic configuration tokens */
    public static array $data = [];

    /**
     * Set a value in the registry.
     * @param string $key
     * @param mixed $value
     */
    public static function set(string $key, mixed $value): void
    {
        self::$data[$key] = $value;
    }

    /**
     * Get a value from the registry.
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return self::$data[$key] ?? $default;
    }
}
