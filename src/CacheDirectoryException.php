<?php

/**
 * ==========================================================================
 * FILE: src/CacheDirectoryException.php
 * ROLE: Dedicated Exception for Cache Directory Failures
 * DESCRIPTION: Thrown when the performance cache directory cannot be created
 *              or accessed with standard restrictive permissions.
 * ==========================================================================
 * Compliance: PHP 8.4+, PSR-12, PHPStan Level 8
 */

declare(strict_types=1);

namespace CmsForNerd;

/**
 * Class CacheDirectoryException
 *
 * Specific exception thrown when the core cache directory creation or resolution fails.
 *
 * @package CmsForNerd
 */
class CacheDirectoryException extends \RuntimeException
{
}
