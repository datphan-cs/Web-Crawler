FROM php:8.1-apache
RUN apt-get update && apt-get install -y \
        libzip-dev \
        zip \
    && docker-php-ext-install zip

# Configuring apache
COPY public/000-default.conf /etc/apache2/sites-available

# Install composer
COPY --from=composer/composer:latest-bin /composer /usr/bin/composer


# Install dependencies
WORKDIR /var/www/html
COPY composer.lock composer.json ./

# Install dependencies
RUN composer install

# Copy the source directory
COPY src/ ./src