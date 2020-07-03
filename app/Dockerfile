FROM php:7.3.19-fpm-alpine

# install pdo, etc...
RUN apk update \
  && apk add --no-cache \
  freetype-dev libjpeg-turbo-dev libpng-dev libmcrypt-dev \
  git \
  vim \
  unzip \
  tzdata \
  libmcrypt-dev \
  libltdl \
  curl-dev \
  libxml2-dev \
  zlib-dev \
  libzip-dev \
  pcre-dev \
  g++ \
  make \
  && docker-php-ext-install pdo_mysql mysqli mbstring gd iconv zip \
  && cp /usr/share/zoneinfo/Asia/Tokyo /etc/localtime \
  && apk del tzdata \
  && rm -rf /var/cache/apk/*

# install xdebug
RUN apk add autoconf
RUN apk add gcc g++ make
RUN pecl install xdebug \
  && docker-php-ext-enable xdebug

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# install laravel
# @todo うまくコマンドとしてインストールできなかった
# RUN composer global require "laravel/installer"
