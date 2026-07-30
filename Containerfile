# ==============================================================================
# Protocol    : Deep State of Mind (DSOM) For My AI
# Author      : Harisfazillah Jamel (LinuxMalaysia)
# Timestamp   : 2026-07-30
# License     : GNU General Public License v3.0
# Standard    : UK English | DBP-standard Bahasa Melayu Malaysia (Piawai)
# ==============================================================================
# =============================================================================
# CmsForNerd v4.1.0 - Containerfile for Render Production Deployments
# Standards: PHP 8.4, Apache, Composer 2.x
# =============================================================================

FROM php:8.4-apache AS builder

# Install required system libraries and PHP extensions for both build and runtime
RUN apt-get update && apt-get install -y --no-install-recommends \
    libzip-dev \
    unzip \
    && docker-php-ext-install zip \
    && rm -rf /var/lib/apt/lists/*

# Install Composer in the builder stage
COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY composer.json composer.lock ./

# Validate platform requirements and install production dependencies
RUN composer check-platform-reqs && \
    composer install --no-dev --no-scripts --no-progress --optimize-autoloader --no-interaction

FROM php:8.4-apache

# Install required system libraries and clean package lists
RUN apt-get update && apt-get install -y --no-install-recommends \
    libzip-dev \
    unzip \
    && docker-php-ext-install zip \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite and mod_headers for routing and .htaccess security headers
RUN a2enmod rewrite headers

# Configure Apache to allow .htaccess overrides only for /var/www/html DocumentRoot
RUN sed -i '/<Directory \/var\/www\/html>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Set active working directory
WORKDIR /var/www/html

# Copy vendor dependencies from the composer builder stage
COPY --from=builder /app/vendor /var/www/html/vendor

# Copy the application source code
COPY . /var/www/html/

# Ensure web server has ownership only over directories requiring runtime writes
# Application source and vendor remain owned by root for security
RUN chown -R www-data:www-data /var/www/html/data /var/www/html/contents

# Expose standard web port
EXPOSE 80
