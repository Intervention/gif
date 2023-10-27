FROM php:8-cli

RUN apt update \
        && apt install -y \
            libpng-dev \
            libmagickwand-dev \
            git \
            libzip-dev \
            zip \
        && docker-php-ext-configure gd --with-freetype --with-jpeg \
        && docker-php-ext-install \
            gd \
        && apt-get clean

# install composer
#
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

