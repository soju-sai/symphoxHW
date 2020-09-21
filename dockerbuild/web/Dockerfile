FROM php:7.3-apache

ENV APACHE_DOCUMENT_ROOT /var/www/html

RUN apt-get update \
  && apt-get install --no-install-recommends -y \
    apt-transport-https \
    build-essential \
    curl \
    debconf-utils \
    gcc \
    git \
    gnupg2 \
    libfreetype6-dev \
    libicu-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libpq-dev \
    libzip-dev \
    locales \
    unzip \
    vim \
    zlib1g-dev \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/* \
  && echo "en_US.UTF-8 UTF-8" >/etc/locale.gen \
  && locale-gen

RUN docker-php-ext-install -j$(nproc) zip gd mysqli pdo_mysql opcache intl

RUN mkdir -p ${APACHE_DOCUMENT_ROOT}
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN a2enmod rewrite
RUN a2enmod headers

COPY . ${APACHE_DOCUMENT_ROOT}

RUN curl -sS https://getcomposer.org/installer \
  | php \
  && mv composer.phar /usr/bin/composer \
  && composer config -g repos.packagist composer https://packagist.jp

WORKDIR ${APACHE_DOCUMENT_ROOT}

COPY composer.json ${APACHE_DOCUMENT_ROOT}

RUN composer install

ENV COMPOSER_ALLOW_SUPERUSER 1

COPY php.ini /usr/local/etc/php/