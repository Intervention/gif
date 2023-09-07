# ##############################################
# stage: composer
# ##############################################
FROM composer:2 as composer

# install composer dependencies
COPY composer.json composer.json
# COPY composer.lock composer.lock
RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist

# ##############################################
# stage: testing
# ##############################################
FROM php:8-cli

# install dependencies
RUN apt update && apt install -y \
            libpng-dev \
            libmagickwand-dev \
        && docker-php-ext-configure gd --with-freetype --with-jpeg \
        && docker-php-ext-install \
            gd \
        && apt-get clean

# copy application
COPY . /app
COPY --from=composer /app/vendor/ /app/vendor/

# run tests
WORKDIR /app
CMD ./vendor/bin/phpunit -vvv
