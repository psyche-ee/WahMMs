FROM php:8.2-cli

# Install system dependencies: git, zip, unzip, and PostgreSQL dev libraries
RUN apt-get update && \
    apt-get install -y git zip unzip libpq-dev && \
    rm -rf /var/lib/apt/lists/*

# Install pdo_pgsql extension
RUN docker-php-ext-install pdo_pgsql

# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Create a directory for PHP sessions and set permissions
RUN mkdir -p /var/lib/php/sessions && chmod 777 /var/lib/php/sessions

# Set PHP to use this directory for sessions
ENV PHP_SESSION_SAVE_PATH=/var/lib/php/sessions

# Copy project files
COPY . .

# Install dependencies if composer.json exists
RUN if [ -f composer.json ]; then composer install; fi

# Expose port
EXPOSE 10000


CMD ["php", "-d", "session.save_path=/var/lib/php/sessions", "-S", "0.0.0.0:${PORT}", "index.php"]