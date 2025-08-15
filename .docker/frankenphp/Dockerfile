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

# Install Composer with parallel downloads enabled
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_PROCESS_TIMEOUT=300

WORKDIR /app

# Copy composer files first for optimal layer caching
COPY src/composer.json src/composer.lock ./

# Install dependencies with development optimizations
#RUN set -eux; \
#    # Enable parallel downloads for faster installs
#    composer config --global process-timeout 300 && \
#    composer config --global repos.packagist composer https://packagist.org && \
#    # Install with optimizations for dev speed
#    composer install \
#        --prefer-dist \
#        --no-interaction \
#        --no-progress \
#        --optimize-autoloader \
#        --apcu-autoloader \
#    && \
#    # Don't clear cache in dev for faster subsequent runs
#    echo "Composer installation complete"

# Copy application code (use .dockerignore for efficiency)
COPY src/ ./

# Development-specific PHP configuration
RUN set -eux; \
    # Create Laravel directories efficiently
    mkdir -p \
        storage/framework/{sessions,views,cache} \
        storage/logs \
        bootstrap/cache \
        public/storage \
    && \
    # Set permissions once
    chown -R www-data:www-data storage bootstrap/cache public/storage && \
    chmod -R 775 storage bootstrap/cache public/storage && \
    # Link storage for development
    php artisan storage:link || true

# Expose port
EXPOSE 80

# Health check optimized for development
HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=2 \
    CMD curl -f http://localhost/up 2>/dev/null || curl -f http://localhost/ || exit 1

# Use tini for proper signal handling
ENTRYPOINT ["/sbin/tini", "--"]

# Development command optimized for fast restarts and file watching
CMD [ \
    "frankenphp", "php-cli", \
    "artisan", "octane:frankenphp", \
    "--host=0.0.0.0", \
    "--port=80", \
    "--workers=4", \
    "--max-requests=1000", \
    "--watch" \
]