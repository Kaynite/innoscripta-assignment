# Use the official PHP image as a base
FROM php:8.3-fpm-alpine

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apk --no-cache add \
    build-base \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libxpm-dev \
    freetype-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    bash \
    fcgi \
    libmcrypt-dev \
    oniguruma-dev

# Clear cache
RUN rm -rf /var/cache/apk/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && pecl install redis \
    && docker-php-ext-install gd pdo pdo_mysql mbstring zip exif pcntl bcmath opcache \
    && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing application directory contents
COPY . /var/www/html

RUN chown -R www-data:www-data .

RUN composer install --no-interaction --optimize-autoloader

RUN chmod +x ./.docker/php/entrypoint.sh
RUN chmod +x ./.docker/php/wait-for-it.sh

ENTRYPOINT ["bash", "/var/www/html/.docker/php/entrypoint.sh"]

USER www-data

CMD ["php-fpm"]
