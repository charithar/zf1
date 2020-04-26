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
    && apt-get install -y --no-install-recommends \
        git \
        zlib1g-dev \
        libzip-dev \
        libicu-dev \
        libmemcached-dev \
        libz-dev \
        libpq-dev \
        libjpeg-dev \
        libpng-dev \
        libfreetype6-dev \
        libssl-dev \
        libmcrypt-dev \
        libxml2-dev \
        libbz2-dev \
        libjpeg62-turbo-dev \
        libssl-dev \
        curl \
        unzip \
#    && git checkout -b php7 origin/php7 \
    && docker-php-ext-configure bcmath --enable-bcmath \
    && docker-php-ext-configure pcntl --enable-pcntl \
    && docker-php-ext-configure pdo_mysql --with-pdo-mysql \
    && docker-php-ext-configure pdo_pgsql --with-pgsql \
    && docker-php-ext-configure mbstring --enable-mbstring \
    && docker-php-ext-configure soap --enable-soap \
    && docker-php-ext-configure intl \
    && docker-php-ext-install \
        bcmath \
        intl \
        mbstring \
        mcrypt \
        mysqli \
        pcntl \
        pdo_mysql \
        pdo_pgsql \
        soap \
        sockets \
        zip

RUN docker-php-ext-configure gd \
        --enable-gd-native-ttf \
        --with-jpeg-dir=/usr/lib \
        --with-freetype-dir=/usr/include/freetype2 \
    && docker-php-ext-install gd

RUN docker-php-ext-install opcache \
  && docker-php-ext-enable opcache

#RUN pecl install mongodb \
#    && docker-php-ext-enable mongodb

RUN git clone https://github.com/php-memcached-dev/php-memcached /usr/src/php/ext/memcached \
  && cd /usr/src/php/ext/memcached \
  && docker-php-ext-configure memcached \
  && docker-php-ext-install memcached

RUN curl -sS https://getcomposer.org/installer \
        | php -- --install-dir=/usr/local/bin --filename=composer

#COPY ./opcache.ini /usr/local/etc/php/conf.d/opcache.ini
#COPY ./timezone.ini /usr/local/etc/php/conf.d/timezone.ini
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY tests/php7_config.ini $PHP_INI_DIR/conf.d/

WORKDIR /app
