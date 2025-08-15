# Use official FrankenPHP image with PHP 8.3 Alpine
FROM dunglas/frankenphp:1.3-php8.3-alpine

# Define build arguments and environment variables
ARG UID=1000
ARG GID=1000
ENV APP_USER=appuser \
    COMPOSER_ALLOW_SUPERUSER=0 \
    COMPOSER_PROCESS_TIMEOUT=300 \
    COMPOSER_HOME=/tmp/composer

# Set working directory
WORKDIR /app

# Install system dependencies
RUN apk add --no-cache \
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
    # Build dependencies (temporary)
    autoconf \
    gcc \
    g++ \
    make \
    pkgconfig \
    linux-headers

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp && \
    docker-php-ext-install -j$(nproc) \
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
        zip && \
    pecl install \
        redis-6.0.2 && \
    docker-php-ext-enable \
        redis

# Clean up build dependencies and caches
RUN apk del --no-cache autoconf gcc g++ make pkgconfig linux-headers && \
    pecl clear-cache && \
    rm -rf \
        /tmp/pear \
        ~/.pearrc \
        /var/cache/apk/* \
        /var/tmp/* \
        /tmp/* \
        ~/.cache \
        /usr/local/lib/php/*.a \
        /usr/local/lib/php/*.la

# Install Composer
COPY --from=composer:2 --chown=${UID}:${GID} /usr/bin/composer /usr/bin/composer

# Create application user and group
RUN addgroup -g ${GID} ${APP_USER} && \
    adduser -u ${UID} -G ${APP_USER} -D -s /bin/sh ${APP_USER} && \
    adduser ${APP_USER} www-data && \
    mkdir -p /tmp/composer && \
    chown ${UID}:${GID} /tmp/composer

# Copy and install application dependencies
#COPY --chown=${UID}:${GID} src/composer.json src/composer.lock ./
#RUN set -eux; \
#    composer install \
#        --no-dev \
#        --no-scripts \
#        --no-progress \
#        --optimize-autoloader \
#        --classmap-authoritative \
#        --apcu-autoloader; \
#    composer clear-cache; \
#    rm -rf /tmp/* /tmp/composer/* /home/${APP_USER}/.composer/cache/*

# Copy application code
COPY --chown=${UID}:${GID} src/ ./

# Set up application directories and permissions
RUN mkdir -p \
        storage/framework/cache \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache && \
    chown -R ${UID}:${GID} storage bootstrap/cache /home/${APP_USER} && \
    chmod -R 775 storage bootstrap/cache && \
    chmod +x artisan

# Switch to non-root user
USER ${APP_USER}

# Expose port
EXPOSE 80

# Configure healthcheck
HEALTHCHECK --interval=30s --timeout=5s --start-period=15s --retries=3 \
    CMD php artisan up > /dev/null 2>&1 || exit 1

# Define entrypoint and command
ENTRYPOINT ["/sbin/tini", "--"]
CMD ["frankenphp", "php-cli", "artisan", "octane:frankenphp", \
     "--host=0.0.0.0", \
     "--port=80", \
     "--workers=auto", \
     "--max-requests=1000", \
     "--watch"]