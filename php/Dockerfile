# php-rasp-lab/php/Dockerfile

FROM php:8.1-fpm

# 安裝編譯 PHP 擴充套件所需的工具
RUN apt-get update && apt-get install -y \
    build-essential \
    autoconf iputils-ping python3


COPY ./rasp_extension /usr/src/php/ext/rasp

# 執行 PHP 官方提供的 helper script 來編譯並啟用 RASP 擴充套件
# 這會自動執行 phpize, ./configure, make, make install
RUN docker-php-ext-install rasp

RUN rm /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini && \
    rm /usr/local/etc/php/conf.d/opcache-recommended.ini || true

