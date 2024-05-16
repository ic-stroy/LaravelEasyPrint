# Use the official PHP image as base
FROM php:7.4-fpm

# Set working directory
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Copy composer.lock and composer.json
COPY composer.lock composer.json /var/www/html/

# Install composer dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-scripts --no-autoloader

# Copy existing application directory contents
COPY . /var/www/html

# Generate autoload files
RUN composer dump-autoload

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
