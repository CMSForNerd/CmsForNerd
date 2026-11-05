#!/usr/bin/env bash
# ==============================================================================
# Purpose     : Install Composer PHP dependencies for CMSForNerd laboratory.
# Prerequisites: Composer v2.x and PHP runtime.
# Usage       : bash setup.sh
# Exit Codes  : 0 on success, non-zero on failure.
# ==============================================================================
set -e

# Inform the operator of dependency installation
echo '[INFO] Installing composer dependencies...'

# Run composer install bypassing only the PHP version restriction while preserving extensions
composer install --ignore-platform-req=php
