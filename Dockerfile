FROM php:8.2-fpm

# Copy composer.lock and composer.json into the working directory
COPY composer.lock composer.json /var/www/

# Set working directory
WORKDIR /var/www/

# Install dependencies for the operating system software
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    zip \
    vim \
    git \
    curl

# Install extensions for php
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install gd

# Install composer (php package manager)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy existing application directory contents to the working directory
COPY . /var/www

# Install application dependencies with composer
RUN composer install --no-interaction --no-dev --prefer-dist

# copy nginx config file
COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Run Laravel migration
RUN php artisan optimize
RUN php artisan cache:clear
RUN php artisan key:generate


# Expose port 80 and start php-fpm server (for FastCGI Process Manager)
EXPOSE 80
CMD ["php-fpm"]
