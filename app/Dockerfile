FROM php:7.4.7-fpm-alpine
# timezone
ARG TZ=Asia/Tokyo
RUN apk upgrade --update
# add modules
RUN docker-php-ext-install pdo_mysql
