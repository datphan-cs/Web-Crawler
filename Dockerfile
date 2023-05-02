FROM php:8.1-apache
RUN apt-get update && apt-get install -y \
        libzip-dev \
        zip \
    && docker-php-ext-install zip
    
# Install composer
COPY --from=composer/composer:latest-bin /composer /usr/bin/composer

# Install dependencies
WORKDIR /var/www/html
COPY composer.lock composer.json ./

# Install dependencies
RUN composer instal

COPY src/* ./