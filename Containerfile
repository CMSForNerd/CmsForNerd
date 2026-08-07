# ==============================================================================
# Protocol    : Deep State of Mind (DSOM) For My AI
# Author      : Harisfazillah Jamel (LinuxMalaysia)
# Timestamp   : 2026-07-30
# License     : GNU General Public License v3.0
# Standard    : UK English | DBP-standard Bahasa Melayu Malaysia (Piawai)
# ==============================================================================
# =============================================================================
# CmsForNerd v4.3.0 - Containerfile for Render Production Deployments
# Standards: PHP 8.4, Apache, Composer 2.x
# =============================================================================

FROM php:8.4-apache AS builder

# Install required system libraries and PHP extensions in builder stage
RUN apt-get update && apt-get install -y --no-install-recommends \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-install zip \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
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

# Configure Apache to listen on unprivileged port 8080
RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf && \
    sed -i 's/:80>/:8080>/' /etc/apache2/sites-available/000-default.conf

# Configure Apache to allow .htaccess overrides specifically for /var/www/html
RUN cat <<'EOF' > /etc/apache2/conf-available/document-root-override.conf
<Directory /var/www/html>
    AllowOverride All
    Require all granted
</Directory>
EOF

RUN a2enconf document-root-override

# Create and configure directories for non-root Apache operation
RUN mkdir -p /var/run/apache2 /var/lock/apache2 /var/log/apache2 && \
    chown -R www-data:www-data /var/run/apache2 /var/lock/apache2 /var/log/apache2

# Set active working directory
WORKDIR /var/www/html

# Copy vendor dependencies from the composer builder stage
COPY --from=builder /app/vendor /var/www/html/vendor

# Copy application files (runtime-required content only via .dockerignore exclusions)
COPY . /var/www/html

# Grant www-data ownership to the entire application directory and writable data directory
RUN chown -R www-data:www-data /var/www/html/data

# Switch to non-root user
USER www-data

# Expose unprivileged web port
EXPOSE 80
EXPOSE 8080
