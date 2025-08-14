# Use the official FrankenPHP image with PHP 8.3 Alpine
FROM dunglas/frankenphp:1.3-php8.3-alpine

# Install system dependencies and build tools
RUN set -eux; \
    apk add --no-cache \
        # Core utilities
        curl \
        git \
        unzip \
        tini \
        # Image processing libraries
        libzip-dev \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        libwebp-dev \
        # Internationalization
        icu-dev \
        # Cryptography
        libsodium \
        libsodium-dev \
        # XML processing
        libxml2-dev \
        oniguruma-dev \
        # Database drivers
        postgresql-dev \
        sqlite-dev \
        # System headers
        linux-headers \
        # Build dependencies for PECL (temporary)
        autoconf \
        gcc \
        g++ \
        make \
        pkgconfig \
    ;

# Install Redis extension via PECL
RUN set -eux; \
    pecl install redis && \
    docker-php-ext-enable redis && \
    php -m | grep redis

# Clean up build dependencies to keep image minimal
RUN apk del autoconf gcc g++ make pkgconfig

# Configure and install other PHP extensions
RUN docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
        --with-webp \
    && docker-php-ext-install -j$(nproc) \
        bcmath \
        exif \
        gd \
        intl \
        opcache \
        pcntl \
        pdo_mysql \
        pdo_pgsql \
        pdo_sqlite \
        sodium \
        zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy application source code
COPY src/ /app/

# Create Laravel directories and set permissions
RUN mkdir -p /app/storage/framework/{sessions,views,cache} \
    && chown -R www-data:www-data /app/storage /app/bootstrap/cache \
    && chmod -R 775 /app/storage /app/bootstrap/cache

# Copy custom PHP configuration
COPY .docker/frankenphp/php.ini /usr/local/etc/php/conf.d/99-custom.ini

# Install PHP dependencies (uncomment when ready)
# RUN composer install --optimize-autoloader --no-interaction --no-progress

# Copy Caddy configuration (if using traditional FrankenPHP server)
# COPY .docker/frankenphp/Caddyfile /etc/caddy/Caddyfile

# Expose HTTP port
EXPOSE 80

# Use tini as init system for proper signal handling
ENTRYPOINT ["/sbin/tini", "--"]

# Health check endpoint
HEALTHCHECK --interval=30s --timeout=10s --start-period=40s --retries=3 \
    CMD curl -f http://localhost/up || exit 1

# Start Laravel Octane with FrankenPHP
CMD [ \
    "frankenphp", "php-cli", \
    "artisan", "octane:frankenphp", \
    "--host=0.0.0.0", \
    "--port=80", \
    "--workers=auto", \
    "--max-requests=1000", \
    "--watch" \
]

# Alternative: Traditional FrankenPHP server mode
# CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]