FROM php:8.3-cli-alpine

RUN apk add --no-cache \
        libpng-dev \
        git \
        zip \
    && docker-php-ext-configure gd \
    && docker-php-ext-install \
        gd

# install composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# setup entrypoint
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
