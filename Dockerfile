#
# Use this dockerfile to run Zf1 tests.
#
# Start the server using docker-compose:
#
#   docker-compose build
#
# You can install dependencies via the container:
#
#   docker-compose run zf1_test composer install
#
# You can run tests using the container:
#
#   docker-compose run zf1_test ../bin/phpunit <path to test file>
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

#RUN pecl install igbinary \
#  && docker-php-ext-enable igbinary

RUN git clone https://github.com/websupport-sk/pecl-memcache /usr/src/php/ext/memcache \
  && cd /usr/src/php/ext/memcache \
  && docker-php-ext-configure memcache \
  && docker-php-ext-install memcache \
  && docker-php-ext-enable memcache

RUN git clone https://github.com/php-memcached-dev/php-memcached /usr/src/php/ext/memcached \
  && cd /usr/src/php/ext/memcached \
#  && docker-php-ext-configure memcached --enable-memcached-igbinary \
  && docker-php-ext-configure memcached \
  && docker-php-ext-install memcached \
  && docker-php-ext-enable memcached

RUN curl -sS https://getcomposer.org/installer \
        | php -- --install-dir=/usr/local/bin --filename=composer

#COPY ./opcache.ini /usr/local/etc/php/conf.d/opcache.ini
#COPY ./timezone.ini /usr/local/etc/php/conf.d/timezone.ini
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY tests/php7_config.ini $PHP_INI_DIR/conf.d/

#COPY . /app

WORKDIR /app
