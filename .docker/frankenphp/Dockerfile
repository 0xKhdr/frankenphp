# Use a pinned FrankenPHP Alpine image
FROM dunglas/frankenphp:1.3-php8.3-alpine

ARG UID=1000
ARG GID=1000
ARG WORKER_COUNT=4

ENV COMPOSER_ALLOW_SUPERUSER=0

# Install runtime dependencies
RUN set -eux; \
    apk add --no-cache \
        curl \
        git \
        icu-data-full \
        icu-libs \
        libpng \
        libjpeg-turbo \
        freetype \
        libzip \
        oniguruma \
        tini \
        shadow \
        su-exec \
        mysql-client

# Install build dependencies
RUN set -eux; \
    apk add --no-cache --virtual .build-deps \
        ${PHPIZE_DEPS} \
        build-base \
        autoconf \
        linux-headers \
        freetype-dev \
        libjpeg-turbo-dev \
        libpng-dev \
        libzip-dev \
        icu-dev \
        oniguruma-dev \
        openssl-dev

# Install PHP core extensions (non-PECL)
RUN set -eux; \
    docker-php-ext-configure gd --with-freetype --with-jpeg; \
    docker-php-ext-install -j$(nproc) \
        exif \
        gd \
        intl \
        mbstring \
        pcntl \
        pdo_mysql \
        bcmath \
        zip

# Install PECL extensions
RUN set -eux; \
    pecl install \
        redis; \
    docker-php-ext-enable redis

# Clean up build dependencies and caches
RUN set -eux; \
    apk del .build-deps; \
    rm -rf /tmp/* /var/cache/apk/*

# Create application user/group
RUN set -eux; \
    addgroup -g ${GID} -S app-group; \
    adduser -u ${UID} -S app-user -G app-group -H -s /bin/false; \
    mkdir -p /var/www/html; \
    chown -R app-user:app-group /var/www/html; \
    chmod 750 /var/www/html

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer; \
    chown app-user:app-group /usr/local/bin/composer; \
    chmod +x /usr/local/bin/composer

# Switch to app-user
USER app-user
WORKDIR /var/www/html

# Copy Composer files for caching
COPY --chown=app-user:app-group ./src/composer.* ./

# Install dependencies
RUN composer install \
    --optimize-autoloader \
    --no-interaction \
    --prefer-dist \
    --no-scripts \
    --classmap-authoritative

# Copy application code
COPY --chown=app-user:app-group ./src ./

# Copy PHP config (as root, so switch temporarily)
USER root
COPY --chown=root:root ./.docker/frankenphp/php.ini /usr/local/etc/php/conf.d/99-custom.ini
USER app-user

# Optimize Laravel
RUN composer run-script post-install-cmd && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan event:cache

# Use tini as entrypoint
ENTRYPOINT ["/sbin/tini", "--"]

# Start FrankenPHP
CMD ["frankenphp", "run", \
     "--host=0.0.0.0", \
     "--port=80", \
     "--workers=${WORKER_COUNT}", \
     "--max-requests=10000", \
     "--log-level=info"]