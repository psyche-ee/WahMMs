FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && \
    apt-get install -y git zip unzip libpq-dev && \
    rm -rf /var/lib/apt/lists/*

# Install pdo_pgsql extension
RUN docker-php-ext-install pdo_pgsql

# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Allow .htaccess overrides
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Copy project files
COPY . .

# Install dependencies if composer.json exists
RUN if [ -f composer.json ]; then composer install; fi

# Set permissions for sessions
RUN mkdir -p /var/lib/php/sessions && chmod 777 /var/lib/php/sessions

# Expose port 80 (Apache default)
EXPOSE 80

# Apache will start automatically