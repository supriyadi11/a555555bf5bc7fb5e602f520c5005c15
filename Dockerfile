# Use an official PHP Apache runtime
FROM php:8.1-apache
# Enable Apache modules
RUN a2enmod rewrite
# Install PostgreSQL client and its PHP extensions
RUN apt-get update \
    && apt-get install -y libpq-dev \
    libicu-dev \
    libmariadb-dev \
    unzip zip \
    zlib1g-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    && docker-php-ext-install pdo pdo_pgsql
#composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
# Set the working directory to /var/www/html
WORKDIR /var/www/html
# Copy the PHP code file in /app into the container at /var/www/html
COPY ../src .