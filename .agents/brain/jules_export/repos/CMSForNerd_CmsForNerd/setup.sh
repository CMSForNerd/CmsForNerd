#!/usr/bin/env bash
set -e
echo 'Installing composer dependencies...'
composer install --ignore-platform-req=php
