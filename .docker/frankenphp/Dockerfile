# Use the official FrankenPHP image with PHP 8.3 Alpine
FROM dunglas/frankenphp:1.3-php8.3-alpine

# Install system dependencies
RUN set -eux; \
    apk add --no-cache \
        curl \
        git \
        unzip \
        libzip-dev \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        libwebp-dev \
        icu-dev \
        libsodium \
        libsodium-dev \
        libxml2-dev \
        oniguruma-dev \
        postgresql-dev \
        sqlite-dev \
        linux-headers \
        tini \
    ;

# Install and configure PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
        bcmath \
        exif \
        gd \
        intl \
        pdo_mysql \
        pdo_pgsql \
        pdo_sqlite \
        sodium \
        zip \
        opcache \
        pcntl \
    ;

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy existing application directory contents
COPY src/ /app/

# Set permissions and create necessary directories
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache && \
    chmod -R 775 /app/storage /app/bootstrap/cache && \
    mkdir -p /app/storage/framework/{sessions,views,cache}
    # Install dependencies (keep dev for now)
#    composer install --optimize-autoloader --no-interaction --no-progress

# Copy custom PHP configuration
COPY .docker/frankenphp/php.ini /usr/local/etc/php/conf.d/99-custom.ini

# Copy Caddy configuration
#COPY .docker/frankenphp/Caddyfile /etc/caddy/Caddyfile

# Expose port 80 for FrankenPHP
EXPOSE 80

# Health check
#HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
#    CMD curl -f http://localhost/health || exit 1

ENTRYPOINT ["/sbin/tini", "--"]

# Start command for FrankenPHP
#CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
CMD ["frankenphp", "php-cli", "artisan", "octane:frankenphp", "--host=0.0.0.0", "--port=80", "--workers=4", "--max-requests=1000", "--log-level"]