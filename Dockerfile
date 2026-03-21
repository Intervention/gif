FROM php:8.3-cli

RUN apt update \
        && apt install -y \
            libpng-dev \
            git \
            zip \
        && pecl install xdebug \
        && docker-php-ext-configure gd \
        && docker-php-ext-enable \
            xdebug \
        && docker-php-ext-install \
            gd \
        && apt-get clean

# install composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# setup entrypoint
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
