# Use the official PHP image as base
FROM php:8.3-apache

# Install required PHP extensions
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libonig-dev \
    libzip-dev \
    libssl-dev \
    mariadb-client \
    netcat-openbsd \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql bcmath \
    && a2enmod rewrite \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /var/www/html

# Install composer (assumes you have composer.json in the same directory)
#COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
#RUN composer install --no-dev

# Copy application files
COPY . .

# Copy Apache vhost file
COPY ./vhost.conf /etc/apache2/sites-available/000-default.conf

# Update permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html

# Copy entrypoint script
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Open port 80 (required for HTTP)
EXPOSE 80

# Use the entrypoint script
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
