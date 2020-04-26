#
# Use this dockerfile to run CIS api.
#
# Start the server using docker-compose:
#
#   docker-compose build
#   docker-compose up
#
# You can install dependencies via the container:
#
#   docker-compose run cis_api composer install
#
# You can manipulate dev mode from the container:
#
#   docker-compose run cis_api composer development-enable
#   docker-compose run cis_api composer development-disable
#   docker-compose run cis_api composer development-status
#
# OR use plain old docker
#
#   docker build -f Dockerfile-dev -t cis_api .
#   docker run -it -p "8080:80" -v $PWD:/var/www cis_api
#
FROM php:7.0-cli

RUN apt-get update \
    && apt-get install -y git zlib1g-dev \
        libzip-dev \
        libmemcached-dev \
#       libssl-dev \
    && apt-get install unzip \
    && git clone https://github.com/php-memcached-dev/php-memcached /usr/src/php/ext/memcached \
    && cd /usr/src/php/ext/memcached && git checkout -b php7 origin/php7 \
    && docker-php-ext-configure memcached \
    && docker-php-ext-install memcached # && pecl install mongodb \
#   && docker-php-ext-enable mongodb \
    && docker-php-ext-install zip \
    && curl -sS https://getcomposer.org/installer \
        | php -- --install-dir=/usr/local/bin --filename=composer

#WORKDIR /home/code
