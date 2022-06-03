FROM php:8.0-fpm

RUN apt-get update && apt-get install -y libpq-dev linux-libc-dev libxml2-dev libpng-dev git zip unzip vim nano cron wget gnupg supervisor --no-install-recommends \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# algunas configuraciones para que funcione el contenedor
RUN docker-php-ext-install pdo pdo_mysql

# Set working directory
WORKDIR /var/www/html


# Copy existing application directory contents
COPY . /var/www/html

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory permissions
COPY --chown=www:www . /var/www/html

# Change current user to www
USER www
# RUN php artisan storage:link

# Expose port 9000 and start php-fpm server
EXPOSE 9000
EXPOSE 80
EXPOSE 2222
EXPOSE 90
CMD ["php-fpm"]
