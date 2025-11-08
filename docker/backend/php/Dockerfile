FROM php:8.4.14-fpm-alpine AS base

# Configuration of the working direction
WORKDIR /var/www/

# It exposes the PHP-FPM and Supervisor ports.
EXPOSE 9000 9001

# Install PHP extensions
RUN apk add --no-cache --virtual .build-deps \
    autoconf \
    g++ \
    gcc \
    icu-dev \
    linux-headers \
    make \
    && docker-php-ext-install intl opcache \
    && docker-php-ext-configure intl --enable-intl \
    && pecl install igbinary xdebug \
    && docker-php-ext-enable igbinary xdebug \
    && pecl install --configureoptions 'enable-apcu-igbinary="yes"' apcu \
    && docker-php-ext-enable apcu \
    && pecl clear-cache \
    && rm -rf /tmp/*

FROM composer:2.8.12 AS build

FROM base AS final

# Add the /var/www/bin folder to the environment variables.
ENV PATH="${PATH}:/var/www/bin"

# Copy the composer file into the final image.
COPY --from=build /usr/bin/composer /usr/bin/composer

# Copies configuration files and startup scripts.
COPY docker/backend/php/rootfilesystem/ /

# Install dependencies
RUN apk add --no-cache bash coreutils supervisor

# Sets stop signal
STOPSIGNAL SIGINT

# Set execute permission for entrypoint script and create log directories
RUN chmod +x /entrypoint.sh && \
    mkdir -p /var/log/php /var/log/supervisor && chmod -R 777 /var/log

# Define input script
ENTRYPOINT ["/entrypoint.sh"]
